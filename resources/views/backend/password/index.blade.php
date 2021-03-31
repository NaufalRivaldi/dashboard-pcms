@extends('layouts.content_form')

@section('content-form')
<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('password-user.update') }}" method="POST">
            @csrf
            <!-- Start - Password old -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    <span class="badge badge-danger">Required</span><br>
                    <div class="label-form">Password lama</div>
                </label>
                <div class="col-sm-10 input-form">
                    <input type="password" name="password_old" class="form-control @if($errors->has('password_old')) is-invalid @endif" value="{{ old('password_old') }}" required>
                    <!-- Start - Error handling -->
                    @if($errors->has('password_old'))
                        <div class="invalid-feedback">{{ $errors->first('password_old') }}</div>
                    @endif
                    <!-- End - Error handling -->
                </div>
            </div>
            <!-- End - Password old -->

            <!-- Start - Password new -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    <span class="badge badge-danger">Required</span><br>
                    <div class="label-form">Password Baru</div>
                </label>
                <div class="col-sm-10 input-form">
                    <input type="password" name="password_new" v-model="password.new" class="form-control @if($errors->has('password_new')) is-invalid @endif" value="{{ old('password_new') }}" @keyup="checkNewPassword()" required>
                    <!-- Start - Error handling -->
                    @if($errors->has('password_new'))
                        <div class="invalid-feedback">{{ $errors->first('password_new') }}</div>
                    @endif
                    <!-- End - Error handling -->
                </div>
            </div>
            <!-- End - Password new -->

            <!-- Start - Password confirmation -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    <span class="badge badge-danger">Required</span><br>
                    <div class="label-form">Confirmasi Password</div>
                </label>
                <div class="col-sm-10 input-form">
                    <input type="password" name="password_confirm" v-model="password.confirm" class="form-control @if($errors->has('password_confirm')) is-invalid @endif" value="{{ old('password_confirm') }}" @keyup="checkNewPassword()" required>
                    <!-- Start - Error handling -->
                    @if($errors->has('password_confirm'))
                        <div class="invalid-feedback">{{ $errors->first('password_confirm') }}</div>
                    @endif
                    <!-- End - Error handling -->
                </div>

                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <small v-if="validation.password" class="text-danger">Password baru dan confirmasi password tidak sama!</small>
                </div>
            </div>
            <!-- End - Password confirmation -->

            <!-- Start - Button -->
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success" :disabled="validation.password">
                        <i class="ti-save"></i> Ubah Password
                    </button>
                </div>
            </div>
            <!-- End - Button -->
        </form>
    </div>
</div>
@endsection

@section('card-button-footer')
<a href="{{ url()->previous() }}" class="btn btn-info">
    <i class="ti-arrow-circle-left"></i> Kembali
</a>
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
        // Data for materi page
        // ------------------------------------------------------------------------
        data: {
            // --------------------------------------------------------------------
            password: {
                new: null,
                confim: null,
            },
            // --------------------------------------------------------------------
            validation: {
                password: false,
            },
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for materi page
        // ------------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------------
            // Check new password
            // --------------------------------------------------------------------
            checkNewPassword: function(){
                if(this.password.new === this.password.confirm){
                    this.validation.password = false;
                }else{
                    this.validation.password = true;
                }
            }
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for materi page
        // ------------------------------------------------------------------------
        mounted() {
            // --------------------------------------------------------------------
            let vm = this;
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------
    })
    // ----------------------------------------------------------------------------
</script>
@endpush