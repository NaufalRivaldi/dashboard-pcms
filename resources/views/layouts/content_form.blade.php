@extends('layouts.app_master')

@push('css')

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
<script>
    $(function(){
        // ----------------------------------------------------------------------------
        //
        // ----------------------------------------------------------------------------
    })
</script>
@endpush