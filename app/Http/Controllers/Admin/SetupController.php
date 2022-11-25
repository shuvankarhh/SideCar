<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;

class SetupController extends Controller
{

    /**
     * Display a listing of the prducts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(empty($request->session()->get('client_id')) ){
            return redirect()->route('StepOne');
        }

        $client = Client::where('Client_ID', $request->session()->get('client_id'))->first();
        $project = Project::where('Project_ID', $request->session()->get('project_id'))->first();

        return view('setup.index', compact('client', 'project'));
    }
  
    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStepOne(Request $request)
    {
        $clients = Client::with('company')->get()->pluck('company.Company_Name', 'Client_ID')->prepend('Please select', '')->all();
        return view('setup.step_one', compact('clients'));
    }

    /**  
     * Post Request to store step1 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreateStepOne(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
        ]);
        $request->session()->put('client_id', $request->get('client_id'));
        $request->session()->forget('project_id');

        return redirect()->route('StepTwo');
    }

    
    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStepTwo(Request $request)
    {
        if(!\Session::has('client_id')){
            return redirect()->route('StepOne'); 
        }

        $clientID = $request->session()->get('client_id');
        
        $c = Client::whereHas('company', function($q){
            $q->where('Company_Name','=', '20th Century Props');
        })->first();
        
        $projects = Project::where('Client_ID', $clientID)->pluck('Project_Name', 'Project_ID')->prepend('Please select', '')->all();
  
        return view('setup.step_two', compact('projects'));
    }
  
    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function postCreateStepTwo(Request $request)
    {
        $request->validate([
            'project_id' => 'required'
        ]);

        $request->session()->put('project_id', $request->get('project_id'));
        
        // goes to file upload
        return redirect()->route('upload');
    }

}
