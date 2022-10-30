<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Webfox\Xero\Oauth2Provider;
use App\Xero\OauthTwoProvider;
use XeroAPI\XeroPHP\Api\IdentityApi;
use Illuminate\Support\Facades\Event;
use Webfox\Xero\Events\XeroAuthorized;
use Webfox\Xero\OauthCredentialManager;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ApiAccessController extends Controller
{

    public function index(Request $request, OauthCredentialManager $oauth, IdentityApi $identity, OauthTwoProvider $provider)
    {
        try {
            $this->validate($request, [
                'code'  => ['required', 'string'],
                'state' => ['required', 'string', "in:{$oauth->getState()}"]
            ]);

            $provider->setClientID($this->getProject()->projectApiSystem->api_key);
            $provider->setClientSecret($this->getProject()->projectApiSystem->api_secret);
            

            $accessToken = $provider->getAccessToken('authorization_code', $request->only('code'));
            $identity->getConfig()->setAccessToken((string)$accessToken->getToken());

            //Iterate tenants
            $tenants = array();
            foreach($identity->getConnections() as $c) {
                $tenants[] = [
                    "Id" => $c->getTenantId(),
                    "Name"=> $c->getTenantName()
                ];
            }

            //Store Token and Tenants
            $oauth->store($accessToken, $tenants);
            //Event::dispatch(new XeroAuthorized($oauth->getData()));

            return $this->onSuccess();
        } catch (\throwable $e) {
            return $this->onFailure($e);
        }
    }


    public function onSuccess()
    {
        return Redirect::route(config('xero.oauth.redirect_on_success'));
    }

    public function onFailure(\throwable $e)
    {
        throw $e;
    }

    // https://sidecar.local/call/1/back
    // https://sidecar.local/xero/auth/callback
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function callBackRedirect($id = false, Request $request)
    {
        Log::info(json_encode($id));
        Log::info(json_encode($request));
    }

}
