@extends('layout')

@section('content')
<div class="container main-container">
  <link href="./css/signin.css" rel="stylesheet">

  <div class="row">
    @if(Session::has('message'))
      <div class="alert alert-success" style= "width: 330px; text-align: center; margin: auto; margin-bottom: 50px">{{Session::get('message')}}</div>
    @endif
    @if(isset($errors) && count($errors) > 0)
      <div class="alert alert-danger" style= "width: 330px; text-align: center; margin: auto; margin-bottom: 50px">
      @foreach($errors as $error)
        <p>{{$error}}</p>
      @endforeach
      </div>
    @endif
    @if(!Auth::check())
      <form class="form-signin thumbnail" method="post" action="signup" role="form" style="border: 4px solid; border-color: #DDD;">
        <h2 class="form-signin-heading">Enter your details:</h2>
        <input type="text" name="firstname" class="form-control" placeholder="First Name" required autofocus style="margin-bottom: 8px">
        <input type="text" name = "lastname" class="form-control" placeholder="Last Name" required style="margin-bottom: 8px">
        <input type="text" name = "username" class="form-control" placeholder="User Name" required style="margin-bottom: 8px">
        <input type="text" name ="email" class="form-control" placeholder="Email address" required  style="margin-bottom: 8px">
        <input id="userpassword" type="password" name = "password" class="form-control" placeholder="Password" required style="margin-bottom: 8px">        
        <input id="repassword" type="password" name = "rePassword" class="form-control" placeholder="Re-enter Password" required style="margin-bottom: 8px" > 
        <button class="btn btn-danger btn-block" type="submit" style="margin-top: 50px">Sign up!</button>
      </form>
    @else
      <div class="alert alert-danger" style= "width: 330px; text-align: center; margin: auto; margin-bottom: 50px">
        Please Logout to continue....
      </div>
    @endif
  </div>
  
</div>
@stop