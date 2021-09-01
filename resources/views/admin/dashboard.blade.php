@extends('admin.layouts.master')

@section('subheader_title', 'Dashboard')
@push('css')
    <style type="text/css">
        ._m-portlet {
            -webkit-box-shadow: 0px 1px 15px 1px rgb(69 65 78 / 8%);
            box-shadow: 0px 1px 15px 1px rgb(69 65 78 / 8%);
            background-color: #00c5dc;
        }

        ._m-portlet .m-portlet__body {
            color: #fff;
        }

        i.fa.fa-user {
            font-size: 7em;
        }

        ._pt-100{
            padding-top: 0px;
        }
        ._pt-100 h3 {
            font-size: 18pt;
        }
        ._pt-100 p {
            font-size: 14pt;
        }
        p.different_sections{
           font-size: 10pt !important;
           _padding-top: 50px !important;
        }

        .m-portlet.m-portlet--bordered-semi .m-portlet__body {
            padding: 2.2rem 2.2rem;
        }

        .m-widget26 .m-widget26__number>small {
            color: #575962;
            padding-top: 12px;
        }
        .m-widget26 .m-widget26__number>small {
            margin-top: 0.3rem;
            display: block;
            font-size: 14pt;
            font-weight: 700;
        }

        .m-widget26 .m-widget26__number {
            font-size: 1.5rem;
            font-weight: 600;
        }

        i.font-size {
            font-size: 3.2rem;
            color: red;

        }
    </style>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
    .daterangepicker .calendar-table th, .daterangepicker .calendar-table td {
        line-height: 0 !important;
    }
    .daterangepicker select.monthselect {
        display: inline-block;
    }

    .daterangepicker select.yearselect {
        display: inline-block;
    }
    </style>
@endpush
@section('content')
    
    <div class="m-portlet _m-portlet">
        <div class="m-portlet__body m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="row m-portlet__body">
                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="col-md-9 col-lg-9 col-xl-9">
                            <div class="_pt-100">
                                <h3>Welcome to Admin Panel</h3>
                                <p>{{ Auth::guard('admin')->user()->email }}</p>
                                {{-- Show dashboard content only admin & subadmin --}}
                                @if(auth::guard('admin')->user()->is_role==0 || auth::guard('admin')->user()->is_role==1)
                                <p class="different_sections">You can manage different sections here</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Show dashboard content only admin & subadmin --}}
    @if(auth::guard('admin')->user()->is_role==0 || auth::guard('admin')->user()->is_role==1)

    <div class="row">
        <div class="col-xl-4">
            <div class="m-portlet m-portlet--bordered-semi">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="m-widget26">
                                <div class="m-widget26__number">
                                    <span class="subtitle">Total Tips</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <i class="flaticon-analytics font-size"></i>
                        </div>
                        <div class="col-xl-9">
                            <div class="m-widget26">
                                <div class="m-widget26__number">
                                    <small class="text-right">{{$totalTips}}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="m-portlet m-portlet--bordered-semi">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="m-widget26">
                                <div class="m-widget26__number">
                                    <span class="subtitle">Total Staff</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <i class="flaticon-user-ok font-size"></i>
                        </div>
                        <div class="col-xl-9">
                            <div class="m-widget26">
                                <div class="m-widget26__number">
                                    <small class="text-right">{{$totalStaffMembers}}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="m-portlet m-portlet--bordered-semi">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="m-widget26">
                                <div class="m-widget26__number">
                                    <span class="subtitle">Total Members</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <i class="flaticon-users font-size"></i>
                        </div>
                        <div class="col-xl-9">
                            <div class="m-widget26">
                                <div class="m-widget26__number">
                                    <small class="text-right">{{$totalMembers}}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- tips-added-day-wise widgets start -->
    @include('admin.widgets.tips-added-day-wise')
    <!-- tips-added-day-wise widgets end -->

    <!-- Total plan sold widgets start -->
    <div class="row">
        <div class="col-xl-12">
            @include('admin.widgets.total-plans-sold')
        </div>
    </div>
    <!-- Total plan sold widgets end -->

	<span  style="display:none;" id="hiddenlabel"></span>
    
    <!-- Total revenues widgets start -->
    @include('admin.widgets.total-revenues')
    <!-- Total revenues widgets end -->
	
    @endif

@endsection
@push('js')
{{-- Show dashboard content only admin & subadmin --}}
@if(auth::guard('admin')->user()->is_role==0 || auth::guard('admin')->user()->is_role==1)
    <script src="{{asset('public/assets/js/echarts.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endif
@endpush
