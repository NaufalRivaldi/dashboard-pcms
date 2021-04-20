<div class="row">
    <div class="col-md-12 text-center">
        <h6>
            SUMMARY LAPORAN PENERIMAAN<br>
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
        <chart-penerimaan :dataset="chartPenerimaan.dataSets" :label="labels"></chart-penerimaan>
    </div>

    <!-- Start - Table uang penerimaan -->
    <div class="col-md-12">
        <table class="table table-striped table-bordered defaultDatatable">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Uang Pendaftaran</th>
                    <th>Uang Kursus</th>
                    <th>Total Penerimaan</th>
                </tr>
            </thead>
            <tbody class="t-scroll-vertikal t-max-height-400">
                <tr v-for="(label, index) in labels">
                    <td>@{{ label }}</td>
                    <td align="right">@{{ chartPenerimaan.dataSets[1].data[index] | numeral('0,0') }}</td>
                    <td align="right">@{{ chartPenerimaan.dataSets[2].data[index] | numeral('0,0') }}</td>
                    <td align="right">@{{ chartPenerimaan.dataSets[0].data[index] | numeral('0,0') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-right">@{{ _.sum(chartPenerimaan.dataSets[1].data) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ _.sum(chartPenerimaan.dataSets[2].data) | numeral('0,0') }}</th>
                    <th class="text-right">@{{ _.sum(chartPenerimaan.dataSets[0].data) | numeral('0,0') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- Start - Table uang penerimaan -->
</div>