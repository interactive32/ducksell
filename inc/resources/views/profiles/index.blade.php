@extends('app')

@section('content')

<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">{{ trans('app.profiles') }}</h3>
        @include('partials.search')
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-6">
                <div class="action-buttons-top">
                    <a href="{{ url('profiles/create') }}" class="btn btn-default">
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
                                <th>{{ trans('app.role') }}</th>
                                <th>{{ trans('app.user_name') }}</th>
                                <th>{{ trans('app.email') }}</th>
                                <th class="action-buttons-right">{{ trans('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ trans('app.role_'.$user->role) }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="action-buttons-right">
                                    <a href="{{ url('profiles/'.$user->id.'/edit') }}" class="btn btn-xs">
                                        <i class="fa fa-edit"></i> {{ trans('app.edit') }}
                                    </a>
                                    @if($user->id != Auth::user()->id)
                                        <a href="{{ url('profiles/'.$user->id.'/destroy') }}" class="btn btn-xs" data-method="post" data-confirm="{{ trans('app.are_you_sure') }}">
                                            <i class="fa fa-remove"></i> {{ trans('app.delete') }}
                                        </a>
                                    @endif
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
