    
	<div id="ncdcontainer" class="ncdcontainer" style="display: none;">

        <div class="form-group m-form__group row @error('type_of_bond') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Type of Bond *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <input type="text" class="form-control m-input" name="type_of_bond" value="{{ old('type_of_bond') }}" placeholder="Type of Bond">
                @error('type_of_bond')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group m-form__group row">
            <label class="col-form-label col-lg-3 col-sm-12" for="name">&nbsp; *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="40%">Investment</th>
                            <th>Duration</th>
                            <th>Maturity Amount</th>
                        </tr>
                    </thead>

                    <tbody class="">
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="ncd_investment[]" placeholder="Investment" onkeypress="return NumericValidation(event);" />
                            </td>
                            <td>
                                <input type="text" class="form-control" name="ncd_duration[]" placeholder="Duration" onkeypress="return NumericValidation(event);"  />
                            </td>
                            <td>
                                <input type="text" class="form-control" name="ncd_maturity_amount[]" placeholder="Maturity Amount" onkeypress="return NumericValidation(event);"  />
                            </td>
                        </tr>
                         <tr>
                            <td>
                                <input type="text" class="form-control" name="ncd_investment[]" placeholder="Investment" onkeypress="return NumericValidation(event);" />
                            </td>
                            <td>
                                <input type="text" class="form-control" name="ncd_duration[]" placeholder="Duration" onkeypress="return NumericValidation(event);"  />
                            </td>
                            <td>
                                <input type="text" class="form-control" name="ncd_maturity_amount[]" placeholder="Maturity Amount" onkeypress="return NumericValidation(event);" />
                            </td>
                        </tr>
                         <tr>
                            <td>
                                <input type="text" class="form-control" name="ncd_investment[]" placeholder="Investment"  onkeypress="return NumericValidation(event);" />
                            </td>
                            <td>
                                <input type="text" class="form-control" name="ncd_duration[]" placeholder="Duration" onkeypress="return NumericValidation(event);" />
                            </td>
                            <td>
                                <input type="text" class="form-control" name="ncd_maturity_amount[]" placeholder="Maturity Amount" onkeypress="return NumericValidation(event);" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-group m-form__group row @error('unit_price') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Unit Price *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <input type="text" class="form-control m-input" name="unit_price" value="{{ old('unit_price') }}" placeholder="Unit Price" id="unit_price" onkeypress="return NumericValidation(event);">
                @error('unit_price')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group m-form__group row @error('ncd_rating') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Rating *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <select class="form-control" name="ncd_rating" id="ncd_rating">
                    <option disabled selected value>-- Rating --</option>
                    @foreach(config('constants.ratings') as $ncdRating)
                    <option value="{{$ncdRating}}">{{$ncdRating}}</option>
                    @endforeach
                </select>
                @error('ncd_rating')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

	</div>
	

@push('js')

    <script>

        jQuery(document).ready(function() {
            $("#segments").change(function(){
                var segmentsVal = $("#segments").val();
                if(segmentsVal=='NCD') {
                    $("#stockName").html('Bond Title *');
                    $('#placeHolderStockName').attr('placeholder', 'Bond Title');
					$("#ncdcontainer").show();
                }else{
                    $("#stockName").html('Stock Name *');
                    $('#placeHolderStockName').attr('placeholder', 'Stock Name');
                    $("#ncdcontainer").hide();
                }
            });  
        });
    </script>
@endpush
