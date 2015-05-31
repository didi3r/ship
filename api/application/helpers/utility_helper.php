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
}