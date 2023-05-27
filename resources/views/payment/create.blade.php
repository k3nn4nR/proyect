@extends('adminlte::page')

@section('title', 'Payment')


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
                                <input type="text" name="total" id="total">
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
                                            <select class="form-control @error('items') is-invalid @enderror" id="items_select" name="items[]">
                                                <option>Choose One..</option>
                                            </select>
                                            @error('items')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </td>
                                        <td><input type="number" name="items_amount[]" onchange="updateSubtotal($(this),this)"></td>
                                        <td><input type="number" name="items_price[]" onchange="updateSubtotal($(this),this)"></td>
                                        <td><input type="number" name="items_subtotal[]"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" id="select-row"></td>
                                        <td>
                                            <select class="form-control" id="services_select" name="services[]">
                                                <option>Choose One..</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="services_amount[]" onchange="updateSubtotal($(this),this)"></td>
                                        <td><input type="number" name="services_price[]" onchange="updateSubtotal($(this),this)"></td>
                                        <td><input type="number" name="services_subtotal[]"></td>
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
        var items, services;
        $(document).ready( function () {
            let headers = { 'Content-type': 'application/json', 'Authorization': 'Bearer '+localStorage.getItem('token') };

            getCompanies(headers)
            getCurrencies(headers)
            getItems(headers)
            getServices(headers)

            $("#add-item-row").click(function(){
                $(".table tbody tr").last().after(
                    '<tr>'+
                        '<td><input type="checkbox" id="select-row"></td>'+
                        '<td>'+items+'</td>'+
                        '<td><input type="number" name="items_amount[]" onchange="updateSubtotal($(this),this)"></td>'+
                        '<td><input type="number" name="items_price[]" onchange="updateSubtotal($(this),this)"></td>'+
                        '<td><input type="number" name="items_subtotal[]"></td>'+
                    '</tr>'
                );
            })

            $("#add-service-row").click(function(){
                $(".table tbody tr").last().after(
                    '<tr>'+
                        '<td><input type="checkbox" id="select-row"></td>'+
                        '<td>'+services+'</td>'+
                        '<td><input type="number" name="services_amount[]" onchange="updateSubtotal($(this),this)"></td>'+
                        '<td><input type="number" name="services_price[]" onchange="updateSubtotal($(this),this)"></td>'+
                        '<td><input type="number" name="services_subtotal[]"></td>'+
                    '</tr>'
                );
            })

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
            object.parent().siblings()[3].children[0].value = (object.parent().siblings()[2].children[0].value*value.value).toFixed(2)
            updateTotal()
        }

        function updateTotal(){
            var sum = 0;
            $(".table tbody tr").each(function(){
                if($(this).children()[4].children[0].value !== '')
                    sum += parseFloat($(this).children()[4].children[0].value)
            });
            $("#total").val(sum.toFixed(2))
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
                    items = '<select class="form-control" id="items_select" name="items[]"><option>Choose One..</option>'
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].item;
                        added.innerHTML = response.data[i].item;
                        select.append(added);
                        items += '<option value="'+response.data[i].item+'">'+response.data[i].item+'</option>'
                    }
                    items += '</select>'
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
                    services = '<select class="form-control" id="services_select" name="services[]"><option>Choose One..</option>'
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].service;
                        added.innerHTML = response.data[i].service;
                        select.append(added);
                        services += '<option value="'+response.data[i].service+'">'+response.data[i].service+'</option>'
                    }
                    services += '</select>'
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
    </script>
@endpush

