<?php

class PostController extends BaseController {
	public function loadposts()
	{
		$group=Group::find(Input::get('id'));
		$message="";
		$data=[];
		$posts=[];
		$posts=$group->getposts();
		foreach ($posts as $post)
		{
			$post->content=nl2br($post->content);
			$post->height=$post->height-15;
			$title=substr($post->title,0,10);
			$message=$message.
			"<div class=\"panel panel-primary note\" style=\"width:{$post->width}px; height:{$post->height}px; left:{$post->left}px; top:{$post->top}px; z-index:{$post->zindex};overflow:hidden;\">
				<input type=\"hidden\" class=\"post_id\" value=\"{$post->id}\">
				<div class=\"panel-heading\">
					<h3 class=\"panel-title\">
						<div class=\"pull-left title\">{$title} </div>
						<div class=\"pull-right\">
			";
			
			if($group->isadmin(Auth::user()->id))
							$message=$message.
						"<span class=\"glyphicon glyphicon-pencil\" onclick=\"editpost(this);\"></span>";
			
			$message=$message.
						"</div>
					</h3>
				</div>
				<div class=\"panel-body content\" style=\"overflow:scroll;height:{}px;\">
					{$post->content}
				</div>
			</div>";
		}
		$data['message']=$message;
		$data['status']="success";
		echo json_encode($data);
	}

	public function getpost()
	{
		$post_id=Input::get('id');
		if(Auth::user()->canupdatepost($post_id))
		{
			$post=Post::find($post_id);
			$data['id']=$post->id;
			$data['title']=html_entity_decode($post->title);
			$data['content']=html_entity_decode($post->content);
			echo json_encode($data);
		}
	}

	public function addpost()
	{
		$group_id=Input::get('group_id');
		if(Auth::user()->canaddpost($group_id))
		{
			$post=new Post();
			$post->created_by=Auth::user()->id;
			$post->modified_by=Auth::user()->id;
			$post->title=htmlentities(Input::get('title'));
			$post->content=htmlentities(Input::get('content'));
			$post->group_id=$group_id;
			$post->position="10,10,0";
			$post->property="200,136";
			$post->save();

			if($group_id!=0)
			{
				$group=Group::find($post->group_id);
				$group->updatepost($post->id,"added");
			}
			else
			{
				$user=User::find($post->created_by);
				$user->updatepost($post->id,Auth::user()->id,"added");
			}
			return Redirect::action('HomeController@loadgroup', array('id' =>$group_id));
		}
	}

	public function updatepost()
	{
		$post=Post::find(Input::get('id'));
		$group=Group::find($post->group_id);

		if(!isset($post) || !isset($group))
			return;

		if(Auth::user()->canupdatepost($post->id))
		{
			if(Input::has('top'))
			{
				$post->position=implode(",", array(Input::get('left'),Input::get('top'),Input::get('zindex')));
				$post->save();
			}
			else if(Input::has('width'))
			{
				$post->property=implode(",", array(Input::get('width'),Input::get('height')));
				$post->save();
			}
			else if(Input::get('action')=="save")
			{
				$post->modified_by=Auth::user()->id;
				$post->title=htmlentities(Input::get('title'));
				$post->content=htmlentities(Input::get('content'));
				$post->save();

				$group->updatepost($post->id,"edited");

				return Redirect::action('HomeController@loadgroup', array('id' =>$group->id));
			}
			else if(Input::get('action')=="delete")
			{

				$group->updatepost($post->id,"deleted");
				$post->delete();
				return Redirect::action('HomeController@loadgroup', array('id' =>$group->id));
			}
		}
	}
}