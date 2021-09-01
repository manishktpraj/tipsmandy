    @push('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style type="text/css">#mf_api{background-color: gray;}</style>
    @endpush
	<div id="mfwrapper" style="display:{{ $tip->segment == 'MF' ? '' : 'none' }};">

        <div class="form-group m-form__group row @error('caps_type') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Fund Type *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <select class="form-control" name="caps_type" id="caps_type">
                    <option disabled selected value>-- Fund Type--</option>
                    @foreach(config('constants.mfFundTypes') as $mfFundTypesValue)
                    <option {{ old('caps_type', $tip->tipmutualfunds->caps_type ?? '') == $mfFundTypesValue ? 'selected' : '' }} value="{{$mfFundTypesValue}}">{{$mfFundTypesValue}}</option>
                    @endforeach
                </select>
                @error('caps_type')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group m-form__group row @error('purpose') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Purpose *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <select class="form-control" name="purpose" id="purpose">
                    <option disabled selected value>-- Purpose--</option>
                    @foreach(config('constants.mfPurpose') as $mfPurposeValue)
                    <option {{ old('purpose', $tip->tipmutualfunds->purpose ?? '') == $mfPurposeValue ? 'selected' : '' }} value="{{$mfPurposeValue}}">{{$mfPurposeValue}}</option>
                    @endforeach
                </select>
                @error('purpose')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group m-form__group row">
			<label class="col-form-label col-lg-3 col-sm-12">Scheme Name *</label>
			<div class="col-lg-5 col-md-5 col-sm-12">
				<input type="text" class="form-control m-input scheme_name" id="scheme_name" name="mf_stock_name" value="{{ old('mf_stock_name', $tip->name ?? '') }}" placeholder="Scheme Name">
			</div>
		</div>

        <div class="form-group m-form__group row">
            <label class="col-form-label col-lg-3 col-sm-12">Scheme Code *</label>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <input type="text" class="form-control m-input scheme_code" id="scheme_code" name="scheme_code" value="{{ old('scheme_code', $tip->tipmutualfunds->scheme_code ?? '') }}" placeholder="Scheme Code">
            </div>
        </div>

        <div class="form-group m-form__group row">
            <label class="col-form-label col-lg-3 col-sm-12">ISIN Div Payout/ISIN Growth *</label>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <input type="text" class="form-control m-input isin_div_payout_isin_growth" id="isin_div_payout_isin_growth" name="isin_div_payout_isin_growth" value="{{ old('isin_div_payout_isin_growth', $tip->tipmutualfunds->isin_div_payout_isin_growth ?? '') }}" placeholder="ISIN Div Payout/ISIN Growth">
            </div>
        </div>

        <div class="form-group m-form__group row">
            <label class="col-form-label col-lg-3 col-sm-12">ISIN Div Reinvestment *</label>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <input type="text" class="form-control m-input isin_div_reinvestment" id="isin_div_reinvestment" name="isin_div_reinvestment" value="{{ old('isin_div_reinvestment', $tip->tipmutualfunds->isin_div_reinvestment ?? '') }}" placeholder="ISIN Div Reinvestment">
            </div>
        </div>

        <div class="form-group m-form__group row">
            <label class="col-form-label col-lg-3 col-sm-12">Scheme Name *</label>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <input type="text" class="form-control m-input mutual_scheme_name" id="mutual_scheme_name" name="mutual_scheme_name" value="{{ old('scheme_name', $tip->tipmutualfunds->scheme_name ?? '') }}" placeholder="Scheme Name">
            </div>
        </div>

        <div class="form-group m-form__group row">
            <label class="col-form-label col-lg-3 col-sm-12">Net Asset Value *</label>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <input type="text" class="form-control m-input net_asset_value" id="net_asset_value" name="net_asset_value" value="{{ old('net_asset_value', $tip->tipmutualfunds->net_asset_value ?? '') }}" placeholder="Net Asset Value">
            </div>
        </div>

        <div class="form-group m-form__group row">
            <label class="col-form-label col-lg-3 col-sm-12">Date *</label>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <input type="text" class="form-control m-input date" id="date" name="date" value="{{ old('mutual_date', $tip->tipmutualfunds->mutual_date ?? '') }}" placeholder="Date">
            </div>
        </div>

        <div class="form-group m-form__group row">
            <label class="col-form-label col-lg-3 col-sm-12">Scheme Type *</label>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <input type="text" class="form-control m-input scheme_type" id="scheme_type" name="scheme_type" value="{{ old('scheme_type', $tip->tipmutualfunds->scheme_type ?? '') }}" placeholder="Scheme Type">
            </div>
        </div>

        <div class="form-group m-form__group row">
            <label class="col-form-label col-lg-3 col-sm-12">Scheme Category *</label>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <input type="text" class="form-control m-input scheme_category" id="scheme_category" name="scheme_category" value="{{ old('scheme_category', $tip->tipmutualfunds->scheme_category ?? '') }}" placeholder="Scheme Category">
            </div>
        </div>

        <div class="form-group m-form__group row">
            <label class="col-form-label col-lg-3 col-sm-12">Mutual Fund Family *</label>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <input type="text" class="form-control m-input mutual_fund_family" id="mutual_fund_family" name="mutual_fund_family" value="{{ old('mutual_fund_family', $tip->tipmutualfunds->mutual_fund_family ?? '') }}" placeholder="Mutual Fund Family">
            </div>
        </div>

        <div class="form-group m-form__group row">
            <label class="col-form-label col-lg-3 col-sm-12">Mf Api Data *</label>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <textarea class="form-control mf_api" name="mf_api" id="mf_api" rows="50">{{ old('mf_api', $tip->tipmutualfunds->mf_api ?? '') }}</textarea>
            </div>
        </div>

        <div class="form-group m-form__group row @error('mf_rating') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Rating *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <select class="form-control" name="mf_rating" id="mf_rating">
                    <option disabled selected value>-- Rating --</option>
                    @foreach(config('constants.ratings') as $mfRating)
                    <option {{ old('mf_rating', $tip->rating ?? '') == $mfRating ? 'selected' : '' }} value="{{$mfRating}}">{{$mfRating}}</option>
                    @endforeach
                </select>
                @error('mf_rating')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

	</div>
	

@push('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

        // CSRF Token
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){

            $("#scheme_name").autocomplete({
                source: function(request, response) {
                    // Fetch data
                    $.ajax({
                        url:"{{route('admin.mutualfunds.search')}}",
                        type: 'get',
                        dataType: "json",
                        data: {
                           _token: CSRF_TOKEN,
                           search: request.term
                        },
                        success: function(data) {
                           response(data);
                        }
                    });
                },
                select: function (event, ui) {
                   // Set selection
                   $('#scheme_name').val(ui.item.label); // display the selected text
                   var scheme_name = ui.item.label; // save selected id to input

                   $.ajax({

                        url: "{{ route('admin.mutualfunds.latest-mutual-fund') }}",
                        data: {scheme_name: scheme_name},
                        method: 'GET',
                        dataType: "JSON",
                        cache: false,
                        success: function(resp) {
                            //console.log(resp);
                            if(resp.error) {
                                toastrMessagesBottomRight(resp.error, 4);
                                return false;
                            }
                            toastrMessagesBottomRight(resp.success, 1);
                            $('#scheme_code').val(resp.scheme_code);
                            $('#isin_div_payout_isin_growth').val(resp.isin_div_payout_isin_growth);
                            $('#isin_div_reinvestment').val(resp.isin_div_reinvestment);
                            $('#mutual_scheme_name').val(resp.scheme_name);
                            $('#net_asset_value').val(resp.net_asset_value);
                            $('#date').val(resp.date);
                            $('#scheme_type').val(resp.scheme_type);
                            $('#scheme_category').val(resp.scheme_category);
                            $('#mutual_fund_family').val(resp.mutual_fund_family);
                            $('#mf_api').val(resp.mf_api);
                        }
                    });
                   return false;
                }
            });

            $('#scheme_name').on('change', function () {

                var scheme_name = $(this).val();

                if(scheme_name=='') {
                    alert('Please select scheme name.');
                    return false;
                }
                console.log(scheme_name);
                /*$.ajax({
                    url: "{{route('admin.tips.getPlansSegment')}}",
                    data : {segments: segments},
                    method: 'GET',
                }).done(function(data){
                    $("#plan_container").empty().html(data.data);
                }).fail(function(jqXHR, ajaxOptions, thrownError){
                    alert('No response from server');
                });*/
            });

        });

        jQuery(document).ready(function() {

            //jQuery(document).on('keyup keypress blur change','.scheme_name',function() {
            jQuery('.scheme_name').unbind('keyup change input paste').bind('keyup change input paste',function(e){
                var scheme_name = jQuery(this).val();
                //console.log(scheme_name);
                 // If the length is 3 or more characters, or the user pressed ENTER, search
                if(scheme_name.length >= 3 || e.keyCode == 13) {
                    // Call the API search function
                    //console.log(scheme_name.length);
                }
            });
        });
    </script>
@endpush
