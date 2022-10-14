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

    public function createInvoice(OauthCredentialManager $xeroCredentials)
    {
        $invoiceFileImporter = new InvoiceFileImport();
        Excel::import($invoiceFileImporter, storage_path('app/ACI-AP_Add.xlsx'));

        $contactname = 'Howard Braunstein Films';
       // $contactname = 'Writers Guild Industry Health Fund';

        //create contact
        $xcontact = new Contact();
        $xcontact->setName($contactname);
        //$xcontact->setContactId($contactname);
        

        foreach ($invoiceFileImporter->data as $key => $data) {

            if($data['name'] == $contactname)
            {
                foreach ($data['invoices'] as $key => $invoice) {
                    $xinvoice = new Invoice();
                    $xinvoice->setType("ACCREC");
                    $xinvoice->setStatus("DRAFT");     
                    $xinvoice->setDate($invoice['invdate']);
                    $xinvoice->setDueDate($invoice['invdate']);
                    $xinvoice->setLineAmountTypes("NoTax"); 
                    $xinvoice->setContact($xcontact);
                    
                    foreach($invoice['glcode'] as $glcode ){
                        $newLine = new LineItem();
                        $newLine->setDescription($glcode['glcode'] ." - ".$glcode['gldesc']);
                        $newLine->setQuantity("1.0000");
                        $newLine->setUnitAmount($glcode['glamt']);
                        //$newLine->setAccountCode();
                        $lineItems[] = $newLine;
                    }

                    $xinvoice->setLineItems($lineItems);
                    $xinvoice->setInvoiceNumber($invoice['invnum']);
                    $xinvoices[] = $xinvoice;
                }

            }
            
        }

        // Check if we've got any stored credentials
        //var_dump(json_encode(new Invoices(['invoices'=> $xinvoices])));
        $this->makeRequest($xeroCredentials, ['invoices'=> $xinvoices]);


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

    




    // /reupload
    public function reset()
    {
        return redirect("/");
    }



}
