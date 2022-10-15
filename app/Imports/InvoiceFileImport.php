<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// get all the rows
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\InvoiceImport;

class InvoiceFileImport implements ToCollection, WithHeadingRow
{

    public $data;
    protected $fileName;

    public function fromFile(string $fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

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
                InvoiceImport::create([
                    'vendorid'  => $row['vendorid'],
                    'invnum'    => $row['invnum'],
                    'invamt'    => $row['invamt'],
                    'invdate'   => $this->transformDate($row['invdate']),
                    'invdue'    => $this->transformDate($row['invdue']),
                    'glcode'    => $row['glcode'],
                    'glamt'     => $row['glamt'],
                    'gldesc'    => $row['gldesc'],
                    'filename'  => $this->fileName,
                    'project_id' => \Session::get('project_id')
                ]);
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
            $date = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            echo $value;
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
        return $date;
    }

}
