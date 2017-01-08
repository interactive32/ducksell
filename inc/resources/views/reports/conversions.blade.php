@extends('app')

@section('content')

<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">{{ trans('app.conversions') }}</h3>
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
                                <th>{{ trans('app.date') }}</th>
                                <th>{{ trans('app.referral') }}</th>
                                <th>{{ trans('app.landing_page') }}</th>
                                <th>{{ trans('app.customer') }}</th>
                                <th>{{ trans('app.transaction_status') }}</th>
                                <th>{{ trans('app.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $row)
                            <tr>
                                <td>{{ $row->created_at->format(trans('app.date_time_format')) }}</td>
                                <td>{{ $row->referral }}@if($row->referral) <a target="_blank" href="{{ url($row->referral) }}"><i class="fa fa-external-link"></i></a>@endif</td>
                                <td>{{ $row->landing_page }}@if($row->landing_page) <a target="_blank" href="{{ url($row->landing_page) }}"><i class="fa fa-external-link"></i></a>@endif</td>
                                <td><a href="{{ url('customers/'.$row->user_id.'/edit') }}">{{ $row->customer }}</a></td>
                                <td><a href="{{ url('transactions/'.$row->transaction_id.'/edit') }}">{{ trans('app.transaction_status_'.$row->status_id) }}</a></td>
                                <td class="text-right">{!! displayAmount($row->listed_amount, $row->listed_currency) !!}</td>
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
