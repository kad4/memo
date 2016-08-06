<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showhome()
	{
		$groups=Auth::user()->getgroups();
		$notifications=Auth::user()->getnotifications();
		$invites=Auth::user()->getinvites();
		$count=0;
		$i=0;
		if(count($notifications)>0)
		{
			foreach ($notifications as $notification)
			{
				$notification->id=$i;
				if($notification->notified==0)
					$count=$count+1;
				$i=$i+1;
			}
		}
		return View::make('home',array('groups' => $groups,'user'=>Auth::user(),'notifications'=>$notifications,'invites'=>$invites,'count'=>$count));
	}

	public function loadgroup()
	{
		$group=Group::find(Input::get('id'));
		if(isset($group))
		{
			if($group->ismember(Auth::user()->id))
			{
				$posts=$group->getposts();
				if($group->isadmin(Auth::user()->id))
					$role="admin";
				else
					$role="user";
				return View::make('canvas',array('group' => $group,'posts' =>$posts,'role' => $role));
			}
			else
				return Redirect::to('/home');
		}
		else
		{
			return Redirect::to('/home');
		}
	}

	public function addgroup()
	{
		return View::make('addgroup');
	}

	public function editgroup()
	{
		$group=Group::find(Input::get('id'));
		if(isset($group))
		{
			$group->owner=$group->getowner();
			$group->admin_users=$group->getadminusers();
			$group->users=$group->getusers();
			return View::make('editgroup',array('group' => $group ));
		}
	}

	public function deletegroup()
	{
		$group=Group::find(Input::get('id'));
		if(isset($group))
		{
			if(isset($group) && $group->isowner(Auth::user()->id))
				return View::make('deletegroup',array('group' => $group));
		}
	}
}