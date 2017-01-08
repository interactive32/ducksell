@extends('app')

@section('content')

<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">{{ trans('app.plugins') }}</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-6">
                <div class="action-buttons-top">
                    <a href="{{ url('plugin/add') }}" class="btn btn-default">
                        <i class="fa fa-plus"></i> &nbsp; {{ trans('app.add_new') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr role="row">
                            <th>{{ trans('app.name') }}</th>
                            <th>{{ trans('app.author') }}</th>
                            <th>{{ trans('app.version') }}</th>
                            <th>{{ trans('app.description') }}</th>
                            <th class="action-buttons-right">{{ trans('app.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $plugin)
                            <tr class="@if(!$plugin->enabled){{ 'text-muted' }}@endif">
                                <td>{{ $plugin->name }}</td>
                                <td>{{ $plugin->author }}</td>
                                <td>{{ $plugin->version }}</td>
                                <td style="max-width: 400px">{{ $plugin->description }}</td>
                                <td class="action-buttons-right">
                                    <a href="{{ url('plugin/toggle/'.$plugin->key) }}" class="btn btn-xs" data-method="post" }}>
                                        @if($plugin->enabled)
                                            <i class="fa fa-toggle-on"></i> {{ trans('app.disable') }}
                                        @else
                                            <i class="fa fa-toggle-off"></i> {{ trans('app.enable') }}
                                        @endif
                                    </a>
                                    <a href="{{ url('plugin/remove/'.$plugin->key) }}" class="btn btn-xs" data-method="post" data-confirm="{{ trans('app.are_you_sure') }}">
                                        <i class="fa fa-remove"></i> {{ trans('app.delete') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
