@extends('auth')

@section('content')

<p class="login-box-msg">{{ trans('app.reset_password') }}</p>
<form role="form" method="POST" action="{{ url('/password/reset') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="token" value="{{ $token }}">
	<div class="form-group">
		<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ trans('app.email') }}">
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="{{ trans('app.password') }}">
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="password_confirmation" placeholder="{{ trans('app.confirm_password') }}">
	</div>
	<div class="row">
		<div class="col-xs-12">
			<button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('app.reset_password') }}</button>
		</div>
	</div>
</form>

@endsection
