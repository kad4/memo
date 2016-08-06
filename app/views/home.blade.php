@extends('layout')

@section('content')
<div class="container main-container">
	<div class="row">

		<!-- Column for notifications -->
		<div class="col-md-4">
			<h1>Groups</h1>
			<div style="background-color:#FAFAFA;padding:5px;padding-left:20px;">
				<h3><a href="addgroup">Create A New Group</a></h3>
				<div style="height:500px;overflow-y:auto;padding:5px;">
					@if(isset($groups) && count($groups)>0)
						<div class="list-group">
							@foreach($groups as $group)
							<a href="group?id={{$group->id}}" class="list-group-item">{{$group->group_name}}</a>
							@endforeach
						</div>
					@endif
				</div>
			</div>
		</div>

		<div class="col-md-8">
			<div class="row">
				<h1>Notifications</h1>
				<div class="notifications" style="padding:2px;">
					@if($count>0)
						@foreach($notifications as $notification)
							@if($notification->notified==0)
								@if($notification->change=="added")
									<div class="alert alert-success alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true" value="{{$notification->id}}" onclick="usernotified(this);">&times;</button>
										{{$notification->firstname}} has {{$notification->change}} 
										post <strong>{{$notification->post_title}}</strong> in 
										<a href="group?id={{$notification->group_id}}">{{$notification->group_name}}</a><br>
										{{$notification->date}}
									</div>
								@elseif($notification->change=="edited")
									<div class="alert alert-info alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true" value="{{$notification->id}}" onclick="usernotified(this);">&times;</button>
										{{$notification->firstname}} has {{$notification->change}} 
										post <strong>{{$notification->post_title}}</strong> in 
										<a href="group?id={{$notification->group_id}}">{{$notification->group_name}}</a><br>
										{{$notification->date}}
									</div>
								@elseif($notification->change=="deleted")
									<div class="alert alert-danger alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true" value="{{$notification->id}}" onclick="usernotified(this);">&times;</button>
										{{$notification->firstname}} has {{$notification->change}} 
										post <strong>{{$notification->post_title}}</strong> in 
										<a href="group?id={{$notification->group_id}}">{{$notification->group_name}}</a><br>
										{{$notification->date}}
									</div>
								@endif
							@endif
						@endforeach
					@else
						<div class="alert alert-info">No Notifications</div>
					@endif
				</div>
			</div>
		
			<div class="row" style="">
				<h1>Invitations</h1>
				<div style="background-color:#FAFAFA;padding:10px;">
					@if(count($invites)>0)
						@foreach($invites as $invite)
							<div class="alert alert-success">
								<input type="hidden" value="{{$invite->id}}">
								<div>You have been added to group <strong>{{$invite->group_name}}</strong></div>
								<div>
									<a type="button" class="btn btn-info btn-sm" style="text-decoration:none;" onClick="processinvite(this,'accept');">Accept</a>
									<a type="button" class="btn btn-warning btn-sm" style="text-decoration:none;" onClick="processinvite(this,'reject');">Reject</a>
								</div>
							</div>
						@endforeach
					@else
						<div class="alert alert-info">
							No Pending Invites
						</div>
					@endif
				</div>
			</div>
		</div>

	</div>
</div>
@stop
