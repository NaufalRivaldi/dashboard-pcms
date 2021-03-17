@extends('layouts.content_form')

@section('content')
<div id="app" class="container-fluid">
    <!-- Start - row -->
    <div class="row">
        <div class="col-md-12">
            <!-- Start - card -->
            <div class="card">
                <div class="card-header row">
                    <h6 class="mt-1">Data</h6>
                </div>
                <div class="card-body">
                    
                    <!-- Start - Detail materi -->
                    <div class="row">
                        <div class="col-sm-2 font-weight-bold">Nama</div>
                        <div class="col-sm-1 text-right">:</div>
                        <div class="col-sm-9">{{ $materi->nama }}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-2 font-weight-bold">Kategori</div>
                        <div class="col-sm-1 text-right">:</div>
                        <div class="col-sm-9">{{ $materi->kategori->nama }}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-2 font-weight-bold">Status</div>
                        <div class="col-sm-1 text-right">:</div>
                        <div class="col-sm-9">{!! status($materi->status) !!}</div>
                    </div>
                    <!-- End - Detail materi -->

                    <hr>

                    <!-- Start - Set Grade -->
                    <h5>Grade setting</h5>
                    <div class="row">
                        
                        <!-- Start - kode_materi -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kode Materi <span class="badge badge-danger">Required</span></label>
                                <input type="text" v-model="materiGrade.kode_materi" :class="validation.kode_materi ? ['form-control', 'is-invalid'] : ['form-control']" @keyup="validationState('kode_materi', null)">
                                <small class="text-info">*Kode tidak boleh sama</small>
                            </div>
                        </div>
                        <!-- Emd - kode_materi -->

                        <!-- Start - kode_grade -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kode Grade <span class="badge badge-danger">Required</span></label>
                                <input type="text" v-model="materiGrade.kode_grade" :class="validation.kode_grade ? ['form-control', 'is-invalid'] : ['form-control']" @keyup="validationState('kode_grade', null)">
                                <small class="text-info">*Kode tidak boleh sama</small>
                            </div>
                        </div>
                        <!-- Emd - kode_grade -->

                        <!-- Start - grade_id -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Grade <span class="badge badge-danger">Required</span></label>
                                <select v-model="materiGrade.grade_id" :class="validation.grade_id ? ['form-control', 'is-invalid'] : ['form-control']" @change="validationState('grade_id', null)">
                                    <option value="">Pilih</option>
                                    <option v-for="(grade, index) in preset.grades" :key="index" :value="grade.id" :disabled="checkGrade(grade.id)">@{{  grade.nama }}</option>
                                </select>
                            </div>
                        </div>
                        <!-- Emd - grade_id -->

                        <!-- Start - biaya -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Biaya <span class="badge badge-danger">Required</span></label>
                                <input type="number" v-model="materiGrade.biaya" :class="validation.biaya ? ['form-control', 'is-invalid'] : ['form-control']" @keyup="validationState('biaya', null)" step="0.1" placeholder="Rp.-">
                            </div>
                        </div>
                        <!-- Emd - biaya -->

                    </div>
                    <!-- End - Set Grade -->

                    <!-- Start - submit button -->
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-success" @click="submit()"><i class="ti-save"></i> Simpan</button>
                        </div>
                    </div>
                    <!-- End - submit button -->

                    <!-- Start - Table grade -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    
                                    <thead>
                                        <tr>
                                            <th>Kode Materi</th>
                                            <th>Kode Grade</th>
                                            <th>Grade</th>
                                            <th>Biaya (Rp.)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody v-if="results.length > 0">
                                        <tr v-for="(result, index) in results" :key="index">
                                            <td>@{{ result.kode_materi }}</td>
                                            <td>@{{ result.kode_grade }}</td>
                                            <td>@{{ result.grade.nama }}</td>
                                            <td>@{{ result.biaya | numeral('0,0') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" @click="deleteGrade(result.id)">
                                                    <i class="ti-trash"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>

                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data grade</td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Start - Table grade -->

                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('master.materi.index') }}" class="btn btn-info">
                        <i class="ti-arrow-circle-left"></i> Kembali
                    </a>
                </div>
            </div>
            <!-- End - card -->
        </div>
    </div>
    <!-- End - row -->
</div>
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
        // Data for materi page
        // ------------------------------------------------------------------------
        data: {
            // --------------------------------------------------------------------
            preset: {
                // ----------------------------------------------------------------
                grades: @json($grade),
                grade_id: @json($grade_id),
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Set result of materi grade here
            // --------------------------------------------------------------------
            results: @json($materiGrade),
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Set data form here
            // --------------------------------------------------------------------
            materiGrade: {
                materi_id: "{{ $materi->id }}",
                kode_materi: null, kode_grade: null, grade_id: null, biaya: 0,
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Set validation
            // --------------------------------------------------------------------
            validation: {
                kode_materi: null, kode_grade: null, grade_id: null, biaya: null,
            }
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for materi page
        // ------------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------------
            // Reset form
            // --------------------------------------------------------------------
            resetForm: function(){
               this.materiGrade.kode_materi     = null;
               this.materiGrade.kode_grade      = null;
               this.materiGrade.grade_id        = null;
               this.materiGrade.biaya           = null;
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Set null or false
            // --------------------------------------------------------------------
            validationState: function(type, value){
                // ----------------------------------------------------------------
                if(type == 'kode_materi'){
                    this.validation.kode_materi = value;
                }
                // ----------------------------------------------------------------
                if(type == 'kode_grade'){
                    this.validation.kode_grade = value;
                }
                // ----------------------------------------------------------------
                if(type == 'grade_id'){
                    this.validation.grade_id = value;
                }
                // ----------------------------------------------------------------
                if(type == 'biaya'){
                    this.validation.biaya = value;
                }
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // True of false for disabled grade
            // --------------------------------------------------------------------
            checkGrade: function(id){
                // ----------------------------------------------------------------
                let state = this.preset.grade_id.includes(id);
                // ----------------------------------------------------------------
                if(state) return true;
                return false;
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Submit form
            // --------------------------------------------------------------------
            submit: function(){
                console.log('asd');
                // ----------------------------------------------------------------
                let vm                  = this;
                let valid               = true;
                let emptyKodeMateri     = 0;
                let emptyKodeGrade      = 0;
                let emptyGradeId        = 0;
                let emptyBiaya          = 0;
                // ----------------------------------------------------------------
                // Check custom validation
                // ----------------------------------------------------------------
                if(vm.materiGrade.kode_materi == null || vm.materiGrade.kode_materi == ''){
                    emptyKodeMateri += 1;
                    vm.validationState('kode_materi', true);
                }
                // ----------------------------------------------------------------
                if(vm.materiGrade.kode_grade == null || vm.materiGrade.kode_grade == ''){
                    emptyKodeGrade += 1;
                    vm.validationState('kode_grade', true);
                }
                // ----------------------------------------------------------------
                if(vm.materiGrade.grade_id == null || vm.materiGrade.grade_id == ''){
                    emptyGradeId += 1;
                    vm.validationState('grade_id', true);
                }
                // ----------------------------------------------------------------
                if(vm.materiGrade.biaya == null || vm.materiGrade.biaya == ''){
                    emptyBiaya += 1;
                    vm.validationState('biaya', true);
                }
                // ----------------------------------------------------------------
                if(emptyKodeMateri > 0) valid = false;
                if(emptyKodeGrade > 0) valid = false;
                if(emptyGradeId > 0) valid = false;
                if(emptyBiaya > 0) valid = false;
                // ----------------------------------------------------------------
                if(valid){
                    // ------------------------------------------------------------
                    // Set request and url
                    // ------------------------------------------------------------
                    let url = "{{ route('master.materi.store.grade') }}";
                    let data = vm.materiGrade;
                    let request = axios.post(url, data);
                    // ------------------------------------------------------------
                    // If request success
                    // ------------------------------------------------------------
                    request.then((response)=>{
                        // --------------------------------------------------------
                        let data = response.data;
                        // --------------------------------------------------------
                        if(data.status) vm.results = data.materiGrades;
                        // --------------------------------------------------------
                        Vue.nextTick(function () {
                            if(data.status){
                                toastr.success(data.message);
                                vm.resetForm();
                                vm.preset.grade_id = data.grade_id;
                            }else{
                                toastr.error(data.message);
                            }
                        })
                        // --------------------------------------------------------
                    })
                    // ------------------------------------------------------------
                    // If request error
                    // ------------------------------------------------------------
                    request.catch((error)=>{
                        toastr.error(error.message);
                    })
                    // ------------------------------------------------------------
                }
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Delete grade
            // --------------------------------------------------------------------
            deleteGrade: function(id){
                // ----------------------------------------------------------------
                let vm = this;
                let url = "{{ route('master.materi.destroy.grade', ':id') }}";
                url = url.replace(':id', id);
                // ----------------------------------------------------------------
                // Set confirm
                // ----------------------------------------------------------------
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data terhapus pada list.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    // ------------------------------------------------------------
                    if (result.value) {
                        // --------------------------------------------------------
                        // Set request
                        // --------------------------------------------------------
                        let request = axios.delete(url);
                        // --------------------------------------------------------
                        // If request success
                        // --------------------------------------------------------
                        request.then((response)=>{
                            // ----------------------------------------------------
                            let data = response.data;
                            // ----------------------------------------------------
                            vm.results = data.materiGrades;
                            // ----------------------------------------------------
                            Vue.nextTick(function () {
                                toastr.success(data.message);    
                                vm.preset.grade_id = data.grade_id;
                            })
                            // ----------------------------------------------------
                        })
                        // --------------------------------------------------------
                        // If request error
                        // --------------------------------------------------------
                        request.catch((error)=>{
                            toastr.error(error.message);
                        })
                        // --------------------------------------------------------
                    }
                    // ------------------------------------------------------------
                })
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for materi page
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