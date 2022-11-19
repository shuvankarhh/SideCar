@extends('layouts.app')
@section('content')

<div class="container d-flex min-vh-100 mt-5">
    <div class="row h-100 w-100 justify-content-center align-items-center align-content-center flex-column m-auto">
        <div class="col-md-8 bg-white">
            <div class="m-2">
                <a class="btn btn-danger" href="{{ route('reupload') }}">Reupload</a>
                <a class="btn btn-primary" href="{{ route('createInvoice') }}">Export To ERP</a> 

        
                <div x-data="{}">
                    <button class="btn btn-primary" x-on:click="chartOfAccountsUpdate">Update Accounts from ERP</button>
                    <button class="btn btn-primary" @click="trackingCategoriesUpdate">Update Tracking Catgories from ERP</button>
                </div>
                
            </div>
            
    
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Vendor ID</th>
                        <th scope="col">Invoice Num</th>
                        <th scope="col">Invoice Amt</th>
                        <th scope="col">Invoice Date</th>
                        <th scope="col">Invoice Due</th>
                        <th scope="col">GL code</th>
                        <th scope="col">GL Amt</th>
                        <th scope="col">GL Desc</th>
                    </tr>
                </thead>
                <tbody>

                    @if (count($data) > 0)
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->vendorid }}</td>
                                <td>{{ $row->invnum }}</td>
                                <td>{{ $row->invamt }}</td>
                                <td>{{ $row->invdate }}</td>
                                <td>{{ $row->invdue }}</td>
                                <td>{{ $row->glcode }}</td>
                                <td>{{ $row->glamt }}</td>
                                <td>{{ $row->gldesc }}</td>
                            </tr>
                        @endforeach
                        
                    @endif
                    
                </tbody>
            </table>

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
