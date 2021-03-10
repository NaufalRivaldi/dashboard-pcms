@extends('layouts.app')

@section('content')
<!-- Start - Header -->
<div class="header">
    <div class="logo text-center"><img src="{{ asset('assets/images/logo-dark.png') }}" alt="Klorofil Logo"></div>
    <p class="lead">Login {{ replaceUnderscore(env('APP_NAME')) }}</p>
</div>
<!-- End - Header -->

<!-- Start - Form Login -->
<form class="form-auth-small" action="" method="POST">
    @csrf
    <!-- Start - Username -->
    <div class="form-group">
        <label for="username" class="control-label sr-only">Username</label>
        <input type="text" name="username" class="form-control" id="username" placeholder="Username">
    </div>
    <!-- Emd - Username -->

    <!-- Start - Password -->
    <div class="input-group">
        <label for="password" class="control-label sr-only">Password</label>
        <input :type="passwordVisible ? 'text' : 'password'" name="password" class="form-control" id="password" placeholder="Password" aria-label="password" aria-describedby="basic-addon-password">

        <div class="input-group-prepend">
            <button type="button" class="input-group-text" id="basic-addon-password" @click="passwordChange()">
                <span class="ti-eye"></span>
            </button>
        </div>
    </div>
    <!-- End - Password -->

    <!-- Start - Button -->
    <button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
    <!-- End - Button -->

    <!-- Start - Bottom -->
    <div class="bottom">
        <span class="helper-text"><i class="fa fa-lock"></i> <a href="#">Forgot password?</a></span>
    </div>
    <!-- End - Bottom -->
</form>
<!-- End - Form Login -->
@endsection

@push('scripts')
<script>
    // ----------------------------------------------------------------------------
    // Set Vue
    // ----------------------------------------------------------------------------
    new Vue({
        // ------------------------------------------------------------------------
        el: '#app',
        // ------------------------------------------------------------------------
        // Data for login page
        // ------------------------------------------------------------------------
        data: {
            passwordVisible: false,
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for login page
        // ------------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------------
            // Password change visible
            // --------------------------------------------------------------------
            passwordChange: function(){
                if(this.passwordVisible) this.passwordVisible = false
                else this.passwordVisible = true
            }
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------
    })
    // ----------------------------------------------------------------------------
</script>
@endpush
