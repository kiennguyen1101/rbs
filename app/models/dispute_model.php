<?php
/**
 * Reverse bidding system Skills_model Class
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

class Dispute_model extends Model {
	 
   /**
	* Constructor 
	*
	*/
	function Dispute_model() 
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
	 function getInfo($table,$fields,$conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select($fields);
		$result = $this->db->get($table);
		return $result;
		
	 }//End of getGroups Function
	 
	 // --------------------------------------------------------------------
	
	/**
	 * Add Project cancellation cases
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function insertProjectCase($insertData=array())
	 {
	 	$this->db->insert('project_cases', $insertData);
		 
	 }//End of insertProjectCase Function
	 
	  // --------------------------------------------------------------------
	
	/**
	 * Insert values to any table
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function insertValues($table,$insertData=array())
	 {
	 	$this->db->insert($table, $insertData);
		 
	 }//End of insertValues Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Get Project Cancellation/Dispute cases
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getProjectCases($conditions=array(),$orCond='',$fields = '',$orderby = array(),$limit=array())
	 {
	 	
		if($orCond!='')
			$this->db->where($orCond, NULL, FALSE); 
			
		//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
			
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}
			
		$this->db->from('project_cases');
		$this->db->join('projects', 'projects.id = project_cases.project_id','left');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('projects.project_name,projects.id as project_id,project_cases.id,project_cases.user_id,project_cases.created,project_cases.case_reason,projects.creator_id,projects.seller_id,project_cases.case_type,project_cases.case_reason,project_cases.payment,project_cases.problem_description,project_cases.private_comments,project_cases.parent,project_cases.updates,project_cases.status,project_cases.admin_id,project_cases.review_type');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of getProjects Function
	 
	 // --------------------------------------------------------------------
	
	/**
	 * Update projects case
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateProjectCase($id=0,$updateData=array(),$conditions=array())
	 {
	 //pr($conditions);exit;
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->update('project_cases', $updateData);
		 
	 }//End of updateProjectCase Function
	 
	  // --------------------------------------------------------------------
	
	/**
	 * delete reviews
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteReview($conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->delete('reviews');
		 
	 }//End of deleteProjects Function
}
// End Skills_model Class
   
/* End of file Skills_model.php */ 
/* Location: ./app/models/Skills_model.php */
?>