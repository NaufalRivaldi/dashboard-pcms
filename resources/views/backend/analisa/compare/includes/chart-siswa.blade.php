<div class="row">
    <div class="col-md-12 text-center">
        <h6>
            PERBANDINGAN SUMMARY LAPORAN SISWA AKTIF<br>
            <span v-if="cabang != null">
                CABANG: @{{ cabang[0] }} DENGAN CABANG: @{{ cabang[1] }}<br>
            </span>

            <span v-if="wilayah != null">
                WILAYAH: @{{ wilayah[0] }} DENGAN WILAYAH: @{{ wilayah[1] }}<br>
            </span>

            <span v-if="subWilayah != null">
                SUB WILAYAH: @{{ subWilayah[0] }} DENGAN SUB WILAYAH: @{{ subWilayah[1] }}<br>
            </span>
            PERIODE: @{{ labels.length > 0 ? labels[0].split(" / ")[0]+' - '+labels[labels.length - 1].split(" / ")[0] : '-' }}
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
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">Periode</th>
                    <th colspan="2">Siswa Aktif</th>
                    <th colspan="2">Siswa Baru</th>
                    <th colspan="2">Siswa Cuti</th>
                    <th colspan="2">Siswa Keluar</th>
                </tr>
                <tr>
                    <th>@{{ labels[0].split(" / ")[1] }}</th>
                    <th>@{{ labels[labels.length - 1].split(" / ")[1] }}</th>
                    <th>@{{ labels[0].split(" / ")[1] }}</th>
                    <th>@{{ labels[labels.length - 1].split(" / ")[1] }}</th>
                    <th>@{{ labels[0].split(" / ")[1] }}</th>
                    <th>@{{ labels[labels.length - 1].split(" / ")[1] }}</th>
                    <th>@{{ labels[0].split(" / ")[1] }}</th>
                    <th>@{{ labels[labels.length - 1].split(" / ")[1] }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(label, index) in labels">
                    <td v-if="index % 2 == 0">@{{ label.split(" / ")[0] }}</td>
                    <td align="right" v-if="index % 2 == 0">@{{ chartSiswaAktif.dataSets[0].data[index] | numeral('0,0') }}</td>
                    <td align="right" v-if="index % 2 == 0">@{{ chartSiswaAktif.dataSets[0].data[index+1] | numeral('0,0') }}</td>
                    <td align="right" v-if="index % 2 == 0">@{{ chartSiswaAktif.dataSets[1].data[index] | numeral('0,0') }}</td>
                    <td align="right" v-if="index % 2 == 0">@{{ chartSiswaAktif.dataSets[1].data[index+1] | numeral('0,0') }}</td>
                    <td align="right" v-if="index % 2 == 0">@{{ chartSiswaAktif.dataSets[2].data[index] | numeral('0,0') }}</td>
                    <td align="right" v-if="index % 2 == 0">@{{ chartSiswaAktif.dataSets[2].data[index+1] | numeral('0,0') }}</td>
                    <td align="right" v-if="index % 2 == 0">@{{ chartSiswaAktif.dataSets[3].data[index] | numeral('0,0') }}</td>
                    <td align="right" v-if="index % 2 == 0">@{{ chartSiswaAktif.dataSets[3].data[index+1] | numeral('0,0') }}</td>
                </tr>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-right">@{{ sumBy(chartSiswaAktif.dataSets[0].data, 0) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ sumBy(chartSiswaAktif.dataSets[0].data, 1) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ sumBy(chartSiswaAktif.dataSets[1].data, 0) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ sumBy(chartSiswaAktif.dataSets[1].data, 1) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ sumBy(chartSiswaAktif.dataSets[2].data, 0) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ sumBy(chartSiswaAktif.dataSets[2].data, 1) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ sumBy(chartSiswaAktif.dataSets[3].data, 0) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ sumBy(chartSiswaAktif.dataSets[3].data, 1) | numeral('0,0') }}</th>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Start - Table uang siswa -->
</div>