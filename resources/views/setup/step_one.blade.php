@extends('layouts.app')

@section('content')
<div class="container d-flex min-vh-100">
    <div class="row h-100 w-100 justify-content-center align-items-center align-content-center flex-column m-auto">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">{{ __('Setup - Step One') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('PostStepOne') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="client" class="col-md-4 col-form-label text-md-end">{{ __('Select Client') }}</label>

                            <div class="col-md-6">
                       
                                <select name="client_id" class="form-control" required autofocus>
                                    @foreach ($clients as $key => $value)
                                    <option value="{{ $key }}"> {{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Next') }}
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
