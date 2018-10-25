@extends('layouts.app')
<!-- @include('includes.admin-navbar') -->

@section('content')


 <br><br><br>
	<div id="headerwrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <img src="{{ asset('template/img/logo2.png') }}" class="img-responsive">     
                </div>
                <div class="col-lg-8 col-lg-offset-2 himg">
                	<br>
                    <h2> To verify email
                    </h2>
                    <a class="btn btn-primary " href="{{route('sendEmailDone',['email'=>$user->email,'verifyToken'=>$user->verifyToken]) }}">Click Here</a>
                </div>
            </div>
        </div>
	</div>


@stop

