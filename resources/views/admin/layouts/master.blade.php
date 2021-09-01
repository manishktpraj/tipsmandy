<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">
		<title>{{ config('app.name', 'Stock Tips Apps') }}</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>

		<!--end::Web font -->

		<!--begin::Global Theme Styles -->
		<link href="{{asset('public/assets/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />

		<link href="{{asset('public/assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->

		<!--begin::Page Vendors Styles -->
		<link href="{{asset('public/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />

		<!--end::Page Vendors Styles -->

		<link href="{{asset('public/assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />

		<!-- custom css -->
		<link href="{{asset('public/assets/css/custom.css')}}" rel="stylesheet" type="text/css" />

		<link rel="shortcut icon" href="{{asset('public/assets/demo/default/media/img/logo/favicon.ico')}}" />

		@stack('css')

	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default" data-root="{{ url('/') }}" id="root">

		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">

			<!-- BEGIN: Header -->
			@include('admin.inc.header')
			<!-- END: Header -->

			<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

				<!-- BEGIN: Left Aside -->
				@include('admin.inc.side-nav-bar')
				<!-- END: Left Aside -->

				<div class="m-grid__item m-grid__item--fluid m-wrapper">

					<!-- BEGIN: Subheader -->

					<div class="m-subheader ">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<h3 class="m-subheader__title m-subheader__title--separator">@yield('subheader_title')</h3>
								<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
									<li class="m-nav__item m-nav__item--home">
										<a href="javascript:;" class="m-nav__link m-nav__link--icon">
											<i class="m-nav__link-icon la la-home"></i>
										</a>
									</li>
									@yield('content_subheader')
								</ul>
							</div>
						</div>
					</div>
					<!-- END: Subheader -->

					<div class="m-content">
						@include('admin.inc.alert')
						<!--Begin::Section-->
						@yield('content')
						<!--End::Section-->

					</div>
				</div>
			</div>

			<!-- end:: Body -->

			<!-- begin::Footer -->
			@include('admin.inc.footer')
			<!-- end::Footer -->
		</div>

		<!-- end:: Page -->

		<!-- begin::Scroll Top -->
		<div id="m_scroll_top" class="m-scroll-top">
			<i class="la la-arrow-up"></i>
		</div>

		<!-- end::Scroll Top -->

		<!--begin::Global Theme Bundle -->
		<script src="{{asset('public/assets/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
		<script src="{{asset('public/assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Vendors -->
		<script src="{{asset('public/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js')}}" type="text/javascript"></script>

		<!--end::Page Vendors -->

		<!--begin::Page Scripts -->
		<script src="{{asset('public/assets/app/js/dashboard.js')}}" type="text/javascript"></script>

		<!--end::Page Scripts -->

		<script src="{{ asset('public/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

		<script src="{{ asset('public/assets/js/custom.js') }}"></script>

		<script src="{{ asset('public/assets/js/admin-custom.js') }}"></script>

		@stack('js')

		@stack('scripts')

	</body>

	<!-- end::Body -->
</html>
