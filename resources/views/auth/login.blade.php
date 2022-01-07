@extends('layout.auth')

@section('title', 'Masuk')
@section('content')
    <main class="text-center form-signin">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                {{ $errors->first() }}
            </div>
        @endif
        <form action="{{ route('auth.login') }}" method="POST">
            @csrf
            <h1>Histo Dashboard</h1>
            <h3 class="h3 mb-3 fw-normal">Silahkan masuk</h3>

            <div class="form-floating">
                <input type="text" name="username" class="form-control" id="floatingInput"
                    placeholder="name@example.com" />
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" />
                <label for="floatingPassword">Password</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">
                Sign in
            </button>
            <p class="mt-5 mb-3 text-muted">Make with 💖 Histo Team</p>
        </form>
    </main>
@endsection
