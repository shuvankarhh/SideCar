<?php

namespace App\Services;

use App\Models\InvoiceImport; 

class FormatInvoiceData
{
    public $data = [];
    public $idsToUpdate=[];
    public $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
        $this->formatApiData($project_id);
    }

    public function formatApiData($project_id)
    {
        $dataToBeImported = InvoiceImport::where(['project_id' => $this->project_id, 'imported' => 0])->get();

        foreach ($dataToBeImported as $key => $row) {
            $this->idsToUpdate[$row->id] = $row->id;
            // Vendor detail
            $this->data[$row['vendorid']]['name'] = $row['vendorid'];
            $this->data[$row['vendorid']]['name'] = $row['vendorid'];
            // invoice detail
            $this->data[$row['vendorid']]['invoices'][$row['invnum']]['invnum'] = (string)$row['invnum'];
            $this->data[$row['vendorid']]['invoices'][$row['invnum']]['invamt'] = (string)$row['invamt'];
            $this->data[$row['vendorid']]['invoices'][$row['invnum']]['invdate'] = $row['invdate'];
            $this->data[$row['vendorid']]['invoices'][$row['invnum']]['invdue'] = $row['invdue'];

            // Gl code detail
            $this->data[$row['vendorid']]['invoices'][$row['invnum']]['glcodes'][$row['glcode']]['glcode'] = $row['glcode'];
            $this->data[$row['vendorid']]['invoices'][$row['invnum']]['glcodes'][$row['glcode']]['glamt'] = (string)$row['glamt'];
            $this->data[$row['vendorid']]['invoices'][$row['invnum']]['glcodes'][$row['glcode']]['gldesc'] = $row['gldesc'];
        }
    }

    public function updateDBRecords()
    {
        InvoiceImport::whereIn('id', $this->idsToUpdate)->where(['project_id' => $this->project_id])->update([
            'imported' => 1,
        ]);
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
        return strtotime($date);
    }

}