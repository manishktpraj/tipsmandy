<!--Begin::Section-->
    <div class="row">
        <div class="col-xl-8">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Tips Added Day Wise
                            </h3>
                        </div>
                    </div>
					<div class="m-portlet__head-tools">
						
						<div class="m-input-icon m-input-icon--right date">
							<input type="text" name="datefiltertipsdayswise" class="form-control m-input datefiltertipsdayswise" placeholder="Date Filter" readonly>
							<span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="fa 	fa-calendar-alt"></i></span></span>
						</div>
						&nbsp;&nbsp;
						<button class="btn btn-sm btn-accent m-btn m-btn--custom m-btn--icon">
                           <span>
                                <i class="la la-search"></i>
                                <span>Apply</span>
                            </span>
                        </button>
						&nbsp;&nbsp;
						&nbsp;&nbsp;
                        <a href="javascript:;" class="btn btn-sm btn-secondary m-btn m-btn--icon">
                            <span>
                                <i class="la la-close"></i>
                                <span>Reset</span>
                            </span>
                        </a>
						<span class="tips_added_day_wise_date_start" style="display:none;"></span>
						<span class="tips_added_day_wise_date_end" style="display:none;"></span>
						
					</div>
                </div>
                <div class="m-portlet__body">
                    <div id="bars_basic" style="height: 500px;"></div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>

        <div class="col-xl-4">
            <!--begin:: Widgets/Inbound Bandwidth-->
            <div class="m-portlet m-portlet--bordered-semi m-portlet--half-height m-portlet--fit " style="min-height: 300px">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Target Achieved
                            </h3>
                        </div>
                    </div>
                    @if(count($sources))
                    <div class="m-portlet__head-tools">
                        <form method="get" id="target_achieved_source_form">
                            <select class="form-control" name="source" onchange="searchFilter();">
                                <option value="">Select</option>
                                @foreach($sources as $source)
                                    <option value="{{$source->id}}" {{ request('source') == $source->id ? 'selected' : '' }}>{{$source->name}}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    @endif
                </div>
                <div class="m-portlet__body">
                    <!--begin::Widget5-->
                    <div class="m-widget20">
                        <div class="m-widget20__number m--font-success">{{$tipsAchived}}</div>
                        <div class="m-widget20__chart" style="height:160px;">
                            <canvas id="_m_chart_bandwidth1"></canvas>
                        </div>
                    </div>
                    <!--end::Widget 5-->
                </div>
            </div>

            <!--end:: Widgets/Inbound Bandwidth-->
            <div class="m--space-30"></div>
        </div>

    </div>
	
	<span  style="display:none;" id="hiddenlabeltipsaddeddaywise"></span>
	
@push('scripts')

<script>

    function searchFilter(){
        document.forms['target_achieved_source_form'].submit();
    }
    jQuery(document).ready(function() {

        //Target achieved js start..
        if (0 != $("#_m_chart_bandwidth1").length) {

            var e = document.getElementById("_m_chart_bandwidth1").getContext("2d"),
                t = e.createLinearGradient(0, 0, 0, 240);
            t.addColorStop(0, Chart.helpers.color("#d1f1ec").alpha(1).rgbString()), t.addColorStop(1, Chart.helpers.color("#d1f1ec").alpha(.3).rgbString());
            var a = {
                type: "line",
                data: {
                    labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October"],
                    datasets: [{
                        label: "Tips Achieved",
                        backgroundColor: t,
                        borderColor: mApp.getColor("success"),
                        pointBackgroundColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                        pointBorderColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                        pointHoverBackgroundColor: mApp.getColor("danger"),
                        pointHoverBorderColor: Chart.helpers.color("#000000").alpha(.1).rgbString(),
                        @php
                        //$tipsTargets = DB::table('tips_targets')->select(DB::raw("(COUNT(*)) as count"),DB::raw("MONTHNAME(created_at) as monthname"))->whereYear('created_at', date('Y'))->groupBy('monthname')->get();
                        //echo $tipsTargets;
                        function tipsAchieved($month) {
                            //return DB::table('tips_targets')->whereMonth('created_at', $month)->count();
                            $source = request('source');

                            return DB::table('tips')
                                    ->join('tips_targets', 'tips.id', '=', 'tips_targets.tip_id')
                                    ->select('tips.*', 'tips_targets.tip_id')
                                    ->when($source, function ($query, $source) {
                                        return $query->where('tips.source_id', $source);
                                    })
                                    ->whereMonth('tips_targets.created_at', $month)
                                    ->count();
                            /*return = DB::table('tips')
                                    ->join('tips_targets', 'tips.id', '=', 'tips_targets.tip_id')
                                    ->select('tips.*', 'tips_targets.tip_id')
                                    ->count();*/
                        }
                        @endphp
                        data: [@for($i = 1; $i <= 12; $i++){{tipsAchieved(str_pad($i, 2, '0', STR_PAD_LEFT))}},@endfor]
                    }]
                },
                options: {
                    title: {
                        display: !1
                    },
                    tooltips: {
                        mode: "nearest",
                        intersect: !1,
                        position: "nearest",
                        xPadding: 10,
                        yPadding: 10,
                        caretPadding: 10
                    },
                    legend: {
                        display: !1
                    },
                    responsive: !0,
                    maintainAspectRatio: !1,
                    scales: {
                        xAxes: [{
                            display: !1,
                            gridLines: !1,
                            scaleLabel: {
                                display: !0,
                                labelString: "Month"
                            }
                        }],
                        yAxes: [{
                            display: !1,
                            gridLines: !1,
                            scaleLabel: {
                                display: !0,
                                labelString: "Value"
                            },
                            ticks: {
                                beginAtZero: !0
                            }
                        }]
                    },
                    elements: {
                        line: {
                            tension: 1e-7
                        },
                        point: {
                            radius: 4,
                            borderWidth: 12
                        }
                    },
                    layout: {
                        padding: {
                            left: 0,
                            right: 0,
                            top: 10,
                            bottom: 0
                        }
                    }
                }
            };
            new Chart(e, a)
        };
        //Target achieved js end..
    });

    //Tips added day wise js start..
    var bars_basic_element = document.getElementById('bars_basic');
    if (bars_basic_element) {
        var bars_basic = echarts.init(bars_basic_element);
        bars_basic.setOption({
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
                    //data: ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30'],
                    data: [@for($i = 1; $i <= $daysInMonth; $i++)'{{$i}}',@endfor],
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
                    name: 'Total Tips',
                    type: 'bar',
                    barWidth: '40%',
                    //data: [100,20,30,30,30,30,30,30,30,30,30,30,30,30,60,2,150,6,5,9,52,75,36,78,58,5,8,7,8,1]
                    @php
                        function tipsCount($createdAt, $year) {
                            return DB::table('tips')->whereDate('created_at', $createdAt)->count();
                        }
                        //$tips = DB::table('tips')->whereMonth('created_at', 6)->whereYear('created_at', \Carbon\Carbon::now()->format('Y'))->get();
                        //$tips = DB::table('tips')->select('id', 'created_at')->whereMonth('created_at', 6)->get();
                        //$tips = DB::table('tips')->select('id', 'created_at')->whereMonth('created_at', 6)->get();
                        $usermcount = [];
                        $userArr = [];
                        foreach ($daywiseTips as $key => $value) {
                            $usermcount[(int)$key] = $value;
                        }
                        $currentMonthYear = date('Y-m');
                        //$currentMonthYear = date('Y');
                        //dd($currentMonthYear);

                    @endphp
                    //data: [@for($i = 1; $i <= $daysInMonth; $i++){{tipsCount($currentMonthYear.'06'.str_pad($i, 2, '0', STR_PAD_LEFT), \Carbon\Carbon::now()->format('Y'))}},@endfor]
                    data: [@for($i = 1; $i <= $daysInMonth; $i++){{tipsCount($currentMonthYear.str_pad($i, 2, '0', STR_PAD_LEFT), \Carbon\Carbon::now()->format('Y'))}},@endfor]
                }
            ]
        });
    };
    //Tips added day wise js end..

    //Datepicker js start
	
	$('input[name="datefiltertipsdayswise"]').daterangepicker({
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
		$("#hiddenlabeltipsaddeddaywise").html(label);
	});

	$('input[name="datefiltertipsdayswise"]').on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
		$(".tips_added_day_wise_date_start").html(picker.startDate.format('DD-MM-YYYY'));
		$(".tips_added_day_wise_date_end").html(picker.endDate.format('DD-MM-YYYY'));
	});
	
	//Datepicker js end
</script>
@endpush
