<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{{ $page_title or " " }}</title>
	<meta name="generator" content="ducksell">
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link href="{{ asset("/AdminLTE/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/AdminLTE/plugins/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset("/AdminLTE/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css" />

	<!--[if lt IE 9]>
	<script src="{{ asset("/AdminLTE/plugins/shivs/html5shiv.js") }}"></script>
	<script src="{{ asset("/AdminLTE/plugins/shivs/respond.min.js") }}"></script>
	<![endif]-->

	<script src="{{ asset ("/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js") }}" type="text/javascript"></script>
	<script src="{{ asset ("/js/common.js?".env('APP_VERSION')) }}" type="text/javascript"></script>

	<link href="{{ asset('/css/app.css?'.env('APP_VERSION')) }}" rel="stylesheet" type="text/css" />

	{!! $plugins_head or '' !!}
</head>
