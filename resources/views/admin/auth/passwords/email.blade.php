@extends('admin.layouts.app')

@section('title', 'Reset Password')

@section('content')
	<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2 m-login--forget-password" id="m_login" style="background-image: url({{asset('public/assets/app/media/img/bg/bg-3.jpg')}});">
			<div class="m-grid__item m-grid__item--fluid m-login__wrapper">
				<div class="m-login__container">
					<div class="m-login__logo">
						<h2>{{ config('app.name', 'Stock Tips Apps') }}</h2>
					</div>
					<div class="m-login__forget-password">
						<div class="m-login__head">
							<h3 class="m-login__title">Forgotten Password ?</h3>
							<div class="m-login__desc">Enter your email to reset your password:</div>
						</div>
						<form class="m-login__form m-form" method="POST" action="{{ route('admin.password.email') }}">
							@csrf
							@if(session('error'))
							<div class="m-alert m-alert--outline alert alert-danger alert-dismissible animated fadeIn" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
								<span>{{session('error')}}</span>
							</div>
							@endif
							@if (session('status'))
								<div class="m-alert m-alert--outline alert alert-success alert-dismissible animated fadeIn" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>{{ session('status') }}</span></div>
							@endif
							@error('email')
								<div class="m-alert m-alert--outline alert alert-danger alert-dismissible animated fadeIn" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
									<span>{{ $message }}</span>
								</div>
							@enderror
							<div class="form-group m-form__group">
								<input id="m_email" type="email" placeholder="{{ __('E-Mail Address') }}" class="form-control m-input" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
							</div>
							<div class="m-login__form-action">
								<button type="submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primaryr">Request</button>
								&nbsp;&nbsp;
								<a  href="{{route('admin.login')}}" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom m-login__btn">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
@endsection
