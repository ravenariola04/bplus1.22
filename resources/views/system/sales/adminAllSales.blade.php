@extends('layouts.app')
@include('includes.admin-navbar')

@section('content')
	<br><br>
	<div class="container mtb">
		<div class="row">

  		<div class="col-lg-6">
	  		<h2>Total Walkin Sales</h2> 

	  		<div class="panel panel-default">
	    		<div class="panel-body" style="background:#999999c7;overflow-x:auto;">
	    			<div class="col-lg-12">
	    			<table class="table table-stripped" id="adminViewAllPayments-table">
						<thead>
							<tr>
								<th>Customer</th>
								<th> Amount </th>
								<th>Amount Paid</th>
								<th>Change</th>
								<th>Date </th>
								<!-- <th>Total Sales </th> -->
							</tr>
						</thead>
						<tbody>
							@foreach($walkinpay as $walkinpays)
								<tr>
									<td>{{$walkinpays->customer_firstname}} {{$walkinpays->customer_lastname}}</td>
									<td>&#8369;{{$walkinpays->total_amount}}</td>
									<td>&#8369;{{$walkinpays->amount_paid}}</td>
									<td>&#8369;{{$walkinpays->change}}</td>
									
									<td>{{$walkinpays->created_at}}</td>

								</tr>
							@endforeach
								<tr>
									<th>Total Sale</th>
									<td>{{$checkDate2->totals}}</td>

								</tr>
						</tbody>
	    			</table>

					</div>
	    		</div>
	  		</div>
  		</div>

  		<div class="col-lg-6">
	  		<h2>Total Reservation Sales</h2> 

	  		<div class="panel panel-default">
	    		<div class="panel-body" style="background:#999999c7;overflow-x:auto;">
	    			<div class="col-lg-12">
	    			<table class="table table-stripped" id="adminViewAllPayments-table">
						<thead>
							<tr>
								<th> Amount </th>
								<th>Amount Paid</th>
								<th>Change</th>
								<th>Date </th>
							</tr>
						</thead>
						<tbody>
							@foreach($payment as $payments)
								<tr>
									<td>&#8369;{{$payments->total_amount}}</td>
									<td>&#8369;{{$payments->amount_paid}}</td>
									<td>&#8369;{{$payments->change}}</td>
									
									<td>{{$payments->created_at}}</td>
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
@stop