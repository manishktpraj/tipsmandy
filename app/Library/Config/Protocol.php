<?php

namespace App\Library\Config;

/**
* Protocol Class
*/
class Protocol
{

	/******** Get Home Page ********/
	public static function home()
	{
		if (!empty(getenv('HTTPS')) && getenv('HTTPS') != 'off') {

			// Secure connection
			return secure_url('/');

		}else{

			// Insecure Connection
			return url('/');

		}
	}

	/**
	 * Get Page Title
	 * @param string $url
	 * @return string $title
	 */
	public static function title($url) {

		// Get Page Content
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
		$content = curl_exec($ch);

		// Get Title
	    preg_match("/<title.*?>[\n\r\s]*(.*)[\n\r\s]*<\/title>/", $content, $title);

	    if (isset($title[1])) {

	        if ($title[1] == '') {
	            return $url;
	        }

	        $get_title = $title[1];

	        return trim($get_title);
	    }else {

	        return $url;

	    }
	}

	/**
	 * Get Facebook Page Info
	 */
	public static function facebook($username, $return)
	{
		$url="https://graph.facebook.com/v2.7/".$username."?fields=id,name,fan_count,picture,is_verified&access_token=1828373127393085|VrtMC2kVjd2JZ2TQI2lGnvu-vHM";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
		$content = curl_exec($ch);
		$content = json_decode($content);

		if (isset($content->error->code)) {
			return FALSE;
		}

		return $content->$return;
	}

	/**
	 * Get Twitter Page Name
	 */
	public static function twitter($username)
	{
		$twitter = self::title('https://twitter.com/'.$username.'');

		$explode = explode('|', $twitter);

		$sub_explode = explode('(', $explode[0]);

		return $sub_explode[0];
	}

	/**
	 * Get Site Favicon
	 */
	public static function favicon($domain)
	{
		$url = 'https://www.google.com/s2/favicons?domain='.$domain.'';

		return $url;
	}

	/**
	 * Check if youtube video is valid
	 */
	public static function isValidYoutubeURL($url)
	{

		$regex_pattern = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/";
		$match;

		if(preg_match($regex_pattern, $url, $match)){

			// Valid url
			return true;

		}else{

			// Not valid url
			return false;

		}
	}

	/**
	 * Get youtube video id
	 */
	public static function getYoutubeID($url)
	{

		parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );

		if (isset($my_array_of_vars['v'])) {
			return $my_array_of_vars['v'];
		}else{
			return false;
		}

	}
}
