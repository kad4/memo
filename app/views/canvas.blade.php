@extends('layout')

@section('content')
<link href="css/canvas.css" rel="stylesheet">
<div class="main-container">
	  	<div class="col-md-2 col-sm-2">
	  		<!-- Sidebar -->
		  	<div class="sidebar">
				<!-- Group Name -->
				<div class="page-header">
					<h1>{{$group->group_name}}</h1>
					@if($role=="admin")
						<a href="editgroup?id={{$group->id}}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-cog"></span>Edit Group</a>
					@endif
				</div>

		  		<div class="">
			  		@if($role=="admin")
			  			<!-- Button to trigger modal for adding note-->
				  		<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addnote"><span class="glyphicon glyphicon-plus"></span> Add Note</button>
				  	@endif
		  		</div>


		  		<!-- Modals for adding and editing notes -->
		  		<div class="">
			  		@if($role=="admin")
			  		<!-- Modal For Adding A Note-->
						<div class="modal fade" id="addnote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-dialog">
						    <div class="modal-content">

						    	<!-- Modal Header -->
						      	<div class="modal-header">
						        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						        	<h4 class="modal-title" id="myModalLabel">Add A New Note</h4>
						      	</div>

						      	<form action="addpost" method="post" class="form-horizontal" role="form" >
						      		<input type="hidden" name="group_id" id="group_id" value="{{$group->id}}">
									
						      		<!-- Modal Body -->
									<div class="modal-body">
										<div class="form-group">
										  	<label for="title" class="col-sm-2 control-label">Title</label>
										  	<div class="col-sm-10">
										      <input type="text" name="title" class="form-control" id="title" placeholder="Enter Post Title" required>
										  	</div>
									  	</div>
									  	<div class="form-group">
										  	<label for="content" class="col-sm-2 control-label">Content</label>
										  	<div class="col-sm-10">
										      <textarea name="content" id="content" class="form-control" style="resize:none;height:300px;">&nbsp;</textarea>
										  	</div>
									  	</div>
									</div>

									<!-- Modal Footer -->
									<div class="modal-footer">
										<button type="submit" class="btn btn-primary btn-sm">Add</button>
										<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
									</div>
						  		</form>

						    </div>
						  </div>
						</div>
						<!-- End of Modal for adding Note -->

						<!-- Modal for updating notes -->
						<div class="modal fade" id="editnote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-dialog">
						    <div class="modal-content">
						    	<!-- Modal Header -->
						      	<div class="modal-header">
						        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						        	<h4 class="modal-title" id="myModalLabel">Edit Note</h4>
						      	</div>

						      	<form action="updatepost" method="post" class="form-horizontal" role="form" >
						      		<input type="hidden" name="id" id="post_id">
									
						      		<!-- Modal Body -->
									<div class="modal-body">
										<div class="form-group">
										  	<label for="title" class="col-sm-2 control-label">Title</label>
										  	<div class="col-sm-10">
										      <input id="title" class="form-control" type="text" name="title" placeholder="Enter Post Title" required>
										  	</div>
									  	</div>
									  	<div class="form-group">
										  	<label for="content" class="col-sm-2 control-label">Content</label>
										  	<div class="col-sm-10">
										      <textarea id="content" class="form-control" name="content" style="resize:none;height:300px;">&nbsp;</textarea>
										  	</div>
									  	</div>
									</div>

									<!-- Modal Footer -->
									<div class="modal-footer">
										<button type="submit" class="btn btn-danger btn-sm pull-left" value="delete" name="action">Delete</button>
										<button type="submit" class="btn btn-primary btn-sm " value="save" name="action">Save</button>
										<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
									</div>
						  		</form>
						    </div>
						  </div>
						</div>
						<!-- End of Modal to updating notes -->
					@endif
		  		</div>
		  	</div>
		</div>

		<div class="col-md-10 col-sm-10">
		  	<!-- Workspace to store notes -->
		  	<div class="workspace" style="border:5px solid #CCCCCC;background-color:#EEEEEE;">
				@if(isset($posts) && count($posts)>0)
					@foreach($posts as $post)
							<div class="panel panel-primary note" style="width:{{$post->width}}px; height:{{$post->height}}px; left:{{$post->left}}px; top:{{$post->top}}px; z-index:{{$post->zindex}};overflow:hidden;">
								<input type="hidden" class="post_id" value="{{$post->id}}">
								<div class="panel-heading">
									<h3 class="panel-title">
										<div class="pull-left title">{{substr($post->title,0,10)}} </div>
										<div class="pull-right">
											@if($role=="admin")
											<span class="glyphicon glyphicon-pencil" onclick="editpost(this);"></span>
											@endif
										</div>
									</h3>
								</div>
								<div class="panel-body content" style="overflow-y:scroll;height:{{$post->height-15}}px;">
									{{nl2br($post->content)}}
								</div>
							</div>
					@endforeach
				@endif
			</div>
		</div>
</div>

<script src="js/canvas.js"></script>
@stop
