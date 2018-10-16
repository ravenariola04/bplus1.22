@extends('layouts.app')
@include('includes.customer-navbar')

@section('content')
	<br><br>
	<div class="container mtb">
  		<h2>View All Reservations <a href="{{ route('customerAddHomeServiceReservation') }}" class="btn btn-md btn-primary">Add New Home Service Reservation</a> <a href="{{ route('customerAddOnSpaReservation') }}" class="btn btn-md btn-primary">Add New On Salon Reservation</a></h2> 

  		<div class="panel panel-default">
    		<div class="panel-body" style="background:#999999c7;overflow-x:auto;">
    			<div class="col-lg-12">
    			<table class="table table-stripped" id="customerViewAllReservations-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Customer</th>
							<th>Date</th>
							<th>Reservation Time</th>
							<th>Address</th>
							<th>Hairstylist</th>
							<th>Services</th>
							<th>Service Type</th>
							<th>Status</th>
							<th>Date Added</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($reservations as $reservation)
							<tr>
								<td>{{$reservation->id}}</td>
								<td>{{$reservation->customer_firstname}} {{$reservation->customer_lastname}}</td>
								<td>{{$reservation->reservation_date}}</td>
								<td>{{$reservation->reservation_time}}</td>
								<td>{{$reservation->address}}</td>
								<td>
									@foreach($employeeReservations as $employeeReservation)
										@if($employeeReservation->reservation_id == $reservation->id)
											{{$employeeReservation->firstname}} {{$employeeReservation->lastname}}
											({{$employeeReservation->expertise}}),
										@endif
									@endforeach
								</td>
								<td>
									@foreach($getServices as $getService)
										@if($reservation->id == $getService->reservation_id)
											{{$getService->name}} (&#8369;{{$getService->price}}),
										@endif
									@endforeach
								</td>
								<td>{{$reservation->type}}</td>
								<td>
									@if($reservation->status == 'Approved')
										{{$reservation->status}} (by {{$reservation->processedByFirstname}} 
										{{$reservation->processedByLastname}})
									@elseif($reservation->status == 'Cancelled')
										{{$reservation->status}} (by {{$reservation->processedByFirstname}} 
										{{$reservation->processedByLastname}})
									@else
										{{$reservation->status}}
									@endif
								</td>
								@php
									$date_added = date("M jS, Y h:i a", strtotime($reservation->date_added)); 
								@endphp
								<td>{{$date_added}}</td>
								<td>
									@if($reservation->status == 'Pending')
										<a href="{{ route('customerCancelReservation', ['reservation_id' => $reservation->id])}}" class="btn btn-md btn-danger" onclick="return confirm('Cancel reservation?');">Cancel</a>
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