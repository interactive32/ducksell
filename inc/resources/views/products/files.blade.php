@extends('app')

@section('content')

<section>
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">{{ trans('app.manage_files') }}</h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-6">
							<div class="action-buttons-top">
								<a href="{{ url('products/'.$product_id.'/files/add') }}" class="btn btn-default">
									<i class="fa fa-plus"></i> &nbsp; {{ trans('app.add_new') }}
								</a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table class="table">
									<thead>
									<tr role="row">
										<th><span class="handle"></span>{{ trans('app.id') }}</th>
										<th>{{ trans('app.file_name') }}</th>
										<th>{{ trans('app.description') }}</th>
										<th class="action-buttons-right" style="min-width: 250px">{{ trans('app.actions') }}</th>
									</tr>
									</thead>
									<tbody id="files" class="ui-sortable" data-product-id="{{ $product_id }}">
									@foreach($data as $file)
										<tr id="{{ $file->id }}">
											<td>
												<a href="#" title="{{ trans('app.drag_sort') }}">
													<span class="handle ui-sortable-handle">
														<i class="fa fa-ellipsis-v"></i>
														<i class="fa fa-ellipsis-v"></i>
													</span>
												</a>
												{{ $file->id }}
											</td>
											<td><a href="{{ url('files/'.$file->id.'/edit') }}">{{ $file->file_name }}</a></td>
											<td>{!! nl2br($file->description) !!}</td>
											<td class="action-buttons-right">
												<a href="{{ url('products/'.$product_id.'/files/'.$file->id.'/remove') }}" class="btn btn-xs" data-method="post" data-confirm="{{ trans('app.are_you_sure') }}">
													<i class="fa fa-remove"></i> {{ trans('app.remove_from_product') }}
												</a>
												<a href="{{ url('files/'.$file->id.'/destroy') }}" class="btn btn-xs" data-method="post" data-confirm="{{ trans('app.are_you_sure') }}">
													<i class="fa fa-remove"></i> {{ trans('app.delete') }}
												</a>
											</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	$(function () {
		$( "#files.ui-sortable" ).sortable({
			update: function( event, ui ) {
				var fileOrder = $(this).sortable('toArray').toString();

				var productId = $(this).data('product-id');
				var url = php_baseURL + '/products/' + productId + '/files/sort' + '?order=' + fileOrder;

				startWaiting();

				$.getJSON(url, function(data){
					stopWaiting();
				}).error(function(jqXHR, textStatus) {
					stopWaiting();
				});

			}
		});
	});
</script>

@endsection
