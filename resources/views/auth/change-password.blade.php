@extends('layout.main')
@section('title', 'User')
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">User</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Ganti Password</li>
        </ol>
        <div>
            <form action="{{ route('user.password.update', ['user' => auth()->user()->id]) }}" method="POST">
                @csrf
                {{-- <div class="mb-3">
                    <label for="password" class="form-label">Password Lama</label>
                    <input type="password" class="form-control" name="password" id="password"
                        value="{{ old('password') }}" />
                    @error('password')
                        <small id="passwordError" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div> --}}
                <div class="mb-3">
                    <label for="newPassword" class="form-label">Password Baru</label>
                    <input type="newPassword" class="form-control" name="new_password" id="newPassword"
                        value="{{ old('new_password') }}" />
                    @error('new_password')
                        <small id="newPasswordError" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="confirmNewPassword" class="form-label">Konfirmasi Password Baru</label>
                    <input type="confirmNewPassword" class="form-control" name="new_password_confirmation"
                        id="confirmNewPassword" value="{{ old('new_password_confirmation') }}" />
                    @error('new_password_confirmation')
                        <small id="confirmNewPasswordError" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">Ubah</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('custom_scripts')
    @if (Session::has('success') || Session::has('error'))
        @include('component.alert');
    @endif
@endsection
