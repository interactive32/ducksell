@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">{{ $file->file_name }}</h3>
				</div>
				<div class="box-body">
					{!! Form::open() !!}
					@include('partials.form_errors')
					<div class="form-group">
						{!! Form::label('file_name', trans('app.file_name')) !!}
						{!! Form::text('file_name', $file->file_name, ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						{!! Form::label('file_name_internal', trans('app.internal_name')) !!}
						<pre>{!! $file->file_name_internal !!}</pre>
					</div>
					<div class="form-group">
						{!! Form::label('description', trans('app.description')) !!}
						{!! Form::textarea('description', $file->description, array('class' => 'form-control')) !!}
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
