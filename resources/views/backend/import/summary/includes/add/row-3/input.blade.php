<!-- Start - siswa_aktif -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-danger">Required</span><br>
        <div class="label-form">Siswa Aktif</div>
    </label>
    <div class="col-sm-10 input-form">
        <input type="text" name="siswa_aktif" v-model="result.summary.siswa_aktif" class="form-control col-sm-6 @if($errors->has('siswa_aktif')) is-invalid @endif" value="{{ $summary->id ? $summary->siswa_aktif : old('siswa_aktif') }}" onkeypress="return onlyNumberKey(event)" required>
        <!-- Start - Error handling -->
        @if($errors->has('siswa_aktif'))
            <div class="invalid-feedback">{{ $errors->first('siswa_aktif') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
</div>
<!-- End - siswa_aktif -->

<!-- Start - siswa_baru -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-danger">Required</span><br>
        <div class="label-form">Siswa Baru</div>
    </label>
    <div class="col-sm-10 input-form">
        <input type="text" name="siswa_baru" v-model="result.summary.siswa_baru" class="form-control col-sm-6 @if($errors->has('siswa_baru')) is-invalid @endif" value="{{ $summary->id ? $summary->siswa_baru : old('siswa_baru') }}" onkeypress="return onlyNumberKey(event)" required>
        <!-- Start - Error handling -->
        @if($errors->has('siswa_baru'))
            <div class="invalid-feedback">{{ $errors->first('siswa_baru') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
</div>
<!-- End - siswa_baru -->

<!-- Start - siswa_cuti -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-danger">Required</span><br>
        <div class="label-form">Siswa Cuti</div>
    </label>
    <div class="col-sm-10 input-form">
        <input type="text" name="siswa_cuti" v-model="result.summary.siswa_cuti" class="form-control col-sm-6 @if($errors->has('siswa_cuti')) is-invalid @endif" value="{{ $summary->id ? $summary->siswa_cuti : old('siswa_cuti') }}" onkeypress="return onlyNumberKey(event)" required>
        <!-- Start - Error handling -->
        @if($errors->has('siswa_cuti'))
            <div class="invalid-feedback">{{ $errors->first('siswa_cuti') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
</div>
<!-- End - siswa_cuti -->

<!-- Start - siswa_keluar -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-danger">Required</span><br>
        <div class="label-form">Siswa Keluar</div>
    </label>
    <div class="col-sm-10 input-form">
        <input type="text" name="siswa_keluar" v-model="result.summary.siswa_keluar" class="form-control col-sm-6 @if($errors->has('siswa_keluar')) is-invalid @endif" value="{{ $summary->id ? $summary->siswa_keluar : old('siswa_keluar') }}" onkeypress="return onlyNumberKey(event)" required>
        <!-- Start - Error handling -->
        @if($errors->has('siswa_keluar'))
            <div class="invalid-feedback">{{ $errors->first('siswa_keluar') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
</div>
<!-- End - siswa_keluar -->