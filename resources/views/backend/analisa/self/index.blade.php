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
        <!-- Start - Dari -->
        <div class="col-md-3">
            <div class="form-group">
                <label>Dari</label>
                <input type="text" name="startDate" class="form-control date-picker-month" value="{{ $item->filterDate[0] }}" v-if="filterState == false">

                <select name="startYear" class="form-control" v-if="filterState">
                    <option value="">Pilih</option>
                    @for($i = 2000; $i <= 2100; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <!-- End - Dari -->

        <!-- Start - Sampai -->
        <div class="col-md-3">
            <div class="form-group">
                <label>Sampai</label>
                <input type="text" name="endDate" class="form-control date-picker-month" value="{{ $item->filterDate[1] }}" v-if="filterState == false">

                <select name="endYear" class="form-control" v-if="filterState">
                    <option value="">Pilih</option>
                    @for($i = 2000; $i <= 2100; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <!-- End - Sampai -->
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Cabang</label>
                <select name="cabang_id" class="form-control select2" :disabled="filter.cabang == false ? true : false">
                    <option value="">Pilih</option>
                    @foreach($cabangs as $id => $value)
                        <option value="{{ $id }}">{{ $value }}</option>
                    @endforeach
                </select>

                <!-- Start - Checkbox -->
                <input type="checkbox" v-model="filter.cabang" @click="checkFilter('cabang')"> Filter sesuai dengan cabang?
                <!-- End - Checkbox -->
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>Wilayah</label>
                <select name="wilayah_id" class="form-control select2" :disabled="filter.wilayah == false ? true : false">
                    <option value="">Pilih</option>
                    @foreach($wilayahs as $id => $value)
                        <option value="{{ $id }}">{{ $value }}</option>
                    @endforeach
                </select>

                <!-- Start - Checkbox -->
                <input type="checkbox" v-model="filter.wilayah" @click="checkFilter('wilayah')"> Filter sesuai dengan wilayah?
                <!-- End - Checkbox -->
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>Sub Wilayah</label>
                <select name="sub_wilayah_id" class="form-control select2" :disabled="filter.subWilayah == false ? true : false">
                    <option value="">Pilih</option>
                    @foreach($subWilayahs as $id => $value)
                        <option value="{{ $id }}">{{ $value }}</option>
                    @endforeach
                </select>

                <!-- Start - Checkbox -->
                <input type="checkbox" v-model="filter.subWilayah" @click="checkFilter('subWilayah')"> Filter sesuai dengan sub wilayah?
                <!-- End - Checkbox -->
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
        @include('backend.analisa.self.includes.chart-penerimaan')
        <!-- End - Analisis chart royalti -->

        <hr>

        <!-- Start - Analisis chart royalti -->
        @include('backend.analisa.self.includes.chart-royalti')
        <!-- End - Analisis chart royalti -->

        <hr>

        <!-- Start - Analisis chart siswa -->
        @include('backend.analisa.self.includes.chart-siswa')
        <!-- End - Analisis chart siswa -->

        <hr>

        <!-- Start - Analisis chart siswa-jurusan -->
        @include('backend.analisa.self.includes.chart-siswa-jurusan')
        <!-- End - Analisis chart siswa-jurusan -->

        <hr>

        <!-- Start - Analisis chart siswa-pendidikan -->
        @include('backend.analisa.self.includes.chart-siswa-pendidikan')
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
            wilayah: null,
            subWilayah: null,
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
            //
        },

        // ------------------------------------------------------------------------
        // Methods for Cabang page
        // ------------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------------
            // Check filter condition form
            // --------------------------------------------------------------------
            checkFilter: function(type){
                // ----------------------------------------------------------------
                switch (type) {
                    case 'cabang':
                        this.filter.wilayah = false;
                        this.filter.subWilayah = false;
                        break;

                    case 'wilayah':
                        this.filter.cabang = false;
                        this.filter.subWilayah = false;
                        break;

                    case 'subWilayah':
                        this.filter.wilayah = false;
                        this.filter.cabang = false;
                        break;
                
                    default:
                        break;
                }
                // ----------------------------------------------------------------
                $('.select2').val(null).trigger('change');
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            search: function(){
                // ----------------------------------------------------------------
                this.status.loading = true;
                // ----------------------------------------------------------------
                let startDate   = $("input[name='startDate']").val();
                let endDate     = $("input[name='endDate']").val();
                let startYear   = $("select[name='startYear']").val();
                let endYear     = $("select[name='endYear']").val();
                let cabang_id   = $("select[name='cabang_id']").val();
                let wilayah_id  = $("select[name='wilayah_id']").val();
                let sub_wilayah_id   = $("select[name='sub_wilayah_id']").val();
                let data = {
                    startDate: startDate,
                    endDate: endDate,
                    startYear: startYear,
                    endYear: endYear,
                    cabang_id: cabang_id,
                    wilayah_id: wilayah_id,
                    sub_wilayah_id: sub_wilayah_id,
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
                        this.cabang = data.cabang;
                        this.wilayah = data.wilayah;
                        this.subWilayah = data.sub_wilayah;
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
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            exportData: function(){
                // ----------------------------------------------------------------
                let startDate   = $("input[name='startDate']").val();
                let endDate     = $("input[name='endDate']").val();
                let startYear   = $("select[name='startYear']").val();
                let endYear     = $("select[name='endYear']").val();
                let cabang_id   = $("select[name='cabang_id']").val();
                let wilayah_id  = $("select[name='wilayah_id']").val();
                let sub_wilayah_id   = $("select[name='sub_wilayah_id']").val();
                // ----------------------------------------------------------------
                let url = "{{ route('main.analisa.export') }}?start_date="+startDate+"&end_date="+endDate+"&start_year="+startYear+"&end_year="+endYear+"&cabang_id="+cabang_id+"&wilayah_id="+wilayah_id+"&sub_wilayah_id="+sub_wilayah_id;
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