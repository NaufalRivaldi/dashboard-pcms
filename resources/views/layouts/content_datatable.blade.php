@extends('layouts.app_master')

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

@section('content')
<div id="app" class="container-fluid">
    <!-- Start - row -->
    <div class="row">
        <div class="col-md-12">
            <!-- Start - card -->
            <div class="card">
                <div class="card-header row">
                    <div class="col-sm-6 card-title">
                        <h6 class="mt-1">List</h6>
                    </div>
                    <div class="col-sm-6 text-right">
                        @yield('card-button')
                    </div>
                </div>
                <div class="card-body">
                    @yield('card-slot-up')
                    <!-- Start - Table -->
                    <div class="table-responsive">
                        <table id="myDataTable" class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    @yield('content-table')
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- End - Table -->
                </div>
                @yield('card-footer')
            </div>
            <!-- End - card -->
        </div>
    </div>
    <!-- End - row -->

    <!-- Start - Modal -->
    @yield('modal')
    <!-- End - Modal -->
</div>
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

<!-- Datatables Init -->
<script src="{{ asset('assets/js/pages/tables-dynamic.init.min.js') }}"></script>

<script>
    $(function(){
        // ----------------------------------------------------------------------------
        // Clone row on head table
        // ----------------------------------------------------------------------------
        $('#myDataTable thead tr').clone(true).appendTo('#myDataTable thead');
        // ----------------------------------------------------------------------------
        // Make column search on head table clone column
        // ----------------------------------------------------------------------------
        $('#myDataTable thead tr:eq(1) th').each( function (i) {
            // ------------------------------------------------------------------------
            var title = $(this).text();
            var type = $(this).data('type');
            // ------------------------------------------------------------------------
            // If title not action && type not none (no input for search column)
            // ------------------------------------------------------------------------
            if(title != 'Action' && type != 'none'){
                switch (type) {
                    // ----------------------------------------------------------------
                    // Select input filtering
                    // ----------------------------------------------------------------
                    case 'select':
                        // ------------------------------------------------------------
                        var data = $(this).data('filtering');
                        var text = '<select class="form-control">';
                        // ------------------------------------------------------------
                        // Set text variable
                        // ------------------------------------------------------------
                        text += '<option value="">All</option>';
                        // ------------------------------------------------------------
                        $.each(data, function(index, val){
                            text += '<option value="'+ val +'">'+ val +'</option>';
                        });
                        // ------------------------------------------------------------
                        text += '</select>';
                        // ------------------------------------------------------------
                        $(this).html(text);
                        // ------------------------------------------------------------
                        $('select', this).on('change', function(){
                            if(datatable.column(i).search() !== this.value){
                                datatable.column(i).search(this.value).draw();
                            }
                        });
                        // ------------------------------------------------------------
                    break;
                    // ----------------------------------------------------------------

                    // ----------------------------------------------------------------
                    // Text input filtering
                    // ----------------------------------------------------------------
                    default:
                        // ------------------------------------------------------------
                        $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
                        // ------------------------------------------------------------
                        $('input', this).on('keyup change', function () {
                            if (datatable.column(i).search() !== this.value) {
                                datatable.column(i).search(this.value).draw();
                            }
                        });
                        // ------------------------------------------------------------
                    break;
                }
            }else{
                $(this).html('');
            }
            
        });
        // ----------------------------------------------------------------------------

        // ----------------------------------------------------------------------------
        // Set datatable
        // ----------------------------------------------------------------------------
        var datatable = $('#myDataTable').DataTable({
            order: [[0, "asc"]],
            processing: true,
            serverSide: true,
            orderCellsTop: true,
            fixedHeader: true,
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            scrollX: true,
            autoWidth: true,
            responsive: true,
            ajax: {
                type: "GET",
                url: "{{ url()->current() . "/json/datatable" }}",
                data: {},
            },
            columns: @json($column)
        });
        // ----------------------------------------------------------------------------
    })
</script>
@endpush