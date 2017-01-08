@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="box box-widget widget-user">
				<div class="widget-user-header bg-green-active">
					<h3 class="widget-user-username"> {{ $profile->name }}</h3>
					<h5 class="widget-user-desc"> {{ trans('app.role_'.$profile->role) }}</h5>
				</div>
				<div class="widget-user-image">
					<img alt="User Avatar" src="{{ getGravatar($profile->email, 100) }}" class="img-circle">
				</div>
				<div class="box-footer">
					<div class="row">
						<div class="col-lg-12">
							<div class="description-block">
								<p class="description-header">{{ $profile->email }}</p>
							</div>
						</div>
					</div>
				</div>
				<div class="box no-shadow no-border">
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
									{!! Form::text('name', $profile->name, ['class' => 'form-control']) !!}
								</div>
								<div class="form-group">
									{!! Form::label('email', trans('app.user_email')) !!}
									{!! Form::text('email', $profile->email, ['class' => 'form-control']) !!}
								</div>
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
	</div>
</section>

@endsection
