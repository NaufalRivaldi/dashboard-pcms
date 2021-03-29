@extends('layouts.content_form')

@section('content-form')
<form action="{{ $siswaCuti->id == null ? route('import.la11.store') : route('import.la11.update', $siswaCuti->id) }}" method="POST">
    @csrf
    @if($siswaCuti->id != null)
        @method('PUT')
    @endif
    <!-- Start - Hidden input -->
    <input type="hidden" name="id" value="{{ $siswaCuti->id }}">
    <!-- End - Hidden input -->

    <!-- Start - Row 1 -->
    @include('backend.import.la11.includes.row-1.input')
    <!-- End - Row 1 -->

    <hr>

    <template v-if="status.form">
        <!-- Start - Row 2 -->
        @include('backend.import.la11.includes.row-2.input')
        <!-- End - Row 2 -->

        <hr>

        <!-- Start - Button -->
        <div class="row">
            <div class="col-sm-12">
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
<a href="{{ route('import.la11.index') }}" class="btn btn-info">
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
                //
            },
            // --------------------------------------------------------------------
            result:{
                siswaCuti: @json($siswaCuti),
            },
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
                let request = axios.get("{{ route('import.la11.check-data-validation') }}", {
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