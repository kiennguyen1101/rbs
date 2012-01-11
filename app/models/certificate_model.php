<?php 
/**
 * Reverse bidding system Account_model Class
 *
 * Handles certificate  information in database.
 *
 * @package		Reverse bidding system
 * @subpackage	Models
 * @category	Settings 
 * @author		Cogzidel Dev Team
 * @version		Version 1.6
 * @created     April 22 2010
 * @created by  Saradha.P
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

class Certificate_model extends Model {
	 
	/**
	 * Constructor 
	 *
	 */
	  function Certificate_model() 
	  {
		parent::Model();
				
      }//Controller End
	 

 /**
	 * Get the package
	 *
	 * @access	private
	 * @param	array data to insert the db.
	 */	 
	 function createPackageUser($insertData)
	 {
	 $this->db->insert('subscriptionuser', $insertData);
	 
	 }//Function createPackage End 
	 //----------------------------------------------------------
	 
	  /**
	 * Get certified user
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */	 
	 
	 function getCertificateUser($condition=array())
	 {
	 
		 if(count($condition)>0)		
				$this->db->where($condition);
				 
			$this->db->select('subscriptionuser.id,subscriptionuser.username,subscriptionuser.package_id,subscriptionuser.valid,subscriptionuser.amount,subscriptionuser.flag,subscriptionuser.created');
			$result = $this->db->get('subscriptionuser');
			
			return $result;
	 }
	 //Function getCertificateUser End
	 //----------------------------------------------------------------------
	 
}
// End certificate_model Class
   
/* End of file certificate_model.php */ 
/* Location: ./app/models/certificate_model.php */
?>