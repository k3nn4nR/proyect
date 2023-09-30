@extends('adminlte::page')

@section('title', 'AdminLTE')
@section('plugins.ApexCharts', true)

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3 id="MontlyPurchase"></h3>
                                    <p>Montly Purchases</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <a href="{{ route('payment.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3 id="LastPurchase"></h3>
                                    <p>Last Purchase</p>
                                </div>
                                <div class="icon">
                                <i class="fas fa-dollar-sign"></i>
                                </div>
                                <a href="{{ route('payment.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>44</h3>
                                    <p>User Registrations</p>
                                </div>
                                <div class="icon">
                                    <i class="icon icon-person-add"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>65</h3>
                                    <p>Unique Visitors</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="chart"></div>
                        </div>
                        <div class="col">
                            <div id="chart1"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="chart2"></div>
                        </div>
                        <div class="col">
                            <div id="chart3"></div>
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
        let headers = { 'Content-type': 'application/json', 'Authorization': 'Bearer '+localStorage.getItem('token') };
        if("{{ session('token') }}")
        {
            localStorage.setItem('token',"{{ session('token') }}".split('|')[1]);
        }
        getPaymentsLastMonth(headers)
        getPaymentsLastSixMonth(headers)
        getCurrentGoods(headers)
        getTagGoods(headers)
        Echo.channel('payment-registered')
        .listen('PaymentRegisteredEvent', (e)=>{
            getPaymentsLastMonth(headers)
        });

        Echo.channel('payment-registered')
        .listen('PaymentRegisteredEvent', (e)=>{
            getPaymentsLastSixMonth(headers)
        });

        Echo.channel('payment-registered')
        .listen('PaymentRegisteredEvent', (e)=>{
            getCurrentGoods(headers)
        });

        Echo.channel('payment-registered')
        .listen('PaymentRegisteredEvent', (e)=>{
            getTagGoods(headers)
        });
    });

    function getPaymentsLastMonth(headers){
        $.ajax({
            url: route('payment.api_payments_last_month'),
            headers: headers,
            success: function (response) {
                $("#MontlyPurchase").html(response.data.length)
                $("#LastPurchase").html(response.data[response.data.length-1].total+" "+response.data[response.data.length-1].code)
            }
        })
    }

    function getPaymentsLastSixMonth(headers){
        $.ajax({
            url: route('payment.api_payments_last_six_months'),
            headers: headers,
            success: function (response) {
                var options3 = {
                    series: [{
                        name: 'Payment',
                        data: response.data
                    }],
                    chart: {
                        type: 'area',
                        stacked: false,
                        height: 350,
                        zoom: {
                            type: 'x',
                            enabled: true,
                            autoScaleYaxis: true
                        },
                        toolbar: {
                            autoSelected: 'zoom'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    markers: {
                        size: 0,
                    },
                    title: {
                        align: 'left'
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            inverseColors: false,
                            opacityFrom: 0.5,
                            opacityTo: 0,
                            stops: [0, 90, 100]
                        },
                    },
                    yaxis: {
                        title: {
                            text: 'Ammount'
                        },
                    },
                    xaxis: {
                        type: 'datetime',
                    },
                }
                var chart3 = new ApexCharts(document.querySelector("#chart3"), options3);
                chart3.render();
            }
        })
    }

    function getCurrentGoods(headers){
        $.ajax({
            url: route('inventory.api_current_goods'),
            headers: headers,
            success: function (response) {
                var options = {
                    chart: {
                        type: 'bar',
                        events: {
                            dataPointSelection: function(event, response, config) {
                                window.location = '/item/'+response.data.twoDSeriesX[config.dataPointIndex]+'/edit'
                            }
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true
                        }
                    },
                    series: [{data: response.data}],                    
                }
                var chart2 = new ApexCharts(document.querySelector("#chart2"), options);
                chart2.render();
            }
        })
    }

    function getTagGoods(headers){
        $.ajax({
            url: route('tag.api_current_tag_goods'),
            headers: headers,
            success: function (response) {
                var options = {
                    chart: {
                        type: 'bar',
                        events: {
                            dataPointSelection: function(event, response, config) {
                                window.location = '/tag/'+response.data.twoDSeriesX[config.dataPointIndex]+'/edit'
                            }
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true
                        }
                    },
                    series: [{data: response.data}],                    
                }
                var chart1 = new ApexCharts(document.querySelector("#chart1"), options);
                chart1.render();
            }
        })
    }
</script>
@endpush