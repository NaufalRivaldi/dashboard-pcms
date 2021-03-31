@extends('layouts.content_form')

@section('content-form')
<form action="{{ $materi->id == null ? route('master.materi.store') : route('master.materi.update', $materi->id) }}" method="POST">
    @csrf
    @if($materi->id != null)
        @method('PUT')
    @endif
    <!-- Start - Hidden input -->
    <input type="hidden" name="id" value="{{ $materi->id }}">
    <!-- End - Hidden input -->

    <!-- Start - Nama -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Nama</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="text" name="nama" class="form-control @if($errors->has('nama')) is-invalid @endif" value="{{ $materi->nama ? $materi->nama : old('nama') }}">
            <!-- Start - Error handling -->
            @if($errors->has('nama'))
                <div class="invalid-feedback">{{ $errors->first('nama') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - Nama -->

    <!-- Start - kategori_id -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Kategori</div>
        </label>
        <div class="col-sm-10 input-form">
            <select name="kategori_id" class="form-control select2 @if($errors->has('kategori_id')) is-invalid @endif">
                <option value="">Pilih</option>
                @foreach($kategori as $id => $value)
                    <option value="{{ $id }}" @if($id == $materi->kategori_id) selected @endif>{{ $value }}</option>
                @endforeach
            </select>
            <!-- Start - Error handling -->
            @if($errors->has('kategori_id'))
                <div class="invalid-feedback">{{ $errors->first('kategori_id') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - kategori_id -->

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
<a href="{{ route('master.materi.index') }}" class="btn btn-info">
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
        // Data for materi page
        // ------------------------------------------------------------------------
        data: {
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for materi page
        // ------------------------------------------------------------------------
        methods: {
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for materi page
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