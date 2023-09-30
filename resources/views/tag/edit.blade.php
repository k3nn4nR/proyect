@extends('adminlte::page')

@section('title', 'Tag')
@section('plugins.Select2', true)

@section('content_header')
    <h1 class="m-0 text-dark">Tag</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="card col-6">
                        <div class="card-body">
                            <form id="tag_form" method="POST" action="{{ route('tag.update',$tag) }}">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                    <label for="tag">{{ __('Tag') }}</label>
                                    <div class="col-md-6">
                                        <input id="tag" type="text" class="form-control @error('tag') is-invalid @enderror" name="tag" required placeholder="{{ $tag->tag }}">
                                        @error('tag')
                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <div class="col-md-4 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary btn-block" onclick="event.preventDefault(); document.getElementById('tag_form').submit();">
                                            {{ __('Save') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card col-6">
                        <div class="card-body">
                            <form id="tags_form" method="POST" action="{{ route('tag.store_tags',$tag) }}">
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
                <div class="row">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="checkboxPrimary3">
                                        <label for="checkboxPrimary3">Primary checkbox</label>
                                    </div>
                                </div>
                            </div>
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
            getItems(headers);
        });

        function getTags(headers){
            var tags_select = $('#tags_select').select2();
            var tags = {!! json_encode($tag->tags) !!};
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

        function getItems(headers){
            $.ajax({
                url: route('tag.api_tag_items',{!! json_encode($tag->tag) !!}),
                headers: headers,
                success: function (response) {
                   
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
    </script>
@endpush

