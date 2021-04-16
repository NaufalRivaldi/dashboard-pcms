@extends('layouts.content_datatable')

@section('card-button')
    <a href="{{ route('import.summary.generate') }}" class="btn btn-primary"><i class="ti-plus"></i> Generate Summary</a>

    <!-- Start - Set level if admin and approver (user cabang pusat) -->
    @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 3)
    <a href="{{ route('import.summary.create') }}" class="btn btn-info"><i class="ti-plus"></i> Add data</a>
    @endif
    <!-- End - Set level if admin and approver (user cabang pusat) -->
@endsection

@section('card-slot-up')
    @if(ImportHelper::notifSummary() > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Data ada yang masih berstatus pending,</strong> Silahkan lakukan pengecekkan dan validasi data.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
@endsection

@section('content-table')
    <th data-type="select" data-filtering='{!! parseJson($filtering->bulan) !!}'>Bulan</th>
    <th>Tahun</th>
    <th>Cabang</th>
    <th data-type="select" data-filtering='{!! parseJson($filtering->status) !!}'>Status</th>
    <th>User Pembuat</th>
    <th>User Approve</th>
    <th>Action</th>
@endsection

<!-- Start - Set column -->
@php
    $column = [
        ["data" => "bulan", "name" => "bulan", "defaultContent" => "-"],
        ["data" => "tahun", "name" => "tahun", "defaultContent" => "-"],
        ["data" => "cabang.nama", "name" => "cabang.nama", "defaultContent" => "-"],
        ["data" => "status", "name" => "status", "defaultContent" => "-"],
        ["data" => "user.nama", "name" => "user.nama", "defaultContent" => "-"],
        ["data" => "user_approve.nama", "name" => "user_approve.nama", "defaultContent" => "-"],
        ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false],
    ];
@endphp
<!-- End - Set column -->

@push('scripts')
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
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for Cabang page
        // ------------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------------
            // Delete data function
            // --------------------------------------------------------------------
            deleteData: function(id){
                // ----------------------------------------------------------------
                let url = "{{ route('import.summary.destroy', ':id') }}";
                url = url.replace(':id', id);
                // ----------------------------------------------------------------
                // Set confirm
                // ----------------------------------------------------------------
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data terhapus pada list.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    // ------------------------------------------------------------
                    if (result.value) {
                        // --------------------------------------------------------
                        // Set request
                        // --------------------------------------------------------
                        let request = axios.delete(url);
                        // --------------------------------------------------------
                        // If request success
                        // --------------------------------------------------------
                        request.then((response)=>{
                            // ----------------------------------------------------
                            let data = response.data;
                            // ----------------------------------------------------
                            $('#myDataTable').DataTable().ajax.reload();
                            // ----------------------------------------------------
                            Vue.nextTick(function () {
                                toastr.success(data.message);    
                            })
                            // ----------------------------------------------------
                        })
                        // --------------------------------------------------------
                        // If request error
                        // --------------------------------------------------------
                        request.catch((error)=>{
                            toastr.error(error.message);
                        })
                        // --------------------------------------------------------
                    }
                    // ------------------------------------------------------------
                })
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

            // --------------------------------------------------------------------
            // Delete event
            // --------------------------------------------------------------------
            $(document).on('click', '.btn-delete', function(){
                let $id = $(this).data('id');
                vm.deleteData($id);
            })
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------
    })
    // ----------------------------------------------------------------------------
</script>
@endpush