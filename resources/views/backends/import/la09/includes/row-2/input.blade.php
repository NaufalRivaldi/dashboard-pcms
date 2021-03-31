<!-- Start - Bulan & tahun -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-danger">Required</span><br>
        <div class="label-form">Jumlah</div>
    </label>
    <div class="col-sm-10 input-form">
        <input type="text" name="jumlah" class="form-control @if($errors->has('jumlah')) is-invalid @endif" value="" v-model="result.siswaBaru.jumlah" onkeypress="return onlyNumberKey(event)" required>
        <!-- Start - Error handling -->
        @if($errors->has('jumlah'))
            <div class="invalid-feedback">{{ $errors->first('jumlah') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
</div>
<!-- End - Bulan & tahun -->