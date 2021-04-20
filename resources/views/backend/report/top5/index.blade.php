@extends('layouts.content_show')

@push('css')
<!-- Datatables core css -->
<link href="{{ asset('assets/plugins/datatables.net-bs4/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Datatables extensions css -->
<link href="{{ asset('assets/plugins/datatables.net-buttons-bs4/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/datatables.net-colreorder-bs4/colreorder.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

<style>
    #myDataTable{
        width: 100% !important;
    }
</style>
@endpush

@section('card-button')
    <button type="button" class="btn btn-danger" @click="exportData()"><i class="ti-file"></i> Export PDF</button>
@endsection

@section('card-content')
    <!-- Start - Filter data -->
    <!-- Start - Change year or month -->
    <div class="custom-control custom-switch">
        <input type="checkbox" v-model="filterState" class="custom-control-input" id="switchForm" @click="setMonthPicker()">
        <label class="custom-control-label" for="switchForm">Filter per tahun?</label>
    </div>
    <!-- Emd - Change year or month -->
    
    <hr>


    <div class="row">
        <!-- Start - Periode -->
        <div class="col-md-3">
            <div class="form-group">
                <label>Periode</label>
                <input type="text" name="periodeBulan" class="form-control date-picker-month" value="{{ $item->periode }}" v-if="filterState == false">

                <select name="periodeTahun" class="form-control" v-if="filterState">
                    <option value="">Pilih</option>
                    @for($i = 2000; $i <= 2100; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <!-- End - Periode -->
    </div>

    
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary" @click="search()"><i class="ti-search"></i> Search</button>
        </div>
    </div>
    
    <hr>
    <!-- End - Filter data -->

    <template v-if="status.loading">
        <center>
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </center>
    </template>

    <template v-else>
        <!-- Start - Analisis chart penerimaan -->
        @include('backend.report.top5.includes.chart-penerimaan')
        <!-- End - Analisis chart royalti -->

        <hr>

        <!-- Start - Analisis chart royalti -->
        @include('backend.report.top5.includes.chart-royalti')
        <!-- End - Analisis chart royalti -->

        <hr>

        <!-- Start - Analisis chart siswa -->
        @include('backend.report.top5.includes.chart-siswa')
        <!-- End - Analisis chart siswa -->

        <hr>

        <!-- Start - Analisis chart siswa-jurusan -->
        @include('backend.report.top5.includes.chart-siswa-jurusan')
        <!-- End - Analisis chart siswa-jurusan -->

        <hr>

        <!-- Start - Analisis chart siswa-pendidikan -->
        @include('backend.report.top5.includes.chart-siswa-pendidikan')
        <!-- End - Analisis chart siswa-pendidikan -->
    </template>
@endsection

@section('card-footer')
    <!-- Card footer code here -->
@endsection

@section('modal')
    <!-- Modal code here -->
@endsection

@push('scripts')
<!-- Datables Core -->
<script src="{{ asset('assets/plugins/datatables.net/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs4/dataTables.bootstrap4.min.js') }}"></script>

<!-- Datables Extension -->
<script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-buttons-bs4/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-colreorder/dataTables.colReorder.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-colreorder-bs4/colReorder.bootstrap4.min.js') }}"></script>

<!-- Other extension -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.min.js"></script>

<script>
    $(document).ready(function() {
        $('.defaultDatatable').DataTable({ "ordering": false });
    } );
</script>

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
                }
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
            periode: "{{ $item->periode }}",
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
            // --------------------------------------------------------------------
            search: function(){
                // ----------------------------------------------------------------
                this.status.loading = true;
                // ----------------------------------------------------------------
                let periodeBulan   = $("input[name='periodeBulan']").val();
                let periodeTahun   = $("select[name='periodeTahun']").val();
                let data = {
                    periodeBulan: periodeBulan,
                    periodeTahun: periodeTahun,
                };
                // ----------------------------------------------------------------
                let request = axios.post("{{ route('main.report.top5.search') }}", data);
                // ----------------------------------------------------------------

                // ----------------------------------------------------------------
                // Request is success
                // ----------------------------------------------------------------
                request.then((response)=>{
                    // ------------------------------------------------------------
                    let data = response.data;
                    // ------------------------------------------------------------
                    if(data.status){
                        this.periode = data.periode;
                        this.chartPenerimaan.labels             = data.dataSetPenerimaan.labels;
                        this.chartPenerimaan.dataSets           = data.dataSetPenerimaan.result;
                        this.chartRoyalti.labels                = data.dataSetRoyalti.labels;
                        this.chartRoyalti.dataSets              = data.dataSetRoyalti.result;
                        this.chartSiswaAktif.labels             = data.dataSetSiswaAktif.labels;
                        this.chartSiswaAktif.dataSets           = data.dataSetSiswaAktif.result;
                        this.chartSiswaAktifJurusan.labels      = data.dataSetSiswaAktifJurusan.labels;
                        this.chartSiswaAktifJurusan.dataSets    = data.dataSetSiswaAktifJurusan.result;
                        this.chartSiswaAktifPendidikan.labels   = data.dataSetSiswaAktifPendidikan.labels;
                        this.chartSiswaAktifPendidikan.dataSets = data.dataSetSiswaAktifPendidikan.result;
                    }else{
                        toastr.error(data.message);
                    }
                    // ------------------------------------------------------------
                    this.status.loading = false;

                    setTimeout(() => {
                        $('.defaultDatatable').DataTable({ "ordering": false });
                    }, 500);
                })
                // ----------------------------------------------------------------

                // ----------------------------------------------------------------
                // Request is errors
                // ----------------------------------------------------------------
                request.catch((error)=>{
                    toastr.error(error.response.data.message);
                    this.status.loading = false;

                    setTimeout(() => {
                        $('.defaultDatatable').DataTable({ "ordering": false });
                    }, 500);
                })
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            exportData: function(){
                // ----------------------------------------------------------------
                let periodeBulan   = $("input[name='periodeBulan']").val();
                let periodeTahun   = $("select[name='periodeTahun']").val();
                // ----------------------------------------------------------------
                let url = "{{ route('main.report.top5.export') }}?periode_bulan="+periodeBulan+"&periode_tahun="+periodeTahun;
                // ----------------------------------------------------------------
                window.open(url, '_blank');
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            setMonthPicker: function(){
                setTimeout(() => {
                    $('.date-picker-month').datepicker( {
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: 'MM yy',
                        onClose: function(dateText, inst) { 
                            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                            let val = new Date(inst.selectedYear, inst.selectedMonth, 1);
                        }
                    });
                }, 200);
            },
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for Cabang page
        // ------------------------------------------------------------------------
        mounted() {
            // --------------------------------------------------------------------
            let vm = this;
            // --------------------------------------------------------------------
            this.status.loading = false;
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------
    })
    // ----------------------------------------------------------------------------
</script>
@endpush