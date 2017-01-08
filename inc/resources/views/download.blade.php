@include('partials.head_minimal')
<body>
{!! $plugins_body_top or '' !!}
<div class="wrapper">
	<section class="content" style="margin-top: 20px">
		<div class="row">
			<div class="col-lg-3 col-lg-offset-2">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title">{{ $customer->name }}</h3>
					</div>
					<div class="box-body">
						<strong>{{ trans('app.email') }}</strong>
						<p class="text-muted">{!! nl2br($customer->email) !!}</p>
						<strong>{{ trans('app.details') }}</strong>
						<p class="text-muted">{!! nl2br($customer->details) !!}</p>
					</div>
					<div class="box-footer">
						<a class="btn btn-default pull-right" href="{{ url('auth/logout') }}">{{ trans('app.sign_out') }}</a>
					</div>
				</div>
			</div>
			<div class="col-lg-5">
				@foreach($transactions as $transaction)
					@if($transaction->status_id == \App\Models\Transaction::STATUS_APPROVED || $transaction->status_id == \App\Models\Transaction::STATUS_PENDING)
						@foreach($transaction->products as $product)
							@if(!$product->trashed())
								<div class="box box-success box-solid">
									<div class="box-header">
										<h3 class="box-title">{{ $product->name }}</h3>
									</div>
									<div class="box-body">
										@if(hasExpired($transaction))
											<div class="alert alert-warning alert-dismissible">
												<button data-dismiss="alert" class="close" type="button">×</button>
												<i class="icon fa fa-calendar-times-o"></i> {{ trans('app.license_expired') }}
											</div>
										@endif
										<strong>{{ trans('app.license_number') }}</strong>
										<p class="text-muted">{{ $product->pivot->license_number }}</p>
										<strong>{{ trans('app.sale_date_placed') }}</strong>
										<p class="text-muted">{{ $transaction->created_at->format(trans('app.date_format')) }}</p>
										<strong>{{ trans('app.status') }}</strong>
										<p class="text-muted">{{ trans('app.transaction_status_'.$transaction->status_id) }}</p>
										@if($product->description)
										<strong>{{ trans('app.description') }}</strong>
										<p class="text-muted">{!! nl2br($product->description) !!}</p>
										@endif
										@if($transaction->status_id == \App\Models\Transaction::STATUS_APPROVED || ($transaction->status_id == \App\Models\Transaction::STATUS_PENDING && config('global.allow-download-pending')))
										<hr>
										<p>
											@if(!hasExpired($transaction))
												@foreach($product->files as $file)
													<a class="btn btn-success" target="_blank" href="{{ url('download/file/'.$product->pivot->license_number.'/'.$file->id) }}">
														<i class="fa fa-download"></i> {{ $file->file_name }}
													</a>
												@endforeach
											@endif
											@if(config('global.allow-certificate'))
											<a class="btn btn-success" target="_blank" href="{{ url('download/certificate/'.$product->pivot->license_number) }}">
												<i class="fa fa-print"></i> {{ trans('app.license_certificate') }}
											</a>
											@endif
											@if(config('global.allow-invoice'))
											<a class="btn btn-success" target="_blank" href="{{ url('download/invoice/'.$transaction->hash) }}">
												<i class="fa fa-print"></i> {{ trans('app.print_invoice') }}
											</a>
											@endif
										</p>
										@endif
									</div>
								</div>
							@endif
						@endforeach
					@endif
				@endforeach
			</div>
		</div>
	</section>
</div>
@include('partials.js_minimal')
{!! $plugins_body_bottom or '' !!}
</body>
</html>