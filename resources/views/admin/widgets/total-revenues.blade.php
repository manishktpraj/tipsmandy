

    <div class="row">
        <div class="col-xl-12">

            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Total Revenue
                            </h3>

                        </div>
                    </div>
					<div class="m-portlet__head-tools">
                        <form method="get" style="display: flex;">
                            <input type="hidden" name="r_start_date" value="{{request('r_start_date')}}">
                            <input type="hidden" name="r_end_date" value="{{request('r_end_date')}}">
    						<div class="m-input-icon m-input-icon--right date">
    							<input type="text" name="r" value="{{request('r')}}" class="form-control m-input datefilterrevenue" placeholder="Date Filter" readonly>
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
    						<span class="revenues_date_start" style="display:none;"></span>
    						<span class="revenues_date_end" style="display:none;"></span>
                        </form>
					</div>
                </div>
                <div class="m-portlet__body">
                    <div id="total_revenues_bars" style="height: 500px;"></div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
	
	<span  style="display:none;" id="hiddenlabelrevenues"></span>


@push('scripts')

<script>


    var total_revenues_bars = document.getElementById('total_revenues_bars');
    
	if (total_revenues_bars) {
        var revenues_bars = echarts.init(total_revenues_bars);
        revenues_bars.setOption({
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
                    name: 'Total Revenue',
                    type: 'bar',
                    barWidth: '30%',
                    //data: [100,20,30]
                    //data: [{{$revenuesArray}}]
                    @php
                        //$plans = DB::table('plans')->get();

                        function totalRevenues($planId) {
                            $r_start_date = request('r_start_date'); //
                            $r_end_date = request('r_end_date'); //
                            $datearr = [];
                            if(!empty($r_end_date) && !empty($r_start_date)) {
                                $datearr['start'] = $r_start_date;
                                //$datearr['end'] = date('Y-m-d', strtotime($r_end_date. ' + 1 day'));
                                $datearr['end'] = $r_end_date;
                            }

                            $getRevenues = DB::table('user_plans')->where('plan_id', $planId)->when($datearr, function ($query, $datearr) {
                                return $query->whereDate('user_plans.plan_start_date','>=',$datearr['start'])->whereDate('user_plans.plan_start_date','<=',$datearr['end']);
                            })->get();

                            $plansRevenues = 0;
                            foreach($getRevenues as $getRevenue) {
                                $plansRevenues +=$getRevenue->price ?? 0;
                            }
                            return $plansRevenues;
                        }
                    @endphp
                    data: [@foreach($plans as $plan){{totalRevenues($plan->id)}},@endforeach]
                }
            ]
        });
    }
	
	
	//Datepicker js start
	
	$('input[name="r"]').daterangepicker({
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
		$("#hiddenlabelrevenues").html(label);
	});

	$('input[name="r"]').on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
		$(".revenues_date_start").html(picker.startDate.format('DD-MM-YYYY'));
		$(".revenues_date_end").html(picker.endDate.format('DD-MM-YYYY'));
        $('input[name="r_start_date"]').val(picker.startDate.format('YYYY-MM-DD'));
        $('input[name="r_end_date"]').val(picker.endDate.format('YYYY-MM-DD'));
	});
	
	//Datepicker js end
</script>
@endpush
