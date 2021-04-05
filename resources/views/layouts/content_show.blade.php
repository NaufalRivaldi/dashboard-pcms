@extends('layouts.app_master')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    /* ------------------------------------------------------------ */
    /* Form style */
    /* ------------------------------------------------------------ */
    .ui-datepicker-calendar {
        display: none;
    }
    /* ------------------------------------------------------------ */
</style>
@endpush

@section('content')
<div id="app" class="container-fluid">
    <!-- Start - row -->
    <div class="row">
        <div class="col-md-12">
            <!-- Start - card -->
            <div class="card">
                <div class="card-header row">
                    <div class="col-sm-6 card-title">
                        <h6 class="mt-1">List</h6>
                    </div>
                    <div class="col-sm-6 text-right">
                        @yield('card-button')
                    </div>
                </div>
                <div class="card-body">
                    @yield('card-content')
                </div>
                @yield('card-footer')
            </div>
            <!-- End - card -->
        </div>
    </div>
    <!-- End - row -->

    <!-- Start - Modal -->
    @yield('modal')
    <!-- End - Modal -->
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function(){
        // ----------------------------------------------------------------------------
        // Init library
        // ----------------------------------------------------------------------------
        $( ".select2" ).select2({
            theme: "bootstrap"
        });
        // ----------------------------------------------------------------------------
        $('.date-picker-month').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                let val = new Date(inst.selectedYear, inst.selectedMonth, 1);
            }
        });
        // ----------------------------------------------------------------------------
    })
</script>
@endpush