<div class="row">
    <div class="col-md-12 text-center">
        <h6>
            PERBANDINGAN SUMMARY LAPORAN SISWA AKTIF BERDASARKAN JURUSAN<br>
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
        <chart-siswa :dataset="chartSiswaAktifJurusan.dataSets" :label="labels"></chart-siswa>
    </div>

    <!-- Start - Table uang siswa -->
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-jurusan">
                <thead>
                    <tr>
                        <th rowspan="2">Periode</th>
                        <th colspan="2" v-for="(value, index) in chartSiswaAktifJurusan.dataSets">
                            @{{ value.label }}
                        </th>
                    </tr>
                    <tr>
                        <template colspan="2" v-for="(value, index) in chartSiswaAktifJurusan.dataSets">
                            <th>@{{ labels[0].split(" / ")[1] }}</th>
                            <th>@{{ labels[labels.length - 1].split(" / ")[1] }}</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(label, index) in labels">
                        <td v-if="index % 2 == 0">@{{ label.split(" / ")[0] }}</td>
                        <template v-for="(value, indexSiswa) in chartSiswaAktifJurusan.dataSets">
                            <td align="right" v-if="index % 2 == 0">
                                @{{ chartSiswaAktifJurusan.dataSets[indexSiswa].data[index] | numeral('0,0') }}
                            </td>
                            <td align="right" v-if="index % 2 == 0">
                                @{{ chartSiswaAktifJurusan.dataSets[indexSiswa].data[index+1] | numeral('0,0') }}
                            </td>
                        </template>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <template v-for="(value, indexSiswa) in chartSiswaAktifJurusan.dataSets">
                            <th class="text-right">
                                @{{ sumBy(chartSiswaAktifJurusan.dataSets[indexSiswa].data, 0) | numeral('0,0') }}
                            </th>
                            <th class="text-right">
                                @{{ sumBy(chartSiswaAktifJurusan.dataSets[indexSiswa].data, 1) | numeral('0,0') }}
                            </th>
                        </template>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Start - Table uang siswa -->
</div>