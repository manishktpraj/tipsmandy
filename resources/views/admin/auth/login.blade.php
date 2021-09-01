@extends('admin.layouts.app')

@section('title', 'Login')

@section('content')
	<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2" id="m_login" style="background-image: url({{asset('public/assets/app/media/img/bg/bg-3.jpg')}});">
			<div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
				<div class="m-login__container">
					<div class="m-login__logo">
						{{--
						<a href="#">
							<img src="../../../assets/app/media/img/logos/logo-1.png">
						</a>
						--}}
						<h2>{{ config('app.name', 'Stock Tips Apps') }}</h2>
					</div>
					<div class="m-login__signin">
						<div class="m-login__head">
							<h3 class="m-login__title">Sign In</h3>
						</div>
						<form class="m-login__form m-form" method="POST" action="{{ route('admin.login') }}">
							@csrf

							@if(session('error'))
							<div class="m-alert m-alert--outline alert alert-danger alert-dismissible animated fadeIn" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
								<span>Incorrect username or password. Please try again.</span>
							</div>
							@endif

							@error('email')
							<div class="m-alert m-alert--outline alert alert-danger alert-dismissible animated fadeIn" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
								<span>{{ $message }}</span>
							</div>
							@enderror

							@if (session('status'))
							<div class="m-alert m-alert--outline alert alert-success alert-dismissible animated fadeIn" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
								<span>{{ session('status') }}</span>
							</div>
							@endif

							<div class="form-group m-form__group">
								<input class="form-control m-input" id="email" type="email" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
							</div>

							<div class="form-group m-form__group">
								<input class="form-control m-input m-login__form-input--last" type="password" placeholder="{{ __('Password') }}" name="password" required autocomplete="current-password">
							</div>

							<div class="row m-login__form-sub">
								{{--
								<div class="col m--align-left m-login__form-left">
									<label class="m-checkbox  m-checkbox--focus">
										<input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
										<span></span>
									</label>
								</div>--}}
								<div class="col m--align-right m-login__form-right">
									<a href="{{ route('admin.password.request') }}" class="m-link">{{ __('Forgot Your Password?') }}</a>
								</div>
							</div>

							<div class="m-login__form-action">
								<button type="submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">Sign In</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
@endsection
