@extends('layouts.app')
@section('content')

<div class="container d-flex min-vh-100">
    <div class="row h-100 w-100 justify-content-center align-items-center align-content-center flex-column m-auto">
        <div class="col-md-8 ">
            <div class="card">
                <div class="card-header">{{ __('Upload file to import') }}</div>

                <div class="card-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('saveFile') }}"  enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="filename" class="col-md-4 col-form-label text-md-end">{{ __('File') }}</label>

                            <div class="col-md-6">
                                <input class="form-control" name="filename" type="file" id="formFile" required>
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

                    <div x-data="{}">
                        <button class="btn btn-primary mb-2" x-on:click="chartOfAccountsUpdate">Update Accounts from ERP</button>
                        <br/>
                        <button class="btn btn-primary" @click="trackingCategoriesUpdate">Update Tracking Catgories from ERP</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function chartOfAccountsUpdate(e) {
        this.$dispatch('loading', true);
        fetch("{{ route('coa') }}", {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
            }
        })
        .then(response => response.text())
        .then(text => {
            this.$dispatch('loading', false);
        })
    }

    function trackingCategoriesUpdate(e) {
        this.$dispatch('loading', true);
        fetch("{{ route('trackingCategories') }}", {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
            }
        })
        .then(response => response.text())
        .then(text => {
            this.$dispatch('loading', false);
        })
    }

</script>

@stop

