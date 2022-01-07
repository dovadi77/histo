<div class="container table-responsive">
    <table class="table" id="{{ $componentID }}">
        <thead>
            <tr>
                <th>#</th>
                @foreach ($thead as $th)
                    <th>{{ ucwords($th) }}</th>
                @endforeach
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
                            <a class="btn btn-primary" href="{{ $edit . '/' . $td }}"><i class="fas fa-edit"></i></a>
                        </td>
                    @endif
                </tr>
                <?php $i += 1; ?>
            @endforeach
        </tbody>
    </table>
</div>
