@extends('layout')

@section('content')
	<div class="container main-container">
		<div class="row">
			<form class="form-horizontal editgroup" role="form" action="addgroup" method="POST">
				<input type="hidden" id="group_id" name="group_id" >
			  	<div class="form-group">
				  	<label for="group_name" class="col-sm-4 control-label">Group Name</label>
				  	<div class="col-sm-8">
				      <input type="text" class="form-control" placeholder="Enter Group Name" name="group_name" required>
				  	</div>
			  	</div>
			  	<button type="submit" class="btn btn-default col-sm-offset-4">Create Group</button>
			</form>
		</div>
	</div>
@stop