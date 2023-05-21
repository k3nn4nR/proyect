@extends('adminlte::page')

@section('title', 'Payment')

@section('plugins.Select2', true)

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
                                        <th>All <input type="checkbox" id="select-all"></th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>E-Mail</th>
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
                                        <td><input type="number" name="items_ammount[]"></td>
                                        <td><input type="number" name="items_quantity[]"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" id="select-row"></td>
                                        <td>
                                            <select class="form-control" id="services_select" name="services[]">
                                                <option>Choose One..</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="services_ammount[]"></td>
                                        <td><input type="number" name="services_quantity[]"></td>
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

            //add new row for service 
            //add new row for item
            //use select for items with name="items"
            //use select for services with name="services"

            // Add new row
            $("#add-item-row").click(function(){
                $(".table tbody tr").last().after(
                    '<tr class="fadetext">'+
                        '<td><input type="checkbox" id="select-row"></td>'+
                        '<td><select class="form-control" id="services_select" name="items[]"><option>Choose One..</option></select></td>'+
                        '<option>Choose One..</option></select></td>'+
                        '<td><input type="number" name="items_ammount[]"></td>'+
                        '<td><input type="number" name="items_quantity[]"></td>'+
                    '</tr>'
                );
            })

            // Add new row
            $("#add-service-row").click(function(){
                $(".table tbody tr").last().after(
                    '<tr class="fadetext">'+
                        '<td><input type="checkbox" id="select-row"></td>'+
                        '<td><select class="form-control" id="items_select" name="services[]"><option>Choose One..</option></select></td>'+
                        '<option>Choose One..</option></select></td>'+
                        '<td><input type="number" name="services_ammount[]"></td>'+
                        '<td><input type="number" name="services_quantity[]"></td>'+
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
                        alert('All rows cannot be deleted.');
                    }else if(isChecked){
                        $(this).remove();
                    }
                });
            });

        });

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

