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
class Skills_model extends Model {
	 
   /**
	* Constructor 
	*
	*/
	function Skills_model() 
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
	 function getGroups($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			 
	 	$this->db->select('groups.id,groups.group_name,groups.descritpion,groups.created,groups.modified');
		$result = $this->db->get('groups');
		return $result;
		
	 }//End of getGroups Function
	 
	 function getGroup($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array())
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
			 
	 	$this->db->select('groups.id,groups.group_name,groups.descritpion,groups.created,groups.modified');
		$result = $this->db->get('groups');
		return $result;
		
	 }//End of getGroups Function
	 
	// --------------------------------------------------------------------
	
	/**
	 * Get bookmark
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getBookmark($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array())
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
			
		$this->db->from('bookmark');
		$this->db->join('projects', 'bookmark.project_id = projects.id','left');
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('projects.id,projects.project_name,projects.project_status,projects.description,projects.budget_min,projects.budget_max,projects.project_categories,projects.creator_id,projects.is_feature,projects.is_urgent,projects.is_hide_bids,projects.created,projects.is_private,projects.is_private,private_users,projects.enddate,projects.seller_id,projects.project_award_date,projects.project_award_date,projects.checkstamp,projects.provider_rated,projects.buyer_rated,projects.project_paid,projects.flag,bookmark.id,bookmark.creator_name,bookmark.project_id,bookmark.project_name,bookmark.project_creator');
			
		$result = $this->db->get();
		return $result;		
		
	 }//End of getGroups Function
	// --------------------------------------------------------------------
	
		
	/**
	 * Get getGroupsWithCategory
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getGroupsWithCategory()
	 {
	 	//Get Groups
		$query 							=$this->getGroups();
		
		//Return Data
		$data 							=array();
		
	 	if($query->num_rows()>0)
		{
			$i=0;
			foreach($query->result() as $row)
			{
				$data[$i]['group_id']		= $row->id;
				$data[$i]['group_name']		= $row->group_name;
				$data[$i]['descritpion']	= $row->descritpion;
				$data[$i]['created']		= $row->created;
				$data[$i]['modified']		= $row->modified;
				$data[$i]['num_categories']	= 0;
				
				$conditions  		= array('group_id'=>$row->id);
				$query_categories 	= $this->getCategories($conditions);
				
				
				//Check for query categories availability
				if($query_categories->num_rows()>0)
				{
					$data[$i]['num_categories']	= $query_categories->num_rows();
					$data[$i]['categories'] = $query_categories;
					
				} //If End - Checks For categories availability
				$i++;
			}
		}//If End - check for group avaliability

		return $data;
	 }//End of getGroupsWithCategory Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Add group
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function addGroup($insertData=array())
	 {
	 	$this->db->insert('groups', $insertData);
		 
	 }//End of addGroup Function
	 
 	// --------------------------------------------------------------------
	
	/**
	 * delete projects
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteGroups($id=0,$conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->delete('groups');
		 
	 }//End of deleteProjects Function
	 // --------------------------------------------------------------------
		
	/**
	 * Convert Categories Id to name
 	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function convertCategoryIdsToName($categoryIds=array())
	 {
	 	$data = array();
		if(count($categoryIds)>0)
		{
			foreach($categoryIds as $categoryId)
			{
				$condition 	= array('categories.id'=>$categoryId);
				$fields 	='categories.id,categories.category_name';
				$query 		= $this->getCategories($condition,$fields);
				$row 		=  $query->row(); 
				
				if($query->num_rows() > 0)
				$data[$categoryId] = $row->category_name;
				//pr($data[$categoryId]);
			}//ForEach End -Traverse Categories		
		}	 	
		return $data;			 
	 }//End of addGroup Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Add Project
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function createProject($insertData=array())
	 {
	 	$this->db->insert('projects', $insertData);
		 
	 }//End of addGroup Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Add Popular searches
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function addPopularSearch($insertData=array())
	 {
	 	$this->db->insert('popular_search', $insertData);
		 
	 }//End of addPopularSearch Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Get Popular searches
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function getPopularSearch($type='')
	 {
	 	$query = "SELECT keyword,count(*) as cnt FROM popular_search WHERE `type` = '".$type."' GROUP BY `keyword` ORDER BY cnt DESC LIMIT 10";
		
	  	$que = $this->db->query($query);
	 	
		return $que;
		 
	 }//End of addPopularSearch Function
	 
	// --------------------------------------------------------------------
	
	/**
	 * Get ProjectsLists for transfer money
	 *	
	 * @access	private
	 * @param	nil
	 * @return	object	object with result set
	 */
	 function getpreviewProjects($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		$this->db->from('projects_preview');
		$this->db->select('projects_preview.id,projects_preview.project_name,projects_preview.project_status,projects_preview.description,projects_preview.budget_min,projects_preview.budget_max,projects_preview.project_categories,projects_preview.creator_id,projects_preview.is_feature,projects_preview.is_urgent,projects_preview.is_hide_bids,projects_preview.created,projects_preview.enddate,projects_preview.seller_id,projects_preview.project_award_date,projects_preview.flag,projects_preview.contact,projects_preview.salary,projects_preview.salarytype');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of getProjects Function
	 
	 // --------------------------------------------------------------------
	
	
	
	/**
	 * Add Project
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function previewProject($insertData=array())
	 {
	 	$this->db->insert('projects_preview', $insertData);
		 
	 }//End of addGroup Function
	 
	// --------------------------------------------------------------------
	
	/**
	 * delete projects
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deletepreviewProject($conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
	 	$this->db->delete('projects_preview');
		 
	 }//End of deleteProjects Function
	 
	 // --------------------------------------------------------------------
	
	/**
	 * Add draft Project
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function draftProject($insertData=array())
	 {
	 	$this->db->insert('draftprojects', $insertData);
		 
	 }//End of addGroup Function
	 
	// --------------------------------------------------------------------
	
	/**
	 * Add Bids
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function createBids($insertData=array())
	 {
	 	$this->db->insert('bids', $insertData);
		 
	 }//End of addGroup Function
	 
	 // --------------------------------------------------------------------
	
	/**
	 * Update Bids
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateBids($id=0,$updateData=array(),$conditions=array())
	 {
	 	if(count($conditions)>0 && is_array($conditions))		
	 		$this->db->where($conditions);
	    else		
		    $this->db->where('id', $id);
	 	$this->db->update('bids', $updateData);
		 
	 }//End of addGroup Function
	 
	// --------------------------------------------------------------------
		
	/**
	 * Edit group
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateGroup($id=0,$updateData=array())
	 {
	 	$this->db->where('groups.id', $id);
	 	$this->db->update('groups', $updateData);
		 
	 }//End of editGroup Function
	 
	// --------------------------------------------------------------------
		
	/**
	 * Update category
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateCategory($id=0,$updateData=array())
	 {
	 	$this->db->where('categories.id', $id);
	 	$this->db->update('categories', $updateData);
		 
	 }//End of editGroup Function 
	 
 	 // --------------------------------------------------------------------
	
	/**
	 * delete projects
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteCategory($id=0,$conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->delete('categories');
		 
	 }//End of deleteProjects Function
	 // --------------------------------------------------------------------
		
	/**
	 * Add category
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function addCategory($insertData=array())
	 {
	 	$this->db->insert('categories', $insertData);
		 
	 }//End of getGroups Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Get Categories
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getCategories($conditions=array(),$fields='')
	 {
	 	//Check For Conditions
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		
		$this->db->from('categories');
		$this->db->join('groups', 'groups.id = categories.group_id','left');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('categories.id,categories.group_id,categories.category_name,groups.group_name, categories.description, categories.page_title, categories.meta_keywords, categories.meta_description, categories.is_active, categories.created, categories.modified');
			
		$result = $this->db->get();
		return $result;
		
	 }//End of getCategories Function
	 
	 function getCategory($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby=array())
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
			
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
		
		$this->db->from('categories');
		$this->db->join('groups', 'groups.id = categories.group_id','left');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('categories.id,categories.group_id,categories.category_name,groups.group_name, categories.description, categories.page_title, categories.meta_keywords, categories.meta_description, categories.is_active, categories.created, categories.modified');
			
		$result = $this->db->get();
		return $result;
		
	 }//End of getCategories Function
	 
 	// --------------------------------------------------------------------
		
	/**
	 * Get Projects
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getProjects($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array())
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
			
		$this->db->from('projects');
		$this->db->join('users', 'users.id = projects.creator_id','left');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('projects.id,projects.project_name,projects.project_status,projects.description,projects.budget_min,projects.budget_max,projects.project_categories,projects.creator_id,projects.is_feature,projects.is_urgent,projects.is_hide_bids,projects.created,projects.attachment_name,projects.attachment_url,users.user_name,projects.enddate,projects.seller_id,projects.project_award_date,projects.project_award_date,projects.contact,projects.salary,projects.flag,projects.escrow_due,users.id as userid,projects.checkstamp,projects.provider_rated,projects.buyer_rated,projects.project_paid,projects.is_private,projects.private_users,users.user_rating,users.num_reviews,users.email');	
		$result =$this->db->get();
		
		return $result;
		
	 }//End of getProjects Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Get Projects for RSS
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getRssProjects($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array(),$orlike=array())
	 {
		//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		//Check For like statement
	 	if(is_array($like) and count($like)>0)		
	 		$this->db->like($like);	
		
		//Check For orlike statement
	 	if(is_array($orlike) and count($orlike)>0){
			$app = '';
			foreach($orlike as $orl){
				$this->db->or_like('projects.project_categories',$orl);
			}
		}
		//echo $app;
		//exit;
		
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
			
		$this->db->from('projects');
		$this->db->join('users', 'users.id = projects.creator_id','left');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('projects.id,projects.project_name,projects.project_status,projects.description,projects.budget_min,projects.budget_max,projects.project_categories,projects.creator_id,projects.is_feature,projects.is_urgent,projects.is_hide_bids,projects.created,users.user_name,projects.enddate,projects.seller_id,projects.project_award_date,projects.project_award_date,users.id as userid,projects.checkstamp,projects.provider_rated,projects.buyer_rated,projects.project_paid,users.user_rating,users.num_reviews');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of getProjects Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Get Projects
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getLatestProjects($limit=array())
	 {
	 	//Check For Conditions
	$conditions = array('projects.project_status' => '0','flag'=>'0');
	 	$this->db->where($conditions);
			
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}	
		//pr($orderby);
		$this->db->order_by('projects.created','desc');
			
		$this->db->from('projects');
		$this->db->join('users', 'users.id = projects.creator_id','left');
		//Check For Fields	 
	 	$this->db->select('projects.id,projects.project_name,projects.project_status,projects.description,projects.budget_min,projects.budget_max,projects.project_categories,projects.creator_id,projects.is_feature,projects.is_urgent,projects.is_hide_bids,projects.created,users.user_name,projects.enddate,projects.seller_id,projects.project_award_date,projects.project_award_date,users.id as userid,projects.checkstamp,projects.provider_rated,projects.buyer_rated,projects.project_paid,projects.is_private,projects.private_users,users.user_rating,users.num_reviews');
			
		$result = $this->db->get();
		
		return $result;
		
	 }//End of getProjects Function

	 /**
	 * Update projects
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateDraftProject($updateData=array(),$conditions=array())
	 {
	// pr($conditions);exit;
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->update('draftprojects', $updateData);
		 
	 }//End of updateProjects Function
	 // --------------------------------------------------------------------

	 
	 // --------------------------------------------------------------------
	
	/**
	 * delete projects
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteDraftProject($conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->delete('draftprojects');
		 
	 }//End of deleteProjects Function
	 


	 // --------------------------------------------------------------------
	
	 

	 

	 /**
	 * Get Projects
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getDraft($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array())
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
			
		$this->db->from('draftprojects');
		$this->db->join('users', 'users.id = draftprojects.creator_id','left');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('draftprojects.id,draftprojects.project_name,draftprojects.project_status,draftprojects.description,draftprojects.budget_min,draftprojects.budget_max,draftprojects.project_categories,draftprojects.creator_id,draftprojects.is_feature,draftprojects.is_urgent,draftprojects.is_hide_bids,draftprojects.created,users.user_name,draftprojects.enddate,draftprojects.seller_id,draftprojects.flag,draftprojects.salary,draftprojects.contact,draftprojects.salarytype,users.id as userid,draftprojects.checkstamp,draftprojects.provider_rated,draftprojects.buyer_rated,draftprojects.project_paid,draftprojects.is_private,draftprojects.private_users');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of getDraft Function
	 
	 // --------------------------------------------------------------------
	 
	 
		
	/**
	 * Get Projects
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getProjectsByProvider($conditions=array(),$fields='',$like=array(),$limit=array())
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
			
		$this->db->from('projects');
		$this->db->join('users', 'users.id = projects.seller_id','left');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('projects.id,projects.project_name,projects.project_status,projects.description,projects.budget_min,projects.budget_max,projects.project_categories,projects.creator_id,projects.is_feature,projects.is_urgent,projects.is_hide_bids,projects.created,users.user_name,projects.enddate,projects.seller_id,projects.project_award_date,users.id as userid,projects.checkstamp,projects.provider_rated,projects.buyer_rated,projects.project_paid,projects.is_private,projects.flag');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of getProjects Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * getReviews
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getReviews($conditions=array(),$fields='',$like=array(),$limit=array())
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
			
		$this->db->from('reviews');
		$this->db->join('users', 'users.id = reviews.buyer_id','inner');
		$this->db->join('projects', 'projects.id = reviews.project_id','inner');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('reviews.id,users.user_name,projects.project_name,projects.id as projectid,projects.created,reviews.rating,reviews.provider_id,projects.project_status,reviews.review_time,reviews.buyer_id,,reviews.comments');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of getReviews Function
	 
	 // --------------------------------------------------------------------
	
	
	/**
	 * Get Top seller List
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 
	 function getTopsellers($limit=array())
	 {
	 	$conditions = array('role_id' => '2','users.user_status' => '1');
	 	$users = $this->user_model->getUsers($conditions);

		$uarray = array();
		$i = 0;
		foreach($users->result() as $user){
			if($user->user_rating != 0)
			$uarray[$user->id] = $user->user_rating * $user->num_reviews;
			$i++;
		}
		arsort($uarray);
		return $uarray;
	 }//End of getTopsellers Function
	 
	 // --------------------------------------------------------------------
	
	
	/**
	 * Get Top seller List
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getTopBuyers()
	 {
	 	
		$conditions = array('role_id' => '1','users.user_status' => '1');
	 	$users = $this->user_model->getUsers($conditions);
		
		$uarray = array();
		$i = 0;
		foreach($users->result() as $user){
			if($user->user_rating != 0)
			$uarray[$user->id] = $user->user_rating * $user->num_reviews;
			$i++;
		}
		arsort($uarray);
		return $uarray;
		
	 }//End of getTopBuyers Function
	 
 	// --------------------------------------------------------------------
	
	
	
	 /*?>//
	 //* Get ProjectsLists
	// *	
	 //* @access	private
	 //@param	nil
	 // @return	object	object with result set
	 
	 function getProjectslist()
	 {
	 	$this->db->from('projects');
		$this->db->select('projects.id,projects.project_name,projects.project_status,projects.description,projects.budget_min,projects.budget_max,projects.project_categories,projects.creator_id,projects.is_feature,projects.is_urgent,projects.is_hide_bids,projects.created,projects.enddate,projects.seller_id');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of getProjects Function
	 
	 // --------------------------------------------------------------------<?php */ 
	 
	 
	 /**
	 * Get ProjectsLists for transfer money
	 *	
	 * @access	private
	 * @param	nil
	 * @return	object	object with result set
	 */
	 function getUsersproject($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		$this->db->from('projects');
		$this->db->select('projects.id,projects.project_name,projects.project_status,projects.description,projects.budget_min,projects.budget_max,projects.project_categories,projects.creator_id,projects.is_feature,projects.is_urgent,projects.is_hide_bids,projects.created,projects.enddate,projects.seller_id,projects.project_award_date,projects.flag,projects.contact,projects.salary,projects.salarytype,projects.is_private,projects.private_users');
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of getProjects Function
	 
	 // --------------------------------------------------------------------
	
	function getUsersproject_with($cod)
	 {
			if(isset($cod))
			{
			 $this->db->where($cod); 
			}
		$this->db->from('projects');
		$this->db->select('projects.id,projects.project_name,projects.project_status,projects.description,projects.budget_min,projects.budget_max,projects.project_categories,projects.creator_id,projects.is_feature,projects.is_urgent,projects.is_hide_bids,projects.created,projects.enddate,projects.seller_id,projects.project_award_date,projects.flag,projects.contact,projects.salary,projects.salarytype,projects.is_private,projects.private_users');
		$result = $this->db->get();
		return $result;
		
	 }//End of getProjects Function
	/**
	 * Get user wise mail inbox
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getInboxmail($conditions=array())
	 {
	 	//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		
			
		$this->db->from('projects');
		
			
	 	$this->db->select('projects.id,projects.project_name,projects.project_status,projects.description,projects.budget_min,projects.budget_max,projects.project_categories,projects.creator_id,projects.is_feature,projects.is_urgent,projects.is_hide_bids,projects.created,projects.enddate,projects.seller_id,projects.project_award_date');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of getProjects Function
	 
	 // --------------------------------------------------------------------
	
		
	/**
	 * Get project details
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getProjectByBid($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby=array())
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
		
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
			
		$this->db->from('bids');
		$this->db->join('projects', 'projects.id = bids.project_id','inner');
		$this->db->join('users', 'users.id = bids.user_id','inner');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('projects.project_name,projects.checkstamp,projects.is_urgent,projects.is_feature,projects.is_private,projects.private_users,users.email,projects.id,projects.project_status,projects.created,projects.seller_id,bids.escrow_flag,bids.id as bidid');
			
		$result = $this->db->get();
		return $result;
		
	 }//End of getProjectByBid Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * Get Bids
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getBidsproject()
	 {
	 	//Get thebid project details for all projects			
		$this->db->from('bids');
		$this->db->select('bids.id,bids.project_id,bids.user_id,bids.bid_days,bids.bid_days,bids.bid_hours,bids.bid_amount,bids.bid_time,bids.bid_desc');
			
		$result = $this->db->get();
		return $result;
		
	 }//End of getProjects Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * Get Bids
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getBids($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby=array())
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
			
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
			
		$this->db->from('bids');
		$this->db->join('users', 'users.id = bids.user_id','inner');
		$this->db->join('projects', 'projects.id = bids.project_id','inner');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('bids.id,bids.project_id,bids.user_id,bids.bid_days,bids.bid_days,bids.bid_hours,bids.bid_amount,bids.bid_time,bids.bid_desc,bids.escrow_flag,users.user_name,users.id as uid,users.user_rating,users.num_reviews,projects.is_hide_bids,projects.creator_id');
			
		$result = $this->db->get();
		
		return $result;
		
	 }//End of getProjects Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * Get RatingHold
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getRatingHold($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby=array())
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
			
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
			
		$this->db->from('rating_hold');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('rating_hold.rating,rating_hold.user_id,rating_hold.project_id');
			
		$result = $this->db->get();
		return $result;
		
	 }//End of getProjects Function
	 
	  // --------------------------------------------------------------------
	 
	 /**
	 * Get Bids
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getSumBids($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby=array())
	 {
	 
		//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->from('bids');
				
	 	$this->db->select_sum('bid_amount');
			
		$result = $this->db->get();

		return $result;
		
	 }//End of getSumBids Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * getTotalReviews
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getSumReviews($conditions=array())
	 {
	 
		//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->from('reviews');
				
	 	$this->db->select_sum('rating');
			
		$set = $this->db->get();
		
		$row = $set->row();

		return $row->rating;
		
	 }//End of getTotalReviews Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * Get Bids
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getNumBids($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby=array())
	 {
	 
		//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->from('bids');
		$num = $this->db->count_all_results();
		return $num;
		
	 }//End of getSumBids Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * Get Lowest Bid
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getLowestBid($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby=array())
	 {
	 
		//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[10]);
		}	
			
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
			
		$this->db->from('bids');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('bids.bid_amount');
		$result = $this->db->get();
		return $result;
		
	 }//End of getLowestBid Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * Get Bids
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function awardProject($conditions=array())
	 {
	 
		//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->from('bids');
		$result = $this->db->get();
		$row = $result->row();
		$prog_id = $row->user_id;
		$checkstamp = md5("Cogzidel:".$prog_id.":".$row->project_id.":".microtime());
		//echo $checkstamp;exit;
		$data = array(
               'seller_id' => $prog_id,
               'checkstamp' => $checkstamp,
               'project_status' => '1',
			   'project_award_date' => get_est_time()
            );
		//print_r($data);exit;
		$this->db->where('projects.id', $row->project_id);
		$this->db->update('projects', $data);
		return $this->db->affected_rows(); 
		//return $num;
		
	 }//End of getSumBids Function 
	 
	 // --------------------------------------------------------------------
		
	/**
	 * accpetProject
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function accpetProject($updateKey=array(),$updateData=array())
	 {
	    $this->db->update('projects',$updateData,$updateKey);
		return $this->db->affected_rows(); 
		 
 	 }//End of accpetProject Function 
	 
	 // --------------------------------------------------------------------
		
	/**
	 * deleteBid
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteBid($conditions=array())
	 {
	    //Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->delete('bids');
		return $this->db->affected_rows(); 
		 
 	 }//End of deleteBid Function
	 
	 
	 function deleteBids($id=0,$conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->delete('bids');
		 
	 }//End of deleteProjects Function
	 
	  // --------------------------------------------------------------------
	
	/**
	 * insert Reviews
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	 function createReview($insertData=array())
	 {
	 	$this->db->insert('reviews',$insertData);
		return $this->db->insert_id();
	 }//End of insertUserContacts Function
	 
	  // --------------------------------------------------------------------
	
	/**
	 * Update projects
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateProjects($id=0,$updateData=array(),$conditions=array())
	 {
	 //pr($conditions);exit;
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->update('projects', $updateData);
		 
	 }//End of updateProjects Function
	 
	 // --------------------------------------------------------------------
	
	
	/**
	 * delete projects
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteProjects($id=0,$conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->delete('projects');
		 
	 }//End of deleteProjects Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * delete projects
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deletedraftprojects($conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
	 	$this->db->delete('draftprojects');
		 
	 }//End of deleteProjects Function
	 
	 // --------------------------------------------------------------------
	
	/**
	 * updateUsers
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateUsers($id=0,$updateData=array())
	 {
	 	$this->db->where('id', $id);
	 	$this->db->update('users', $updateData);
		 
	 }//End of updateUsers Function
	 
	 // --------------------------------------------------------------------
	
	/**
	 * updateReviews
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateReviews($id=0,$updateData=array())
	 {
	 	$this->db->where('id', $id);
	 	$this->db->update('reviews', $updateData);
		 
	 }//End of updateUsers Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * updateprojects
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function manageProjects($updateData=array(),$conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
	 	$this->db->update('projects', $updateData);
		 
	 }//End of updateUsers Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * insert Reviews
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	 function insertReport($insertData=array())
	 {
	 	$this->db->insert('report_violation',$insertData);
	 }//End of insertUserContacts Function
	 
	  // --------------------------------------------------------------------
	 
	 /**
	 * insert RatingHold
	 *
	 * @access	private
	 * @param	array of values

	 */
	 function insertRatingHold($insertData=array())
	 {
	 	$this->db->insert('rating_hold',$insertData);
	 }//End of insertUserContacts Function
	 
	  // --------------------------------------------------------------------
	  
	  /**
	 * insert Reviews
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	function cr_thumb($filename = '')
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = $filename;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = 120;
		$config['height'] = 90;
		
		$this->load->library('image_lib', $config);
		
		
		if ( ! $this->image_lib->resize())
		{
			echo $this->image_lib->display_errors();
		}
	}
	
	// --------------------------------------------------------------------
	
	
	  /**
	 * insert Reviews
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	function cr_Logo($filename = '')
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = $filename;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['raw_name']       ='h1_logo.jpg';
		pr($filename);
		exit;
		$this->load->library('image_lib', $config);
		
		
		if ( ! $this->image_lib->resize())
		{
			echo $this->image_lib->display_errors();
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * 
	 * Get number of projects added
	 * @access	private
	 * @return	favourite and blocked users list
	 */
	 
	 function getNumProjectsByMonth($mon,$year)
	 {
	 	$query = "SELECT count(*) as cnt FROM projects WHERE FROM_UNIXTIME(created, '%c,%Y') = '$mon,$year' ";
	  	$que = $this->db->query($query);
	 	
		$res = $que->row();
		
		return $res->cnt;
	 }//End of flash_message Function
	 
	 // --------------------------------------------------------------------
	
	/**
	 * 
	 * lowBidNotification
	 * @access	private
	 * @return	favourite and blocked users list
	 */
	 
	 function lowBidNotification($bidamt,$prjid)
	 {
	 	
		//echo $bidamt;
		//Check For Conditions
		$conditions = array('bids.project_id' => $prjid,'bids.lowbid_notify' => '1','bids.bid_amount >' => $bidamt);
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->from('bids');
		$result = $this->db->get();
		if($result->num_rows() > 0){
			foreach($result->result() as $bid){
				$user = $this->user_model->getUsers(array('users.id' => $bid->user_id),'users.email,users.user_name');
				$userRow = $user->row();
				
				$project = $this->getProjects(array('projects.id' => $bid->project_id),'projects.project_name');
				$projectDetails = $project->row();
				//pr($projectDetails);exit;
				
				//pr($userRow);exit;
				//Send Mail
				$conditionUserMail = array('email_templates.type'=>'lowbid_notify');
				$result            = $this->email_model->getEmailSettings($conditionUserMail);
				$rowUserMailConent = $result->row();
				
				$splVars = array("!project_name" => '<a href="'.site_url('project/view/'.$bid->project_id).'">'.$projectDetails->project_name.'</a>',"!provider_name" => $userRow->user_name,"!contact_url" => site_url('contact'),'!site_name' => $this->config->item('site_title'),'!bid_amt2' => "$".$bid->bid_amount,'!bid_amt' => "$".$bidamt);
				$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
				$mailContent = strtr($rowUserMailConent->mail_body, $splVars);
				$toEmail     = $userRow->email;
				$fromEmail   = $this->config->item('site_admin_mail');
				//echo $mailContent;exit;
				$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
				//exit;
				
			}
		}
		//pr($result->result());exit;
	 }//End of flash_message Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * 
	 * sendTwitter - sending message to twitter
	 * @access	private
	 * @return	true/false
	 * Added on June 09 2009
	 */
	function sendTwitter($message='',$user,$pass,$apiUrl='http://twitter.com/statuses/update.xml')
	{
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, "$apiUrl");
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_POST, 1);
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "status=$message");
		curl_setopt($curl_handle, CURLOPT_USERPWD, "$user:$pass");
		//Attempt to send
		$buffer = @curl_exec($curl_handle);
		curl_close($curl_handle);
		if(strpos($buffer,'<error>') !== false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}//End of sendTwitter Function
	
	function tinyUrl($url){
	    $tiny = 'http://tinyurl.com/api-create.php?url=';
	    return file_get_contents($tiny.urlencode(trim($url)));
	}
	
	function newWantList($insert=array()) {
		$this->db->insert('want_list', $insert);
	}
}
// End Skills_model Class
   
/* End of file Skills_model.php */ 
/* Location: ./app/models/Skills_model.php */
?>