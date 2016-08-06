@extends('layout')

@section('content')
	<script src="js/editgroup.js"></script>
	<div class="container main-container">
		<div class="row">
			<div class="col-sm-offset-4">
				<h2><a href="group?id={{$group->id}}">Back To Group</a></h2>
			</div>
			<form class="form-horizontal editgroup" role="form" action="editgroup" method="POST">
				<input type="hidden" name="id" value="{{$group->id}}" id="groupId">
			  	<div class="form-group">
				  	<label for="group_name" class="col-sm-4 control-label">Group Name</label>
				  	<div class="col-sm-8">
				      <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter Group Name" value="{{$group->group_name}}" required>
				  	</div>
			  	</div>

			  	<div class="form-group">
				  	<label for="group_name" class="col-sm-4 control-label">Owner</label>
				  	<div class="col-sm-8">
				      	<fieldset disabled>
				      		<input type="text" class="form-control" id="owner" placeholder="Enter Group Name" value="{{$group->owner->username}}">
				  		</fieldset>
				  	</div>
				</div>

				<div class="form-group">
					<label for="admin_users" class="col-sm-4 control-label">Users</label>
					<div class="col-sm-8">
					 	<div class="input-group">
				      		<span class="input-group-btn">
				        		<button class="btn btn-default" type="button" onclick="sendinvite(this);"><span class="glyphicon glyphicon-plus"></span></button>
				      		</span>
				      		<input type="text" class="form-control" placeholder="Email Address">
				    	</div>
					</div>
				</div>

				<div class="form-group" id="message" style="display:none;">
					<div class="col-sm-offset-4" >
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-4" >
						Admin Users
						<div class="list-group">
							@if(isset($group->admin_users))
								@foreach($group->admin_users as $user)
									<div class="list-group-item">
										<input type="hidden" value="{{$user->id}}">
										<div>
											{{$user->username}}
											<span class="glyphicon glyphicon-remove pull-right remove"  onclick="deleteuser(this);"></span>
										</div>
										<div>
											<a href="#" onclick="changerole(this);">Remove Admin Rights</a>
										</div>
									</div>
								@endforeach
							@endif
						</div>

						Normal Users
						<div class="list-group">
							@if(isset($group->users))
								@foreach($group->users as $user)
									<div class="list-group-item">
										<input type="hidden" value="{{$user->id}}">
										<div>
											{{$user->username}}
											<span class="glyphicon glyphicon-remove pull-right remove"  onclick="deleteuser(this);"></span>
										</div>
										<div>
											<a href="#" onclick="changerole(this);">Make Admin</a>
										</div>
									</div>
								@endforeach
							@endif
						</div>
					</div>
				</div>

				<div class="col-sm-offset-4">
				  	<button type="submit" class="btn btn-sm btn-default pull-left">Save Changes</button>
			  		@if(Auth::user()->isgroupowner($group->id))
			  			<a class="btn btn-sm btn-danger pull-right" href="deletegroup?id={{$group->id}}">Delete Group</a>
			  		@endif
		  		</div>
			</form>
		</div>
	</div>
@stop