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
					<div class="nav-tabs-custom no-shadow">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#tab_1">{{ trans('app.upload_file') }}</a></li>
							<li><a data-toggle="tab" href="#tab_2">{{ trans('app.existing_files') }}</a></li>
							<li><a data-toggle="tab" href="#tab_3">{{ trans('app.browse_server') }}</a></li>
						</ul>
						<div class="tab-content">
							@include('partials.form_errors')
							<br>
							<div id="tab_1" class="tab-pane active">
								<div class="form-group">
									{!! Form::label('file', trans('app.maximum_size').' '.ini_get('upload_max_filesize') .'/'. ini_get('post_max_size')) !!}
									{!! Form::file('file', null, array('class' => 'form-control')) !!}
								</div>
							</div>
							<div id="tab_2" class="tab-pane">
								<div class="form-group">
									{!! Form::label('reuse_file', trans('app.file')) !!}
									{!! Form::select('reuse_file', ['' => ''] + $reuse_files, null, ['class' => 'form-control']) !!}
								</div>
							</div>
							<div id="tab_3" class="tab-pane">
								<div class="form-group">
									{!! Form::label('existing_file', $tmp_path) !!}
									{!! Form::select('existing_file', ['' => ''] + $existing_files, null, ['class' => 'form-control']) !!}
								</div>
							</div>
						</div>
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
