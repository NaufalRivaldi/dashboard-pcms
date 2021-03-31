<!-- Start - Bulan & tahun -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-danger">Required</span><br>
        <div class="label-form">Bulan / tahun</div>
    </label>
    <div class="col-sm-10 input-form">
        <input type="text" name="bulan_tahun" class="form-control col-sm-6 @if($errors->has('bulan_tahun')) is-invalid @endif date-picker-month" value="{{ $siswaCuti->id ? $siswaCuti->bulan_tahun : old('bulan_tahun') }}" required>
        <!-- Start - Error handling -->
        @if($errors->has('bulan_tahun'))
            <div class="invalid-feedback">{{ $errors->first('bulan_tahun') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
</div>
<!-- End - Bulan & tahun -->

<!-- Start - cabang_id -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-danger">Required</span><br>
        <div class="label-form">Cabang</div>
    </label>
    <div class="col-sm-10 input-form">
        <select name="cabang_id" class="form-control select2 @if($errors->has('cabang_id')) is-invalid @endif" required>
            <option value="">Pilih</option>
            @foreach($cabangs as $id => $value)
                <option value="{{ $id }}" @if($id == $siswaCuti->cabang_id) selected @endif>{{ $value }}</option>
            @endforeach
        </select>
        <!-- Start - Error handling -->
        @if($errors->has('cabang_id'))
            <div class="invalid-feedback">{{ $errors->first('cabang_id') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
</div>
<!-- End - cabang_id -->

<!-- Start - button -->
<div class="row" v-if="status.form == false ? true : false">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
        <button type="button" class="btn btn-success" @click="checkDataValidation()">Check data</button>
    </div>
</div>
<!-- End - button -->