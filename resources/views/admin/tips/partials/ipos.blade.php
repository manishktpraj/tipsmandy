    
    <div id="iposcontainer" class="iposcontainer" style="display: none;">

        <div class="form-group m-form__group row @error('price_range') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Range *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <input type="text" class="form-control m-input" name="price_range" value="{{ old('price_range') }}" placeholder="Price Range">
                @error('price_range')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group m-form__group row @error('ipo_open_date') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">&nbsp;</label>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <input type="date" class="form-control m-input" name="ipo_open_date" value="{{ old('ipo_open_date') }}" >
                @error('ipo_open_date')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12">
                <input type="date" class="form-control m-input" name="ipo_close_date" value="{{ old('ipo_close_date') }}">
                @error('ipo_close_date')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group m-form__group row @error('ipo_buy_range') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Buy % *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <input type="text" class="form-control m-input" name="ipo_buy_range" value="{{ old('ipo_buy_range') }}" placeholder="Percentage" onkeypress="return NumericValidation(event);">
                @error('ipo_buy_range')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group m-form__group row @error('ipo_avoid_range') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Avoid % *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <input type="text" class="form-control m-input" name="ipo_avoid_range" value="{{ old('ipo_avoid_range') }}" placeholder="Percentage" onkeypress="return NumericValidation(event);">
                @error('ipo_avoid_range')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group m-form__group row @error('ipo_rating') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Rating *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <select class="form-control" name="ipo_rating" id="ipo_rating">
                    <option disabled selected value>-- Rating --</option>
                    @foreach(config('constants.ratings') as $ncdRating)
                    <option value="{{$ncdRating}}">{{$ncdRating}}</option>
                    @endforeach
                </select>
                @error('ipo_rating')
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
                if(segmentsVal=='IPOs') {
                    $("#iposcontainer").show();
                }else{
                    $("#iposcontainer").hide();
                }
            });  
        });
    </script>
@endpush
