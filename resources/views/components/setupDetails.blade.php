    <li class="nav-item">
        <a class="nav-link" href="#">{!! $client->company->Company_Name !!} |</a>
    </li>

@if(Session::has('project_id'))
        <li class="nav-item">
            <a class="nav-link" href="#">{!! $project->Project_Name !!} |</a>
        </li>
@endif
