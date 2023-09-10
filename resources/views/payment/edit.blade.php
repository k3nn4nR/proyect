@extends('adminlte::page')

@section('title', 'Payment')
@section('plugins.Select2', true)
@section('plugins.Datatables', true)

@section('content_header')
    <h1 class="m-0 text-dark">Payment</h1>
@stop

@section('content')

    <div class="container col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="card-body">
                                <form id="form" method="POST" action="{{ route('payment.update',$payment) }}">
                                    @method('PUT')
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
                                        <div class="col-md-6">
                                            <label for="currency_select">{{ __('Total') }}</label>
                                            <input type="text" name="total" id="total" value="{{$payment->total}}">
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
                        <div class="row">
                            <div class="col">
                                <div class="card-body">
                                    <form id="tags_form" method="POST" action="{{ route('payment.store_tags',$payment) }}">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="tags_select">{{ __('Tag') }}</label>
                                                <select class="form-control @error('tags') is-invalid @enderror" id="tags_select" name="tags[]" multiple="multiple">
                                                </select>
                                                @error('tags')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <div class="col-md-4 col-md-offset-4">
                                                <button type="submit" class="btn btn-primary btn-block" onclick="event.preventDefault(); document.getElementById('tags_form').submit();">
                                                    {{ __('Save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col">
                                {{ $payment->codes_of_payment }}
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="exampleInputFile">File input</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="">Upload</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-stripped" id="myTable">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($payment->types && $payment->types->isNotEmpty())
                            @foreach ($payment->types as $type)
                                <tr>
                                    <td>{{ $type->type }}</td>
                                    <td>{{ $type->pivot->amount}}</td>
                                    <td>{{ $type->pivot->price}}</td>
                                    <td>{{ $type->pivot->subtotal}}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="{{ $type->type }}">
                                            <i class="fas fa-fw fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        @if($payment->items && $payment->items->isNotEmpty())
                            @foreach ($payment->items as $item)
                                <tr>
                                    <td>{{ $item->item }}</td>
                                    <td>{{ $item->pivot->amount}}</td>
                                    <td>{{ $item->pivot->price}}</td>
                                    <td>{{ $item->pivot->subtotal}}</td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        @if($payment->services && $payment->services->isNotEmpty())
                            @foreach ($payment->services as $service)
                                <tr>
                                    <td>{{ $service->service }}</td>
                                    <td>{{ $service->pivot->amount}}</td>
                                    <td>{{ $service->pivot->price}}</td>
                                    <td>{{ $service->pivot->subtotal}}</td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('payment.type_code',$payment) }}">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">{{ __('Recipient')}}:</label>
                        <input type="text" class="form-control" name="type" id="recipient-name">
                    </div>
                    <div class="form-group">
                        <label for="code">{{ __('Code') }}</label>
                        <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" required autofocus>
                        @error('code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <br>
                    insertarlo en el inventario
                    <br>
                    mostrar el boton hasta que se hayan ingresado tantos codigos como la cantidad adquirida
                    <div class="form-group row ">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> 
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary" onclick="customModalSubmitFunction('modalID')">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
    <script>
         $(document).ready( function () {
            $('#exampleModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var recipient = button.data('whatever') // Extract info from data-* attributes
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                var modal = $(this)
                modal.find('.modal-title').text('Add code to ' + recipient)
                modal.find('.modal-body #recipient-name').val(recipient)
            })
            let headers = { 'Content-type': 'application/json', 'Authorization': 'Bearer '+localStorage.getItem('token') };
            getCompanies(headers)
            getCurrencies(headers)
            $('#myTable').DataTable({})
            Echo.channel('tag-registered')
            .listen('TagRegisteredEvent', (e)=>{
                getTags(headers)
            });
            getTags(headers);
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
                    document.getElementById('company_select').value="{{ $payment->company->company }}"
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
                    document.getElementById('currency_select').value="{{ $payment->currency->currency }}"
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
        function getTags(headers){
            var tags_select = $('#tags_select').select2();
            var tags = {!! json_encode($payment->tags) !!};
            $.ajax({
                url: route('tag.api_index'),
                headers: headers,
                success: function (response) {
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].tag;
                        added.innerHTML = response.data[i].tag;
                        if(tags.some(el => el.tag === response.data[i].tag))
                            added.selected = true
                        tags_select.append(added);
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
        function customModalSubmitFunction(){
            $.ajax({
                type: "POST",
                url: route('payment.type_code',$payment),
            });
        }
    </script>
@endpush
