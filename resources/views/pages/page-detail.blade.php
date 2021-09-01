<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Stock Tips Apps') }}</title>

	<link rel="stylesheet" type="text/css" href="{{asset('public/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('public/css/custom.css')}}">
</head>
	<body>
		<header>
			<div class="container">
				<div class="row">
					<div class="col-12"><a href="#_"><img src="{{asset('public/images/logo.png')}}" alt=""/></a></div>
				</div>
			</div>
		</header>
		
		<div class="mid-text">

			<div class="container">
				<div class="row">
					<div class="col-12 p-bg">
			 			<h1>{!! $pageDetail->name !!}</h1>
					</div>
				</div>
			</div>

			<div class="container">
				{!! $pageDetail->content !!}
			</div>
		</div>
		
	<footer>
		<img src="{{asset('public/images/logo.png')}}" alt=""/> <p>Copyright © Tips Mandi.com {{date('Y')}}</p>
	</footer>
	</body>
</html>
