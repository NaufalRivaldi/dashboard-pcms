@extends('layouts.content_show')

@push('css')
<!-- Datatables core css -->
<link href="{{ asset('assets/plugins/datatables.net-bs4/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Datatables extensions css -->
<link href="{{ asset('assets/plugins/datatables.net-buttons-bs4/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/datatables.net-colreorder-bs4/colreorder.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

<style>
    
</style>
@endpush

@section('card-button')
    <button type="button" class="btn btn-danger" @click="exportData()" :disabled="results == null"><i class="ti-file"></i> Export PDF</button>
@endsection

@section('card-content')
    <!-- Start - Filter data -->
   <div class="row">
        <!-- Start - Filter Month -->
        <div class="col-md-3">
            <div class="form-group">
                <label>Pilih Periode</label>
                <input type="text" name="filterDate" class="form-control date-picker-month" value="{{ $item->filterDate }}">
            </div>
        </div>
        <!-- End - Filter Month -->
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
        <div class="row">
            <!-- Start - List cabang -->
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered defaultDatatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Cabang</th>
                                <th>Owner</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="t-scroll-vertikal t-max-height-400">
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
    // Set Vue
    // ----------------------------------------------------------------------------
    new Vue({
        // ------------------------------------------------------------------------
        el: '#app',
        // ------------------------------------------------------------------------
        // Data for Cabang page
        // ------------------------------------------------------------------------
        data: {
            // --------------------------------------------------------------------
            status: {
                loading: true,
            },
            // --------------------------------------------------------------------
            results : null,
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
                let filterDate  = $("input[name='filterDate']").val();
                // ----------------------------------------------------------------
                let request = axios.post("{{ route('main.report.unreport.search') }}", { filterDate: filterDate });
                // ----------------------------------------------------------------

                // ----------------------------------------------------------------
                // Request is success
                // ----------------------------------------------------------------
                request.then((response)=>{
                    // ------------------------------------------------------------
                    let data = response.data;
                    // ------------------------------------------------------------
                    if(data.status){
                        this.results = data.results;
                    }else{
                        toastr.error(data.message);
                    }
                    // ------------------------------------------------------------
                    this.status.loading = false;
                    setTimeout(() => {
                        $('.defaultDatatable').DataTable({ "ordering": false });
                    }, 200);
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
                    }, 200);
                })
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            exportData: function(){
                // ----------------------------------------------------------------
                let filterDate  = $("input[name='filterDate']").val();
                // ----------------------------------------------------------------
                let url = "{{ route('main.report.unreport.export') }}?filter_date="+filterDate;
                // ----------------------------------------------------------------
                window.open(url, '_blank');
                // ----------------------------------------------------------------
            },
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