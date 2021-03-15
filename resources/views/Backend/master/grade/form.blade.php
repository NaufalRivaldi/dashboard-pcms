@extends('layouts.content_form')

@section('content-form')
<form action="{{ $grade->id == null ? route('master.grade.store') : route('master.grade.update', $grade->id) }}" method="POST">
    @csrf
    @if($grade->id != null)
        @method('PUT')
    @endif
    <!-- Start - Hidden input -->
    <input type="hidden" name="id" value="{{ $grade->id }}">
    <!-- End - Hidden input -->

    <!-- Start - Nama -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Nama</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="text" name="nama" class="form-control @if($errors->has('nama')) is-invalid @endif" value="{{ $grade->nama ? $grade->nama : old('nama') }}">
            <!-- Start - Error handling -->
            @if($errors->has('nama'))
                <div class="invalid-feedback">{{ $errors->first('nama') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - Nama -->

    <!-- Start - Nama -->
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <button type="submit" class="btn btn-success">
                <i class="ti-save"></i> Simpan
            </button>
        </div>
    </div>
    <!-- End - Nama -->
</form>
@endsection

@section('card-button-footer')
<a href="{{ route('master.grade.index') }}" class="btn btn-info">
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
        // Data for grade page
        // ------------------------------------------------------------------------
        data: {
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for grade page
        // ------------------------------------------------------------------------
        methods: {
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for grade page
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