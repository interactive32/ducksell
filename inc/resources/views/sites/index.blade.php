@extends('app')

@section('content')

<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">{{ trans('app.sites') }}</h3>
        @include('partials.search')
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-6">
                <div class="action-buttons-top">
                    <a href="{{ url('sites/create') }}" class="btn btn-default">
                        <i class="fa fa-plus"></i> &nbsp; {{ trans('app.add_new') }}
                    </a>
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
                                <th>{{ trans('app.name') }}</th>
                                <th class="action-buttons-right">{{ trans('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $site)
                            <tr>
                                <td>{{ $site->id }}</td>
                                <td><a href="{{ url('sites/'.$site->id.'/edit') }}">{{ $site->name }}</a></td>
                                <td class="action-buttons-right">
                                    <a href="{{ url('sites/'.$site->id.'/edit') }}" class="btn btn-xs">
                                        <i class="fa fa-edit"></i> {{ trans('app.edit') }}
                                    </a>
                                    <a href="{{ url('sites/'.$site->id.'/destroy') }}" class="btn btn-xs" data-method="post" data-confirm="{{ trans('app.are_you_sure') }}">
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
        @include('partials.pagination', [$data])
    </div>
</div>


@endsection
