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
        <form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.users.import')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="m-portlet__body">

                <div class="form-group m-form__group row @error('member_excel_file') has-danger @enderror">
                    <label class="col-xl-2 col-lg-2 col-form-label" for="member_excel_file">&nbsp;</label>
                    <div class="col-xl-5 col-lg-5">
                        <input type="file" class="form-control m-input" name="member_excel_file" accept=".xlsx" required="required">
                        @error('member_excel_file')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                        <span class="m-form__help">Import functionality can be used to bulk upload new Member to your system using Excel file (.xlsx) format.</span>
                    </div>
                </div>

                <div class="form-group m-form__group row ">
                    <label class="col-xl-2 col-lg-2 col-form-label">&nbsp;</label>
                    <div class="col-xl-9 col-lg-9">
                        <a href="{{asset('public/memberExcelsample/SampleImportv1.xlsx')}}" download>Download</a> Sample Excel (.xlsx) template
                    </div>
                </div>

            </div>

            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-10 ml-lg-auto">
                            <button type="submit" class="btn btn-success">Upload</button>
                            <a href="{{route('admin.users.index')}}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--end::Form-->
    </div>

    <!--end::Portlet-->

@endsection
