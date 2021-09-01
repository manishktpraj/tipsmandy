@extends('admin.layouts.master')

@section('subheader_title', $pageTitle)

@section('content_subheader')
<!-- BEGIN: Subheader -->
<li class="m-nav__separator">-</li>
<li class="m-nav__item">
    <a href="javascript:;" class="m-nav__link">
        <span class="m-nav__link-text">Dashboard</span>
    </a>
</li>
<li class="m-nav__separator">-</li>
<li class="m-nav__item">
    <a href="" class="m-nav__link">
        <span class="m-nav__link-text">{{$subTitle}}</span>
    </a>
</li>
<!-- END: Subheader -->
@endsection

@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        {{$subTitle}}
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        {!! $backButtons !!}
                    </li>
                </ul>
            </div>
        </div>

        <!--begin::Form-->
        <form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.tips.edit', $tip->id)}}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="m-portlet__body">

                <div class="form-group m-form__group row">
                    <label class="col-form-label col-lg-3 col-sm-12">Segments *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <select id="segments" name="segment" class="form-control" required="required">
                            <option value="">Select Segment</option>
                            @foreach($segments as $segment)
                            <option {{ old('segment', $tip->segment ?? '') == $segment ? 'selected' : '' }} value="{{$segment}}">{{$segment}}</option>
                            @endforeach
                        </select>
                        @error('segment')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @php
                    $commoditywrapperDisplay = 'none';
                    if($tip->segment=='Commodity') {
                        $commoditywrapperDisplay = '';
                    }
					$currencywrapperDisplay = 'none';
                    if($tip->segment=='Currency') {
                        $currencywrapperDisplay = '';
                    }
                @endphp
                @include('admin.tips.partials.edit-commodity')

                @php
                    $symbolsrapidapiwrapperDisplay = 'none';
                    if($tip->segment=='Delivery' || $tip->segment=='Intraday') {
                        $symbolsrapidapiwrapperDisplay = '';
                    }
                @endphp
                <div id="symbolsrapidapiwrapper" style="display: {{$symbolsrapidapiwrapperDisplay}};">

                    <div class="form-group m-form__group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Symbol *</label>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <select id="symbols" name="symbolVal" class="form-control">
                                <option value="">Select Symbol</option>
                                @php
                                    $symbolNs = explode(".", $tip->symbols);
                                    $symbol = $symbolNs['0'] ?? '';
                                    $symbolVal = $symbolNs['1'] ?? '';  //Null Coalescing Operator
                                @endphp
                                <option {{ old('symbolVal', $symbolVal) == 'NS' ? 'selected' : '' }} value="NS">NSE</option>
                                <option {{ old('symbolVal', $symbolVal) == 'BO' ? 'selected' : '' }} value="BO">BSE</option>
                            </select>
                            @error('symbols')
                            <div class="form-control-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Symbol *</label>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <input type="text" class="form-control m-input" value="{{ old('symbol', $tip->symbol ?? '') }}" id="symbol" placeholder="Symbol">
                            @error('symbol')
                            <div class="form-control-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <a href="javascript:;" class="btn btn-outline-info" id="searchYahooFinance">Search</a>
                        </div>
                    </div>

                    <div class="form-group m-form__group row" id="regularMarketPriceDiv">
                        <label class="col-form-label col-lg-3 col-sm-12">Regular Market Price *</label>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <input type="text" class="form-control m-input" id="regularMarketPrice" value="{{ old('price', $tip->price ?? '') }}" placeholder="Regular Market Price">
                        </div>
                    </div>

                </div>

                <input type="hidden" name="symbol" value="{{ old('symbol', $tip->symbol ?? '') }}" id="stocksymbol" />

                <div class="form-group m-form__group row">
                    <label class="col-form-label col-lg-3 col-sm-12" for="name">Plans *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="40%">Name</th>
                                </tr>
                            </thead>

                            <tbody class="plans_wrapper" id="plan_container">
                                @foreach($tip->tipsPlans as $row_tip_plan)
                                <tr>
                                    <td>
                                        <input type="text" name="tipsplans[plan_name][{{$row_tip_plan->plan_id}}]" class="form-control" value="{{$row_tip_plan->planDetail->name ?? 'N/A'}}" readonly />
                                        <input type="hidden" class="form-control" name="tipsplans[plan_id][{{$row_tip_plan->plan_id}}]" value="{{$row_tip_plan->plan_id}}" readonly />
                                    </td>
                                    <td>
                                        <label class="m-checkbox col-lg-2">
                                            <input type="checkbox" name="tipsplans[plan_status][{{$row_tip_plan->plan_id}}]" value="1" {{ $row_tip_plan->is_status == 1 ? 'checked' : '' }}>
                                            <span></span>
                                        </label>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @php
                    $tipSegmentDisplay = '';
                    //if($tip->segment=='Commodity' || $tip->segment=='Currency') {
                    if($tip->segment=='Currency') {
                        $tipSegmentDisplay = 'none';
                    }
                @endphp
                <div class="form-group m-form__group row @error('stock_name') has-danger @enderror" id="stockwrapper" style="display:{{$tipSegmentDisplay}}">
                    <label class="col-form-label col-lg-3 col-sm-12" id="stockName">Stock Name *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="text" class="form-control m-input" id="placeHolderStockName" name="stock_name" value="{{ old('stock_name', $tip->name ?? '') }}" placeholder="Stock Name" {{ $tip->segment == 'Commodity' ? '' : 'required' }}>
                        @error('stock_name')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- NCD SECTION START -->
                @include('admin.tips.partials.edit-ncd')
                <!-- NCD SECTION END -->
                <!-- IPOs SECTION START -->
                @include('admin.tips.partials.edit-ipos')
                <!-- IPOs SECTION END -->
                <!-- FDs SECTION START -->
                @include('admin.tips.partials.edit-fds')
                <!-- FDs SECTION END -->
                <!-- MF SECTION START -->
                @include('admin.tips.partials.edit-mf')
                <!-- MF SECTION END -->
                @php
                    $tipscontainerDisplay = 'none';
                    if($tip->segment=='Delivery' || $tip->segment=='Intraday' || $tip->segment=='Future' || $tip->segment=='Option' || $tip->segment=='Currency' || $tip->segment=='Commodity' || $tip->segment=='Boolean') {
                        $tipscontainerDisplay = '';
                    }
                @endphp
                <div id="tipscontainer" class="tipscontainer" style="display: {{$tipscontainerDisplay}};">
                    <div class="form-group m-form__group row @error('price') has-danger @enderror">
                        <label class="col-form-label col-lg-3 col-sm-12">Stock Price *</label>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <input type="text" class="form-control _stockprice m-input" name="price" value="{{ old('price', $tip->price ?? '') }}" placeholder="Stock Price" onkeypress="return NumericValidation(event);">
                            @error('price')
                            <div class="form-control-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group m-form__group row @error('source') has-danger @enderror">
                        <label class="col-form-label col-lg-3 col-sm-12">Source *</label>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <select id="source" name="source" class="form-control">
                                <option value="">Select Source</option>
                                @foreach($sources as $source)
                                <option {{ old('source', $tip->source_id ?? '') == $source->id ? 'selected' : '' }} value="{{$source->id}}">{{$source->name}}</option>
                                @endforeach
                            </select>
                            @error('source')
                            <div class="form-control-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <label class="col-form-label col-lg-3 col-sm-12" for="name">Targets *</label>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="40%">Name</th>
                                        <th>Price</th>
                                        <th>Achieved</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody class="targets_wrapper">
                                    @foreach($tip->tipsTipstargets as $row_tip_tips_target)
                                    <input type="hidden" name="targets_id[{{$row_tip_tips_target->id}}]" value="{{$row_tip_tips_target->id}}" >
                                    <tr id="remove_targets_wrapper">
                                        <td>
                                            {{--
                                            <input type="text" class="form-control" name="update_target_names[{{$row_tip_tips_target->id}}]" value="{{$row_tip_tips_target->name}}" placeholder="Target Name*" required />
                                            --}}
                                            <select name="update_target_names[{{$row_tip_tips_target->id}}]" class="form-control" required>
                                                <option {{ $row_tip_tips_target->name == 'T1' ? 'selected' : '' }} value="T1">T1</option>
                                                <option {{ $row_tip_tips_target->name == 'T2' ? 'selected' : '' }} value="T2">T2</option>
                                                <option {{ $row_tip_tips_target->name == 'T3' ? 'selected' : '' }} value="T3">T3</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" name="update_tip_targets_id[{{$row_tip_tips_target->id}}]" value="{{$row_tip_tips_target->id}}" >
                                            <input type="text" class="form-control" name="update_target_prices[{{$row_tip_tips_target->id}}]" value="{{$row_tip_tips_target->price}}" placeholder="Target Price*" required onkeypress="return NumericValidation(event);"  />
                                        </td>
                                        <td>
                                            <label class="m-checkbox col-lg-2">
                                                <input name="update_achieved[{{$row_tip_tips_target->id}}]" type="checkbox" value="1" {{ $row_tip_tips_target->is_achieved == 1 ? 'checked' : '' }}>
                                                <span></span>
                                            </label>
                                        </td>
                                        <td><button type="button" id="btn_remove_targets" class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only" style="margin-top: 6px;"><i class="la la-trash-o"></i></button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group m-form__group row">
                        <label class="col-form-label col-lg-3 col-sm-12">&nbsp;</label>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <button type="button" class="btn m-btn--pill btn-outline-info m-btn m-btn--custom" id="btn_add_target">Add more targets</button>
                        </div>
                    </div>
					
					<div class="form-group m-form__group row @error('buy_range') has-danger @enderror">
                        <label class="col-form-label col-lg-3 col-sm-12">Range *</label>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <input type="text" class="form-control m-input" name="buy_range" value="{{ old('buy_range', $tip->buy_range ?? '') }}" placeholder="Range">
                            @error('buy_range')
                            <div class="form-control-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group m-form__group row @error('stop_loss') has-danger @enderror">
                        <label class="col-form-label col-lg-3 col-sm-12">Stop Loss *</label>
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <input type="text" class="form-control m-input" name="stop_loss" value="{{ old('stop_loss', $tip->stop_loss ?? '') }}" placeholder="Stop Loss" onkeypress="return NumericValidation(event);">
                            @error('stop_loss')
                            <div class="form-control-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
				
				<div class="form-group m-form__group row @error('note') has-danger @enderror">
					<label class="col-form-label col-lg-3 col-sm-12">Note</label>
					<div class="col-lg-7 col-md-7 col-sm-12">
						<input type="text" class="form-control m-input" name="note" value="{{ old('note', $tip->note ?? '') }}" maxlength="34" placeholder="Note">
						<span class="m-form__help">34 Character Limit.</span>
						@error('note')
						<div class="form-control-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>
            </div>

            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-9 ml-lg-auto">
                            {!! $renderButtons !!}
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--end::Form-->
    </div>

    <!--end::Portlet-->

@endsection

@push('js')

    <script>

        jQuery(document).ready(function() {
			
			@if($tip->segment=='Commodity')
				$("#stockName").html('Commodity Title *');
				$('#placeHolderStockName').attr('placeholder', 'Commodity');
			@else
				$("#stockName").html('Stock Name *');
				$('#placeHolderStockName').attr('placeholder', 'Stock Name');
			@endif
			
			//Update Commodity price js start
			jQuery(document).on('keyup keypress blur change','#commodityRate',function() {
				var commodityRate = $('#commodityRate').val();
					$('._stockprice').val(commodityRate);
				
			});	
			//Update Commodity price js end
			
            var max_fields      = 30; //maximum input boxes allowed
            var targets_wrapper         = jQuery(".targets_wrapper"); //Fields wrapper
            var btn_add_target      = jQuery("#btn_add_target"); //Add button ID

            var pv = 0; //initlal text box count

            jQuery(btn_add_target).click(function(e){ //on add input button click
                e.preventDefault();
                if(pv < max_fields){ //max input box allowed

                    pv++; //text box increment

                    //jQuery(targets_wrapper).append('<tr id="remove_targets_wrapper"><td><input type="text" class="form-control" name="target_names['+pv+']" placeholder="Target Name*" required /></td><td><input type="text" class="form-control" name="target_prices['+pv+']" placeholder="Target Price*" required onkeypress="return NumericValidation(event);"  /></td><td><label class="m-checkbox col-lg-2"><input name="target_achieved['+pv+']" type="checkbox" value="1"><span></span></label></td><td><button type="button" id="btn_remove_targets" class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only" style="margin-top: 6px;"><i class="la la-trash-o"></i></button></td></tr>'); //add input box
                    jQuery(targets_wrapper).append('<tr id="remove_targets_wrapper"><td><select name="target_names['+pv+']" class="form-control" required><option value="T1">T1</option><option value="T2">T2</option><option value="T3">T3</option></select></td><td><input type="text" class="form-control" name="target_prices['+pv+']" placeholder="Target Price*" required onkeypress="return NumericValidation(event);"  /></td><td><label class="m-checkbox col-lg-2"><input name="target_achieved['+pv+']" type="checkbox" value="1"><span></span></label></td><td><button type="button" id="btn_remove_targets" class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only" style="margin-top: 6px;"><i class="la la-trash-o"></i></button></td></tr>'); //add input box

                }

            });

            //Remove specific div
            jQuery(targets_wrapper).on("click","#btn_remove_targets", function(e){ //user click on remove text
                if (confirm("Are you sure you want to delete...?")) {
                    e.preventDefault();
                    jQuery(this).closest('#remove_targets_wrapper').remove();
                    pv--;
                }
            });
        });

        $(document).ready(function () {

            // Listen for 'change' event, so this triggers when the user clicks on the checkboxes labels
            $('#segments').on('change', function () {

                var segments = $('option:selected',this).val();
                if(segments=='') {
                    alert('Please select segment.');
                    return false;
                }
                $.ajax({
                    url: "{{route('admin.tips.getPlansSegment')}}",
                    data : {segments: segments},
                    method: 'GET',
                }).done(function(data){
                    $("#plan_container").empty().html(data.data);
                }).fail(function(jqXHR, ajaxOptions, thrownError){
                    alert('No response from server');
                });

            });

            /* Get plan list by segments */
            /*var segments = [];

            // Listen for 'change' event, so this triggers when the user clicks on the checkboxes labels
            $('input[name="segments[]"]').on('change', function (e) {

                e.preventDefault();
                segments = []; // reset
                $('input[name="segments[]"]:checked').each(function()
                {
                    segments.push($(this).val());
                });

                $.ajax({
                    url: "{{route('admin.tips.getPlansSegment')}}",
                    data : {segments: segments},
                    method: 'GET',
                }).done(function(data){
                    $("#plan_container").empty().html(data.data);
                }).fail(function(jqXHR, ajaxOptions, thrownError){
                    alert('No response from server');
                });

            });*/

        });

        jQuery(document).ready(function() {

            $("#segments").change(function(){
                if($("#segments").val()=='Delivery' || $("#segments").val()=='Intraday' || $("#segments").val()=='Future' || $("#segments").val()=='Option' || $("#segments").val()=='Currency' || $("#segments").val()=='Commodity' || $("#segments").val()=='Boolean') {
                    $("#tipscontainer").show();
                }else{
                    $("#tipscontainer").hide();
                }
            });

            $("#segments").change(function(){

                $("#regularMarketPrice").val('');
                $("#commodityRate").val('');

                var segmentsVal = $("#segments").val();

                if(segmentsVal=='Intraday' || segmentsVal=='Delivery') {
                    $("#commoditywrapper").hide();
                    $("#symbolsrapidapiwrapper").show();
                }else if(segmentsVal=='Commodity') {
                    $("#symbolsrapidapiwrapper").hide();
                    $("#commoditywrapper").show();
                }else{
                    $("#symbolsrapidapiwrapper").hide();
                    $("#commoditywrapper").hide();
                }
				//Currency 
				if(segmentsVal=='Currency') {
                    $("#currencywrapper").show();
                    
                }else{
					$("#currencywrapper").hide();
				}
                //mutual funds
                if(segmentsVal=='MF') {
                    $("#mfwrapper").show();
                    $("#stockwrapper").hide();
                }else if(segmentsVal=='Commodity') {
                    console.log(segmentsVal);
					$("#stockName").html('Commodity Title *');
                    $('#placeHolderStockName').attr('placeholder', 'Commodity');
                    //$("#stockwrapper").hide();
                }else{
                    $("#mfwrapper").hide();
                    $("#stockwrapper").show();
					$("#stockName").html('Stock Name *');
                    $('#placeHolderStockName').attr('placeholder', 'Stock Name');
                }
            });

        });

        //$("#symbols").change(function(){
        $("#searchYahooFinance").click(function(){

            $("#regularMarketPrice").val('');
            $("#commodityRate").val('');

            //var symbol = $(this).val();
            var symbolVal = $("#symbol").val();
            //var selectedText = $("#symbols").find("option:selected").text();

            var symbol = $("#symbols").val();
            //console.log("Selected Text: " + selectedText + " Value: " + selectedValue);

            if(symbol=='' || symbolVal=='') {
                alert('Please select valid data.');
                return false;
            }

            $.ajax({

                url: "{{ route('rapidapiStockV3GetChart') }}",
                data: {
                    symbol: symbol,
                    symbolVal: symbolVal
                },
                method: 'GET',
                dataType: "JSON",
                cache: false,
                beforeSend: function(){
                // Show image container
                    $("#searchYahooFinance").html('Searching...');
                },
                success: function(resp) {

                    $("#searchYahooFinance").html('Search');

                    //console.log(resp);
                    if(resp.error) {

                        toastrMessagesBottomRight(resp.error, 4);

                        return false;
                    }

                    if(resp.regularMarketPrice) {
                        toastrMessagesBottomRight(resp.success, 1);
                        $('#regularMarketPriceDiv').show();
                        $('#regularMarketPrice').val(resp.regularMarketPrice);
                        $('#stockPrice').val(resp.regularMarketPrice);
                        $('#stocksymbol').val(symbolVal);
                        return false;
                    }else{
                        alert('No response from server');
                        return false;
                    }
                }
            });
        });
    </script>
@endpush
