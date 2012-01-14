<?php 
/**
 * Reverse bidding system Account_model Class
 *
 * Handles Account information in database.
 *
 * @package		Reverse bidding system
 * @subpackage	Models
 * @category	Settings 
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

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
    
     
 
 
 */
	 class Account_model extends Model {
	 
	/**
	 * Constructor 
	 *
	 */

	  function Account_model() 
	  {
	  	parent::Model();
      }//Controller End
	  
	  /*Get admin Balance
	  *
	  */
	  function adminBalance($condition=array())
	    {
			if(isset($condition) and count($condition) > 0)
			  $this->db->where($condition);
			
			$this->db->select_sum('amount');
			$result = $this->db->get('user_balance');
			return $result;
			    
		}//Funciton end
	//-----------------------------------------
	
	/**
	 * Get Userslist
	 *
	 * @access	private
	 * @param	nil
	 * @return	object	object with result set
	 */
	 function getUserslist($conditions=array())
	 {
	
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->from('users');
		$this->db->join('roles', 'roles.id = users.role_id','left');	
			
	 	$this->db->select('users.id,roles.role_name,users.user_name,users.name,users.role_id,users.country_symbol,users.message_notify,users.password,users.email,users.city,users.state,users.profile_desc,users.rate,users.project_notify,users.user_status,users.activation_key,users.created');
		
		$result = $this->db->get();
		return $result;
		
	 }//End of getUsers Function
	 
	 // --------------------------------------------------------------------	
	 
	 
	 
}
// End Account_model Class
   
/* End of file Account.php */ 
/* Location: ./app/models/Account.php */