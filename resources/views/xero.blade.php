@extends('layouts.app')

@section('content') 



<div class="container d-flex min-vh-100">
    <div class="row h-100 w-100 justify-content-center align-items-center align-content-center flex-column m-auto">
        <div class="col-md-8 ">
            <div class="card">
                <div class="card-header">{{ __('Connect to Xero') }}</div>

                <div class="card-body">
                    

                        <div class="row mb-3">
                            @if($error)
                                <h1>Your connection to Xero failed</h1>
                                <p>{{ $error }}</p>
                                <a href="{{ route('xero.auth.authorize') }}" class="btn btn-primary btn-large mt-4">
                                    Reconnect to Xero
                                </a>
                            @elseif($connected)
                                <h1>You are connected to Xero</h1>
                                @foreach ($organisations as $organisation )
                                    <p>{{ $organisation->getName() }} via {{ $username }}</p>
                                    <br>
                                @endforeach
                                
                                <a href="{{ route('xero.auth.authorize') }}" class="btn btn-primary btn-large mt-4">
                                    Reconnect to Xero
                                </a>
                            @else
                                <h1>You are not connected to Xero</h1>
                                <a href="{{ route('xero.auth.authorize') }}" class="btn btn-primary btn-large mt-4">
                                    Connect to Xero
                                </a>
                            @endif
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>




@endsection
