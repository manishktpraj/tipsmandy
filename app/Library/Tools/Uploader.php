<?php

namespace App\Library\Tools;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Image;
use Protocol;
use Helper;

/**
*
* Uploader
*
* @author Web Nautical <php_mahaveer@webnautical.com>
* @category Media
* @link https://webnautical.com/
* @copyright 2020 Web Nautical
*
*/
class Uploader
{

	/**
	 * Upload single file
	 */
	public static function uploadsinglefile($file, $image_path)
	{

		//$salt_image  = time().rand(1111, 9999);

		$uplode_image_path = $image_path;

        // open an image file
        $get_image_size = getimagesize($file->getRealPath());
        $width = $get_image_size[0];
        $height = $get_image_size[1];
        $canvas = Image::canvas($width, $height);
        $thumb_image = Image::make($file->getRealPath())->resize($width, $canvas, function($constraint){
            $constraint->aspectRatio();
        });

        $canvas->insert($thumb_image, 'fit');

        //$salt_image  = time().bin2hex(openssl_random_pseudo_bytes(22));

        $salt_image  = time().$width.'X'.$height;

        $image_name = $salt_image.'.'.$file->getClientOriginalExtension();

        // now you are able to resize the instance
        $thumb_image->save($uplode_image_path.'/'.$image_name, 80);

        return $image_name;
	}

	/**
	 * Upload Avatar
	 * @param string $avatar
	 * @param boolean $edit
	 * @return string $avatar_link
	 */
	public static function upload_avatar($avatar, $username)
	{
		// Get time
		$time        = md5(time().microtime());

		// Make new name
		$avatar_name = $username.'-'.$time.'.png';

		// Upload Avatar
		$avatar_img  = Image::make($avatar->getRealPath());

		// Resize Avatar
		$avatar_img->resize(100, 100);

		// Save Avatar
		$avatar_img->save(public_path().'/uploads/avatars/'.$avatar_name);

		// Avatar link
		$avatar_link = Protocol::home().'/application/public/uploads/avatars/'.$avatar_name;

		return $avatar_link;
	}

	/**
	 * Upload Avatar from URL
	 * @param string $avatar
	 * @return string $avatar_link
	 */
	public static function upload_avatar_url($avatar, $username)
	{
		try {

			// Get time
			$time        = md5(time().microtime());

			// Make new name
			$avatar_name = $username.'-'.$time.'.png';

			// Upload Avatar
			$avatar_img  = Image::make($avatar);

			// Resize Avatar
			$avatar_img->resize(100, 100);

			// Save Avatar
			$avatar_img->save(public_path().'/uploads/avatars/'.$avatar_name);

			// Avatar link
			$avatar_link = Protocol::home().'/application/public/uploads/avatars/'.$avatar_name;

			return $avatar_link;

		} catch (\Exception $e) {

			// Something went wrong
			return 'avatar.png';

		}
	}

}
