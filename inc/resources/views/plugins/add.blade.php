@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">{{ trans('app.add_new') }}</h3>
				</div>
				<div class="box-body">
					{!! Form::open(['files' => true]) !!}
					<div class="form-group">
						{!! Form::label('file', trans('app.maximum_size').' '.ini_get('upload_max_filesize') .'/'. ini_get('post_max_size')) !!}
						{!! Form::file('file', null, array('class' => 'form-control')) !!}
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
