<?php   
/**
 * Reverse bidding system skills Class
 *
 * Permits admin to handle the skills for this site
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Skills 
 * @author		
 * @version		
 * @created		December 22 2008
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
class skills extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	   
	/**
	* Constructor 
	*
	* Loads language files and models needed for this controller
	*/
	function Skills()
	{
	   parent::Controller();
	   
	   //Check For Admin Logged in
		if(!isAdmin())
			redirect_admin('login');
		
		//Get Config Details From Db
		$this->config->db_config_fetch();
			
	    //Debug Tool
	   	//$this->output->enable_profiler=true;
		
		// loading the lang files
		$this->lang->load('admin/common',$this->config->item('language_code'));
		$this->lang->load('admin/skills',$this->config->item('language_code'));
		$this->lang->load('admin/validation',$this->config->item('language_code'));
		
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		$this->load->model('admin_model');
		$this->load->model('skills_model');

	}//Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads skills settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewGroups()
	{	
		//Load model
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Get Groups
		$groups	=	$this->skills_model->getGroups();
		
		$start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	 $page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		//Get Groups
	    $categoryies=	$this->skills_model->getGroup(NULL,NULL,NULL,$limit,$order);
		$this->outputData['groups'] = $categoryies;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/viewGroups');
		$config['total_rows'] 	 = $groups->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewGroups');
		
		//Load View
	   	$this->load->view('admin/skills/viewGroups',$this->outputData);
	   
	}//End of groups function
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Loads skills settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteGroup()
	{	
		//Load model
		 $this->load->helper('form');
		$groupid = $this->uri->segment(4,'0');
		
	if($groupid==0)
	{	
		//Load Form Helper
		$this->load->helper('form');
		$getgroups	=	$this->skills_model->getGroups();
		$grouplist  =   $this->input->post('grouplist');
		if(!empty($grouplist))
		{
			foreach($grouplist as $res)
			 {
				
				$condition = array('groups.id'=>$res);
				$this->skills_model->deleteGroups(NULL,$condition);
			 }
		 }
		 else
		 {
		  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please select Group')));
		   redirect_admin('skills/viewGroups');
		 }
	}
	else
	{
	$condition = array('groups.id'=>$groupid);
	$this->skills_model->deleteGroups(NULL,$condition);
	}
		 
	$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('delete_success')));
	redirect_admin('skills/viewGroups');
	   
	}//End of delete groups function
	
	// --------------------------------------------------------------------
	
	/**
	 * Add Group.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function addGroup()
	{	
		//Load model
		$this->load->model('skills_model');
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('addGroup'))
		{	
			//Set rules
			$this->form_validation->set_rules('group_name','lang:group_name_validation','required|trim|xss_clean|callback_groupNameCheck');
			$this->form_validation->set_rules('descritpion','lang:descritpion_validation');
			
			if($this->form_validation->run())
			{	
				  //prepare insert data
				  $insertData                  	  	= array();	
			      $insertData['group_name']  		= $this->input->post('group_name');
				  $insertData['descritpion']  		= $this->input->post('descritpion');
				  $insertData['created']			= get_est_time();
				  $insertData['modified']			= get_est_time();

				  //Add Groups
				  $this->skills_model->addGroup($insertData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('added_success')));
				   redirect_admin('skills/viewGroups');
		 	} 
		} //If - Form Submission End
		
		//Get Groups
		$this->outputData['groups']	=	$this->skills_model->getGroups();
		
		//Load View
	   	$this->load->view('admin/skills/addGroup',$this->outputData);
	   
	}//End of addGroup function
	
	// --------------------------------------------------------------------
	
	/**
	 * Edit Group.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editGroup()
	{	
		//Get id of the group	
		$id = is_numeric($this->uri->segment(4))?$this->uri->segment(4):0;
		
		//Load model
		$this->load->model('skills_model');
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('editGroup'))
		{	
				
			//Set rules
			$this->form_validation->set_rules('group_name','lang:group_name_validation','required|trim|xss_clean|callback_groupNameCheck');
			$this->form_validation->set_rules('descritpion','lang:descritpion_validation');			
			
			
			if($this->form_validation->run())
			{	
				  //prepare update data
				  $updateData                  	  	= array();	
			      $updateData['group_name']  		= $this->input->post('group_name');
				  $updateData['descritpion']  		= $this->input->post('descritpion');
				  $updateData['modified']			= get_est_time();

				  //Add Groups
				  $this->skills_model->updateGroup($this->input->post('id',true),$updateData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				  redirect_admin('skills/viewGroups');
		 	} 
		} //If - Form Submission End
		
		//Set Condition To Fetch The Group
		$condition = array('groups.id'=>$id);
		
		//Get Groups
		$this->outputData['groups']	=	$this->skills_model->getGroups($condition);
		
		//Load View
	   	$this->load->view('admin/skills/editGroup',$this->outputData);
	   
	}//End of addGroup function
	
	
	// --------------------------------------------------------------------
	
	/**
	 * View Categories
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewCategories()
	{	
		//Load model
		$this->load->model('skills_model');
		
		//Get Categories
		$category =	$this->skills_model->getCategories();
		
		$start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	 $page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		//Get Groups
	    $categoryies=	$this->skills_model->getCategory(NULL,NULL,NULL,$limit,$order);
		$this->outputData['categories'] = $categoryies;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/viewCategories');
		$config['total_rows'] 	 = $category->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewCategories');
		
		//Load View
	   	$this->load->view('admin/skills/viewCategories',$this->outputData);
	   
	}//End of index function
	
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Loads skills settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteCategory()
	{	
		//Load model
		
		//Load Form Helper
		
		$this->load->helper('form');
		$categoryid = $this->uri->segment(4,'0');
		
	if($categoryid==0)
	{			
		
		$getgroups	=	$this->skills_model->getCategories();
		$categoryList  =   $this->input->post('categoryList');
		 if(!empty($categoryList))
		 {
				foreach($categoryList as $res)
				 {
					
					$condition = array('categories.id'=>$res);
					$this->skills_model->deleteCategory(NULL,$condition);
				 }
			}
			  else
			  {
			   $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please select category')));
				redirect_admin('skills/viewCategories');
			   }
		 }
			 else
			 {
			 $condition = array('categories.id'=>$categoryid);
			 $this->skills_model->deleteCategory(NULL,$condition);
			 } 	 
		$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('delete_success')));
		redirect_admin('skills/viewCategories');
	   
	}//End of groups function
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Add Category.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function addCategory()
	{	
		//Load model
		$this->load->model('skills_model');
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('addCategory'))
		{	
			//Set rules
			$this->form_validation->set_rules('category_name','lang:category_name_validation','required|trim|xss_clean|callback_categoryNameCheck');
			$this->form_validation->set_rules('group_id','lang:group_id_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('is_active','lang:is_active_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('description','lang:description_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('page_title','lang:page_title_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('meta_keywords','lang:meta_keywords_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('meta_description','lang:meta_description_validation','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{	
				  //prepare insert data
				  $insertData                  	  	= array();	
			      $insertData['category_name']  	= $this->input->post('category_name');
				  $insertData['group_id']  			= $this->input->post('group_id');
				  $insertData['is_active']  		= $this->input->post('is_active');
				  $insertData['description']  		= $this->input->post('description');
				  $insertData['page_title']  		= $this->input->post('page_title');
				  $insertData['meta_keywords']  	= $this->input->post('meta_keywords');
				  $insertData['meta_description']  	= $this->input->post('meta_description');
				  $insertData['created']			= get_est_time();
				  $insertData['modified']			= get_est_time();

				  //Add Category
				  $this->skills_model->addCategory($insertData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('added_success')));
				  redirect_admin('skills/viewCategories');
		 	} 
		} //If - Form Submission End
		
		//Get Categories
		$this->outputData['categories']	=	$this->skills_model->getCategories();
		
		//Get Groups
		$this->outputData['groups']	=	$this->skills_model->getGroups();
		
		//Load View
	   	$this->load->view('admin/skills/addCategory',$this->outputData);
	   
	}//End of addCategory function
	
		// --------------------------------------------------------------------
	
	/**
	 * Edit Category.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editCategory()
	{	
		//Get id of the category	
		$id = is_numeric($this->uri->segment(4))?$this->uri->segment(4):0;
		
		//Load model
		$this->load->model('skills_model');
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('editCategory'))
		{	
				
			//Set rules
			$this->form_validation->set_rules('category_name','lang:category_name_validation','required|trim|xss_clean|callback_categoryNameCheck');
			$this->form_validation->set_rules('group_id','lang:group_id_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('is_active','lang:is_active_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('description','lang:description_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('page_title','lang:page_title_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('meta_keywords','lang:meta_keywords_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('meta_description','lang:meta_description_validation','required|trim|xss_clean');		
			
			if($this->form_validation->run())
			{	
				  //prepare update data
				  $updateData                  	  	= array();	
			      $updateData['category_name']  	= $this->input->post('category_name');
				  $updateData['group_id']  			= $this->input->post('group_id');
				  $updateData['is_active']  		= $this->input->post('is_active');
				  $updateData['description']  		= $this->input->post('description');
				  $updateData['page_title']  		= $this->input->post('page_title');
				  $updateData['meta_keywords']  	= $this->input->post('meta_keywords');
				  $updateData['meta_description']  	= $this->input->post('meta_description');
				  $updateData['modified']			= get_est_time();

				  //Add Groupss
				  $this->skills_model->updateCategory($this->input->post('id',true),$updateData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				  redirect_admin('skills/viewCategories');
		 	} 
		} //If - Form Submission End
		
		
		//Get Groups
		$this->outputData['groups']			=	$this->skills_model->getGroups();
		
		//Set Condition To Fetch The Category
		$condition = array('categories.id'=>$id);
		
		//Get Categories
		$this->outputData['categories']		=	$this->skills_model->getCategories($condition);
		
		//Load View
	   	$this->load->view('admin/skills/editCategory',$this->outputData);
	   
	}//End of editCategory function
	
	// --------------------------------------------------------------------
	
	/**
	 * checks whether group name already exists or not.
	 *
	 * @access	private
	 * @param	string Name of group
	 * @return	bool true or false
	 */
	
	function groupNameCheck($group_name)
	{
			
		//Condition to check
		if($this->input->post('operation')!==false and $this->input->post('operation')=='edit')
			$condition = array('group_name'=>$group_name,'id <>'=>$this->input->post('id'));
		else
			$condition = array('group_name'=>$group_name);
		
		//Check with table
		$resultGroupName = $this->skills_model->getGroups($condition);
		
		if ($resultGroupName->num_rows()>0)
		{
			$this->form_validation->set_message('groupNameCheck', $this->lang->line('group_name_unique'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}//End of groupNameCheck function
	
	// --------------------------------------------------------------------
	
	/**
	 * checks whether category name already exists or not.
	 *
	 * @access	private
	 * @param	string name of category
	 * @return	bool true or false
	 */
	
function categoryNameCheck($category_name)
	{
		//Condition to check
		if($this->input->post('operation')!==false and $this->input->post('operation')=='edit')
			$condition = array('categories.category_name'=>$category_name,'categories.id <>'=>$this->input->post('id'));
		else
			$condition = array('categories.category_name'=>$category_name);

		
		//Check with table
		$resultCategoryName = $this->skills_model->getCategories($condition);
		
		if ($resultCategoryName->num_rows()>0)
		{
			$this->form_validation->set_message('categoryNameCheck', $this->lang->line('category_name_unique'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}//End of groupNameCheck function
	
	
	/**
	 * Add Group.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewBids()
	{	
		//Load model
		$this->load->model('skills_model');
		//Get bidProjects
		$bidProjects1	=	$this->skills_model->getBids();
		$this->outputData['projects']	=	$this->skills_model->getProjects();
		$start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
		//$condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id);
		$bidProjects 	         = $this->skills_model->getBids(NULL,NULL,NULL,$limit,$order);
		$this->outputData['bidProjects'] = $bidProjects;
		
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/viewBids');
		$config['total_rows'] 	 = $bidProjects1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewBids');
		
		//Load View
	   	$this->load->view('admin/skills/viewBids',$this->outputData);
	   
	}//End of addGroup function
	
	/**
	 * manageBids
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function searchBids()
	{	
		if($this->input->post('projectid'))
		  {
			  //Load model
			$this->load->model('skills_model');
			//Get bidProjects
			$bidProjects1	=	$this->skills_model->getBids();
			$this->outputData['projects']	=	$this->skills_model->getProjects();
			
			$projectid  =   $this->input->post('projectid');
			
			$condition = array('bids.project_id'=>$projectid);
			$list =  $this->skills_model->getBids($condition);
			$count = count($list);
			if($count > 0)
			  $this->outputData['bidProjects'] = $list;
			//Load View
			$this->load->view('admin/skills/viewBids',$this->outputData);
		  }
		 else
		  { 
	    	//Load View
		    $this->load->view('admin/skills/searchBids',$this->outputData);
		  }	
	}//End of addGroup function
	
		
	/**
	 * manageBids
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function manageBids()
	{	
		//Load model
		$this->load->model('skills_model');
		//Get bidProjects
		$bidProjects1	=	$this->skills_model->getBids();
		$this->outputData['projects']	=	$this->skills_model->getProjects();
		if($this->input->post('projectList'))
		{
			$projectList  =   $this->input->post('projectList');
			
			$list = array();
			$i=0;
			foreach($projectList as $res)
			 {
				//echo $res;
				$condition = array('bids.id'=>$res);
				$result =  $this->skills_model->getBids($condition);
				//pr($result->result());
				$list[$i] = $result;
				$i = $i+1;
				//$list[$i] = $list->result();
			 }
			
			 $this->outputData['list'] = $list;

			//pr($_POST);
			//exit;
			//Load View
			$this->load->view('admin/skills/editBids',$this->outputData);
		}
		else
		  {
		     $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Please choose the projects to edit')));
			 redirect_admin('skills/viewBids');
		  }	 
		
	   
	}//End of addGroup function
	
	/**
	 * manageBids to edit the bid amounts
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editBids()
	{	
		//Load model
		$this->load->model('skills_model');
			
		//Get bidProjects
		$project  =   $this->input->post('bidid');
		$amount  =   $this->input->post('amount');
		$count = count($project);
		for($i=0;$i<$count;$i++)
		 {
			//update the amount value
			$condition = array('bids.id'=>$project[$i]);
			$updateKey = array('bids.bid_amount'=>$amount[$i]);
		 	$this->skills_model->updateBids(NULL,$updateKey,$condition);
		 }
        $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));
		redirect_admin('skills/viewBids');
	   
	}//End of addGroup function
	
	
	/**
	 * deleteBids
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteBids()
	{	
		//Load model
		
		$this->load->model('skills_model');
		$this->load->helper('form');
		
		//Get bidProjects
		$bidProjects1	=	$this->skills_model->getBids();
		$this->outputData['projects']	=	$this->skills_model->getProjects();
		$projectList  =   $this->input->post('projectList');
		if(!empty($projectList))
		{
			foreach($projectList as $res)
			 {
				//update the amount value
				$condition = array('bids.id'=>$res);
				$this->skills_model->deleteBids(NULL,$condition);
			 }
		 }
		 else
		 {
		 $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please Select Bid Project')));
	   redirect_admin('skills/viewBids');
		 }
       $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('deleted_success')));
	   redirect_admin('skills/viewBids');
	   
	}//End of addGroup function
	
	/**
	 * viewProjects
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewProjects()
	{	
		//Load model
		$this->load->model('skills_model');
		//Get Projects
		$projects1	=	$this->skills_model->getProjects();
		
		$start =  $this->uri->segment(4,0);  
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		$projects 	         = $this->skills_model->getProjects(NULL,NULL,NULL,$limit,$order);   
		
		$this->outputData['projects'] = $projects;
		
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/viewProjects');  
		$config['total_rows'] 	 = $projects1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewProjects');
			
		//Load View
	$this->load->view('admin/skills/viewProjects',$this->outputData);
		  
	}//End of viewProjects function
	
	
	function viewProjects1()
	{	
		//Load model
		$this->load->model('admin_model');
		$this->outputData['users']      = $this->admin_model->getUsers();
		
		$start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]		 = ($start-1) * $page_rows;
		else
		    $limit[1]		 = $start * $page_rows;
			
			$order[0]            ='id';
		$order[1]            ='asc';
		//Get total projects
		$days = date( 'W,m,Y', time() );
		$cond1 = '%u,%m,%Y';
		$cond2 = $days;
		
		$projects1   = $this->admin_model->getProjects(NULL,NULL);
		$projects   = $this->admin_model->getProjects(NULL,NULL,$limit,$order);
		$this->outputData['projects'] = $projects;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/viewprojects1');  
		$config['total_rows'] 	 = $projects1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewprojects1');
		//assign some value to check the project type
		
		
			
		//Load View
	$this->load->view('admin/skills/viewProjects1',$this->outputData);
		  
	}//End of viewProjects function
	
	function projectDeatils(){
		$prid =  $this->uri->segment(4,0);
		$cond = array('projects.id' => $prid);
		$projects 	         = $this->skills_model->getProjects($cond);   
		
		$this->outputData['projects'] = $projects;
		//Load View
		$this->load->view('admin/skills/viewProjects',$this->outputData);
	}
	
	/**
	 * viewProjects
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function openProjects()
	{	
		//Load model
		$this->load->model('skills_model');
		//Get Projects
	    $condition 		 = array('projects.project_status'=>'0');
        $projects1	=	$this->skills_model->getProjects($condition);
		//pr($projects1);
		$start =  $this->uri->segment(4,0);  
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
	    $condition 		 = array('projects.project_status'=>'0');
		$projects 	         = $this->skills_model->getProjects($condition,NULL,NULL,$limit,$order);
		
		$this->outputData['projects'] = $projects;
		
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/openProjects');  
		$config['total_rows'] 	 = $projects1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewProjects');
					$this->outputData['namefunct'] ='openprojects';
			
			//Load View
		$this->load->view('admin/skills/viewProjects',$this->outputData);
		  
	}//End of viewProjects function
	
	
	/**
	 * viewProjects
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function closedProjects()
	{	
		//Load model
		$this->load->model('skills_model');
		//Get Projects
	    $condition 		 = array('projects.project_status'=>'2');
        $projects1	=	$this->skills_model->getProjects($condition);
		
		$start =  $this->uri->segment(4,0);  
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
	    $condition 		 = array('projects.project_status'=>'2');
		$projects 	         = $this->skills_model->getProjects($condition,NULL,NULL,$limit,$order);
		
		$this->outputData['projects'] = $projects;
		
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/closedProjects');  
		$config['total_rows'] 	 = $projects1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewProjects');
		$this->outputData['namefunct'] ='closedprojects';
			
			//Load View
		$this->load->view('admin/skills/viewProjects',$this->outputData);
		  
	}//End of viewProjects function
	
	
	
	/**
	 * manageBids
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function manageProjects()
	{	
		//Load model
		$this->load->model('skills_model');
		
		//Assign proejct edit type
		if($this->input->post('today'))
		  $this->outputData['today'] = 'today';
		if($this->input->post('todayOpen'))
		  $this->outputData['todayOpen'] = 'todayOpen';
		if($this->input->post('todayClosed'))
		  $this->outputData['todayClosed'] = 'todayClosed';
		if($this->input->post('thisWeek'))
		  $this->outputData['thisWeek'] = 'thisWeek';
		if($this->input->post('thisMonth'))
		  $this->outputData['thisMonth'] = 'thisMonth';      
		if($this->input->post('thisYear'))
		  $this->outputData['thisYear'] = 'thisYear';
		//Get Projects
		if($this->input->post('projectList'))
		{
			$projectList  =   $this->input->post('projectList');
			$list = array();
			$i=0;
			foreach($projectList as $res)
			 {
				$i = $i+1;
				$condition = array('projects.id'=>$res);
				$list[$i] =  $this->skills_model->getProjects($condition);
				//$list[$i] = $list->result();
			 }
			 $this->outputData['projects'] = $list;
			
			//Load View
			$this->load->view('admin/skills/editProjects',$this->outputData);
		}
		else
		  {
		     $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Please choose the projects to edit')));
			 redirect_admin('skills/viewProjects1');
		  }	 
		
	   
	}//End of addGroup function
	
	/**
	 * manageBids to edit the bid amounts
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editProjects()
	{	
		//Load model
		$this->load->model('skills_model');
			
		//Get bidProjects
		$count =  count($this->input->post('projectid'));
		
		$projectid = $this->input->post('projectid',TRUE);
		$projectstatus = $this->input->post('projectstatus',TRUE);
		$projectname = $this->input->post('projectname',TRUE);
		$projectdescription = $this->input->post('projectdescription',TRUE);
		$projectmin = $this->input->post('projectmin',TRUE);
		$projectmax = $this->input->post('projectmax',TRUE);
		$projectfeatured = $this->input->post('projectfeatured',TRUE);
		$projecturgent = $this->input->post('projecturgent',TRUE);
		$projecthidden = $this->input->post('projecthidden',TRUE);
		//pr($projectfeatured);
		for($i=0;$i<$count;$i++)
		  {
		  	$updateKey['project_name']    = $projectname[$i]; 
			$updateKey['project_status']  = $projectstatus[$i];
			$updateKey['description']     = $projectdescription[$i];
			$updateKey['budget_min']      = $projectmin[$i];
			$updateKey['budget_max']      = $projectmax[$i];
			$updateKey['is_feature']      = $projectfeatured[$i][0];
			$updateKey['is_urgent']       = $projecturgent[$i][0];
			$updateKey['is_hide_bids']    = $projecthidden[$i][0];
			$condition = array('projects.id'=>$projectid[$i]);
			$this->skills_model->updateProjects(NULL,$updateKey,$condition);
		  }
        $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));
		if($this->input->post('today'))
		  redirect_admin('skills/todayProjects');
		if($this->input->post('todayOpen'))
		  redirect_admin('skills/todayOpen');
		if($this->input->post('todayClosed'))
		  redirect_admin('skills/todayClosed');
		if($this->input->post('thisWeek'))
		  redirect_admin('skills/thisWeek');
		if($this->input->post('thisMonth'))
		  redirect_admin('skills/thisMonth');        
		if($this->input->post('thisYear'))
		  redirect_admin('skills/thisYear'); 
		redirect_admin('skills/viewProjects');
	   
	}//End of addGroup function
	
	
	/**
	 * deleteBids
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteProjects()
	{	
		//Load model
		$this->load->model('skills_model');
		//Get bidProjects
		$bidProjects1	=	$this->skills_model->getBids();
		$this->outputData['projects']	=	$this->skills_model->getProjects();
		$projectList  =   $this->input->post('projectList');
		if(!empty($projectList))
		 {
			foreach($projectList as $res)
			 { 
				//update the amount value
				$condition = array('projects.id'=>$res);
				$this->skills_model->deleteProjects(NULL,$condition);
			 }
		   }
		  else
		  {
		   $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please Select Project')));
		   redirect_admin('skills/viewProjects');
		  } 
       $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('deleted_success')));
	   
	   redirect_admin('skills/viewProjects');
	   
	}//End of addGroup function
	
	/**
	 * searchProjects
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function searchProjects()
	{	
		if($this->input->post('projectid'))
		  {
			  //Load model
			$this->load->model('skills_model');
	
			//Get Projects		
			if($this->input->post('projectid'))
			  {
				$id        = $this->input->post('projectid'); 
			    $condition = array('projects.id'=>$id);
			  }	
			 	 
			$this->outputData['projects']	=	$this->skills_model->getProjects($condition);
			
			//Load View
			$this->load->view('admin/skills/viewProjects',$this->outputData);
		  }
		  else if($this->input->post('projectname'))
		  {
			  //Load model
			$this->load->model('skills_model');
	
			//Get Projects		
			if($this->input->post('projectname'))
			  {
				$id        = $this->input->post('projectname'); 
			    $like = array('projects.project_name'=>$id);
			  }	
			 	 
			$this->outputData['projects']	=	$this->skills_model->getProjects(NULL,NULL,$like);
			
			//Load View
			$this->load->view('admin/skills/viewProjects',$this->outputData);
		  }
		 else
		  { 
	    	//Load View
		    $this->load->view('admin/skills/searchProjects',$this->outputData);
		  }	
	}//End of searchProjects function
	
	/**
	 * Get Today open projects
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */
	function todayProjects()
	{
		//Get total users 
	$this->load->model('admin_model');
		$this->outputData['users']      = $this->admin_model->getUsers();
		
		//Get total projects
		$days = date( 'd,m,Y', time() );
		$cond1 = '%d,%m,%Y';
		$cond2 = $days;
		
		$start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]		 = ($start-1) * $page_rows;
		else
		    $limit[1]		 = $start * $page_rows;
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		$projects1   = $this->admin_model->gettodayProjects($cond1,$cond2);
		$projects   = $this->admin_model->gettodayProjects($cond1,$cond2,$limit,$order);
		$this->outputData['projects'] = $projects;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/todayProjects');  
		$config['total_rows'] 	 = $projects1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'today');
		//assign some value to check the project type
		$this->outputData['namefunct']      = 'today'; 
		
		$this->load->view('admin/skills/viewProjects1',$this->outputData);
		
	} //Function Index End
//--------------------------------------------------------------------------------------

/**
	 * Get Today open projects
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */
	function todayOpen()
	{
		//Get total users 
		$this->load->model('admin_model');
		$this->outputData['users']      = $this->admin_model->getUsers();
		$days = date( 'd,m,Y', time() );
		$cond1 = '%d,%m,%Y';
		$cond2 = $days;
		$status = '0';
		$projects1   = $this->admin_model->getProjectsdetails($cond1,$cond2,NULL,$status = '0');
		
		$start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]		 = ($start-1) * $page_rows;
		else
		    $limit[1]		 = $start * $page_rows;
		//Get total projects
		$order[0]            ='id';
		$order[1]            ='asc';
		
		$projects   = $this->admin_model->getProjectsdetails($cond1,$cond2,$limit,$status = '0',$order);
		$this->outputData['projects'] = $projects;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/todayOpen');  
		$config['total_rows'] 	 = $projects1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'todayOpen');
		//assign some value to check the project type
		$this->outputData['namefunct']      = 'todayOpen'; 
		
		$this->load->view('admin/skills/viewProjects1',$this->outputData);
		
		
	} //Function Index End
//---------------------------------------------------------------------------------------


/**
	 * Get Today open projects
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */
	function todayClosed()
	{
		//Get total users 
		$this->load->model('admin_model');
		$this->outputData['users']      = $this->admin_model->getUsers();
		$days = date( 'd,m,Y', time() );
		$cond1 = '%d,%m,%Y';
		$cond2 = $days;
		$status = '2';
		$projects1   = $this->admin_model->getProjectsdetails($cond1,$cond2,NULL,$status = '2');
		
		$start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]		 = ($start-1) * $page_rows;
		else
		    $limit[1]		 = $start * $page_rows;
			
			$order[0]            ='id';
		$order[1]            ='asc';
		//Get total projects

		$projects   = $this->admin_model->getProjectsdetails($cond1,$cond2,$limit,$status = '2',$order);
		$this->outputData['projects'] = $projects;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/todayClosed');  
		$config['total_rows'] 	 = $projects1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'todayClosed');
		//assign some value to check the project type
		$this->outputData['namefunct']      = 'todayClosed'; 
		
		$this->load->view('admin/skills/viewProjects1',$this->outputData);
		
	} //Function Index End
//---------------------------------------------------------------------------------------

/**
	 * Get Today open projects
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */
	function thisMonth()
	{
		//Get total users 
		$this->load->model('admin_model');
		$this->outputData['users']      = $this->admin_model->getUsers();
		
		//Get total projects
		$days = date( 'm,Y', time() );
		$cond1 = '%m,%Y';
		$cond2 = $days;
		
		$start = $this->uri->segment(4,0);
		//Get the inbox mail list 
		$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]		 = ($start-1) * $page_rows;
		else
		    $limit[1]		 = $start * $page_rows;

		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		$projects1   = $this->admin_model->getProjects($cond1,$cond2);
		$projects  = $this->admin_model->getProjects($cond1,$cond2,$limit,$order);
		
		$this->outputData['projects'] = $projects;
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/thisMonth');  
		$config['total_rows'] 	 = $projects1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'thisMonth');
		//assign some value to check the project type
		$this->outputData['namefunct']      = 'thisMonth'; 
		
		$this->load->view('admin/skills/viewProjects1',$this->outputData);
		
	} //Function Index End
//---------------------------------------------------------------------------------------

/**
	 * Get Today open projects
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */
	function thisWeek()
	{
		//Get total users 
		$this->load->model('admin_model');
		$this->outputData['users']      = $this->admin_model->getUsers();
		
		$start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]		 = ($start-1) * $page_rows;
		else
		    $limit[1]		 = $start * $page_rows;
			
			$order[0]            ='id';
		$order[1]            ='asc';
		//Get total projects
		$days = date( 'W,m,Y', time() );
		$cond1 = '%u,%m,%Y';
		$cond2 = $days;
		
		$projects1   = $this->admin_model->getProjects($cond1,$cond2);
		$projects   = $this->admin_model->getProjects($cond1,$cond2,$limit,$order);
		$this->outputData['projects'] = $projects;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/thisWeek');  
		$config['total_rows'] 	 = $projects1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'thisWeek');
		//assign some value to check the project type
		$this->outputData['namefunct']      = 'thisWeek'; 
		
		$this->load->view('admin/skills/viewProjects1',$this->outputData);
		
		
	} //Function Index End
//---------------------------------------------------------------------------------------

/**
	 * Get Today open projects
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */
	function thisYear()
	{
		//Get total users 
		$this->load->model('admin_model');
		$start = $this->uri->segment(4,0);
		$this->outputData['users']      = $this->admin_model->getUsers();
		
		//Get total projects
		$days = date( 'Y', time() );
		$cond1 = '%Y';
		$cond2 = $days;
		
		
		//Get the inbox mail list 
     	$page_rows   =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
	
		
		if($start > 0)
		   $limit[1]		 = ($start-1) * $page_rows;
		else
		    $limit[1]		 = $start * $page_rows;
		
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		$projects1   = $this->admin_model->getProjects($cond1,$cond2);
		$projects   = $this->admin_model->getProjects($cond1,$cond2,$limit,$order);
		$this->outputData['projects'] = $projects;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/thisYear');  
		$config['total_rows'] 	 = $projects1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'thisYear');
		//assign some value to check the project type
		$this->outputData['namefunct']      = 'thisYear'; 
		
		$this->load->view('admin/skills/viewProjects1',$this->outputData);
		
	} //Function Index End
//---------------------------------------------------------------------------------------
	
	/* Search keyword
	*/
	function search()
	{ 
	  
	   if($this->input->post('id'))
	     {   ?>
			 <div class="clsTable">
			 <form name="searchTransaction" action="<?php echo admin_url('skills/searchProjects');?>" method="post">
				<input type="hidden" name="name" id="name" />
				 <tr><td><label><?php echo $this->lang->line('Enter Project Id'); ?></label></td><td><input type="text" name="projectid" id="projectid" /></td></tr>
				 <tr><td></td><td><input type="submit" name="search" value="<?php echo $this->lang->line('search');?>" class="clsSubmitBt1" /></td></tr>
			</form>
			</div>	<?php
		 } 
		if($this->input->post('name'))
		{  ?>
			<div class="clsTable">
			 <form name="searchTransaction" action="<?php echo admin_url('skills/searchProjects');?>" method="post">
				 <input type="hidden" name="id" id="id" />
				 <tr><td><label><?php echo $this->lang->line('Enter Project Name'); ?></label></td><td><input type="text" name="projectname" id="projectname" /></td></tr>
				 <tr><td></td><td><input type="submit" name="search" value="<?php echo $this->lang->line('search');?>" class="clsSubmitBt1" /></td></tr>
			</form>
			</div> <?php 
		} 
	}//Function end
	
	/* Project Report Violation
	/ Param nil
	*/
	function reportViolation()
	{
		//Get total users 
		$this->outputData['users']      = $this->admin_model->getUsers();
		
		//Get total Report Violation
		$reports1 = $this->admin_model->getReports();
		
		
		$start =  $this->uri->segment(4,0);  
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='desc';
		
		$reports 	         = $this->admin_model->getReports(NULL,NULL,NULL,$limit,$order);   
		$this->outputData['reportViolation'] = $reports;
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('skills/reportViolation');  
		$config['total_rows'] 	 = $reports1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'reportViolation');
		
		$this->load->view('admin/skills/viewReports',$this->outputData);
	}
	
	/* delete Project Report Violation
	/ Param id
	*/
	function deleteReport()
	{
		$id = $this->uri->segment(4,0);
		$condition = array('report_violation.id'=>$id);
		$this->admin_model->deleteReport($condition);
		
		$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('delete_success')));
		redirect_admin('skills/reportViolation');
	}
	
}
//End  skillSettings Class

/* End of file skillSettings.php */ 
/* Location: ./app/controllers/admin/skillSettings.php */
?>