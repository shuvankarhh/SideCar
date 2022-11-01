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
use App\Services\FormatInvoiceData;
use Webfox\Xero\OauthCredentialManager;

class InvoiceController extends Controller
{

    public $formatInvoice;

    public function __construct(FormatInvoiceData $formatInvoice)
    {
        $this->formatInvoice = $formatInvoice;
    } 

    // xlx upload
    public function index(Request $request)
    {

        $p = \App\Models\Project::where('Project_ID', 1)->first();
        //dd($p->projectApiSystem->apiAccessToken()->exists());
        return view('pages.upload');
    }

    // /fileupload
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

    public function testMethod()
    {
        $invoiceFileImporter = new InvoiceFileImport();
        Excel::import($invoiceFileImporter, storage_path('app/ACI-AP_Add.xlsx'));
        
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
                
                $lineItems=[];
                foreach($invoice['glcodes'] as $glcode ){
                    $newLine = new LineItem();
                    $newLine->setDescription($glcode['gldesc']);
                    $newLine->setQuantity("1.0000");
                    $newLine->setUnitAmount($glcode['glamt']);
                    // $this->project->COA_Break_Character
                    $newLine->setAccountCode($glcode['glcode']);
                        //$newLine->setTracking($glcode['glcode']); Signs of Xmas from '150/Signs of Xmas'
                        // "Tracking": [
                        //     {
                        //       "TrackingCategoryID": "e2f2f732-e92a-4f3a9c4d-ee4da0182a13",
                        //       "Name": "Activity/Workstream",
                        //       "Option": "Onsite consultancy"
                        //     }
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
            $tenantID = $xeroCredentials->getTenantId();
            var_dump($tenantID); // f0edac46-76ca-48ce-b479-442cff00012f
            $xero = resolve(\XeroAPI\XeroPHP\Api\AccountingApi::class);
            // line 58337 and 58101
            $xero->updateOrCreateInvoices($tenantID, new Invoices($inovices));
        }
    }

    protected function breakGLCode($code, $char)
    {
        return implode($char, $code);
    }


}
