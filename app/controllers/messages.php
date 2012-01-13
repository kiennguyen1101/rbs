<?php     
/** 
 * Reverse bidding system Messages Class
 *
 * Project Messages related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Buyer 
 * @author		Cogzidel Dev Team
 * @version		Version 1.0
 * @created		December 31 2008
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
class Messages extends Controller { 

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Messages()
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
		$this->load->model('messages_model');
		$this->load->model('certificate_model');
		
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		
	    //Get Footer content
		$this->outputData['pages']	= $this->common_model->getPages();
		
		//Get Latest Projects
		$limit_latest = $this->config->item('latest_projects_limit');
		$limit3 = array($limit_latest);
		$this->outputData['latestProjects']	= $this->skills_model->getLatestProjects($limit3);
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
	    $this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		$this->lang->load('enduser/postMessage', $this->config->item('language_code'));
		//load Helpers
		$this->load->helpers('users');
	    if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		//Innermenu tab selection
		$this->outputData['innerClass1']   = '';
		$this->outputData['innerClass1']   = 'selected';
		
		//Load the session liberary
		$this->load->library('session');
	} //Controller End 
	// --------------------------------------------------------------------
	
	/**
	 * Load Messages Related To A Project
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function project()
	{
    	//language file
		$this->lang->load('enduser/editProfile', $this->config->item('language_code'));	
		
		//check user login or  not
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		$start = '0';
		
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		if($this->uri->segment(3))
		 {
			//Get Project Id
			$project_id	 = $this->uri->segment(3,'0');
			$conditions = array('projects.id'=>$project_id);
			$this->outputData['projects']	   =  $this->skills_model->getProjects($conditions);
			
			
			//Remove the session value
			$this->session->unset_userdata('project_id');
			if($this->uri->segment(3))
			   {
				 $this->session->set_userdata('project_id',$this->uri->segment(3,'0'));  
			   } 
			
			//Get all message trasaction with some limit
			$condition = array('messages.project_id'=>$project_id);
			$totalMessages 	 = $this->messages_model->getProjectMessages($condition);
			$this->outputData['messages1'] = $totalMessages; 
			$this->outputData['msgprojectid'] = $project_id;
			$limit[0]			 = $page_rows;
			$limit[1]			 = $start;
			 
			 //Get all message trasaction with some limit
			$totalMessages1 	 = $this->messages_model->getProjectMessages($condition,NULL,NULL,$limit);
			$this->outputData['messages'] = $totalMessages1; 
			
			//Pagination
			$this->load->library('pagination');
			$config['base_url'] 	 = site_url('messages/projectMessages');
			$config['total_rows'] 	 = $totalMessages->num_rows();		
			$config['per_page']     = $page_rows; 
			$config['cur_page']     = $start;
			$this->pagination->initialize($config);		
			$this->outputData['pagination_inbox']   = $this->pagination->create_links2(false,'projectMessages');
			$this->outputData['totalMessages'] =  count($totalMessages->result());
			$this->load->view('messages/projectMessages',$this->outputData);	
	    }
	} //Function signUp End
	// --------------------------------------------------------------------
	
	
	/**
	 * Reply the messages to the sender
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function messageReply()
	{	
	//Reply the messages to the sender
	//Get the message details
	$messageid     =  $this->uri->segment(3,0);
	$message_condition  = array('messages.id'=>$messageid);
	$messages           = $this->messages_model->getProjectMessages($message_condition);
	$message            = $messages->row();
	
	//Get the users details
	$from_id            = $message->from_id;
	$user_condition     = array('users.id'=>$from_id);
	$users              = $this->user_model->getUsers($user_condition);
	$user              = $users->row();
	//Get the projects details for the message
	$project_condition             =  array('projects.id'=>$message->project_id);
	$projects                      =  $this->skills_model->getUsersproject($project_condition);
	$project                       =  $projects->row();

	$this->outputData['messages']  =  $messages; 
	$this->outputData['users']     =  $users;
	$this->outputData['projects']  =  $projects;	
	
	//load validation libraray
	$this->load->library('form_validation');
		
	//Load Form Helper
	$this->load->helper('form');		
		
	//Intialize values for library and helpers	
	$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
	//Get Form Data	
		if($this->input->post('postMessage') or $this->input->post('previewMessage'))
		{	
			//Set rules
			$this->form_validation->set_rules('message','lang:message_validation','required|min_length[5]|trim|xss_clean');
			if($this->form_validation->run())
			{
				  $insertData              		  	= array();	
			      $insertData['project_id']  	  	= $project->id;
				  $insertData['to_id']      		= $user->id;
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
				  if($preview == FALSE)
				    {
					  //Notification message
					  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Message Has Been Posted Successfully')));
					  redirect('messages/viewMail/',$this->loggedInUser->id);			
					}
			}//If End - Form Validation
		}//If End - Check For Form Submission
	$this->load->view('messages/messageReply',$this->outputData);
	}	//Function message reply end
//-------------------------------------------------------------------------------------------------------	
	
	/**
	 * Load Messages Related To A Project
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function projectMessages()
	{	
			
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		
		$start = $this->uri->segment(3,0);
		
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		//Get Project Id
		if($this->session->userdata('project_id'))
		   $project_id	 = $this->session->userdata('project_id');
		
		$conditions = array('projects.id'=>$project_id);
		$this->outputData['projects']	   =  $this->skills_model->getProjects($conditions);
		
		//Get all message trasaction with some limit
		$msgcondition = array('messages.project_id'=>$project_id);
		$totalMessages 	 = $this->messages_model->getProjectMessages($msgcondition);
		$this->outputData['messages1'] = $totalMessages; 
		
		$limit[0]			 = $page_rows;
		$limit[1]			 = ($start - 1 )* $page_rows;
		 
		//Get all message trasaction with some limit
		$totalMessages1 	 = $this->messages_model->getProjectMessages($msgcondition,NULL,NULL,$limit);
		$this->outputData['messages'] = $totalMessages1; 
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('messages/projectMessages');
		$config['total_rows'] 	 = $totalMessages->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_inbox']   = $this->pagination->create_links2(false,'projectMessages');
		$this->outputData['totalMessages'] =  count($totalMessages->result());
	    $this->load->view('messages/projectMessages',$this->outputData);	
	} //Function signUp End
	// --------------------------------------------------------------------
	
	/**
	 * Post Messages Related To A Project
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function post()
	{	
		//language file
		$this->lang->load('enduser/editProfile', $this->config->item('language_code'));
		if(!isset($this->loggedInUser->id))
		{
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		}
		  
		  if($this->loggedInUser->suspend_status==1)
		 {
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Suspend Error')));
			redirect('info');
		 }
		  	
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/postMessage', $this->config->item('language_code'));
		
		//Assign the value for check the button is preview or submit
		$preview = FALSE;
		
		//Assign the value
		$this->outputData['previewMessages'] = array();
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
		if($this->uri->segment(3,0))
		{
			$condition = array('bids.project_id'=>$this->uri->segment(3,0));
		    $bidsUser          = $this->skills_model->getBids($condition);
			$this->outputData['bidUser'] = $bidsUser; 
		}	
		//print_r($biduser_id);
		//echo count($biduser_id);
		//Get Project Id
		if($this->uri->segment(3,'0'))
		   {
		     $project_id	 = $this->uri->segment(3,'0');
		   }
		else
		   {
		    $project_id  = $this->input->post('project_id');
		   }   
	    $conditions = array('projects.id'=>$project_id);
		$projectDetails   =  $this->skills_model->getProjects($conditions);
		$this->outputData['projects']	 = $projectDetails;
		$projectDetails   = $projectDetails->row();
		
		if($this->loggedInUser->role_id == '1')
		{
			if($projectDetails->creator_id != $this->loggedInUser->id )
			  {
				 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You cannot post Message for this project')));
				 redirect('info');
			  }
		}
		
		//Get all project Lists
		$this->outputData['projectsList']	   =  $this->skills_model->getProjects();
		
		$conditions_message = array('messages.project_id'=>$project_id);
		$message_user=$this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['messages']	   =$message_user;
		if($this->input->post('to') != '' or $this->input->post('toid') !='')
		  {
			//Get Form Data	
			if($this->input->post('postMessage') or $this->input->post('previewMessage'))
			{	
			
				
				
				//Set rules
				$this->form_validation->set_rules('message','lang:message_validation','required|min_length[5]|trim|xss_clean|callback_emailpresent_projectname_check|callback_emailpresent_check|callback__phonenumber_check|callback__phonenumber_projectname_check');
				$this->form_validation->set_rules('to','Programmer Id','trim|xss_clean');
				$this->form_validation->set_rules('toid','Programmer Id','trim|is_no|xss_clean');
				if($this->form_validation->run())
				{
					  $insertData              		  	= array();	
					  $insertData['project_id']  	  	= $this->input->post('project_id');
					  $insertData['to_id']      		= $this->input->post('to');
					  $insertData['from_id']    	  	= $this->loggedInUser->id;
					  $insertData['message']       		= $this->input->post('message');
					  $insertData['created']       		= get_est_time();
					  //pr($insertData);exit;
					  if($this->input->post('to') == '0')
						   $this->outputData['user_name']    = 'Everyone';
					  else
						   $this->outputData['user_name']    =  $this->input->post('to');   
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
							$this->outputData['to_id']           =   $this->input->post('to');
					   }
					   if($this->input->post('postMessage'))
					   {
						 //Create Projects
						 
						 //$this->messages_model->postMessage($insertData);
						//Get the receiver name 
						if($this->input->post('to') == '0')
						 {
							 //Get all username and send email to all users
								 $usercondition = array('users.role_id'=>'2'); 
								  $users          = $this->user_model->getUsers($usercondition);
								  $user          = $users->row();
								 // pr($users->result());
								  foreach($users->result() as $users_email)
								  {
								  $insertData              		= array();	
									$insertData['project_id']  	  	= $this->input->post('project_id');
									$insertData['to_id']      		= $users_email->id;
									$insertData['from_id']    	  	= $this->loggedInUser->id;
									$insertData['message']       	= $this->input->post('message');
									$insertData['created']       	= get_est_time();
									$this->messages_model->postMessage($insertData);
								 // echo $users_email->email; 
								  //send email to the buyer or programmer receive new mail or PMB
								  $this->load->model('email_model');
								  $conditionUserMail = array('email_templates.type'=>'message_template');
								  $result            = $this->email_model->getEmailSettings($conditionUserMail);
								  $rowUserMailConent = $result->row();
								  $splVars = array("!site_name" => $this->config->item('site_title'),"!username" =>$user->user_name,"!reason" =>'Project',"!sender_name" => $this->loggedInUser->user_name,"!site_url" => site_url(), "!contact_url" => site_url('contact'));
								  $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
								  $mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
								  $toEmail     = $users_email->email;
								  $fromEmail   = $this->config->item('site_admin_mail');
								  $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
								  }
								
						  }
						 
						if($this->input->post('to')) 	
						  {
							  $usercondition = array('users.id'=>$this->input->post('to')); 
							
							  $user          = $this->user_model->getUsers($usercondition);
							  
							  $user          = $user->row();
							 
							  if(isset($user))
							  {
							  
							  //Create Projects
							  $insertData              		  	= array();	
							  $insertData['project_id']  	  	= $this->input->post('project_id');
							  $insertData['to_id']      		= $user->id;
							  $insertData['from_id']    	  	= $this->loggedInUser->id;
							  $insertData['message']       		= $this->input->post('message');
							  $insertData['created']       		= get_est_time();
							  $this->messages_model->postMessage($insertData);
							  
							  //send email to the buyer or programmer receive new mail or PMB
							  $this->load->model('email_model');
							  $conditionUserMail = array('email_templates.type'=>'message_template');
							  $result            = $this->email_model->getEmailSettings($conditionUserMail);
							  $rowUserMailConent = $result->row();
							  
							  $splVars = array("!site_name" => $this->config->item('site_title'),"!username" =>$user->user_name,"!reason" =>'Project',"!sender_name" => $this->loggedInUser->user_name,"!site_url" => site_url(), "!contact_url" => site_url('contact'));
							  $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
							  $mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
							  $toEmail     = $user->email;
							  $fromEmail   = $this->config->item('site_admin_mail');
							  $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
							  }
						 }
						 if($this->input->post('toid')) 	
						  {
							  $toid = explode(',',$this->input->post('toid'));
							  foreach($toid as $id)
							  {
								  $usercondition = array('users.id'=>$id,'users.role_id'=>'2'); 
								  $users          = $this->user_model->getUsers($usercondition);
								  $user          = $users->row();
								  if(isset($user) and isset($user->email))
								  {
								  //send email to the buyer or programmer receive new mail or PMB
								  $this->load->model('email_model');
								  $conditionUserMail = array('email_templates.type'=>'message_template');
								  $result            = $this->email_model->getEmailSettings($conditionUserMail);
								  $rowUserMailConent = $result->row();
								  $splVars = array("!site_name" => $this->config->item('site_title'),"!username" =>$user->user_name,"!reason" =>'Project',"!sender_name" => $this->loggedInUser->user_name,"!site_url" => site_url(), "!contact_url" => site_url('contact'));
								  $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
								  $mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
								  $toEmail     = $user->email;
								  $fromEmail   = $this->config->item('site_admin_mail');
								  $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
								  }
							  }
						 }
					   }
					  if($this->input->post('postMessage'))
					   {
						  //Notification message
						  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Message Has Been Posted Successfully')));
						  redirect('messages/viewMail/',$this->loggedInUser->id);			
						}  
					
				}//If End - Form Validation
			}//If End - Check For Form Submission
		}
		 else
		  {
			 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please choose programmer to post message')));
		  }	
		//Get Project Id
		$conditions = array('projects.id'=>$project_id);
		$this->outputData['projects']	   =  $this->skills_model->getProjects($conditions);
		 if(isLoggedIn()===false)
		{
			 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged to post messages on the Project Message Board')));
			 redirect('info/index/success');	
		}
 	   if($this->input->post('to') == '' and $this->input->post('to_id') =='' and !isSeller())
		  {
		  	$this->load->view('messages/buyerPostmessage',$this->outputData);
		  }
		else
		{
			$this->load->view('messages/postMessage',$this->outputData);	
		}
	} //Function signUp End
//----------------------------------------------------------------------------------------------------------------------------	
	
	/**
	 *  view all mail list to the particular user
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function viewMail()
	{	
	    if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		
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
		if($this->loggedInUser)
		{
		$user_id     =$this->loggedInUser->id;  	
		$conditions = array('projects.creator_id'=>$user_id);
		$postuserslist	   =  $this->skills_model->getUsersproject($conditions);
		
		//Get logged user role
		   $this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		}
		//Get the users details
		$usersList	   =  $this->user_model->getUserslist();
		$this->outputData['usersList'] =  $usersList->result();	
		
		//Get the projects details
		$projectList	   =  $this->skills_model->getUsersproject();
		$this->outputData['projectList'] =  $projectList->result();	
		
		//----------- inbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		//Get all the messages from message table
		
		$conditions_message = array('messages.to_id'=>$user_id,'messages.deluserid'=>'');
		$usersInboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersInboxtotal'] =  $usersInboxtotal->result();
		$this->outputData['total_inbox'] =$usersInboxtotal->num_rows();
		//pr($this->outputData['usersInboxtotal']);
		$usersInboxtotal->num_rows();
			
		$conditions_message = array('messages.to_id'=>$user_id,'messages.deluserid'=>'');
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start;
		 
		 //Get all message trasaction with some limit
		$usersInbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersInbox'] =  $usersInbox->result();
	    $usersInbox->num_rows();
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('messages/inbox');
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
		$this->outputData['total_outbox']=$usersOutboxtotal->num_rows();
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
		$config['base_url'] 	 = site_url('messages/outbox');
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
	 *  view all mail list to the particular user
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function searchMail()
	{	
	    if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		
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
			 if($postuserslist->num_rows() > 0)
			  {
				 $postuserslist    = $postuserslist->row();
				 $keyword          = $postuserslist->id;
			  }
			 else
			  {
			  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','The keyword does not match in Mail List'));
				redirect('messages/viewMail');
			  } 
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
		
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('messages/searchInbox');
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
			 
		$usersOutboxtotal 	 = $this->messages_model->getProjectMessages1($conditions_message);
		$this->outputData['usersOutboxtotal'] =  $usersOutboxtotal->result();
		
		//Check the keyword is set or not		
		if(is_numeric($this->input->post('keyword')))
		     $conditions_message = array('messages.from_id'=>$user_id,'messages.project_id'=>$keyword);
		else if($this->input->post('keyword'))
		  {
			if(!is_numeric($this->input->post('keyword')))
			   {
				 $conditions_message = array('messages.from_id'=>$user_id,'messages.to_id'=>$keyword);	 
			   }	 
		  }		 
		else
		     $conditions_message = array('messages.from_id'=>$user_id);		
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start;
		 
		 //Get all message trasaction with some limit
		$usersOutbox 	 = $this->messages_model->getProjectMessages1($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersOutbox'] =  $usersOutbox->result();
		
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('messages/searchOutbox');
		$config['total_rows'] 	 = $usersOutboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'searchOutbox');
		$this->outputData['totalOutbox'] =  count($usersOutbox->result());
        //-----------outbox mail end here ---------------//
		if($usersInboxtotal->num_rows() =='0' and $usersOutboxtotal->num_rows() == '0')
		  {
  		    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','The keyword does not match in Mail List'));
			redirect('messages/viewMail');
		  }	
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
	   if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
	   
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
		$config['base_url'] 	 = site_url('messages/searchInbox');
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
			 
		$usersOutboxtotal 	 = $this->messages_model->getProjectMessages1($conditions_message);
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('messages/searchOutbox');
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
		$usersOutbox 	 = $this->messages_model->getProjectMessages1($conditions_message,NULL,NULL,$limit);
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
	    if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		
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
		$config['base_url'] 	 = site_url('messages/searchInbox');
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
			 
		$usersOutboxtotal 	 = $this->messages_model->getProjectMessages1($conditions_message);
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
		$usersOutbox 	 = $this->messages_model->getProjectMessages1($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersOutbox'] =  $usersOutbox->result();
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('messages/searchOutbox');
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
	    
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		
		//language file - Change this file to do display text modification
		$k=0;
		
		//Get page value
	    $start_inbox =  $this->uri->segment(3,'0');
		$start_outbox =  $this->uri->segment(3,'0');
		
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
     	$page_rows         					 =  $this->config->item('mail_limit'); 
		if($start_inbox > 0)
	       $start_inbox = ($start_inbox-1) * $page_rows;
		//Get all the messages from message table
		$conditions_message = array('messages.to_id'=>$user_id,'messages.to_id '=>'0','messages.deluserid'=>'');
		$usersInboxtotal 	 = $this->messages_model->getMessages($conditions_message);
		$this->outputData['usersInboxtotal'] =  $usersInboxtotal->result();
		$this->outputData['total_inbox'] =$usersInboxtotal->num_rows();		
		$conditions_message = array('messages.to_id'=>$user_id,'messages.to_id '=>'0','messages.deluserid'=>'');
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start_inbox;
		 
		 //Get all message trasaction with some limit
		$usersInbox 	 = $this->messages_model->getMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersInbox'] =  $usersInbox->result();
	    
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('messages/inbox');
		$config['total_rows'] 	 = $usersInboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start_inbox;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_inbox']   = $this->pagination->create_links2(false,'inbox');
		$this->outputData['totalInbox'] =  count($usersInbox->result());
       //-----------inbox mail end here ---------------//
		
		//----------- outbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');; 
		if($start_outbox > 0)
	       $start_outbox = ($start_outbox-1) * $page_rows;
		//Get all the messages from message table
		$conditions_message = array('messages.from_id'=>$user_id,'messages.deluserid'=>'');
		$usersOutboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersOutboxtotal'] =  $usersOutboxtotal->result();
		$this->outputData['total_outbox']=$usersOutboxtotal->num_rows();
		$usersOutboxtotal->num_rows();
		
		$conditions_message = array('messages.from_id'=>$user_id,'messages.deluserid'=>'');
		 //Get all message trasaction with some limit
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start_outbox;
		$usersOutbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersOutbox'] =  $usersOutbox->result();
		 //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('messages/outbox');
		$config['total_rows'] 	 = $usersOutboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start_outbox;
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
	    if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		$k=0;
		
		//Get page value
	     $start_inbox = $this->uri->segment(3,'0');
		 $start_outbox= $this->uri->segment(3,'0');
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
     	$page_rows         					 =  $this->config->item('mail_limit');
		if($start_inbox > 0)
	       $start_inbox = ($start_inbox-1) * $page_rows;

		//Get all the messages from message table
		$conditions_message = array('messages.to_id'=>$user_id,'messages.deluserid'=>'');
		$usersInboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersInboxtotal'] =  $usersInboxtotal->result();
		$this->outputData['total_inbox'] =$usersInboxtotal->num_rows();
		$usersInboxtotal->num_rows();
		
		
		$conditions_message = array('messages.to_id'=>$user_id,'messages.deluserid'=>'');
		 //Get all message trasaction with some limit
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start_inbox; 
		 
		$usersInbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersInbox'] =  $usersInbox->result();
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('messages/inbox');
		$config['total_rows'] 	 = $usersInboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start_inbox;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_inbox']   = $this->pagination->create_links2(false,'inbox');
		$this->outputData['totalInbox'] =  count($usersInbox->result());
       //-----------inbox mail end here ---------------//
		
		//----------- outbox mail start here ---------------//
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		if($start_outbox > 0)
	    $start_outbox = ($start_outbox-1) * $page_rows;
		
		//Get all the messages from message table
		$conditions_message = array('messages.from_id'=>$user_id,'messages.deluserid'=>'');
		$usersOutboxtotal 	 = $this->messages_model->getProjectMessages($conditions_message);
		$this->outputData['usersOutboxtotal'] =  $usersOutboxtotal->result();
		$this->outputData['total_outbox']=$usersOutboxtotal->num_rows();
		$usersOutboxtotal->num_rows();
				
		$conditions_message = array('messages.from_id'=>$user_id,'messages.deluserid'=>'');
		$limit[0]			 = $page_rows;
		$limit[1]			 = $start_outbox;
		
		 //Get all message trasaction with some limit
		$usersOutbox 	 = $this->messages_model->getProjectMessages($conditions_message,NULL,NULL,$limit);
		$this->outputData['usersOutbox'] =  $usersOutbox->result();
		$usersOutbox->num_rows();
	
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('messages/outbox');
		$config['total_rows'] 	 = $usersOutboxtotal->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start_outbox;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'outbox');
		$this->outputData['totalOutbox'] =  count($usersOutbox->result());
	    $this->load->view('messages/viewMail',$this->outputData);	
	}// function end here
//	--------------------------------------
	
	/**
	 * Post mail Messages Related To A Project
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function composeMail()
	{	
	    
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		
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
		
		//Get Project Id
		$user_id	       = $this->loggedInUser->id;
		$logged_userrole   =  $this->loggedInUser->role_id;
		 
		//Get all the users projects who are all won the projects
		if($logged_userrole == '2')
		   $conditions = array('projects.programmer_id'=>$user_id,'projects.project_status !='=>'2','projects.flag'=>'0');
        if($logged_userrole == '1')
		   $conditions = array('projects.creator_id'=>$user_id,'projects.project_status !='=>'2','projects.flag'=>'0');		   
		
		$wonProjects  = $this->skills_model->getUsersproject($conditions);
		$count = $wonProjects->num_rows();
		if($count <= 0 )
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('There is no open project to Post Mail')));
			redirect('info');
		  }
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
			$this->form_validation->set_rules('message','lang:message_validation','required|min_length[5]|trim|xss_clean|callback_emailpresent_projectname_check|callback_emailpresent_check|callback__phonenumber_check|callback__phonenumber_projectname_check');
			$this->form_validation->set_rules('to','lang:project_validation','required|trim|xss_clean');
		    $this->form_validation->set_rules('prog_id','lang:provider_validation','required|is_no|trim|xss_clean');
			if($this->form_validation->run())
			{
                  //Get the message posted user name
				  $userId  = $this->user_model->getUsers();
				  $this->outputData['userList'] =  $userId->result();
				  $user_id     = $userId->row();
				  $this->outputData['project_name'] = $this->input->post('to');
				
				  $conditions = array('projects.id'=>$this->input->post('to'),'projects.project_status !='=>'2');		   
    		      $wonProjects  = $this->skills_model->getUsersproject($conditions); 
				  
				  foreach($wonProjects->result() as $res)
				  	{
					   if($this->loggedInUser->role_id == '1')
					     {
					        $programmer_id =  $res->programmer_id;
						 }
					   else if($this->loggedInUser->role_id == '2')	 
					     {
					   	    $programmer_id  = $res->creator_id; 
						 }
					   else	
					     {
						 	$programmer_id = '0';
						 } 
					}
				  $insertData              		  	= array();	
			      $insertData['project_id']  	  	= $this->input->post('to');
				  $insertData['to_id']      		= $programmer_id;
				  $insertData['from_id']    	  	= $this->loggedInUser->id;
				  $insertData['message']       		= $this->input->post('message');
				  $insertData['created']       		= get_est_time();
				  
				  //Get Username
				  $to_id      		                = $this->input->post('to');
				  if($this->input->post('prog_id') == '0')
						   $this->outputData['user_name']    = 'Everyone';
					  else
						   $this->outputData['user_name']    =  $this->input->post('prog_id');  
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
					  if($this->input->post('prog_id') == '0')
						  {	
						  $usercondition = array('users.role_id'=>'2'); 
								  $users          = $this->user_model->getUsers($usercondition);
								  $user          = $users->row();
								  //pr($users->result());
								  foreach($users->result() as $users_email)
								  {
								    $insertData              		= array();	
									$insertData['project_id']  	  	= $this->input->post('to');
									$insertData['to_id']      		= $users_email->id;
									$insertData['from_id']    	  	= $this->loggedInUser->id;
									$insertData['message']       	= $this->input->post('message');
									$insertData['created']       	= get_est_time();
									$this->messages_model->postMessage($insertData);
								  //send email to the buyer or programmer receive new mail or PMB
								  $this->load->model('email_model');
								  $conditionUserMail = array('email_templates.type'=>'message_template');
								  $result            = $this->email_model->getEmailSettings($conditionUserMail);
								  $rowUserMailConent = $result->row();
								  $splVars = array("!site_name" => $this->config->item('site_title'),"!username" =>$user->user_name,"!reason" =>'Project',"!sender_name" => $this->loggedInUser->user_name,"!site_url" => site_url(), "!contact_url" => site_url('contact'));
								  $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
								  $mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
								  $toEmail     = $users_email->email;
								  $fromEmail   = $this->config->item('site_admin_mail');
								  $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
								  }
								
						  }
						 
						if($this->input->post('prog_id')) 	
						  {
							  $usercondition = array('users.id'=>$this->input->post('prog_id')); 
							
							  $user          = $this->user_model->getUsers($usercondition);
							  
							  $user          = $user->row();
							 
							  if(isset($user))
							  {
							  
							  //Create Projects
							  $insertData              		  	= array();	
							  $insertData['project_id']  	  	= $this->input->post('to');
							  $insertData['to_id']      		= $user->id;
							  $insertData['from_id']    	  	= $this->loggedInUser->id;
							  $insertData['message']       		= $this->input->post('message');
							  $insertData['created']       		= get_est_time();
							  $this->messages_model->postMessage($insertData);
							  
							  //send email to the buyer or programmer receive new mail or PMB
							  $this->load->model('email_model');
							  $conditionUserMail = array('email_templates.type'=>'message_template');
							  $result            = $this->email_model->getEmailSettings($conditionUserMail);
							  $rowUserMailConent = $result->row();
							  
							  $splVars = array("!site_name" => $this->config->item('site_title'),"!username" =>$user->user_name,"!reason" =>'Project',"!sender_name" => $this->loggedInUser->user_name,"!site_url" => site_url(), "!contact_url" => site_url('contact'));
							  $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
							  $mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
							  $toEmail     = $user->email;
							  $fromEmail   = $this->config->item('site_admin_mail');
							  $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
							  }
						 }			
				       /*//Create Projects
						  $this->messages_model->postMessage($insertData);
						  $usercondition = array('users.id'=>$insertData['to_id']); 
						  $user          = $this->user_model->getUsers($usercondition);
						  $user          = $user->row();
						  if(isset($user->email))  
						  {
						 //send email to the buyer or programmer receive new mail or PMB
						  $this->load->model('email_model');
						  $conditionUserMail = array('email_templates.type'=>'message_template');
						  $result            = $this->email_model->getEmailSettings($conditionUserMail);
						  $rowUserMailConent = $result->row();
						  $splVars = array("site_name" => $this->config->item('site_title'),"username" =>$user->user_name,"reason"=>'Project',"sender_name" => $this->loggedInUser->user_name,"!site_url" => site_url(), "contact_url" => site_url('contact'));
						  
						  $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
						  $mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
						  $toEmail     = $user->email;
						  $fromEmail   = $this->config->item('site_admin_mail');
						  $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
					  	  }
						  //Get the receiver name 
						else
						  {
							  //Get all username and send email to all users
							  $user          = $this->user_model->getUsers();
							  foreach($user->result() as $user) 
							  {
								 //send email to the buyer or programmer receive new mail or PMB
								  $this->load->model('email_model');
								  $conditionUserMail = array('email_templates.type'=>'message_template');
								  $result            = $this->email_model->getEmailSettings($conditionUserMail);
								  $rowUserMailConent = $result->row();
								   pr($rowUserMailConent); 
								  $splVars = array("site_name" => $this->config->item('site_title'),"username" =>$user->user_name,"reason" =>'Project' ,"!sender_name" => $this->loggedInUser->user_name,"site_url" => site_url(), "contact_url" => site_url('contact'));
								  $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
								  $mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
								  $toEmail     = $user->email;
								  $fromEmail   = $this->config->item('site_admin_mail');
								  $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
							  }*/
						  //}
					  $preview = FALSE;
					 } 
				  if($preview == FALSE)
				    {
					  //Notification message
					  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Message Has Been Posted Successfully')));
					  redirect('messages/viewMail/',$this->loggedInUser->id);			
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
	} //Function signUp End
	
	// Puhal Chnages Start to remove E-mail fom the Inbox (Sep 18 Issue 6)

	function deleteInbox()
	{
	
	 $inbox = $this->input->post('inbox');
	
	  for($i=0;$i<count($inbox);$i++)
	     {
	  		$tablename='messages'; 
			$userid=$this->loggedInUser->id;
	  		
			if($inbox[$i]!='')
			{
			    $conditions=array('id'=>$inbox[$i]);
			    $deluser=$this->common_model->getTableData($tablename,$conditions);
			    $deluser_result=$deluser->row();
			    $deluserid=$deluser_result->deluserid;
				
				$deluseridarr=explode(',',$deluserid);
				$del=array();
				$del[]=$userid;
				$res=array_merge($deluseridarr,$del);
				$resstr=implode(',',$res);
			}
			else
			{
			   $resstr=$userid;
			    $this->session->set_flashdata('flash_message1', $this->common_model->flash_message('error',$this->lang->line('please select deleted mail')));
		redirect('messages/viewMail');	
			}
			$updateData = array('deluserid'=>$resstr);
			$this->common_model->updateTableData($tablename,$inbox[$i],$updateData,NULL);
			 }
			$this->session->set_flashdata('flash_message1', $this->common_model->flash_message('error',$this->lang->line('message deleted successfully')));
		redirect('messages/viewMail');  	
	}
	
	/**
	 * delete outbox
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function deleteOutbox()
	{
	
	  $outbox  = $this->input->post('outbox');
         for($i=0;$i<count($outbox);$i++)
	       {
	  		$tablename='messages';
			$userid=$this->loggedInUser->id;
			if($outbox[$i]!='')
			{
				$conditions=array('id'=>$outbox[$i]);
				$deluser=$this->common_model->getTableData($tablename,$conditions);
				$deluser_result=$deluser->row();
				$deluserid=$deluser_result->deluserid;
				$deluseridarr=explode(',',$deluserid);
				$del=array();
				$del[]=$userid;
				$res=array_merge($deluseridarr,$del);
				$resstr=implode(',',$res);
				}
			else
			{
			
			   $resstr=$userid;
			   $this->session->set_flashdata('flash_message2', $this->common_model->flash_message('error',$this->lang->line('please select deleted mail')));
		redirect('messages/viewMail');	
			}
			$updateData = array('deluserid'=>$resstr);
			$this->common_model->updateTableData($tablename,$outbox[$i],$updateData,NULL);	
	  }
	  $this->session->set_flashdata('flash_message2', $this->common_model->flash_message('error',$this->lang->line('message deleted successfully')));
		redirect('messages/viewMail');	
	}
	
	function emailpresent_check()
	{	
			
		$description=$_POST['message'];
		$reg = '/[\s]*[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/';

		if(preg_match($reg, $description)) {

		$this->form_validation->set_message('emailpresent_check','Emails Not Allowed');
		return FALSE;
	}
	else
	{
		return TRUE;
	}
}
// For project name  field (Check for E-mail address) 	
 function emailpresent_projectname_check()
{
	$description=$_POST['message'];

$reg = '/[\s]*[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/';

if(preg_match($reg, $description)) {

$this->form_validation->set_message('emailpresent_projectname_check','Emails Not Allowed');
return FALSE;
}
else
{
return TRUE;
}
}		

function _phonenumber_check()
	{
		$description=$_POST['message'];
		//$reg = '/(\d)?(\s|-)?(\()?(\d){3}(\))?(\s|-){1}(\d){3}(\s|-){1}(\d){4}/';
		$reg="/\(?[0-9]{1}\)?[-. ]?[0-9]{1}[-. ]?[0-9]{1}/";

  		 if(preg_match($reg, $description)) {   
	    
              $this->form_validation->set_message('_phonenumber_check','Phone numbers Not Allowed');
			  return FALSE;
		}
		else
		{
          	return TRUE;
         }
       
  	}


// For project name  field (Check for Phone number) 		 
	function _phonenumber_projectname_check()
	{
		$projectName=$_POST['message'];
		//$reg = '/(\d)?(\s|-)?(\()?(\d){3}(\))?(\s|-){1}(\d){3}(\s|-){1}(\d){4}/';
		$reg="/\(?[0-9]{1}\)?[-. ]?[0-9]{1}[-. ]?[0-9]{1}/";

  		 if(preg_match($reg, $projectName)) {   
	    
              $this->form_validation->set_message('_phonenumber_projectname_check','Phone numbers Not Allowed');
			  return FALSE;
		}
		else
		{
          	return TRUE;
         }
       
  	}	
	
}
//End  Messages Class

/* End of file Messages.php */ 
/* Location: ./app/controllers/Messages.php */
