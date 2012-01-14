<?php
/**
 * Reverse bidding system Auth_model Class
 *
 * This Model will take care of handling Package details.
 *
 * @package		Reverse bidding system
 * @subpackage	Models
 * @category	package_model 
 * @author		
 * @version		Version 1.6
 * @created     April 27 2010
 * @created by  Saradha.P
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

class Package_model extends Model {
	 
	/**
	 * Constructor 
	 *
	 */
	  function package_model() 
	  {
		parent::Model();
				
      }//Controller End
	  
	  
	  /**
	 * insert the new package
	 *
	 * @access	private
	 * @param	array	data to insert the db
	 */
	   function createPackage($insertData=array())
		 {
			$this->db->insert('packages', $insertData);
		 }//End of createPackage Function
		 
	// --------------------------------------------------------------------
		 
	 /**
	 * Get the package
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */	 
	function getPackages($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select('packages.id,packages.package_name,packages.description,packages.start_date,packages.end_date,packages.total_days,packages.amount,packages.isactive');
		$result = $this->db->get('packages');
		return $result;
		
	 }//End of getPackage Function
	//-------------------------------------------------------------------------------- 
	 
	 
	 /**
	 * Get Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getUsers($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->distinct('id,user_name');
		$result = $this->db->get('users');
		return $result;
		
	 }//End of getUsers Function
	//-------------------------------------------------------------------------------------------------
	
	/**
	 * get Package 
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */ 
	 
	 function getPackage($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array())
	 {
	 	//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		//Check For like statement
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
		//pr($orderby);
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
			 
	 	$this->db->select('packages.id,packages.package_name,packages.description,packages.start_date,packages.end_date,packages.total_days,packages.amount,packages.isactive');
		$result = $this->db->get('packages');
		return $result;
		
	 }//End of getPackages Function
	//-------------------------------------------------------------------------------------------------- 
	 
	
	
	/**
	 * Update the package
	 *
	 * @access	private
	 * @param	array  of data to update the db.	
	 */ 
	function updatePackages($id=0,$updateData=array(),$conditions=array())
	 {
	 	if(count($conditions)>0 && is_array($conditions))		
	 		$this->db->where($conditions);
	    else		
		    $this->db->where('id', $id);
	 	$this->db->update('packages', $updateData);
		 
	 }//End of updatePackages Function
	//---------------------------------------------------------------------------------------
	 
 
 /**
	 * delete the package
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */

 function deletePackages($conditions=array())
	 {
	    //Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->delete('packages');
		return $this->db->affected_rows(); 
		 
 	 }//Function deletePackage End
	//-------------------------------------------------------------------------------------


/**
	 * insert the subscription user 
	 *
	 * @access	private
	 * @param	array	of data to insert the db
	 */	 
	 
	  function addsubscription($insertData=array())
	 {
	
	 	$this->db->insert('subscriptionuser', $insertData);
	 }//End of add subscriptionuser  Function
	 
     //-----------------------------------------------------------------	
	 
	 
	/**
	 * Get Subscription Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */  
	 function getSubscriptionUser($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			
			$this->db->from('subscriptionuser');
			$this->db->join('packages', 'packages.id = subscriptionuser.package_id','left');
			$this->db->join('users','users.id=subscriptionuser.username','left');
			 
	 	$this->db->select('subscriptionuser.id,subscriptionuser.package_id,packages.package_name,subscriptionuser.username,subscriptionuser.valid,subscriptionuser.amount,subscriptionuser.created,users.user_name');
		$result = $this->db->get();
		return $result;
		
	 }//End of getSubscriptionusers Function
	//--------------------------------------------------------------------------------------------
	
	/**
	 * Get Subscription Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */ 
	 
	 function getSubscription_User($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array())
	 {
	 	//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		//Check For like statement
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
		//pr($orderby);
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
			
			$this->db->from('subscriptionuser');
			$this->db->join('packages', 'packages.id = subscriptionuser.package_id','left');
			$this->db->join('users','users.id=subscriptionuser.username','left');
			 
	 	$this->db->select('subscriptionuser.id,subscriptionuser.package_id,packages.package_name,subscriptionuser.username,subscriptionuser.valid,subscriptionuser.amount,subscriptionuser.created,users.user_name');
		$result = $this->db->get();
		return $result;
		
	 }//End of getsubscription_users Function
	//--------------------------------------------------------------------------
	
	/**
	 * Update Subscription users
	 *
	 * @access	private
	 * @param	array	of data to update the db.
	 */ 
	 function updateSubscritionUser($id=0,$updateData=array(),$conditions=array())
	 {
	 	if(count($conditions)>0 && is_array($conditions))		
	 		$this->db->where($conditions);
	    else		
		    $this->db->where('id', $id);
	 	$this->db->update('subscriptionuser', $updateData);
		 
	 }//End of updateSubscriptionUser Function
	//-----------------------------------------------------------------
	
	/**
	 * deleteSubscriptionuser 
	 *
	 * @access	private
	 * @param	array	conditions to delete the data in the db.
	 * @return	object	object with result set deleted rows
	 */ 
	 
	 function deleteSubscription($conditions=array())
	 {
	    //Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->delete('subscriptionuser');
		return $this->db->affected_rows(); 
		 
 	 }//Function deletesubscription End
	 
	//-------------------------------------------------------------------------------
	
	/**
	 * Get Subscription Payment
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */ 
	 
	 function getSubscriptionpayment($conditions=array(),$limit=array(),$orderby = array())
	 {
		if(is_array($conditions) and count($conditions)>0)	
	 		$this->db->where($conditions);
			
			if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}
		//pr($orderby);
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
			
			
			$this->db->from('subscriptionuser');
			$this->db->join('packages', 'packages.id = subscriptionuser.package_id','left');
			$this->db->join('transactions', 'transactions.package_id=subscriptionuser.package_id','left');
			$this->db->join('users','users.id=subscriptionuser.username','left');
			 
			
		    /*$this->db->from('subscriptionuser');
			$this->db->join('packages', 'packages.id = subscriptionuser.package_id','left');
			$this->db->join('transactions', 'transactions.package_id=subscriptionuser.package_id','left');
			$this->db->join('users','users.id=transactions.creator_id','left');*/
			 
	 	    $this->db->select('transactions.id,transactions.package_id,packages.package_name,packages.total_days,transactions.amount,packages.end_date,users.user_name'); 
	 	
		$result = $this->db->get();
		return $result;
	 }//Function getSubscriptionpayment End
	 //----------------------------------------------------------------------------
	 
	 /**
	 * Get Subscription payment
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	  
	function getSubscriptionpayments($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		//Check For like statement
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
		//pr($orderby);
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
			
			$this->db->from('packages');
			$this->db->join('transactions', 'transactions.package_id=packages.id','left');
			$this->db->join('users','users.id=transactions.creator_id','left');
			 
	$this->db->select('transactions.id,transactions.package_id,packages.package_name,packages.total_days,transactions.amount,packages.end_date,users.user_name'); 
	 	
		$result = $this->db->get();
		
		return $result;
		
	 }//End of getsubscription payment Function 
	 
 //------------------------------------------------------------------------
	} 
	// End package_model Class
   
/* End of file package_model.php */ 
/* Location: ./app/models/package_model.php */

	  ?>