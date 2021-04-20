<!doctype html>
<html lang="en">
<head>
    <title>Top5 Export</title>
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

            .chartSize {
                min-height: 100%;
                max-width: 100%;
                max-height: 100%;
                height: auto!important;
                width: auto!important;
            }

            .table-jurusan { width: 100% !important; font-size: .6em !important; }
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
        
        <section class="pagebrake">
            <!-- Start - Analisis chart penerimaan -->
            @include('backend.report.top5.includes.chart-penerimaan')
            <!-- End - Analisis chart royalti -->
            <hr>
            <!-- Start - Analisis chart royalti -->
            @include('backend.report.top5.includes.chart-royalti')
            <!-- End - Analisis chart royalti -->
            <hr>
        </section>

        <section class="pagebrake mt-1">
            <!-- Start - Analisis chart siswa -->
            @include('backend.report.top5.includes.chart-siswa')
            <!-- End - Analisis chart siswa -->
            <hr>
        </section>

        <section class="pagebrake mt-1">
            <!-- Start - Analisis chart siswa-jurusan -->
            @include('backend.report.top5.includes.chart-siswa-jurusan')
            <!-- End - Analisis chart siswa-jurusan -->
            <hr>
        </section>

        <section class="pagebrake mt-1">
            <!-- Start - Analisis chart siswa-pendidikan -->
            @include('backend.report.top5.includes.chart-siswa-pendidikan')
            <!-- End - Analisis chart siswa-pendidikan -->
        </section>
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
        // Component chart penerimaan
        // ----------------------------------------------------------------------------
        Vue.component('chart-penerimaan', {
            extends: VueChartJs.Bar,
            props: ['label', 'dataset'],
            mounted () {
                this.renderChart({
                    labels: this.label,
                    datasets: this.dataset
                }, 
                {
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: { 
                        mode: 'label', 
                        label: 'mylabel', 
                        callbacks: { 
                            label: function(tooltipItem, data) { 
                                return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
                            }
                        }, 
                    },
                })
            }
        })
        // ----------------------------------------------------------------------------

        // ----------------------------------------------------------------------------
        // Component chart royalti
        // ----------------------------------------------------------------------------
        Vue.component('chart-royalti', {
            extends: VueChartJs.Bar,
            props: ['label', 'dataset'],
            mounted () {
                this.renderChart({
                    labels: this.label,
                    datasets: this.dataset
                }, 
                {
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: { 
                        mode: 'label', 
                        label: 'mylabel', 
                        callbacks: { 
                            label: function(tooltipItem, data) { 
                                return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
                            }, 
                        }, 
                    }, 
                    scales: {
                        xAxes: [
                            {
                                ticks: {
                                    callback: function(label) {
                                        if (/\s/.test(label)) {
                                            return label.replace(" / ", " ").split(" ");
                                        }else{
                                            return label;
                                        }              
                                    }
                                }
                            }
                        ]
                    }
                })
            }
        })
        // ----------------------------------------------------------------------------

        // ----------------------------------------------------------------------------
        // Component chart siswa
        // ----------------------------------------------------------------------------
        Vue.component('chart-siswa', {
            extends: VueChartJs.Bar,
            props: ['label', 'dataset'],
            mounted () {
                this.renderChart({
                    labels: this.label,
                    datasets: this.dataset
                }, 
                {
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: { 
                        mode: 'label', 
                        label: 'mylabel', 
                        callbacks: { 
                            label: function(tooltipItem, data) { 
                                return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
                            }, 
                        }, 
                    },
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                        },
                        xAxes: [
                            {
                                ticks: {
                                    callback: function(label) {
                                        if (/\s/.test(label)) {
                                            return label.replace(" / ", " ").split(" ");
                                        }else{
                                            return label;
                                        }              
                                    }
                                }
                            }
                        ]
                    }
                })
            }
        })
        // ----------------------------------------------------------------------------

        // ----------------------------------------------------------------------------
        // Component chart siswa jurusan
        // ----------------------------------------------------------------------------
        Vue.component('chart-siswa-jurusan', {
            extends: VueChartJs.Bar,
            props: ['label', 'dataset'],
            mounted () {
                this.renderChart({
                    labels: this.label,
                    datasets: this.dataset
                }, 
                {
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: { 
                        mode: 'label', 
                        label: 'mylabel', 
                        callbacks: { 
                            label: function(tooltipItem, data) { 
                                return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
                            }, 
                        }, 
                    }, 
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true
                        }
                    },
                })
            }
        })
        // ----------------------------------------------------------------------------

        // ----------------------------------------------------------------------------
        // Component chart siswa pendidikan
        // ----------------------------------------------------------------------------
        Vue.component('chart-siswa-pendidikan', {
            extends: VueChartJs.Bar,
            props: ['label', 'dataset'],
            mounted () {
                this.renderChart({
                    labels: this.label,
                    datasets: this.dataset
                }, 
                {
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: { 
                        mode: 'label', 
                        label: 'mylabel', 
                        callbacks: { 
                            label: function(tooltipItem, data) { 
                                return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
                            }, 
                        }, 
                    },
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true
                        }
                    }, 
                })
            }
        })
        // ----------------------------------------------------------------------------

        // ----------------------------------------------------------------------------
        // Set Vue
        // ----------------------------------------------------------------------------
        new Vue({
            // ------------------------------------------------------------------------
            el: '#app',
            // ------------------------------------------------------------------------
            // Data for Cabang page
            // ------------------------------------------------------------------------
            data: {
                status: {
                    loading: true,
                },
                // --------------------------------------------------------------------
                // Form data
                // --------------------------------------------------------------------
                filterState: false,
                // --------------------------------------------------------------------
                // Chart global data
                // --------------------------------------------------------------------
                periode: "{{ $periode }}",
                // --------------------------------------------------------------------

                // --------------------------------------------------------------------
                // Chart uang penerimaan (uang daftar, uang kursus, total penerimaan)
                // --------------------------------------------------------------------
                chartPenerimaan: {
                labels  : @json($dataSetPenerimaan['labels']),
                dataSets: @json($dataSetPenerimaan['result']),
                },
                // --------------------------------------------------------------------
                // Chart uang royalti
                // --------------------------------------------------------------------
                chartRoyalti: {
                    labels  : @json($dataSetRoyalti['labels']),
                    dataSets: @json($dataSetRoyalti['result']),
                },
                // --------------------------------------------------------------------
                // Chart siswa
                // --------------------------------------------------------------------
                chartSiswaAktif: {
                    labels  : @json($dataSetSiswaAktif['labels']),
                    dataSets: @json($dataSetSiswaAktif['result']),
                },
                // --------------------------------------------------------------------
                // Chart siswa aktif jurusan
                // --------------------------------------------------------------------
                chartSiswaAktifJurusan: {
                    labels  : @json($dataSetSiswaAktifJurusan['labels']),
                    dataSets: @json($dataSetSiswaAktifJurusan['result']),
                },
                // --------------------------------------------------------------------
                // Chart siswa aktif pendidikan
                // --------------------------------------------------------------------
                chartSiswaAktifPendidikan: {
                    labels  : @json($dataSetSiswaAktifPendidikan['labels']),
                    dataSets: @json($dataSetSiswaAktifPendidikan['result']),
                },
                // --------------------------------------------------------------------
            },
            // ------------------------------------------------------------------------

            computed: {
                //
            },

            // ------------------------------------------------------------------------
            // Methods for Cabang page
            // ------------------------------------------------------------------------
            methods: {
                print: function(){
                    window.print();
                },
            },
            // ------------------------------------------------------------------------

            // ------------------------------------------------------------------------
            // Mounted for Cabang page
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