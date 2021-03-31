@extends('layouts.content_form')

@section('content-form')
<form action="{{ route('import.summary.generate.store') }}" method="POST">
    @csrf

    <!-- Start - Row 1 -->
    @include('backend.import.summary.includes.add.row-1.input')
    <!-- End - Row 1 -->

    <hr>

    <template v-if="status.form">
        <!-- Start - Row 2 -->
        @include('backend.import.summary.includes.add.row-2.input')
        <!-- End - Row 2 -->

        <hr>

        <!-- Start - Button -->
        <div class="row">
            <div class="col-sm-12 text-center">
                <button type="submit" class="btn btn-success" :disabled="(this.import.la03 != false && this.import.la06 != false && this.import.la07 != false && this.import.la09 != false && this.import.la12 != false && this.import.la13 != false) ? false : true">
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
                label: {
                    month: null,
                    year: null,
                    cabang: null,
                },
            },
            // --------------------------------------------------------------------
            result:{
                summary: @json($summary),
            },
            // --------------------------------------------------------------------
            import: {
                la03: false,
                la06: false,
                la07: false,
                la09: false,
                la12: false,
                la13: false,
            },
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Set Computed property
        // ------------------------------------------------------------------------
        computed: {
            label: function(){ return this.preset.label },
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
                        // --------------------------------------------------------
                        toastr.success(data.message);
                        // --------------------------------------------------------
                        this.status.form = true;
                        // --------------------------------------------------------
                        this.preset.label.month = data.month;
                        this.preset.label.year = data.year;
                        this.preset.label.cabang = data.cabang;
                        // --------------------------------------------------------
                        this.import.la03 = data.la03;
                        this.import.la06 = data.la06;
                        this.import.la07 = data.la07;
                        this.import.la09 = data.la09;
                        this.import.la12 = data.la12;
                        this.import.la13 = data.la13;
                        // --------------------------------------------------------
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
            // Check import status
            // --------------------------------------------------------------------
            checkImportStatus: function(){
                // ----------------------------------------------------------------
                let valid = false;
                // ----------------------------------------------------------------
                if(this.import.la03 != false && this.import.la06 != false && this.import.la07 != false && this.import.la09 != false && this.import.la12 != false && this.import.la13 != false){
                    valid = true;
                }
                // ----------------------------------------------------------------
                return valid;
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for siswa Cuti page
        // ------------------------------------------------------------------------
        mounted() {
            console.log(this.import.la03);
            // --------------------------------------------------------------------
            let vm = this;
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Set template data
            // --------------------------------------------------------------------\
            let pageType = "{{ $pageType }}";
            if(pageType != "create") vm.status.form = true;
            else vm.status.form = false;
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