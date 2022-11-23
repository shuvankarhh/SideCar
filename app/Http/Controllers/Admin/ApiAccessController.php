<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectApiSystem;
use Illuminate\Support\Facades\Log;
use App\Xero\OauthTwoProvider;
use XeroAPI\XeroPHP\Api\IdentityApi;
use Illuminate\Support\Facades\Event;
use Webfox\Xero\Events\XeroAuthorized;
use Webfox\Xero\OauthCredentialManager;
use Illuminate\Support\Facades\Redirect;

class ApiAccessController extends Controller
{

    // call back URL https://sidecar.local/xero/auth/callback
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

            Log::info(json_encode($accessToken));
            //Store Token and Tenants
            $oauth->store($accessToken, $tenants);

            // After auth store select tenant to store
            //Event::dispatch(new XeroAuthorized($oauth->getData()));

            return $this->onSuccess();
        } catch (\throwable $e) {
            return $this->onFailure($e);
        }
    }

    // we redirect to file upload page via xero config file
    public function onSuccess()
    {
        // if already have tenant for project
        if(empty($this->getProject()->projectApiSystem->tanent_id))
            return redirect()->route('confirmTenant');
        return Redirect::route(config('xero.oauth.redirect_on_success'));
    }

    public function onFailure(\throwable $e)
    {
        throw $e;
    }

    public function confirmTenant(OauthCredentialManager $xeroCredentials)
    {
        if ($xeroCredentials->exists()) {
            $tenants = $xeroCredentials->getTenants();
        }

        $key = array_search($this->getProject()->projectApiSystem->tanent_id, array_column($tenants, 'Id'));
        if($key != false){
            $selectedTenant = $tenants[$key];
        }

        return view('pages.confirmTenant',[
            'tenants' => $tenants,
            'selectTenant' => $selectedTenant ?? ''
        ]);
    }

    public function updateConfirmTenant(Request $request)
    {
        $request->validate([
            'tanent_id' => 'required',
        ]);

        $this->getProject()->projectApiSystem()->update([
            'tanent_id' => $request->get('tanent_id')
        ]);
        return redirect()->route('upload');
    }

    // project is tenants one Xero
    // Multi projects can have one APi key access
    // client and project id in api to and client_id
    public function testMethod(OauthCredentialManager $xeroCredentials, IdentityApi $identity)
    {
        //$invoiceFileImporter = new InvoiceFileImport();
        //Excel::import($invoiceFileImporter, storage_path('app/ACI-AP_Add.xlsx'));
        if ($xeroCredentials->exists())
        {    
            $identity->getConfig()->setAccessToken((string)$xeroCredentials->getAccessToken());
            dd($identity->getConnections());

            $tenantID = $xeroCredentials->getTenants();
            var_dump($tenantID); // f0edac46-76ca-48ce-b479-442cff00012f
        }
    }


    public function getCOA(OauthCredentialManager $xeroCredentials)
    {
        if ($xeroCredentials->exists()) {
            // Tenant ID is based on Project Orgination ID... we can allow for all the Orgination ... need to be
            $tenantID = $this->getProject()->projectApiSystem->tanent_id;
            $xero = resolve(\XeroAPI\XeroPHP\Api\AccountingApi::class);
            $data = $xero->getAccounts($tenantID);
            (new \App\Xero\StoreChartOfAccounts)->store($this->getProject()->projectApiSystem->id, $data);
        }
        return response()->json($data ?? []);
    }

    public function getTrackingCategories(OauthCredentialManager $xeroCredentials)
    {
        if ($xeroCredentials->exists()) {
            // Tenant ID is based on Project Orgination ID... we can allow for all the Orgination ... need to be
            //$tenantID = $xeroCredentials->getTenantId(1);
            $tenantID = $this->getProject()->projectApiSystem->tanent_id;
            $xero = resolve(\XeroAPI\XeroPHP\Api\AccountingApi::class);
            $data = $xero->getTrackingCategories($tenantID);
            (new \App\Xero\StoreTrackingCategories)->store($this->getProject()->projectApiSystem->id, $data);
        }
        return response()->json($data ?? []);
    }

    public function apiInfo()
    {
        return view('setup.apiSetup', []);
    }

    public function storeApiInfo(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'software' => 'required',
            'api_key' => 'required|min:10',
            'api_secret' => 'required|min:10'
        ]);

        ProjectApiSystem::create([
            'project_id' => $this->getProject()->Project_ID,
            'client_id' => $this->getClient()->Client_ID,
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'software' => $request->get('software'),
            'api_key' => $request->get('api_key'),
            'api_secret' => $request->get('api_secret')
        ]);

        return redirect()->route('upload');
    }

}
