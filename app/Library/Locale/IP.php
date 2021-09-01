<?php

namespace App\Library\Locale;


/**
* IP Address Class
*/
class IP
{
	
	// Get IP
	public static function get()
	{

		// Client IP
	    if (filter_var(getenv('HTTP_CLIENT_IP'), FILTER_VALIDATE_IP)){

	    	$ipaddress = getenv('HTTP_CLIENT_IP');

	    }else if(filter_var(getenv('HTTP_X_FORWARDED_FOR'), FILTER_VALIDATE_IP)){

	    	$ipaddress = getenv('HTTP_X_FORWARDED_FOR');

	    }else if(filter_var(getenv('HTTP_X_FORWARDED'), FILTER_VALIDATE_IP)){

	    	$ipaddress = getenv('HTTP_X_FORWARDED');

	    }else if(filter_var(getenv('HTTP_FORWARDED_FOR'), FILTER_VALIDATE_IP)){

	    	$ipaddress = getenv('HTTP_FORWARDED_FOR');

	    }else if(filter_var(getenv('HTTP_FORWARDED'), FILTER_VALIDATE_IP)){

	    	$ipaddress = getenv('HTTP_FORWARDED');

	    }else if(filter_var(getenv('REMOTE_ADDR'), FILTER_VALIDATE_IP)){

	        $ipaddress = getenv('REMOTE_ADDR');

	    }else{

	        $ipaddress = false;

	    }

	    return $ipaddress;

	}
}