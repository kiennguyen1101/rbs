<?php
/**
 * Reverse bidding system Buyer Class
 *
 * Buyer related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Buyer 

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

class Affiliate extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Affiliate()
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
		$this->load->model('user_model');
		$this->load->model('skills_model');
		$this->load->model('page_model');
	    $this->load->model('email_model');
		$this->load->model('auth_model');
		$this->load->model('affiliate_model');


		//Page Title and Meta Tags
		$this->outputData 			= $this->common_model->getPageTitleAndMetaData();
		
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
		
		// loading the lang files
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		$this->lang->load('admin/common',$this->config->item('language_code'));
		$this->lang->load('admin/setting',$this->config->item('language_code'));
		$this->lang->load('admin/validation',$this->config->item('language_code'));
		
		$this->outputData['current_page'] = 'buyer';
		
		//Load helpers
		$this->load->helper('users');
		$this->load->helper('file');
		$this->outputData['groups']	=	$this->skills_model->getGroups();
		$this->outputData['popular'] = $this->skills_model->getPopularSearch('work');


	} //Controller End 
	// --------------------------------------------------------------------
	
	
	/**
	 * Loads the affiliate page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function index()
	{		
		//Load model
		$this->load->model('settings_model');
		$this->load->model('user_model');
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		//language file
		$this->lang->load('enduser/review', $this->config->item('language_code'));
		
		//Get Affiliate Payment
		$affiliate_result = $this->affiliate_model->getAffiliatePayment();
		$this->outputData['affiliate'] = $affiliate_result;
		
		//Get site settngs
		$this->outputData['settings']	 = 	$this->settings_model->getSiteSettings();
		
		if(isset($this->loggedInUser->user_name)) {
		$cond	=	array('user_name' => $this->loggedInUser->user_name);
		$getUsers	= $this->user_model->getUsers($cond);		
		

		foreach($getUsers->result() as $getUser) {
			$user_email	=	$getUser->email;
			break;
		}
		}
		
        if($this->input->post('submit_questions'))
		{	
			//Set rules
			$this->form_validation->set_rules('your_email','lang:your email validation','required|trim|xss_clean|valid_email');
			
			$this->outputData['your_email'] 			= $this->input->post('your_email');
			$this->outputData['affiliate_question'] 	= $this->input->post('affiliate_question');
			$this->outputData['description'] 			= $this->input->post('description');
			
			if($this->form_validation->run())
			{	
				//echo $this->input->post('your_email');
				//Check the email exist or not
				$email = $this->input->post('your_email');
				if(isset($this->loggedInUser->id)) {
					$condition = array('email' => $email, 'user_name' => $this->loggedInUser->user_name);
				} else {
					$condition = array('email' => $email);
				}
				//pr($condition);
				$affiliate_email_result = $this->affiliate_model->checkUserEmail($condition);
				$this->outputData['affiliate_email'] = $affiliate_email_result;	
				
				if(isset($this->loggedInUser->id) and !isset($affiliate_email_result['email']))
				{
					///$this->outputData['question_failed'] = "You cannot post a message as a guest because we found an account with the email address ".$email.'.';	
					$this->outputData['question_failed'] 	= "Please use your system email address. Your email is ".$user_email.'.';	
				}
				if(!isset($this->loggedInUser->id) and isset($affiliate_email_result['email']))
				{
						 //prepare insert data
						  $insertData                  	= array();	
						  $insertData['email']  		= $this->input->post('your_email');
						  $insertData['subject']  		= $this->input->post('Subject');
						  $insertData['questions']  	= $this->input->post('description');
		
						  //Add Category
						  $this->affiliate_model->addAffiliateQuestions($insertData);	

						  $this->outputData['question_failed'] 		= "<p>Thank you</p><br/><p>Submission Received. You will receive an email response from Iboxaudio staff as soon as possible.</p>";							  

				}
				if(isset($this->loggedInUser->id) and isset($affiliate_email_result['email']))
				{
						 //prepare insert data
						  $insertData                  	= array();	
						  $insertData['email']  		= $this->input->post('your_email');
						  $insertData['subject']  		= $this->input->post('Subject');
						  $insertData['questions']  	= $this->input->post('description');
		
						  //Add Category
						  $this->affiliate_model->addAffiliateQuestions($insertData);	

						  $this->outputData['question_failed'] 		= "<p>Thank you</p><br/><p>Submission Received. You will receive an email response from Iboxaudio staff as soon as possible.</p>";							  

				}
				else {
					if(!isset($affiliate_email_result['num_rows']) and !isset($this->loggedInUser->id)) {
						  //prepare insert data
						  $insertData                  	= array();	
						  $insertData['email']  		= $this->input->post('your_email');
						  $insertData['subject']  		= $this->input->post('Subject');
						  $insertData['questions']  	= $this->input->post('description');
		
						  //Add Category
						  $this->affiliate_model->addAffiliateQuestions($insertData);	
						  



						  $this->outputData['question_failed'] 		= "<p>Thank you</p><br/><p>Submission Received. You will receive an email response from Iboxaudio staff as soon as possible.</p>";							  
					} 		
				}	
				
				//Intialize values for library and helpers	
				$this->load->view('affiliate/affiliates',$this->outputData);

			} 
		}
		
		//Innermenu tab selection
		$this->outputData['innerClass0']   = '';
		$this->outputData['innerClass0']   = 'selected';
		
		//$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
		$this->load->view('affiliate/affiliates',$this->outputData);
		
	} //End of top buyers review 
	
	/**
	 * To post the affiliate questions
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function manageAffiliates()
	{	
		//Load model
		$this->load->model('settings_model');
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }

		
		$condition = array('sales.refid' => $this->loggedInUser->user_name);
		$this->outputData['affiliate_sales'] = $this->affiliate_model->getAffiliateSales($condition);
		
		// get affiliate sales total  
		$condition1 = array('sales.refid' => $this->loggedInUser->user_name);
		$this->outputData['affiliate_sales_total'] = $this->affiliate_model->getSalesTotal($condition1);
		
		// get affiliate welcome messages
		$condition2 = array('affiliate_welcome_msg.refid' => $this->loggedInUser->user_name, 'msg_status' => 0);
		$msg_result = $this->affiliate_model->getAffiliateWelcomeMsg($condition2);
		$msg_num_rows	=	$msg_result->num_rows();
		$msg_result_row	=	$msg_result->row();
		if(isset($msg_result_row->welcome_msg))
		$this->outputData['affiliate_welcome_msg']	=	$msg_result_row->welcome_msg;
		
		//Innermenu tab selection
		$this->outputData['innerClass5']   = '';
		$this->outputData['innerClass5']   = 'selected';
		
		if($this->input->post('welcomemsg')) { 
		
		//Set rules
		$this->form_validation->set_rules('welcome_message','lang:welcome_message_validation','required|trim|xss_clean');
		
		if($this->form_validation->run())
		{
			$welcome_msg = 	$this->input->post('welcome_message');
				
				  if($msg_num_rows == 0) {
					  //prepare insert data
					  $insertData                  			= 		array();	
					  $insertData['refid']  				= 		$this->loggedInUser->user_name;
					  $insertData['welcome_msg']  			= 		$welcome_msg;
		
					  //print_r($insertData);
		
					  //Add Category
					  $this->affiliate_model->addAffiliateWelcomeMsg($insertData);	
					  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));	
					  //$this->load->view('affiliate/affilateCenter',$this->outputData);
				  } 
				 else {
					$updateKey1									= array('affiliate_welcome_msg.refid'=>$this->loggedInUser->user_name, 'id' => $msg_result_row->id);
					
					$updateData1                 	  			= array();	
					$updateData1['welcome_msg ']  				= $welcome_msg;
					
					//Update Site Settings
					$this->affiliate_model->updateAffiliateWelcomeMeg($updateKey1,$updateData1);	
					$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));			  
				  }

			}
		}
		
					// get affiliate welcome messages
					$condition2 = array('affiliate_welcome_msg.refid' => $this->loggedInUser->user_name);
					$msg_result = $this->affiliate_model->getAffiliateWelcomeMsg($condition2);
					$msg_num_rows	=	$msg_result->num_rows();
					$msg_result_row	=	$msg_result->row();
					
					if(isset($msg_result_row->welcome_msg))
					$this->outputData['affiliate_welcome_msg']	=	$msg_result_row->welcome_msg;

					// insert total affiliate amount 
					$this->load->model('affiliate_model');
					$this->load->model('user_model');		
					$results 	         = $this->affiliate_model->getReleasePayments();  

        //pr($results->result());
		if($results->num_rows()>0)
        {
				 foreach($results->result() as $row) {
				 //prepare insert data
				  $insertData                  	= 		array();	
				  $insertData['refid']  		= 		$row->refid;
				  $insertData['account_type']  	= 		$row->account_type;
				  $insertData['payment']  	    = 		$row->total;
				  
					// get user
					$condition = array('users.user_name'=>$insertData['refid']);
					$user_data = $this->user_model->getUsers($condition);					  
					//$user_data_result = $user_data->result();
					$user_data_row = $user_data->row();
					
					$user_id = 0;

					if(isset($user_data_row->id)) {
						if($user_data_row->id != '') {
						$user_id = $user_data_row->id;				  
						}
					} 
					
				  
				  $insertData['user_id']  	    = 		$user_id;
				  $insertData['created_date']  	    = get_est_time();
				  
				$date_with_time = show_date($insertData['created_date']);
				
				$arr = explode(' ',$date_with_time);
				
				//pr($arr_str);
				for($i=0; $i<count($arr); $i++) {
				$mon = $arr[0];
				$year = $arr[2];
				}
				
				$created_date_forrmat = $mon.", ".$year;
				  
				  $insertData['created_date_format']  	    = 		$created_date_forrmat;
				  
				  $insertData['is_released']  	    = 		'0';
				  
				  $unrelease_condition			 =	array('refid' => $row->refid, 'is_released' => '0');				  
				  $unrelease_results 	         = $this->affiliate_model->getUnReleasePayments($unrelease_condition);
				  $unreleased_row				 =  $unrelease_results->row();

				  $released_condition			 =	array('refid' => $row->refid, 'is_released' => '1');				  
				  $released_results 	         =  $this->affiliate_model->getUnReleasePayments($released_condition);
				  $released_row					 =  $released_results->row();
				  
					  if($unrelease_results->num_rows()>0) {
							$updateKey['payment']    = $row->total; 
							$cond = array('affiliate_unreleased_payments.refid' => $row->refid,'affiliate_unreleased_payments.id' => $unreleased_row->id);
							$result =  $this->affiliate_model->updateUnReleasedPayments(TRUE,$updateKey,$cond);
					  }
					  
					  else {
					   
					  if($released_results->num_rows()>0) {
						  foreach($released_results->result() as $row1) {
						  
						  $sales_total		=	$row->total;
						  
						$released_total	=	$row1->total;
						  
						 $check_balance	=	$sales_total - $released_total;
						  
							  if($check_balance > 0) {
									//prepare insert data
									$insertData1                  		= 		array();	
									$insertData1['refid']  				= 		$row->refid;
									$insertData1['account_type']  		= 		$row->account_type;
									$insertData1['payment']  	    	= 		$check_balance; $user_id;
									$insertData1['user_id']  	    	= 		$user_id;
									$insertData1['created_date']  		= 		get_gmt_time();
				  					$insertData1['created_date_format']	= 		$created_date_forrmat;
									$insertData1['is_released']  	    = 		'0';
									
									$condition = array('users.user_name'=>$insertData1['refid']);
									$this->affiliate_model->addUnReleasedPayments($insertData1);	
							  	
							  }
						  
						  }
					  
					  }
					  
					  }
					  
					  if($unrelease_results->num_rows() == 0 and $released_results->num_rows() == 0) 
					  {
							$this->affiliate_model->addUnReleasedPayments($insertData);	
					  }
				  
				  }
					
					}
					
					

		//exit;		
		
		
		
		$cond = array('affiliate_released_payments.refid'=>$this->loggedInUser->user_name);
		$released_amount =  $this->affiliate_model->getReleasedPayments($cond);
		$this->outputData['released_amount'] = $released_amount;
		$this->load->view('affiliate/affilateCenter',$this->outputData);

		
	} //End of top buyers review 
	
	
	/**
	 * To list the affiliate guests list
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function affiliateGuests()
	{	
		//Load model
		$this->load->model('settings_model');
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		$this->load->view('affiliate/affilateGuest',$this->outputData);

		
	} //End of top buyers review 
	
	
	function ref()
	{	
		//Load model
		$this->load->model('settings_model');
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		$condition = array('users.user_name' => $this->uri->segment(3));	
		
		$clientdate	=	$this->config->item('clientdate');
		$clienttime	=	$this->config->item('clienttime');
		$clientbrowser	=$this->config->item('clientbrowser');
		$clientip	=	$this->config->item('clientip');
		$clienturl	=	$this->config->item('clienturl');
		//$clientdate	=	$this->config->item('clientip');
		
		if($this->uri->segment(3)) 
		{	
				
		$affiliate_users = $this->affiliate_model->getAffiliateUsers($condition);
		
		$this->load->library('session');
		
		$this->session->set_userdata('refId', $this->uri->segment(3));
		
	
			  //prepare insert data
			  $insertData                  	= 		array();	
			  $insertData['refid']  		= 		$this->uri->segment(3);
			  $insertData['created_date']  	= 		"$clientdate";
			  $insertData['time']  			= 		"$clienttime";
			  $insertData['browser']  		= 		"$clientbrowser";
			  $insertData['ipaddress']  	= 		"$clientip";
			  $insertData['refferalurl']  	= 		"$clienturl";
			  //$insertData['buy']  			= 		"$buy";
			  
			 

			  //Add Category
			 
			  $this->affiliate_model->addClickThroughs($insertData);			
		

		//Get Affiliate Payment
		$affiliate_result = $this->affiliate_model->getAffiliatePayment();
		
		$this->outputData['affiliate'] = $affiliate_result;
		
		//Get site settngs
		$this->outputData['settings']	 = 	$this->settings_model->getSiteSettings();
	
		//Get Affiliates
		//$this->load->view('affiliate/affiliates',$this->outputData);
		redirect('');
		}
	   
	}//End of index function
	
	/**
	 * To display text links
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function textlink()
	{	
		//Load model
		$this->load->model('settings_model');
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		$this->outputData['affiliate_sales'] = $this->affiliate_model->getAffiliateSales();
		
		//Innermenu tab selection
		$this->outputData['innerClass1']   = '';
		$this->outputData['innerClass1']   = 'selected';

		
		$this->load->view('affiliate/textLink',$this->outputData);

		
	} //End of top buyers review 
	
	
	/**
	 * To display banners
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function banners()
	{	
		//Load model
		$this->load->model('settings_model');
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		$this->outputData['affiliate_sales'] = $this->affiliate_model->getAffiliateSales();
		
		//Innermenu tab selection
		$this->outputData['innerClass2']   = '';
		$this->outputData['innerClass2']   = 'selected';
		
		$this->load->view('affiliate/banners',$this->outputData);

		
	} //End of top buyers review 
	
	/**
	 * To display project lists
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function projectList()
	{	
		//Load model
		$this->load->model('settings_model');
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		$this->outputData['affiliate_sales'] = $this->affiliate_model->getAffiliateSales();
		
		if($this->uri->segment(3) != '') {
				$condition			=	array('projects.project_status' => '0');
				$this->outputData['projectlist_result'] 	=	$this->skills_model->getProjects($condition);
				$this->load->view('affiliate/viewProjectList',$this->outputData);
			
		}
		
		//Innermenu tab selection
		$this->outputData['innerClass3']   = '';
		$this->outputData['innerClass3']   = 'selected';
		
		$this->load->view('affiliate/projectList',$this->outputData);

		
	} //End of top buyers review 
	
	/**
	 * To display text Feed
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function textFeed()
	{	
		//Load model
		$this->load->model('settings_model');
		$this->load->model('skills_model');
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		$this->outputData['affiliate_sales'] = $this->affiliate_model->getAffiliateSales();
		
		if($this->uri->segment(2) == 'textFeed' and $this->uri->segment(4) != '') {
		
			$poject_type	=	strtoupper($this->uri->segment(3));
			$refid	=	$this->uri->segment(4);
			
			if($poject_type == 'Y') {
			
				$this->outputData['textFeed_result'] 	=	$this->skills_model->getProjects();
				$this->load->view('affiliate/viewTextFeed',$this->outputData);
			
			} else if($poject_type == 1) {
			
				$condition 		=		array('projects.is_feature' => 1);
				$this->outputData['textFeed_result'] 	=	$this->skills_model->getProjects($condition);
				$this->load->view('affiliate/viewTextFeed',$this->outputData);

			} else {
			
				$this->outputData['textFeed_result'] 	=	$this->skills_model->getProjects();
				$this->load->view('affiliate/viewTextFeed',$this->outputData);
				
			}
		}

		//Innermenu tab selection
		$this->outputData['innerClass4']   = '';
		$this->outputData['innerClass4']   = 'selected';
		
		$this->load->view('affiliate/textFeed',$this->outputData);

		
	} //End of top buyers review 
	
	/**
	 * To display rssFeeds
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function rssFeeds()
	{	
		//Load model
		$this->load->model('settings_model');
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		$this->outputData['affiliate_sales'] = $this->affiliate_model->getAffiliateSales();
		
		//Innermenu tab selection
		$this->outputData['innerClass5']   = '';
		$this->outputData['innerClass5']   = 'selected';
		
		$this->load->view('affiliate/rssFeeds',$this->outputData);

		
	} //End of top buyers review 
	
	

} //End  Buyer Class

/* End of file Buyer.php */ 
/* Location: ./app/controllers/Buyer.php */
?>