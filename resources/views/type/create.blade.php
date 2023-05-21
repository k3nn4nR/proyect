@extends('adminlte::page')

@section('title', 'Type')

@section('plugins.Select2', true)

@section('content_header')
    <h1 class="m-0 text-dark">Type Create</h1>
@stop

@section('adminlte_css')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@stop

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="form" method="POST" action="{{ route('type.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="type">{{ __('Type') }}</label>
                            <div class="col-md-6">
                                <input id="type" type="text" class="form-control @error('type') is-invalid @enderror" name="type" required autofocus>
                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="select">{{ __('Select') }}</label>
                            <div class="col-md-6">
                                <select class="form-control @error('brand') is-invalid @enderror" id="select" name="brand" data-placeholder="Click to Choose...">
                                </select>
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
        </div>
    </div>
</div>
@stop

@push('js')
    <script>
        $(document).ready( function () {
            $('#select').select2();
            Echo.channel('brand-registered')
            .listen('BrandRegisteredEvent', (e)=>{
                getBrands()
            });
            getBrands()
        });
        function getBrands(){
            $.ajax({
                url: route('brand.api_index'),
                headers: { 'Content-type': 'application/json', 'Authorization': 'Bearer '+localStorage.getItem('token') },
                success: function (response) {
                    var select = $('#select');
                    for (var i = 0; i < response.data.length; i++){
                        var added = document.createElement('option');
                        added.value = response.data[i].brand;
                        added.innerHTML = response.data[i].brand;
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

