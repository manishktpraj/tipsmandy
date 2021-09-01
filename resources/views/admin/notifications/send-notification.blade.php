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
                        
                    </li>
                </ul>
            </div>
        </div>

        <!--begin::Form-->
        <form class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.notifications.index')}}" method="post">
            @csrf
            <div class="m-portlet__body">
				
				<div class="form-group m-form__group row @error('notification_type') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Notification Type *</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
						<select class="form-control" name="notification_type" required="required">
							<option disabled selected value>-- Select Type --</option>
							<option value="1">All</option>
							<option value="2">Subscribed Users</option>
							<option value="3">Guest Users</option>
						</select>
                        @error('notification_type')
                        <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
				
				
				<div class="form-group m-form__group row @error('notification_type') has-danger @enderror">
                    <label class="col-form-label col-lg-3 col-sm-12">Content*</label>
                    <div class="col-lg-7 col-md-7 col-sm-12">
						<textarea name="notification" class="form-control" maxlength="60">{{ old('notification') }}</textarea>
						<span class="m-form__help">60 Character Limit.</span>
						@error('notification')
						<div class="form-control-feedback">{{ $message }}</div>
						@enderror
                    </div>
                </div>
				
			</div>

            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-9 ml-lg-auto">
                            <button type="submit" class="btn btn-success">Send Notification</button>
                            <a href="" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--end::Form-->
    </div>

    <!--end::Portlet-->

@endsection


