@extends('layouts.content_datatable')

@section('card-button')
    <a href="{{ route('import.la03.import') }}" class="btn btn-success"><i class="ti-file"></i> Import Data</a>
    <a href="{{ route('import.la03.create') }}" class="btn btn-info"><i class="ti-plus"></i> Add data</a>
@endsection

@section('content-table')
    <th data-type="select" data-filtering='{!! parseJson($filtering->bulan) !!}'>Bulan</th>
    <th>Tahun</th>
    <th data-type="select" data-filtering='{!! parseJson($filtering->type) !!}'>Type</th>
    <th>Nama Pembayar</th>
    <th>Nominal</th>
    <th data-type="select" data-filtering='{!! parseJson($filtering->cabang) !!}'>Cabang</th>
    <th>Materi</th>
    <th>Grade</th>
    <th>Action</th>
@endsection

<!-- Start - Set column -->
@php
    $column = [
        ["data" => "bulan", "name" => "bulan", "defaultContent" => "-"],
        ["data" => "tahun", "name" => "tahun", "defaultContent" => "-"],
        ["data" => "type", "name" => "type", "defaultContent" => "-"],
        ["data" => "nama_pembayar", "name" => "nama_pembayar", "defaultContent" => "-"],
        ["data" => "nominal", "name" => "nominal", "defaultContent" => "-"],
        ["data" => "cabang.nama", "name" => "cabang.nama", "defaultContent" => "-"],
        ["data" => "materi_grade.materi.nama", "name" => "materi_grade.materi.nama", "defaultContent" => "-"],
        ["data" => "materi_grade.grade.nama", "name" => "materi_grade.grade.nama", "defaultContent" => "-"],
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
            // Update status function
            // --------------------------------------------------------------------
            setStatus: function(type, id){
                // ----------------------------------------------------------------
                let url = "{{ route('import.la03.update.status', ['type' => ':type', 'id' => ':id']) }}";
                url = url.replace(':type', type);
                url = url.replace(':id', id);
                // ----------------------------------------------------------------
                // Set confirm
                // ----------------------------------------------------------------
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data status akan terubah.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ubah',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    // ------------------------------------------------------------
                    if (result.value) {
                        // --------------------------------------------------------
                        // Set request
                        // --------------------------------------------------------
                        let request = axios.put(url);
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

            // --------------------------------------------------------------------
            // Delete data function
            // --------------------------------------------------------------------
            deleteData: function(id){
                // ----------------------------------------------------------------
                let url = "{{ route('import.la03.destroy', ':id') }}";
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
            // Status event
            // --------------------------------------------------------------------
            $(document).on('click', '.btn-status', function(){
                let $id = $(this).data('id');
                let $type = $(this).data('type');
                vm.setStatus($type, $id);
            })
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