<?php
/**
 * Reverse bidding system Page_model Class
 *
 * Help to handle tables related to static pages of the system.
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

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
    
 */

 class Page_model extends Model {
	 
	/**
	 * Constructor 
	 *
	 */
	  function Page_model() 
	  {
		parent::Model();
				
      }//Controller End
	 
	// --------------------------------------------------------------------
		
	/**
	 * delete page
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deletePage($id=0,$conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
		 $this->db->delete('page');
		 
	 }//End of addFaqCategory Function
	 
	// --------------------------------------------------------------------
	


		

	/**
	 * Get Static Pages
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
		// Puhal changes Start. For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2)	 
	 function getPages($conditions=array(),$like=array(),$like_or=array())
	 {
	 	//Check For like statement
	 	if(is_array($like) and count($like)>0)		
	 		$this->db->like($like);
			
		//Check For like statement
	 	if(is_array($like_or) and count($like_or)>0)		
	 		$this->db->or_like($like_or);
// Puhal changes End. For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2)			
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		
		$this->db->from('page');
	 	$this->db->select('page.id,page.url,page.created,page.name,page.page_title,page.content,page.is_active');
		$result = $this->db->get();
		return $result;
		
	 }//End of getFaqs Function

	 
	 // --------------------------------------------------------------------
		
	/**
	 * Add  Static Page
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function addpage($insertData=array())
	 {
	 	$this->db->insert('page', $insertData);
		 
	 }//End of addFaqCategory Function

	 
	// --------------------------------------------------------------------
		
	/**
	 * Update Static Page
	 *
	 * @access	private
	 * @param	array	an associative array - for update key values
	 * @param	array	an associative array of update data
	 * @return	void
	 */
	 function updatePage($updateKey=array(),$updateData=array())
	 {
	 	 $this->db->update('page',$updateData,$updateKey);
		 
	 }//End of updateFaq Function 
	 

}
// End Page_model Class
   
/* End of file Page_model.php */ 
/* Location: ./app/models/Page_model.php */