    <li class="nav-item">
        <a class="nav-link" href="{{ route('StepOne') }}">{!! $client->company->Company_Name !!} |</a>
    </li>

@if(Session::has('project_id'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('StepTwo') }}">{!! $project->Project_Name !!} |</a>
        </li>
@endif
