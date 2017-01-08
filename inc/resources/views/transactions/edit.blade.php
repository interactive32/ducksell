@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-3">
			<div class="box box-success">
				<div class="box-header">
					<div class="pull-right box-tools">
						<button class="btn btn-default btn-sm" type="button" data-widget="collapse" data-toggle="tooltip">
							<i class="fa fa-minus"></i>
						</button>
					</div>
					<h3 class="box-title">
						{{ trans('app.customer') }}
						@if($transaction->customer->trashed())
							({{ trans('app.deleted') }})
						@endif
					</h3>
				</div>
				<div class="box-body">
					<strong>{{ trans('app.name') }}</strong>
					<p class="text-muted">{!! nl2br($transaction->customer->name) !!}</p>
					<strong>{{ trans('app.email') }}</strong>
					<p class="text-muted">{!! nl2br($transaction->customer->email) !!}</p>
					<strong>{{ trans('app.details') }}</strong>
					<p class="text-muted">{!! nl2br($transaction->customer->details) !!}</p>
				</div>
				<div class="box-footer">
					<a class="btn btn-primary pull-right" href="{{ url('/customers/'.$transaction->customer->id.'/edit') }}">{{ trans('app.edit') }}</a>
				</div>
			</div>
			{!! $plugins_transactions_edit or '' !!}
		</div>
		<div class="col-lg-6">
			<div class="box box-success box-solid">
				<div class="box-header">
					<div class="pull-right box-tools">
						<button class="btn btn-default btn-sm" type="button" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</div>
					<h3 class="box-title">{{ trans('app.transaction_details') }}</h3>
				</div>
				<div class="box-body">
					@if(hasExpired($transaction))
						<div class="alert alert-warning alert-dismissible">
							<button data-dismiss="alert" class="close" type="button">×</button>
							<i class="icon fa fa-calendar-times-o"></i> {{ trans('app.license_expired') }}
						</div>
					@endif
					<strong>{{ trans('app.created_at') }}</strong>
					<p class="text-muted">{{ $transaction->created_at->format(trans('app.date_time_format')) }}</p>
						<strong>
							{{ trans('app.listed_amount') }}
							@if($transaction->status_id == \App\Models\Transaction::STATUS_APPROVED || $transaction->status_id == \App\Models\Transaction::STATUS_PENDING)
								<a target="_blank" class="pull-right" href="{{ url('/download/invoice/'.$transaction->hash.'?currency=listed') }}">{{ trans('app.print_invoice').' '.$transaction->listed_currency }}</a>
							@endif
						</strong>
					<p class="text-muted">{!! displayAmount($transaction->listed_amount, $transaction->listed_currency) !!}</p>
					@if($transaction->listed_amount != $transaction->customer_amount || $transaction->listed_currency != $transaction->customer_currency)
						<strong>
							{{ trans('app.customer_amount') }}
							@if($transaction->status_id == \App\Models\Transaction::STATUS_APPROVED || $transaction->status_id == \App\Models\Transaction::STATUS_PENDING)
								<a target="_blank" class="pull-right" href="{{ url('/download/invoice/'.$transaction->hash.'?currency=customer') }}">{{ trans('app.print_invoice').' '.$transaction->customer_currency }}</a>
							@endif
						</strong>
					<p class="text-muted">{!! displayAmount($transaction->customer_amount, $transaction->customer_currency) !!}</p>
					@endif
					@if($transaction->listed_amount != $transaction->processor_amount || $transaction->listed_currency != $transaction->processor_currency)
						<strong>
							{{ trans('app.processor_amount') }}
							@if($transaction->status_id == \App\Models\Transaction::STATUS_APPROVED || $transaction->status_id == \App\Models\Transaction::STATUS_PENDING)
								<a target="_blank" class="pull-right" href="{{ url('/download/invoice/'.$transaction->hash.'?currency=processor') }}">{{ trans('app.print_invoice').' '.$transaction->processor_currency }}</a>
							@endif
						</strong>
					<p class="text-muted">{!! displayAmount($transaction->processor_amount, $transaction->processor_currency) !!}</p>
					@endif
					<strong>{{ trans('app.transaction_hash') }}</strong>
					<p class="text-muted">{{ $transaction->hash }}</p>
					<strong>{{ trans('app.payment_processors') }}</strong>
					<p class="text-muted">{{ $transaction->payment_processor }}</p>
					<hr>
					<p><strong>{{ trans('app.products') }}</strong></p>
					@foreach($transaction->products as $key => $product)
						<p class="text-muted clearfix">
							<span class="pull-left">{{ $product->name.' ('.$product->pivot->license_number.')' }}</span>
							<span class="pull-right">{!! displayAmount($product->pivot->listed_amount, $transaction->listed_currency) !!}</span>
						</p>
					@endforeach
					<hr>
					@foreach($transaction->metadata as $metavalue)
						<strong>@if(Lang::has('app.'.$metavalue->key)){{ trans('app.'.$metavalue->key) }}@else{{ $metavalue->key }}@endif</strong>
						<p class="text-muted">{{ $metavalue->value }}</p>
					@endforeach
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			@if(!$transaction->updates->isEmpty())
			<div class="box box-success">
				<div class="box-header">
					<div class="pull-right box-tools">
						<button class="btn btn-default btn-sm" type="button" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</div>
					<h3 class="box-title">{{ trans('app.transaction_updates') }}</h3>
				</div>
				<div class="box-body">
				@foreach($transaction->updates as $value)
					<strong>{{ $value->updated_by }}</strong>
					<strong class="pull-right">{{ $value->created_at->format(trans('app.date_time_format')) }}</strong>
					<p class="text-muted">
						@if(Lang::has('app.'.$value->description)){{ trans('app.'.$value->description) }}@else{{ $value->description }}@endif
						@if(Lang::has('app.'.$value->value)){{ trans('app.'.$value->value) }}@else{{ $value->value }}@endif
					</p>
				@endforeach
				</div>
			</div>
			@endif
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">{{ trans('app.transaction_status') }}</h3>
				</div>
				<div class="box-body">
					{!! Form::open() !!}
					@include('partials.form_errors')
					<div class="form-group">
						<?php $status = \App\Models\Transaction::STATUS_APPROVED ?>
						{!! Form::radio('transaction_status', $status, $transaction->status_id == $status ? true : false ) !!}
						{!! Form::label('transaction_status', trans('app.transaction_status_'.$status).($transaction->status_id == $status ? ' ('.trans('app.current').')' : '')) !!}
					</div>
					<div class="form-group">
						<?php $status = \App\Models\Transaction::STATUS_PENDING ?>
						{!! Form::radio('transaction_status', $status, $transaction->status_id == $status ? true : false) !!}
						{!! Form::label('transaction_status', trans('app.transaction_status_'.$status).($transaction->status_id == $status ? ' ('.trans('app.current').')' : '')) !!}
					</div>
					<div class="form-group">
						<?php $status = \App\Models\Transaction::STATUS_REFUNDED ?>
						{!! Form::radio('transaction_status', $status, $transaction->status_id == $status ? true : false) !!}
						{!! Form::label('transaction_status', trans('app.transaction_status_'.$status).($transaction->status_id == $status ? ' ('.trans('app.current').')' : '')) !!}
					</div>
					<div class="form-group">
						<?php $status = \App\Models\Transaction::STATUS_CANCELED ?>
						{!! Form::radio('transaction_status', $status, $transaction->status_id == $status ? true : false) !!}
						{!! Form::label('transaction_status', trans('app.transaction_status_'.$status).($transaction->status_id == $status ? ' ('.trans('app.current').')' : '')) !!}
					</div>
					<div class="form-group">
						<?php $status = \App\Models\Transaction::STATUS_FRAUD ?>
						{!! Form::radio('transaction_status', $status, $transaction->status_id == $status ? true : false) !!}
						{!! Form::label('transaction_status', trans('app.transaction_status_'.$status).($transaction->status_id == $status ? ' ('.trans('app.current').')' : '')) !!}
					</div>
					<p>{{ trans('app.transaction_updates_note') }}</p>
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


