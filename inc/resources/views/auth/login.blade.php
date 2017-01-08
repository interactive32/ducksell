@extends('auth')

@section('content')

<p class="login-box-msg">{{ trans('app.sign_in_to_start') }}</p>
<form method="POST" action="{{ url('/auth/login') }}">
	<div class="row">
		<div class="col-xs-12">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group has-feedback">
				<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ trans('app.email') }}">
			</div>
			<div class="form-group has-feedback">
				<input type="password" class="form-control" name="password" placeholder="{{ trans('app.password') }}">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="checkbox icheck" style="margin:0 0 15px 20px">
				<input type="checkbox" name="remember" id="remember">
				<label for="remember">{{ trans('app.remember_me') }}</label>
			</div>
		</div>
	</div>
	@if(config('global.recaptcha-sitekey') && config('global.recaptcha-secretkey') && env('APP_ENV') == 'production')
		<div class="g-recaptcha" data-sitekey="{{ config('global.recaptcha-sitekey') }}"></div>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	@endif
	<div class="row">
		<div class="col-xs-12">
			<button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('app.sign_in') }}</button>
		</div>
	</div>
</form>
<hr/>
<div class="text-center">
	<a href="{{ url('/password/email') }}">{{ trans('app.forgot_password') }}</a><br>
</div>

@endsection
