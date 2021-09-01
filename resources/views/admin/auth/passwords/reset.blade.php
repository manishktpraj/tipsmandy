@extends('admin.layouts.app')
@section('title', 'Reset Password')
@section('content')
	<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--2 m-login-2--skin-2 m-login--forget-password" id="m_login" style="background-image: url({{asset('public/assets/app/media/img/bg/bg-3.jpg')}});">
		<div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
			<div class="m-login__container">
				<div class="m-login__logo">
					<h2>{{ config('app.name', 'Stock Tips Apps') }}</h2>
				</div>
				<div class="m-login__forget-password">
					<div class="m-login__head">
						<h3 class="m-login__title">{{ __('Reset Password') }}</h3>
					</div>
					<form class="m-login__form m-form" method="POST" action="{{ route('admin.password.update') }}">
						@csrf

						@error('email')
							<div class="m-alert m-alert--outline alert alert-danger alert-dismissible animated fadeIn" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
								<span>{{ $message }}</span>
							</div>
						@enderror

						<input type="hidden" name="token" value="{{ $token }}">

						<div class="form-group m-form__group">
							<input id="email" type="email" placeholder="{{ __('E-Mail Address') }}" class="form-control m-input" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
						</div>

						<div class="form-group m-form__group">
							<input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control m-input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
							@error('password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
						</div>

						<div class="form-group m-form__group">
							<input id="password-confirm" type="password" class="form-control m-input" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required autocomplete="new-password">
						</div>

						<div class="m-login__form-action">
							<button type="submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primaryr">{{ __('Reset Password') }}</button>
							&nbsp;&nbsp;
							<a  href="{{route('admin.login')}}" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom m-login__btn">Cancel</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
