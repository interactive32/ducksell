@extends('auth')

@section('content')

<p class="login-box-msg">{{ trans('app.reset_password') }}</p>
@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	<div class="row">
		<div class="col-xs-12">
			<a href="{{ url('/') }}" class="btn btn-default btn-block btn-flat"> &laquo; &nbsp; {{ trans('app.back') }}</a>
		</div>
	</div>
@else
<form role="form" method="POST" action="{{ url('/password/email') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="form-group has-feedback">
		<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ trans('app.email') }}">
	</div>
	<div class="row">
		<div class="col-xs-4">
			<a href="{{ url('/') }}" class="btn btn-default btn-block btn-flat"> &laquo; &nbsp; {{ trans('app.back') }}</a>
		</div>
		<div class="col-xs-8">
			<button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('app.send_password') }}</button>
		</div>
	</div>
</form>
@endif

@endsection
