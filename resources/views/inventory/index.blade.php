@extends('adminlte::page')

@section('title', 'Inventory')

@section('plugins.Datatables', true)

@section('content_header')
    <h1 class="m-0 text-dark">Inventory</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-5 col-sm-3">
                        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical"></div>
                    </div>
                    <div class="col-7 col-sm-9">
                        <div class="tab-content" id="vert-tabs-tabContent"></div>
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
            getInventory(headers)
            Echo.channel('payment-registered')
            .listen('PaymentRegisteredEvent', (e)=>{
                for(var i = 0 ; $('#vert-tabs-tab').children().length ; i++)
                    $('#vert-tabs-tab').children()[i].remove()
                for(var i = 0 ; $('#vert-tabs-tabContent').children().length ; i++)
                    $('#vert-tabs-tabContent').children()[i].remove()
                getInventory(headers)
            });
        });

        function getInventory(headers){
            $.ajax({
                url: route('inventory.api_index'),
                headers: headers,
                success: function (response) {
                    var tabs = $('#vert-tabs-tab');
                    var tab_content = $('#vert-tabs-tabContent');
                    for (var i = 0; i < response.data.length; i++){
                        var add_a = document.createElement('a')
                        add_a.className = "nav-link"
                        add_a.id = "vert-tabs-"+response.data[i].warehouse+'-tab'
                        add_a.href = "#vert-tabs-"+response.data[i].warehouse
                        add_a.role = "tab"
                        add_a.innerHTML = response.data[i].warehouse
                        add_a.setAttribute('data-toggle',"pill")
                        add_a.setAttribute('aria-controls',"vert-tabs-"+response.data[i].warehouse)
                        add_a.setAttribute('aria-selected', "false")
                        tabs.append(add_a);
                        var add_div = document.createElement('div')
                        add_div.className = "tab-pane fade"
                        add_div.id = "vert-tabs-"+response.data[i].warehouse
                        add_div.role = "tabpanel"
                        add_div.setAttribute('aria-labelledby', "vert-tabs-settings-tab")
                        add_div.innerHTML = '<div class="card-body">'+
                        '<div class="table-responsive">'+
                            '<table id="myTable-'+response.data[i].warehouse+'" class="table table-bordered table-striped table-hover" style="width:100%">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th>Item</th>'+
                                        '<th>Code</th>'+
                                        '<th>Amount</th>'+
                                        '<th></th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tfoot>'+
                                    '<tr>'+
                                        '<th>Item</th>'+
                                        '<th>Code</th>'+
                                        '<th>Amount</th>'+
                                        '<th></th>'+
                                    '</tr>'+
                                '</tfoot>'+
                            '</table>'+
                        '</div></div>'
                        tab_content.append(add_div);
                        var table = $('#myTable-'+response.data[i].warehouse).DataTable({
                            data: response.data[i].inventory,
                            columns: [
                                { data: 'item' },
                                { data: 'code' },
                                { data: 'amount' },
                                {
                                    render: function ( data, type, row, meta ) {
                                        return '<form action="'+route('inventory.consume',[response.data[i].warehouse,row.item])+'" method="post"> @csrf'+
                                        '<button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i></button></form>'
                                    },
                                }
                            ],
                        });
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
    </script>
@endpush

