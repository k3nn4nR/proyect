@extends('adminlte::page')

@section('title', 'Payment')

@section('plugins.Select2', true)
@section('plugins.SweetAlert2', true)

@section('content_header')
    <h1 class="m-0 text-dark">Payment Create</h1>
@stop

@section('adminlte_css')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@stop

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="form" method="POST" action="{{ route('payment.store') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="currency_select">{{ __('Currency') }}</label>
                                <select class="form-control @error('currency') is-invalid @enderror" id="currency_select" name="currency">
                                    <option>Choose One..</option>
                                </select>
                                @error('currency')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="company_select">{{ __('Company') }}</label>
                                <select class="form-control @error('company') is-invalid @enderror" id="company_select" name="company">
                                    <option>Choose One..</option>
                                </select>
                                @error('company')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="total">{{ __('Total') }}</label>
                                <input type="text" id="total" disabled>
                            </div>
                            <div class="col-md-9">
                                <button class="btn btn-success col-md-3" id="add-item-row" onclick="event.preventDefault();">Add Item</button>
                                <button class="btn btn-success col-md-3" id="add-service-row" onclick="event.preventDefault();">Add Service</button>
                                <button class="btn btn-danger col-md-3" id="remove-row" onclick="event.preventDefault();">Remove Row</button>
                            </div>
                        </div>

                        <div class="form-group row">
                            <table class="table responsive">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>Description</th>
                                        <th>Ammount</th>
                                        <th>Price</th>
                                        <th>Sub-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox" id="select-row"></td>
                                        <td>
                                            <select class="form-control" id="items_select" name="items[]">
                                                <option>Choose One..</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="items_ammount[]" onchange="updateSubtotal($(this),this)"></td>
                                        <td><input type="number" name="items_price[]" onchange="updateSubtotal($(this),this)"></td>
                                        <td><input type="number" disabled ></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" id="select-row"></td>
                                        <td>
                                            <select class="form-control" id="services_select" name="services[]">
                                                <option>Choose One..</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="services_ammount[]" onchange="updateSubtotal($(this),this)"></td>
                                        <td><input type="number" name="services_price[]" onchange="updateSubtotal($(this),this)"></td>
                                        <td><input type="number" disabled ></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="form-group row ">
                            <div class="col-md-4 col-md-offset-4">
                                <button type="submit" class="btn btn-primary btn-block" onclick="event.preventDefault(); document.getElementById('form').submit();">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
    <script>
        const tableBody = document.getElementById("myTableBody");
        $(document).ready( function () {
            let headers = { 'Content-type': 'application/json', 'Authorization': 'Bearer '+localStorage.getItem('token') };
            let items = [], services = [];
            Echo.channel('currency-registered')
            .listen('CurrencyRegisteredEvent', (e)=>{
                getCurrencies(headers)
            });
            Echo.channel('company-registered')
            .listen('CompanyRegisteredEvent', (e)=>{
                getCompanies(headers)
            });
            Echo.channel('item-registered')
            .listen('ItemRegisteredEvent', (e)=>{
                getItems(headers)
            });
            Echo.channel('service-registered')
            .listen('ServiceRegisteredEvent', (e)=>{
                getServices(headers)
            });
            getCompanies(headers)
            getCurrencies(headers)
            getItems(headers)
            getServices(headers)

            // Add new row for item
            $("#add-item-row").click(function(){
                $(".table tbody tr").last().after(
                    '<tr class="fadetext">'+
                        '<td><input type="checkbox" id="select-row"></td>'+
                        '<td><select class="form-control" id="services_select" name="items[]"><option>Choose One..</option></select></td>'+
                        '<option>Choose One..</option></select></td>'+
                        '<td><input type="number" name="items_ammount[]" onchange="updateSubtotal($(this))></td>'+
                        '<td><input type="number" name="items_price[]" onchange="updateSubtotal($(this))></td>'+
                        '<td><input type="number" disabled ></td>'+
                    '</tr>'
                );
            })

            // Add new row for service 
            $("#add-service-row").click(function(){
                $(".table tbody tr").last().after(
                    '<tr class="fadetext">'+
                        '<td><input type="checkbox" id="select-row"></td>'+
                        '<td><select class="form-control" id="items_select" name="services[]"><option>Choose One..</option></select></td>'+
                        '<option>Choose One..</option></select></td>'+
                        '<td><input type="number" name="services_ammount[]" onchange="updateSubtotal($(this))></td>'+
                        '<td><input type="number" name="services_price[]" onchange="updateSubtotal($(this))></td>'+
                        '<td><input type="number" disabled ></td>'+
                    '</tr>'
                );
            })

            // Select all checkbox
            $("#select-all").click(function(){
                var isSelected = $(this).is(":checked");
                if(isSelected){
                    $(".table tbody tr").each(function(){
                        $(this).find('input[type="checkbox"]').prop('checked', true);
                    })
                }else{
                    $(".table tbody tr").each(function(){
                        $(this).find('input[type="checkbox"]').prop('checked', false);
                    })
                }
            });
            
            // Remove selected rows
            $("#remove-row").click(function(){
                $(".table tbody tr").each(function(){
                    var isChecked = $(this).find('input[type="checkbox"]').is(":checked");
                    var tableSize = $(".table tbody tr").length;
                    if(tableSize == 1){
                        Swal.fire(
                            'Error!',
                            'At least 1 row is expected!',
                            'error'
                        )
                    }else if(isChecked){
                        $(this).remove();
                    }
                });
            });
        });

        function updateSubtotal(object,value){
            var headRow = object.parent().siblings()
            var rowCount = 0;
            var cRow = headRow;  // used as a reference to the current row
            console.log(cRow.nextSibling)
            while (cRow = cRow.nextSibling)  // While there is a next sibling, loop
            {
                console.log(cRow)
                // if (cRow.tagName == "TR" && cRow.style.display === "")
                //     rowCount++;      // increment rowCount by 1
                if(cRow.nextSibling.tagName === 'items_ammount[]')
                    console.log(object.siblings(".items_price[]"))
            }
            // console.log(value.name)
            // console.log('value: '+value.value)
            // var sibling;
            // if(value.name === 'items_ammount[]')
            //     console.log(object.siblings(".items_price[]"))
            // if(value.name === 'items_price[]')
            //     console.log(object.siblings(".items_ammount[]"))
            // if(value.name === 'services_price[]')
            //     console.log(object.siblings(".services_ammount[]"))
            // if(value.name === 'services_ammount[]')
            //     console.log(object.siblings(".services_price[]"))
        }

        function updateTotal(){

        }

        function getCompanies(headers){
            $.ajax({
                url: route('company.api_index'),
                headers: headers,
                success: function (response) {
                    var select = $('#company_select');
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].company;
                        added.innerHTML = response.data[i].company;
                        select.append(added);
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
        function getCurrencies(headers){
            $.ajax({
                url: route('currency.api_index'),
                headers: headers,
                success: function (response) {
                    var select = $('#currency_select');
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].currency;
                        added.innerHTML = response.data[i].currency;
                        select.append(added);
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
        function getItems(headers){
            $.ajax({
                url: route('item.api_index'),
                headers: headers,
                success: function (response) {
                    var select = $('#items_select');
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].item;
                        added.innerHTML = response.data[i].item;
                        select.append(added);
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
        function getServices(headers){
            $.ajax({
                url: route('service.api_index'),
                headers: headers,
                success: function (response) {
                    var select = $('#services_select');
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].service;
                        added.innerHTML = response.data[i].service;
                        select.append(added);
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
    </script>
@endpush

