<?php 
/**
 * Reverse bidding system Dispute Class
 *
 * Project related tasks are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Project 
 * @author		Cogzidel Dev Team
 * @version		Version 1.0
 * @created		March 31 2009
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
class Dispute extends Controller {
 
	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Dispute()
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
		$this->load->model('skills_model');
		$this->load->model('dispute_model');
		$this->load->model('email_model');
					 
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		//Get Top programmers
		$topProgrammers = $this->common_model->getPageTitleAndMetaData();
		
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
		$this->lang->load('enduser/project', $this->config->item('language_code'));
		$this->lang->load('enduser/createProject', $this->config->item('language_code'));
		$this->outputData['project_period']    =    $this->config->item('project_period');
	} //Constructor End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Open the project cancel case
	 *
	 * @access	private
	 * @param	NULL
	 * @return	contents
	 */ 
	function openCase()
	{
		//Load Language
		$this->lang->load('enduser/cancelProject', $this->config->item('language_code'));
		
		//Check Whether User Logged In Or Not
	    if(isLoggedIn() === false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please login to open case')));
			redirect('info');
		}
		//language file
		$this->lang->load('enduser/review', $this->config->item('language_code'));
		
		//Check for Login details.
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be login access to this page')));
		    redirect('info');
		  }
		//If Admin try to access this url...redirect him
		if(isAdmin() === true)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please login to open case')));
			redirect('info');
		}

		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		//Get Form Data	
		if($this->input->post('submit'))
		{	
			//Set rules
			$this->form_validation->set_rules('project_id','lang:projectid_validation','required|is_natural_no_zero|trim|xss_clean');
			
			if($this->form_validation->run())
			{
				$prjid = $this->input->post('project_id');
				$condition2 = array('projects.id' => $prjid);
				$res = $this->skills_model->getProjects($condition2);
				$row = $res->row();
				
				if(is_object($row)){
					redirect('dispute/createCase/'.$prjid);
				}
				else{				
					//Notification message
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('invalid project id')));
					redirect('dispute/openCase');
				}
			}
		}
		//Check For Programmer Session
		if(isSeller())
		{
        	$provider_id = $this->loggedInUser->id;
			$conditions3		= array('bids.user_id '=>$provider_id,'projects.project_status =' => '2','projects.programmer_id' => $provider_id);
			$this->outputData['projects']  =  $this->skills_model->getProjectByBid($conditions3);
		}
		if(isBuyer())
		{
        	$buyer_id = $this->loggedInUser->id;
			$conditions		= array('projects.creator_id'=>$buyer_id,'projects.project_status =' => '2');
			$this->outputData['projects']  =  $this->skills_model->getProjectsByProvider($conditions);
		}
		
		$this->load->view('dispute/cancelProject',$this->outputData);
	}//Function extendBid End
	
	// --------------------------------------------------------------------
	
	/**
	 * Open the project cancel case
	 *
	 * @access	private
	 * @param	NULL
	 * @return	contents
	 */ 
	function createCase()
	{
		//Load Language
		$this->lang->load('enduser/cancelProject', $this->config->item('language_code'));
		
		//Check Whether User Logged In Or Not
	    if(isLoggedIn() === false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please login to create case')));
			redirect('info');
		}
		//If Admin try to access this url...redirect him
		if(isAdmin() === true)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please login to create case')));
			redirect('info');
		}
		
		//Load helpers
		$this->load->helper('users');
		$this->load->helper('projectcases');
		
		//load validation libraray

		$this->load->library('form_validation');

		//Load Form Helper

		$this->load->helper('form');

		//Intialize values for library and helpers	

		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		//Get Form Data	

		if($this->input->post('createCase'))
		{
			//Set rules
			$this->form_validation->set_rules('problem_description','lang:problem_description_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('comments','','trim|xss_clean');
			$this->form_validation->set_rules('payment','lang:payment_validation','is_natural_no_zero|trim|xss_clean');
			
			if($this->form_validation->run())
			{	
				  if(check_form_token()===false)
				  {
				  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('token_error')));
				  	redirect('info');
				  }
				  
				  $insertData              	  			= array();	
			      $insertData['project_id']    	  		= $this->input->post('project_id');
				  $insertData['case_type']  			= $this->input->post('case_type');
				  $insertData['case_reason']   			= $this->input->post('case_reason');
				  $insertData['problem_description']    = $this->input->post('problem_description');
				  $insertData['private_comments']    	= $this->input->post('comments');
				  $insertData['review_type']    		= $this->input->post('review');
				  $insertData['payment']    			= $this->input->post('payment');
				  $insertData['user_id']    			= $this->loggedInUser->id;
				  $insertData['created']    			= get_est_time();
				  
				  //Create Case
				  $this->dispute_model->insertProjectCase($insertData);
				  
				  $project_id = $insertData['project_id'];
				  $condition2 = array('projects.id' => $project_id);
				  $res = $this->skills_model->getProjects($condition2);
				  $prj = $res->row();
				  
				  if(isSeller()){
				  	$other_user = $prj->user_name;
					$user_type = 'Provider';
				  }
				  if(isBuyer()){
				  	$provider_id = $prj->programmer_id;
					$providerRow = getUserInfo($provider_id);
					$other_user = $providerRow->user_name;
					$user_type = 'Buyer';
				  }
				  
				//Send Mail to other user about the case
				$conditionUserMail = array('email_templates.type'=>'cancellation_case');
				$result            = $this->email_model->getEmailSettings($conditionUserMail);
				$rowUserMailConent = $result->row();
				//Update the details
				$splVars = array("!project_name" => '<a href="'.site_url('project/view/'.$prj->id).'">'.$prj->project_name.'</a>',"!other_user" => $other_user,"!contact_url" => site_url('contact'),"!user" => $this->loggedInUser->user_name,'!site_title' => $this->config->item('site_title'),"!link" => site_url('dispute/viewCase/'.$this->db->insert_id()));
				
				$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
				$mailContent = strtr($rowUserMailConent->mail_body, $splVars);
				$toEmail     = $prj->email;
				$fromEmail   = $this->config->item('site_admin_mail');
				$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
				
				//Send acknowledgement Mail to siteadmin
				$conditionUserMail = array('email_templates.type'=>'project_cancel_admin');
				$result            = $this->email_model->getEmailSettings($conditionUserMail);
				$rowUserMailConent = $result->row();
				//Update the details
				$splVars = array("!project_name" => '<a href="'.site_url('project/view/'.$prj->id).'">'.$prj->project_name.'</a>',"!user" => $this->loggedInUser->user_name,'!user_type' => $user_type,'!user_type' => $user_type,'!case_id' => $this->db->insert_id());

				$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
				$mailContent = strtr($rowUserMailConent->mail_body, $splVars);
				$toEmail     = $this->config->item('site_admin_mail');
				$fromEmail   = $prj->email;
				$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Case opened successfully')));
				redirect('info');
			}	
		}
		
		$project_id = $this->uri->segment(3);
		$condition2 = array('projects.id' => $project_id);
		$res = $this->skills_model->getProjects($condition2);
		$this->outputData['project'] = $res->row();
		$this->outputData['provider'] = getUserInfo($this->outputData['project']->programmer_id);
		$this->load->view('dispute/createCase',$this->outputData);
	}//End of createCase function
	
	// --------------------------------------------------------------------
	
	/**
	 * View the cancellation/Dispute case
	 *
	 * @access	private
	 * @param	case id
	 * @return	contents
	 */ 
	function viewCase()
	{
		//Load Language
		$this->lang->load('enduser/cancelProject', $this->config->item('language_code'));
		
		//Check Whether User Logged In Or Not
	    if(isLoggedIn() === false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please login to view case')));
			redirect('info');
		}
		//If Admin try to access this url...redirect him
		if(isAdmin() === true)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please login to view case')));
			redirect('info');
		}
		
		//Load model
		$this->load->helper('users');
		$this->load->helper('projectcases');
		
		//load validation libraray

		$this->load->library('form_validation');

		//Load Form Helper

		$this->load->helper('form');

		//Intialize values for library and helpers	

		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		//Get Form Data	

		if($this->input->post('respondCase'))
		{
			//Set rules
			if($this->input->post('updates') == '0')
				$this->form_validation->set_rules('problem_description','lang:problem_description_validation','required|trim|xss_clean');
			else
				$this->form_validation->set_rules('problem_description','lang:problem_description_validation','trim|xss_clean');
			$this->form_validation->set_rules('comments','','trim|xss_clean');
			
			if($this->form_validation->run())
			{	
				  if(check_form_token()===false)
				  {
				  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('token_error')));
				  	redirect('info');
				  }
				  
				  $insertData              	  			= array();	
			      $insertData['parent']    	  			= $this->input->post('case_id');
				  $insertData['problem_description']    = $this->input->post('problem_description');
				  $insertData['private_comments']    	= $this->input->post('comments');
				  $insertData['user_id']    			= $this->loggedInUser->id;
				  $insertData['created']    			= get_est_time();
				  if($this->input->post('updates') != '0')
				  $insertData['updates']    	= $this->input->post('updates');
				  
				  //Create Case
				  $this->dispute_model->insertProjectCase($insertData);
				  
				  $project_id = $this->input->post('project_id');
				  $condition2 = array('projects.id' => $project_id);
				  $res = $this->skills_model->getProjects($condition2);
				  $prj = $res->row();
				  
				  if(isSeller()){
				  	$other_user = $prj->user_name;
					$user_type = 'Provider';
				  }
				  if(isBuyer()){
				  	$provider_id = $prj->programmer_id;
					$providerRow = getUserInfo($provider_id);
					$other_user = $providerRow->user_name;
					$user_type = 'Buyer';
				  }
				  
				//Send Mail to other user about the case
				$conditionUserMail = array('email_templates.type'=>'respond_case');
				$result            = $this->email_model->getEmailSettings($conditionUserMail);
				$rowUserMailConent = $result->row();
				//Update the details
				$splVars = array("!project_name" => '<a href="'.site_url('project/view/'.$prj->id).'">'.$prj->project_name.'</a>',"!pr_name" => $prj->project_name,"!other_user" => $other_user,"!contact_url" => site_url('contact'),"!user" => $this->loggedInUser->user_name,'!site_title' => $this->config->item('site_title'),"!link" => site_url('dispute/viewCase/'.$insertData['parent']));
				
				$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
				$mailContent = strtr($rowUserMailConent->mail_body, $splVars);
				$toEmail     = $prj->email;
				$fromEmail   = $this->config->item('site_admin_mail');
				$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
				
				//Send acknowledgement Mail to siteadmin
				$conditionUserMail = array('email_templates.type'=>'response_case_admin');
				$result            = $this->email_model->getEmailSettings($conditionUserMail);
				$rowUserMailConent = $result->row();
				//Update the details
				$splVars = array("!project_name" => '<a href="'.site_url('project/view/'.$prj->id).'">'.$prj->project_name.'</a>',"!user" => $this->loggedInUser->user_name,'!user_type' => $user_type,'!case_id' => $insertData['parent']);

				$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
				$mailContent = strtr($rowUserMailConent->mail_body, $splVars);
				$toEmail     = $this->config->item('site_admin_mail');
				$fromEmail   = $prj->email;
				$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
				
				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('response added successfully')));
				redirect('dispute/viewCase/'.$insertData['parent']);
			}	
		}
		if($this->input->post('reopen')){
			$insertData              	  = array();	
			$insertData['parent']    	  = $this->input->post('case_id');
			$insertData['user_id']    	  = $this->loggedInUser->id;
			$insertData['created']    	  = get_est_time();
			$insertData['updates']    	  = $this->lang->line('case reopened');
			
			//Create Case
			$this->dispute_model->insertProjectCase($insertData);
			
			//prepare update data
			$updateData                 = array();	
			$updateData['status']  		= 'open';
		
			//update case
			$this->skills_model->updateProjectCase($this->input->post('case_id'),$updateData);
			
			//Notification message
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Case reopened successfully')));
			redirect('dispute/viewCase/'.$insertData['parent']);
		}
		
		$caseid = $this->uri->segment('3',0);
		$condition2 = array('project_cases.id' => $caseid);
		$res = $this->dispute_model->getProjectCases($condition2);
		if($res->num_rows() == 0)
		{
			//Notification message
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Invalid input given')));
			redirect('info');
		}
		$this->outputData['projectCase'] = $res->row();
		
		$condition3 = array('project_cases.parent' => $caseid);
		$this->outputData['caseResolution'] = $this->dispute_model->getProjectCases($condition3);

		//pr($this->outputData['projectCase']);exit;
		//$this->outputData['provider'] = getUserInfo($this->outputData['project']->programmer_id);
		$this->load->view('dispute/viewCase',$this->outputData);
	}//End of ViewCase function
	
	// --------------------------------------------------------------------
	
	/**
	 * View all open project Cancellation/Dispute cases
	 *
	 * @access	private
	 * @param	NULL
	 * @return	contents
	 */ 
	function viewOpenCases()
	{
		//Load Language
		$this->lang->load('enduser/cancelProject', $this->config->item('language_code'));
		
		//Check Whether User Logged In Or Not
	    if(isLoggedIn() === false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please login to view open cases')));
			redirect('info');
		}
		//If Admin try to access this url...redirect him
		if(isAdmin() === true)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please login to view open cases')));
			redirect('info');
		}
		
		//Load model
		$this->load->helper('users');
		$this->load->helper('projectcases');
		
		$condition2 = array('project_cases.case_type' => 'cancel','project_cases.parent' => '0','project_cases.status' => 'open');
		$orCondition = "(projects.creator_id = '".$this->loggedInUser->id."' or projects.programmer_id = '".$this->loggedInUser->id."')";
		//'projects.creator_id' => $this->loggedInUser->id,'projects.programmer_id' => $this->loggedInUser->id;
		$this->outputData['cancellation'] = $this->dispute_model->getProjectCases($condition2,$orCondition);
		
		$condition3 = array('project_cases.case_type' => 'dispute','project_cases.parent' => '0','project_cases.status' => 'open');
		$orCondition2 = "(projects.creator_id = '".$this->loggedInUser->id."' or projects.programmer_id = '".$this->loggedInUser->id."')";
		$this->outputData['disputes'] = $this->dispute_model->getProjectCases($condition3,$orCondition2);
		//pr($this->outputData['disputes']->result());exit;
		
		$this->load->view('dispute/openCases',$this->outputData);
	}//End of viewOpenCases function
	
	// --------------------------------------------------------------------
	
	/**
	 * View all closed project Cancellation/Dispute cases
	 *
	 * @access	private
	 * @param	NULL
	 * @return	contents
	 */ 
	function viewClosedCases()
	{
		//Load Language
		$this->lang->load('enduser/cancelProject', $this->config->item('language_code'));
		
		//Check Whether User Logged In Or Not
	    if(isLoggedIn() === false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please login to view closed cases')));
			redirect('info');
		}
		//If Admin try to access this url...redirect him
		if(isAdmin() === true)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please login to view closed cases')));
			redirect('info');
		}
		
		//Load model
		$this->load->helper('users');
		$this->load->helper('projectcases');
		
		$condition2 = array('project_cases.case_type' => 'cancel','project_cases.parent' => '0','project_cases.status' => 'closed');
		$orCondition = "(projects.creator_id = '".$this->loggedInUser->id."' or projects.programmer_id = '".$this->loggedInUser->id."')";
		//'projects.creator_id' => $this->loggedInUser->id,'projects.programmer_id' => $this->loggedInUser->id;
		$this->outputData['cancellation'] = $this->dispute_model->getProjectCases($condition2,$orCondition);
		
		$condition3 = array('project_cases.case_type' => 'dispute','project_cases.parent' => '0','project_cases.status' => 'closed');
		$orCondition2 = "(projects.creator_id = '".$this->loggedInUser->id."' or projects.programmer_id = '".$this->loggedInUser->id."')";
		$this->outputData['disputes'] = $this->dispute_model->getProjectCases($condition3,$orCondition2);
		//pr($this->outputData['disputes']->result());exit;
		
		$this->load->view('dispute/closedCases',$this->outputData);
	}//End of viewClosedCases function
	
	} //End  Project Class
/* End of file Project.php */ 
/* Location: ./app/controllers/Project.php */
?>