@extends('adminlte::page')

@section('title', 'Payment')

@section('content_header')
    <h1 class="m-0 text-dark">Payment</h1>
@stop

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card">
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
                        <div class="card-body">
                            {{ $payment->tags }}
                        </div>
                        <div class="card-body">
                            {{ $payment->codes_of_payment }}
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($payment->codes_paid && $payment->codes_paid->isNotEmpty())
                                @foreach ($payment->codes_paid as $code)
                                    <tr>
                                        <td>{{ $code->code }}</td>
                                        <td>{{ $code->pivot->amount}}</td>
                                        <td>{{ $code->pivot->price}}</td>
                                        <td>{{ $code->pivot->subtotal}}</td>
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
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
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
            getCurrencies(headers)
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
    </script>
@endpush
