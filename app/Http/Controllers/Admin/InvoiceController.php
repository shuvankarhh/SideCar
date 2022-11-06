<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Storage;
use App\Imports\InvoiceFileImport;
use Excel;

use XeroAPI\XeroPHP\Models\Accounting\Contact;
use XeroAPI\XeroPHP\Models\Accounting\LineItem;
use XeroAPI\XeroPHP\Models\Accounting\Invoice;
use XeroAPI\XeroPHP\Models\Accounting\Invoices;
use XeroAPI\XeroPHP\Models\Accounting\LineItemTracking;
use App\Services\FormatInvoiceData;
use Webfox\Xero\OauthCredentialManager;
use XeroAPI\XeroPHP\Api\IdentityApi;

class InvoiceController extends Controller
{

    public $formatInvoice;

    public function __construct(FormatInvoiceData $formatInvoice)
    {
        $this->formatInvoice = $formatInvoice;
    } 

    // Check if access key exists for the project and tenant
    // if not redirect to manage api access page
    // Also need to check if we have access to tenant
    public function index(Request $request, OauthCredentialManager $xeroCredentials, IdentityApi $identity)
    {
        $p = \App\Models\Project::where('Project_ID', \Session::get('project_id'))->first();
        if($p->projectApiSystem()->exists() == false)
        {   
            return abort(403, 'Please provide API keys for this project');
        }
        if($p->projectApiSystem->apiAccessToken()->exists() == false)
        {   
            return redirect()->route('xero.auth.success');
        }else{

            // check if we have access token have access to tenant
            // if not we need to get update access token with new tenant ID
            if ($xeroCredentials->exists()) {
                //$identity->getConfig()->setAccessToken((string)$xeroCredentials->getAccessToken());
                $tenants = $xeroCredentials->getTenants();
               // dd($identity->getConnections(), $tenants); // f0edac46-76ca-48ce-b479-442cff00012f
            }

        }
        return view('pages.upload');
    }

    // fileupload
    public function saveFile(Request $request)
    {
        // unique file validation needed
        $request->validate([
            'file' => ['required', 'mimes:xlx,xls,xlsx']
        ]);

        // same file name should give error
        if($request->file('file'))
        {
            $path = $request->file('file')->store('excel-files');

            $invoiceFileImporter = (new InvoiceFileImport())->fromFile($request->file->getClientOriginalName());
            Excel::import($invoiceFileImporter, storage_path('app/' . $path));
            // delete the file after import
            unlink(storage_path('app/' . $path));
        }
        // need to redirect to create invoices view
        return redirect()->route('importView');
        //return back()->with('fileUploaded', 'File Imported Successfully.');
    }

    public function importView()
    {
        $this->formatInvoice->setProject($this->getProject());
        return view('pages.invoiceView',[
            'data' => $this->formatInvoice->rawData()
        ]);
    }

    // each project have differen Xero instance and redirect URL need to include project ID so we can store the access token base on project
    public function createInvoice(OauthCredentialManager $xeroCredentials)
    {
        $this->formatInvoice->setProject($this->getProject());
        $formatInvoicedata = $this->formatInvoice->formatApiData();

        foreach ($formatInvoicedata as $key => $data) {
            //create contact
            $xcontact = new Contact();
            $xcontact->setName($data['name']);
            //$xcontact->setContactId($data['name']);

            foreach ($data['invoices'] as $key => $invoice) {
                $xinvoice = new Invoice();
                $xinvoice->setType("ACCPAY");
                $xinvoice->setStatus("DRAFT");     
                $xinvoice->setDate($invoice['invdate']);
                $xinvoice->setDueDate($invoice['invdate']);
                $xinvoice->setLineAmountTypes("NoTax"); 
                $xinvoice->setContact($xcontact);
                
                $lineItems = [];
                foreach($invoice['glcodes'] as $glcode ){
                    $newLine = new LineItem();
                    $newLine->setDescription($glcode['gldesc']);
                    $newLine->setQuantity("1.0000");
                    $newLine->setUnitAmount($glcode['glamt']);
            

                    $glCodeDetail = $this->breakGLCode($glcode['glcode']);
                    $newLine->setAccountCode($glCodeDetail['glcode']);
                    // Signs of Xmas from '150/Signs of Xmas'
                    if(!empty($glCodeDetail['tracking']))
                        $newLine->setTracking($glCodeDetail['tracking']); 

                    $glCodeDetail = [];
                    $lineItems[] = $newLine;
                }

                $xinvoice->setLineItems($lineItems);
                $xinvoice->setInvoiceNumber($invoice['invnum']);
                $xinvoices[] = $xinvoice;
            }
            
        }

        $this->makeRequest($xeroCredentials, ['invoices'=> $xinvoices]);
        $this->formatInvoice->updateDBRecords();
        return view('pages.home', [
            'homepage_link' => config('common.homepage_link')
        ]);
    }

    public function reupload()
    {
        $this->formatInvoice->setProject($this->getProject());
        $this->formatInvoice->removeRecords();
        return redirect()->route('upload');
    }

    private function makeRequest($xeroCredentials, $inovices)
    {
        if ($xeroCredentials->exists()) {
            // Tenant ID is based on Project Orgination ID... we can allow for all the Orgination
            $tenantID = $xeroCredentials->getTenantId(1);
            var_dump($tenantID); // f0edac46-76ca-48ce-b479-442cff00012f
            $xero = resolve(\XeroAPI\XeroPHP\Api\AccountingApi::class);
            // line 58337 and 58101
            $xero->updateOrCreateInvoices($tenantID, new Invoices($inovices));
        }
    }

    protected function breakGLCode($GLcode)
    {
        $trackingLine = new LineItemTracking();
        if(!str_contains($GLcode, $this->getProject()->COA_Break_Character))
        {
            return [
                'glcode' => $GLcode,
                'tracking' => ''
            ];
        }

        $cods = explode((string)$this->getProject()->COA_Break_Character, $GLcode);
        // Name of the name and option (required)
        $trackingLine->setName('Show'); // category name is set to show 
        $trackingLine->setOption($cods[1]);// and option (case sensitive)
        return  [
            'glcode' => $cods[0] . '-00-000',
            'tracking' => [$trackingLine]
        ];
    }


}
