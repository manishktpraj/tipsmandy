    
	<div id="commoditywrapper" style="display: {{$commoditywrapperDisplay}};">
                    
		<div class="form-group m-form__group row">
			<label class="col-form-label col-lg-3 col-sm-12">Symbol *</label>
			<div class="col-lg-5 col-md-5 col-sm-12">
				<select id="symbolcommodity" name="symbolcommodity" class="form-control">
					<option value="">Select Symbol</option>
					{{--
					<option {{ $tip->symbol == 'XAU' ? 'selected' : '' }} value="XAU">XAU</option>
					<option {{ $tip->symbol == 'XAG' ? 'selected' : '' }} value="XAG">XAG</option>
					<option {{ $tip->symbol == 'PA' ? 'selected' : '' }} value="PA">PA</option>
					--}}
					<option {{ $tip->symbol == 'MCX' ? 'selected' : '' }} value="MCX">MCX</option>
					<option {{ $tip->symbol == 'NCDEX' ? 'selected' : '' }} value="NCDEX">NCDEX</option>
				</select>
				@error('symbol')
				<div class="form-control-feedback">{{ $message }}</div>
				@enderror
			</div>
			
			{{--
			<div class="col-lg-3 col-md-3 col-sm-12">
				<a href="javascript:;" class="btn btn-outline-info" id="searchcommodityRate">Search</a>
			</div>
			--}}
			
		</div>

		<div class="form-group m-form__group row" id="commodityPriceDiv" style="display:;">
			<label class="col-form-label col-lg-3 col-sm-12">Regular Market Price *</label>
			<div class="col-lg-5 col-md-5 col-sm-12">
				<input type="text" class="form-control m-input" id="commodityRate" value="{{ old('price', $tip->price ?? '') }}" placeholder="Rate">
			</div>
		</div>

	</div>
	
	<div class="form-group m-form__group row" id="currencywrapper" style="display: {{$currencywrapperDisplay}};">
		<label class="col-form-label col-lg-3 col-sm-12">Currency *</label>
		<div class="col-lg-5 col-md-5 col-sm-12">
			<input type="text" class="form-control m-input" name="currency" value="{{ old('currency', $tip->currency_name ?? '') }}" placeholder="">
		</div>
	</div>

@push('js')

    <script>

        /*jQuery(document).ready(function() {

            $("#segments").change(function(){

                var segmentsVal = $("#segments").val();

                if(segmentsVal=='Intraday' || segmentsVal=='Delivery') {
					$("#commoditywrapper").hide();
                    $("#symbolsrapidapiwrapper").show();
                }else if(segmentsVal=='Commodity' || segmentsVal=='Currency') {
					$("#symbolsrapidapiwrapper").hide();
                    $("#commoditywrapper").show();
                }else{
                    $("#symbolsrapidapiwrapper").hide();
                    $("#commoditywrapper").hide();
                }
            });  
        });*/

        $("#searchcommodityRate").click(function(){
			
			$("#regularMarketPrice").val('');
			$("#commodityRate").val('');
			
            //var symbol = $(this).val();
            var symbolVal = $("#symbolcommodity").val();
            //var selectedText = $("#symbols").find("option:selected").text();
            
            if(symbolVal=='') {
                alert('Please select valid data.');
                return false;
            }

            $.ajax({

                url: "{{ route('rapidapiliveMetalPrices') }}",
                data: {symbolVal: symbolVal},
                method: 'GET',
                dataType: "JSON",
                cache: false,
                beforeSend: function(){
                // Show image container
                    $("#searchcommodityRate").html('Searching...');
                },
                success: function(resp) {

                    $("#searchcommodityRate").html('Search');

                    //console.log(resp);
                    if(resp.error) {

                        toastrMessagesBottomRight(resp.error, 4);
                        
                        return false;
                    }

                    if(resp.regularMarketPrice) {
                        toastrMessagesBottomRight(resp.success, 1);
                        $('#commodityPriceDiv').show();
                        $('#commodityRate').val(resp.regularMarketPrice);
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
