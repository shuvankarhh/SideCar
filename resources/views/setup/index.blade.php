@extends('layouts.app')

@section('content')
<div class="container d-flex min-vh-100">
    <div class="row h-100 w-100 justify-content-center align-items-center align-content-center flex-column m-auto">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">{{ __('Selected Client and Project') }}</div>

                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            {{ $client->company->Company_Name }}
                        </div>

                        <div class="col-md-6">
                            {{ $project->Project_Name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
