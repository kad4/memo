<?php

class ClientController extends BaseController {
	public function checkuser()
	{
		$username=Input::Get('username');
		$password=Input::Get('password');
		$data=array();
		if (Auth::attempt(array('username' => $username, 'password' => $password)))
		{
			$data['status']=true;
			$data['userid']=Auth::user()->id;
		}
		else
		{
			$data['status']=false;
		}
		echo json_encode($data);
	}

	public function checknotes()
	{
		$user=User::find(Input::get('userid'));
		if(isset($user))
		{
			$data=[];
			if($user->changes!="")
			{
				$data['update']=true;
			}
			else
			{
				$data['update']=false;
			}
			echo json_encode($data);
		}
	}

	public function checkgroups()
	{
		$user=User::find(Input::get('userid'));

		if(isset($user))
		{
			$data=[];
			$groups=$user->getgroups();
			foreach ($groups as $group) 
			{
				$temp=new stdClass;
				$temp->id=$group->id;		
				$temp->group_name=$group->group_name;
				$data[]=$temp;
			}
			echo json_encode($data);
		}
	}	

	public function notifications()
	{
		$user=User::find(Input::get('userid'));
		if(isset($user))
		{
			$data=[];
			$notifications=$user->getnotifications();
			foreach ($notifications as $notification) 
			{
				if($notification->user_id!=$user->id)
				{
					$temp=new stdClass;
					$temp->id=$notification->post_id;
					$temp->date=$notification->date;
					$temp->value=$notification->firstname." has ".$notification->change." ".$notification->post_title." in ".$notification->group_name;
					$temp->type="notification";
					$data[]=$temp;
				}
			}

			$invites=$user->getinvites();
			foreach ($invites as $invite) 
			{
				$temp=new stdClass;
				$temp->id=$invite->id;
				$temp->date=$invite->date;
				$temp->value="You have been invited to group ".$invite->group_name;
				$temp->type="invite";
			}
			echo json_encode($data);
		}
	}


	public function allnotes()
	{
		$user=User::find(Input::get('userid'));
		$totalnotes=[];
		if(isset($user))
		{
			$groups=$user->getgroups();
			foreach ($groups as $group) 
			{
				$groupNotes=$group->getposts();
				foreach ($groupNotes as $groupNote) 
				{
					$totalnotes[]=$groupNote;
				}
			}
		}

		$data=[];
		if(isset($totalnotes) && count($totalnotes)>0)
		{
			foreach ($totalnotes as $note) 
			{
				$temp=new stdClass;
				$temp->id=$note->id;
				$temp->owner=$note->created_by;
				$temp->title=$note->title;
				$temp->content=$note->content;
				$temp->group_id=$note->group_id;
				$temp->modified_by=$note->modified_by;
				$data[]=$temp;
			}
		}
		echo json_encode($data);
	}

	public function newnotes()
	{
		$user=User::find(Input::get('userid'));
		if(isset($user))
		{
			$notifications=$user->getnotifications();
			$data=[];
			if(count($notifications)>0)
			{
				foreach ($notifications as $notification) 
				{
					if($notification->change!="deleted")
					{
						$temp=new stdClass;
						$post=Post::find($notification->post_id);
						if(isset($post))
						{
								$temp->id=$post->id;
								$temp->owner=$post->created_by;
								$temp->title=$post->title;
								$temp->content=$post->content;
								$temp->group_id=$post->group_id;
								$temp->modified_by=$post->modified_by;

								$data[]=$temp;
						}
					}
				}
			}
			echo json_encode($data);
		}
	}

	public function deletednotes()
	{
		$user=User::find(Input::get('userid'));
		if(isset($user))
		{
			$notifications=$user->getnotifications();
			$data=[];
			if(count($notifications)>0)
			{
				foreach ($notifications as $notification) 
				{
					if($notification->change=="deleted")
					{
						$data[]=$notification->post_id;
					}
				}
			}
			echo implode(",", $data);
		}
	}


	public function clearnotifications()
	{
		$user=User::find(Input::get('userid'));
		if(isset($user))
		{
			$user->clearnotifications();
		}
	}
}