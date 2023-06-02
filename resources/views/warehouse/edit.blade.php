@extends('adminlte::page')

@section('title', 'Warehouse')
@section('plugins.Select2', true)

@section('content_header')
    <h1 class="m-0 text-dark">Warehouse</h1>
@stop

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="card col-6">
                        <div class="card-body">
                            <form id="warehouse_form" method="POST" action="{{ route('warehouse.update',$warehouse) }}">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                    <label for="warehouse">{{ __('Warehouse') }}</label>
                                    <div class="col-md-6">
                                        <input id="warehouse" type="text" class="form-control @error('warehouse') is-invalid @enderror" name="warehouse" required placeholder="{{ $warehouse->warehouse }}">
                                        @error('warehouse')
                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <div class="col-md-4 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary btn-block" onclick="event.preventDefault(); document.getElementById('warehouse_form').submit();">
                                            {{ __('Save') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card col-6">
                        <div class="card-body">
                            <form id="tags_form" method="POST" action="{{ route('warehouse.store_tags',$warehouse) }}">
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
            Echo.channel('tag-registered')
            .listen('TagRegisteredEvent', (e)=>{
                getTags(headers)
            });
            getTags(headers);
        });

        function getTags(headers){
            var tags_select = $('#tags_select').select2();
            var tags = {!! json_encode($warehouse->tags) !!};
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
    </script>
@endpush

