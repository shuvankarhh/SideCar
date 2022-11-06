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

            Log::info(json_encode($request));

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

    // project is tenants one Xero
    // Multi projects can have one APi key access
    // client and project id in api to and client_id
    public function testMethod(OauthCredentialManager $xeroCredentials, IdentityApi $identity)
    {
        //$invoiceFileImporter = new InvoiceFileImport();
        //Excel::import($invoiceFileImporter, storage_path('app/ACI-AP_Add.xlsx'));
        if ($xeroCredentials->exists()) {
            
            $identity->getConfig()->setAccessToken((string)$xeroCredentials->getAccessToken());
            dd($identity->getConnections());

            $tenantID = $xeroCredentials->getTenants();
            var_dump($tenantID); // f0edac46-76ca-48ce-b479-442cff00012f
            //$xero = resolve(\XeroAPI\XeroPHP\Api\AccountingApi::class);
            // line 58337 and 58101
           // $xero->updateOrCreateInvoices($tenantID, new Invoices($inovices));
        }
    }

}
