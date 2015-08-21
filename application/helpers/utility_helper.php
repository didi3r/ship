<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('asset_url()'))
{
	function assets_url($print = true)
	{
		if($print)
			echo base_url() . 'public/';
		else
			return base_url() . 'public/';
	}

	function is_set($var)
	{
		echo isset($var) ? $var : '';
	}

	function sanitise_url($url, $encode = false) {
	    $url = filter_var(urldecode($url), FILTER_SANITIZE_SPECIAL_CHARS);
	    if (! filter_var($url, FILTER_VALIDATE_URL))
	        return false;
	    return $encode ? urlencode($url) : $url;
	}
}