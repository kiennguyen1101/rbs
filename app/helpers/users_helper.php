<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
	// ------------------------------------------------------------------------

	/**
	 * getUserInfo
	 *
	 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function getUserInfo($userId=NULL)
	{
		$CI 	=& get_instance();
		$mod 	= $CI->load->model('user_model');
		$conditions = array('users.id'=>$userId);
		$result = $CI->user_model->getUsers($conditions);
		if($result->num_rows()>0)
		{
			$data = $result->row();	
		} else {
			$data = '';
		}
		
		return $data;	
	}
	
	function file_get_contents_curl($url) 
	{

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		$data = curl_exec($ch);
		
		curl_close($ch);
		
		return $data;
	}
	
/* End of file users_helper.php */
/* Location: ./app/helpers/users_helper.php */
?>