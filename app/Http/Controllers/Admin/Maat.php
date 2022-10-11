<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Excel\Exports\CostAndFormatExport;
use App\Excel\Imports\CostAndFormat;
use Excel;


class MaatController extends Controller
{

    public function index()
    {
        //dd('adsf');
        return (new CostAndFormatExport())->download('costs.xlsx');
    }

    public function import(Request $request)
    {
        if($request->file())
        {
            $path = $request->file('file')->store('excel-files');
            $array = Excel::import(new CostAndFormat, storage_path('app/' . $path));
            //$array = Excel::toArray(new CostAndFormat, storage_path('app/' . $path));
            dd($array);
        }
        
    }

    public function export()
    {
        # code...
    }

}
