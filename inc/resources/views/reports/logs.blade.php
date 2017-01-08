@extends('app')

@section('content')

<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">{{ trans('app.logs') }}</h3>
        @include('partials.search')
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-6">
                <div class="action-buttons-top">
                    <a href="{{ url('logs/purge') }}" class="btn btn-default" data-method="post" data-confirm="{{ trans('app.are_you_sure') }}">
                        <i class="fa fa-trash-o"></i> &nbsp; {{ trans('app.purge') }}
                    </a>
                    @include('partials.export')
                </div>
            </div>
            <div class="col-xs-6">
                @include('partials.per_page')
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr role="row">
                                <th>{{ trans('app.id') }}</th>
                                <th>{{ trans('app.time') }}</th>
                                <th>{{ trans('app.type') }}</th>
                                <th>{{ trans('app.message') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->created_at->format(trans('app.date_time_format')) }}</td>
                                <td>{{ $row->type }}</td>
                                <td>
                                    <a href="#" class="show-log-data">
                                        @if(Lang::has('app.'.$row->message)){{ trans('app.'.$row->message) }}@else{{ $row->message }}@endif
                                        <div class="log-content"><span class="log-content-raw">{{ $row->data }}</span></div>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('partials.pagination', [$data])
    </div>
</div>

<script>
    $(function () {
        $(".show-log-data").click(function(){
            var content = $(this).find('.log-content').html();

            BootstrapDialog.show({
                title: trans_content,
                message: content,
                size: BootstrapDialog.SIZE_WIDE,
                type: BootstrapDialog.TYPE_INFO,
                closable: true
            });
        });
    });
</script>
@endsection
