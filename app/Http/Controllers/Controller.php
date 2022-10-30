<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Project;
use App\Models\Client;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    const PAGINATION_NUM_OF_ITEM = 5;

    
    public function getProject()
    {
        return Project::where('Project_ID', \Session::get('project_id'))->first();
    }

    public function getClient()
    {
        return Client::where('Client_ID', \Session::get('client_id'))->first();
    }
}


