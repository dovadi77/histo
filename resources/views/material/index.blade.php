@extends('layout.main')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Material</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Daftar Material</li>
        </ol>
        <div class="mb-4 d-flex flex-column">
            <a name="addMaterial" id="addMaterial" class="btn btn-primary" href="{{ route('dash.material.create') }}"
                role="button">Tambah Data</a>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Material
            </div>
            <div class="card-body">
                <x-table componentID="materialTable" :rows="$materials" edit="/historian/material/edit"
                    delete="/historian/material/delete" />
            </div>
        </div>
    </div>
@endsection
@section('custom_scripts')
    @if (Session::has('success') || Session::has('error'))
        @include('components.alert');
    @endif
    <script>
        const table = initTable('materialTable');
    </script>
@endsection
