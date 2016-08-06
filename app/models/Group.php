<?php

class Group extends Eloquent{

	public function ismember($user_id)
	{
		if(in_array($user_id, $this->getmembers()))
			return true;
		else
			return false;
	}

	public function isadmin($user_id)
	{
		if(in_array($user_id, explode(",",$this->admin_users)))
			return true;
		else
			return false;
	}

	public function isowner($user_id)
	{
		if($user_id==$this->owner)
			return true;
		else
			return false;
	}

	public function getowner()
	{
		return(User::find($this->owner));
	}

	public function getmembers()
	{
		$users=[];
		$normal_users=explode(",", $this->users);
		$admin_users=explode(",",$this->admin_users);
		$users=array_merge($normal_users,$admin_users);
		return ($users);
	}

	public function getadminusers()
	{
		if($this->admin_users=="")
			return;
		$admin_users=explode(",", $this->admin_users);
		$data=[];
		foreach ($admin_users as $user) 
		{
			$data[]=User::find($user);
		}
		return $data;
	}

	public function getusers()
	{
		if($this->users=="")
			return;
		$users=explode(",", $this->users);
		$data=[];
		foreach ($users as $user) 
		{
			$data[]=User::find($user);
		}
		return $data;
	}

	public function getposts()
	{
		$posts=Post::where('group_id',$this->id)->get();
		foreach ($posts as $post) 
		{
			$post->addproperty();
		}
		return $posts;
	}

	public function updatepost($id,$change)
	{
		$user_ids=$this->getmembers();

		foreach ($user_ids as $user_id) 
		{
			$user=User::find($user_id);
			if(isset($user))
				$user->updatepost($id,Auth::user()->id,$change);
		}
	}

	public function addmember($user_id)
	{
		if($this->ismember($user_id))
			return "error";
		if($this->users=="")
			$this->users=$user_id;
		else
		{
			$users=explode(",", $this->users);
			if(!in_array($user_id, $user_id))
				$users[]=$user_id;
			$this->users=implode(",", $users);
		}
		$this->save();
	}

	public function deletemember($user_id)
	{
		
		$admin_users=explode(",", $this->admin_users);
		$admin_users=array_diff($admin_users,array($user_id));
		$this->admin_users=implode(",",$admin_users );
	
		$users=explode(",", $this->users);
		if(count($users)>1)			
		{
			$users=array_diff($users,array($user_id));
			$this->users=implode(",",$users);	
		}
		else if($this->users==$user_id)
			$this->users="";

		$this->save();
	}

	public function changerole($user_id)
	{
		$admin_users=explode(",", $this->admin_users);
		$users=explode(",", $this->users);
		if(in_array($user_id, $admin_users))
		{
			$admin_user=array_diff($admin_users,array($user_id));
			$this->admin_users=implode(",",$admin_user);

			if(count($users)>1)			
			{
				$users[]=$user_id;
				$this->users=implode(",",$users);
			}
			else
			{
				$this->users=$user_id;
			}
		}
		else if(in_array($user_id, $users))
		{
			$admin_users[]=$user_id;
			$users=array_diff($users,array($user_id));
			$this->users=implode(",",$users);
			$this->admin_users=implode(",",$admin_users);	
		}

		$this->save();
	}

}