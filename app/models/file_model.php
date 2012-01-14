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
class File_model extends Model {
	 
   /**
	* Constructor 
	*
	*/
	function File_model() 
	  {
	  	parent::Model();
      }//Controller End
	
	// --------------------------------------------------------------------
		
	/**
	 * Get groups
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getFile($conditions=array())
	 {
		if(count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$result = $this->db->get('files');
			return $result;
		
	 }//End of getGroups Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Delete files from uplaod file list
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function deleteFile($conditions=array())
	 {
		if(count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$result = $this->db->delete('files');
			return $result;
		
	 }//End of getGroups Function
	 
	 // --------------------------------------------------------------------	
		
	/**
	 * Get groups
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getFileSize($conditions=array())
	 {
		if(count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->select_sum('file_size','totalsize');
		$result = $this->db->get('files');
			return $result;
		
	 }//End of getGroups Function
	 
	 // --------------------------------------------------------------------	
		
	/**
	 * Add Files	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function postFile($insertData=array())
	 {
	 	$this->db->insert('files', $insertData);
		return;
		 
	 }//End of addGroup Function
	 
	 // --------------------------------------------------------------------
		
		// --------------------------------------------------------------------
		
	/**
	 * Update Files
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateFile($id=0,$updateData=array())
	 {
	 	$this->db->where('faqs.id', $id);
	 	$this->db->update('faqs', $updateData);
		 
	 }//End of updateFaq Function 

	 
}
// End Messages_model Class
   
/* End of file Messages_model.php */ 
/* Location: ./app/models/Messages_model.php */