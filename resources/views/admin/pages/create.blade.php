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
        <form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.pages.create')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="m-portlet__body">
                
				<div class="form-group m-form__group @error('name') has-danger @enderror">
                    <label for="name">
                        Name*
                    </label>

                    <input type="text" class="form-control m-input" name="name" value="{{ old('name') }}" placeholder="Name" required="required" autofocus>
					@error('name')
					<div class="form-control-feedback">{{ $message }}</div>
					@enderror
                </div>
				
				<div class="form-group m-form__group @error('content') has-danger @enderror">
                    <label for="content">
                        Content*
                    </label>

                    <textarea name="content" class="form-control ckeditor">{{ old('content') }}</textarea>
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
    {{--<script src="{{ asset('public/assets/ckeditor/ckeditor.js')}}"></script>--}}
    <script src="{{ asset('public/assets/ckeditor-full-package/ckeditor.js')}}"></script>
    <script data-sample="1">
        CKEDITOR.replace('editor1');
        CKEDITOR.replace('editor2');
    </script>
@endpush
