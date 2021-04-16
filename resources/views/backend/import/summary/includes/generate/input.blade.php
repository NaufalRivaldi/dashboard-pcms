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
    <div class="row">

        <!-- Start - LA03 -->
        <div class="col-md-4 col-sm-3 border p-3">
            <template v-if="this.import.la03">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA03-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>

                <!-- Start - result -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Periode</td>
                            <td>@{{ this.import.la03.bulan_tahun }}</td>
                        </tr>
                        <tr>
                            <td>Uang Pendaftaran</td>
                            <td>@{{ this.import.la03.u_pendaftaran | numeral('0,0') }}</td>
                        </tr>
                        <tr>
                            <td>Uang Kursus</td>
                            <td>@{{ this.import.la03.u_kursus | numeral('0,0') }}</td>
                        </tr>
                        <tr>
                            <td>Total Penerimaan</td>
                            <td>@{{ (this.import.la03.u_pendaftaran + this.import.la03.u_kursus) | numeral('0,0') }}</td>
                        </tr>
                        <tr>
                            <td>Royalti (10%)</td>
                            <td>@{{ (this.import.la03.u_pendaftaran + this.import.la03.u_kursus) * 0.1 | numeral('0,0') }}</td>
                        </tr>
                    </table>
                </div>
                <!-- End - result -->

                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA03-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a target="_blank" href="{{ route('import.la03.index') }}" class="btn btn-sm btn-block btn-danger">Import data</a>
            </template>
        </div>
        <!-- End - LA03 -->

        <!-- Start - LA06 -->
        <div class="col-md-4 col-sm-3 border p-3">
            <template v-if="this.import.la06">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA06-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>

                <!-- Start - result -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Periode</td>
                            <td>@{{ this.import.la06.bulan_tahun }}</td>
                        </tr>
                        <tr>
                            <td>Total Siswa Aktif</td>
                            <td>@{{ this.import.la06.jumlah_siswa | numeral('0,0') }}</td>
                        </tr>
                    </table>

                    <table class="table table-bordered table-striped">
                        <tr v-for="(detail, index) in this.import.la06.siswa_aktif_details">
                            <td>@{{ detail.materi.nama }}</td>
                            <td>@{{ detail.jumlah }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <th>@{{ sumJumlah(this.import.la06.siswa_aktif_details) | numeral('0,0') }}</th>
                        </tr>
                    </table>
                </div>
                <!-- End - result -->

                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA06-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a target="_blank" href="{{ route('import.la06.index') }}" class="btn btn-sm btn-block btn-danger">Import data</a>
            </template>
        </div>
        <!-- End - LA06 -->

        <!-- Start - LA07 -->
        <div class="col-md-4 col-sm-3 border p-3">
            <template v-if="this.import.la07">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA07-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>

                <!-- Start - result -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Periode</td>
                            <td>@{{ this.import.la07.bulan_tahun }}</td>
                        </tr>
                        <tr>
                            <td>Total Siswa Aktif</td>
                            <td>@{{ this.import.la07.jumlah_siswa | numeral('0,0') }}</td>
                        </tr>
                    </table>

                    <table class="table table-bordered table-striped">
                        <tr v-for="(detail, index) in this.import.la07.siswa_aktif_pendidikan_details">
                            <td>@{{ detail.pendidikan.nama }}</td>
                            <td>@{{ detail.jumlah }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <th>@{{ sumJumlah(this.import.la07.siswa_aktif_pendidikan_details) | numeral('0,0') }}</th>
                        </tr>
                    </table>
                </div>
                <!-- End - result -->

                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA07-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a target="_blank" href="{{ route('import.la07.index') }}" class="btn btn-sm btn-block btn-danger">Import data</a>
            </template>
        </div>
        <!-- End - LA07 -->

        <!-- Start - LA09 -->
        <div class="col-md-4 col-sm-3 border p-3">
            <template v-if="this.import.la09">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA09-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>

                <!-- Start - result -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Periode</td>
                            <td>@{{ this.import.la09.bulan_tahun }}</td>
                        </tr>
                        <tr>
                            <td>Total Siswa Baru</td>
                            <td>@{{ this.import.la09.jumlah | numeral('0,0') }}</td>
                        </tr>
                    </table>
                </div>
                <!-- End - result -->

                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA09-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a target="_blank" href="{{ route('import.la09.index') }}" class="btn btn-sm btn-block btn-danger">Import data (Optional)</a>
            </template>
        </div>
        <!-- End - LA09 -->

        <!-- Start - LA12 -->
        <div class="col-md-4 col-sm-3 border p-3">
            <template v-if="this.import.la12">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA12-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>

                <!-- Start - result -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Periode</td>
                            <td>@{{ this.import.la12.bulan_tahun }}</td>
                        </tr>
                        <tr>
                            <td>Total Siswa Keluar</td>
                            <td>@{{ this.import.la12.jumlah | numeral('0,0') }}</td>
                        </tr>
                    </table>
                </div>
                <!-- End - result -->

                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA12-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a target="_blank" href="{{ route('import.la12.index') }}" class="btn btn-sm btn-block btn-danger">Import data (Optional)</a>
            </template>
        </div>
        <!-- End - LA12 -->

        <!-- Start - LA13 -->
        <div class="col-md-4 col-sm-3 border p-3">
            <template v-if="this.import.la13">
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA13-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>

                <!-- Start - result -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Periode</td>
                            <td>@{{ this.import.la13.bulan_tahun }}</td>
                        </tr>
                        <tr>
                            <td>Total Siswa Cuti</td>
                            <td>@{{ this.import.la13.jumlah | numeral('0,0') }}</td>
                        </tr>
                    </table>
                </div>
                <!-- End - result -->

                <button type="button" class="btn btn-sm btn-block btn-success">Sudah diimport</button>
            </template>

            <template v-else>
                <span class="badge badge-success">@{{ this.label.cabang.kode + "-LA13-" + this.label.year + this.label.month + ".CSV" }}</span><br><br>
                <a target="_blank" href="{{ route('import.la11.index') }}" class="btn btn-sm btn-block btn-danger">Import data (Optional)</a>
            </template>
        </div>
        <!-- End - LA13 -->
    <!-- End - Table status import -->

    </div>
</div>