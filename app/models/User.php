<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function canupdatepost($post_id)
	{
		$user_id=$this->id;
		$group_id=Post::find($post_id)->group_id;
		return ($this->isadmin($group_id));
	}

	public function canaddpost($group_id)
	{
		return ($this->isadmin($group_id));
	}

	public function isadmin($group_id)
	{
		$group=Group::find($group_id);
		if(isset($group))
		{
			if($group->isadmin(Auth::user()->id))
				return true;
		}
		return false;
	}

	public function ismember($group_id)
	{
		$group=Group::find($group_id);
		if(isset($group))
		{
			if($group->ismember(Auth::user()->id))
				return true;
		}
		return false;
	}

	public function isowner($post_id)
	{
		$user_id=$this->id;
		$post_owner=Post::find($post_id)->created_by;
		if($post_owner==$user_id)
			return true;
		else
			return false;
	}

	public function isgroupowner($group_id)
	{
		if($group_id==0)
			return true;
		$group=Group::find($group_id);
		if($group->isowner(Auth::user()->id))
			return true;
		else
			return false;
	}

	public function getnotifications()
	{
		$notifications=unserialize($this->changes);
		return $notifications;
	}

	public function getgroups()
	{
		$user_id=$this->id;
		$groups=Group::all();
		$group_list=[];

		foreach ($groups as $group) 
		{
			if($group->ismember($user_id))
				$group_list[]=$group;
		}
		return $group_list;
	}

	public function getinvites()
	{
		$invites=unserialize($this->invites);
		return $invites;
	}

	public function addinvite($group)
	{
		$new_obj=new stdClass;

		$new_obj->id=$group->id;
		$new_obj->group_name=$group->group_name;
		$new_obj->date=date('Y-m-d H:i:s');

		if($this->invites=="")
		{
			$data=[];
			$data[]=$new_obj;
			$this->invites=serialize($data);
		}
		else
		{
			$obj=unserialize($this->invites);
			$obj[]=$new_obj;
			$this->invites=serialize($obj);
		}

		$this->save();
	}


	public function updatepost($post_id,$user_id,$change)
	{
		$new_obj=new stdClass;
		$post=Post::find($post_id);
		$user=User::find($user_id);
		$group=Group::find($post->group_id);

		if(!isset($user) || !isset($group))
			return;

		$new_obj->post_id=$post->id;
		$new_obj->post_title=$post->title;
		$new_obj->user_id=$user->id;
		$new_obj->firstname=$user->firstname;
		$new_obj->change=$change;
		$new_obj->date=date('Y-m-d H:i:s');
		$new_obj->synced=0;

		$new_obj->group_id=$group->id;
		$new_obj->group_name=$group->group_name;

		if($this->id==$user_id)
			$new_obj->notified=1;
		else
			$new_obj->notified=0;

		if($this->changes=="")
		{
			$data=[];
			$data[]=$new_obj;
			$this->changes=serialize($data);
		}
		else
		{
			$data=unserialize($this->changes);
			$data[]=$new_obj;
			$this->changes=serialize($data);
		}
		$this->save();	
	}


	public function clearnotifications()
	{
		$data=[];
		$this->changes=serialize($data);
		$this->save();
	}

	public function cleargroupnotifications($group_id)
	{
		$notifications=$this->getnotifications();
		$data=[];
		foreach ($notifications as $notification) 
		{
			if($notification->group_id!=$group_id)
			{
				$data[]=$notification;
			}
		}
	}

}