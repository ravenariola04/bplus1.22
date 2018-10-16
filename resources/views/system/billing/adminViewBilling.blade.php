@extends('layouts.app')
@include('includes.admin-navbar')

@section('content')
	<br><br>
	<div class="container mtb">
  		<h2>View All Billing</h2> 

  		<div class="panel panel-default">
    		<div class="panel-body" style="background:#999999c7;overflow-x:auto;">
    			<div class="col-lg-12">
    			<table class="table table-stripped" id="adminViewAllBilling-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Customer</th>
							<th>Stylist/s</th>
							<th>Services</th>
							<th>Cashier</th>
							<th>Total</th>
							<th>Status</th>
							<th>Date/Time Of Reservation</th>
							<th>Date/Time Reserved</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($billings as $billing)
							@php
								$reservation_date = date("M jS, Y", strtotime($billing->reservation_date)); 
								$created_at = date("M jS, Y", strtotime($billing->created_at));
							@endphp
							<tr>
								<td>{{$billing->id}}</td>
								<td>{{$billing->customer_firstname}} {{$billing->customer_lastname}}</td>
								<td>
									@foreach($billingEmployees as $billingEmployee)
										@if($billingEmployee->billing_id == $billing->id)
											{{$billingEmployee->firstname}} {{$billingEmployee->lastname}},
										@endif
									@endforeach
								</td>
								<td>
									@foreach($getServices as $getService)
										@if($getService->billing_id == $billing->id)
											{{$getService->service_name}} (&#8369;{{$getService->service_price}}),
										@endif
									@endforeach
								</td>
								<td>{{$billing->cashier_firstname}} {{$billing->cashier_lastname}}</td>
								<td>
									@foreach($getServiceTotal as $getServiceTotal1)
										@if($getServiceTotal1->billing_id == $billing->id)
											&#8369;{{$getServiceTotal1->total}}
										@endif
									@endforeach
								</td>
								<td>{{$billing->status}}</td>
								<td>{{$reservation_date}} <br> {{$billing->reservation_time}}</td>
								<td>{{$created_at}}</td>
								<td>
									@if($billing->status != 'Paid')
										<a href="{{ route('adminPayBilling', ['billing_id' => $billing->id]) }}" class="btn btn-md btn-primary">Pay</a>
										<a href="#" class="btn btn-md btn-danger">Cancel</a>
									@endif
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