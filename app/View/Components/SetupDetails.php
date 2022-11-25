<?php

namespace App\View\Components;

use Illuminate\View\View;
use App\Models\Client;
use App\Models\Project;
use Illuminate\View\Component;
use Session;

class SetupDetails extends Component
{
    public $client;
    public $project;
 
    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = Client::where('Client_ID', Session::get('client_id'))->first();
        $this->project = Project::where('Project_ID', Session::get('project_id'))->first();
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $viewData = [
            'client' => $this->client ?? '',
            'project' => $this->project ?? '',
            'fileUpload' => !empty($this->client) && !empty($this->project)
        ];
        return view('components.setupDetails', compact('viewData'));
    }

}