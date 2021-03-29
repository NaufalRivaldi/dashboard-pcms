@extends('layouts.app_master')

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