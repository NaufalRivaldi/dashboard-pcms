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
    
    <!-- Start - materi_id -->
    <div class="col-sm-3">
        <select name="materi_id[]" v-model="detail.materi_id" class="form-control @if($errors->has('materi_id')) is-invalid @endif" required>
            <option value="">Pilih</option>
            @foreach($materis as $materi)
                <option value="{{ $materi->id }}">{{ $materi->nama }}</option>
            @endforeach
        </select>
        <!-- Start - Error handling -->
        @if($errors->has('materi_id'))
            <div class="invalid-feedback">{{ $errors->first('materi_id') }}</div>
        @endif
        <!-- End - Error handling -->
    </div>
    <!-- Start - materi_id -->

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