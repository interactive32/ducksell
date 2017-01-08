@include('partials.head_minimal')
<body>
<div class="wrapper">
	<section class="content">
		<div class="error-page text-center">
			<h1 class="text-yellow">503</h1>
			<h3><i class="fa fa-warning text-yellow"></i> {{ trans('app.information') }}</h3>
			<p>
				{{ trans('app.maintenance_mode') }}
			</p>
		</div>
	</section>
</div>
@include('partials.js_minimal')
</body>
</html>