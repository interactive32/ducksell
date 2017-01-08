@include('partials.head')
<body onload="window.print();">
    <div class="wrapper">
        <section class="invoice">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        {{ config('global.company-name') }}
                        <small class="pull-right">{{ trans('app.date').': '. \Carbon\Carbon::create()->format(trans('app.date_format')) }}</small>
                    </h2>
                </div>
            </div>
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <strong>{{ trans('app.from') }}</strong>
                    <address>
                        {{ config('global.company-name') }}<br>
                        {!!  nl2br(config('global.company-details')) !!}
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    <strong>{{ trans('app.to') }}</strong>
                    <address>
                        {!! nl2br($transaction->customer->details) !!}
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>{{ trans('app.invoice_number').': '. (config('global.invoice-offset') + $transaction->id) }}</b><br>
                    <b>{{ trans('app.sale_date_placed') }}:</b> {{ $transaction->created_at->format(trans('app.date_format')) }}<br>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{ trans('app.quantity') }}</th>
                            <th>{{ trans('app.product') }}</th>
                            <th>{{ trans('app.license_number') }}</th>
                            <th class="text-right">{{ trans('app.amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $total = 0 ?>
                        @foreach($transaction->products as $product)
                            <tr>
                                <td>1</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->pivot->license_number }}</td>
                                <td class="text-right">{!! displayAmount($product->pivot->{$currency.'_amount'}) !!}</td>
                            </tr>
                            <?php $total += $product->pivot->{$currency.'_amount'} ?>
                        @endforeach
                        @if($transaction->metadata->contains('key', 'tax') && $transaction->metadata->where('key', 'tax')->first()->value > 0)
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-right">{!! trans('app.tax').': '.displayAmount($transaction->metadata->where('key', 'tax')->first()->value * 100, $transaction->{$currency.'_currency'}) !!}</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-right"><b>{!! trans('app.total').': '.displayAmount($total + $transaction->metadata->where('key', 'tax')->first()->value * 100, $transaction->{$currency.'_currency'}) !!}</b></td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-right"><b>{!! trans('app.total').': '.displayAmount($total, $transaction->{$currency.'_currency'}) !!}</b></td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row" style="margin-top: 50px">
                <div class="col-xs-12">
                    {!! config('global.invoice-footer') !!}
                </div>
            </div>
        </section>
    </div>
</body>
</html>