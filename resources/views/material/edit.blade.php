@extends('layout.main')
@section('title', 'Material')
@section('custom_styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
@endsection
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Material</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dash.material.index') }}">Daftar Material</a></li>
            <li class="breadcrumb-item active">Edit Material</li>
        </ol>
        <div>
            <form action="{{ route('dash.material.update', ['material' => $material->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <x-input id="title" name="title" label="Judul" type="text" value="{{ $material->title }}" />
                <x-input id="banner" name="banner" label="Banner" type="file" />
                <x-input id="header" name="header" label="Header" type="file" />
                <div class="mb-3">
                    <label for="content" class="form-label">Konten</label>
                    <textarea class="form-control" id="content" rows="5"
                        name="content">{{ $material->content }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="parent" class="form-label">Apakah Parent</label>
                    <select class="form-control text-uppercase" id="parent" aria-errormessage="parentError" name="isParent">
                        <option selected disabled value="{{ $material->parent_id == 0 ? 1 : 0 }}">
                            {{ $material->parent_id == 0 ? 'YES' : 'NO' }}</option>
                    </select>
                    @error('isParent')
                        <small id="parentError" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                @if ($material->parent_id != 0)
                    <div class="mb-3" id="parentContainer">
                        <label for="parent" class="form-label">Parent</label>
                        <select class="form-control text-uppercase" id="parent" aria-errormessage="parentError"
                            name="parent">
                            @foreach ($parents as $p)
                                <option {{ $p->id == $material->parent_id ? 'selected' : '' }}
                                    value="{{ $p->id }}">
                                    {{ $p->title }}</option>
                            @endforeach
                        </select>
                        @error('parent')
                            <small id="parentError" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                @endif
                <div class="mb-3">
                    <label for="active" class="form-label">Apakah Aktif</label>
                    <select class="form-control text-uppercase" id="active" aria-errormessage="activeError" name="active">
                        <option {{ $material->active ? 'selected' : '' }} value="1">YES</option>
                        <option {{ !$material->active ? 'selected' : '' }} value="0">NO</option>
                    </select>
                    @error('active')
                        <small id="activeError" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                @if ($material->parent_id != 0)
                    <div id="quizContainer">
                        <h3>Quiz</h3>
                        <x-input id="quizTitle" name="quiz_title" label="Judul Quiz" type="text"
                            value="{{ $material->quiz->title }}" />
                        @if ($material->quiz->type == 'multiple')
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
                                        <?php $questions = explode('|', $material->quiz->content);
                                        $answers = explode(',', $material->quiz->answer); ?>
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
                                            <?php $content = explode('|', $material->quiz->content); ?>
                                            <td><input class="form-control" type="text" name="questions[]"
                                                    value="{{ $content[1] ?? '' }}" /></td>
                                            <td><input class="form-control" type="text" name="popup"
                                                    value="{{ $content[0] ?? '' }}" /></td>
                                            <td><input class="form-control" type="text" name="answers[]"
                                                    value="{{ $material->quiz->answer }}"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
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
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script>
        let simplemde = new SimpleMDE({
            element: document.getElementById("content"),
            spellChecker: false,
            forceSync: true
        });
    </script>
@endsection
