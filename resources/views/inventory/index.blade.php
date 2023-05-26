@extends('adminlte::page')

@section('title', 'Inventory')

@section('content_header')
    <h1 class="m-0 text-dark">Inventory</h1>
@stop

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
    <script>
         $(document).ready( function () {
            let headers = { 'Content-type': 'application/json', 'Authorization': 'Bearer '+localStorage.getItem('token') };
            getCompanies(headers)
        });

        function getCompanies(headers){
            $.ajax({
                url: route('inventory.api_index'),
                headers: headers,
                success: function (response) {
                    console.log(response.data.length)
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
    </script>
@endpush

