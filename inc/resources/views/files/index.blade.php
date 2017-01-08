@extends('app')

@section('content')

<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">{{ trans('app.files') }}</h3>
        @include('partials.search')
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-6">
                <div class="action-buttons-top">
                    <a href="{{ url('files/add') }}" class="btn btn-default">
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
                                <th>{{ trans('app.file_name') }}</th>
                                <th>{{ trans('app.description') }}</th>
                                <th>{{ trans('app.products') }}</th>
                                <th>{{ trans('app.size') }}</th>
                                <th class="action-buttons-right">{{ trans('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $file)
                            <tr>
                                <td>{{ $file->id }}</td>
                                <td><a href="{{ url('/files/'.$file->id.'/edit') }}">{{ $file->file_name }}</a></td>
                                <td>{!! nl2br($file->description) !!}</td>
                                <td>@foreach($file->products()->get() as $product)<a href="{{ url('products/'.$product->id.'/edit') }}">{{ $product->name }}</a><br>@endforeach</td>
                                <td class="text-right">{{ humanFileSize($file->size) }}</td>
                                <td class="action-buttons-right">
                                    <a href="{{ url('products/download/'.$file->id) }}" class="btn btn-xs">
                                        <i class="fa fa-download"></i> {{ trans('app.download') }}
                                    </a>
                                    <a href="{{ url('files/'.$file->id.'/destroy') }}" class="btn btn-xs" data-method="post" data-confirm="{{ trans('app.are_you_sure') }}">
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
