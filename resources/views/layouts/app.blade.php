<!doctype html>
<html lang="en" class="fullscreen-bg">
<head>
	<title>{{ $title }} | Dashboard PCMS</title>
	<!-- Start - Meta tag -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- End - Meta tag -->

	<!-- Start - App css -->
	<link href="{{ asset('assets/css/bootstrap-custom.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
	<!-- End - App css -->

	<!-- Start - Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
	<!-- End - Fonts -->

	<!-- Start - Favicon -->
	<link rel="shortcut icon" href="{{ asset('assets/images/pcms.ico') }}">
	<!-- End - Favicon -->

	<!-- Start - CSS -->
	@stack('css')
	<!-- End - CSS -->
</head>
<body>
	<!-- Start - WRAPPER -->
	<div id="wrapper" class="d-flex align-items-center justify-content-center">
		<div class="auth-box ">
			<div class="left">
				<div id="app" class="content">
					@yield('content')
				</div>
			</div>
			<div class="right">
				<div class="overlay" style="background: rgba(99,156,185,0);"></div>
				<div class="content text">
					<!-- <h1 class="heading">{{ replaceUnderscore(env('APP_NAME')) }}</h1>
					<p>by The Develovers</p> -->
				</div>
			</div>
		</div>
	</div>
	<!-- End - WRAPPER -->

	<!-- Vendor -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

	<!-- Start - Script -->
	<script src="{{ asset('js/app.js') }}"></script>
	
	@stack('scripts')
	<!-- End - Script -->
</body>
</html>