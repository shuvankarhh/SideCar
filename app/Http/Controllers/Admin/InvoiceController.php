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
    // xlx upload
    public function index()
    {
        return view('pages.upload');
    }

    // /fileupload
    public function saveFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlx,xls,xlsx'
        ]);

        
        if($request->file('file'))
        {
            $path = $request->file('file')->store('excel-files');

            $invoiceFileImporter = (new InvoiceFileImport())->fromFile($request->file->getClientOriginalName());
            Excel::import($invoiceFileImporter, storage_path('app/' . $path));

            // delete the file after import
           // unlink(storage_path('app/' . $path));
        }
        return back()->with('fileUploaded', 'File Imported Successfully.');
    }

    public function testMethod()
    {
        $invoiceFileImporter = new InvoiceFileImport();
        Excel::import($invoiceFileImporter, storage_path('app/ACI-AP_Add.xlsx'));
        
    }

    // each project have differen Xero instance and redirect URL need to include project ID so we can store the access token base on project
    public function createInvoice(OauthCredentialManager $xeroCredentials)
    {
        $formatInvoicedata = new FormatInvoiceData(Session::get('project_id'));

        foreach ($formatInvoicedata->data as $key => $data) {

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
                    $newLine->setAccountCode($glcode['glcode']);
                    $lineItems[] = $newLine;
                }

                $xinvoice->setLineItems($lineItems);
                $xinvoice->setInvoiceNumber($invoice['invnum']);
                $xinvoices[] = $xinvoice;
            }
            
        }

        $this->makeRequest($xeroCredentials, ['invoices'=> $xinvoices]);
        $formatInvoicedata->updateDBRecords();
        return view('pages.home', [
            'homepage_link' => config('common.homepage_link')
        ]);
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


}
