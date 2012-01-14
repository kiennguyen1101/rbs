<?php 
/**
 * Reverse bidding system Transaction_model Class
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
class Transaction_model extends Model {
	 
   /**
	* Constructor 
	*
	*/
	function Transaction_model() 
	  {
	  
	  	parent::Model();
      }//Controller End
	  
	// --------------------------------------------------------------------
		
	/**
	 * Add Transaction
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function addTransaction($insertData=array())
	 {
	 	$this->db->insert('transactions', $insertData);
		 
	 }//End of addTransaction Function
	 
	// --------------------------------------------------------------------
	
	
	/**
	 * Add escrow releaseTransaction
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function addescorwRelease($insertData=array())
	 {
		$this->db->insert('escrow_release_request', $insertData);
		 
	 }//End of addTransaction Function
	 
	// --------------------------------------------------------------------
	
	
	 /**
	 * delete ban list
	 *
	 * @access	private
	 * @param	array	an associative array of delete values
	 * @return	void
	 */
	 function deleteEscrowrelease($conditions=array())
	 {
	    if(count($conditions)>0)		
	 		$this->db->where($conditions);
		$this->db->delete('escrow_release_request');
		 
	 }//End of deleteBan Function 
	 
	 // --------------------------------------------------------------------
	 
	/**
	 * Add escrow releaseTransaction
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	  function getescrowRelease($conditions=array(),$fields='',$like=array(),$limit=array(),$order=array())
	 {
	// pr($conditions);exit;
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
		   $this->db->orderby('request_date','desc');	
		
		$this->db->join('transactions', 'transactions.id = escrow_release_request.transaction_id','left');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 	
	 			$this->db->select('escrow_release_request.id,escrow_release_request.transaction_id,escrow_release_request.status,escrow_release_request.request_date,transactions.type,transactions.creator_id,transactions.amount,transactions.reciever_id');
		$result = $this->db->get('escrow_release_request');
		return $result;
	 }//End of getTransactions Function
	 
	// --------------------------------------------------------------------


	/**
	 * Get getTransactions
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getallTransactions($conditions=array(),$fields='',$like=array(),$limit=array(),$order=array())
	 {
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
		if(is_array($order) and count($order)>0)			
		   $this->db->orderby($order[0],$order[1]);
		else   
		   $this->db->orderby('transaction_time','desc');	
		
		
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 	
	 			$this->db->select('transactions.id,transactions.type,transactions.creator_id,transactions.provider_id ,transactions.paypal_address,transactions.reciever_id,transactions.transaction_time,transactions.project_id,transactions.amount,transactions.status,transactions.description');
		$result = $this->db->get('transactions');
		return $result;
	 }//End of getTransactions Function

	 
	// --------------------------------------------------------------------	

		
	/**
	 * Get getTransactions
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getTransactions($conditions=array(),$fields='',$like=array(),$limit=array(),$order=array())
	 {
	 //pr($conditions);
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
		   $this->db->orderby('transaction_time','asc');	
		
		
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 	
	 			$this->db->select('transactions.id,transactions.type,transactions.creator_id,transactions.paypal_address,transactions.reciever_id,transactions.transaction_time,transactions.project_id,transactions.amount,transactions.update_flag,transactions.status,transactions.description');
				$result = $this->db->get('transactions');
		
		return $result;
	 }//End of getTransactions Function
	 
	 
     /**
	 * Get getTransactions
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getTransactions_with($conditions=array(),$fields='',$like=array(),$limit=array(),$order=array())
	 {
	 //pr($conditions);
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
		if(is_array($order) and count($order)>0)			
		   $this->db->orderby($order[0],$order[1]);
		else   
		   $this->db->orderby('transaction_time','desc');	
		
		
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 	
	 			$this->db->select('transactions.id,transactions.type,transactions.creator_id,transactions.paypal_address,transactions.reciever_id,transactions.transaction_time,transactions.project_id,transactions.amount,transactions.update_flag,transactions.status,transactions.description');
				$result = $this->db->get('transactions');
		
		return $result;
	 }//End of getTransactions Function

	 
	// --------------------------------------------------------------------	
	/**
	 * Get getBalance
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getBalance($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select('user_balance.id,user_balance.user_id,user_balance.amount');
		$result = $this->db->get('user_balance');
		return $result;
		
	 }//End of getBalance Function
	
	// --------------------------------------------------------------------
		
	/**
	 * Update balance of a user
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateBalance($updateKey=array(),$updateData=array())
	 {
	 	$this->db->update('user_balance',$updateData,$updateKey);
		
	 }//End of updateBalance Function  
	 
	 
	 /**
	 * add balance of a user
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function addBalance($insertData=array())
	 {
	 	$res=$this->db->insert('user_balance',$insertData);
		pr($res);
		return $res;
		
	 }//End of updateBalance Function  
	 
	 
	// --------------------------------------------------------------------
		
	/**
	 * Update Transaction
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateTransaction($updateKey=array(),$updateData=array())
	 {
	 $this->db->update('transactions',$updateData,$updateKey);
		 
	 }//End of editGroup Function 
	 
}
// End Transaction_model Class
   
/* End of file Transaction_model.php */ 
/* Location: ./app/models/Transaction_model.php */