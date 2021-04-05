<div class="row">
    <div class="col-md-12 text-center">
        <h6>
            LAPORAN JUMLAH ROYALTI<br>
            CABANG: @{{ cabang }}<br>
            PERIODE: @{{ labels[0]+' - '+labels[11] }}
        </h6>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-6">
        <chart-royalti :dataset="chartRoyalti.dataSets" :label="labels"></chart-royalti>
    </div>

    <!-- Start - Table uang royalti -->
    <div class="col-md-6">
        <table class="table table-striped table-bordered defaultDatatable">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Royalti</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(label, index) in labels">
                    <td>@{{ label }}</td>
                    <td align="right">@{{ chartRoyalti.dataSets[0].data[index] | numeral('0,0') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-right">@{{ _.sum(chartRoyalti.dataSets[0].data) | numeral('0,0') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- Start - Table uang royalti -->
</div>