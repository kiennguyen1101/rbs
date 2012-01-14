<?php 
/**
 * Reverse bidding system Users Class
 *
 * Buyer related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Users 
 
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

 */ 

class Users extends Controller {

	//Global variable
        public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Users()
	{
	   parent::Controller();
	    
	   //Get Config Details From Db
		$this->config->db_config_fetch();
	   
	   //Manage site Status 
		if($this->config->item('site_status') == 1)
		redirect('offline');
	  
	   //Debug Tool
	   	//$this->output->enable_profiler=true;		
		
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		$this->load->model('auth_model');
		$this->load->model('skills_model');
		
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		
		//Get Footer content
		$conditions = array('page.is_active'=> 1);
		$this->outputData['pages']	=	$this->page_model->getPages($conditions);
		
		//Get Latest Projects
		$limit_latest = $this->config->item('latest_projects_limit');
		$limit3 = array($limit_latest);
		$this->outputData['latestProjects']	= $this->skills_model->getLatestProjects($limit3);
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		$this->lang->load('enduser/sellerConfirm', $this->config->item('language_code'));
	} //Controller End 
	// --------------------------------------------------------------------
	
	/**
	 * Loads Home page of the site.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */	
	function login()
	{	
		//language file
		$this->lang->load('enduser/loginUsers', $this->config->item('language_code'));
		
		//Load Models - for this function
		$this->load->model('user_model');
		
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load cookie 
		$this->load->helper('cookie');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Load Library File
		$this->load->library('encrypt');
		
		// check the login for remember user 
		if($this->auth_model->getUserCookie('user_name')!='' and $this->auth_model->getUserCookie('user_password')!='')
			 { 
				 
				 $conditions 		=  array('user_name'=>$this->auth_model->getUserCookie('user_name'),'password' => md5($this->auth_model->getUserCookie('user_password')),'users.user_status' => '1');

				$query				= $this->user_model->getUsers($conditions);
				
						if($query->num_rows() > 0)
						{
						 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success','Logged In Successfull'));
						  
						}
				redirect('account');
				}
				
		
			if($this->uri->segment(3,0))
			{
				if($this->uri->segment(3,0)=='support')
				{
					 $this->session->set_userdata('support','support');  
				}	
				elseif($this->uri->segment(3,0)=='project')	 
				{
					 $this->session->set_userdata('job','project');  
					 $this->session->set_userdata('job_view','view');  
					 $this->session->set_userdata('job_id',$this->uri->segment(5,0));  
				   	 
				}
			}
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		//pr($_POST);
		//Get Form Data	
		if($this->input->post('usersLogin'))
		{
			//Set rules
			$this->form_validation->set_rules('username','lang:user_name_validation','required|trim|min_length[5]|xss_clean');
			$this->form_validation->set_rules('pwd','lang:password_validation','required|trim|xss_clean');
			if($this->form_validation->run())
			{
				if(getBanStatus($this->input->post('username')))
				{
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Ban Error')));
					redirect('info');
		 		}
			
			
				// Puhal Changes Removed the role Id from the conditions, inorder to remove the buyer and seller radio button (Sep 17 Issue 4)
				
			 	$conditions 		=  array('user_name'=>$this->input->post('username'),'password' => md5($this->input->post('pwd')),'users.user_status' => '1');
				

				$query				= $this->user_model->getUsers($conditions);
				

				if($query->num_rows() > 0)
				{
					  $row =  $query->row();
                      
					  // update the last activity in the users table
					  $updateData = array();
                      $updateData['last_activity'] = get_est_time();
					  //Get Activation Key
		              $activation_key = $row->id;
				      // update process for users table
				      $this->user_model->updateUser(array('id'=>$row->id),$updateData);
					 //Check For Password
					//if($this->input->post('pwd')==$this->common_model->getDecryptedString($row->password))
					
					
					 if(1)
					 {
//pr($row);
					 	//Set Session For User
						$this->auth_model->setUserSession($row);
						
						// Puhal Changes for the Remember me button (Sep 17 Issue 3)			
						if($this->input->post('remember'))
						{
						    $insertData=array();
						    $insertData['username']=$this->input->post('username');
						    $insertData['password']=$this->input->post('pwd');
						    $expire=60*60*24*100;
							if( $this->auth_model->getUserCookie('uname')=='')
							{ 
							$this->user_model->addRemerberme($insertData,$expire); 
							
							}		
						}
						else
						{
						   $this->user_model->removeRemeberme(); 
							
							}	
						
					 	 //Notification message
						 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success','Logged In Successfull'));
				   if($this->session->userdata('job_id')!='')
					{
						$jobid=$this->session->userdata('job_id');
						$this->session->unset_userdata('job');
						$this->session->unset_userdata('view');		
						$this->session->unset_userdata('job_id');	
						redirect('project/view/'.$jobid);		
					
					}
					// check for private project user login 	
					if($this->session->userdata('private_user')!='')
					{
						if($this->session->userdata('private_user')==$row->id or $this->session->userdata('creator_id')==$row->id )
						{
							 $project_id=$this->session->userdata('project_id');
							  $this->session->unset_userdata('private');
							  $this->session->unset_userdata('type');		
							  $this->session->unset_userdata('private_user');
							  $this->session->unset_userdata('project_id');	
							  $this->session->unset_userdata('creator_id');	
							 
							  redirect('project/view/'.$project_id);			
						 
						  }
					else
					{
					          $this->session->unset_userdata('private');
							  $this->session->unset_userdata('type');		
							  $this->session->unset_userdata('private_user');
							  $this->session->unset_userdata('project_id');
							   $this->session->unset_userdata('creator_id');		
							  
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('This is not your private project')));
					redirect('info');
					}
				}	
						   
				if($this->session->userdata('support')=='' and $this->session->userdata('project')=='')
				{	
						 redirect('account');	
					}
				elseif($this->session->userdata('support')!='')
				{
						$this->session->unset_userdata('support');
						redirect('support');	
				} 
							elseif($this->session->userdata('project')!='')
				{
					$id=$this->session->userdata('id');
					$this->session->unset_userdata('project');
					$this->session->unset_userdata('view');		
					$this->session->unset_userdata('id');	
					
					redirect('project/view/'.$id);				
				}
							
				 } else {

					 //Notification message
					 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','Login failed! Incorrect username or password'));
					 redirect('users/login');
				 }
					 
				} else {
				
					 //Notification message
					 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','Login failed! Incorrect username or password'));
				 	 redirect('users/login');
				} //If username exists			
			}//If End - Check For Validation				
		} //If End - Check For Form Submission
		$this->load->view('users/loginUsers',$this->outputData);
	} //Function login End
	// --------------------------------------------------------------------
	
	/**
	 * Loads forgotPassword page of the site.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */	
	function forgotPassword()
	{	
		//language file
		$this->lang->load('enduser/forgotPassword', $this->config->item('language_code'));
		
		//Load Models - for this function
		$this->load->model('user_model');
		
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		//Get Form Data	- Forgot Password
		if($this->input->post('forgotPassword'))
		{
			//Set rules
			$this->form_validation->set_rules('username','lang:user_name_validation','required|trim|min_length[5]|xss_clean');			
			if($this->form_validation->run())
			{
				$username 		=	$this->input->post('username');
				$conditions 	= 	array('user_name'=>$username);
				$query 			=  	$this->user_model->getUsers($conditions);
				$usersData      =   $query->row();
				
				if($query->num_rows()>0)
				{
				 $newpassword    =   '';
				 for($i=0;$i<5;$i++)
				  {
				  $newpassword .=chr(rand(65,90));
				  $newpassword .=chr(rand(97,122));
				  }
				 //Update the suers password	
				 $updateData['password']    		  = md5($newpassword);
				 $updateKey 		= array('users.id'=>$usersData->id);
			     $this->user_model->updateUser($updateKey,$updateData);
					
				//Send Mail
				$conditionUserMail = array('email_templates.type'=>'forget_password');
				$this->load->model('email_model');
				$result            = $this->email_model->getEmailSettings($conditionUserMail);
				$rowUserMailConent = $result->row();
				$splVars = array("!site_title" => $this->config->item('site_title'), "!url" => site_url(''), "!username" =>$usersData->user_name ,"!newpassword" =>$newpassword);
				
				$mailSubject = $rowUserMailConent->mail_subject;
				$mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
				$toEmail = $usersData->email;
				$fromEmail = $this->config->item('site_admin_mail');
				$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);	

				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success','Your password has been sent to your registered email address!'));
				redirect('users/forgotPassword');					
				} else {
					 //Notification message
					 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','User Name Failed'));
				 	 redirect('users/forgotPassword');					
				}
			}//If End - Check For Validation				
		} //If End - Check For Form Submission For Forgot Password
		
		//Get Form Data	- Forgot Username
		if($this->input->post('forgotUsername'))
		{
			//Set rules
			$this->form_validation->set_rules('email','lang:email_validation','required|trim|valid_email|xss_clean');			
			if($this->form_validation->run())
			{
				$email 			=	$this->input->post('email');
				$conditions 	= 	array('email'=>$email);
				$query 			=  	$this->user_model->getUsers($conditions);
				$usersData      =   $query->row();
				if($query->num_rows()>0)
				{
				 //Create new password
				 $newpassword    =   '';
				 for($i=0;$i<5;$i++)
				  {
				  $newpassword .=chr(rand(65,90));
				  $newpassword .=chr(rand(97,122));
				  }
				 //Update the suers password	
				 $updateData['password']    		  = md5($newpassword);
				 $updateKey 		= array('users.id'=>$usersData->id);
			     $this->user_model->updateUser($updateKey,$updateData);
					
				//Send Mail
				$conditionUserMail = array('email_templates.type'=>'forget_password');
				$this->load->model('email_model');
				$result            = $this->email_model->getEmailSettings($conditionUserMail);
				$rowUserMailConent = $result->row();
				$splVars = array("!site_title" => $this->config->item('site_title'), "!url" => site_url(''), "!username" =>$usersData->user_name ,"!newpassword" =>$newpassword);
				$mailSubject = $rowUserMailConent->mail_subject;
				$mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
				$toEmail = $usersData->email;
				$fromEmail = $this->config->item('site_admin_mail');
				$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);	
					
				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success','Your username has been sent to your registered email address'));
				redirect('users/forgotPassword');					
				} else {
					 //Notification message
					 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','Email Failed'));
				 	 redirect('users/forgotPassword');					
				}				
			}//If End - Check For Validation				
		} //If End - Check For Form Submission For Forgot Username	
		$this->load->view('users/forgotPassword',$this->outputData);
	} //Function forgotPassword End
	// --------------------------------------------------------------------
	
	/**
	 * Loads logout .
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */	
	function logout()
	{	
		$this->auth_model->clearUserSession();
		$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('logout_success')));
		$this->auth_model->clearUserCookie(array('username','password'));
		$this->auth_model->clearUserCookie(array('user_name','user_password'));
		redirect('info');
				
	} //Function logout End
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads login for user to post  .
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */	
	function post()
	{	
		$this->auth_model->clearUserSession();
		$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('logout_success')));
		redirect('info');
	} //Function logout End
	
	/**
	 *Get the  job deatils for session check 
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */	
	
	function getData()
	{ 
	//language file
		$this->lang->load('enduser/loginUsers', $this->config->item('language_code'));
		
		//Load Models - for this function
		$this->load->model('user_model');
		
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load cookie 
		$this->load->helper('cookie');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Load Library File
		$this->load->library('encrypt');
		$this->uri->segment(4);
		
		if($this->uri->segment(3)!='')
			{
			 
					 $this->session->set_userdata('project','project');  
					 $this->session->set_userdata('view','view');  
					 $this->session->set_userdata('id',$this->uri->segment(3,0));  
					 
				
			}
			redirect('users/login');
	}//Function getData End
	//---------------------------------------------------------------------------------
	
	/**
	 * get the details from private project to store session
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */	
	function getProjectDetails()
	{ 
	//language file
		$this->lang->load('enduser/loginUsers', $this->config->item('language_code'));
		
		//Load Models - for this function
		$this->load->model('user_model');
		
		
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load cookie 
		$this->load->helper('cookie');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Load Library File
		$this->load->library('encrypt');
		$this->uri->segment(4);
		
		if($this->uri->segment(3)!='')
			{
			 
				$this->session->set_userdata('private','project');  
				$this->session->set_userdata('type','view');  
				$this->session->set_userdata('project_id',$this->uri->segment(3,0)); 
				$this->session->set_userdata('private_user',$this->uri->segment(4,0)); 
				$this->session->set_userdata('creator_id',$this->uri->segment(5,0));
				$condition='projects.id='.$this->uri->segment(3,0);
				$query="SELECT * FROM projects WHERE ". $condition;
				$result=$this->db->query($query);
					foreach( $result->result() as $project)
					{
						$project_name=$project->project_name;
					}
				redirect('users/login/'.$project_name);
			}
			
	}//Function getProjectDetails End 
	//---------------------------------------------------------------------------- 
	
} //End  Users Class

/* End of file Users.php */ 
/* Location: ./app/controllers/Users.php */