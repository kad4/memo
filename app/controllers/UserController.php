<?php

class UserController extends BaseController {
	public function login()
	{
		$username=Input::get('username');
		$password=Input::get('password');
		if (Auth::attempt(array('username' => $username, 'password' => $password)))
			return Redirect::to('/home');
		else
			return Redirect::to('/');
	}

	public function logout()
	{
		Auth::logout();
		return Redirect::to('/');
	}


	public function signup()
	{
		Input::flash();
		$input = Input::all();
		$validator = Validator::make($input,
			array(
				'username' => 'required|min:6',
				'firstname' => 'required|min:3',
				'lastname' => 'required|min:3',
				'email' => 'required|email',
				'password' => 'required|min:6'
				));
		if ($validator->fails())
		{
			$messages = $validator->messages()->all();
			$this->data['errors']=$messages;
			return View::make('signup',$this->data);
		}
		else
		{
			$flag=false;
        	if(User::where('username','=',Input::get('username'))->count() > 0)
        	{
        		$flag=true;
        		$this->Data['errors'][]="Username already Exists!";
        	}

        	if( User::where('email','=',Input::get('email'))->count() > 0)
        	{
        		$flag=true;
        		$this->Data['errors'][]="Another user exists with that email address";
        	}

        	if (Input::get('rePassword') !== Input::get('password'))
        	{
        		$flag=true;
        		$this->Data['errors'][]="Passwords don't match with each other.";
        	}

        	if($flag==false)
        	{
                $user = new User();
                $user->firstname=Input::get('firstname');
                $user->lastname=Input::get('lastname');
                $user->username=Input::get('username');
                $user->password=Hash::make(Input::get('password'));
                $user->email=Input::get('email');
                $user->changes=serialize(array());
                $user->invites=serialize(array());
                $user->save();
                return Redirect::to('/signup')->with('message', 'Signup Successful');
            } 
		}
		return View::make("signup",$this->Data);
	}

	public function sendinvite()
	{
		$group=Group::find(Input::get('id'));
		$email=Input::get('email');
		if(isset($group))
		{
			$user=User::where('email',$email)->first();
			if(isset($user))
			{
				if($group->ismember($user))
				{
					$data['status']="error";
					$data['error']="<div class=\"alert alert-warning alert-dismissable\">
					  				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
					  				<p>User Already Present</p>
								</div>";
					echo json_encode($data);
					return;
				}
				else
				{
					$user->addinvite($group);

					$data['status']="success";
					$data['message']="<div class=\"alert alert-success alert-dismissable\">
					  				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
					  				<p>Request was sent to user</p>
								</div>";
					echo json_encode($data);
					return;
				}
			}
			else
			{
				$data['status']="error";
				$data['error']="<div class=\"alert alert-warning alert-dismissable\">
					  				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
					  				<p>User with that email address not found</p>
								</div>";
				echo json_encode($data);
				return;
			}
		}
	}


	public function processinvite()
	{
		$group=Group::find(Input::get('id'));
		$action=Input::get('action');
		if($action=="accept")
		{
			if(!$group->ismember(Auth::user()->id))
			{
				$group->addmember(Auth::user()->id);
				$old_posts=$group->getposts();
				$user=Auth::user();
				foreach ($old_posts as $post) 
				{
					$user->updatepost($post->id,$user->id,"added");
				}
			}
		}

		$new_obj=array();
		$old_obj=unserialize(Auth::user()->invites);
		foreach ($old_obj as $obj) 
		{
			if($obj->id!=$group->id)
				$new_obj[]=$obj;
		}

		Auth::user()->invites=serialize($new_obj);
		Auth::user()->save();
	}


	public function changerole()
	{
		$group=Group::find(Input::get('id'));
		$user_id=Input::get('user_id');
		if(isset($group))
		{
			if(!$group->isowner($user_id))
				$group->changerole($user_id);
		}
	}


	public function deleteuser()
	{
		$group=Group::find(Input::get('id'));
		$user=User::find(Input::get('user_id'));
		if(isset($group) && isset($user))
		{
			if($group->isowner($user->id))
			{
				$data['status']="error";
				$data['error']="<div class=\"alert alert-warning alert-dismissable\">
		  				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
		  				<p>Cannot delete owner</p>
					</div>";
				echo json_encode($data);
				return;
			}
			
			$user->cleargroupnotifications($group->id);
			$response=$group->deletemember($user->id);
			$data['status']="success";
			echo json_encode($data);
			return;
		}
	}


	public function usernotified()
	{
		$num=Input::get('id');
		$notifications=Auth::user()->getnotifications();
		if(count($notifications)>$num && $num>=0)
		{
			$notifications[$num]->notified=1;
		}
		Auth::user()->changes=serialize($notifications);
		Auth::user()->save();

	}
}