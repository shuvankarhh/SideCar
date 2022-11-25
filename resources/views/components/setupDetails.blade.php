
    <li class="nav-item">
        @if(!empty($viewData['client']))
            <a class="nav-link" href="{{ route('StepOne') }}">| {!! $client->company->Company_Name !!} |</a>
        @else
            <a class="nav-link" href="{{ route('StepOne') }}">| Set Client |</a>
        @endif
    </li>

    <li class="nav-item">
        @if(!empty($viewData['project']))
        <a class="nav-link" href="{{ route('StepTwo') }}">| {!! $project->Project_Name !!} |</a>
        @else
            <a class="nav-link" href="{{ route('StepTwo') }}">| Set Project |</a>
        @endif
    </li>

    @if(!empty($viewData['fileUpload']))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('upload') }}">| Invoice Upload |</a>
    </li>
    @endif

