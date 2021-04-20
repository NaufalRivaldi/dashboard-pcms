<div class="row">
    <div class="col-md-12 text-center">
        <h6>
            SUMMARY LAPORAN SISWA AKTIF<br>
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
        <chart-siswa :dataset="chartSiswaAktif.dataSets" :label="labels"></chart-siswa>
    </div>

    <!-- Start - Table uang siswa -->
    <div class="col-md-12">
        <table class="table table-striped table-bordered defaultDatatable">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Siswa Aktif</th>
                    <th>Siswa Baru</th>
                    <th>Siswa Cuti</th>
                    <th>Siswa Keluar</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(label, index) in labels">
                    <td>@{{ label }}</td>
                    <td align="right">@{{ chartSiswaAktif.dataSets[0].data[index] | numeral('0,0') }}</td>
                    <td align="right">@{{ chartSiswaAktif.dataSets[1].data[index] | numeral('0,0') }}</td>
                    <td align="right">@{{ chartSiswaAktif.dataSets[2].data[index] | numeral('0,0') }}</td>
                    <td align="right">@{{ chartSiswaAktif.dataSets[3].data[index] | numeral('0,0') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-right">@{{ _.sumBy(chartSiswaAktif.dataSets[0].data, item => Number(item)) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ _.sumBy(chartSiswaAktif.dataSets[1].data, item => Number(item)) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ _.sumBy(chartSiswaAktif.dataSets[2].data, item => Number(item)) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ _.sumBy(chartSiswaAktif.dataSets[3].data, item => Number(item)) | numeral('0,0') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- Start - Table uang siswa -->
</div>