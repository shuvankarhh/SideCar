@extends('layouts.app')
@section('content')

<div class="container d-flex min-vh-100 mt-5">
    <div class="row h-100 w-100 justify-content-center align-items-center align-content-center flex-column mt-2">
        <div class="col-md-12 bg-white">

            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
    
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if (count($users) > 0)
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <a class="btn btn-primary" href="{{ route('userEdit', $user->id) }}">Edit</a>
                                    @if (count($users) > 2)
                                        <a class="btn btn-danger" href="{{ route('userDelete', $user->id) }}">Delete</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        
                    @endif
                    
                </tbody>
            </table>

        </div>
    </div>
</div>


@stop
