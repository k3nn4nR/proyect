@extends('adminlte::page')

@section('plugins.Datatables', true)

@section('title', 'Currency')

@section('content_header')
    <h1 class="m-0 text-dark">Currency</h1>
@stop

@section('adminlte_css')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@stop

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Currency</th>
                                    <th>Created</th>
                                    <th>Code</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Currency</th>
                                    <th>Created</th>
                                    <th>Code</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
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
            var table = $('#myTable').DataTable({
                ajax: {
                    url: 'api/currency',
                    headers: { 'Content-type': 'application/json' },
                    headers: { 'Authorization': 'Bearer '+localStorage.getItem('token') }
                },
                columns: [
                    { data: 'currency' },
                    { data: 'code' },
                    { data: 'created_at' },
                    {
                        render: function ( data, type, row, meta ) {
                            return '<a href="'+route('currency.edit',row.currency)+'" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>'+
                            '<form action="'+route('currency.destroy',row.currency)+'" method="post"> @csrf'+
                            '<input type="hidden" name="_method" value="DELETE" >'+
                            '<button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i></button></form>'
                        },
                    }
                ],
            });

            Echo.channel('currency-registered')
            .listen('CurrencyRegisteredEvent', (e)=>{
                table.ajax.reload();
            });
        });
    </script>
@endpush
