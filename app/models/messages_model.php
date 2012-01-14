<?php
/**
 * Reverse bidding system Messages_model Class
 *
 * Update site settings informations in database.
 *
 * @package		Reverse bidding system
 * @subpackage	Models
 * @category	Skills 
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
class Messages_model extends Model {
	 
   /**
	* Constructor 
	*
	*/
	function Messages_model() 
	  {
	  	parent::Model();
      }//Controller End
	
	// --------------------------------------------------------------------
		
	/* Get Users List */
	 function getUsers()   
	  {
	  	$this->db->select('users.id,users.user_name,users.role_id');
		$result = $this->db->get('users');
		return $result->result();
	  }
	
	
	/**
	 * updateMailnotification
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateMailnotification($id=0,$updateData=array(),$conditions=array())
	 {
	 //pr($conditions);exit;
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->update('messages', $updateData);
		 
	 }//End of updateMailnotification Function
	 
	 // --------------------------------------------------------------------
	
	
	
	/**
	 * Get logged user details
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getLoggedUser($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select('users.id,users.user_name,users.role_id');
		$result = $this->db->get('users');
		//pr($result);
		return $result->result();
		
	 }//End of getGroups Function
	 
	 // --------------------------------------------------------------------
	
		
	/**
	 * Get groups
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getTotalMessages($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select('messages.id');
		$result = $this->db->get('messages');
		return $result->num_rows();
		
	 }//End of getGroups Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Add Project
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function postMessage($insertData=array())
	 {
	 	$this->db->insert('messages', $insertData);
		 
	 }//End of addGroup Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Get Project Messages
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getProjectMessages($conditions=array(),$fields='',$like=array(),$limit=array(),$order=array())
	 {
	 	//Check For Conditions
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		
		if(is_array($like) and count($like)>0)
			$this->db->like($like);	
		
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}
		if(is_array($order) and count($order)>0)			
		   $this->db->orderby($order[0],$order[1]);
		else   
		   $this->db->orderby('messages.created','desc');	
		
		$this->db->from('messages');
		$this->db->join('users', 'users.id = messages.from_id','left');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('messages.id,messages.project_id,messages.from_id,messages.to_id, messages.message, messages.created,users.user_name,messages.deluserid');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of getProjectMessages Function
	 
	 /**
	 * Get Project Messages
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getProjectMessages1($conditions=array(),$fields='',$like=array(),$limit=array())
	 {
	 	//Check For Conditions
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		
		if(is_array($like) and count($like)>0)
			$this->db->like($like);	
		
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}
		
		$this->db->from('messages');
		$this->db->join('users', 'users.id = messages.to_id','left');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('messages.id,messages.project_id,messages.from_id,messages.to_id, messages.message, messages.created,users.user_name,messages.deluserid');
			
		$result = $this->db->get();
		//pr($result->result());
		return $result;
		
	 }//End of getProjectMessages Function
	 
	 
	 /**
	 * Get Project Messages
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getMessages($conditions=array(),$fields='',$like=array(),$limit=array())
	 {
	 	//Check For Conditions
	 	if(count($conditions)>0)		
	 		$this->db->or_where($conditions);
		
		if(is_array($like) and count($like)>0)
			$this->db->like($like);	
		
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}
		
		$this->db->from('messages');
		$this->db->join('users', 'users.id = messages.from_id','left');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('messages.id,messages.project_id,messages.from_id,messages.to_id, messages.message, messages.created,users.user_name,messages.deluserid');
			
		$result = $this->db->get();
		//print_r($result);
		return $result;
		
	 }//End of getProjectMessages Function
	 
	 
	function getmessage_userdetails($conditions=array(),$fields='',$like=array(),$limit=array()) 
	{
	pr($conditions);
	 //Check For Conditions
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		
		if(is_array($like) and count($like)>0)
			$this->db->like($like);	
		
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}
		
		$this->db->from('messages');
		$this->db->join('users', 'users.id = messages.from_id','left');
		$this->db->group_by('users.user_name');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('messages.id,messages.project_id,messages.from_id,messages.to_id, messages.message, messages.created,users.user_name,messages.deluserid');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
	}
}
// End Messages_model Class
   
/* End of file Messages_model.php */ 
/* Location: ./app/models/Messages_model.php */