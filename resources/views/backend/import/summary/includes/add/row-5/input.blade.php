<!-- Start - Dynamic form detail sa materi -->
<div class="form-group row">
    <label class="col-sm-2 col-form-label">
        <span class="badge badge-danger">Required</span><br>
        <div class="label-form">Siswa Aktif Berdasarkan Pendidikan</div>
    </label>
    <div class="col-sm-10 input-form">
        <div class="row mb-3" v-for="(summaryPendidikan, index) in result.summary.summary_sa_pendidikan">
            <!-- Start - pendidikan_id -->
            <div class="col-md-5">
                <select name="pendidikan_id[]" v-model="summaryPendidikan.pendidikan_id" class="form-control" required>
                    <option value="">Pilih pendidikan</option>
                    @foreach($pendidikans as $pendidikan)
                        <option value="{{ $pendidikan->id }}">{{ $pendidikan->nama }}</option>
                    @endforeach
                </select>
            </div>
            <!-- End - pendidikan_id -->

            <!-- Start - jumlah -->
            <div class="col-md-5">
                <input type="text" name="jumlah_p[]" v-model="summaryPendidikan.jumlah" class="form-control" onkeypress="return onlyNumberKey(event)" placeholder="jumlah" required>
            </div>
            <!-- End - jumlah -->
            
            <!-- Start - button delete -->
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" @click="deleteRowPendidikan(index)">
                    <i class="ti-trash"></i>
                </button>
            </div>
            <!-- Start - button delete -->
        </div>

        <!-- Start - button add -->
        <div class="row mb-3">
            <div class="col-md-10 text-right">
                <button type="button" class="btn btn-success" @click="addRowPendidikan()">
                    <i class="ti-plus"></i>
                </button>
            </div>
        </div>
        <!-- End - button add -->
    </div>
</div>
<!-- End - Dynamic form detail sa materi -->