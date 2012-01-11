<?php
/**
 * Reverse bidding system Payment_model Class
 *
 * Handle Payment informations in database.
 *
 * @package		Reverse bidding system
 * @subpackage	Models
 * @category	Skills 
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
class Payment_model extends Model {
	 
   /**
	* Constructor 
	*
	*/
	function Payment_model() 
	  {
	  
	  	parent::Model();
      }//Controller End
	 
	// --------------------------------------------------------------------
		
	/**
	 * Get getPaymentSettings
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */ 
	 function getPaymentSettings()
	 {	 
	 	$payments = array();
	 	$this->db->select('payments.id,payments.title,payments.deposit_description,payments.withdraw_description,payments.is_deposit_enabled,payments.is_withdraw_enabled,payments.deposit_minimum,payments.withdraw_minimum,payments.mail_id,payments.url,commission,is_enable,url_status');
		$query = $this->db->get('payments');
		if($query->num_rows()>0)
		{
			foreach($query->result() as $row)
			{
				//Set Data
				$payments[$row->title]['id'] 					= $row->id;
				$payments[$row->title]['deposit_description']	= $row->deposit_description;
				$payments[$row->title]['withdraw_description']  = $row->withdraw_description;
				$payments[$row->title]['is_deposit_enabled'] 	= $row->is_deposit_enabled;
				$payments[$row->title]['is_withdraw_enabled']   = $row->is_withdraw_enabled;
				$payments[$row->title]['deposit_minimum'] 		= $row->deposit_minimum;
				$payments[$row->title]['withdraw_minimum'] 		= $row->withdraw_minimum;
				$payments[$row->title]['mail_id'] 				= $row->mail_id;
				$payments[$row->title]['url'] 					= $row->url;
				$payments[$row->title]['commission'] 			= $row->commission;
				$payments[$row->title]['is_enable'] 			= $row->is_enable;
				
                $payments[$row->title]['url_status'] 			= $row->url_status;

				
			} //Payment Gateway Traversal End			
		} else {
			die('Payment Gateway Missing');
		}
		return $payments;
		
	 }//End of getPaymentSettings Function
	 
	// --------------------------------------------------------------------
	
	function getPayment($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		$this->db->from('payments');
			
	 	$this->db->select('payments.id,payments.title,payments.deposit_description,payments.withdraw_description,payments.commission');
		 
		$result = $this->db->get();
		return $result;
		
	 }//End of getUserContacts Function
	 
	 /**
	 * Update payment settings information.
	 *
	 * @access	private
	 * @param	array	update information related to payment
	 * @return	void
	 */
	 function updatePaymentSettings($updateKey=array(),$updateData=array())
	 {
		$this->db->update('payments',$updateData,$updateKey);
		
	 }//End of updatePaymentSettings Function
	 
}
// End Payment_model Class
   
/* End of file Payment_model.php */ 
/* Location: ./app/models/Payment_model.php */