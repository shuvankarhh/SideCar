<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// get all the rows
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class InvoiceImport implements ToCollection, WithHeadingRow
{

    public $data;
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function collection(Collection $rows)
    {
        /**
         * VendorID:
         *InvNum	InvDate	InvAmt	InvDue	InvDesc	GLCode	GLAmt	GLDesc
        *
        * InvAMT is total of the invoice and GLcode is the line are description
         */
        foreach ($rows as $row) 
        {
            if (!empty($row['vendorid'])) {
            
                // Vendor detail
                $this->data[$row['vendorid']]['name'] = $row['vendorid'];
                // invoice detail
                $this->data[$row['vendorid']]['invoices'][$row['invnum']]['invnum'] = (string)$row['invnum'];
                $this->data[$row['vendorid']]['invoices'][$row['invnum']]['invamt'] = (string)$row['invamt'];
                
                $invdate = $this->transformDate($row['invdate']);
                $invdue = $this->transformDate($row['invdue']);
                $this->data[$row['vendorid']]['invoices'][$row['invnum']]['invdate'] = "/Date(".strtotime($invdate)."+0000)/";
                $this->data[$row['vendorid']]['invoices'][$row['invnum']]['invdue'] = "/Date(".strtotime($invdue)."+0000)/";

                // Gl code detail
                $this->data[$row['vendorid']]['invoices'][$row['invnum']]['glcode'][$row['glcode']]['glcode'] = $row['glcode'];
                $this->data[$row['vendorid']]['invoices'][$row['invnum']]['glcode'][$row['glcode']]['glamt'] = (string)$row['glamt'];
                $this->data[$row['vendorid']]['invoices'][$row['invnum']]['glcode'][$row['glcode']]['gldesc'] = $row['gldesc'];

            }
        }

    }

    /**
     * Transform a date value into a Carbon object.
     *
     * @return \Carbon\Carbon|null
     */
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

}
