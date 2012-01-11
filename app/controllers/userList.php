<?php    
/**
 * Reverse bidding system Mail Class
 *
 * Programmer related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Buyer 
 * @author		Cogzidel Dev Team
 * @version		Version 1.0
 * @created		Feburary 04 2009
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
class UserList extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	public $logUser;   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */ 
	function UserList()
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
		$this->load->model('user_model');
		$this->load->model('messages_model');
		$this->load->model('certificate_model');
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		//echo $logUser                       = $this->loggedInUser->role_id;
		
		
		//Get Footer content
		$this->outputData['pages']	= $this->common_model->getPages();	
		
		//Get logged user role
		if($this->loggedInUser)
		  {
		   $this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		  }
			
		//Get Latest Projects
		$limit_latest = $this->config->item('latest_projects_limit');
		$limit3 = array($limit_latest);
		$this->outputData['latestProjects']	= $this->skills_model->getLatestProjects($limit3);
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		$this->lang->load('enduser/userlist', $this->config->item('language_code'));
		
		//Innermenu tab selection
		$this->outputData['innerClass7']   = '';
		$this->outputData['innerClass7']   = 'selected';
	   
	   //Get all suers list
	   $usersList	   =  $this->user_model->getUserslist();
	   $this->outputData['usersList'] =  $usersList->result();	
	   
	   if($this->loggedInUser)
	   {
	   //Get Favouriteusers and Blocked User List
		$this->load->model('user_model');
		$conditions  = array('user_list.creator_id'=>$this->loggedInUser->id);
		$this->outputData['favouriteUsers']  = $this->user_model->getFavourite($conditions);
	   }
	} //Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Load users for the blocked and favourite users for the logged user
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function index()
	{	
		 if($this->loggedInUser)
	       {
		    //Get all users list
			$this->outputData['usersLists'] = $this->user_model->getUsers();
			 //Load the userlist view
		    $this->load->view('users/userList',$this->outputData);	
		   }	
		 else
		   {
		   		$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
				redirect('info');	
		   }  
	   
	} //Function index End
//-----------------------------------------------------------------------------------//


   /**
	 * contact programmers
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function contactProgrammer()
	{	
		//Load language file
		$this->lang->load('enduser/postMessage', $this->config->item('language_code'));
		
		//Get Project Id
		if($this->uri->segment(3))
		   {
		   $this->outputData['touser']	 = $this->uri->segment(3,'0');
		   }
		
		
		if($this->uri->segment(3,0))
		   $this->outputData['touser'] =  $this->uri->segment(3,0);
		if($this->input->post('toId'))   
		   $this->outputData['touser'] =  $this->input->post('toId',TRUE);
		if($this->input->post('projectName',TRUE))
		   $this->outputData['projectName'] = $this->input->post('projectName',TRUE);
		   
		$conditions  = array('user_list.creator_id'=>$this->loggedInUser->id,'user_list.user_role'=>'1');
		$this->outputData['favouriteUsers']  = $this->user_model->getFavourite($conditions);
  
		//Get Projects List
		$condition = array('projects.project_status' => '0','projects.creator_id'=>$this->loggedInUser->id,'projects.project_status'=>'0');
		$this->outputData['projectsList']	= $this->skills_model->getProjects($condition);
		//pr($this->outputData['projectsList']);
		//Get the users details
		$usersList	   =  $this->user_model->getUserslist();
		$this->outputData['usersList'] =  $usersList->result();	
		if($this->input->post('postMessage') or $this->input->post('previewMessage'))
		{
		   //Set rules
		  $this->form_validation->set_rules('message','lang:message_validation','required|trim|min_length[5]|xss_clean');
		  if($this->form_validation->run())
			{
				$insertData              		= array();	
				$insertData['project_id']       = $this->input->post('projectName',TRUE);
				$insertData['to_id']      		= $this->input->post('toId',TRUE);
				$insertData['from_id']    	  	= $this->loggedInUser->id;
				$insertData['message']       	= $this->input->post('message',TRUe);
				$insertData['created']       	= get_est_time();
				if($this->input->post('previewMessage'))
				     {
					 	$this->outputData['previewMessages'] =  $insertData;
						$preview = TRUE;
					 }
				  if($this->input->post('postMessage'))
				     {					
				       //Create Projects
				      $this->load->model('messages_model');
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
				
			}
		}	
		//Load the userlist view
		
		$this->load->view('messages/contactProgrammer',$this->outputData);	
	   
	} //Function contactprogrammer End
  //--------------------------------------------------------------------------------------//	
  
  /**
	 * inviteProgrammer 
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function inviteProgrammer()
	{	
		//Load language file
		$this->lang->load('enduser/postMessage', $this->config->item('language_code'));
		$this->lang->load('enduser/userlist', $this->config->item('language_code'));
		   
		$userid   =    $this->uri->segment(3,0);
		$this->outputData['userid'] =  $userid;
		
		$conditions2 = array('users.id' => $userid);
		$this->outputData['touser']= $this->user_model->getUsers($conditions2);
		
		$conditions  = array('user_list.creator_id'=>$this->loggedInUser->id,'user_list.user_role'=>'1');
		$this->outputData['favouriteUsers']  = $this->user_model->getFavourite($conditions);
  
		//Get Projects List
		$condition = array('projects.project_status' => '0','projects.creator_id'=>$this->loggedInUser->id,'projects.project_status'=>'0');
		$result	= $this->skills_model->getProjects($condition);
		$this->outputData['projectsList'] = $result->result();
		   
		if($this->loggedInUser)
		  {
			
			$condition = array('projects.creator_id'=>$this->loggedInUser->id,'projects.project_status'=>'0');
			$res = $this->skills_model->getUsersproject($condition);
			if($res->num_rows() > 0 )
			{
		    if($this->input->post('inviteProgrammer'))
			  {
				if($this->input->post('projects'))
				{
					//Load model
					$this->load->model('email_model');
					$userid = $this->input->post('toid');
					$conditions2 = array('users.id' => $userid);
					$buyer = $this->user_model->getUsers($conditions2);
					$buyerRow = $buyer->row();
		
					//Send Mail to programmer as invitaion
					$conditionUserMail = array('email_templates.type'=>'privateInvitation');
					$result            = $this->email_model->getEmailSettings($conditionUserMail);
					
					$rowUserMailConent = $result->row();
					
					$list = $this->input->post('projects');
					$projectnames = '"';
					$project_ids = '';
					foreach($list as $res)
					  {
						$condition = array('projects.id' => $res);
						$result	= $this->skills_model->getProjects($condition); 
						foreach($result->result() as $rec)
						   $projectnames .= $rec->project_name.',';
						   
					  }
					$projectnames .= '"';
					$url = '';
					
					if(isset($list)) 
					  {
					    if(count($list) > 0)
						 {
						   foreach($list as $res)
						     {
						     $url .= site_url('project/view/'.$res).'<br>';
							 
							 }
						 } 
						 else
						     $url = site_url('project/view/'.$res);
							 							 
					  }
					
					$splVars = array("!buyername!" => $this->loggedInUser->user_name, "(!projectname!)" => $projectnames, "!projecturl!" => $url ,"!siteurl!" => $this->config->item('base_url'));
					$mailSubject = $rowUserMailConent->mail_subject;
					$mailContent = strtr($rowUserMailConent->mail_body, $splVars);
					$toEmail = $buyerRow->email;
					$fromEmail = $this->config->item('site_admin_mail');
					$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
					
					$project_id = implode(',',$list); 
					if($this->input->post('toid'))
					{
						 //Insert values to the project invitaion lists
						$insertData        = array();
						$insertData['id']  = '';
						$insertData['project_id']   = $project_id;
						$insertData['sender_id']    = $this->loggedInUser->id;
						$insertData['receiver_id']  = $this->input->post('toid');
						$insertData['invite_date']  = get_est_time();
						$this->user_model->insertProgrammerInvitation($insertData);
					}
					
					$others = $this->input->post('otheruser');
					$others = explode(',',$others);
					foreach($others as $user)
					{
						$conditions2 = array('users.user_name' => $user,'users.role_id'=>'2');
						$buyer = $this->user_model->getUsers($conditions2);
						$buyerRow = $buyer->row();
						
						if($buyerRow)
						{
						   $toEmail = $buyerRow->email;
						   $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
						   
						   //Insert values to the project invitaion lists
						   $insertData        = array();
						   $insertData['id']  = '';
						   $insertData['project_id']   = $project_id;
						   $insertData['sender_id']    = $this->loggedInUser->id;
						   $insertData['receiver_id']  = $buyerRow->id;
						   $insertData['invite_date']  = get_est_time();
						   $this->user_model->insertProgrammerInvitation($insertData);
						}
					}
					
					//Notification message
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Message Has Been Posted Successfully')));
					redirect('info/index/success');	
			     }	
			  else
			     {
				 	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Choose Project')));
					redirect('info');
				 }	 
             }	 
			}
	      else
		    {
				//Notification message
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be post project to invite programmers')));
			redirect('info');	
			}
		}
	  else
	    {
			//Notification message
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged to invite programmers')));
			redirect('info');
		}	
		$this->load->view('buyer/inviteIndividual',$this->outputData);
	} //Function inviteprogrammer End
  //--------------------------------------------------------------------------------------//		
	
	/**
	 * add new users as favourite user to the logged user
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function addFavourite()
	{	
			//Get Favouriteusers and Blocked User List
			$this->load->model('user_model');
			$conditions  = array('user_list.creator_id'=>$this->loggedInUser->id);
			$this->outputData['favouriteUsers']  = $this->user_model->getFavourite($conditions);
			
			//Get the creater role 
			$creator_role =  $this->input->post('creator_role');
			
			//Get all the users list and check if the user can make correct favorite or not
			//Get the users details
			$usersList	   =  $this->user_model->getUserslist();
			$this->outputData['usersList'] =  $usersList->result();	
			//pr($usersList->result());
			
			//Set rule to input field
			$this->form_validation->set_rules('add_favourite','lang:username','required|trim|xss_clean');
			if($this->form_validation->run())
			{
				
				if($this->input->post('add_favourite') == $this->loggedInUser->user_name)
				  {
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('favourite themself')));
					redirect('userList');
				  }
				if($this->input->post('add_favourite'))
				{
					//For loop to check the userrole
					foreach($usersList->result() as $rec)
					  {
						//check the username with the given input value to add favourite
						if($rec->user_name == $this->input->post('add_favourite'))
						   {
							  //check the creator role id with the add favourite user role id
							  if ( $rec->role_id == $creator_role )
								 {
								   //check the logged user role id
								   if($creator_role == '1')
										$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Enter programmer name only')));
								   if($creator_role == '2')
										$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Enter buyer name only')));
								   redirect('userList');
								 } 
						   }
					  }
				}
				
				$username = $this->input->post('add_favourite');
				
				//check the user is exists or not in the user)_list table
				$conditions  = array('user_list.user_name'=>$username,'user_list.user_role'=>'1','user_list.creator_id'=>$this->loggedInUser->id);
				$favouriteUsers  = $this->user_model->getFavourite($conditions);
				$res1 = $favouriteUsers->num_rows();
				if($res1 >0 )
				  {
				  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User already exists')));
			        redirect('userList');
				  }
				  
				//Check the same user already available in same format
				//check the user is exists or not in the user)_list table
				$conditions  = array('user_list.user_name'=>$username,'user_list.user_role'=>'2','user_list.creator_id'=>$this->loggedInUser->id);
				$favouriteUsers  = $this->user_model->getFavourite($conditions);
				$res2 = $favouriteUsers->num_rows();
				if($res2 >0 )
				  {
				  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User already exists')));
			        redirect('userList');
				  }
				
				//check the user name is exists or not in user table
				$this->load->model('messages_model');
				$conditions  = array('users.user_name'=>$username);
				$loggedusers = $this->messages_model->getLoggedUser($conditions);
				$res = count($loggedusers);
				if($res > 0)
				 {
					foreach($loggedusers as $userrole)
					  {
					   $user_id   =  $this->loggedInUser->role_id;
					  }
					$insertData['id']         = '';
					$insertData['creator_id'] = $this->loggedInUser->id;
					$insertData['user_id']    = $userrole->id;
					$insertData['user_name']  = $userrole->user_name;
					$insertData['user_role']  = $this->input->post('role');  
				    $this->user_model->addFavourite($insertData);
				    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your input data is updated')));
			        redirect('userList');
				 }
				else
				 {
				 	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User does not Exists')));
			        redirect('userList');
				 } 
			}
			else
			{
				//Get the users details
				$usersList	   =  $this->user_model->getUserslist();
				$this->outputData['usersList'] =  $usersList->result();	
				$this->load->view('users/userList',$this->outputData);
			}
	} //Function addFavourite End
//-------------------------------------------------------------------------------------------------
	
	
		
	/**
	 * add new users as blockedList user to the logged user
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function addBlock()
	{	
		//Get Favouriteusers and Blocked User List
			$this->load->model('user_model');
			$conditions  = array('user_list.creator_id'=>$this->loggedInUser->id);
			$this->outputData['favouriteUsers']  = $this->user_model->getFavourite($conditions);
			
			//Get the creater role 
			$creator_role =  $this->input->post('creator_role');
			
			//Get all the users list and check if the user can make correct favorite or not
			//Get the users details
			$usersList	   =  $this->user_model->getUserslist();
			$this->outputData['usersList'] =  $usersList->result();	
			
			
			
			//Set rule to input field
			$this->form_validation->set_rules('add_block','lang:username','required|trim|xss_clean');
			if($this->form_validation->run())
			{
				
				if($this->input->post('add_block') == $this->loggedInUser->user_name)
				  {
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('blocked themself')));
					redirect('userList');
				  }
				  
				if($this->input->post('add_block'))
				{
					//For loop to check teh userrole
					foreach($usersList->result() as $rec)
					  {
						//check the username with the given input value to add favourite
						if($rec->user_name == $this->input->post('add_block'))
						   {
							  //check the creator role id with the add favourite user role id
							  if ( $rec->role_id == $creator_role )
								 {
								   //check the logged user role id
								   if($this->loggedInUser->role_id == '1')
										$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Enter programmer name only')));
								   if($this->loggedInUser->role_id == '2')
										$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Enter buyer name only')));
								   redirect('userList');
								 } 
						   }
					  }
				}
				
				
				$username = $this->input->post('add_block');
				
				//check the user is exists or not in the user_list table as favourite user
				$conditions  = array('user_list.user_name'=>$username,'user_list.user_role'=>'1','user_list.creator_id'=>$this->loggedInUser->id);
				$favouriteUsers  = $this->user_model->getFavourite($conditions);
				$block_res1 = $favouriteUsers->num_rows();
				
				if($block_res1 >0 )
				  {
				  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User already exists')));
			        redirect('userList');
				  }
				
				//check the user is exists or not in the user_list table as blocked user
				$conditions  = array('user_list.user_name'=>$username,'user_list.user_role'=>'2','user_list.creator_id'=>$this->loggedInUser->id);
				$favouriteUsers  = $this->user_model->getFavourite($conditions);
				$block_res2 = $favouriteUsers->num_rows();
				
				if($block_res2 >0 )
				  {
				  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User already exists')));
			        redirect('userList');
				  }
				  
				//check the user name is exists or not in user table
				$conditions  = array('users.user_name'=>$username);
				$this->load->model('messages_model');
				$loggedusers = $this->messages_model->getLoggedUser($conditions);
				$res2 = count($loggedusers);
				if($res2 <= 0)
				 {
				 	$updateData['user_role']  = $this->input->post('role');  
				 	$conditions  = array('user_list.user_name'=>$username);			  
				    $this->user_model->updateFavourite($updateData,$conditions);
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User does not Exists')));
			        redirect('userList');
				 }
				if($res2 > '0')
				 {
					foreach($loggedusers as $userrole)
					  {
					   $user_id   =  $userrole->id;
					  }
					$insertData['id']         = '';
					$insertData['creator_id'] = $this->loggedInUser->id;
					$insertData['user_id']    = $userrole->id;
					$insertData['user_name']  = $userrole->user_name;
					$insertData['user_role']  = $this->input->post('role');  
				    $this->user_model->addFavourite($insertData);
				    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your input data is updated')));
			        redirect('userList');
				 }
				else
				 {
				 	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User does not Exists')));
			        redirect('userList');
				 } 
			}
			else
			{
				//Get the users details
				$usersList	   =  $this->user_model->getUserslist();
				$this->outputData['usersList'] =  $usersList->result();	
				$this->load->view('users/userList',$this->outputData);
			}
	   
	} //Function addBlocked End
//-----------------------------------------------------------------------------------------------------
	
	/**
	 * change the existing users as favourite user to the logged user
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function changeUser()
	{	
		$user_id = $this->uri->segment(3,0);
		
		//check the user name is exists or not in user table
		$conditions  = array('user_list.user_id'=>$user_id,'user_list.creator_id'=>$this->loggedInUser->id);
		$loggedusers = $this->user_model->getFavourite($conditions);
		foreach($loggedusers->result() as $res)
		  {
		  	$userrole = $res->user_role;
		  }
		
		$res = count($loggedusers);
		if($res > 0)
		 {
			if($userrole == '1')
			  {
			    $userrole = '2';
			  }
			else if($userrole == '2')
			  {
			  $userrole = '1';  
			  }
			$updateData['user_role']    = $userrole;
			$conditions  = array('user_list.user_id'=>$user_id,'user_list.creator_id'=>$this->loggedInUser->id);
			$this->user_model->updateFavourite($updateData,$conditions);
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your input data is updated')));
			redirect('userList');
	     }
	} //Function changeUser End
//-----------------------------------------------------------------------------------	
	
	
	/**
	 * delete the existing users for the logged user from user_list table
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function deleteUser()
	{	
		$user_id = $this->uri->segment(3,0);
		//check the user name is exists or not in user table
		$conditions  = array('user_list.user_id'=>$user_id);
		$loggedusers = $this->user_model->getFavourite($conditions);
		foreach($loggedusers->result() as $res)
		  {
		  	$userrole = $res->user_role;
		  }
		
		$res = count($loggedusers);
		if($res > 0)
		 {
			if($userrole == '1')
			  $userrole = '2';
			if($userrole == '2')
			  $userrole = '1';  
			$updateData['user_role']    = $userrole;
			$conditions  = array('user_list.user_id'=>$user_id,'user_list.creator_id'=>$this->loggedInUser->id);
			$this->user_model->deleteFavourite($conditions);
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your input data is updated')));
			redirect('userList');
	     }
	} //Function deleteUser End
//----------------------------------------------------------------------------	
		
	/**
	 * add new users as favourite user to the logged user from user profile
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function addFavouriteUsers()
	{	
			$new_buyer =  $this->uri->segment(3);
			//Get Favouriteusers and Blocked User List
			$this->load->model('user_model');
			$conditions  = array('user_list.creator_id'=>$this->loggedInUser->id);
			$this->outputData['favouriteUsers']  = $this->user_model->getFavourite($conditions);
			
			//Get the creater role 
			$creator_role =  $this->input->post('creator_role');
			
			//Get all the users list and check if the user can make correct favorite or not
			//Get the users details
			$usersList	   =  $this->user_model->getUserslist();
			$this->outputData['usersList'] =  $usersList->result();	
			$role = $this->loggedInUser->role_id;
			//For loop to check teh userrole
			foreach($usersList->result() as $rec)
			  {
			  	//check the username with the given input value to add favourite
				if($rec->id == $new_buyer)
				   {
				   	  //check the creator role id with the add favourite user role id
					  if ( $rec->role_id == $role )
						 {
						   //check the logged user role id
						   if($creator_role == '1')
						        $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Enter programmer name only')));
						   if($creator_role == '2')
						      	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Enter buyer name only')));
			               redirect('userList');
						 } 
				   }
			  }
			
			//Set rule to input field
			if($new_buyer)
			{
				$username = $new_buyer;
				$conditions  = array('users.id'=>$username);
				$logged = $this->messages_model->getLoggedUser($conditions);
				
				foreach($logged as $disp)
				  {
				  	$name = $disp->user_name;
				  }
				//check the user is exists or not in the user_list table as favourite user
				$conditions  = array('user_list.user_name'=>$name,'user_list.user_role'=>'1','user_list.creator_id'=>$this->loggedInUser->id);
				$favouriteUsers  = $this->user_model->getFavourite($conditions);
				$res1 = $favouriteUsers->num_rows();
				if($res1 >0 )
				  {
				  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User already exists')));
			        redirect('userList');
				  }
				  
				//Check the same user already available in same format
				//check the user is exists or not in the user_list table as blocked user
				$conditions  = array('user_list.user_name'=>$name,'user_list.user_role'=>'2','user_list.creator_id'=>$this->loggedInUser->id);
				$favouriteUsers  = $this->user_model->getFavourite($conditions);
				$res2 = $favouriteUsers->num_rows();
				if($res2 >0 )
				  {
				  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User already exists')));
			        redirect('userList');
				  }
				
				$rec = count($logged);
				if($rec < 0 )
				  {
				    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User does not Exists')));
			        redirect('userList');
				  }
				//check the user name is exists or not in user table
				$conditions  = array('users.user_name'=>$name);
				$loggedusers = $this->messages_model->getLoggedUser($conditions);
				$rec1 = count($loggedusers);
				if($rec1 > 0)
				 {
					foreach($loggedusers as $userrole)
					  {
					   $user_id   =  $userrole->id;
					  }
					$insertData['id']         = '';
					$insertData['creator_id'] = $this->loggedInUser->id;
					$insertData['user_id']    = $userrole->id;
					$insertData['user_name']  = $userrole->user_name;
					$insertData['user_role']  = '1';  
				    $this->user_model->addFavourite($insertData);
				    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your input data is updated')));
			        redirect('userList');
				 }
				else
				 {
				 	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User does not Exists')));
			        redirect('userList');
				 } 
			}
			else
			{
				$this->load->view('users/userList',$this->outputData);
			}
	} //Function addFavouriteUser End
//--------------------------------------------------------------------------------------	
	
	/**
	 * add new users as favourite user to the logged user
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function addBlockedUsers()
	{	
			$new_buyer =  $this->uri->segment(3);
			//Get Favouriteusers and Blocked User List
			$this->load->model('user_model');
			$conditions  = array('user_list.creator_id'=>$this->loggedInUser->id);
			$this->outputData['favouriteUsers']  = $this->user_model->getFavourite($conditions);
			
			//Get the creater role 
			$creator_role =  $this->input->post('creator_role');
			
			//Get all the users list and check if the user can make correct favorite or not
			//Get the users details
			$usersList	   =  $this->user_model->getUserslist();
			$this->outputData['usersList'] =  $usersList->result();	
			$role = $this->loggedInUser->role_id;
			//For loop to check teh userrole
			foreach($usersList->result() as $rec)
			  {
			  	//check the username with the given input value to add favourite
				if($rec->id == $new_buyer)
				   {
				   	  //check the creator role id with the add favourite user role id
					  if ( $rec->role_id == $role )
						 {
						   //check the logged user role id
						   if($creator_role == '1')
						        $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Enter programmer name only')));
						   if($creator_role == '2')
						      	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Enter buyer name only')));
			               redirect('userList');
						 } 
				   }
			  }
			
			//Set rule to input field
			if($new_buyer)
			{
				$username = $new_buyer;
				$conditions  = array('users.id'=>$username);
				$logged = $this->messages_model->getLoggedUser($conditions);
				
				foreach($logged as $disp)
				  {
				  	$name = $disp->user_name;
				  }
				//check the user is exists or not in the user_list table as favourite user
				$conditions  = array('user_list.user_name'=>$name,'user_list.user_role'=>'1','user_list.creator_id'=>$this->loggedInUser->id);
				$favouriteUsers  = $this->user_model->getFavourite($conditions);
				$res1 = $favouriteUsers->num_rows();
				if($res1 >0 )
				  {
				  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User already exists')));
			        redirect('userList');
				  }
				  
				//check the user is exists or not in the user_list table as blocked user
				$conditions  = array('user_list.user_name'=>$name,'user_list.user_role'=>'2','user_list.creator_id'=>$this->loggedInUser->id);
				$favouriteUsers  = $this->user_model->getFavourite($conditions);
				$res2 = $favouriteUsers->num_rows();
				if($res2 >0 )
				  {
				  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User already exists')));
			        redirect('userList');
				  }
				$rec = count($logged);
				if($rec < 0 )
				  {
				    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User does not Exists')));
			        redirect('userList');
				  }
				//check the user name is exists or not in user table
				$conditions  = array('users.user_name'=>$name);
				$loggedusers = $this->messages_model->getLoggedUser($conditions);
				$rec1 = count($loggedusers);
				if($rec1 > 0)
				 {
					foreach($loggedusers as $userrole)
					  {
					   $user_id   =  $userrole->id;
					  }
					$insertData['id']         = '';
					$insertData['creator_id'] = $this->loggedInUser->id;
					$insertData['user_id']    = $userrole->id;
					$insertData['user_name']  = $userrole->user_name;
					$insertData['user_role']  = '2';  
				    $this->user_model->addFavourite($insertData);
				    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your input data is updated')));
			        redirect('userList');
				 }
				else
				 {
				 	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('User does not Exists')));
			        redirect('userList');
				 } 
			}
			else
			{
				$this->load->view('users/userList',$this->outputData);
			}
	} //Function addBlockedUser End
//-------------------------------------------------------------------------------------------
}

//End  userlist Class
/* End of file Mail.php */ 
/* Location: ./app/controllers/userlist.php */