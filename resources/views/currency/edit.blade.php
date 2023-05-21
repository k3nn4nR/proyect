@extends('adminlte::page')

@section('title', 'Currency')

@section('content_header')
    <h1 class="m-0 text-dark">Currency</h1>
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
                            <form id="form" method="POST" action="{{ route('currency.update',$currency) }}">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                    <label for="currency">{{ __('currency') }}</label>

                                    <div class="col-md-6">
                                        <input id="currency" type="text" class="form-control @error('currency') is-invalid @enderror" name="currency" required placeholder="{{ $currency->currency }}">

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
                    @if($currency->tags->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Tags
                            </div>
                            <div class="card-body">
                            @foreach ($currency->tags as $tag)
                                {{ $tag }}
                            @endforeach
                        </div>
                    @endif
                    @if($currency->codes->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Codes
                            </div>
                            <div class="card-body">
                            @foreach ($currency->codes as $code)
                                {{ $parent }}
                            @endforeach
                        </div>
                    @endif
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

