@extends('adminlte::page')

@section('title', 'Brand')

@section('content_header')
    <h1 class="m-0 text-dark">Brand</h1>
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
                            <form id="form" method="POST" action="{{ route('brand.update',$brand) }}">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                    <label for="brand">{{ __('Brand') }}</label>

                                    <div class="col-md-6">
                                        <input id="brand" type="text" class="form-control @error('brand') is-invalid @enderror" name="brand" required placeholder="{{ $brand->brand }}">

                                        @error('brand')
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
                    @if($brand->tags->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Tags
                            </div>
                            <div class="card-body">
                            @foreach ($brand->tags as $tag)
                                {{ $tag }}
                            @endforeach
                        </div>
                    @endif
                    @if($brand->codes->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Codes
                            </div>
                            <div class="card-body">
                            @foreach ($brand->codes as $code)
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

