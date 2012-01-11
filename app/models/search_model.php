<?php 

/**

 * Reverse bidding system Search_model Class

 *

 * Update site settings informations in database.

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

class Search_model extends Model {

	 

   /**

	* Constructor 

	*

	*/

	function Search_model() 

	  {

	  	parent::Model();

      }//Controller End

	 

 	// --------------------------------------------------------------------

		

	/**

	 * Get Projects

	 *	

	 * @access	private

	 * @param	array	conditions to fetch data

	 * @return	object	object with result set

	 */

	 function getProjects($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array(),$like1=array())

	 {

	 	//Check For Conditions

	 	if(is_array($conditions) and count($conditions)>0)		

	 		$this->db->where($conditions);

		

		//Check For like statement

	 	if(is_array($like) and count($like)>0)

			$this->db->or_like($like);	

		if(is_array($like1) and count($like1)>0 and $like1 !='')

			$this->db->like($like1);	

		//pr($like1);	

		

		//Check For Limit	

		if(is_array($limit))		

		{

			if(count($limit)==1)

	 			$this->db->limit($limit[0]);

			else if(count($limit)==2)

				$this->db->limit($limit[0],$limit[1]);

		}	

		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);

		$this->db->from('projects');

		$this->db->join('users', 'users.id = projects.creator_id','left');

		//Check For Fields	 

		if($fields!='')

				$this->db->select($fields);

		else 		

	 		$this->db->select('projects.id,projects.project_name,projects.description,projects.budget_min,projects.project_status,projects.budget_max,projects.project_categories,projects.creator_id,projects.is_feature,projects.is_urgent,projects.is_hide_bids,projects.created,projects.is_private,projects.private_users,users.user_name');

			

		$result = $this->db->get();

		
		return $result;

		

	 }//End of getProjects Function

	 

	/**

	 * getUsers

	 *	

	 * @access	private

	 * @param	array	conditions to fetch data

	 * @return	object	object with result set

	 */

	 function getUsers($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array(),$like1=array())

	 {

	 	//Check For Conditions

	 	if(is_array($conditions) and count($conditions)>0)		

	 		$this->db->where($conditions);

			

		//Check For like statement

	 	if(is_array($like) and count($like)>0)

			$this->db->or_like($like);	

		if(is_array($like1) and count($like1)>0)

			$this->db->like($like1);		

		//$this->db->like($like);	

		//pr($like1);

		//Check For Limit	

		if(is_array($limit))		

		{

			if(count($limit)==1)

	 			$this->db->limit($limit[0]);

			else if(count($limit)==2)

				$this->db->limit($limit[0],$limit[1]);

		}	

		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);

		$this->db->from('users');
		$this->db->join('user_categories', 'user_categories.user_id = users.id','left');	
		
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('users.id,user_categories.user_categories,users.user_name,users.name,users.role_id,users.country_symbol,users.message_notify,users.password,users.email,users.city,users.state,users.profile_desc,users.rate,users.project_notify,users.user_status,users.activation_key,users.created,users.last_activity,users.num_reviews,users.user_rating');
		 
		$result = $this->db->get();
		

		return $result;

		

	 }//End of getUsers Function
	 
	 

     
	 /**

	 * getUsers

	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */

	 function getSearch($conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
	    $this->db->from('search_keyword');
		$this->db->group_by('keyword');
		$this->db->select('search_keyword.id,search_keyword.keyword,search_keyword.type');
		$result = $this->db->get();
		
		return $result;
	 } //End Function
	 
	/**

	 * getUsers

	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */

	 function getCategory($conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
	    $this->db->from('categories');
		
		$this->db->select('categories.id,categories.category_name');
		$result = $this->db->get();
		
		return $result;
	 } //End Function
	 
	 
	/**
	 * insertSearch

	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */

	 function insertSearch($data=array())
	 {
	 	$this->db->insert('search_keyword',$data);
	 } //End Function
	  
	  
	 /**
	 * getUsers

	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */

	 function deleteSearch($conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		$this->db->delete('search_keyword');
	 } //End Function 
	 
	 
}

// End Search_model Class

   

/* End of file Search_model.php */ 

/* Location: ./app/models/Search_model.php */