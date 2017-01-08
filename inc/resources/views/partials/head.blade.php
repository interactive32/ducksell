<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{{ $page_title or "DuckSell" }}</title>
	<meta name="generator" content="ducksell">
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link href="{{ asset("/AdminLTE/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/AdminLTE/plugins/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css" />

	<!-- Theme style -->
	<link href="{{ asset("/AdminLTE/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/AdminLTE/dist/css/skins/skin-green.min.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/AdminLTE/plugins/iCheck/square/green.css") }}" rel="stylesheet" type="text/css" />

	<!--[if lt IE 9]>
	<script src="{{ asset("/AdminLTE/plugins/shivs/html5shiv.js") }}"></script>
	<script src="{{ asset("/AdminLTE/plugins/shivs/respond.min.js") }}"></script>
	<![endif]-->

	<script src="{{ asset ("/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js") }}" type="text/javascript"></script>
	<script src="{{ asset ("/js/common.js?".APP_VERSION) }}" type="text/javascript"></script>

	<link href="{{ asset("/AdminLTE/plugins/morris/morris.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/css/bootstrap3-dialog/bootstrap-dialog.min.css") }}" rel="stylesheet" type="text/css" />

	<link href="{{ asset('/css/app.css?'.APP_VERSION) }}" rel="stylesheet" type="text/css" />

	<script>
		var php_baseURL = "{{ url('/') }}";
		var php_heartbeatFreq = 6000;
		var php_currentRoute = "{{ getRouteName() }}";
		var php_csrf_token = "{{ csrf_token() }}";
		var trans_warning = "{{ trans('app.warning') }}";
		var trans_important = "{{ trans('app.important') }}";
		var trans_information = "{{ trans('app.information') }}";
		var trans_content = "{{ trans('app.content') }}";
		var trans_ok = "{{ trans('app.ok') }}";
		var trans_cancel = "{{ trans('app.cancel') }}";
		var waiting_for_response;

		function startWaiting(){
			waiting_for_response = true;
			$('html').addClass('busy');
			return;
		}

		function stopWaiting(){
			waiting_for_response = false;
			$('html').removeClass('busy');
			return;
		}
	</script>

	{!! $plugins_head or '' !!}
</head>