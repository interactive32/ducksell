@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2">
			<div class="box box-success box-solid">
				<div class="box-header">
					<h3 class="box-title">{{ trans('app.add_new') }}</h3>
				</div>
				<div class="box-body">
					{!! Form::open() !!}
					@include('partials.form_errors')
					<div class="form-group">
						{!! Form::label('name', trans('app.name')) !!}
						{!! Form::text('name', null, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="box-footer">
					{!! Form::submit(trans('app.submit'), array('class' => 'btn btn-primary pull-right')) !!}
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</section>

@endsection
