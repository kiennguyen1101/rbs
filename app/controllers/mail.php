<?php
/**
 * Reverse bidding system Mail Class
 *
 * Seller related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Buyer 
 * @author		
 * @version		
 * @created		
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

class Mail extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Mail()
	{
	   parent::Controller();
	   
	    //Get Config Details From Db
		$this->config->db_config_fetch();
		
	   //Manage site Status 
		if($this->config->item('site_status') == 1)
		redirect('offline');
	   
	   //Debug Tool
	   //$this->output->enable_profiler=true;		
		
		//Load Models required for this controller
		$this->load->model('common_model');
		$this->load->model('skills_model');
		
		
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		
		//Get Footer content
		$this->outputData['pages']	= $this->common_model->getPages();	
		
		//Get logged user role
		$this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		
		//Get Latest Projects
		$limit_latest = $this->config->item('latest_projects_limit');
		$limit3 = array($limit_latest);
		$this->outputData['latestProjects']	= $this->skills_model->getLatestProjects($limit3);
		
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
	} //Controller End 
	// --------------------------------------------------------------------
	
	/**
	 * Load Messages Related To A Project
	 *
	 * @access	private
	 * @param	project id
	 * @return	void
	 */ 
	function index()
	{	
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/mail', $this->config->item('language_code'));
		
		if(!is_numeric($this->uri->segment(3)))  
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
			 redirect('info');
		  } 
		//Get Project Id
		$project_id	 = $this->uri->segment(3,'0');
		$conditions = array('projects.id'=>$project_id);
		$this->outputData['projects']	   =  $this->skills_model->getProjects($conditions);
		
		$conditions_message = array('messages.project_id'=>$project_id);
		$this->outputData['messages']	   =  $this->messages_model->getProjectMessages($conditions_message);
	    $this->load->view('mail',$this->outputData);	
	} //Function signUp End
//-----------------------------------------------------------------------------------------------	
		
	/**
	 *  view all mail list to the particular user
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function viewMail()
	{	
	    //language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		$k=0;
		
		
		//Get page value
	    $start = 0;
		
		//Get Project details who are all post projects
		if($this->uri->segment(3,'0'))
		  {
			$user_id	 = $this->uri->segment(3,'0');
			$k=0;
		  }
     	if($this->input->post('keyword'))
		  {
		  	$this->outputData['keyword'] = $this->input->post('keyword'); 
			$user_id     =$this->loggedInUser->id;
			$k=0;
		  }
		if($this->input->post('keyword') == '')
		  {
		  	$k=1;
		  }
		$user_id     =$this->loggedInUser->id;  	
		$conditions = array('projects.creator_id'=>$user_id);
		$postuserslist	   =  $this->skills_model->getUsersproject($conditions);
		
		//Get logged user role
		$this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		
		//Get the users details
		$usersList	   =  $this->user_model->getUserslist();
		$this->outputData['usersList'] =  $usersList->result();	
		
		//Get the projects details
		$projectList	   =  $this->skills_model->getUsersproject();
		$this->outputData['projectList'] =  $projectList->result();	
		
		//----------- inbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		
		//Get all the messages from message table
		$conditions_message = array('messages.to_id'=>$user_id);
		$usersInboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersInboxtotal'] =  $usersInboxtotal->result();
		$usersInboxtotal->num_rows();
				
		$conditions_message = array('messages.to_id'=>$user_id);
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start;
		 
		 //Get all message trasaction with some limit
		$usersInbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersInbox'] =  $usersInbox->result();
		 $usersInbox->num_rows();
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('mail/inbox');
		$config['total_rows'] 	 = $usersInboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_inbox']   = $this->pagination->create_links2(false,'inbox');
		$this->outputData['totalInbox'] =  count($usersInbox->result());
        //-----------inbox mail end here ---------------//
		
		//----------- outbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		
		//Get all the messages from message table
		$conditions_message = array('messages.from_id'=>$user_id,'messages.deluserid'=>'');
		$usersOutboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersOutboxtotal'] =  $usersOutboxtotal->result();
		$usersOutboxtotal->num_rows();
				
		$conditions_message = array('messages.from_id'=>$user_id,'messages.deluserid'=>'');
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start;
		 
		 //Get all message trasaction with some limit
		$usersOutbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersOutbox'] =  $usersOutbox->result();
		$usersOutbox->num_rows();
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('mail/outbox');
		$config['total_rows'] 	= $usersOutboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'outbox');
		$this->outputData['totalOutbox'] =  count($usersOutbox->result());
       //-----------outbox mail end here ---------------//
	    $this->load->view('messages/viewMail',$this->outputData);	
	}// function end here
//	--------------------------------------
	
	/**
	 *  view all mail list to the particular user
	 *
	 * @access	private
	 * @param	search-keyword
	 * @return	void
	 */ 
	function searchMail()
	{	
	//language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		$k=0;
		//Get page value
	    $start = 0;
		
		//Remove the session value
		$this->session->unset_userdata('keyword');
		
		if(is_numeric($this->input->post('keyword')))
		     $keyword = $this->input->post('keyword');
			 
		if($this->input->post('keyword'))
		 {
		  if(!is_numeric($this->input->post('keyword')))
		   {
		     $keyword = $this->input->post('keyword');	 
			 
			 $conditions       = array('users.user_name'=>$keyword);
		     $postuserslist	   =  $this->user_model->getUserslist($conditions);
			 $postuserslist    = $postuserslist->row();
			 $keyword          = $postuserslist->id;
		   }	 
		 }
		//Get Project details who are all post projects
		
     	if($this->input->post('keyword'))
		  {
		  	$this->outputData['keyword'] = $this->input->post('keyword'); 
			$user_id                     =$this->loggedInUser->id;
			$k=0;
		  }
		if($this->input->post('keyword') == '')
		  {
		  	$k=1;
		  }
		  
		if($this->input->post('keyword'))
		   {
		     $this->session->set_userdata('keyword',$this->input->post('keyword'));  
		    } 
		   
		$user_id     =$this->loggedInUser->id;  	
		$conditions = array('projects.creator_id'=>$user_id);
		$postuserslist	   =  $this->skills_model->getUsersproject($conditions);
		
		//Get logged user role
		$this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		
		//Get the users details
		$usersList	   =  $this->user_model->getUserslist();
		$this->outputData['usersList'] =  $usersList->result();	
		
		//Get the projects details
		$projectList	   =  $this->skills_model->getUsersproject();
		$this->outputData['projectList'] =  $projectList->result();	
		//----------- inbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		
		//Get all the messages from message table
		//Check the keyword is set or not
		if(is_numeric($this->input->post('keyword')))
		     $conditions_message = array('messages.to_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->input->post('keyword'))
		  {
		     if(!is_numeric($this->input->post('keyword')))
 		       $conditions_message = array('messages.to_id'=>$user_id,'messages.from_id'=>$keyword);
		  }	   
		else
		     $conditions_message = array('messages.to_id'=>$user_id); 	 
			 
		$usersInboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersInboxtotal'] =  $usersInboxtotal->result();
		
		//Check the keyword is set or not		
		if(is_numeric($this->input->post('keyword')))
		     $conditions_message = array('messages.to_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->input->post('keyword'))
		  {
			if(!is_numeric($this->input->post('keyword')))
				 $conditions_message = array('messages.to_id'=>$user_id,'messages.from_id'=>$keyword);	 
		  }		 
		else
		     $conditions_message = array('messages.to_id'=>$user_id);
			  
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start;
		 
		 //Get all message trasaction with some limit
		$usersInbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersInbox'] =  $usersInbox->result();
		 $usersInbox->num_rows();
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('mail/searchInbox');
		$config['total_rows'] 	 = $usersInboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_inbox']   = $this->pagination->create_links2(false,'searchInbox');
		$this->outputData['totalInbox'] =  count($usersInbox->result());
       //-----------inbox mail end here ---------------//
		
		//----------- outbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		
		//Get all the messages from message table
		//Check the keyword is set or not		
		if(is_numeric($this->input->post('keyword')))
		     $conditions_message = array('messages.from_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->input->post('keyword'))
		  {
			if(!is_numeric($this->input->post('keyword')))
				 $conditions_message = array('messages.from_id'=>$user_id,'messages.to_id'=>$keyword);	 
		  }		 
		else
		     $conditions_message = array('messages.from_id'=>$user_id);
			 
		$usersOutboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersOutboxtotal'] =  $usersOutboxtotal->result();
		
		//Check the keyword is set or not		
		if(is_numeric($this->input->post('keyword')))
		     $conditions_message = array('messages.from_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->input->post('keyword'))
		  {
			if(!is_numeric($this->input->post('keyword')))
				 $conditions_message = array('messages.from_id'=>$user_id,'messages.to_id'=>$keyword);	 
		  }		 
		else
		     $conditions_message = array('messages.from_id'=>$user_id);		
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start;
		 
		 //Get all message trasaction with some limit
		$usersOutbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersOutbox'] =  $usersOutbox->result();
	    
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('mail/searchOutbox');
		$config['total_rows'] 	 = $usersOutboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'searchOutbox');
		$this->outputData['totalOutbox'] =  count($usersOutbox->result());
       //-----------outbox mail end here ---------------//
	    $this->load->view('messages/viewMail',$this->outputData);	
	}// function end here
//	--------------------------------------
	
	
	
	
	/**
	 *  search the mail inbox
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function searchInbox()
	{	
	    //language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		$k=0;
		
		$keyword = $this->session->userdata('keyword');
		//Get page value
	    $start = $this->uri->segment(3,0);
		if($start > 0)
			$start = $start -1;
		
		if(is_numeric($this->session->userdata('keyword')))
		     $keyword = $this->session->userdata('keyword');
			 
		if($this->session->userdata('keyword'))
		 {
		  if(!is_numeric($this->session->userdata('keyword')))
		   {
		     $keyword = $this->input->post('keyword');	 
			 
			 $conditions       = array('users.user_name'=>$keyword);
		     $postuserslist	   =  $this->user_model->getUserslist($conditions);
			 $postuserslist    = $postuserslist->row();
			 $keyword          = $postuserslist->id;
		   }	 
		 }
		//Get Project details who are all post projects
     	if($this->session->userdata('keyword'))
		  {
		  	$this->outputData['keyword'] = $this->input->post('keyword'); 
			$user_id                     =$this->loggedInUser->id;
			$k=0;
		  }
		if($this->session->userdata('keyword') == '')
		  {
		  	$k=1;
		  }
		$user_id     =$this->loggedInUser->id;  	
		$conditions = array('projects.creator_id'=>$user_id);
		$postuserslist	   =  $this->skills_model->getUsersproject($conditions);
		//Get logged user role
		$this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		
		//Get the users details
		$usersList	   =  $this->user_model->getUserslist();
		$this->outputData['usersList'] =  $usersList->result();	
		
		//Get the projects details
		$projectList	   =  $this->skills_model->getUsersproject();
		$this->outputData['projectList'] =  $projectList->result();	
		//----------- inbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		
		//Get all the messages from message table
		//Check the keyword is set or not
		if(is_numeric($this->session->userdata('keyword')))
		     $conditions_message = array('messages.to_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->input->post('keyword'))
		  {
		     if(!is_numeric($this->session->userdata('keyword')))
 		       $conditions_message = array('messages.to_id'=>$user_id,'messages.from_id'=>$keyword);
		  }	   
		else
		     $conditions_message = array('messages.to_id'=>$user_id); 	 
			 
		$usersInboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersInboxtotal'] =  $usersInboxtotal->result();
		
		//Check the keyword is set or not		
		if(is_numeric($this->session->userdata('keyword')))
		     $conditions_message = array('messages.to_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->input->post('keyword'))
		  {
			if(!is_numeric($this->session->userdata('keyword')))
				 $conditions_message = array('messages.to_id'=>$user_id,'messages.from_id'=>$keyword);	 
		  }		 
		else
		     $conditions_message = array('messages.to_id'=>$user_id);
			  
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start;
		 
		 //Get all message trasaction with some limit
		$usersInbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersInbox'] =  $usersInbox->result();
		 $usersInbox->num_rows();
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('mail/searchInbox');
		$config['total_rows'] 	 = $usersInboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_inbox']   = $this->pagination->create_links2(false,'searchInbox');
		$this->outputData['totalInbox'] =  count($usersInbox->result());
       //-----------inbox mail end here ---------------//
		
		//----------- outbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		
		//Get all the messages from message table
		//Check the keyword is set or not		
		if(is_numeric($this->session->userdata('keyword')))
		     $conditions_message = array('messages.from_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->input->post('keyword'))
		  {
			if(!is_numeric($this->session->userdata('keyword')))
				 $conditions_message = array('messages.from_id'=>$user_id,'messages.to_id'=>$keyword);	 
		  }		 
		else
		     $conditions_message = array('messages.from_id'=>$user_id);
			 
		$usersOutboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('mail/searchOutbox');
		$config['total_rows'] 	 = $usersOutboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'searchOutbox');
		$this->outputData['totalOutbox'] =  count($usersOutbox->result());
		$this->outputData['usersOutboxtotal'] =  $usersOutboxtotal->result();
		$usersOutboxtotal->num_rows();
		
		//Check the keyword is set or not		
		if(is_numeric($this->session->userdata('keyword')))
		     $conditions_message = array('messages.from_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->session->userdata('keyword'))
		  {
			if(!is_numeric($this->session->userdata('keyword')))
				 $conditions_message = array('messages.from_id'=>$user_id,'messages.to_id'=>$keyword);	 
		  }		 
		else
		     $conditions_message = array('messages.from_id'=>$user_id);		
		$limit[0]			 = $page_rows;
		$limit[1]			 = '0';
		 
		 //Get all message trasaction with some limit
		$usersOutbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersOutbox'] =  $usersOutbox->result();
	
		$this->outputData['totalOutbox'] =  count($usersOutbox->result());
		
       //-----------outbox mail end here ---------------//
	    $this->load->view('messages/viewMail',$this->outputData);	
	}// function end here
//	--------------------------------------
	
	
	/**
	 *  search the mail outbox
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function searchOutbox()
	{	
	     //language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		$k=0;
		$keyword = $this->session->userdata('keyword');
		
		//Get page value
	    $start = $this->uri->segment(3,0);
		if($start > 0)
			$start = $start -1;
		
		if(is_numeric($this->session->userdata('keyword')))
		     $keyword = $this->session->userdata('keyword');
			 
		if($this->session->userdata('keyword'))
		 {
		  if(!is_numeric($this->session->userdata('keyword')))
		   {
		     $keyword = $this->session->userdata('keyword');	 
			 $conditions       = array('users.user_name'=>$keyword);
		     $postuserslist	   =  $this->user_model->getUserslist($conditions);
			 $postuserslist    = $postuserslist->row();
			 $keyword          = $postuserslist->id;
		   }	 
		 }
		//Get Project details who are all post projects
		
     	if($this->session->userdata('keyword'))
		  {
		  	$this->outputData['keyword'] = $this->input->post('keyword'); 
			$user_id                     =$this->loggedInUser->id;
			$k=0;
		  }
		if($this->session->userdata('keyword') == '')
		  {
		  	$k=1;
		  }
		$user_id     =$this->loggedInUser->id;  	
		$conditions = array('projects.creator_id'=>$user_id);
		$postuserslist	   =  $this->skills_model->getUsersproject($conditions);
		
		//Get logged user role
		$this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		
		//Get the users details
		$usersList	   =  $this->user_model->getUserslist();
		$this->outputData['usersList'] =  $usersList->result();	
		
		//Get the projects details
		$projectList	   =  $this->skills_model->getUsersproject();
		$this->outputData['projectList'] =  $projectList->result();	
		//----------- inbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		
		//Get all the messages from message table
		//Check the keyword is set or not
		if(is_numeric($this->session->userdata('keyword')))
		     $conditions_message = array('messages.to_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->session->userdata('keyword'))
		  {
		     if(!is_numeric($this->session->userdata('keyword')))
 		       $conditions_message = array('messages.to_id'=>$user_id,'messages.from_id'=>$keyword);
		  }	   
		else
		     $conditions_message = array('messages.to_id'=>$user_id); 	 
		
		$usersInboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		 //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('mail/searchInbox');
		$config['total_rows'] 	 = $usersInboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_inbox']   = $this->pagination->create_links2(false,'searchInbox');
		$this->outputData['usersInboxtotal'] =  $usersInboxtotal->result();
		$usersInboxtotal->num_rows();
		
		//Check the keyword is set or not		
		if(is_numeric($this->session->userdata('keyword')))
		     $conditions_message = array('messages.to_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->session->userdata('keyword'))
		  {
			if(!is_numeric($this->session->userdata('keyword')))
				 $conditions_message = array('messages.to_id'=>$user_id,'messages.from_id'=>$keyword);	 
		  }		 
		else
		     $conditions_message = array('messages.to_id'=>$user_id);
			  
		$limit[0]			 = $page_rows;
		$limit[1]			 = '0';
		 
		 //Get all message trasaction with some limit
		$usersInbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersInbox'] =  $usersInbox->result();
		 $usersInbox->num_rows();
		$this->outputData['totalInbox'] =  count($usersInbox->result());
       //-----------inbox mail end here ---------------//
		
		//----------- outbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		
		//Get all the messages from message table
		//Check the keyword is set or not		
		if(is_numeric($this->session->userdata('keyword')))
		     $conditions_message = array('messages.from_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->session->userdata('keyword'))
		  {
			if(!is_numeric($this->session->userdata('keyword')))
				 $conditions_message = array('messages.from_id'=>$user_id,'messages.to_id'=>$keyword);	 
		  }		 
		else
		     $conditions_message = array('messages.from_id'=>$user_id);
			 
		$usersOutboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersOutboxtotal'] =  $usersOutboxtotal->result();
		
		//Check the keyword is set or not		
		if(is_numeric($this->session->userdata('keyword')))
		     $conditions_message = array('messages.from_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->session->userdata('keyword'))
		  {
			if(!is_numeric($this->session->userdata('keyword')))
				 $conditions_message = array('messages.from_id'=>$user_id,'messages.to_id'=>$keyword);	 
		  }		 
		else
		     $conditions_message = array('messages.from_id'=>$user_id);
		
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start;
		 
		 //Get all message trasaction with some limit
		$usersOutbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersOutbox'] =  $usersOutbox->result();
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('mail/searchOutbox');
		$config['total_rows'] 	 = $usersOutboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'searchOutbox');
		$this->outputData['totalOutbox'] =  count($usersOutbox->result());
       //-----------outbox mail end here ---------------//
	    $this->load->view('messages/viewMail',$this->outputData);	
	}// function end here
//	--------------------------------------

  /**
	 *  view all mail list to the particular user
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function inbox()
	{	
	    //language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		$k=0;
		
		//Get page value
	    $start =  $this->uri->segment(3,'0');;
		
		//Get Project details who are all post projects
		
     	if($this->input->post('keyword'))
		  {
		  	$this->outputData['keyword'] = $this->input->post('keyword'); 
			$user_id     =$this->loggedInUser->id;
			$k=0;
		  }
		if($this->input->post('keyword') == '')
		  {
		  	$k=1;
		  }
		$user_id     =$this->loggedInUser->id;  	
		$conditions = array('projects.creator_id'=>$user_id);
		$postuserslist	   =  $this->skills_model->getUsersproject($conditions);
		
		//Get logged user role
		$this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;;

		//Get the users details
		$usersList	   =  $this->user_model->getUserslist();
		$this->outputData['usersList'] =  $usersList->result();	
		
		//Get the projects details
		$projectList	   =  $this->skills_model->getUsersproject();
		$this->outputData['projectList'] =  $projectList->result();	
		
		//----------- inbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		
		//Get all the messages from message table
		$conditions_message = array('messages.to_id'=>$user_id);
		$usersInboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersInboxtotal'] =  $usersInboxtotal->result();
		$usersInboxtotal->num_rows();
				
		$conditions_message = array('messages.to_id'=>$user_id);
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start;
		 
		 //Get all message trasaction with some limit
		$usersInbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersInbox'] =  $usersInbox->result();
		$usersInbox->num_rows();
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('mail/inbox');
		$config['total_rows'] 	 = $usersInboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_inbox']   = $this->pagination->create_links2(false,'inbox');
		$this->outputData['totalInbox'] =  count($usersInbox->result());
       //-----------inbox mail end here ---------------//
		
		//----------- outbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		
		//Get all the messages from message table
		$conditions_message = array('messages.from_id'=>$user_id);
		$usersOutboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersOutboxtotal'] =  $usersOutboxtotal->result();
		$usersOutboxtotal->num_rows();
		
		$conditions_message = array('messages.from_id'=>$user_id);
		 //Get all message trasaction with some limit
		$limit[0]			 = $page_rows;
		$limit[1]			 = '0';
		$usersOutbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersOutbox'] =  $usersOutbox->result();
		 //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('mail/outbox');
		$config['total_rows'] 	 = $usersOutboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = '0';
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'outbox');
		$this->outputData['totalOutbox'] =  count($usersOutbox->result());
		$usersOutbox->num_rows();		
       //-----------outbox mail end here ---------------//
	    $this->load->view('messages/viewMail',$this->outputData);	
	}// function end here
//	--------------------------------------
	
	/**
	 *  view all mail list to the particular user
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function outbox()
	{	
	//language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		$k=0;
		
		//Get page value
	    $start = $this->uri->segment(3,'0');
		
		//Get Project details who are all post projects
		
     	if($this->input->post('keyword'))
		  {
		  	$this->outputData['keyword'] = $this->input->post('keyword'); 
			$user_id     =$this->loggedInUser->id;
			$k=0;
		  }
		if($this->input->post('keyword') == '')
		  {
		  	$k=1;
		  }
		$user_id     =$this->loggedInUser->id;  	
		$conditions = array('projects.creator_id'=>$user_id);
		$postuserslist	   =  $this->skills_model->getUsersproject($conditions);
		
		//Get logged user role
		$this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		
		//Get the users details
		$usersList	   =  $this->user_model->getUserslist();
		$this->outputData['usersList'] =  $usersList->result();	
		
		//Get the projects details
		$projectList	   =  $this->skills_model->getUsersproject();
		$this->outputData['projectList'] =  $projectList->result();	
		//----------- inbox mail start here ---------------//
     	$page_rows         					 =  $this->config->item('mail_limit');; 

		//Get all the messages from message table
		$conditions_message = array('messages.to_id'=>$user_id);
		$usersInboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersInboxtotal'] =  $usersInboxtotal->result();
		$usersInboxtotal->num_rows();
		
		$conditions_message = array('messages.to_id'=>$user_id);
		 //Get all message trasaction with some limit
		$limit[0]			 = $page_rows;
		$limit[1]			 = '0'; 
		 
		$usersInbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersInbox'] =  $usersInbox->result();
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('messages/inbox');
		$config['total_rows'] 	 = $usersInboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = '0';
		$this->pagination->initialize($config);		
		$this->outputData['pagination_inbox']   = $this->pagination->create_links2(false,'inbox');
		$this->outputData['totalInbox'] =  count($usersInbox->result());
		$usersInbox->num_rows();		
       //-----------inbox mail end here ---------------//
		
		//----------- outbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		
		//Get all the messages from message table
		$conditions_message = array('messages.from_id'=>$user_id);
		$usersOutboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersOutboxtotal'] =  $usersOutboxtotal->result();
		$usersOutboxtotal->num_rows();
		$conditions_message = array('messages.from_id'=>$user_id);
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start;
		 
		 //Get all message trasaction with some limit
		$usersOutbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersOutbox'] =  $usersOutbox->result();
		$usersOutbox->num_rows();
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('mail/outbox');
		$config['total_rows'] 	 = $usersOutboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'outbox');
		$this->outputData['totalOutbox'] =  count($usersOutbox->result());
       //-----------outbox mail end here ---------------//
	   
	    $this->load->view('messages/viewMail',$this->outputData);	
	}// function end here
//	--------------------------------------
	
	
	
	
	/**
	 * Post mail Messages Related To A Project
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function composeMail()
	{	
	    //Assign the value for check the button is preview or submit
		$preview = FALSE;
		
		//Assign the value
		$this->outputData['previewMessages'] = array();
		
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/postMessage', $this->config->item('language_code'));

		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');		
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		
		//Get Project Id
		$user_id	 = $this->loggedInUser->id;
		$logged_userrole   =  $this->loggedInUser->role_id;
		 
		//Get all the users projects who are all won the projects
		if($logged_userrole == '2')
		   $conditions = array('projects.seller_id'=>$user_id);
        if($logged_userrole == '1')
		   $conditions = array('projects.creator_id'=>$user_id);		   
		$wonProjects  = $this->skills_model->getUsersproject($conditions);
		$this->outputData['wonProjects'] = $wonProjects->result();
		 
		 //Get all the users lists
		 $usersList  = $this->user_model->getUsers();
		 $this->outputData['usersList'] = $usersList->result(); 
		 
		$conditions = array('messages.from_id'=>$user_id);
		$userslist  = $this->messages_model->getProjectMessages();
		$this->outputData['projects']	   =  $userslist ;
		
		//Get Form Data	
		if($this->input->post('postMessage') or $this->input->post('previewMessage'))
		{	
			//Set rules
			$this->form_validation->set_rules('message','lang:message_validation','required|min_length[5]|trim|xss_clean');

			if($this->form_validation->run())
			{
                  //Get the message posted user name
				  $condition = array('users.user_name'=>$this->input->post('to'));
				  $userId  = $this->user_model->getUsers($condition);
				  $user_id     = $userId->row();
				  $this->outputData['user_name'] = $this->input->post('to');
				   
				  $insertData              		  	= array();	
			      $insertData['project_id']  	  	= '0';
				  $insertData['to_id']      		= $user_id->id;
				  $insertData['from_id']    	  	= $this->loggedInUser->id;
				  $insertData['message']       		= $this->input->post('message');
				  $insertData['created']       		= get_est_time();
				  
				  //Get Username
				  $to_id      		                = $this->input->post('to');
				  if(!is_numeric($to_id))
				  {
				  		$users_list=$this->messages_model->getUsers();
						foreach($users_list as $message)
				  			{
				   			if($this->input->post('to')    === $message->user_name)
							  {
							  		$insertData['to_id']  =   $message->id;
							  }
							}  
				  }
				  if($this->input->post('previewMessage'))
				     {
					 	$this->outputData['previewMessages'] =  $insertData;
						$preview = TRUE;
					 }
				  if($this->input->post('postMessage'))
				     {					
				       //Create Projects
				      $this->messages_model->postMessage($insertData);	
					  $preview = FALSE;
					 } 
				  //echo $preview;
				  if($preview == FALSE)
				    {
					  //Notification message
					  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Message Has Been Posted Successfully')));
					  redirect('info/index/success');			
					}
			}//If End - Form Validation
		}//If End - Check For Form Submission
		
		//Get Project Id
		if($this->uri->segment(3))
		{
			//Get project id for post message for the particular project
			$project_id	 = $this->uri->segment(3,'0');
			$conditions = array('projects.id'=>$project_id);
			$this->outputData['projects']	   =  $this->skills_model->getProjects($conditions);
		}
		 if(isLoggedIn()===false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged to post messages on the Project Message Board')));
			redirect('info/index/success');	
		}
		$this->load->view('messages/composeMail',$this->outputData);	
	} //Function composemail End
}

//End  Mail Class

/* End of file Mail.php */ 
/* Location: ./app/controllers/Mail.php */