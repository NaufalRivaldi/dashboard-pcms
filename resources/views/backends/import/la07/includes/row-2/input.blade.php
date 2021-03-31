<div class="row mb-3">
    <div class="col-sm-3">
        Materi / Jurusan <span class="badge badge-danger">Required</span>
    </div>
    <div class="col-sm-3">
        Jumlah <span class="badge badge-danger">Required</span>
    </div>
    <div class="col-sm-3"></div>
</div>

<!-- Start - Dynamic form -->
<div class="row mb-3" v-for="(detail, index) in result.siswaAktifDetail" :key="index">
    
    <!-- Start - pendidikan_id -->
    <div class="col-sm-3">
        <select name="pendidikan_id[]" v-model="detail.pendidikan_id" class="form-control @if($errors->has('pendidikan_id')) is-invalid @endif" required>
            <option value="">Pilih</option>
            @foreach($pendidikans as $pendidikan)
                <option value="{{ $pendidikan->id }}">{{ $pendidikan->nama }}</option>
            @endforeach
        </select>
        <!-- Start - Error handling -->
        @if($errors->has('pendidikan_id'))
            <div class="invalid-feedback">{{ $errors->first('pendidikan_id') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
    <!-- Start - pendidikan_id -->

    <!-- Start - jumlah -->
    <div class="col-sm-3">
        <input type="text" v-model="detail.jumlah" name="jumlah[]" class="form-control @if($errors->has('jumlah')) is-invalid @endif" value="" v-model="result.jumlah" onkeypress="return onlyNumberKey(event)" required>
        <!-- Start - Error handling -->
        @if($errors->has('jumlah'))
            <div class="invalid-feedback">{{ $errors->first('jumlah') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
    <!-- Start - jumlah -->

    <!-- Start - button delete -->
    <div class="col-sm-3">
        <button type="button" class="btn btn-danger" @click="deleteRow(index)" :disabled="result.siswaAktifDetail.length == 1 ? true : false">
            <i class="ti-trash"></i>
        </button>
    </div>
    <!-- Start - button delete -->

</div>
<!-- End - Dynamic form -->

<!-- Start - button add -->
<div class="row mb-3">
    <div class="col-sm-6 text-right">
        <button type="button" class="btn btn-success" @click="addRow()">
            <i class="ti-plus"></i>
        </button>
    </div>
</div>
<!-- End - button add -->