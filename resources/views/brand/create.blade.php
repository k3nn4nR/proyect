@extends('adminlte::page')

@section('title', 'Brand')

@section('content_header')
    <h1 class="m-0 text-dark">Brand Create</h1>
@stop

@section('adminlte_css')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@stop

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="form" method="POST" action="{{ route('brand.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="brand">{{ __('Brand') }}</label>

                            <div class="col-md-6">
                                <input id="brand" type="text" class="form-control @error('brand') is-invalid @enderror" name="brand" required autofocus>

                                @error('brand')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row ">
                            <div class="col-md-4 col-md-offset-4">
                                <button type="submit" class="btn btn-primary btn-block">
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
            
        });
    </script>
@endpush

