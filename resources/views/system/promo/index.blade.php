@extends('layouts.app')
@include('includes.admin-navbar')

@section('content')
	<br><br>
	<div class="container mtb">
  		<h2>View All Promos <a href="{{ route('promo.create') }}" class="btn btn-md btn-primary">Add New Promo</a></h2> 
  		<div class="panel panel-default">
    		<div class="panel-body" style="background:#999999c7;overflow-x:auto;">
    			<div class="col-lg-12">
    			<table class="table table-stripped" id="viewAllWalkin-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Promo Name</th>
							<!-- <th>Lastname</th> -->
							<th>Price</th>
							<!-- <th>Hair stylist</th> -->
							<!-- <th>Walk-in Date</th> -->
							<!-- <th>Walk-in Time</th> -->
							<!-- <th>Status</th> -->
							<!-- <th>Action</th> -->
						</tr>
					</thead>
					<tbody>
						@foreach($promos as $promo)
						<tr>
							<td>{{$promo->id}}</td>
							<td>{{$promo->name}}</td>
							<td>
								@foreach($getServices as $getService)
									@if($getService->psid == $getService->sid)
										(&#8369;{{$promo->price}}) 

									@endif
								@endforeach
							</td>
							
							
						</tr>
						@endforeach
					</tbody>
    			</table>

				</div>
    		</div>
  		</div>
	</div>
@stop