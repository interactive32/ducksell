<table class="table table-bordered">
    <thead>
        <tr>
            <th class="td-width-count">#</th>
            <th class="td-width-info">{{ trans('app.country') }}</th>
            <th class="td-width-total text-right">{{ trans('app.users') }}</th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 1 ?>
    @foreach($data as $key => $val)
        <tr>
            <td>{{ $i }}</td>
            <td class="long-words">{{ $key  }}</td>
            <td class="text-right"><span class="badge bg-orange">{{ $val }}</span></td>
        </tr>
        <?php ++$i ?>
    @endforeach
    </tbody>
</table>