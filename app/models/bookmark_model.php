<?php 
/**
 * Reverse bidding system User_model Class
 *
 * helps to achieve common tasks related to the site like flash message formats,pagination variables.
 *
 * @package		Reverse bidding system
 * @subpackage	Models
 * @category	Common_model 
 * @author		Cogzidel Dev Team
 * @version		Version 1.0
 * @link		http://www.cogzidel.com
 
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
    If you want more information, please email me at bala.k@cogzidel.com or 
    contact us from http://www.cogzidel.com/contact 
 
 
 */
	 class bookmark_model extends Model {
	 
	/**
	 * Constructor 
	 *
	 */
	  function bookmark_model() 
	  {
		parent::Model();
				
      }//Controller End
	  
	// --------------------------------------------------------------------
	
		
	/**
	 * create user
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	 function createBookmark($insertData=array())
	 {
	 	$this->db->insert('bookmark', $insertData);
	 }//End of createUser Function
	 
	 // --------------------------------------------------------------------
	
	
	/**
	 * Get bookmark details
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getBookmark($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		$this->db->from('bookmark');
			
	 	$this->db->select('bookmark.id,bookmark.creator_id,bookmark.creator_name,bookmark.project_id,bookmark.project_name,bookmark.project_creator');
		 
		$result = $this->db->get();
		return $result;
		
	 }//End of getUserContacts Function
	 //---------------------------------------------------------------------------------------------------------------//
	 
	
	
	
	/**
	 * insert User Contacts
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	 function insertUserContacts($insertData=array())
	 {
	 	$this->db->insert('user_contacts',$insertData);
	 }//End of insertUserContacts Function
	 	/**
	 * insert User Categorys
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	 function insertUserCategories($insertData=array())
	 {
	 	$this->db->insert('user_categories',$insertData);
	 }//End of insertUserContacts Function
	 
	 
 	// --------------------------------------------------------------------
		
	/**
	 * create userBalanceAccount 
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	 function createUserBalance($insertBalance=array())
	 {
	 	$this->db->insert('user_balance', $insertBalance);
	 }//End of createUser Function
	 
 	// --------------------------------------------------------------------
		
		
	/**
	 * Update users
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateUser($updateKey=array(),$updateData=array())
	 {
	    $this->db->update('users',$updateData,$updateKey);
		 
	 }//End of editGroup Function 
	 
	 	
	/**
	 * Update usersCategories
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateCategories($updateKey=array(),$updateData1=array())
	 {
	    $this->db->update('user_categories',$updateData1,$updateKey);
		 
	 }//End of editGroup Function 
	 
	 
	 
	 		
	/**
	 * Update usersContent
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateUserContacts($userContacts=array(),$updateKey2)
	 {
	 	//pr($userContacts);exit;
	    $this->db->update('user_contacts',$userContacts,$updateKey2);
		 
	 }//End of editGroup Function 
	 
	// --------------------------------------------------------------------
	
	
	/**
	 * Get Userslist
	 *
	 * @access	private
	 * @param	nil
	 * @return	object	object with result set
	 */
	 function getUserslist()
	 {
	 	$this->db->from('users');
		$this->db->join('roles', 'roles.id = users.role_id','left');	
			
	 	$this->db->select('users.id,roles.role_name,users.user_name,users.name,users.role_id,users.country_symbol,users.message_notify,users.password,users.email,users.city,users.state,users.profile_desc,users.rate,users.project_notify,users.user_status,users.activation_key,users.created');
		 
		$result = $this->db->get();
		return $result;
		
	 }//End of getUsers Function
	 
	 // --------------------------------------------------------------------
		
	
		
	/**
	 * Get Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getUsers($conditions=array(),$fields='')
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			
		
		$this->db->from('users');
		$this->db->join('roles', 'roles.id = users.role_id','left');	
		
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('users.id,roles.role_name,users.user_name,users.name,users.role_id,users.country_symbol,users.message_notify,users.password,users.email,users.city,users.state,users.profile_desc,users.rate,users.project_notify,users.user_status,users.activation_key,users.created,users.last_activity,users.num_reviews,users.user_rating');
		 
		$result = $this->db->get();
		return $result;
		
	 }//End of getUsers Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Get User Contacts
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getUserContacts($conditions=array(),$fields='')
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		$this->db->from('user_contacts');
		
		if($fields!='')
				$this->db->select($fields);
		else	
	 	$this->db->select('user_contacts.id,user_contacts.msn,user_contacts.gtalk,user_contacts.yahoo,user_contacts.skype');
		 
		$result = $this->db->get();
		return $result;
		
	 }//End of getUserContacts Function
	 //---------------------------------------------------------------------------------------------------------------//
	 
	 
	 /**
	 * Get User Categories
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getUserCategories($conditions=array(),$fields='')
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		$this->db->from('user_categories');
		
		if($fields!='')
				$this->db->select($fields);
		else	
	 	$this->db->select('user_categories.user_categories');
		 
		$result = $this->db->get();
		return $result;
		
	 }//End of getUserContacts Function
	 
	// --------------------------------------------------------------------
		
	/**
	 * Get Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function allowToPostProject($creator_id = false)
	 {

		
	 }//End of getCategories Function	 
	 
	 
	 /**
	 * Loads userslist for transfer money
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */	
	function userProjectdata($conditions=array())
	{	
		if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select('users.id,users.user_name,users.role_id');
		$result = $this->db->get('users');
		return $result;
				
	} //Function logout End
	
	 /**
	 * Loads userslist for favourite users and blocked users
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */	
	function getFavouritelist($conditions=array())
	{	
		if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select('user_list.id,user_list.user_name');
		$result = $this->db->get('user_list');
		return $result;
				
	} //Function logout End
	
	
	/**
	 * 
	 * Get the favourite and blocked users list from user_list atable
	 * @access	private
	 * @return	favourite and blocked users list
	 */
	 
	 function getFavourite($conditions=array())
	 {
	  	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		$this->db->from('user_list');
	 	$this->db->select('user_list.id,user_list.creator_id,user_list.user_id,user_list.user_name,user_list.user_role');
		$result = $this->db->get();
		//pr($result);
		
		return $result;
	 }//End of flash_message Function
	 
	// --------------------------------------------------------------------
	
	 
	/**
	 * insert User details for favourite users
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	 function addFavourite($insertData=array())
	 {
	 	$this->db->insert('user_list',$insertData);
	 }//End of insertUserContacts Function 
	 
	 /**
	 * Update user_list for favourite users and blockedusers
	 *
	 * @access	private
	 * @param	array	an associative array of update values
	 * @return	void
	 */
	 function updateFavourite($updateData=array(),$conditions=array())
	 {
	    if(count($conditions)>0)		
	 		$this->db->where($conditions);
		$this->db->update('user_list',$updateData);
		 
	 }//End of editGroup Function 
	 
	 
	 /**
	 * delete from user_list for favourite users and blockedusers
	 *
	 * @access	private
	 * @param	array	an associative array of delete values
	 * @return	void
	 */
	 function deleteFavourite($conditions=array())
	 {
	    if(count($conditions)>0)		
	 		$this->db->where($conditions);
		$this->db->delete('user_list');
		 
	 }//End of editGroup Function 
}
// End User_model Class
   
/* End of file User_model.php */ 
/* Location: ./app/models/User_model.php */