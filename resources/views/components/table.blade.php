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
            {{-- {{ dd($thead) }} --}}
            @foreach ($rows as $td)
                <tr>
                    <td>{{ $i }}</td>
                    @foreach ($thead as $th)
                        <td>{{ $td[$th] }}</td>
                    @endforeach
                </tr>
                <?php $i += 1; ?>
            @endforeach
        </tbody>
    </table>
</div>
