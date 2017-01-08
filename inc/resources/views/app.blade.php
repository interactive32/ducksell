@include('partials.head')
<body class="skin-green">
{!! $plugins_body_top or '' !!}
<div class="wrapper">
	@include('partials.header')
	@include('partials.sidebar')
	<div class="content-wrapper">
		@include('partials.flash_notifications')
		@if(isset($back_button))
		<section class="content-header clearfix">
			<div class="back-button">
				<a href="{{ url($back_button['route']) }}"><i class="icon fa fa-angle-double-left"></i> {{ $back_button['title'] }}</a>
			</div>
		</section>
		@endif
		<section class="content">
			@yield('content')
		</section>
	</div>
	@include('partials.footer')
</div>
@include('partials.js')
{!! $plugins_body_bottom or '' !!}
</body>
</html>