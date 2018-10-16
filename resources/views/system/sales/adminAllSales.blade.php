@extends('layouts.app')
@include('includes.admin-navbar')

@section('content')
	<br><br>
	<div class="container mtb">
		<div class="col-lg-6">
	  		<h2>Total Sales</h2> 

	  		<div class="panel panel-default">
	    		<div class="panel-body" style="background:#999999c7;overflow-x:auto;">
	    			<div class="col-lg-12">
	    			<table class="table table-stripped" id="adminViewAllPayments-table">
						<thead>
							<tr>
								<!-- <th>Customer</th> -->
								<th>Total Amount </th>
								<!-- <th>Amount Paid</th> -->
								<!-- <th>Change</th> -->
								<th>Date </th>
							</tr>
						</thead>
						<tbody>
							@foreach($checkDate as $payment)
								<tr>
									<!-- <td>{{$payment->total_amount}} {{$payment->customer_lastname}}</td> -->
									<td>&#8369;{{$payment->totals}}</td>
									<!-- <td>&#8369;{{$payment->amount_paid}}</td> -->
									<!-- <td>&#8369;{{$payment->change}}</td> -->
									@php
										$date_added = date("Y-m-d, h:i:s", $payment->created_at); 
									@endphp
									<td>{{$payment->ca}}</td>
								</tr>
							@endforeach

							
						</tbody>
	    			</table>

					</div>
	    		</div>
	  		</div>
  		</div>

	</div>
@stop