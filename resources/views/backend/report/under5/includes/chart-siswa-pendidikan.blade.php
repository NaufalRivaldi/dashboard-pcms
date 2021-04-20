<div class="row">
    <div class="col-md-12 text-center">
        <h6>
            UNDER 5 SUMMARY SISWA AKTIF BERDASARKAN PENDIDIKAN<br>
            PERIODE: @{{ periode }}
        </h6>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <chart-siswa :dataset="chartSiswaAktifPendidikan.dataSets" :label="chartSiswaAktifPendidikan.labels"></chart-siswa>
    </div>

    <!-- Start - Table uang siswa -->
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered defaultDatatable">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th v-for="(value, index) in chartSiswaAktifPendidikan.dataSets">
                            @{{ value.label }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(label, index) in chartSiswaAktifPendidikan.labels">
                        <td>@{{ label }}</td>
                        <td align="right" v-for="(value, indexSiswa) in chartSiswaAktifPendidikan.dataSets">
                            @{{ chartSiswaAktifPendidikan.dataSets[indexSiswa].data[index] | numeral('0,0') }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right" v-for="(value, indexSiswa) in chartSiswaAktifPendidikan.dataSets">
                            @{{ _.sumBy(chartSiswaAktifPendidikan.dataSets[indexSiswa].data, item => Number(item)) | numeral('0,0') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- Start - Table uang siswa -->
</div>