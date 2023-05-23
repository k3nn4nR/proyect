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
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <form id="form" method="POST" action="{{ route('payment.update',$payment) }}">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                    <label for="payment">{{ __('Payment') }}</label>

                                    <div class="col-md-6">
                                        <input id="payment" type="text" class="form-control @error('payment') is-invalid @enderror" name="payment" required placeholder="{{ $payment->payment }}">

                                        @error('payment')
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
                    @if($payment->tags && $payment->tags->isNotEmpty())
                        @foreach ($payment->tags as $tag)
                            {{ $tag }}
                        @endforeach
                    @endif
                    <table class="table">
                        <tbody>
                            @if($payment->codes && $payment->codes->isNotEmpty())
                                @foreach ($payment->codes as $code)
                                    <tr>
                                        <td>{{ $code->code }}</td>
                                        <td>{{ $code->pivot->amount}}</td>
                                        <td>{{ $code->pivot->quantity}}</td>
                                        <td>{{ $code->pivot->subtotal}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @if($payment->items && $payment->items->isNotEmpty())
                                @foreach ($payment->items as $item)
                                    <tr>
                                        <td>{{ $item->item }}</td>
                                        <td>{{ $item->pivot->amount}}</td>
                                        <td>{{ $item->pivot->quantity}}</td>
                                        <td>{{ $item->pivot->subtotal}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @if($payment->services && $payment->services->isNotEmpty())
                                @foreach ($payment->services as $service)
                                    <tr>
                                        <td>{{ $service->service }}</td>
                                        <td>{{ $service->pivot->amount}}</td>
                                        <td>{{ $service->pivot->quantity}}</td>
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
            
        });
    </script>
@endpush

