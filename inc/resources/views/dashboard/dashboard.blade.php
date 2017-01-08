@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-6 text-left">
			<h1 style="margin:0 0 20px 0; font-size: 24px;">{{ trans('app.dashboard') }}</h1>
		</div>
		<div class="col-lg-6 text-right">
			<p class="clearfix">
				<select id="dashboard-period-select" class="form-control pull-right dashboard-top-select">
					<option value="1">{{ trans('app.minute_ago_1') }}</option>
					<option value="3" selected="selected">{{ trans('app.minute_ago_3') }}</option>
					<option value="5">{{ trans('app.minute_ago_5') }}</option>
					<option value="15">{{ trans('app.minute_ago_15') }}s</option>
					<option value="30">{{ trans('app.minute_ago_30') }}</option>
					<option value="60">{{ trans('app.minute_ago_60') }}</option>
					<option value="120">{{ trans('app.minute_ago_120') }}</option>
					<option value="360">{{ trans('app.minute_ago_360') }}</option>
					<option value="720">{{ trans('app.minute_ago_720') }}</option>
					<option value="1440">{{ trans('app.minute_ago_1440') }}</option>
					<option value="2880">{{ trans('app.minute_ago_2880') }}</option>
					<option value="4320">{{ trans('app.minute_ago_4320') }}</option>
					<option value="7200">{{ trans('app.minute_ago_7200') }}</option>
					<option value="10080">{{ trans('app.minute_ago_10080') }}</option>
					<option value="20160">{{ trans('app.minute_ago_20160') }}</option>
				</select>
				<select id="dashboard-site-select" class="form-control pull-right dashboard-top-select">
					<option value="0" selected="selected">{{ trans('app.all_sites') }}</option>
					@foreach(\App\Models\Site::all() as $site)
						<option value="{{ $site->id }}">{{ $site->name }}</option>
					@endforeach
				</select>
			</p>
		</div>
	</div>
</section>

<section id="dashboard">
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="small-box bg-green">
				<div class="inner">
					<h3 id="total-online">&nbsp;</h3>
					<p>{{ trans('app.active_users') }}</p>
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
				<span class="small-box-footer" href="#">
					{{ trans('app.desktop') }} <span id="browser-desktop"></span><sup>%</sup>  {{ trans('app.mobile') }} <span id="browser-mobile"></span><sup>%</sup>
				</span>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3><span id="today-sales-count">&nbsp;</span></h3>
					<p>{{ trans('app.today_sales_count') }}</p>
				</div>
				<div class="icon">
					<i class="fa fa-shopping-basket"></i>
				</div>
				<span class="small-box-footer">
					{{ trans('app.today_sales_amount') }} <span id="today-sales-amount">&nbsp;</span>
				</span>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3><span id="new-users-rate">&nbsp;</span><sup>%</sup></h3>
					<p>{{ trans('app.new_users') }}</p>
				</div>
				<div class="icon">
					<i class="fa fa-user-plus"></i>
				</div>
				<span class="small-box-footer" href="#">
					{{ trans('app.new_users_total') }} <span id="new-users">&nbsp;</span>
				</span>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="small-box bg-red">
				<div class="inner">
					<h3><span id="bounce-rate">&nbsp;</span><sup>%</sup></h3>
					<p>{{ trans('app.bounce_rate') }}</p>
				</div>
				<div class="icon">
					<i class="fa fa-reply"></i>
				</div>
				<span class="small-box-footer" href="#">
					{{ trans('app.bounced') }} <span id="bounced">&nbsp;</span>
				</span>
			</div>
		</div>
	</div>
	<div class="row">
		<section class="col-lg-6">
			<div class="box">
				<div class="box-header">
					<div class="pull-right box-tools">
						<button class="btn btn-default btn-sm" type="button" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</div>
					<h3 class="box-title">{{ trans('app.top_pages') }}</h3>
				</div>
				<div id="top-pages" class="box-body">
				</div>
			</div>
			<div class="box">
				<div class="box-header">
					<div class="pull-right box-tools">
						<button class="btn btn-default btn-sm" type="button" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</div>
					<h3 class="box-title">{{ trans('app.top_referrals') }}</h3>
				</div>
				<div id="top-referrals" class="box-body">
				</div>
			</div>
		</section>
		<section class="col-lg-6">
			<div class="box box-solid bg-light-blue-gradient">
				<div class="box-header" style="cursor: move;">
					<div class="pull-right box-tools">
						<button class="btn btn-primary btn-sm" type="button" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</div>
					<h3 class="box-title">{{ trans('app.visitors') }}</h3>
				</div>
				<div class="box-body no-padding">
					<div class="row">
						<div class="col-md-12">
							<div class="box-body">
								<div id="world-map" style="width: 100%; height: 417px"></div>
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="box">
				<div class="box-header">
					<div class="pull-right box-tools">
						<button class="btn btn-default btn-sm" type="button" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</div>
					<h3 class="box-title">{{ trans('app.top_countries') }}</h3>
				</div>
				<div id="top-countries" class="box-body">
				</div>
			</div>
		</section>
	</div>
</section>

<script>
	$(function () {

		var mapData;
		var time_ago_sec = $('#dashboard-period-select').val();
		var site_select = $('#dashboard-site-select').val();
		var long_period = false;

		function doHeartbeat() {

			if (waiting_for_response == true || long_period) return;

			var url = php_baseURL + '/getdata?t=' + time_ago_sec + '&s=' + site_select;

			startWaiting();

			$.getJSON(url, function (data) {

				mapData = data.regions;
				var markerData = data.locations;
				var top_pages_view = data.top_pages_view;
				var top_referrals_view = data.top_referrals_view;
				var top_countries_view = data.top_countries_view;
				var total_online = data.total_online;
				var browser_desktop = data.browser_desktop;
				var browser_mobile = data.browser_mobile;
				var bounced = data.bounced;
				var bounce_rate = data.bounce_rate;
				var new_users = data.new_users;
				var new_users_rate = data.new_users_rate;
				var today_sales_amount = data.today_sales_amount;
				var today_sales_count = data.today_sales_count;

				var mapObj = $('#world-map').vectorMap('get', 'mapObject');

				$('#top-pages').html(top_pages_view);
				$('#top-referrals').html(top_referrals_view);
				$('#top-countries').html(top_countries_view);
				$('#total-online').html(total_online);
				$('#browser-desktop').html(browser_desktop);
				$('#browser-mobile').html(browser_mobile);
				$('#bounced').html(bounced);
				$('#bounce-rate').html(bounce_rate);
				$('#new-users').html(new_users);
				$('#new-users-rate').html(new_users_rate);
				$('#today-sales-amount').html(today_sales_amount);
				$('#today-sales-count').html(today_sales_count);

				// update regioins
				mapObj.series.regions[0].clear();
				mapObj.series.regions[0].setValues(mapData);

				// update markers
				var mapMarkers = [];
				var mapMarkersValues = [];
				mapMarkers.length = 0;
				mapMarkersValues.length = 0;
				for (var i = 0, l = markerData.length; i < l; i++) {
					mapMarkers.push({name: markerData[i].name, latLng: markerData[i].latLng});
					mapMarkersValues.push(markerData[i].total);
				}
				mapObj.removeAllMarkers();
				mapObj.addMarkers(mapMarkers, []);
				mapObj.series.markers[0].setValues(mapMarkersValues);

				stopWaiting();

				// stop fetching for really long periods
				if (time_ago_sec > 60) {
					long_period = true;
				}

			}).error(function (jqXHR, textStatus) {
				console.log(textStatus);
				stopWaiting();
			});

		}

		function heartbeatLoop() {

			if (php_heartbeatFreq > 0) {
				setTimeout(function () {
					doHeartbeat();
					heartbeatLoop();
				}, php_heartbeatFreq);
			}
		}

		$('#dashboard-period-select').on('change', function () {
			time_ago_sec = $(this).val();
			long_period = false; // reset so it runs at least once
			stopWaiting();
			doHeartbeat();
		});

		$('#dashboard-site-select').on('change', function () {
			site_select = $(this).val();
			long_period = false; // reset so it runs at least once
			stopWaiting();
			doHeartbeat();
		});

		$('#world-map').vectorMap({
			backgroundColor: "transparent",
			map: 'world_mill_en',
			series: {
				regions: [{
					values: [],
					scale: ['#477fb7', '#08519c'], //scale: ['#C8EEFF', '#0071A4'],
					normalizeFunction: 'polynomial',
					min: 1,
					max: 10,
				}],
				markers: [{
					attribute: 'r',
					scale: [5, 20],
					values: [],
					min: 1,
					max: 10
				}],
			},
			markerStyle: {
				initial: {
					fill: '#f39c12',
					stroke: '#383f47'
				}
			},
			markers: [],

			onRegionLabelShow: function (e, el, code) {
				if (!mapData || mapData[code] == null) {
					return true;
				}
				el.html(el.html() + ' <br>Active users: ' + mapData[code]);
			}
		});

		// start heartbeat loop
		if (php_currentRoute == 'dashboard@dashboard' && php_heartbeatFreq > 0) {
			doHeartbeat();
			heartbeatLoop();
		}
	});

</script>

@endsection
