@extends('layout')

@section('content')
	<div class="container main-container">
		<div class="row">
			<div class="jumbotron ">
				<h1 >Warning!</h1>
				<h2>You are about to delete group '<strong>{{$group->group_name}}</strong>'</h2>
			</div>

			<button class="btn btn-lg btn-default pull-left" onclick="javascript:history.go(-1);">Back</button>
			<form class="form-horizontal" role="form" action="deletegroup" method="POST">
				<input type="hidden" name="id" value="{{$group->id}}">
				<button type="submit" class="btn btn-lg btn-danger pull-right">Proceed</button>
			</form>
		</div>
	</div>
@stop