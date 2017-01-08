@extends('app')

@section('content')

<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">{{ trans('app.transactions') }}</h3>
        @include('partials.search')
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-6">
                <div class="action-buttons-top">
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
                                <th>{{ trans('app.external_sale_id') }}</th>
                                <th>{{ trans('app.date') }}</th>
                                <th>{{ trans('app.status') }}</th>
                                <th>{{ trans('app.payment_processor') }}</th>
                                <th>{{ trans('app.name') }}</th>
                                <th>{{ trans('app.customer') }}</th>
                                <th>{{ trans('app.product') }}</th>
                                <th>{{ trans('app.amount') }}</th>
                                <th class="action-buttons-right">{{ trans('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->external_sale_id }}</td>
                                <td>{{ $transaction->created_at->format(trans('app.date_time_format')) }}</td>
                                <td>{{ trans('app.transaction_status_'.$transaction->status_id) }}</td>
                                <td>{{ $transaction->payment_processor }}</td>
                                <td>{{ $transaction->customer_name }}</td>
                                <td>{{ $transaction->customer_email }}</td>
                                <td>{{ $transaction->product_name.' ('.$transaction->license_number.')' }}</td>
                                <td class="text-right">{!! displayAmount($transaction->listed_amount, $transaction->listed_currency) !!}</td>
                                <td class="action-buttons-right">
                                    <a href="{{ url('transactions/'.$transaction->id.'/edit') }}" class="btn btn-xs">
                                        <i class="fa fa-edit"></i> {{ trans('app.edit') }}
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
