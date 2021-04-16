@extends('layouts.content_form')

@section('content-form')
<form action="{{ $user->id == null ? route('master.user.store') : route('master.user.update', $user->id) }}" method="POST">
    @csrf
    @if($user->id != null)
        @method('PUT')
    @endif
    <!-- Start - Hidden input -->
    <input type="hidden" name="id" value="{{ $user->id }}">
    <!-- End - Hidden input -->

    <!-- Start - Nama -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Nama</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="text" name="nama" class="form-control @if($errors->has('nama')) is-invalid @endif" value="{{ $user->nama ? $user->nama : old('nama') }}" required>
            <!-- Start - Error handling -->
            @if($errors->has('nama'))
                <div class="invalid-feedback">{{ $errors->first('nama') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - Nama -->

    <!-- Start - Username -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Username</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="text" name="username" class="form-control @if($errors->has('username')) is-invalid @endif" value="{{ $user->username ? $user->username : old('username') }}" required>
            <!-- Start - Error handling -->
            @if($errors->has('username'))
                <div class="invalid-feedback">{{ $errors->first('username') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - Username -->

    <!-- Start - Email -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Email</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="email" name="email" class="form-control @if($errors->has('email')) is-invalid @endif" value="{{ $user->email ? $user->email : old('email') }}" required>
            <!-- Start - Error handling -->
            @if($errors->has('email'))
                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - Email -->

    <!-- Start - level_id -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Level</div>
        </label>
        <div class="col-sm-10 input-form">
            <select name="level_id" v-model="level_id" class="form-control @if($errors->has('level_id')) is-invalid @endif" required>
                <option value="">Pilih</option>
                @foreach($level as $id => $value)
                    <option value="{{ $id }}" @if($id == $user->level_id) selected @endif>{{ $value }}</option>
                @endforeach
            </select>
            <!-- Start - Error handling -->
            @if($errors->has('level_id'))
                <div class="invalid-feedback">{{ $errors->first('level_id') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - level_id -->

    <!-- Start - cabang_id -->
    <div class="form-group row" v-if="level_id == 4">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-success">Optional</span><br>
            <div class="label-form">Cabang</div>
        </label>
        <div class="col-sm-10 input-form">
            <select name="cabang_id" class="form-control @if($errors->has('cabang_id')) is-invalid @endif">
                <option value="">Pilih</option>
                @foreach($cabang as $id => $value)
                    <option value="{{ $id }}" @if($id == $user->cabang_id) selected @endif>{{ $value }}</option>
                @endforeach
            </select>
            <!-- Start - Error handling -->
            @if($errors->has('cabang_id'))
                <div class="invalid-feedback">{{ $errors->first('cabang_id') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - cabang_id -->

    <!-- Start - Button -->
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <button type="submit" class="btn btn-success">
                <i class="ti-save"></i> Simpan
            </button>
        </div>
    </div>
    <!-- End - Button -->
</form>
@endsection

@section('card-button-footer')
<a href="{{ route('master.user.index') }}" class="btn btn-info">
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
        // Data for user page
        // ------------------------------------------------------------------------
        data: {
            level_id: "{{ $user->level_id }}",
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for user page
        // ------------------------------------------------------------------------
        methods: {
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for user page
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