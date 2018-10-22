@extends('layouts.app')
@include('includes.admin-navbar')

@section('content')
    
    <br><br><br>
	<div id="headerwrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <img src="{{ asset('template/img/logo2.png') }}" class="img-responsive">     
                </div>
                <div class="col-lg-8 col-lg-offset-2 himg">
                    <h3>We are the Beauty Plus Salon 
                    	<br><br>We Want to Make Your Life Beautiful 
                		#FOREVERYOUNG
                    </h3>
                </div>
            </div>
            <div class="row">
                 <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                      <li data-target="#myCarousel" data-slide-to="1"></li>
                      <li data-target="#myCarousel" data-slide-to="2"></li>
                      <li data-target="#myCarousel" data-slide-to="3"></li>
                      <li data-target="#myCarousel" data-slide-to="4"></li>
                      <li data-target="#myCarousel" data-slide-to="5"></li>
                      <li data-target="#myCarousel" data-slide-to="6"></li>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                      <div class="item active">
                        <img src="{{ asset('template/img/s1.jpg') }}" alt="Los Angeles" style="width:100%;">
                      </div>

                      <div class="item">
                        <img src="{{ asset('template/img/s2.jpg') }}" alt="Chicago" style="width:100%;">
                      </div>
                    
                      <div class="item">
                        <img src="{{ asset('template/img/s3.jpg') }}" alt="Chicago" style="width:100%;">
                      </div>

                      <div class="item">
                        <img src="{{ asset('template/img/s4.jpg') }}" alt="Chicago" style="width:100%;">
                      </div>

                      <div class="item">
                        <img src="{{ asset('template/img/s5.jpg') }}" alt="Chicago" style="width:100%;">
                      </div>

                      <div class="item">
                        <img src="{{ asset('template/img/s6.jpg') }}" alt="Chicago" style="width:100%;">
                      </div>

                      <div class="item">
                        <img src="{{ asset('template/img/s7.jpg') }}" alt="Chicago" style="width:100%;">
                      </div>
                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                      <span class="glyphicon glyphicon-chevron-left"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                      <span class="glyphicon glyphicon-chevron-right"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>


            </div>
        </div>
	</div>

@stop