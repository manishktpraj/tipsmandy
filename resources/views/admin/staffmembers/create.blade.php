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
        <form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.staffmembers.create')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="m-portlet__body">

                <div class="form-group m-form__group row @error('role') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Role *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <select name="role" id="role" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($roles as $role)
                            <option value="{{$role->id}}" {{ old('role') == $role->id ? 'selected' : '' }}>{{$role->name}}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group m-form__group row @error('name') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Name *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="text" class="form-control m-input" name="name" value="{{ old('name') }}" placeholder="Name" required autofocus>
                        @error('name')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group m-form__group row @error('email') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Email *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="email" class="form-control m-input" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email">
                        @error('email')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group m-form__group row @error('password') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Password *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input id="password" type="password" class="form-control m-input" name="password" placeholder="*******" required autocomplete="current-password">
                        @error('password')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group m-form__group row @error('phone_no') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Phone No *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <input type="text" class="form-control m-input" value="{{ old('phone_no') }}" name="phone_no" placeholder="Phone No" autocomplete="phone_no" onkeypress="return NumericValidation(event);" maxlength="10" required>
                        @error('phone_no')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-9 ml-lg-auto">
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
