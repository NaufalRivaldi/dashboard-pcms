@extends('layouts.content_datatable')

@section('card-button')
    
@endsection

@section('card-slot-up')
    <!-- Start - Detail siswaAktif -->
    <div class="row">
        <div class="col-sm-3 font-weight-bold">Kode Cabang</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $siswaAktif->cabang->kode }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Nama Cabang</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $siswaAktif->cabang->nama }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Bulan</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ strtoupper(setMonth($siswaAktif->bulan)) }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Tahun</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $siswaAktif->tahun }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Jumlah Total Siswa Aktif</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $siswaAktif->siswa_aktif_details->sum('jumlah') }}</div>
    </div>
    <!-- End - Detail siswaAktif -->

    <hr>

    <h5>Detail Siswa Aktif Berdasarkan Jurusan</h5>
@endsection

@section('content-table')
    <th>Materi / Jurusan</th>
    <th>Jumlah</th>
    <!-- <th>Action</th> -->
@endsection

@section('card-footer')
    <div class="card-footer text-center">
        <a href="{{ route('import.la06.index') }}" class="btn btn-info">
            <i class="ti-arrow-circle-left"></i> Kembali
        </a>
    </div>
@endsection

<!-- Start - Set column -->
@php
    $column = [
        ["data" => "materi.nama", "name" => "materi.nama", "defaultContent" => "-"],
        ["data" => "jumlah", "name" => "jumlah", "defaultContent" => "-"],
        //["data" => "action", "name" => "action", "orderable" => false, "searchable" => false],
    ];
@endphp
<!-- End - Set column -->

@section('modal')
    <!-- Start - Modal edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <!-- Start - form -->
                <form action="{{ route('import.la06.show.update', $siswaAktif->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        
                        <!-- Start - Hidden input -->
                        <input type="hidden" name="id" value="" v-model="result.id">
                        <!-- End - Hidden input -->

                        <!-- Start - materi_id -->
                        <div class="form-group">
                            <div class="label-form mb-1">
                                Materi <span class="badge badge-danger">Required</span>
                            </div>
                            <div class="input-form">
                                <select name="materi_id" v-model="result.materi_id" class="form-control @if($errors->has('materi_id')) is-invalid @endif" required>
                                    <option value="">Pilih</option>
                                    @foreach($materis as $materi)
                                        <option value="{{ $materi->id }}">{{ $materi->nama }}</option>
                                    @endforeach
                                </select>
                                <!-- Start - Error handling -->
                                @if($errors->has('materi_id'))
                                    <div class="invalid-feedback">{{ $errors->first('materi_id') }}</div>
                                @endif
                                <!-- End - Error handling -->
                            </div>
                        </div>
                        <!-- End - materi_id -->

                        <!-- Start - Jumlah -->
                        <div class="form-group">
                            <div class="label-form mb-1">
                                Jumlah <span class="badge badge-danger">Required</span>
                            </div>
                            <div class="input-form">
                                <input type="text" name="jumlah" class="form-control @if($errors->has('jumlah')) is-invalid @endif" value="" v-model="result.jumlah" onkeypress="return onlyNumberKey(event)">
                                <!-- Start - Error handling -->
                                @if($errors->has('jumlah'))
                                    <div class="invalid-feedback">{{ $errors->first('jumlah') }}</div>
                                @endif
                                <!-- End - Error handling -->
                            </div>
                        </div>
                        <!-- End - Jumlah -->

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti-save"></i> Save
                        </button>
                    </div>
                </form>
                <!-- End - form -->
            </div>
        </div>
    </div>
    <!-- End - Modal edit -->

    <!-- Start - Modal Validation -->
    <div class="modal fade" id="modalValidation" tabindex="-1" aria-labelledby="modalValidationLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalValidationLabel">Verifikasi Data siswa aktif</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-success btn-block btn-accept">Accept</button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-secondary btn-block" data-dismiss="modal">Pending</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui ab labore exercitationem iste velit modi, rem eligendi? Totam aspernatur tenetur delectus porro dolores. Facilis necessitatibus aperiam officia ipsum similique ratione.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- End - Modal Validation -->
@endsection

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
            // --------------------------------------------------------------------
            siswaAktifDetails: @json($siswaAktif->siswa_aktif_details),
            // --------------------------------------------------------------------
            result: {
                siswa_aktif_detail_id: null,
                materi_id:null,
                jumlah:0,
            },
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for Cabang page
        // ------------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------------
            // Edit data function
            // --------------------------------------------------------------------
            editData: function(id){
                let data = _.filter(this.siswaAktifDetails, { id: parseInt(id) });
                this.result = data[0];
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Delete data function
            // --------------------------------------------------------------------
            deleteData: function(id){
                // ----------------------------------------------------------------
                let url = "{{ route('import.la06.show.destroy', ':id') }}";
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
                            location.reload();
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
            // Edit event
            // --------------------------------------------------------------------
            $(document).on('click', '.btn-edit', function(){
                let $id = $(this).data('id');
                vm.editData($id);
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