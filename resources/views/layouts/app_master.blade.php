<!doctype html>
<html lang="en">
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

    <!-- Toestr Js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Start - Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
	<!-- End - Fonts -->

	<!-- Start - Favicon -->
	<link rel="shortcut icon" href="{{ asset('assets/images/pcms.ico') }}">
	<!-- End - Favicon -->

    <!-- Start - Stack CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    @stack('css')
    <!-- End - Stack CSS -->
</head>
<body>
    <!-- WRAPPER -->
    <div id="wrapper">
        <!-- NAVBAR -->
        @include('layouts.components.navbar')
        <!-- END NAVBAR -->

        <!-- LEFT SIDEBAR -->
        @include('layouts.components.sidebar_left')
        <!-- END LEFT SIDEBAR -->

        <!-- MAIN -->
        <div class="main" style="height:auto">
            <!-- MAIN CONTENT -->
            <div class="main-content">
                @include('layouts.components.breadcrumb')    
                
                @include('layouts.components.alert')
                @yield('content')
            </div>
            <!-- END MAIN CONTENT -->
        </div>
        <!-- END MAIN -->

        <div class="clearfix"></div>
        
        <!-- footer -->
        <footer>
            <div class="container-fluid">
                <p class="copyright">&copy; {{ date('Y') }} Purwa Caraka Music Studio. All Rights Reserved.</p>
            </div>
        </footer>
        <!-- end footer -->
    </div>
    <!-- END WRAPPER -->

    <!-- Vendor -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- Sweetalert 2 -->
    <script src="{{ asset('js/plugin/sweetalert2.js') }}"></script>

    <!-- Toestr Js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Start - Stack Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
    <!-- End - Stack Scripts -->

    <!-- App -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <!-- Custom JS -->
    <script>
        function onlyNumberKey(evt) {
            // Only ASCII charactar in that range allowed
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }
    </script>

</body>
</html>