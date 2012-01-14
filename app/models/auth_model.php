<?php
/**
 * Reverse bidding system Auth_model Class
 *
 * This Model will take care of handling sessions of the user.
 *
 * @package		Reverse bidding system
 * @subpackage	Models
 * @category	Common_model 
 * @author		
 * @version		
 * @link		
 
  <Reverse bidding system> 
    Copyright (C) <2009>  <Cogzidel Technologies>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

 */ 
class Auth_model extends Model {
	 
	/**
	 * Constructor 
	 *
	 */
	  function Auth_model() 
	  {
		parent::Model();
				
      }//Controller End
	 
	// --------------------------------------------------------------------
		
	/**
	 * Get Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function loginAsAdmin($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select('admins.id');
		$result = $this->db->get('admins');
		if($result->num_rows()>0)
			return true;
		else 
			return false;	
	 }//End of loginAsAdmin Function
	 
 	// --------------------------------------------------------------------
		
	/**
	 * Get Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function setAdminSession($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select('admins.id,admins.admin_name');
		$result = $this->db->get('admins');
		if($result->num_rows()>0)
		{
			$row = $result->row();
			$values = array ('admin_id'=>$row->id,'logged_in'=>TRUE,'admin_role'=>'admin'); 
			$this->session->set_userdata($values);
		}
		
	 }//End of setAdminSession Function
	 
	// --------------------------------------------------------------------
		
	/**
	 * Get Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function setUserSession($row=NULL)
	 {
	 	switch($row->role_name)
		{
			case 'buyer':
			  
				$values = array('user_id'=>$row->id,'logged_in'=>TRUE,'role'=>'buyer');
				$this->session->set_userdata($values);
				break;
				
			case 'seller':
			
				$values = array('user_id'=>$row->id,'logged_in'=>TRUE,'role'=>'seller');
				$this->session->set_userdata($values);
				break;	
		}
	 	
	 }//End of setUserSession Function
	 
	 
	 
	 // Puhal Changes Start Function added for the Remenber me option  (Sep 17 Issue 3)	
	 
	  function setUserCookie($name='',$value ='',$expire = '',$domain='',$path = '/',$prefix ='')
	 {
	 		 $cookie = array(
                   'name'   =>$name,
                   'value'  => $value,
                   'expire' => $expire,
                   'domain' => $domain,
                   'path'   => $path,
                   'prefix' => $prefix,
               );
			  set_cookie($cookie); 
	 }//End of setUserCookie Function	

		
		
	 function getUserCookie($name='')
	 {
		 $val=get_cookie($name,TRUE); 
		return $val;
	 }//End of getUserCookie Function		
	 
 
	  function clearUserCookie($name=array())
	 {
	 	foreach($name as $val)
		{
			delete_cookie($val);
		}	
	 }//End of clearSession Function*/
	 
// Puhal Changes End Function added for the Remenber me option  (Sep 17 Issue 3)		 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	// --------------------------------------------------------------------
		
	/**
	 * clearSession
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function clearAdminSession()
	 {
	 
	 	$array_items = array ('admin_id' => '','logged_in_admin'=>'','admin_role'=>'');
	    $this->session->unset_userdata($array_items);
		
	 }//End of clearSession Function
	 
	// --------------------------------------------------------------------
		
	/**
	 * clearUserSession
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function clearUserSession()
	 {
	 	$array_items = array('user_id' => '','logged_in'=>'','role'=>'');
		$this->session->unset_userdata($array_items);
	 }//End of clearSession Function
	 
}
// End Auth_model Class
   
/* End of file Auth_model.php */ 
/* Location: ./app/models/Auth_model.php */