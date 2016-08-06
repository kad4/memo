<?php

class GroupController extends BaseController {
	public function addgroup()
	{
		$group_name=htmlentities(Input::get('group_name'));
		$group=new Group();
		$group->group_name=$group_name;
		$group->owner=Auth::user()->id;
		$group->admin_users=Auth::user()->id;
		$group->save();

		return Redirect::action('HomeController@loadgroup', array('id' =>$group->id));
	}

	public function editgroup()
	{
		$group_id=Input::get('id');
		if(Auth::user()->isadmin($group_id))
		{
			$group=Group::find($group_id);
			$group->group_name=htmlentities(Input::get('group_name'));
			$group->save();
		}
		return Redirect::action('HomeController@loadgroup', array('id' =>$group_id));
	}

	public function deletegroup()
	{
		$group_id=Input::get('id');
		if($group_id!=0)
		{
			$group=Group::find($group_id);
			if(isset($group) && $group->isowner(Auth::user()->id))
			{
				$posts=$group->getposts();
				foreach ($posts as $post)
					$post->delete();

				$user_ids=$group->getmembers();
				foreach ($user_ids as $user_id) 
				{
					$user=User::find($user_id);
					if(isset($user))
						$user->cleargroupnotifications($group->id);
				}

				$group->delete();
				return Redirect::to('/home');
			}
		}
	}
}