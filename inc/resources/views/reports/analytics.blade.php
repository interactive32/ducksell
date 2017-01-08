@extends('app')

@section('content')

<section>
    <div class="row">
        <div class="col-lg-6 text-left">
            <h1 style="margin:0 0 20px 0; font-size: 24px;">{{ trans('app.reports') }}</h1>
        </div>
        <div class="col-lg-6 text-right">
            <p class="clearfix">
                <select id="reports-site-select" class="form-control pull-right dashboard-top-select" onchange="location = this.options[this.selectedIndex].value;">
                    <option value="{{ url('reports') }}">{{ trans('app.all_sites') }}</option>
                    @foreach(\App\Models\Site::all() as $site)
                        <option @if(isset($_GET['site_id']) && $_GET['site_id'] == $site->id)selected="selected"@endif value="{{ url('reports?site_id=').$site->id }}">{{ $site->name }}</option>
                    @endforeach
                </select>
            </p>
        </div>
    </div>
</section>

<section id="visits">
    <div class="row">
        <div class="col-lg-4">
            <div class="box box-info">
                <div class="box-header">
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <h3 class="box-title">{{ trans('app.this_month') }}</h3>
                </div>
                <div id="top-pages" class="box-body">
                    <div class="chart tab-pane active" id="chart-this-month"></div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-6 text-center right-border">
                            <span>{{ trans('app.page_views_total') }}<br>{{ $data_this_month->sum('pageviews') }}</span>
                        </div>
                        <div class="col-xs-6 text-center">
                            <span>{{ trans('app.new_users_total') }}<br>{{ $data_this_month->sum('new_users') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <h3 class="box-title">{{ trans('app.this_week') }}</h3>
                </div>
                <div id="top-pages" class="box-body">
                    <div class="chart tab-pane active" id="chart-this-week"></div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-6 text-center right-border">
                            <span>{{ trans('app.page_views_total') }}<br>{{ $data_this_week->sum('pageviews') }}</span>
                        </div>
                        <div class="col-xs-6 text-center">
                            <span>{{ trans('app.new_users_total') }}<br>{{ $data_this_week->sum('new_users') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="box box-default">
                <div class="box-header">
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <h3 class="box-title">{{ trans('app.today') }}</h3>
                </div>
                <div id="top-referrals" class="box-body">
                    <div class="chart tab-pane active" id="chart-today"></div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-6 text-center right-border">
                            <span>{{ trans('app.page_views_total') }}<br>{{ $data_today->sum('pageviews') }}</span>
                        </div>
                        <div class="col-xs-6 text-center">
                            <span>{{ trans('app.new_users_total') }}<br>{{ $data_today->sum('new_users') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="box box-info">
                <div class="box-header">
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <h3 class="box-title">{{ trans('app.last_month') }}</h3>
                </div>
                <div id="top-pages" class="box-body">
                    <div class="chart tab-pane active" id="chart-last-month"></div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-6 text-center right-border">
                            <span>{{ trans('app.page_views_total') }}<br>{{ $data_last_month->sum('pageviews') }}</span>
                        </div>
                        <div class="col-xs-6 text-center">
                            <span>{{ trans('app.new_users_total') }}<br>{{ $data_last_month->sum('new_users') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <h3 class="box-title">{{ trans('app.last_week') }}</h3>
                </div>
                <div id="top-pages" class="box-body">
                    <div class="chart tab-pane active" id="chart-last-week"></div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-6 text-center right-border">
                            <span>{{ trans('app.page_views_total') }}<br>{{ $data_last_week->sum('pageviews') }}</span>
                        </div>
                        <div class="col-xs-6 text-center">
                            <span>{{ trans('app.new_users_total') }}<br>{{ $data_last_week->sum('new_users') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="box box-warning">
                <div class="box-header">
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <h3 class="box-title">{{ trans('app.this_year') }}</h3>
                </div>
                <div id="top-pages" class="box-body">
                    <div class="chart tab-pane active" id="chart-this-year"></div>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-6 text-center right-border">
                            <span>{{ trans('app.page_views_total') }}<br>{{ $data_this_year->sum('pageviews') }}</span>
                        </div>
                        <div class="col-xs-6 text-center">
                            <span>{{ trans('app.new_users_total') }}<br>{{ $data_this_year->sum('new_users') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="sales">
    <div class="row">
        <div class="col-lg-4">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('app.this_month') }}</h3>
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body table-responsive report sales">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>{{ trans('app.product') }}</th>
                                <th class="text-right" style="width:20%">{{ trans('app.sales') }}</th>
                            </tr>
                            <?php $sums = [] ?>
                            @foreach($sales_this_month as $sales)
                                <?php if(!isset($sums[$sales->listed_currency])) $sums[$sales->listed_currency] = 0; ?>
                                <?php $sums[$sales->listed_currency] += $sales->listed_amount ?>
                                <tr>
                                    <td>{{ $sales->product_name.' ('.$sales->transaction_count.')' }}</td>
                                    <td class="text-right">{!! displayAmount($sales->listed_amount, $sales->listed_currency) !!}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-right"><strong>{{ trans('app.total') }}</strong></td>
                                <td class="text-right">
                                    @if(!empty($sums))
                                        @foreach($sums as $key => $sum)
                                           <p><strong>{!! displayAmount($sum, $key) !!}</strong></p>
                                        @endforeach
                                    @else
                                        <p><strong>{!! displayAmount(0) !!}</strong></p>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('app.last_month') }}</h3>
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body table-responsive report sales">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>{{ trans('app.product') }}</th>
                            <th class="text-right" style="width:20%">{{ trans('app.sales') }}</th>
                        </tr>
                        <?php $sums = [] ?>
                        @foreach($sales_last_month as $sales)
                            <?php if(!isset($sums[$sales->listed_currency])) $sums[$sales->listed_currency] = 0; ?>
                            <?php $sums[$sales->listed_currency] += $sales->listed_amount ?>
                            <tr>
                                <td>{{ $sales->product_name.' ('.$sales->transaction_count.')' }}</td>
                                <td class="text-right">{!! displayAmount($sales->listed_amount, $sales->listed_currency) !!}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right"><strong>{{ trans('app.total') }}</strong></td>
                            <td class="text-right">
                                @if(!empty($sums))
                                    @foreach($sums as $key => $sum)
                                        <p><strong>{!! displayAmount($sum, $key) !!}</strong></p>
                                    @endforeach
                                @else
                                    <p><strong>{!! displayAmount(0) !!}</strong></p>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('app.this_week') }}</h3>
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body table-responsive report sales">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>{{ trans('app.product') }}</th>
                            <th class="text-right" style="width:20%">{{ trans('app.sales') }}</th>
                        </tr>
                        <?php $sums = [] ?>
                        @foreach($sales_this_week as $sales)
                            <?php if(!isset($sums[$sales->listed_currency])) $sums[$sales->listed_currency] = 0; ?>
                            <?php $sums[$sales->listed_currency] += $sales->listed_amount ?>
                            <tr>
                                <td>{{ $sales->product_name.' ('.$sales->transaction_count.')' }}</td>
                                <td class="text-right">{!! displayAmount($sales->listed_amount, $sales->listed_currency) !!}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right"><strong>{{ trans('app.total') }}</strong></td>
                            <td class="text-right">
                                @if(!empty($sums))
                                    @foreach($sums as $key => $sum)
                                        <p><strong>{!! displayAmount($sum, $key) !!}</strong></p>
                                    @endforeach
                                @else
                                    <p><strong>{!! displayAmount(0) !!}</strong></p>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('app.last_week') }}</h3>
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body table-responsive report sales">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>{{ trans('app.product') }}</th>
                            <th class="text-right" style="width:20%">{{ trans('app.sales') }}</th>
                        </tr>
                        <?php $sums = [] ?>
                        @foreach($sales_last_week as $sales)
                            <?php if(!isset($sums[$sales->listed_currency])) $sums[$sales->listed_currency] = 0; ?>
                            <?php $sums[$sales->listed_currency] += $sales->listed_amount ?>
                            <tr>
                                <td>{{ $sales->product_name.' ('.$sales->transaction_count.')' }}</td>
                                <td class="text-right">{!! displayAmount($sales->listed_amount, $sales->listed_currency) !!}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right"><strong>{{ trans('app.total') }}</strong></td>
                            <td class="text-right">
                                @if(!empty($sums))
                                    @foreach($sums as $key => $sum)
                                        <p><strong>{!!displayAmount($sum, $key) !!}</strong></p>
                                    @endforeach
                                @else
                                    <p><strong>{!! displayAmount(0) !!}</strong></p>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('app.today') }}</h3>
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body table-responsive report sales">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>{{ trans('app.product') }}</th>
                            <th class="text-right" style="width:20%">{{ trans('app.sales') }}</th>
                        </tr>
                        <?php $sums = [] ?>
                        @foreach($sales_today as $sales)
                            <?php if(!isset($sums[$sales->listed_currency])) $sums[$sales->listed_currency] = 0; ?>
                            <?php $sums[$sales->listed_currency] += $sales->listed_amount ?>
                            <tr>
                                <td>{{ $sales->product_name.' ('.$sales->transaction_count.')' }}</td>
                                <td class="text-right">{!! displayAmount($sales->listed_amount, $sales->listed_currency) !!}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right"><strong>{{ trans('app.total') }}</strong></td>
                            <td class="text-right">
                                @if(!empty($sums))
                                    @foreach($sums as $key => $sum)
                                        <p><strong>{!! displayAmount($sum, $key) !!}</strong></p>
                                    @endforeach
                                @else
                                    <p><strong>{!! displayAmount(0) !!}</strong></p>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('app.all_time') }}</h3>
                    <div class="pull-right box-tools">
                        <button class="btn btn-default btn-sm" type="button" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body table-responsive report sales">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>{{ trans('app.product') }}</th>
                            <th class="text-right" style="width:20%">{{ trans('app.sales') }}</th>
                        </tr>
                        <?php $sums = [] ?>
                        @foreach($sales_all_time as $sales)
                            <?php if(!isset($sums[$sales->listed_currency])) $sums[$sales->listed_currency] = 0; ?>
                            <?php $sums[$sales->listed_currency] += $sales->listed_amount ?>
                            <tr>
                                <td>{{ $sales->product_name.' ('.$sales->transaction_count.')' }}</td>
                                <td class="text-right">{!! displayAmount($sales->listed_amount, $sales->listed_currency) !!}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right"><strong>{{ trans('app.total') }}</strong></td>
                            <td class="text-right">
                                @if(!empty($sums))
                                    @foreach($sums as $key => $sum)
                                        <p><strong>{!! displayAmount($sum, $key) !!}</strong></p>
                                    @endforeach
                                @else
                                    <p><strong>{!! displayAmount(0) !!}</strong></p>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
    var graph_labels = ['{{ trans('app.page_views') }}', '{{ trans('app.new_users') }}'];
    var graph_today = {!! $data_today !!};
    var graph_this_week = {!! $data_this_week !!};
    var graph_last_week = {!! $data_last_week !!};
    var graph_this_month = {!! $data_this_month !!};
    var graph_last_month = {!! $data_last_month !!};
    var graph_this_year = {!! $data_this_year !!};


    $(function () {
        var chart_1 = new Morris.Area({
            element: 'chart-today',
            data: graph_today,
            xkey: 'y',
            ykeys: ['pageviews', 'new_users'],
            labels: graph_labels,
            lineColors: ['#3c8dbc', '#a0d0e0'],
            hideHover: 'auto',
            behaveLikeLine: true
        });

        var chart_2 = new Morris.Area({
            element: 'chart-this-week',
            data: graph_this_week,
            xkey: 'y',
            ykeys: ['pageviews', 'new_users'],
            labels: graph_labels,
            lineColors: ['#3c8dbc', '#a0d0e0'],
            hideHover: 'auto',
            behaveLikeLine: true
        });

        var chart_3 = new Morris.Area({
            element: 'chart-last-week',
            data: graph_last_week,
            xkey: 'y',
            ykeys: ['pageviews', 'new_users'],
            labels: graph_labels,
            lineColors: ['#3c8dbc', '#a0d0e0'],
            hideHover: 'auto',
            behaveLikeLine: true
        });

        var chart_4 = new Morris.Area({
            element: 'chart-this-month',
            data: graph_this_month,
            xkey: 'y',
            ykeys: ['pageviews', 'new_users'],
            labels: graph_labels,
            lineColors: ['#3c8dbc', '#a0d0e0'],
            hideHover: 'auto',
            behaveLikeLine: true
        });

        var chart_5 = new Morris.Area({
            element: 'chart-last-month',
            data: graph_last_month,
            xkey: 'y',
            ykeys: ['pageviews', 'new_users'],
            labels: graph_labels,
            lineColors: ['#3c8dbc', '#a0d0e0'],
            hideHover: 'auto',
            behaveLikeLine: true
        });

        var chart_6 = new Morris.Area({
            element: 'chart-this-year',
            data: graph_this_year,
            xkey: 'y',
            ykeys: ['pageviews', 'new_users'],
            labels: graph_labels,
            lineColors: ['#3c8dbc', '#a0d0e0'],
            hideHover: 'auto',
            behaveLikeLine: true
        });
    });
</script>

@endsection
