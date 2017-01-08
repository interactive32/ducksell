@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1">
			@if($site->trashed())
				@include('partials.record_deleted')
			@endif
			<div class="box box-success box-solid">
				<div class="box-header">
					<h3 class="box-title">{{ $site->name}}</h3>
				</div>
				<div class="box-body">
					{!! Form::open() !!}
					@include('partials.form_errors')
					<div class="form-group">
						{!! Form::label('name', trans('app.name')) !!}
						{!! Form::text('name', $site->name, ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						{!! Form::label('name', trans('app.tracking_code')) !!}
						<pre>{{ $tracking_code }}</pre>
					</div>
				</div>
				<div class="box-footer">
					{!! Form::submit($site->trashed() ? trans('app.restore') : trans('app.submit'), array('class' => 'btn btn-primary pull-right')) !!}
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</section>

@endsection


