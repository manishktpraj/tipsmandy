    
    <div id="fdscontainer" class="fdscontainer" style="display: {{ $tip->segment == 'FDs' ? '' : 'none' }};">

        <div class="form-group m-form__group row @error('interest') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Interest *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <input type="text" class="form-control m-input" name="interest" value="{{ old('interest', $tip->interest ?? '') }}" placeholder="Interest" onkeypress="return NumericValidation(event);">
                @error('interest')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group m-form__group row @error('start_year') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Year Range *</label>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <input type="text" class="form-control m-input" name="start_year" value="{{ old('start_year', $tip->start_year ?? '') }}" placeholder="Minimum Days" onkeypress="return NumericValidation(event);">
                <!--span class="m-form__help">Minimum Days</span-->
                @error('start_year')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12">
                <input type="text" class="form-control m-input" name="end_year" value="{{ old('end_year', $tip->end_year ?? '') }}" placeholder="Maximum Days" onkeypress="return NumericValidation(event);">
                <!--span class="m-form__help">Maximum Days</span-->
                @error('end_year')
                <div class="form-control-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group m-form__group row @error('fds_rating') has-danger @enderror">
            <label class="col-form-label col-lg-3 col-sm-12">Rating *</label>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <select class="form-control" name="fds_rating" id="fds_rating">
                    <option disabled selected value>-- Rating --</option>
                    @foreach(config('constants.ratings') as $ncdRating)
                    <option {{ old('fds_rating', $tip->rating ?? '') == $ncdRating ? 'selected' : '' }} value="{{$ncdRating}}">{{$ncdRating}}</option>
                    @endforeach
                </select>
                @error('fds_rating')
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
                if(segmentsVal=='FDs') {
                    $("#fdscontainer").show();
                }else{
                    $("#fdscontainer").hide();
                }
            });  
        });
    </script>
@endpush
