@extends('layout.main')
@section('title', 'Material')
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Material</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dash.material.index') }}">Daftar Material</a></li>
            <li class="breadcrumb-item active">Tambah Material</li>
        </ol>
        <div>
            <form action="{{ route('dash.material.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <x-input id="title" name="title" label="Judul" type="text" />
                <x-input id="banner" name="banner" label="Banner" type="file" />
                <x-input id="header" name="header" label="Header" type="file" />
                <x-input id="content" name="content" label="Konten" type="text" />
                <div class="mb-3">
                    <label for="parent" class="form-label">Apakah Parent</label>
                    <select class="form-control text-uppercase" id="parent" aria-errormessage="parentError" name="isParent">
                        <option selected disabled>Tentukan...</option>
                        <option value="1">YES</option>
                        <option value="0">NO</option>
                    </select>
                    @error('isParent')
                        <small id="parentError" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3" id="parentContainer" hidden>
                    <label for="parent" class="form-label">Parent</label>
                    <select class="form-control text-uppercase" id="parent" aria-errormessage="parentError" name="parent">
                        <option selected disabled>Tentukan...</option>
                        @foreach ($parents as $p)
                            <option value="{{ $p->id }}">{{ $p->title }}</option>
                        @endforeach
                    </select>
                    @error('parent')
                        <small id="parentError" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
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
                <div id="quizContainer" hidden>
                    <h3>Quiz</h3>
                    <x-input id="quizTitle" name="quiz_title" label="Judul Quiz" type="text" />
                    <div class="mb-3">
                        <label for="quiz" class="form-label">Pilih tipe Quiz</label>
                        <select class="form-control text-uppercase" id="quiz" aria-errormessage="quizError" name="quiz">
                            <option selected disabled>Tentukan...</option>
                            <option value="multiple">Multiple (Pilgan)</option>
                            <option value="voice">Voice (Suara)</option>
                        </select>
                        @error('quiz')
                            <small id="quizError" class="form-text text-danger">{{ $message }}</small>
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
        let isParent = document.querySelector("#parent");
        let multiple = document.querySelector('#multiple');
        let voice = document.querySelector('#voice');
        let tBodyMul = multiple.querySelector('table > tbody');
        let tBodyVoi = voice.querySelector('table > tbody');
        let quiz = document.querySelector('#quiz');

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

        isParent.addEventListener('change', (e) => {
            if (isParent.value === '0') {
                document.querySelector('#parentContainer').hidden = false;
                document.querySelector('#quizContainer').hidden = false;
            } else {
                document.querySelector('#parentContainer').hidden = true;
                document.querySelector('#quizContainer').hidden = true;
            }
        });
        quiz.addEventListener('change', (e) => {
            if (quiz.value === 'voice') {
                multiple.hidden = true;
                voice.hidden = false;
            } else {
                multiple.hidden = false;
                voice.hidden = true;
            }
        });
    </script>
@endsection
