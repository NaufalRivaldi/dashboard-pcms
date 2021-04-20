<div class="row">
    <div class="col-md-12 text-center">
        <h6>
            TOP5 REKAPTULASI SISWA AKTIF BERDASARKAN JURUSAN<br>
            PERIODE: @{{ periode }}
        </h6>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <chart-siswa :dataset="chartSiswaAktifJurusan.dataSets" :label="chartSiswaAktifJurusan.labels"></chart-siswa>
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
                    <tr v-for="(label, index) in chartSiswaAktifJurusan.labels">
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