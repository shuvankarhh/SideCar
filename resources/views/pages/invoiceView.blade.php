@extends('layouts.app')
@section('content')

<div class="container d-flex min-vh-100">
    <div class="row h-100 w-100 justify-content-center align-items-center align-content-center flex-column m-auto">
        <div class="col-md-8 ">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Handle</th>
                    </tr>
                </thead>
                <tbody>

                    @if (count($data) > 0)
                        @foreach ($data as $row)
                            <tr>
                                <th>{{ $row->vendorid }}</th>
                                <td>{{ $row->invnum }}</td>
                                <td>{{ $row->invamt }}</td>
                                <td>{{ $row->invdate }}</td>
                                <td>{{ $row->glcode }}</td>
                            </tr>
                        @endforeach
                        
                    @endif
                    
                </tbody>
            </table>

            <a class="btn btn-primary" href="{{ route('createInvoice') }}">Import</a> <a class="btn btn-primary">Delete</a>
        </div>
    </div>
</div>

@stop
