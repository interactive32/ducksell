@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
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
					<div class="form-group">
						{!! Form::label('external_id', trans('app.external_id')) !!}
						{!! Form::text('external_id', null, ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						{!! Form::label('price', trans('app.listed_price')) !!}
						{!! Form::text('price', '0.00', ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						{!! Form::label('description', trans('app.description')) !!}
						{!! Form::textarea('description', null, array('class' => 'form-control')) !!}
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
