@extends('app')

@section('content')

<section>
    <div class="row">
        <div class="col-lg-3">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('app.files') }}</h3>
                </div>
                <div class="box-body">
                    <p>
                        @foreach($product->files as $file)
                            <p>
                                <strong>{{ $file->file_name }}
                                    <a class="pull-right" href="{{ url('products/download/'.$file->id) }}">
                                        <i class="fa fa-download"></i>
                                    </a>
                                </strong>
                            </p>
                        @endforeach
                    </p>
                </div>
                <div class="box-footer text-right">
                    <a href="{{ url('products/'.$product->id.'/files') }}" class="btn btn-primary" type="button">{{ trans('app.manage_files') }}</a>
                </div>
            </div>
            {!! $plugins_products_edit or '' !!}
        </div>
        <div class="col-lg-6">
            @if($product->trashed())
                @include('partials.record_deleted')
            @endif
            <div class="box box-success box-solid">
                <div class="box-header">
                    <h3 class="box-title">{{ $product->name }}</h3>
                </div>
                <div class="box-body">
                    {!! Form::open() !!}
                    @include('partials.form_errors')
                    <div class="form-group">
                        {!! Form::label('name', trans('app.name')) !!}
                        {!! Form::text('name', $product->name, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('external_id', trans('app.external_id')) !!}
                        {!! Form::text('external_id', $product->external_id, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('price', trans('app.listed_price')) !!}
                        {!! Form::text('price', $product->getPrice() / 100, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', trans('app.description_rich')) !!}
                        {!! Form::textarea('description', $product->description, array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! Form::submit($product->trashed() ? trans('app.restore') : trans('app.submit'), array('class' => 'btn btn-primary pull-right')) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('app.recent_sales') }}</h3>
                    <div class="box-tools pull-right">
                        <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                @foreach($recent_sales as $sale)
                    <hr>
                    <p>
                        <strong>{{ $sale->transaction->created_at->format(trans('app.date_time_format')) }}</strong>
                        <span class="pull-right">
                            @if($sale->transaction->status_id != \App\Models\Transaction::STATUS_APPROVED)
                                <small class="label label-warning">{{ trans('app.transaction_status_'.$sale->transaction->status_id) }}</small>
                            @endif
                            @if(hasExpired($sale->transaction))
                                <small class="label bg-orange">{{ trans('app.expired') }}</small>
                            @endif
                        </span>
                    </p>
                    <div class="text-muted">
                        <div>{{ $sale->transaction->customer->name }}</div>
                        <div>{{ $sale->transaction->customer->email }}</div>
                    </div>
                    <div class="text-right">
                        <p>
                            <a class="btn btn-default" href="{{ url('transactions/'.$sale->transaction->id.'/edit') }}">{{ trans('app.edit') }}</a>
                        </p>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
