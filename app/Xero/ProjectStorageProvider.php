<?php

namespace App\Xero;

use Illuminate\Session\Store;
use Webfox\Xero\OauthCredentialManager;
use App\Models\Project;
use League\OAuth2\Client\Token\AccessTokenInterface;
use App\Xero\OauthTwoProvider;
use App\Models\ProjectApiSystem;
use Illuminate\Support\Facades\Crypt;
/**
 * Check FileStore.php file from reference
 * XeroServiceProvider.php for more detail and Xero.php (config file)
 */

class ProjectStorageProvider implements OauthCredentialManager
{

    /** @var OauthTwoProvider  */
    protected $oauthProvider;

    /** @var Store */
    protected $session;

    /** @var project */
    protected $project;


    public function __construct(Project $project, Store $session, OauthTwoProvider $oauthProvider)
    {
        $this->project          =  $project->where('Project_ID', \Session::get('project_id'))->first();
        if(empty($this->project->projectApiSystem))
        {
            throw new \Exception("Please provide API access keys!");
        }
        $this->oauthProvider    =  $oauthProvider;
        $this->session          =  $session;

        $oauthProvider->setClientID($this->project->projectApiSystem->api_key);
        $oauthProvider->setClientSecret($this->project->projectApiSystem->api_secret);
    }

    public function getAccessToken(): string
    {
        return $this->data('token');
    }

    public function getRefreshToken(): string
    {
        return $this->data('refresh_token');
    }

    public function getTenantId(int $tenant = 0): string
    {
        if(!isset($this->data('tenants')[$tenant]))
        {
            throw new \Exception("No such tenant exists");
        }
        return $this->data('tenants')[$tenant]['Id'];
    }

    public function getProjectTenant()
    {
        return $this->project->projectApiSystem->tanent_id;
    }

    public function getTenants(): ?array
    {
        return $this->data('tenants');
    } 

    public function getExpires(): int
    {
        return $this->data('expires');
    }

    public function getState(): string
    {
        return $this->session->get('xero_oauth2_state');
    }

    public function getAuthorizationUrl(): string
    {
        $redirectUrl = $this->oauthProvider->getAuthorizationUrl(['scope' => config('xero.oauth.scopes')]);
        $this->session->put('xero_oauth2_state', $this->oauthProvider->getState());

        return $redirectUrl;
    }

    public function getData(): array
    {
        return $this->data();
    }

    public function exists(): bool
    {
        return !empty($this->project->projectApiSystem->access_details);
    }

    public function isExpired(): bool
    {
        return time() >= $this->data('expires');
    }

    public function refresh(): void
    {
        $newAccessToken = $this->oauthProvider->getAccessToken('refresh_token', [
            'refresh_token' => $this->getRefreshToken(),
        ]);

        $this->store($newAccessToken);
    }

    /**
     * Store the details of the access token
     *
     * Should store array [
     *   'token'         => $token->getToken(),
     *   'refresh_token' => $token->getRefreshToken(),
     *   'id_token'      => $token->getValues()['id_token'],
     *   'expires'       => $token->getExpires(),
     *   'tenants'       => $tenants ?? $this->getTenants(),
     * ]
     *
     * @param AccessTokenInterface $token
     * @param Array|null          $tenants
     */
    public function store(AccessTokenInterface $token, array $tenants = null): void
    {
        $accessDetails = json_encode([
            'token'         => $token->getToken(),
            'refresh_token' => $token->getRefreshToken(),
            'id_token'      => $token->getValues()['id_token'],
            'expires'       => $token->getExpires(),
            'tenants'     => $tenants ?? $this->getTenants()
        ]);

        $token = ProjectApiSystem::where(['id' => $this->project->projectApiSystem->id, 'api_key' => base64_encode($this->project->projectApiSystem->api_key)])
        ->update([
            'access_details'=>  $accessDetails
        ]);

        
        if ($token === false) {
            throw new \Exception("Failed to write to DB");
        }
        
    }

    public function delete(): void
    {
        // POST https://identity.xero.com/connect/revocation
        // revoke token
        ProjectApiSystem::where(['id' => $this->project->projectApiSystem->id])->update([
            'access_details' => ''
        ]);
    }

    public function getUser(): ?array
    {

        try {
            $jwt = new \XeroAPI\XeroPHP\JWTClaims();
            $jwt->setTokenId($this->data('id_token'));
            $decodedToken = $jwt->decode();

            return [
                'given_name'  => $decodedToken->getGivenName(),
                'family_name' => $decodedToken->getFamilyName(),
                'email'       => $decodedToken->getEmail(),
                'user_id'     => $decodedToken->getXeroUserId(),
                'username'    => $decodedToken->getPreferredUsername(),
                'session_id'  => $decodedToken->getGlobalSessionId()
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function data($key = null)
    {
        if (!$this->exists()) {
            throw new \Exception('Xero oauth credentials are missing');
        }

        // have to query again to find out the toke stored in DB
        $dataDetail = Project::where('Project_ID', \Session::get('project_id'))->first();

        $cacheData = json_decode($dataDetail->projectApiSystem->access_details, true);

        return empty($key) ? $cacheData : ($cacheData[$key] ?? null);
    }
}