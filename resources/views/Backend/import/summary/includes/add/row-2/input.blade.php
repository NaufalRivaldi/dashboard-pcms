<!-- Start - Alert -->
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Lengkapi data import!</strong> Jika sudah lengkap anda dapat membuat summary import.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<!-- End - Alert -->

<!-- Start - Table status import -->
<div class="container-fluid">
    <div class="row text-center">

        <!-- Start - LA03 -->
        <div class="col-md-2 col-sm-3 border p-3">
            <template v-if="this.statusImport.la03">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA03-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA03-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a href="{{ route('import.la03.index') }}" class="btn btn-sm btn-block btn-danger">Import data</a>
            </template>
        </div>
        <!-- End - LA03 -->

        <!-- Start - LA06 -->
        <div class="col-md-2 col-sm-3 border p-3">
            <template v-if="this.statusImport.la06">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA06-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA06-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a href="{{ route('import.la06.index') }}" class="btn btn-sm btn-block btn-danger">Import data</a>
            </template>
        </div>
        <!-- End - LA06 -->

        <!-- Start - LA07 -->
        <div class="col-md-2 col-sm-3 border p-3">
            <template v-if="this.statusImport.la07">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA07-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA07-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a href="{{ route('import.la07.index') }}" class="btn btn-sm btn-block btn-danger">Import data</a>
            </template>
        </div>
        <!-- End - LA07 -->

        <!-- Start - LA09 -->
        <div class="col-md-2 col-sm-3 border p-3">
            <template v-if="this.statusImport.la09">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA09-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA09-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a href="{{ route('import.la09.index') }}" class="btn btn-sm btn-block btn-danger">Import data</a>
            </template>
        </div>
        <!-- End - LA09 -->

        <!-- Start - LA12 -->
        <div class="col-md-2 col-sm-3 border p-3">
            <template v-if="this.statusImport.la12">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA12-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA12-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a href="{{ route('import.la12.index') }}" class="btn btn-sm btn-block btn-danger">Import data</a>
            </template>
        </div>
        <!-- End - LA12 -->

        <!-- Start - LA13 -->
        <div class="col-md-2 col-sm-3 border p-3">
            <template v-if="this.statusImport.la13">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA13-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA13-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a href="{{ route('import.la11.index') }}" class="btn btn-sm btn-block btn-danger">Import data</a>
            </template>
        </div>
        <!-- End - LA13 -->
    </div>
</div>
<!-- End - Table status import -->