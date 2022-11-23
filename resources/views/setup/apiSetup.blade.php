@extends('layouts.app')

@section('content')
<div class="container d-flex min-vh-100">
    <div class="row h-100 w-100 justify-content-center align-items-center align-content-center flex-column m-auto">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">{{ __('Setup - Step One') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('apiInfo') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input name="name" type="text" class="form-control" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <input name="description" type="text" class="form-control" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="software" class="col-md-4 col-form-label text-md-end">{{ __('Software') }}</label>

                            <div class="col-md-6">
                                <input name="software" type="text" class="form-control" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="api_key" class="col-md-4 col-form-label text-md-end">{{ __('Api Key') }}</label>

                            <div class="col-md-6">
                                <input name="api_key" type="text" class="form-control" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="client" class="col-md-4 col-form-label text-md-end">{{ __('Api Secret') }}</label>

                            <div class="col-md-6">
                                <input name="api_secret" type="text" class="form-control" required />
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
    </div>
</div>
@endsection
