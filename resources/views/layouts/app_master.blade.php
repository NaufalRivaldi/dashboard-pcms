<!doctype html>
<html lang="en">
<head>
    <title>{{ $title }} | {{ replaceUnderscore(env('APP_NAME')) }}</title>
    <!-- Start - Meta tag -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- End - Meta tag -->

    <!-- jQuery UI required by datepicker editable -->
    <link href="assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />

    <!-- JQVMap css -->
    <link href="assets/plugins/jqvmap/jqvmap.min.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Tour css -->
    <link href="assets/plugins/bootstrap-tour/bootstrap-tour-standalone.min.css" rel="stylesheet" type="text/css" />

    <!-- Start - App css -->
    <link href="assets/css/bootstrap-custom.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- End - App css -->

    <!-- Start - Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
	<!-- End - Fonts -->

	<!-- Start - Favicon -->
	<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
	<!-- End - Favicon -->

    <!-- Start - Stack CSS -->
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
        <div class="main">
            <!-- MAIN CONTENT -->
            <div class="main-content">
                @include('layouts.components.breadcrumb')    

                @yield('content')
            </div>
            <!-- END MAIN CONTENT -->
        </div>
        <!-- END MAIN -->

        <div class="clearfix"></div>
        
        <!-- footer -->
        <footer>
            <div class="container-fluid">
                <p class="copyright">&copy; 2021 Naufal Rivaldi. All Rights Reserved.</p>
            </div>
        </footer>
        <!-- end footer -->
    </div>
    <!-- END WRAPPER -->

    <!-- Vendor -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('assets/plugins/jquery-jeditable/jquery.jeditable.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-jeditable/jquery.jeditable.masked.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script> <!-- required by datepicker plugin -->
    <script src="{{ asset('assets/plugins/jquery-jeditable/jquery.jeditable.datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jqvmap/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.canvaswrapper.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.colorhelpers.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.saturated.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.browser.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.drawSeries.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.uiConstants.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.legend.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.hover.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.flot.tooltip/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/justgage/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/justgage/justgage.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs4/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.appear/jquery.appear.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-tour/bootstrap-tour-standalone.js') }}"></script>

    <!-- Init -->
    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/ui-dragdroppanel.init.min.js') }}"></script>

    <!-- App -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <!-- Start - Stack Scripts -->
    @stack('scripts')
    <!-- End - Stack Scripts -->
</body>
</html>