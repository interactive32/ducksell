@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-3">
			<div class="box box-success">
				<div class="box-header">
					<div class="pull-right box-tools">
						<button class="btn btn-default btn-sm" type="button" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</div>
					<h3 class="box-title">{{ trans('app.about') }}</h3>
				</div>
				<div class="box-body">
					@foreach($customer->metadata as $metavalue)
						<strong>@if(Lang::has('app.'.$metavalue->key)){{ trans('app.'.$metavalue->key) }}@else{{ $metavalue->key }}@endif</strong>
						<p class="text-muted">{{ $metavalue->value }}</p>
					@endforeach
				</div>
			</div>
			{!! $plugins_customers_edit or '' !!}
			@if(!$recent_downloads->isEmpty())
			<div class="box box-success">
				<div class="box-header">
					<div class="pull-right box-tools">
						<button class="btn btn-default btn-sm" type="button" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</div>
					<h3 class="box-title">{{ trans('app.recent_downloads') }}</h3>
				</div>
				<div class="box-body">
					@foreach($recent_downloads as $download)
						<strong class="clearfix">
							{{ $download->file_name }}
							<span class="pull-right">{{ $download->created_at->format(trans('app.date_time_format')) }}</span>
						</strong>
						<p class="text-muted">
							{{ trans('app.ip').' '.$download->ip_address }}
						</p>
					@endforeach
				</div>
			</div>
			@endif
		</div>
		<div class="col-lg-6">
			@if($customer->trashed())
				@include('partials.record_deleted')
			@endif
			<div class="box box-widget widget-user">
				<div class="widget-user-header bg-green-active">
					@if(!$customer->transactions->isEmpty() && config('global.allow-direct-links'))
						<a class="btn btn-success btn-xs pull-right" href="{{ url('/download?q='.$customer->transactions->first()->hash) }}">{{ trans('app.login_as_customer') }}</a>
					@endif
					<h3 class="widget-user-username"> {{ $customer->name }}</h3>
					<h5 class="widget-user-desc"> {{ trans('app.customer') }}</h5>
				</div>
				<div class="widget-user-image">
					<img alt="User Avatar" src="{{ getGravatar($customer->email, 100) }}" class="img-circle">
				</div>
				<div class="box-footer">
					<div class="row">
						<div class="col-lg-12">
							<div class="description-block">
								<p class="description-header">{{ $customer->email }}</p>
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
									{!! Form::text('name', $customer->name, ['class' => 'form-control']) !!}
								</div>
								<div class="form-group">
									{!! Form::label('email', trans('app.user_email')) !!}
									{!! Form::text('email', $customer->email, ['class' => 'form-control']) !!}
								</div>
								<div class="form-group">
									{!! Form::label('details', trans('app.details')) !!}
									{!! Form::textarea('details', $customer->details, array('class' => 'form-control')) !!}
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
						{!! Form::submit($customer->trashed() ? trans('app.restore') : trans('app.submit'), array('class' => 'btn btn-primary pull-right')) !!}
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="box box-success">
				<div class="box-header">
					<div class="pull-right box-tools">
						<button class="btn btn-default btn-sm" type="button" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</div>
					<h3 class="box-title">{{ trans('app.sales').' ('.$customer->transactions->count().')' }}</h3>
				</div>
				<div class="box-body">
					@if(!$customer->transactions->isEmpty())
						<?php $totals = [];?>
						@foreach($customer->transactions as $transaction)
							<?php if(!isset($totals[$transaction->listed_currency])): $totals[$transaction->listed_currency] = 0; endif ?>
							<hr>
							<p>
								<strong>{{ $transaction->created_at->format(trans('app.date_time_format')) }}</strong>
								<span class="pull-right">
									@if($transaction->status_id != \App\Models\Transaction::STATUS_APPROVED)
										<small class="label label-warning">{{ trans('app.transaction_status_'.$transaction->status_id) }}</small>
									@endif
									@if(hasExpired($transaction))
										<small class="label bg-orange">{{ trans('app.expired') }}</small>
									@endif
								</span>
							</p>
							<div class="text-muted" style="margin: 20px 0 30px 0">
								@foreach($transaction->products as $key => $product)
									<?php $totals[$transaction->listed_currency] = $totals[$transaction->listed_currency] + $product->pivot->listed_amount; ?>
									<div>
										<span>{{ $product->name.' ('.$product->pivot->license_number.')' }}</span>
										<span class="pull-right">{!! displayAmount($product->pivot->listed_amount, $transaction->listed_currency) !!}</span>
									</div>
								@endforeach
							</div>
							<p class="text-right">
								<a class="btn btn-default" href="{{ url('/transactions/'.$transaction->id.'/edit') }}">{{ trans('app.edit') }}</a>
							</p>
						@endforeach
						<hr>
						<div>
							@foreach($totals as $currency => $total)
								<p class="text-right">
									<strong style="padding: 8px">{!! trans('app.total').' '.displayAmount($total, $currency) !!}</strong>
								</p>
							@endforeach
						</div>
					@endif
				</div>
				<div class="box-footer">
					<a class="btn btn-primary pull-right" href="{{ url('/transactions/create/'.$customer->id) }}">{{ trans('app.add_new') }}</a>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection
