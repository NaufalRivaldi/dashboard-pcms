@extends('layouts.content_form')

@section('content-form')
<form action="{{ $summary->id == null ? route('import.summary.store') : route('import.summary.update', $summary->id) }}" method="POST">
    @csrf
    @if($summary->id != null)
        @method('PUT')
    @endif
    <!-- Start - Hidden input -->
    <input type="hidden" name="id" value="{{ $summary->id }}">
    <!-- End - Hidden input -->

    <!-- Start - Row 1 -->
    @include('backend.import.summary.includes.add.row-1.input')
    <!-- End - Row 1 -->

    <hr>

    <template v-if="status.form">
        <!-- Start - Row 2 -->
        @include('backend.import.summary.includes.add.row-2.input')
        <!-- End - Row 2 -->

        <hr>

        <!-- Start - Row 3 -->
        @include('backend.import.summary.includes.add.row-3.input')
        <!-- End - Row 3 -->

        <hr>

        <!-- Start - Row 4 -->
        @include('backend.import.summary.includes.add.row-4.input')
        <!-- End - Row 4 -->

        <hr>

        <!-- Start - Row 5 -->
        @include('backend.import.summary.includes.add.row-5.input')
        <!-- End - Row 5 -->

        <hr>

        <!-- Start - Button -->
        <div class="row">
            <div class="col-sm-12 text-center">
                <button type="submit" class="btn btn-success" :disabled="status.form == false ? true : false">
                    <i class="ti-save"></i> Simpan
                </button>
            </div>
        </div>
        <!-- End - Button -->
    </template>

    <!-- Start - alert for validation data -->
    <template v-else>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                    Check data terlebih dahulu.
                </div>
            </div>
        </div>
    </template>
    <!-- Start - alert for validation data -->
</form>
@endsection

@section('card-button-footer')
<a href="{{ route('import.summary.index') }}" class="btn btn-info">
    <i class="ti-arrow-circle-left"></i> Kembali
</a>
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
        // Data for siswa Cuti page
        // ------------------------------------------------------------------------
        data: {
            // --------------------------------------------------------------------
            status: {
                form: false,
            },
            // --------------------------------------------------------------------
            // Preset data
            // --------------------------------------------------------------------
            preset: {
                templateSummaryMateri: {
                    materi_id: "", jumlah: 0,
                },
                templateSummaryPendidikan: {
                    pendidikan_id: "", jumlah: 0,
                },
            },
            // --------------------------------------------------------------------
            result:{
                summary: @json($summary),
            },
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------
        
        // ------------------------------------------------------------------------
        // Set computed property
        // ------------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------------
            totalPenerimaan: function(){
                let total = parseInt(this.result.summary.uang_pendaftaran) + parseInt(this.result.summary.uang_kursus);
                return Number.isNaN(total) ? 0 : total;
            },
            // --------------------------------------------------------------------
            royalti: function(){
                let total = this.totalPenerimaan * 0.1;
                return Number.isNaN(total) ? 0 : total;
            }
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for siswa Cuti page
        // ------------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------------
            // Check data validation for form
            // --------------------------------------------------------------------
            checkDataValidation: function(){
                // ----------------------------------------------------------------
                let data = {
                    date        : $("input[name='bulan_tahun']").val(),
                    cabang_id   : $("select[name='cabang_id']").val(),
                };
                // ----------------------------------------------------------------
                let request = axios.get("{{ route('import.summary.check-data-validation') }}", {
                                params: data
                            });
                // ----------------------------------------------------------------
                
                // ----------------------------------------------------------------
                // Request is success
                // ----------------------------------------------------------------
                request.then((response)=>{
                    let data = response.data;
                    if(data.status){
                        toastr.success(data.message);
                        this.status.form = true;
                    }else{
                        toastr.error(data.message);
                        this.status.form = false;
                    }
                });
                // ----------------------------------------------------------------
                // Request is failed
                // ----------------------------------------------------------------
                request.catch((error)=>{
                    let data = error.response.data;
                    toastr.error(data.message);
                    this.status.form = false;
                });
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Add row on dynamic form
            // --------------------------------------------------------------------
            addRowMateri: function(){
                this.result.summary.summary_sa_materi.push(_.cloneDeep(_.get(this.preset, 'templateSummaryMateri')));
            },
            // --------------------------------------------------------------------
            addRowPendidikan: function(){
                this.result.summary.summary_sa_pendidikan.push(_.cloneDeep(_.get(this.preset, 'templateSummaryPendidikan')));
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Delete row on dynamic form
            // --------------------------------------------------------------------
            deleteRowMateri: function(index){
                this.result.summary.summary_sa_materi.splice(index, 1);
            },
            // --------------------------------------------------------------------
            deleteRowPendidikan: function(index){
                this.result.summary.summary_sa_pendidikan.splice(index, 1);
            },
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for siswa Cuti page
        // ------------------------------------------------------------------------
        mounted() {
            // --------------------------------------------------------------------
            let vm = this;
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Set template data
            // --------------------------------------------------------------------\
            let pageType = "{{ $pageType }}";
            if(pageType == "create"){
                vm.result.summary.summary_sa_materi = new Array(_.cloneDeep(_.get(vm.preset, 'templateSummaryMateri')));
                vm.result.summary.summary_sa_pendidikan = new Array(_.cloneDeep(_.get(vm.preset, 'templateSummaryPendidikan')));
            }else{
                vm.status.form = true;
            }
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Jquery trigger
            // --------------------------------------------------------------------
            $("input[name='val_bulan_tahun']").on('change', function(){
                vm.checkMonthYear();
            });
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------
    })
    // ----------------------------------------------------------------------------
</script>
@endpush