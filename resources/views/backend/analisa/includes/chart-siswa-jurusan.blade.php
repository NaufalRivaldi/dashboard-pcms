<div class="row">
    <div class="col-md-12 text-center">
        <h6>
            SUMMARY LAPORAN SISWA AKTIF BERDASARKAN JURUSAN<br>
            <span v-if="cabang != null">
                CABANG: @{{ cabang }}<br>
            </span>

            <span v-if="wilayah != null">
                WILAYAH: @{{ wilayah }}<br>
            </span>

            <span v-if="subWilayah != null">
                SUB WILAYAH: @{{ subWilayah }}<br>
            </span>
            PERIODE: @{{ labels.length > 0 ? labels[0]+' - '+labels[labels.length - 1] : '-' }}
        </h6>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <chart-siswa :dataset="chartSiswaAktifJurusan.dataSets" :label="labels"></chart-siswa>
    </div>

    <!-- Start - Table uang siswa -->
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered defaultDatatable">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th v-for="(value, index) in chartSiswaAktifJurusan.dataSets">
                            @{{ value.label }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(label, index) in labels">
                        <td>@{{ label }}</td>
                        <td align="right" v-for="(value, indexSiswa) in chartSiswaAktifJurusan.dataSets">
                            @{{ chartSiswaAktifJurusan.dataSets[indexSiswa].data[index] | numeral('0,0') }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right" v-for="(value, indexSiswa) in chartSiswaAktifJurusan.dataSets">
                            @{{ _.sumBy(chartSiswaAktifJurusan.dataSets[indexSiswa].data, item => Number(item)) | numeral('0,0') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- Start - Table uang siswa -->
</div>