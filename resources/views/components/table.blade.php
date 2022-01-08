<div class="container table-responsive">
    <table class="table" id="{{ $componentID }}">
        <thead>
            <tr>
                <th>#</th>
                @foreach ($thead as $th)
                    <th>{{ ucwords($th) }}</th>
                @endforeach
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            @foreach ($rows as $td)
                <tr>
                    <td>{{ $i }}</td>
                    @foreach ($thead as $th)
                        <td>{{ $td[$th] }}</td>
                    @endforeach
                    @if ($edit)
                        <td>
                            <a class="btn btn-primary" href="{{ $edit . '/' . $td['id'] }}"><i
                                    class="fas fa-edit"></i></a>
                        </td>
                    @endif
                    @if ($delete)
                        <td>
                            <form action="{{ $delete . '/' . $td['id'] }}" method="post">
                                @csrf
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    @endif
                </tr>
                <?php $i += 1; ?>
            @endforeach
        </tbody>
    </table>
</div>
