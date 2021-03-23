@extends('layouts.content_datatable')

@section('card-button')
    <a href="{{ route('import.la03.show.pdf', $pembayaran->id) }}" class="btn btn-danger  @if($pembayaran->status == 0) disabled @endif"><i class="ti-file"></i> Export PDF</a>

    @if($pembayaran->status == 0)
        <button class="btn btn-success" data-toggle="modal" data-target="#modalValidation"><i class="ti-check"></i> Accept</button>
    @endif
@endsection

@section('card-slot-up')
    <!-- Start - Detail pembayaran -->
    <div class="row">
        <div class="col-sm-3 font-weight-bold">Kode Cabang</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $pembayaran->cabang->kode }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Nama Cabang</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $pembayaran->cabang->nama }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Bulan</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ setMonth($pembayaran->bulan) }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Tahun</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $pembayaran->tahun }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Status</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{!! statusValidate($pembayaran->status) !!}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Penerimaan uang pendaftaran</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">@{{ uangPendaftaran | numeral('0,0') }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Penerimaan uang kursus</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">@{{ uangKursus | numeral('0,0') }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Total Penerimaan</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">@{{ uangTotal | numeral('0,0') }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Royalti (10%)</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">@{{ uangRoyalti | numeral('0,0') }}</div>
    </div>
    <!-- End - Detail pembayaran -->

    <hr>

    <h5>Detail Pembayaran</h5>
@endsection

@section('content-table')
    <th data-type="select" data-filtering='{!! parseJson($filtering->type) !!}'>Type</th>
    <th>Nama Pembayar</th>
    <th>Nominal (Rp.)</th>
    <th>Action</th>
@endsection

@section('card-footer')
    <div class="card-footer text-center">
        <a href="{{ route('import.la03.index') }}" class="btn btn-info">
            <i class="ti-arrow-circle-left"></i> Kembali
        </a>
    </div>
@endsection

<!-- Start - Set column -->
@php
    $column = [
        ["data" => "type", "name" => "type", "defaultContent" => "-"],
        ["data" => "nama_pembayar", "name" => "nama_pembayar", "defaultContent" => "-"],
        ["data" => "nominal", "name" => "nominal", "defaultContent" => "-"],
        ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false],
    ];
@endphp
<!-- End - Set column -->

@section('modal')
    <!-- Modal -->
    <div class="modal fade" id="modalValidation" tabindex="-1" aria-labelledby="modalValidationLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalValidationLabel">Verifikasi Data Pembayaran</h5>
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
            uangPendaftaran: 0,
            uangKursus: 0,
            uangTotal: 0,
            uangRoyalti: 0,

            pembayaranDetails: @json($pembayaran->pembayaran_details),
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for Cabang page
        // ------------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------------
            // Calculation data function
            // --------------------------------------------------------------------
            calculation: function(){
                // ----------------------------------------------------------------
                let vm = this; 
                let pendaftaran = 0; let kursus = 0; let total = 0; let royalti = 0;
                // ----------------------------------------------------------------
                // uang pendaftaran
                // ----------------------------------------------------------------
                $.each(vm.pembayaranDetails, function(idx, value){
                    if(value.type == 1){
                        pendaftaran += value.nominal;
                    }
                });

                vm.uangPendaftaran = pendaftaran;
                // ----------------------------------------------------------------
                // uang kursus
                // ----------------------------------------------------------------
                $.each(vm.pembayaranDetails, function(idx, value){
                    if(value.type == 2){
                        kursus += value.nominal;
                    }
                });

                vm.uangKursus = kursus;
                // ----------------------------------------------------------------
                // uang total
                // ----------------------------------------------------------------
                vm.uangTotal = kursus + pendaftaran;
                // ----------------------------------------------------------------
                // uang royalti
                // ----------------------------------------------------------------
                vm.uangRoyalti = (kursus + pendaftaran) * 0.1;
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Validation accept function
            // --------------------------------------------------------------------
            accept: function(){
                console.log('asd');
                // ----------------------------------------------------------------
                let url = "{{ route('import.la03.show.accept', ':id') }}";
                url = url.replace(':id', "{{ $pembayaran->id }}");
                let request = axios.put(url);
                // ----------------------------------------------------------------
                // If request success
                // ----------------------------------------------------------------
                request.then((response)=>{
                    // ------------------------------------------------------------
                    let data = response.data;
                    // ------------------------------------------------------------
                    location.reload();
                    // ------------------------------------------------------------
                    Vue.nextTick(function () {
                        toastr.success(data.message);    
                    })
                    // ------------------------------------------------------------
                })
                // ----------------------------------------------------------------
                // If request error
                // ----------------------------------------------------------------
                request.catch((error)=>{
                    toastr.error(error.message);
                })
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Delete data function
            // --------------------------------------------------------------------
            deleteData: function(id){
                // ----------------------------------------------------------------
                let url = "{{ route('import.la03.show.destroy', ':id') }}";
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
            vm.calculation();
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Accept event
            // --------------------------------------------------------------------
            $(document).on('click', '.btn-accept', function(){
                vm.accept();
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