@extends('adminlte::page')

@section('title', 'Type')

@section('content_header')
    <h1 class="m-0 text-dark">Type</h1>
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
                            {{ $type }}
                        </div>
                    </div>
                    @if($type->tags->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Tags
                            </div>
                            <div class="card-body">
                            @foreach ($type->tags as $tag)
                                {{ $tag }}
                            @endforeach
                        </div>
                    @endif
                    @if($type->codes->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Codes
                            </div>
                            <div class="card-body">
                            @foreach ($type->codes as $tag)
                                {{ $tag }}
                            @endforeach
                        </div>
                    @endif
                    @if($type->brand)
                        <div class="card">
                            <div class="card-header">
                                Brand
                            </div>
                            <div class="card-body">
                            {{ $type->brand }}
                        </div>
                    @endif
                    @if($type->payments->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Payment
                            </div>
                            <div class="card-body">
                            @foreach ($type->payments as $tag)
                                {{ $tag }}
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

