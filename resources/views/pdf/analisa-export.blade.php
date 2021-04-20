<!doctype html>
<html lang="en">
<head>
    <title>Analisa Export</title>
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
        <!-- Start - Analisis chart penerimaan -->
        <section class="pagebrake">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h6>
                        SUMMARY LAPORAN PENERIMAAN<br>
                        <span v-if="cabang != null">
                            CABANG: @{{ cabang }}<br>
                        </span>

                        <span v-if="wilayah != null">
                            WILAYAH: @{{ wilayah }}<br>
                        </span>

                        <span v-if="subWilayah != null">
                            SUB WILAYAH: @{{ subWilayah }}<br>
                        </span>
                        PERIODE: @{{ labels.length > 0 ? labels[0]+' - '+labels[labels.length - 1] : '-' }}
                    </h6>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <center>
                        <chart-penerimaan :dataset="chartPenerimaan.dataSets" :label="labels" :width="1300" :height="500"></chart-penerimaan>                
                    </center>
                </div>

                <!-- Start - Table uang penerimaan -->
                <div class="col-md-12">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>Periode</th>
                            <th>Uang Pendaftaran</th>
                            <th>Uang Kursus</th>
                            <th>Total Penerimaan</th>
                        </tr>
                        
                        <tr v-for="(label, index) in labels">
                            <td>@{{ label }}</td>
                            <td align="right">@{{ chartPenerimaan.dataSets[1].data[index] | numeral('0,0') }}</td>
                            <td align="right">@{{ chartPenerimaan.dataSets[2].data[index] | numeral('0,0') }}</td>
                            <td align="right">@{{ chartPenerimaan.dataSets[0].data[index] | numeral('0,0') }}</td>
                        </tr>

                        <tr>
                            <th class="text-center">Total</th>
                            <th class="text-right">@{{ _.sum(chartPenerimaan.dataSets[1].data) | numeral('0,0') }}</th>
                            <th class="text-right">@{{ _.sum(chartPenerimaan.dataSets[2].data) | numeral('0,0') }}</th>
                            <th class="text-right">@{{ _.sum(chartPenerimaan.dataSets[0].data) | numeral('0,0') }}</th>
                        </tr>
                    </table>
                </div>
                <!-- Start - Table uang penerimaan -->
            </div>
            <!-- End - Analisis chart royalti -->

            <hr>
        </section>

        <!-- Start - Analisis chart royalti -->
        <section class="pagebrake">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h6>
                        LAPORAN JUMLAH ROYALTI<br>
                        <span v-if="cabang != null">
                            CABANG: @{{ cabang }}<br>
                        </span>

                        <span v-if="wilayah != null">
                            WILAYAH: @{{ wilayah }}<br>
                        </span>

                        <span v-if="subWilayah != null">
                            SUB WILAYAH: @{{ subWilayah }}<br>
                        </span>
                        PERIODE: @{{ labels.length > 0 ? labels[0]+' - '+labels[labels.length - 1] : '-' }}
                    </h6>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <chart-royalti :dataset="chartRoyalti.dataSets" :label="labels"></chart-royalti>
                </div>

                <!-- Start - Table uang royalti -->
                <div class="col-md-12">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>Periode</th>
                            <th>Royalti</th>
                        </tr>

                        <tr v-for="(label, index) in labels">
                            <td>@{{ label }}</td>
                            <td align="right">@{{ chartRoyalti.dataSets[0].data[index] | numeral('0,0') }}</td>
                        </tr>

                        <tr>
                            <th class="text-center">Total</th>
                            <th class="text-right">@{{ _.sum(chartRoyalti.dataSets[0].data) | numeral('0,0') }}</th>
                        </tr>
                    </table>
                </div>
                <!-- Start - Table uang royalti -->
            </div>
            <!-- End - Analisis chart royalti -->

            <hr>
        </section>

        <!-- Start - Analisis chart siswa -->
        <section class="pagebrake">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h6>
                        SUMMARY LAPORAN SISWA AKTIF<br>
                        <span v-if="cabang != null">
                            CABANG: @{{ cabang }}<br>
                        </span>

                        <span v-if="wilayah != null">
                            WILAYAH: @{{ wilayah }}<br>
                        </span>

                        <span v-if="subWilayah != null">
                            SUB WILAYAH: @{{ subWilayah }}<br>
                        </span>
                        PERIODE: @{{ labels.length > 0 ? labels[0]+' - '+labels[labels.length - 1] : '-' }}
                    </h6>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <chart-siswa :dataset="chartSiswaAktif.dataSets" :label="labels"></chart-siswa>
                </div>

                <!-- Start - Table uang siswa -->
                <div class="col-md-12">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>Periode</th>
                            <th>Siswa Aktif</th>
                            <th>Siswa Baru</th>
                            <th>Siswa Cuti</th>
                            <th>Siswa Keluar</th>
                        </tr>
                        
                        <tr v-for="(label, index) in labels">
                            <td>@{{ label }}</td>
                            <td align="right">@{{ chartSiswaAktif.dataSets[0].data[index] | numeral('0,0') }}</td>
                            <td align="right">@{{ chartSiswaAktif.dataSets[1].data[index] | numeral('0,0') }}</td>
                            <td align="right">@{{ chartSiswaAktif.dataSets[2].data[index] | numeral('0,0') }}</td>
                            <td align="right">@{{ chartSiswaAktif.dataSets[3].data[index] | numeral('0,0') }}</td>
                        </tr>

                        <tr>
                            <th class="text-center">Total</th>
                            <th class="text-right">@{{ _.sumBy(chartSiswaAktif.dataSets[0].data, item => Number(item)) | numeral('0,0') }}</th>
                            <th class="text-right">@{{ _.sumBy(chartSiswaAktif.dataSets[1].data, item => Number(item)) | numeral('0,0') }}</th>
                            <th class="text-right">@{{ _.sumBy(chartSiswaAktif.dataSets[2].data, item => Number(item)) | numeral('0,0') }}</th>
                            <th class="text-right">@{{ _.sumBy(chartSiswaAktif.dataSets[3].data, item => Number(item)) | numeral('0,0') }}</th>
                        </tr>
                    </table>
                </div>
                <!-- Start - Table uang siswa -->
            </div>
            <!-- End - Analisis chart siswa -->

            <hr>
        </section>

        <!-- Start - Analisis chart siswa-jurusan -->
        <section class="pagebrake">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h6>
                        SUMMARY LAPORAN SISWA AKTIF BERDASARKAN JURUSAN<br>
                        <span v-if="cabang != null">
                            CABANG: @{{ cabang }}<br>
                        </span>

                        <span v-if="wilayah != null">
                            WILAYAH: @{{ wilayah }}<br>
                        </span>

                        <span v-if="subWilayah != null">
                            SUB WILAYAH: @{{ subWilayah }}<br>
                        </span>
                        PERIODE: @{{ labels.length > 0 ? labels[0]+' - '+labels[labels.length - 1] : '-' }}
                    </h6>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <chart-siswa :dataset="chartSiswaAktifJurusan.dataSets" :label="labels"></chart-siswa>
                </div>

                <!-- Start - Table uang siswa -->
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Periode</th>
                                <th v-for="(value, index) in chartSiswaAktifJurusan.dataSets">
                                    @{{ value.label }}
                                </th>
                            </tr>

                            <tr v-for="(label, index) in labels">
                                <td>@{{ label }}</td>
                                <td align="right" v-for="(value, indexSiswa) in chartSiswaAktifJurusan.dataSets">
                                    @{{ chartSiswaAktifJurusan.dataSets[indexSiswa].data[index] | numeral('0,0') }}
                                </td>
                            </tr>

                            <tr>
                                <th>Total</th>
                                <th class="text-right" v-for="(value, indexSiswa) in chartSiswaAktifJurusan.dataSets">
                                    @{{ _.sumBy(chartSiswaAktifJurusan.dataSets[indexSiswa].data, item => Number(item)) | numeral('0,0') }}
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- Start - Table uang siswa -->
            </div>
            <!-- End - Analisis chart siswa-jurusan -->

            <hr>

            <!-- Start - Analisis chart siswa-pendidikan -->
            <div class="row">
                <div class="col-md-12 text-center">
                    <h6>
                        SUMMARY LAPORAN SISWA AKTIF BERDASARKAN Pendidikan<br>
                        <span v-if="cabang != null">
                            CABANG: @{{ cabang }}<br>
                        </span>

                        <span v-if="wilayah != null">
                            WILAYAH: @{{ wilayah }}<br>
                        </span>

                        <span v-if="subWilayah != null">
                            SUB WILAYAH: @{{ subWilayah }}<br>
                        </span>
                        PERIODE: @{{ labels.length > 0 ? labels[0]+' - '+labels[labels.length - 1] : '-' }}
                    </h6>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <chart-siswa :dataset="chartSiswaAktifPendidikan.dataSets" :label="labels"></chart-siswa>
                </div>

                <!-- Start - Table uang siswa -->
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Periode</th>
                                <th v-for="(value, index) in chartSiswaAktifPendidikan.dataSets">
                                    @{{ value.label }}
                                </th>
                            </tr>

                            <tr v-for="(label, index) in labels">
                                <td>@{{ label }}</td>
                                <td align="right" v-for="(value, indexSiswa) in chartSiswaAktifPendidikan.dataSets">
                                    @{{ chartSiswaAktifPendidikan.dataSets[indexSiswa].data[index] | numeral('0,0') }}
                                </td>
                            </tr>

                            <tr>
                                <th>Total</th>
                                <th class="text-right" v-for="(value, indexSiswa) in chartSiswaAktifPendidikan.dataSets">
                                    @{{ _.sumBy(chartSiswaAktifPendidikan.dataSets[indexSiswa].data, item => Number(item)) | numeral('0,0') }}
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- Start - Table uang siswa -->
            </div>
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
                    responsive: false,
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
                filter: {
                    cabang: true,
                    wilayah: false,
                    subWilayah: false,
                },
                // --------------------------------------------------------------------
                // Form data
                // --------------------------------------------------------------------
                filterState: false,
                // --------------------------------------------------------------------
                // Chart global data
                // --------------------------------------------------------------------
                cabang: "{{ $cabang }}",
                wilayah: "{{ $wilayah }}",
                subWilayah: "{{ $sub_wilayah }}",
                labels: @json($labels),
                // --------------------------------------------------------------------

                // --------------------------------------------------------------------
                // Chart uang penerimaan (uang daftar, uang kursus, total penerimaan)
                // --------------------------------------------------------------------
                chartPenerimaan: {
                    dataSets: @json($dataSetPenerimaan),
                },
                // --------------------------------------------------------------------
                // Chart uang royalti
                // --------------------------------------------------------------------
                chartRoyalti: {
                    dataSets: @json($dataSetRoyalti),
                },
                // --------------------------------------------------------------------
                // Chart siswa
                // --------------------------------------------------------------------
                chartSiswaAktif: {
                    dataSets: @json($dataSetSiswaAktif),
                },
                // --------------------------------------------------------------------
                // Chart siswa aktif jurusan
                // --------------------------------------------------------------------
                chartSiswaAktifJurusan: {
                    dataSets: @json($dataSetSiswaAktifJurusan),
                },
                // --------------------------------------------------------------------
                // Chart siswa aktif pendidikan
                // --------------------------------------------------------------------
                chartSiswaAktifPendidikan: {
                    dataSets: @json($dataSetSiswaAktifPendidikan),
                },
                // --------------------------------------------------------------------
            },
            // ------------------------------------------------------------------------

            computed: {
                widthScreen: function(){
                    return screen.width;
                }
            },

            // ------------------------------------------------------------------------
            // Methods for Cabang page
            // ------------------------------------------------------------------------
            methods: {
                print: function(){
                    window.print();
                }
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