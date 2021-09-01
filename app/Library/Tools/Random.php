<?php

namespace App\Library\Tools;
use Illuminate\Support\Str;

/**
* Random
*/
class Random
{

	/**
	 * Generate Unique ID
	 */
	public static function unique()
	{
		// Generate Unique MD5 String
		$string = crc32(uniqid());

		// Return 10 Unique ID Characters
		return substr($string, 0, 10);
	}

	/**
	 * Generate Slug
	 */
	public static function slug($title, $id)
	{
		$slug = Str::slug($title, '-').'-'.$id.'.html';

		return $slug;
	}

	/**
	 * Generate Activation Code
	 */
	public static function activation_code($email)
	{
		return strtoupper(sha1(mt_rand(10000,99999).time().microtime().$email.md5($email)));
	}

	/**
	 * Generate SMS Code Activation
	 */
	public static function sms_code()
	{
		return mt_rand(100000,999999);
	}
}
