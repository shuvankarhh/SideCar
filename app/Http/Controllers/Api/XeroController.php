<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Webfox\Xero\OauthCredentialManager;
use XeroAPI\XeroPHP\Api\IdentityApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XeroController extends Controller
{

    public function index(Request $request, OauthCredentialManager $xeroCredentials)
    {
        try {
            
            // Check if we've got any stored credentials
            if ($xeroCredentials->exists() && !$xeroCredentials->isExpired()) {
                /* 
                 * We have stored credentials so we can resolve the AccountingApi, 
                 * If we were sure we already had some stored credentials then we could just resolve this through the controller
                 * But since we use this route for the initial authentication we cannot be sure!
                 */
                $xero             = resolve(\XeroAPI\XeroPHP\Api\AccountingApi::class);
                $organisations = $xero->getOrganisations($xeroCredentials->getTenantId())->getOrganisations();

                $organisationName = $organisations[0]->getName();
                $user             = $xeroCredentials->getUser();
                $username         = "{$user['given_name']} {$user['family_name']} ({$user['username']})";

                // if I am connected to XERO redirect to the file import process
                if($xeroCredentials->exists()){
                    //return redirect()->route('upload');
                }
            }

        } catch (\throwable $e) {
            // This can happen if the credentials have been revoked or there is an error with the organisation (e.g. it's expired)
            $error = $e->getMessage();
        }

        Log::info(json_encode($request));

        return view('xero', [
            'connected'        => $xeroCredentials->exists(),
            'error'            => $error ?? null,
            'organisationName' => $organisationName ?? null,
            'username'         => $username ?? null,
            'organisations'    => $organisations ?? null
        ]);
    }

    // method to handle automatic get the access token
    // will return access token
    public function getAccessToken(){
        return redirect()->route('xero.auth.authorize');
    }

    public function revokeAccessToken(OauthCredentialManager $xeroCredentials, IdentityApi $identity)
    {
        $project = $this->getProject();

        $response = Http::withToken(base64_encode($project->projectApiSystem->api_key . ":" . $project->projectApiSystem->api_secret), 'Basic')
        ->asForm()
        ->post('https://identity.xero.com/connect/revocation',[
            'token' => $xeroCredentials->getRefreshToken()
        ]);
        $project->projectApiSystem->update(['access_details' => null]);

        return response()->json($response ?? []);
    }

    // registor form and password change
    // fileupload form error remove on reupload
    // Cron JOB to refresh token

    public function refreshToken(OauthCredentialManager $xeroCredentials)
    {
        $project = $this->getProject();

        $response = Http::withToken(base64_encode($project->projectApiSystem->api_key . ":" . $project->projectApiSystem->api_secret), 'Basic')
        //->withHeaders(['Content-Type'=> 'application/x-www-form-urlencoded'])
        ->asForm()
        ->post(config('xero.oauth.url_access_token'),[
            'grant_type'=>'refresh_token',
            'refresh_token' => $xeroCredentials->getRefreshToken()
        ]);

        return response()->json($response ?? []);
    }

}