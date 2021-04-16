@extends('layouts.content_show')

@section('card-button')
    
@endsection

@section('card-content')
    <!-- Start - Detail siswaBaru -->
    <div class="row">
        <div class="col-sm-3 font-weight-bold">Kode Cabang</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $siswaBaru->cabang->kode }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Nama Cabang</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $siswaBaru->cabang->nama }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Bulan</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ strtoupper(setMonth($siswaBaru->bulan)) }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Tahun</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $siswaBaru->tahun }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Jumlah Total Siswa Baru</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $siswaBaru->jumlah }}</div>
    </div>
    <!-- End - Detail siswaBaru -->
@endsection

@section('card-footer')
    <div class="card-footer text-center">
        <a href="{{ route('import.la09.index') }}" class="btn btn-info">
            <i class="ti-arrow-circle-left"></i> Kembali
        </a>
    </div>
@endsection

@section('modal')
    <!-- Start - Modal Validation -->
    <div class="modal fade" id="modalValidation" tabindex="-1" aria-labelledby="modalValidationLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalValidationLabel">Verifikasi Data siswa Baru</h5>
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
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for Cabang page
        // ------------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------------
            // Validation accept function
            // --------------------------------------------------------------------
            accept: function(){
                // ----------------------------------------------------------------
                let url = "{{ route('import.la09.show.accept', ':id') }}";
                url = url.replace(':id', "{{ $siswaBaru->id }}");
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
            // Accept event
            // --------------------------------------------------------------------
            $(document).on('click', '.btn-accept', function(){
                vm.accept();
            })
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------
    })
    // ----------------------------------------------------------------------------
</script>
@endpush