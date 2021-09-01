<?php

namespace App\Library\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use App\User;
use App\Admin;
use Protocol;

/**
* Profile class
*/
class Profile
{

	/**
	 * Profile Picture
	 * @param integer $id
	 * @return string $url
	 */
	public static function admin_avatar($id)
	{
		$user = Admin::where('id', $id)->first(['avatar']);

		// Check user
		if ($user) {

			if(!empty($user->avatar) && File::exists('public/uploads/avatar/'.$user->avatar)){

				return Protocol::home().'/public/uploads/avatar/'.$user->avatar;

			}else{

				return Protocol::home().'/public/avatars/noavatarmale.png';
			}
		}

		// User not found
		return Protocol::home().'/public/avatars/noavatarmale.png';
	}

	/**
	 * Profile Picture
	 * @param integer $user_id
	 * @return string $url
	 */
	public static function picture($user_id)
	{
		$user = User::where('id', $user_id)->first(['avatar', 'gender']);

		// Check user
		if ($user) {

			if(!empty($user->avatar) && File::exists('public/uploads/avatar/'.$user->avatar)){

				return Protocol::home().'/public/uploads/avatar/'.$user->avatar;

			}else{

				if($user->gender==1){

					return Protocol::home().'/public/avatars/noavatarmale.png';

				}else{

					return Protocol::home().'/public/avatars/noavatarfemale.png';
				}

			}

		}

		// User not found
		return Protocol::home().'/public/avatars/noavatar.png';
	}

}
