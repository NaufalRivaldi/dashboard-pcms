@extends('layouts.content_form')

@section('content-form')
<form action="{{ $kategori->id == null ? route('master.kategori.store') : route('master.kategori.update', $kategori->id) }}" method="POST">
    @csrf
    @if($kategori->id != null)
        @method('PUT')
    @endif
    <!-- Start - Hidden input -->
    <input type="hidden" name="id" value="{{ $kategori->id }}">
    <!-- End - Hidden input -->

    <!-- Start - Kode -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Kode</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="text" name="kode" class="form-control col-sm-6 @if($errors->has('kode')) is-invalid @endif" value="{{ $kategori->kode ? $kategori->kode : old('kode') }}">
            <!-- Start - Error handling -->
            @if($errors->has('kode'))
                <div class="invalid-feedback">{{ $errors->first('kode') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - Kode -->

    <!-- Start - Nama -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Nama</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="text" name="nama" class="form-control @if($errors->has('nama')) is-invalid @endif" value="{{ $kategori->nama ? $kategori->nama : old('nama') }}">
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
<a href="{{ route('master.kategori.index') }}" class="btn btn-info">
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
        // Data for kategori page
        // ------------------------------------------------------------------------
        data: {
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for kategori page
        // ------------------------------------------------------------------------
        methods: {
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for kategori page
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