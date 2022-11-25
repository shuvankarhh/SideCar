@extends('layouts.app')

@section('content')
<div class="container d-flex min-vh-100">
    <div class="row h-100 w-100 justify-content-center align-items-center align-content-center flex-column m-auto">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">{{ __('Setup - Step Two') }}</div>

                <div class="card-body">
                    <form method="POST" id="project_id_form" action="{{ route('PostStepTwo') }}" x-data="submitProject" @submit.prevent="submitForm" >
                        @csrf

                        <div class="row mb-3">
                            <label for="project_id" class="col-md-4 col-form-label text-md-end">{{ __('Select Project') }}</label>

                            <div class="col-md-6">
                       
                                <select name="project_id" class="form-control" required x-on:change="submitForm">
                                    @foreach ($projects as $key => $value)
                                    <option value="{{ $key }}"> {{$value}}</option>
                                    @endforeach
                                </select>
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

<script>
    function submitProject() {
        return {
            formData:{
                project_id:""
            },
            submitForm() {
                this.$dispatch('loading', true);
                document.getElementById('project_id_form').submit();
            }
        }
    }
</script>
@endsection
