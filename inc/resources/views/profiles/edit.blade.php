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
					<h3 class="box-title">{{ $user->name .' ('.trans('app.role_'.$user->role).')' }}</h3>
				</div>
				<div class="box-body">
					{!! Form::open() !!}
					<div class="nav-tabs-custom no-shadow">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#profile-details">{{ trans('app.profile_details') }}</a></li>
							<li class=""><a data-toggle="tab" href="#password">{{ trans('app.change_password') }}</a></li>
						</ul>
					</div>
					<div class="tab-content">
						@include('partials.form_errors')
						<div class="tab-pane active" id="profile-details">
							<div class="form-group">
								{!! Form::label('name', trans('app.user_name')) !!}
								{!! Form::text('name', $user->name, ['class' => 'form-control']) !!}
							</div>
							<div class="form-group">
								{!! Form::label('email', trans('app.user_email')) !!}
								{!! Form::text('email', $user->email, ['class' => 'form-control']) !!}
							</div>
							@if(isAdmin())
								<div class="form-group">
									<?php $available_roles = [1 => trans('app.role_1'), 2 => trans('app.role_2')] ?>
									{!! Form::label('role', trans('app.role')) !!}
									{!! Form::select('role', $available_roles, $user->role, ['class' => 'form-control']) !!}
								</div>
							@endif
						</div>
						<div class="tab-pane" id="password">
							<!-- autocomplete bug  -->
							<input style="display:none"><input type="password" style="display:none">
							<div class="form-group">
								{!! Form::label('password', trans('app.new_password')) !!}
								{!! Form::password('password', ['class' => 'form-control']) !!}
							</div>
							<div class="form-group">
								{!! Form::label('password_confirmation', trans('app.repeat_password')) !!}
								{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
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
