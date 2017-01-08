<table class="table table-bordered">
    <thead>
        <tr>
            <th class="td-width-count">#</th>
            <th class="td-width-info">{{ trans('app.active_page') }}</th>
            <th class="td-width-total text-right">{{ trans('app.users') }}</th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 1 ?>
    @foreach($data as $key => $val)
        <tr>
            <td>{{ $i }}</td>
            <td class="long-words">{{ $key }}@if($key) <a target="_blank" href="{{ url($key) }}"><i class="fa fa-external-link"></i></a>@endif</td>
            <td class="text-right"><span class="badge bg-red">{{ $val }}</span></td>
        </tr>
        <?php ++$i ?>
    @endforeach
    </tbody>
</table>