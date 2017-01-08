@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="box box-widget widget-user">
				<div class="widget-user-header bg-green-active">
					<h3 class="widget-user-username"> {{ trans('app.new_customer') }}</h3>
					<h5 class="widget-user-desc"> </h5>
				</div>
				<div class="widget-user-image clearfix">
					<img alt="User Avatar" src="{{ getGravatar('example@example.com', 100) }}" class="img-circle">
				</div>
				<div class="box-footer">
					<div class="row">
						<div class="col-lg-12">
							<div class="description-block">
								<p class="description-header"> &nbsp; </p>
							</div>
						</div>
					</div>
				</div>
				<div class="box no-shadow no-border">
					<div class="box-body">
						{!! Form::open() !!}
						@include('partials.form_errors')
						<div class="form-group">
							{!! Form::label('name', trans('app.user_name')) !!}
							{!! Form::text('name', null, ['class' => 'form-control']) !!}
						</div>
						<div class="form-group">
							{!! Form::label('email', trans('app.user_email')) !!}
							{!! Form::text('email', null, ['class' => 'form-control']) !!}
						</div>
						<div class="form-group">
							{!! Form::label('details', trans('app.details')) !!}
							{!! Form::textarea('details', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="box-footer">
						{!! Form::submit(trans('app.submit'), array('class' => 'btn btn-primary pull-right')) !!}
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection
