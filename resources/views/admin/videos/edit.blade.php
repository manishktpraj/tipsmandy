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
        <form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.videos.edit', $video->id)}}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="m-portlet__body">
				
				<div class="form-group m-form__group @error('title') has-danger @enderror">
                    <label for="name">
                        Title*
                    </label>

                    <select name="title" id="title" class="form-control" required>
						<option value="">Select</option>
						@foreach($videoCategories as $videoCategory)
						<option value="{{$videoCategory->id}}" {{ old('title', $video->title) == $videoCategory->id ? 'selected' : '' }}>{{$videoCategory->title}}</option>
						@endforeach
					</select>
                    @error('title')
                    <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>
				
				<div class="form-group m-form__group @error('sub_title') has-danger @enderror">
                    <label for="name">
                        Sub Title*
                    </label>

                    <input type="text" class="form-control m-input" name="sub_title" value="{{ old('sub_title', $video->sub_title) }}" placeholder="Sub Title" required="required">
                    @error('sub_title')
                    <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group m-form__group @error('video_url') has-danger @enderror">
                    <label for="name">
                        Video Url*
                    </label>

                    <input type="url" class="form-control m-input" name="video_url" value="{{ old('video_url', $video->video_url) }}" placeholder="Video Url" required="required">
                    @error('video_url')
                    <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group m-form__group @error('icon') has-danger @enderror">
                    <label for="icon">
                        Icon*
                    </label>
                    
                    <input type="file" class="form-control m-input" id="icon_icon" name="icon" onchange="loadFile(event, 'icon')" accept="image/*" />
                    <p></p>
                    @error('icon')
                    <div class="form-control-feedback">{{ $message }}</div>
                    @enderror

                    <p></p>
                    <div class="fileinput fileinput-exists">
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                            @if(!empty($video->icon))
                            <img src="{{asset('public/uploads/icons/'.$video->icon)}}" id="icon" style="max-height: 140px;">
                            @else
                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="icon" style="max-height: 140px;">
                            @endif
                            
                        </div>
                    </div>
                    
                </div>
				
                <div class="form-group m-form__group @error('content') has-danger @enderror">
                    <label for="content">
                        Content*
                    </label>
                    
                    <textarea class="form-control" id="editor2" name="content" placeholder="Content" rows="6" required="required">{{ old('content', $video->content ?? '') }}</textarea>
                    @error('content')
                    <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                    
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
@endpush
