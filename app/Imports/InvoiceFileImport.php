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
use App\Models\ChartOfAccount;
use App\Models\TrackingCategory;
use App\Models\TrackingOption;

class InvoiceFileImport implements ToCollection, WithHeadingRow
{

    public $data;
    protected $fileName;
    protected $project;

    public function fromFile(string $fileName, $project)
    {
        $this->fileName = $fileName;
        $this->project = $project;
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

                $praseGLcode = $this->parseGLCodeDetail($row['glcode']);

                InvoiceImport::create([
                    'vendorid'  => $row['vendorid'],
                    'invnum'    => $row['invnum'],
                    'invamt'    => $row['invamt'],
                    'invdate'   => $this->transformDate($row['invdate']),
                    'invdue'    => $this->transformDate($row['invdue']),
                    'glcode'    => $row['glcode'],
                    'coa'       => $praseGLcode['glcode'],
                    'tracking_category' => $praseGLcode['tracking']['category'] ?? null,
                    'tracking_option'   => $praseGLcode['tracking']['option'] ?? null,
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


    // online active
    public function parseGLCodeDetail($GLcode)
    {
        if(!str_contains($GLcode, $this->project->COA_Break_Character))
        {
            return [
                'glcode' => $GLcode,
                'tracking' => ''
            ];
        }
        $cods = explode($this->project->COA_Break_Character, $GLcode);

        // find glcode from char of account
        $getCOA = ChartOfAccount::where('project_api_system_id', $this->project->projectApiSystem->id)
        ->where('status', ChartOfAccount::ACTIVE)
        ->where('code', 'LIKE', $cods[0].'%')->first();
        return [
            'glcode' => $getCOA->code ?? $cods[0],
            'tracking' => $this->trackingDetails($cods[1])
        ];
    }

    // there could be multipule categories
    public function trackingDetails($trackingOption)
    {
        $data = [
            'category' =>  "",
            'option' =>  ""
        ];

        $option = TrackingOption::where('name', 'Like', '%'.$trackingOption.'%')->where('status', TrackingOption::ACTIVE)->first();
        if (empty($option)) {
            return $data;
        }

        $category = $option->trackingCategory()->where('project_api_system_id', $this->project->projectApiSystem->id)->first();

        if (empty($category)) {
            return $data;
        }
        
        // find option category
        return [
            'category' => $category->name ?? "",
            'option' => $option->name ?? ""
        ];
    }



}
