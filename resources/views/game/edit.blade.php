@extends('layout.main')
@section('title', 'game')
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Game</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dash.game.index') }}">Daftar Game</a></li>
            <li class="breadcrumb-item active">Edit Game</li>
        </ol>
        <div>
            <form action="{{ route('dash.game.update', ['game' => $game->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <x-input id="title" name="title" label="Judul" type="text" value="{{ $game->title }}" />
                <x-input id="banner" name="banner" label="Banner" type="file" />
                <div class="mb-3" id="levelContainer">
                    <label for="level" class="form-label">Level</label>
                    <select class="form-control text-uppercase" id="level" aria-errormessage="levelError" name="level">
                        <option selected disabled>Tentukan...</option>
                        <option {{ $game->level == 'easy' ? 'selected' : '' }} value="easy">Mudah</option>
                        <option {{ $game->level == 'medium' ? 'selected' : '' }} value="medium">Sedang</option>
                        <option {{ $game->level == 'hard' ? 'selected' : '' }} value="hard">Sulit</option>
                    </select>
                    @error('level')
                        <small id="levelError" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="active" class="form-label">Apakah Aktif</label>
                    <select class="form-control text-uppercase" id="active" aria-errormessage="activeError" name="active">
                        <option {{ $game->active ? 'selected' : '' }} value="1">YES</option>
                        <option {{ !$game->active ? 'selected' : '' }} value="0">NO</option>
                    </select>
                    @error('active')
                        <small id="activeError" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <x-input id="maxTime" name="max_time" label="Batas Waktu (dalam detik)" type="number"
                    value="{{ $game->max_time }}" />
                <div id="gameContainer">
                    <h3>Game</h3>
                    @if ($game->type == 'multiple')
                        <div id="multiple">
                            <h3>Pertanyaan</h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th>Correct Answer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $questions = explode('|', $game->content);
                                    $answers = explode(',', $game->answer); ?>
                                    @foreach ($questions as $q)
                                        <?php $q = explode(';', $q); ?>
                                        <tr>
                                            <td><input class="form-control" type="text" name="questions[]"
                                                    value="{{ $q[0] }}" /></td>
                                            <td><input class="form-control" type="text" name="choices[]"
                                                    placeholder="Jawaban 1" value="{{ $q[1] }}" /><input
                                                    class="form-control" type="text" name="choices[]"
                                                    placeholder="Jawaban 2" value="{{ $q[2] }}" /><input
                                                    class="form-control" type="text" name="choices[]"
                                                    placeholder="Jawaban 3" value="{{ $q[3] }}" /><input
                                                    class="form-control" type="text" name="choices[]"
                                                    placeholder="Jawaban 4" value="{{ $q[4] }}" />
                                            </td>
                                            <td><input class="form-control" type="number" name="answers[]"
                                                    placeholder="0-3" value="{{ $answers[$loop->index] }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div id="voice">
                            <h3>Pertanyaan</h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Pertanyaan</th>
                                        <th>Kata - kata Popup</th>
                                        <th>Kata yang harus diucapkan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php $content = explode('|', $game->content); ?>
                                        <td><input class="form-control" type="text" name="questions[]"
                                                value="{{ $content[1] ?? '' }}" /></td>
                                        <td><input class="form-control" type="text" name="popup"
                                                value="{{ $content[0] ?? '' }}" /></td>
                                        <td><input class="form-control" type="text" name="answers[]"
                                                value="{{ $game->answer }}"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
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
        @include('components.alert');
    @endif
@endsection
