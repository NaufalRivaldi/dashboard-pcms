<!-- Start - Dynamic form detail sa materi -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-danger">Required</span><br>
        <div class="label-form">Siswa Aktif Berdasarkan Jurusan</div>
    </label>
    <div class="col-sm-10 input-form">
        <div class="row mb-3" v-for="(summaryMateri, index) in result.summary.summary_sa_materi">
            <!-- Start - materi_id -->
            <div class="col-md-5">
                <select name="materi_id[]" v-model="summaryMateri.materi_id" class="form-control" required>
                    <option value="">Pilih jurusan</option>
                    @foreach($materis as $materi)
                        <option value="{{ $materi->id }}">{{ $materi->nama }}</option>
                    @endforeach
                </select>
            </div>
            <!-- End - materi_id -->

            <!-- Start - jumlah -->
            <div class="col-md-5">
                <input type="text" name="jumlah_m[]" v-model="summaryMateri.jumlah" class="form-control" onkeypress="return onlyNumberKey(event)" placeholder="jumlah" required>
            </div>
            <!-- End - jumlah -->
            
            <!-- Start - button delete -->
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" @click="deleteRowMateri(index)">
                    <i class="ti-trash"></i>
                </button>
            </div>
            <!-- Start - button delete -->
        </div>

        <!-- Start - button add -->
        <div class="row mb-3">
            <div class="col-md-10 text-right">
                <button type="button" class="btn btn-success" @click="addRowMateri()">
                    <i class="ti-plus"></i>
                </button>
            </div>
        </div>
        <!-- End - button add -->
    </div>
</div>
<!-- End - Dynamic form detail sa materi -->