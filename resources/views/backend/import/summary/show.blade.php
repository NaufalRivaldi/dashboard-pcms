@extends('layouts.content_show')

@section('card-button')
    @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 3 && $summary->status == 0)
        <button class="btn btn-success" data-toggle="modal" data-target="#modalValidation"><i class="ti-check"></i> Approve</button>
    @elseif($summary->status == 1)
        <a href="{{ route('import.summary.show.pdf', $summary->id) }}" class="btn btn-danger"><i class="ti-file"></i> Export PDF</a>
    @endif
@endsection

@section('card-content')
    <!-- Start - Detail summary -->
    <div class="row">
        <div class="col-sm-3 font-weight-bold">Kode Cabang</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $summary->cabang->kode }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Nama Cabang</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $summary->cabang->nama }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Bulan</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ strtoupper(setMonth($summary->bulan)) }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Tahun</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $summary->tahun }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Status</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{!! statusValidate($summary->status) !!}</div>
    </div>

    <hr>

    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Penerimaan Uang Pendaftaran</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ number_format($summary->uang_pendaftaran) }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Penerimaan Uang Kursus</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ number_format($summary->uang_kursus) }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Total Penerimaan</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ number_format($summary->uang_pendaftaran + $summary->uang_kursus) }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Royalti (10%)</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ number_format(($summary->uang_pendaftaran + $summary->uang_kursus) * 0.1) }}</div>
    </div>

    <hr>

    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Jumlah siswa aktif</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $summary->siswa_aktif }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Jumlah siswa baru</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $summary->siswa_baru }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Jumlah siswa cuti</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $summary->siswa_cuti }}</div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-3 font-weight-bold">Jumlah siswa keluar</div>
        <div class="col-sm-1 text-right">:</div>
        <div class="col-sm-8">{{ $summary->siswa_keluar }}</div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-6">
            <b>Siswa Aktif berdasarkan jurusan:</b>
            <table class="table table-striped table-bordered">
                @foreach($summary->summary_sa_materi as $row)
                    <tr>
                        <td width="65%">{{ $row->materi->nama }}</td>
                        <td>{{ $row->jumlah }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="col-md-6">
            <b>Siswa Aktif berdasarkan pendidikan:</b>
            <table class="table table-striped table-bordered">
                @foreach($summary->summary_sa_pendidikan as $row)
                    <tr>
                        <td width="65%">{{ $row->pendidikan->nama }}</td>
                        <td>{{ $row->jumlah }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <!-- End - Detail summary -->
@endsection

@section('card-footer')
    <div class="card-footer text-center">
        <a href="{{ route('import.summary.index') }}" class="btn btn-info">
            <i class="ti-arrow-circle-left"></i> Kembali
        </a>
    </div>
@endsection

@section('modal')
    <!-- Start - Modal Validation -->
    <div class="modal fade" id="modalValidation" tabindex="-1" aria-labelledby="modalValidationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalValidationLabel">Verifikasi Import Summary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-success btn-block btn-approve">Approve</button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-secondary btn-block btn-pending" data-dismiss="modal">Pending</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <p>Apakah Data sudah Valid / Benar ? Apabila sudah valid klik Approve, jika belum valid klik Pending.</p>
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
            // Validation approve function
            // --------------------------------------------------------------------
            approve: function(){
                // ----------------------------------------------------------------
                let url = "{{ route('import.summary.show.approve', ':id') }}";
                url = url.replace(':id', "{{ $summary->id }}");
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
                        if(data.status) toastr.success(data.message);    
                        else toastr.error(data.message);
                    })
                    // ------------------------------------------------------------
                })
                // ----------------------------------------------------------------
                // If request error
                // ----------------------------------------------------------------
                request.catch((error)=>{
                    toastr.error(error.response.data.message);
                })
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Validation pending function
            // --------------------------------------------------------------------
            pending: function(){
                // ----------------------------------------------------------------
                let url = "{{ route('import.summary.show.pending', ':id') }}";
                url = url.replace(':id', "{{ $summary->id }}");
                let request = axios.put(url);
                // ----------------------------------------------------------------
                // If request success
                // ----------------------------------------------------------------
                request.then((response)=>{
                    // ------------------------------------------------------------
                    let data = response.data;
                    // ------------------------------------------------------------
                    Vue.nextTick(function () {
                        if(data.status) toastr.success(data.message);    
                        else toastr.error(data.message);
                    })
                    // ------------------------------------------------------------
                })
                // ----------------------------------------------------------------
                // If request error
                // ----------------------------------------------------------------
                request.catch((error)=>{
                    toastr.error(error.response.data.message);
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
            // approve event
            // --------------------------------------------------------------------
            $(document).on('click', '.btn-approve', function(){
                vm.approve();
            })
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // pending event
            // --------------------------------------------------------------------
            $(document).on('click', '.btn-pending', function(){
                vm.pending();
            })
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------
    })
    // ----------------------------------------------------------------------------
</script>
@endpush