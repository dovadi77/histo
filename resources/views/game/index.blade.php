@extends('layout.main')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Game</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Daftar Game</li>
        </ol>
        <div class="mb-4 d-flex flex-column">
            <a name="addGame" id="addGame" class="btn btn-primary" href="{{ route('dash.game.create') }}"
                role="button">Tambah Data</a>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Game
            </div>
            <div class="card-body">
                <x-table componentID="gameTable" :rows="$game" edit="/historian/game/edit"
                    delete="/historian/game/delete" />
            </div>
        </div>
    </div>
@endsection
@section('custom_scripts')
    @if (Session::has('success') || Session::has('error'))
        @include('components.alert');
    @endif
    <script>
        const table = initTable('gameTable');
    </script>
@endsection
