            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Total Plans Sold
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <form method="get" style="display: flex;">
                            <input type="hidden" name="plan_sold_start_date" value="{{request('plan_sold_start_date')}}">
                            <input type="hidden" name="plan_sold_end_date" value="{{request('plan_sold_end_date')}}">
                            <div class="m-input-icon m-input-icon--right date" id="datetimepicker1">
                                <input type="text" name="datefilter" value="{{request('datefilter')}}" class="form-control m-input datefilter" placeholder="Date Filter" readonly>
                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="fa fa-calendar-alt"></i></span></span>
                            </div>
                            &nbsp;&nbsp;
                            <button type="submit" class="btn btn-sm btn-accent m-btn m-btn--custom m-btn--icon">
                               <span>
                                    <i class="la la-search"></i>
                                    <span>Apply</span>
                                </span>
                            </button>
                            &nbsp;&nbsp;
                            &nbsp;&nbsp;
                            <a href="{{route('admin.dashboard')}}" class="btn btn-sm btn-secondary m-btn m-btn--icon">
                                <span>
                                    <i class="la la-close"></i>
                                    <span>Reset</span>
                                </span>
                            </a>
                            <span class="datestart" style="display:none;"></span>
                            <span class="endstart" style="display:none;"></span>
                        </form>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div id="plans_sold_bars" style="height: 500px;"></div>
                </div>
            </div>
            <!--end::Portlet-->
@push('scripts')
    <script>
        var plans_sold_bars = document.getElementById('plans_sold_bars');
        if (plans_sold_bars) {
            var plans_sold_basic = echarts.init(plans_sold_bars);
            plans_sold_basic.setOption({
                color: ['#00c5dc'],
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        data: [{!!$plansArray!!}],
                        axisTick: {
                            alignWithLabel: true
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: 'Plan Sold',
                        type: 'bar',
                        barWidth: '30%',
                        //data: [{{$plansSoldArray}}]
                        @php
                        function totalPlanSold($planId) {
                            $plan_sold_start_date = request('plan_sold_start_date'); //
                            $plan_sold_end_date = request('plan_sold_end_date'); //
                            $datearr = [];
                            if(!empty($plan_sold_end_date) && !empty($plan_sold_start_date)) {
                                $datearr['start'] = $plan_sold_start_date;
                                //$datearr['end'] = date('Y-m-d', strtotime($plan_sold_end_date. ' + 1 day'));
                                $datearr['end'] = $plan_sold_end_date;
                            }

                            $getTotalPlanSold = DB::table('user_plans')->where('plan_id', $planId)->when($datearr, function ($query, $datearr) {
                                return $query->whereDate('user_plans.plan_start_date','>=',$datearr['start'])->whereDate('user_plans.plan_start_date','<=',$datearr['end']);
                            })->count();

                            return $getTotalPlanSold;
                        }
                        @endphp
                        data: [@foreach($plans as $plan){{totalPlanSold($plan->id)}},@endforeach]
                    }
                ]
            });
        }

        //Datepicker js start
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                  cancelLabel: 'Clear',
                  format: 'DD-MM-YYYY'
            },

            showDropdowns: true,
            changeMonth: false,
            minYear: 1999,
            maxYear: parseInt(moment().format('YYYY'),10),

            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
            $("#hiddenlabel").html(label);
        });

        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            $(".datestart").html(picker.startDate.format('DD-MM-YYYY'));
            $(".endstart").html(picker.endDate.format('DD-MM-YYYY'));
            $('input[name="plan_sold_start_date"]').val(picker.startDate.format('YYYY-MM-DD'));
            $('input[name="plan_sold_end_date"]').val(picker.endDate.format('YYYY-MM-DD'));
        });
        //Datepicker js end
    </script>
@endpush
