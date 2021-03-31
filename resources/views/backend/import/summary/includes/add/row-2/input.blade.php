<!-- Start - uang_pendaftaran -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-danger">Required</span><br>
        <div class="label-form">Penerimaan Uang Pendaftaran</div>
    </label>
    <div class="col-sm-10 input-form">
        <input type="text" name="uang_pendaftaran" v-model="result.summary.uang_pendaftaran" class="form-control col-sm-6 @if($errors->has('uang_pendaftaran')) is-invalid @endif" value="{{ $summary->id ? $summary->uang_pendaftaran : old('uang_pendaftaran') }}" onkeypress="return onlyNumberKey(event)" required>
        <!-- Start - Error handling -->
        @if($errors->has('uang_pendaftaran'))
            <div class="invalid-feedback">{{ $errors->first('uang_pendaftaran') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
</div>
<!-- End - uang_pendaftaran -->

<!-- Start - uang_kursus -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-danger">Required</span><br>
        <div class="label-form">Penerimaan Uang Kursus</div>
    </label>
    <div class="col-sm-10 input-form">
        <input type="text" name="uang_kursus" v-model="result.summary.uang_kursus" class="form-control col-sm-6 @if($errors->has('uang_kursus')) is-invalid @endif" value="{{ $summary->id ? $summary->uang_kursus : old('uang_kursus') }}" onkeypress="return onlyNumberKey(event)" required>
        <!-- Start - Error handling -->
        @if($errors->has('uang_kursus'))
            <div class="invalid-feedback">{{ $errors->first('uang_kursus') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
</div>
<!-- End - uang_kursus -->

<!-- Start - total_penerimaan -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-success">Optional</span><br>
        <div class="label-form">Total Penerimaan</div>
    </label>
    <div class="col-sm-10 input-form">
        <input type="text" name="total_penerimaan" v-model="totalPenerimaan" class="form-control col-sm-6 @if($errors->has('total_penerimaan')) is-invalid @endif" value="{{ old('total_penerimaan') }}" onkeypress="return onlyNumberKey(event)" disabled>
        <!-- Start - Error handling -->
        @if($errors->has('total_penerimaan'))
            <div class="invalid-feedback">{{ $errors->first('total_penerimaan') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
</div>
<!-- End - total_penerimaan -->

<!-- Start - royalti -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-success">Optional</span><br>
        <div class="label-form">Royalti (10%)</div>
    </label>
    <div class="col-sm-10 input-form">
        <input type="text" name="royalti" v-model="royalti" class="form-control col-sm-6 @if($errors->has('royalti')) is-invalid @endif" value="{{ old('royalti') }}" onkeypress="return onlyNumberKey(event)" disabled>
        <!-- Start - Error handling -->
        @if($errors->has('royalti'))
            <div class="invalid-feedback">{{ $errors->first('royalti') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
</div>
<!-- End - royalti -->