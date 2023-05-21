@extends('adminlte::page')

@section('title', 'Company')

@section('content_header')
    <h1 class="m-0 text-dark">Company</h1>
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
                            <form id="form" method="POST" action="{{ route('company.update',$company) }}">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                    <label for="company">{{ __('Company') }}</label>

                                    <div class="col-md-6">
                                        <input id="company" type="text" class="form-control @error('company') is-invalid @enderror" name="company" required placeholder="{{ $company->company }}">

                                        @error('company')
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
                    @if($company->tags->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Tags
                            </div>
                            <div class="card-body">
                            @foreach ($company->tags as $tag)
                                {{ $tag }}
                            @endforeach
                        </div>
                    @endif
                    @if($company->codes->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Codes
                            </div>
                            <div class="card-body">
                            @foreach ($company->codes as $code)
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

