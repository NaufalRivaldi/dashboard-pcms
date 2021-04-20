<div class="row">
    <div class="col-md-12 text-center">
        <h6>
            UNDER 5 SUMMARY TOTAL PENERIMAAN<br>
            PERIODE: @{{ periode }}
        </h6>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <chart-penerimaan :dataset="chartPenerimaan.dataSets" :label="chartPenerimaan.labels" :width="1300" :height="500"></chart-penerimaan>
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
                <tr v-for="(label, index) in chartPenerimaan.labels">
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