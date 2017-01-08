<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <title>{{ trans('app.license_certificate') }}</title>
</head>
<body onload="window.print();">
<p>
<pre>
LICENSE CERTIFICATE
===================

{{ trans('app.name').': '.$transaction->customer->name }}

{{ trans('app.email').': '.$transaction->customer->email }}

{{ trans('app.product').': '.$product->name }}

{{ trans('app.license_number').': '.$license_number }}

{{ trans('app.sale_date_placed').': '.$transaction->created_at->format(trans('app.date_time_format')) }}

</pre>
</p>
</body>
</html>