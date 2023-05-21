@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('plugins.Sweetalert2', true)

@section('adminlte_css')
    @stack('css')
    @yield('css')

@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Preloader Animation --}}
        @if($layoutHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@vite(['resources/js/app.js'])
@routes

@section('adminlte_js')
    @stack('js')
    @yield('js')
    <script>
        $(document).ready( function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Echo.channel('brand-registered')
            .listen('BrandRegisteredEvent', (e)=>{
                Toast.fire({
                    type: 'success',
                    title: e.message
                })
            });
            
            Echo.channel('item-registered')
            .listen('ItemRegisteredEvent', (e)=>{
                Toast.fire({
                    type: 'success',
                    title: e.message
                })
            });
            
            Echo.channel('type-registered')
            .listen('TypeRegisteredEvent', (e)=>{
                Toast.fire({
                    type: 'success',
                    title: e.message
                })
            });

            Echo.channel('code-registered')
            .listen('CodeRegisteredEvent', (e)=>{

                Toast.fire({
                    type: 'success',
                    title: e.message
                })
            });
            
            Echo.channel('tag-registered')
            .listen('TagRegisteredEvent', (e)=>{
                Toast.fire({
                    type: 'success',
                    title: e.message
                })
            });

            Echo.channel('service-registered')
            .listen('ServiceRegisteredEvent', (e)=>{
                Toast.fire({
                    type: 'success',
                    title: e.message
                })
            });

            Echo.channel('payment-registered')
            .listen('PaymentRegisteredEvent', (e)=>{
                Toast.fire({
                    type: 'success',
                    title: e.message
                })
            });

            Echo.channel('currency-registered')
            .listen('CurrencyRegisteredEvent', (e)=>{
                Toast.fire({
                    type: 'success',
                    title: e.message
                })
            });

            Echo.channel('company-registered')
            .listen('CompanyRegisteredEvent', (e)=>{
                Toast.fire({
                    type: 'success',
                    title: e.message
                })
            });
        });
    </script>
@stop
