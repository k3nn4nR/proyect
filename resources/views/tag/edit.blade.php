@extends('adminlte::page')

@section('title', 'Tag')

@section('content_header')
    <h1 class="m-0 text-dark">Tag</h1>
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
                            {{ $tag }}
                        </div>
                    </div>
                    @if($tag->childs->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Childs
                            </div>
                            <div class="card-body">
                            @foreach ($tag->childs as $child)
                                {{ $child }}
                            @endforeach
                        </div>
                    @endif
                    @if($tag->parents->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Parents
                            </div>
                            <div class="card-body">
                            @foreach ($tag->parents as $parent)
                                {{ $parent }}
                            @endforeach
                        </div>
                    @endif
                    @if($tag->items->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Items
                            </div>
                            <div class="card-body">
                            @foreach ($tag->items as $item)
                                {{ $item }}
                            @endforeach
                        </div>
                    @endif
                    @if($tag->brands->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Brands
                            </div>
                            <div class="card-body">
                            @foreach ($tag->brands as $brand)
                                {{ $brand }}
                            @endforeach
                        </div>
                    @endif
                    @if($tag->services->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Services
                            </div>
                            <div class="card-body">
                            @foreach ($tag->services as $service)
                                {{ $service }}
                            @endforeach
                        </div>
                    @endif
                    @if($tag->types->isNotEmpty())
                        <div class="card">  
                            <div class="card-header">
                                Types
                            </div>
                            <div class="card-body">
                            @foreach ($tag->types as $type)
                                {{ $type }}
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

