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
        </div>

        <!--begin::Form-->
        <form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.settings')}}" method="post" enctype="multipart/form-data">
            
            @method('PUT')

            @csrf
            
            <div class="m-portlet__body">
                
                <div class="form-group m-form__group row @error('whats_app_number') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">WhatsApp Number *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="text" class="form-control m-input" name="whats_app_number" value="{{ old('whats_app_number', config('settings.whats_app_number')) }}" placeholder="WhatsApp Number" required="required" onkeypress="return NumericValidation(event);" maxlength="10" autofocus>
                        @error('whats_app_number')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
				
				<div class="form-group m-form__group row @error('toll_free_number') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Toll Free Number *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="text" class="form-control m-input" name="toll_free_number" value="{{ old('toll_free_number', config('settings.toll_free_number')) }}" placeholder="Toll Free Number" onkeypress="return NumericValidation(event);">
                        @error('whats_app_number')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
				
                <div class="form-group m-form__group row @error('email') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Email *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="email" class="form-control m-input" name="email" value="{{ old('email', config('settings.email')) }}" placeholder="Email" required="required">
                        @error('email')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group m-form__group row @error('facebook') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Facebook *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="url" class="form-control m-input" name="facebook" value="{{ old('facebook', config('settings.facebook')) }}" placeholder="Facebook">
                        @error('facebook')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group m-form__group row @error('twitter') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Twitter *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="url" class="form-control m-input" name="twitter" value="{{ old('twitter', config('settings.twitter')) }}" placeholder="Twitter">
                        @error('twitter')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group m-form__group row @error('youtube_video_link') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Youtube Url *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="url" class="form-control m-input" name="youtube_video_link" value="{{ old('youtube_video_link', config('settings.youtube_video_link')) }}" placeholder="Youtube Url" required="required">
                        @error('youtube_video_link')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group m-form__group row @error('youtube_thumbnail') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Youtube Thumbnail</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="file" class="form-control m-input" id="thumbnail_youtube" name="youtube_thumbnail" onchange="loadFile(event, 'youtube_thumbnail')" />
                        <p></p>
                        @error('youtube_thumbnail')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror

                        <p></p>
                        <div class="fileinput fileinput-exists">
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                @if (config('settings.youtube_thumbnail') != null)
                                    <img src="{{ url('storage/app/public/'.config('settings.youtube_thumbnail')) }}" id="youtube_thumbnail" style="max-height: 140px;">
                                @else
                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="youtube_thumbnail" style="max-height: 140px;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @if(Helper::checkPermission(Auth::guard('admin')->user()->is_role, 'settings', 'is_edit'))
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-9 ml-lg-auto">
                            <button type="submit" class="btn btn-sm btn-success">Update Settings</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </form>

        <!--end::Form-->
    </div>

    <!--end::Portlet-->

@endsection

@push('js')

<script>

    jQuery(document).ready(function() {

        
    });

</script>
@endpush
