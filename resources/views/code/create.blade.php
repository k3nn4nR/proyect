@extends('adminlte::page')

@section('title', 'Code')
@section('plugins.Select2', true)

@section('content_header')
    <h1 class="m-0 text-dark">Code Create</h1>
@stop

@section('adminlte_css')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@stop

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="form" method="POST" action="{{ route('code.store') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="code">{{ __('Code') }}</label>
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" required autofocus>
                                @error('code')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="brand_select">{{ __('Brand') }}</label>
                                <select class="form-control @error('brand') is-invalid @enderror" id="brand_select" name="brand">
                                    <option disabled selected>Choose one...</option>
                                </select>
                                @error('brand')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="type_select">{{ __('Model') }}</label>
                                <select class="form-control @error('type') is-invalid @enderror" id="type_select" name="type">
                                    <option disabled selected>Choose one...</option>
                                </select>
                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="item_select">{{ __('Item') }}</label>
                                <select class="form-control @error('item') is-invalid @enderror" id="item_select" name="item">
                                    <option disabled selected>Choose one...</option>
                                </select>
                                @error('item')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="currency_select">{{ __('Currency') }}</label>
                                <select class="form-control @error('currency') is-invalid @enderror" id="currency_select" name="currency">
                                    <option disabled selected>Choose one...</option>
                                </select>
                                @error('currency')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
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
        $(document).ready( function () {
            var headers = { 'Content-type': 'application/json', 'Authorization': 'Bearer '+localStorage.getItem('token') }
            Echo.channel('brand-registered')
            .listen('BrandRegisteredEvent', (e)=>{
                getBrands(headers)
            });
            Echo.channel('type-registered')
            .listen('TypeRegisteredEvent', (e)=>{
                getBrands(headers)
            });
            Echo.channel('item-registered')
            .listen('ItemRegisteredEvent', (e)=>{
                getBrands(headers)
            });
            Echo.channel('currency-registered')
            .listen('CurrencyRegisteredEvent', (e)=>{
                getCurrency(headers)
            });
            getTypes(headers)
            getBrands(headers)
            getItem(headers)
            getCurrency(headers)
        });
        function getBrands(headers){
            var brand_select = $('#brand_select').select2();
            $.ajax({
                url: route('brand.api_index'),
                headers: headers,
                success: function (response) {
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].brand;
                        added.innerHTML = response.data[i].brand;
                        brand_select.append(added);
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
        function getTypes(headers){
            var type_select = $('#type_select').select2();
            $.ajax({
                url: route('type.api_index'),
                headers: headers,
                success: function (response) {
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].type;
                        added.innerHTML = response.data[i].type;
                        type_select.append(added);
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
        function getItem(headers){
            var item_select = $('#item_select').select2();
            $.ajax({
                url: route('item.api_index'),
                headers: headers,
                success: function (response) {
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].item;
                        added.innerHTML = response.data[i].item;
                        item_select.append(added);
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
        function getCurrency(headers){
            var currency_select = $('#currency_select').select2();
            $.ajax({
                url: route('currency.api_index'),
                headers: headers,
                success: function (response) {
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].currency;
                        added.innerHTML = response.data[i].currency;
                        currency_select.append(added);
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
    </script>
@endpush

