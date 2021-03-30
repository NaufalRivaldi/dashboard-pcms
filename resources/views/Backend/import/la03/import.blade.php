@extends('layouts.content_form')

@section('content-form')
<form action="{{ route('import.la03.import.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Start - File excel -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Import CSV</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="file" name="file_import" class="form-control col-sm-6 @if($errors->has('file_import')) is-invalid @endif" value="">
            <small class="text-info">*File: LA03 format CSV</small>
            <!-- Start - Error handling -->
            @if($errors->has('file_import'))
                <div class="invalid-feedback">{{ $errors->first('file_import') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - File excel -->

    <!-- Start - Button -->
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <button type="submit" class="btn btn-success">
                <i class="ti-save"></i> Simpan
            </button>
        </div>
    </div>
    <!-- End - Button -->
</form>
@endsection

@section('card-button-footer')
<a href="{{ route('import.la03.index') }}" class="btn btn-info">
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
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for Cabang page
        // ------------------------------------------------------------------------
        mounted() {
            // --------------------------------------------------------------------
            let vm = this;
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------
    })
    // ----------------------------------------------------------------------------
</script>
@endpush