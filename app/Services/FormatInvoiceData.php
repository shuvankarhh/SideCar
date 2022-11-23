<?php

namespace App\Services;

use App\Models\InvoiceImport; 
use App\Models\ChartOfAccount;
use App\Models\TrackingCategory;
use App\Models\TrackingOption;
class FormatInvoiceData
{
    public $data = [];
    public $idsToUpdate=[];
    public $project;

    public function setProject($project)
    {
        $this->project = $project;
    }

    public function rawData()
    {
        return InvoiceImport::where(['project_id' => \Session::get('project_id'), 'imported' => 0])->get();
    }

    public function formatApiData()
    {
        $dataToBeImported = $this->rawData();

        foreach ($dataToBeImported as $key => $row) {
            //$data['row_id'] = $row->id;
            // Vendor detail
            $data[$row['vendorid']]['name'] = $row['vendorid'];
            $data[$row['vendorid']]['name'] = $row['vendorid'];
            // invoice detail
            $data[$row['vendorid']]['invoices'][$row['invnum']]['invnum'] = (string)$row['invnum'];
            $data[$row['vendorid']]['invoices'][$row['invnum']]['invamt'] = (string)$row['invamt'];
            $data[$row['vendorid']]['invoices'][$row['invnum']]['invdate'] = $row['invdate'];
            $data[$row['vendorid']]['invoices'][$row['invnum']]['invdue'] = $row['invdue'];

            // Gl code detail
            $data[$row['vendorid']]['invoices'][$row['invnum']]['glcodes'][$row['glcode']]['glamt'] = (string)$row['glamt'];
            $data[$row['vendorid']]['invoices'][$row['invnum']]['glcodes'][$row['glcode']]['gldesc'] = $row['gldesc'];

            $data[$row['vendorid']]['invoices'][$row['invnum']]['glcodes'][$row['glcode']]['glcode'] = $row['coa'];
            $data[$row['vendorid']]['invoices'][$row['invnum']]['glcodes'][$row['glcode']]['tracking'] = [$row['tracking_category'] => $row['tracking_option']];

        }
        return $data;
    }

    public function updateDBRecords()
    {
        return InvoiceImport::where(['project_id' => $this->project->Project_ID])
            ->update([
                'imported' => 0,
            ]);
        
    }

    public function removeRecords()
    {
        return InvoiceImport::where(['project_id' => $this->project->Project_ID, 'imported' => 0])->delete();
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