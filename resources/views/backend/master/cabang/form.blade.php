@extends('layouts.content_form')

@section('content-form')
<form action="{{ $cabang->id == null ? route('master.cabang.store') : route('master.cabang.update', $cabang->id) }}" method="POST">
    @csrf
    @if($cabang->id != null)
        @method('PUT')
    @endif
    <!-- Start - Hidden input -->
    <input type="hidden" name="id" value="{{ $cabang->id }}">
    <!-- End - Hidden input -->

    <!-- Start - Kode -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Kode</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="text" name="kode" class="form-control col-sm-6 @if($errors->has('kode')) is-invalid @endif" value="{{ $cabang->kode ? $cabang->kode : old('kode') }}" required>
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
            <input type="text" name="nama" class="form-control @if($errors->has('nama')) is-invalid @endif" value="{{ $cabang->nama ? $cabang->nama : old('nama') }}" required>
            <!-- Start - Error handling -->
            @if($errors->has('nama'))
                <div class="invalid-feedback">{{ $errors->first('nama') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - Nama -->

    <!-- Start - latitude -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-success">Optional</span><br>
            <div class="label-form">Latitude</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="text" name="latitude" class="form-control @if($errors->has('latitude')) is-invalid @endif" value="{{ $cabang->latitude ? $cabang->latitude : old('latitude') }}">
            <!-- Start - Error handling -->
            @if($errors->has('latitude'))
                <div class="invalid-feedback">{{ $errors->first('latitude') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - latitude -->

    <!-- Start - longitude -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-success">Optional</span><br>
            <div class="label-form">Longitude</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="text" name="longitude" class="form-control @if($errors->has('longitude')) is-invalid @endif" value="{{ $cabang->longitude ? $cabang->longitude : old('longitude') }}">
            <!-- Start - Error handling -->
            @if($errors->has('longitude'))
                <div class="invalid-feedback">{{ $errors->first('longitude') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - longitude -->

    <!-- Start - wilayah_id -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Wilayah</div>
        </label>
        <div class="col-sm-10 input-form">
            <select name="wilayah_id" class="form-control select2 @if($errors->has('wilayah_id')) is-invalid @endif">
                <option value="">Pilih</option>
                @foreach($wilayah as $id => $value)
                    <option value="{{ $id }}" @if($id == $cabang->wilayah_id) selected @endif>{{ $value }}</option>
                @endforeach
            </select>
            <!-- Start - Error handling -->
            @if($errors->has('wilayah_id'))
                <div class="invalid-feedback">{{ $errors->first('wilayah_id') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - wilayah_id -->

    <!-- Start - wilayah_id -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Sub Wilayah</div>
        </label>
        <div class="col-sm-10 input-form">
            <select name="sub_wilayah_id" class="form-control select2 @if($errors->has('sub_wilayah_id')) is-invalid @endif">
                <option value="">Pilih</option>
                @foreach($subWilayah as $id => $value)
                    <option value="{{ $id }}" @if($id == $cabang->sub_wilayah_id) selected @endif>{{ $value }}</option>
                @endforeach
            </select>
            <!-- Start - Error handling -->
            @if($errors->has('sub_wilayah_id'))
                <div class="invalid-feedback">{{ $errors->first('sub_wilayah_id') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - wilayah_id -->

    <!-- Start - user_id -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Owner</div>
        </label>
        <div class="col-sm-10 input-form">
            <select name="user_id" class="form-control select2 @if($errors->has('user_id')) is-invalid @endif">
                <option value="">Pilih</option>
                @foreach($owner as $id => $value)
                    <option value="{{ $id }}" @if($id == $cabang->user_id) selected @endif>{{ $value }}</option>
                @endforeach
            </select>
            <!-- Start - Error handling -->
            @if($errors->has('user_id'))
                <div class="invalid-feedback">{{ $errors->first('user_id') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - user_id -->

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
<a href="{{ route('master.cabang.index') }}" class="btn btn-info">
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