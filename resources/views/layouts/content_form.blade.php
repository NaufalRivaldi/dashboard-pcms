@extends('layouts.app_master')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<style>
    /* ------------------------------------------------------------ */
    /* Form style */
    /* ------------------------------------------------------------ */
    .label-form{
        font-weight: bold;
        font-size: 1em;
    }

    .form-group > label > span{
        font-size: .7em;
    }

    .input-form{
        align-items: center !important;
        display: inline-grid;
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
                    <h6 class="mt-1">Add Form</h6>
                </div>
                <div class="card-body">
                    @yield('content-form')
                </div>
                <div class="card-footer text-center">
                    @yield('card-button-footer')
                </div>
            </div>
            <!-- End - card -->
        </div>
    </div>
    <!-- End - row -->
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function(){
        // ----------------------------------------------------------------------------
        // Init library
        // ----------------------------------------------------------------------------
        $( ".select2" ).select2({
            theme: "bootstrap"
        });
        // ----------------------------------------------------------------------------
    })
</script>
@endpush