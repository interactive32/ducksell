@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="box box-success box-solid">
				<div class="box-header">
					<div class="pull-right box-tools">
						<button data-widget="collapse" type="button" class="btn btn-default btn-sm">
							<i class="fa fa-minus"></i>
						</button>
					</div>
					<h3 class="box-title">{{ trans('app.new_profile') }}</h3>
				</div>
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
						<?php $available_roles = [1 => trans('app.role_1'), 2 => trans('app.role_2')] ?>
						{!! Form::label('role', trans('app.role')) !!}
						{!! Form::select('role', $available_roles, null, ['class' => 'form-control']) !!}
					</div>
					<!-- autocomplete bug  -->
					<input style="display:none"><input type="password" style="display:none">
					<div class="form-group">
						{!! Form::label('password', trans('app.password')) !!}
						{!! Form::password('password', ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						{!! Form::label('password_confirmation', trans('app.repeat_password')) !!}
						{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
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
