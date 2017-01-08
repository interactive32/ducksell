@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="box box-success box-solid">
				<div class="box-header">
					<h3 class="box-title">{{ trans('app.transaction').' - '.trans('app.add_new') }}</h3>
				</div>
				<div class="box-body">
					{!! Form::open() !!}
					@include('partials.form_errors')
					<div class="form-group">
						{!! Form::label('customer', trans('app.customer')) !!}
						{!! Form::text('customer', $customer->email, ['class' => 'form-control', 'disabled']) !!}
					</div>
					<div class="form-group">
						{!! Form::label('product_id', trans('app.products')) !!}
						{!! Form::select('product_id', $products, null, ['class' => 'form-control', 'required']) !!}
					</div>
					<div class="form-group">
						{!! Form::label('listed_amount', trans('app.price')) !!} <a href="#" class="pull-right toggle-advanced">{{ trans('app.advanced') }}</a>
						{!! Form::text('listed_amount', 0, ['class' => 'form-control', 'required']) !!}
					</div>
					<div class="advanced hidden">
						<div class="form-group">
							{!! Form::label('listed_currency', trans('app.currency')) !!}
							{!! Form::text('listed_currency', config('global.default-manual-currency'), ['class' => 'form-control', 'required']) !!}
						</div>
						<div class="form-group">
							{!! Form::label('customer_amount', trans('app.customer_amount')) !!}
							{!! Form::text('customer_amount', null, ['class' => 'form-control']) !!}
						</div>
						<div class="form-group">
							{!! Form::label('customer_currency', trans('app.customer_currency')) !!}
							{!! Form::text('customer_currency', null, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::checkbox('send_email_to_customer', 1, ['class' => 'form-control', 'required']) !!}
						{!! Form::label('send_email_to_customer', trans('app.send_email_to_customer')) !!}
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

<script>
	$(function () {
		$(".toggle-advanced").click(function(){
			$(".advanced").toggleClass("hidden");
		});
	});
</script>

@endsection
