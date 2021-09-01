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
        <form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.stockedgevideos.create')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="m-portlet__body">

                <div class="form-group m-form__group @error('title') has-danger @enderror">
                    <label for="name">
                        Title*
                    </label>

                    <input type="text" class="form-control m-input" name="title" value="{{ old('title') }}" placeholder="Title" required="required">
                    @error('title')
                    <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group m-form__group @error('video_url') has-danger @enderror">
                    <label for="name">
                        Video Url*
                    </label>

                    <input type="url" class="form-control m-input" name="video_url" value="{{ old('video_url') }}" placeholder="Video Url" required="required">
                    @error('video_url')
                    <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>
				
				<div class="form-group m-form__group @error('youtube_thumbnail') has-danger @enderror">
                    <label for="youtube_thumbnail">
                        Youtube Thumbnail*
                    </label>
                    
					<input type="file" class="form-control m-input" id="thumbnail_youtube" name="youtube_thumbnail" onchange="loadFile(event, 'youtube_thumbnail')" />
					<p></p>
					@error('youtube_thumbnail')
					<div class="form-control-feedback">{{ $message }}</div>
					@enderror

					<p></p>
					<div class="fileinput fileinput-exists">
						<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
							
							<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="youtube_thumbnail" style="max-height: 140px;">
							
						</div>
					</div>
                    
                </div>

            </div>

            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-12 ml-lg-auto">
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

<script src="{{ asset('public/assets/ckeditor-full-package/ckeditor.js')}}"></script>
<script data-sample="1">
    CKEDITOR.replace('editor1');
    CKEDITOR.replace('editor2');
</script>

<script>

    jQuery(document).ready(function() {

        
    });

</script>
@endpush
