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
    <a href="" class="btn btn-danger"><i class="ti-file"></i> Export PDF</a>
@endsection

@section('card-content')
    <!-- Start - Filter data -->
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Dari</label>
                <input type="text" name="startDate" class="form-control date-picker-month" value="{{ $item->filterDate[0] }}">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>Sampai</label>
                <input type="text" name="endDate" class="form-control date-picker-month" value="{{ $item->filterDate[1] }}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>Cabang</label>
                <select name="cabang_id" class="form-control select2">
                    <option value="">Pilih</option>
                    @foreach($cabangs as $id => $value)
                        <option value="{{ $id }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

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
        @include('backend.analisa.includes.chart-penerimaan')
        <!-- End - Analisis chart royalti -->

        <hr>

        <!-- Start - Analisis chart royalti -->
        @include('backend.analisa.includes.chart-royalti')
        <!-- End - Analisis chart royalti -->

        <hr>

        <!-- Start - Analisis chart siswa -->
        @include('backend.analisa.includes.chart-siswa')
        <!-- End - Analisis chart siswa -->

        <hr>

        <!-- Start - Analisis chart siswa-jurusan -->
        @include('backend.analisa.includes.chart-siswa-jurusan')
        <!-- End - Analisis chart siswa-jurusan -->

        <hr>

        <!-- Start - Analisis chart siswa-pendidikan -->
        @include('backend.analisa.includes.chart-siswa-pendidikan')
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
            // Chart global data
            // --------------------------------------------------------------------
            cabang: "{{ $cabang }}",
            periode: [],
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

        // ------------------------------------------------------------------------
        // Methods for Cabang page
        // ------------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------------
            search: function(){
                // ----------------------------------------------------------------
                this.status.loading = true;
                // ----------------------------------------------------------------
                let startDate   = $("input[name='startDate']").val();
                let endDate     = $("input[name='endDate']").val();
                let cabang_id   = $("select[name='cabang_id']").val();
                let data = {
                    startDate: startDate,
                    endDate: endDate,
                    cabang_id: cabang_id,
                };
                // ----------------------------------------------------------------
                let request = axios.post("{{ route('main.analisa.search') }}", data);
                // ----------------------------------------------------------------

                // ----------------------------------------------------------------
                // Request is success
                // ----------------------------------------------------------------
                request.then((response)=>{
                    // ------------------------------------------------------------
                    let data = response.data;
                    // ------------------------------------------------------------
                    if(data.status){
                        this.labels = data.labels;
                        this.cabang = data.cabang.nama;
                        this.chartPenerimaan.dataSets = data.dataSetPenerimaan;
                        this.chartRoyalti.dataSets = data.dataSetRoyalti;
                        this.chartSiswaAktif.dataSets = data.dataSetSiswaAktif;
                        this.chartSiswaAktifJurusan.dataSets = data.dataSetSiswaAktifJurusan;
                        this.chartSiswaAktifPendidikan.dataSets = data.dataSetSiswaAktifPendidikan;
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
            }
            // --------------------------------------------------------------------
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