<div class="row mb-3">
    <div class="col-sm-3">
        Type <span class="badge badge-danger">Required</span>
    </div>
    <div class="col-sm-3">
        Nama Pembayar <span class="badge badge-success">Optional</span>
    </div>
    <div class="col-sm-3">
        Nominal (Rp.) <span class="badge badge-danger">Required</span>
    </div>
    <div class="col-sm-3"></div>
</div>

<!-- Start - Dynamic form -->
<div class="row mb-3" v-for="(detail, index) in result.pembayaranDetail" :key="index">
    
    <!-- Start - type -->
    <div class="col-sm-3">
        <select name="type[]" v-model="detail.type" class="form-control @if($errors->has('type')) is-invalid @endif" required>
            <option value="">Pilih</option>
            <option value="1">Penerimaan Uang Pendaftaran</option>
            <option value="2">Penerimaan Uang Kursus</option>
        </select>
        <!-- Start - Error handling -->
        @if($errors->has('type'))
            <div class="invalid-feedback">{{ $errors->first('type') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
    <!-- Start - type -->

    <!-- Start - nama_pembayar -->
    <div class="col-sm-3">
        <input type="text" v-model="detail.nama_pembayar" name="nama_pembayar[]" class="form-control @if($errors->has('nama_pembayar')) is-invalid @endif" value="">
        <!-- Start - Error handling -->
        @if($errors->has('nama_pembayar'))
            <div class="invalid-feedback">{{ $errors->first('nama_pembayar') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
    <!-- Start - nama_pembayar -->

    <!-- Start - nominal -->
    <div class="col-sm-3">
        <input type="text" v-model="detail.nominal" name="nominal[]" class="form-control @if($errors->has('nominal')) is-invalid @endif" value="" v-model="result.nominal" onkeypress="return onlyNumberKey(event)" required>
        <!-- Start - Error handling -->
        @if($errors->has('nominal'))
            <div class="invalid-feedback">{{ $errors->first('nominal') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
    <!-- Start - nominal -->

    <!-- Start - button delete -->
    <div class="col-sm-3">
        <button type="button" class="btn btn-danger" @click="deleteRow(index)" :disabled="result.pembayaranDetail.length == 1 ? true : false">
            <i class="ti-trash"></i>
        </button>
    </div>
    <!-- Start - button delete -->

</div>
<!-- End - Dynamic form -->

<!-- Start - button add -->
<div class="row mb-3">
    <div class="col-sm-9 text-right">
        <button type="button" class="btn btn-success" @click="addRow()">
            <i class="ti-plus"></i>
        </button>
    </div>
</div>
<!-- End - button add -->