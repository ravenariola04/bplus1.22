@extends('layouts.app')
@include('includes.admin-navbar')

@section('content')
	<br><br>
	<div class="container mtb">
		<div class="col-lg-6">
	  		<h2>Sales Report</h2> 
	 		<div class="hline"></div>

	 		<form role="form" method="POST" action="{{ route('storeSalesDate') }}">
                   	{{ csrf_field() }}

	 				<div class="row"><br>

						<div class="col-lg-6 col-md-6">
							<br><label>From:</label>
							<input type="date" class="form-control" name="date_from" required autofocus placeholder="Date From" >

							@if ($errors->has('date_from'))
                                <strong>{{ $errors->first('date_from') }}</strong>
                            @endif
						</div>

						<div class="col-lg-6 col-md-6">
							<br><label>To:</label>
							<input type="date" class="form-control" name="date_to"  required autofocus placeholder="Date To" >

							@if ($errors->has('date_to'))
                                <strong>{{ $errors->first('date_to') }}</strong>
                            @endif
						</div>

					</div>

					<div class="row col-lg-12">
						<br>
			  			<button type="submit" class="btn btn-theme">Submit</button>
			  			<a href="{{ route('viewAllReservations') }}" class="btn btn-theme">Back</a>
			  		</div>
					
			</form>
  		<!-- <div class="col-lg-6">
	  		<h2>Walk-in Payments</h2> 

	  		<div class="panel panel-default">
	    		<div class="panel-body" style="background:#999999c7;overflow-x:auto;">
	    			<div class="col-lg-12">
	    			<table class="table table-stripped" id="adminViewAllWalkinPayments-table">
						<thead>
							<tr>
								<th>Customer</th>
								<th>Total Amount Due</th>
								<th>Amount Paid</th>
								<th>Change</th>
								<th>Date Added</th>
							</tr>
						</thead>
						<tbody>
							@foreach($walkinPayments as $walkinPayment)
								<tr>
									<td>{{$walkinPayment->customer_firstname}} {{$walkinPayment->customer_lastname}}</td>
									<td>&#8369;{{$walkinPayment->total_amount}}</td>
									<td>&#8369;{{$walkinPayment->amount_paid}}</td>
									<td>&#8369;{{$walkinPayment->change}}</td>
									@php
										$date_added = date("M jS, Y h:i a", strtotime($walkinPayment->created_at)); 
									@endphp
									<td>{{$date_added}}</td>
								</tr>
							@endforeach
						</tbody>
	    			</table>

					</div>
	    		</div>
	  		</div>
  		</div> -->
	</div>
@stop