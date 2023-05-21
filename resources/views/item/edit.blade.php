@extends('adminlte::page')

@section('title', 'Item')

@section('content_header')
    <h1 class="m-0 text-dark">Item</h1>
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
                            {{ $item }}
                        </div>
                    </div>
                    @if($item->tags->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                Tags
                            </div>
                            <div class="card-body">
                            @foreach ($item->tags as $tag)
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

