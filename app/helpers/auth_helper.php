<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// ------------------------------------------------------------------------

/**
 * Check Whether the user is an admin
 *
 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */
	function isAdmin()
	{
		$CI 	=& get_instance();
		return $CI->session->userdata ('admin_role') == 'admin'? TRUE: FALSE;
	}

// ------------------------------------------------------------------------

/**
 * Check Whether the user is an admin
 *
 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */
	function isProgrammer()
	{
		$CI 	=& get_instance();
		return  $CI->session->userdata('role') == 'programmer'?TRUE:FALSE;
	}
	
// ------------------------------------------------------------------------

/**
 * Check Whether the user is logged in
 *
 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */
	function isLoggedIn()
	{
		$CI 	=& get_instance();
		return  $CI->session->userdata('logged_in') == '1'?TRUE:FALSE;
	}
	
// ------------------------------------------------------------------------

/**
 * Check Whether the user is an admin
 *
 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */
	function isBuyer()
	{
		$CI 	=& get_instance();
		return  $CI->session->userdata('role') == 'buyer'?TRUE:FALSE;
	}	
	
	function  getBanStatus($uname)
	{
	
		$CI 	=& get_instance();
		$CI->load->model('common_model');
		$condition =array('users.user_name'=>$uname);
		$sus_status= $CI->common_model->getTableData('users',$condition,'users.ban_status');
		$sus_status = $sus_status->row();
		if(isset($sus_status->ban_status))		
			return $sus_status->ban_status;
		else
		 	return false;
		
	}
	

/* End of file MY_url_helper.php */
/* Location: ./app/helpers/MY_url_helper.php */