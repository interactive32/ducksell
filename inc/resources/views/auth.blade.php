@include('partials.head_minimal')
<body class="auth">
{!! $plugins_body_top or '' !!}
<div class="wrapper">
	<section class="content">
		<div class="login-box">
			<div class="login-logo">
				&nbsp;
			</div>
			<div class="login-box-body">
				@include('partials.form_errors')
				@yield('content')
			</div>
		</div>
	</section>
</div>
@include('partials.js_minimal')
{!! $plugins_body_bottom or '' !!}
</body>
</html>