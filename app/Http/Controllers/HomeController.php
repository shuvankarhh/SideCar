<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Company;
use App\Models\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $c = Client::whereHas('company', function($q)
        {
            $q->where('Company_Name','=', '20th Century Props');

        })->first();
        
        $projects = Project::all();
        return view('home', [
            'projects' => $projects
        ]);
    }
}
