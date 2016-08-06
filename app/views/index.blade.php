@extends('layout')

@section('content')
	<div class="container main-container">
    <!-- blank row for vertical spacing-->
    <div class="row">
        <div class="jumbotron">
        	<a href="/"><img src="img/logo1.png" alt="" class="pull-right">
            <h1>memoKAD</h1></a>
            <p>Share notes with your friends the fun and cool way. </p>
            <p>
        </div>
    </div>

    <div class="row">
    	<div class="col-md-4 col-xs-6">
            <!-- <p><a href="index.html" class="btn btn-success btn-block">Sign IN! >></a></p> -->
        </div>
        <div class="col-md-4 col-xs-6">
            <!-- <p><a href="index.html" class="btn btn-success btn-block">Sign Up</a></p> -->
        </div>
        <div class="col-md-4 col-xs-6">
           <p><a href="about.html" class="btn btn-success btn-block">Learn More</a></p>
        </div>
    </div>
    
    <div class="row">
        <div class="jumbotron">
            <div class="row">
            <div class="col-md-4 col-sm-6">
                <p><strong>Save your memos on the go!</strong></p>
                <img src="img/screenshot1.png" alt=""> 
            </div>

            <div class="col-md-3 col-md-offset-1 col-sm-6">
                <p><strong>Work together in a group!</strong></p> 
                <img src="img/screenshot1.png" alt="">
            </div>

            <div class="col-md-3 col-md-offset-1 col-sm-6">
                <p><strong> Get memos at your desktop!</strong></p>
                <img src="img/screenshot2.png" alt="" class="pull-left"> 
            </div>
            </div>
        </div>
    </div>

    <footer class="row">
         <p><small></small></p>
    </footer>

</div> <!-- end container -->
@stop