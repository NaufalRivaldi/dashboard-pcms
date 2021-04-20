<!doctype html>
<html lang="en">
<head>
    <title>Compare Export</title>
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
	<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
	<!-- End - Favicon -->

    <!-- Start - Stack CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <!-- End - Stack CSS -->

    <style>
        @media print {
            .btn-print {
                display :  none !important;
            }

            .pagebrake {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="row">
            <div class="col-md-12 text-center">
                <button class="btn btn-primary btn-print" @click="print()"><i class="ti-printer"></i> Print</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <h6>
                    REPORT CABANG YANG BELUM MELAKUKAN IMPORT DATA<br>
                    PERIODE: @{{ periode }}
                </h6>
            </div>
        </div>
        <hr>
        
        <div class="row">
            <!-- Start - List cabang -->
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Cabang</th>
                                <th>Owner</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in results">
                                <td>@{{ index + 1 }}</td>
                                <td>@{{ row.nama }}</td>
                                <td>@{{ row.owner.nama }}</td>
                                <td>Belum melakukan import data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Start - List cabang -->
        </div>
    </div>

    <!-- Vendor -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- Other extension -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.min.js"></script>

    <!-- Start - Stack Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        // ----------------------------------------------------------------------------
        // Set Vue
        // ----------------------------------------------------------------------------
        new Vue({
            // ------------------------------------------------------------------------
            el: '#app',
            // ------------------------------------------------------------------------
            // Data
            // ------------------------------------------------------------------------
            data: {
                periode : "{{ $periode }}",
                results : @json($results),
            },
            // ------------------------------------------------------------------------

            computed: {
                //
            },

            // ------------------------------------------------------------------------
            // Methods
            // ------------------------------------------------------------------------
            methods: {
                print: function(){
                    window.print();
                },
            },
            // ------------------------------------------------------------------------

            // ------------------------------------------------------------------------
            // Mounted 
            // ------------------------------------------------------------------------
            mounted() {
                //
            },
            // ------------------------------------------------------------------------
        })
        // ----------------------------------------------------------------------------
    </script>

</body>
</html>