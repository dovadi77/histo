@extends('layout.main')
@section('title', 'Game')
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Game</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dash.game.index') }}">Daftar Game</a></li>
            <li class="breadcrumb-item active">Tambah Game</li>
        </ol>
        <div>
            <form action="{{ route('dash.game.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <x-input id="title" name="title" label="Judul" type="text" />
                <x-input id="banner" name="banner" label="Banner" type="file" />
                <div class="mb-3" id="levelContainer">
                    <label for="level" class="form-label">Level</label>
                    <select class="form-control text-uppercase" id="level" aria-errormessage="levelError" name="level">
                        <option selected disabled>Tentukan...</option>
                        <option value="easy">Mudah</option>
                        <option value="medium">Sedang</option>
                        <option value="hard">Sulit</option>
                    </select>
                    @error('level')
                        <small id="levelError" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <x-input id="maxTime" name="max_time" label="Batas Waktu (dalam detik)" type="number" />
                <div class="mb-3">
                    <label for="active" class="form-label">Apakah Aktif</label>
                    <select class="form-control text-uppercase" id="active" aria-errormessage="activeError" name="active">
                        <option selected disabled>Tentukan...</option>
                        <option value="1">YES</option>
                        <option value="0">NO</option>
                    </select>
                    @error('active')
                        <small id="activeError" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div id="gameContainer">
                    <h3>Game</h3>
                    <div class="mb-3">
                        <label for="game" class="form-label">Pilih tipe game</label>
                        <select class="form-control text-uppercase" id="game" aria-errormessage="gameError" name="game">
                            <option selected disabled>Tentukan...</option>
                            <option value="multiple">Multiple (Pilgan)</option>
                            <option value="voice">Voice (Suara)</option>
                        </select>
                        @error('game')
                            <small id="gameError" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div id="multiple" hidden>
                        <h3>Pertanyaan</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Correct Answer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><button type="button" class="btn btn-danger" onclick="delRow(this)">Del</button>
                                    </td>
                                    <td><input class="form-control" type="text" name="questions[]" /></td>
                                    <td><input class="form-control" type="text" name="choices[]"
                                            placeholder="Jawaban 1" /><input class="form-control" type="text"
                                            name="choices[]" placeholder="Jawaban 2" /><input class="form-control"
                                            type="text" name="choices[]" placeholder="Jawaban 3" /><input
                                            class="form-control" type="text" name="choices[]" placeholder="Jawaban 4" />
                                    </td>
                                    <td><input class="form-control" type="number" name="answers[]" placeholder="0-3"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"><button type="button" class="btn btn-info"
                                            onclick="addMulColumn()">Tambah</button></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="voice" hidden>
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
                                    <td><input class="form-control" type="text" name="questions[]" /></td>
                                    <td><input class="form-control" type="text" name="popup" /></td>
                                    <td><input class="form-control" type="text" name="answers[]"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">Tambah</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('custom_scripts')
    @if (Session::has('success') || Session::has('error'))
        @include('components.alert');
    @endif
    <script>
        let multiple = document.querySelector('#multiple');
        let voice = document.querySelector('#voice');
        let tBodyMul = multiple.querySelector('table > tbody');
        let tBodyVoi = voice.querySelector('table > tbody');
        let game = document.querySelector('#game');

        const addMulColumn = () => {
            tBodyMul.innerHTML += `<tr>
                                <td><button type="button" class="btn btn-danger" onclick="delRow(this)">Del</button></td>
                                <td><input class="form-control" type="text" name="questions[]" /></td>
                                <td><input class="form-control" type="text" name="choices[]"
                                        placeholder="Jawaban 1" /><input class="form-control" type="text" name="choices[]"
                                        placeholder="Jawaban 2" /><input class="form-control" type="text" name="choices[]"
                                        placeholder="Jawaban 3" /><input class="form-control" type="text" name="choices[]"
                                        placeholder="Jawaban 4" /></td>
                                <td><input class="form-control" type="number" name="answers[]" placeholder="0-3"></td>
                            </tr>`;
        }
        const delRow = (el) => {
            el.parentNode.parentNode.remove();
        }

        game.addEventListener('change', (e) => {
            if (game.value === 'voice') {
                multiple.hidden = true;
                voice.hidden = false;
            } else {
                multiple.hidden = false;
                voice.hidden = true;
            }
        });
    </script>
@endsection
