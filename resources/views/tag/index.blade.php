@extends('adminlte::page')

@section('plugins.Datatables', true)

@section('title', 'Tag')

@section('content_header')
    <h1 class="m-0 text-dark">Tag</h1>
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
                                    <th>Tag</th>
                                    <th>Created</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Tag</th>
                                    <th>Created</th>
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
            let table = $('#myTable').DataTable({
                processing: true,
                ajax: {
                    url: 'api/tag',
                    headers: { 'Content-type': 'application/json' },
                    headers: { 'Authorization': 'Bearer '+localStorage.getItem('token') }
                },
                columns: [
                    { data: 'tag' },
                    { data: 'created_at' },
                    {
                        render: function ( data, type, row, meta ) {
                            return '<a href="'+route('tag.edit',row.tag)+'" class="btn btn-sm btn-warning"><i class="fa fa-trash"></i></a>'+
                            '<form action="'+route('tag.destroy',row.tag)+'" method="post"> @csrf'+
                            '<input type="hidden" name="_method" value="DELETE" >'+
                            '<button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i></button></form>'
                        },
                    }
                ],
            });

            Echo.channel('tag-registered')
            .listen('TagRegisteredEvent', (e)=>{
                table.ajax.reload();
            });
        });
    </script>
@endpush

