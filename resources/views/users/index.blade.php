@extends('assets.layout')
@section('title')
    <x-title title="List Users" />
@endsection
@section('data')
    <div class="container-fluid px-4">
        <h1 class="mt-4">List Users</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">List users has been registered</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Users
            </div>
            <div class="card-body">
                <x-table componentID="usersTable" :rows="$users" />
            </div>
        </div>
    </div>
@endsection
@section('custom_scripts')
    <script>
        let table = DataTable.initTable('usersTable');
    </script>
@endsection
