<?php 
/**
 * Reverse bidding system Project Class
 *
 * Project related tasks are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Project 
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
class Project extends Controller {
 
	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Project()
	{
	   parent::Controller();
	   
	   
	   //Get Config Details From Db
		$this->config->db_config_fetch();
		
		//Load the helper file reviews
		$this->load->helper('reviews');
	   
	   //Debug Tool
	   //$this->output->enable_profiler=true;		
		
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		$this->load->model('skills_model');
		$this->load->model('user_model');
		$this->load->model('transaction_model');
		$this->load->model('settings_model');
		$this->load->model('file_model');
					 
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
		
		//Get draft Projects
		if(isset($this->loggedInUser->id))
		{
		$condition = array('draftprojects.creator_id' => $this->loggedInUser->id);
		$this->outputData['draftProjects']	= $this->skills_model->getDraft($condition);
		
		$this->load->model('user_model');
		$conditions  = array('user_list.creator_id'=>$this->loggedInUser->id,'user_list.user_role'=>'1');
		$this->outputData['favouriteUsers']  = $this->user_model->getFavourite($conditions);
        }
		
		 //Post the maximum size of memory limit
		$maximum        = $this->config->item('upload_limit');
 	    $this->outputData['maximum_size'] = $maximum;
		if($this->loggedInUser)
		{
			//Conditions
			 $conditions							= array('files.user_id'=>$this->loggedInUser->id);
			 $this->outputData['fileInfo'] 			= $this->file_model->getFile($conditions);
		}
		
		//Initital payment settings for projects
		$paymentSettings = $this->settings_model->getSiteSettings();
  	    $this->outputData['feature_project']   = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
		$this->outputData['urgent_project']    = $paymentSettings['URGENT_PROJECT_AMOUNT'];
		$this->outputData['hide_project']      = $paymentSettings['HIDE_PROJECT_AMOUNT'];
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		$this->lang->load('enduser/project', $this->config->item('language_code'));
		$this->lang->load('enduser/createProject', $this->config->item('language_code'));
		$this->outputData['project_period']    =    $this->config->item('project_period');
	
	} //Constructor End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Buyer signUp page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function create()
	{	

		//echo $this->loggedInUser->email;
		$result = '0';
		$manage = '1';
		$this->outputData['showPreview']			= false;
		
		//Load Language
		
		$this->lang->load('enduser/withdrawMoney', $this->config->item('language_code'));
		$this->lang->load('enduser/createBids', $this->config->item('language_code'));
		
		$this->outputData['created']          = get_est_time();
		$this->outputData['enddate']          = get_est_time() + ($this->input->post('openDays') * 86400);
		
		//Check For Buyer Session
		if(!isBuyer())
		{
        	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged in as a buyer to post projects')));
			redirect('info');
		}	
		
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
	    if($this->input->post('projectid'))
		{
		  	$project = $this->input->post('projectid');
		}
		
		//Save the draft projects
		if($this->input->post('save_draft'))
		{
			//echo $this->input->post('projectName');
			 if($this->input->post('projectName'))
			  {
				$draft_name = $this->input->post('projectName');
				$condition  =  array('draftprojects.project_name'=>$draft_name);
				$draft = $this->skills_model->getDraft($condition);
				//pr($draft->num_rows());
				
				//pr($draft->result());
				if($draft->num_rows() <= 0)
				{
							
					$insertData              		  	= array();	
					$insertData['project_name']  	  	= $this->input->post('projectName');
					
					$insertData['description']      	= $this->input->post('description');
					if($this->input->post('budget_min',TRUE))
						$insertData['budget_min']    	= $this->input->post('budget_min');
					else
						$insertData['budget_min']    	= '';	
					
					if($this->input->post('budget_max',TRUE))
						$insertData['budget_max']    	= $this->input->post('budget_max');
					else
						$insertData['budget_max']    	= '';	
					
					$insertData['is_feature']       	= $this->input->post('is_feature');
					$insertData['is_urgent']         	= $this->input->post('is_urgent');
					$insertData['is_hide_bids']         = $this->input->post('is_hide_bids');
					if($this->input->post('is_private'))
					{
					   $insertData['is_private']   = $this->input->post('is_private');
					}   
					$insertData['creator_id']       	= $this->loggedInUser->id;
					$insertData['created']       		= get_est_time();
					$insertData['enddate']       		= get_est_time() + ($this->input->post('openDays') * 86400);		  
					//$insertData['open_days']       		= $this->input->post('openDays');		  
					//pr($insertData);
					if($insertData)            
					  {
						$this->skills_model->draftProject($insertData);
					  } 
				}
				   
			//}
		
			else
			{
				$draft = $draft->row();
				$updateDraft              		  	= array();	
				$updateDraft['project_name']  	  	= $this->input->post('projectName');
				
				$updateDraft['description']      	= $this->input->post('description');
				if($this->input->post('budget_min',TRUE))
					$updateDraft['budget_min']    	= $this->input->post('budget_min');
				else
					$updateDraft['budget_min']    	= '';	
				
				if($this->input->post('budget_max',TRUE))
					$updateDraft['budget_max']    	= $this->input->post('budget_max');
				else
					$updateDraft['budget_max']    	= '';	
				
				$updateDraft['is_feature']       	= $this->input->post('is_feature');
				$updateDraft['is_urgent']         	= $this->input->post('is_urgent');
				$updateDraft['is_hide_bids']        = $this->input->post('is_hide_bids');
				//$updateDraft['open_days']       	= $this->input->post('openDays');
				if($this->input->post('is_private'))
				{
				   $updateDraft['is_private']   = $this->input->post('is_private');
				}   
				$updateDraft['creator_id']       	= $this->loggedInUser->id;
				$updateDraft['created']       		= get_est_time();
				$updateDraft['enddate']       		= get_est_time() + ($this->input->post('openDays') * 86400);		  
				
				$categories = $this->input->post('categories');
				//pr($categories);
				//Work With Project Categories
				$project_categoriesNameArray 	           = $this->skills_model->convertCategoryIdsToName($categories);
				$project_categoriesNameString              = implode(',',$project_categoriesNameArray);
			    $updateDraft['project_categories']         = $project_categoriesNameString;
				//pr($updateDraft);
				if($updateDraft)            
				  {
					$condition = array('draftprojects.id'=>$draft->id);
					$this->skills_model->updateDraftProject($updateDraft,$condition);
				  }  
			   }
			   
			} 
			else
			   {
			   	   $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You cannot save this project as Draft')));
		           redirect('info');	
			   } 
		   //Notification message
		   $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your project has been saved as Draft')));
		   redirect('info/index/success');				  
		  }
		
		//Get Form Data	
		if($this->input->post('createProject') or $this->input->post('preview_project'))
		{	
//Set rules
			$this->form_validation->set_rules('projectName','lang:project_name_validation','required|trim|min_length[5]|xss_clean');
			$this->form_validation->set_rules('description','lang:description_validation','required|min_length[25]|trim|xss_clean');
			$this->form_validation->set_rules('attachment','lang:attachment_validation','callback_attachment_check');
			$this->form_validation->set_rules('categories[]','lang:categories_validation','required');
			$this->form_validation->set_rules('is_feature','lang:is_feature_validation','trim');
			$this->form_validation->set_rules('is_urgent','lang:is_urgent_validation','trim');
			$this->form_validation->set_rules('is_hide_bids','lang:is_hide_bids_validation','trim');
			
			if($this->form_validation->run())
			{
				  //This is condition check for post similar project
				  $conditions   = array('projects.project_name'=>$this->input->post('projectName'));
		          $postSimilar    = $this->skills_model->getUsersproject($conditions);
				  $res   =  $postSimilar->num_rows();
				  if($res > 0)
				  {
				  	$sameProject =  $postSimilar->row();
				  	$project = $sameProject->id;
				  }
				  //echo $this->input->post('update');
				  //Check the post project is already exists or not
				  //echo $this->input->post('update');
				  
				  if($this->input->post('update',true)===false)
				     $manage = '1';
				  else
				     $manage  = '0'; 	  
				  if($manage !='0')
				    {
					  if($res > 0)
						{
						   $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Project already Exists')));
						   redirect('project/postProject/'.$project);
						}
					}
				  
				  $insertData              		  	= array();	
			      $insertData['project_name']  	  	= $this->input->post('projectName');
				  $insertData['description']      	= $this->input->post('description');
				  if($this->input->post('update') == '0')
					{
					  $insertData['description']    	= $this->input->post('description').'<br/>';
					  $insertData['description']    	.= $this->input->post('add_description');
					} 
				  else
				      $insertData['description']    	= $this->input->post('description');	
				  $insertData['budget_min']    	  	= $this->input->post('budget_min');
				  $insertData['budget_max']       	= $this->input->post('budget_max');
				  $insertData['is_feature']       	= $this->input->post('is_feature');
				  $insertData['is_urgent']       	= $this->input->post('is_urgent');
				  $insertData['is_hide_bids']       = $this->input->post('is_hide_bids');
				  //$insertData['open_days']       	= $this->input->post('openDays');	
				  if($this->input->post('is_private'))
					{
					   $insertData['is_private']    = $this->input->post('is_private');
					} 
				  $insertData['creator_id']       	= $this->loggedInUser->id;
				  $insertData['created']       		= get_est_time();
				  $insertData['enddate']       		= get_est_time() + ($this->input->post('openDays') * 86400);
				  $result                           = '0';
				  if($this->input->post('preview_project'))
		          {
					   $this->outputData['showPreview']			= true;
					   $result                                  = '1';
					   $this->outputData['project_status']  	= 'Pending';
					   $this->outputData['project_name']  	  	= $this->input->post('projectName');
					   //Update additional information for projects
					   if($this->input->post('update') == '0')
					      {
					   	   $this->outputData['description']    	= $this->input->post('description').'<br>';
						   $this->outputData['description']    	.= $this->input->post('add_description');
						  } 
					   else
					       $this->outputData['description']    	= $this->input->post('description');	   
						
					   $this->outputData['budget_min']    	  	= $this->input->post('budget_min');
					   $this->outputData['budget_max']       	= $this->input->post('budget_max');
					   $this->outputData['is_feature']       	= $this->input->post('is_feature');
					   $this->outputData['is_urgent']       	= $this->input->post('is_urgent');
					   $this->outputData['is_hide_bids']        = $this->input->post('is_hide_bids');
					  // $this->outputData['open_days']       	= $this->input->post('openDays');	
					   if($this->input->post('is_private'))
						{
						   $insertData['is_private']            = $this->input->post('is_private');
						} 
					   $this->outputData['creator_id']       	= $this->loggedInUser->id;
					   $this->outputData['created']       		= get_est_time();
					   $this->outputData['enddate']       		= get_est_time() + ($this->input->post('openDays') * 86400);
					   $categories = $this->input->post('categories');
					  
					  //Work With Project Categories
					  $project_categoriesNameArray 	           = $this->skills_model->convertCategoryIdsToName($categories);
					  $project_categoriesNameString            = implode(',',$project_categoriesNameArray);
					  $this->outputData['project_categories']  = $project_categoriesNameString;
		         }
				 
				 //check the condition for view the preview about the project
				 if($result == '0' )
				 { 
					 //Get the values from settings table
					 $paymentSettings = $this->settings_model->getSiteSettings();
					 $feature_project = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
					 $urgent_project  = $paymentSettings['URGENT_PROJECT_AMOUNT'];
					 $hide_project    = $paymentSettings['HIDE_PROJECT_AMOUNT'];
					 $this->outputData['feature_project']  = $feature_project;
					 $this->outputData['urgent_project']   = $urgent_project;
					 $this->outputData['hide_project']     = $hide_project;
					 $this->outputData['created']          = get_est_time();
					 $this->outputData['enddate']          = get_est_time() + ($this->input->post('openDays') * 86400);
					  if($manage !='0')
						{
						 //initial value set for check the featured , urgent, hide projects
						 $settingAmount=0;
						 
						 //check the values for featured, urgent, hide projects
						 if($this->input->post('is_feature',TRUE))
							{
								$settingAmount=$settingAmount+$feature_project;
							}
						 if($this->input->post('is_urgent',TRUE))
							{
								$settingAmount=$settingAmount+$urgent_project;
							}
						 if($this->input->post('is_hide_bids',TRUE))
							{
								$settingAmount=$settingAmount+$hide_project;
							}
						
						//Check User Balance
						$condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);
						$results 	 			 = $this->transaction_model->getBalance($condition_balance);
						
						//If Record already exists
						if($results->num_rows()>0)
						{
							//get balance detail
							$rowBalance = $results->row();		
							$this->outputData['userAvailableBalance'] = $rowBalance->amount;
				//			$avail_balance                            = $rowBalance->amount;
						}	
						if($this->input->post('is_hide_bids',TRUE) or $this->input->post('is_urgent',TRUE) or $this->input->post('is_feature',TRUE)) 
						{
							$withdrawvalue = $rowBalance->amount - ( $settingAmount + $paymentSettings['PAYMENT_SETTINGS'] );
							if( $withdrawvalue < 0 )
							{
								$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('your not having sufficient balance')));
								//redirect('project/create');
							}
							else
							{
								//Check User Balance
								
								 //Update Amount	
								$updateKey 			  = array('user_balance.user_id'=>$this->loggedInUser->id);	
								$updateData 		  = array();
								$updateData['amount'] = $rowBalance->amount   -   $settingAmount;
								$results 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
								
									 //Insert transaction for post projects
								$insertTransaction = array(); 
								$insertTransaction['creator_id']  = $this->loggedInUser->id;
								$insertTransaction['type'] 		 = $this->lang->line('Project Fee');
								$insertTransaction['amount'] 	 = $settingAmount;
								$insertTransaction['transaction_time'] 	 	 = get_est_time();
								$insertTransaction['status'] 	 = 'Completed'; //Can Be success,failed,pending
								$insertTransaction['description'] = $this->lang->line('Project Fee for Bid');
								 
				
								  //$insertData['user_type'] 		 = $role;				  
								  
									if($this->loggedInUser->role_id == '1')
									  {
										$insertTransaction['buyer_id']   = $this->loggedInUser->id;
										$insertTransaction['user_type']  = $this->lang->line('Project Fee for Bid');
									  }
											  
									if($this->loggedInUser->role_id == '2')
									  {
										$insertTransaction['provider_id'] = $this->loggedInUser->id;
										$insertTransaction['user_type']   = $this->lang->line('Project Fee for Bid');
									  }
								  //pr($insertData);	
												  
								  $this->load->model('transaction_model');
								  $this->transaction_model->addTransaction($insertTransaction);	
								 
							}
						}
					 }		
					//Get payment settings for check minimum balance from settings table
					 $this->outputData['paymentSettings']	     = $paymentSettings;	
					 $this->outputData['PAYMENT_SETTINGS']       = $paymentSettings['PAYMENT_SETTINGS'];
						
					  $categories = $this->input->post('categories');
					  
					  //Work With Project Categories
					  $project_categoriesNameArray 	   = $this->skills_model->convertCategoryIdsToName($categories);
					  $project_categoriesNameString    = implode(',',$project_categoriesNameArray);
					  
					   $insertData['project_categories'] = $project_categoriesNameString;
					 
					 //Create Projects before it for update the projects datas for manage option	   
					  if($this->input->post('update') == '0')
						{
							if($this->input->post('projectid'))
							{
								
								//Notification message
								//Load Model For Mail
								$this->load->model('email_model');
								
								//Send Mail
								$conditionUserMail = array('email_templates.type'=>'projectpost_notification');
								$result            = $this->email_model->getEmailSettings($conditionUserMail);
								
								$rowUserMailConent = $result->row();
								
								$splVars = array("!site_name" => $this->config->item('site_title'), "!username" => $this->loggedInUser->user_name,"!profile" => $project_categoriesNameString, "!projectid" => $this->db->insert_id(),"!date"=>get_datetime(time()));
								$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
								$mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
								$toEmail = $this->loggedInUser->email;
								$fromEmail = $this->config->item('site_admin_mail',TRUE);
								//echo $mailContent;
								//$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);		
								
								//Update the data
								$project = $this->input->post('projectid');
								$condition 		 = array('projects.id'=>$project);
								$this->skills_model->manageProjects($insertData,$condition);
								
								$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Project has been Updated Successfully')));
								redirect('buyer/viewMyProjects');
							}
						}
					  if($this->input->post('createProject'))
						{
						   //Load Model For Mail
							$this->load->model('email_model');
							//Send Mail
							$conditionUserMail = array('email_templates.type'=>'projectpost_notification');
							$result            = $this->email_model->getEmailSettings($conditionUserMail);
							$rowUserMailConent = $result->row();
							$splVars = array("!site_name" => $this->config->item('site_title'), "!username" => $this->loggedInUser->user_name,"!profile" => $project_categoriesNameString, "!projectid" => $this->db->insert_id(),"!date"=>get_datetime(time()));
							$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
							$mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
							$toEmail = $this->loggedInUser->email;
							$fromEmail = $this->config->item('site_admin_mail',TRUE);
							//	$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);	
						    // insert the projects details into project table
						   $this->skills_model->createProject($insertData);
						   $delete_condition   =  array('draftprojects.project_name'=>$this->input->post('projectName'));
						   $this->skills_model->deletedraftprojects($delete_condition);
						   	
						   //Notification message
						   $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Project has been Posted Successfully')));
						   redirect('buyer/viewMyProjects');
						}  
				  redirect('info/index/success');
				}
				
			}//Form Validation End
		}//If - Form Submission End
	
		//Get Groups
		$this->outputData['groupsWithCategories']	=	$this->skills_model->getGroupsWithCategory();
	   
	    if($result == '0' )
		  {
	        $this->load->view('project/createProject',$this->outputData);
	      }
	   else
		 {
		   $this->load->view('project/previewProject',$this->outputData);		
		 }
	} //Function create End
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Buyer signUp page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function createBid()
	{	

		//Load Language
		$this->lang->load('enduser/createBids', $this->config->item('language_code'));

		//Check For Buyer Session
		if(!isSeller())
		{
        	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged in as a Programmer to bid projects')));
			redirect('info');
		}	
		
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		//Get Form Data	
		if($this->input->post('postBid'))
		{	
			

			//Set rules
			$this->form_validation->set_rules('bidAmt','lang:Bid_Amount_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('days','lang:Bid_days_validation','required|trim|xss_clean');
			
			
			
			if($this->form_validation->run())
			{
				  $insertData              		  	= array();	
			      $insertData['project_id']  	  	= $this->input->post('project_id');
				  $insertData['user_id']      		= $this->loggedInUser->id;
				  $insertData['bid_days']    	  	= $this->input->post('days');
				  $insertData['bid_hours']    	  	= $this->input->post('hours');
				  $insertData['bid_amount']       	= $this->input->post('bidAmt');
				  $insertData['bid_time']       	= get_est_time();
				  $insertData['bid_desc']       	= $this->input->post('message2');
				  
				  //Check User Balance
				  
				  //Create bids
				  $this->skills_model->createBids($insertData);				  				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your bid Has Been Posted Successfully')));
				  redirect('info/index/success');
				
			}//Form Validation End
			else{
			
				$project_id = $this->input->post('project_id');
				$conditions = array('projects.id'=>$project_id);
				$this->outputData['projects']	   		=  $this->skills_model->getProjects($conditions);
				
				$conditions = array('bids.user_id'=>$this->loggedInUser->id,'bids.project_id'=>$project_id);
				$this->outputData['bid']  =  $this->skills_model->getBids($conditions);
				
				$this->outputData['project_id'] = $project_id;
				$this->load->model('messages_model');
				$message_conditions = array('messages.project_id'=>$project_id);
				$this->outputData['totalMessages']	    =  $this->messages_model->getTotalMessages($message_conditions);
			   
			}
		}//If - Form Submission End
		$this->load->view('project/postBid',$this->outputData);
	} //Function create End
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Buyer signUp page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function editBid()
	{	

		//Load Language
		$this->lang->load('enduser/createBids', $this->config->item('language_code'));

		//Check For Buyer Session
		if(!isSeller())
		{
        	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged in as a Programmer to bid projects')));
			redirect('info');
		}	
		
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
	
		//Get Form Data	
		if($this->input->post('postBid'))
		{	
			//echo "hi";exit;

			//Set rules
			$this->form_validation->set_rules('bidAmt','lang:Bid_Amount_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('days','lang:Bid_days_validation','required|trim|xss_clean');
			
			
			
			if($this->form_validation->run())
			{
				  //print_r($_POST);exit;
				  $updateData              		  	= array();	
			      $updateData['project_id']  	  	= $this->input->post('project_id');
				  $updateData['user_id']      		= $this->loggedInUser->id;
				  $updateData['bid_days']    	  	= $this->input->post('days');
				  $updateData['bid_hours']    	  	= $this->input->post('hours');
				  $updateData['bid_amount']       	= $this->input->post('bidAmt');
				  $updateData['bid_desc']       	= $this->input->post('message2');
				  
				  
				  //Create bids
				  $this->skills_model->updateBids($this->input->post('bidId'),$updateData);
				  				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your bid Has Been edited Successfully')));
				  redirect('info/index/success');
				
			}//Form Validation End
			else{
			
				//Get Project Id
				$project_id = $this->input->post('project_id');
				$conditions = array('bids.user_id'=>$this->loggedInUser->id,'bids.project_id'=>$project_id);
				$this->outputData['bid']  =  $this->skills_model->getBids($conditions);
				
				$conditions1 = array('projects.id'=>$project_id);
				$this->outputData['projects']  =  $this->skills_model->getProjects($conditions1);
				$this->outputData['project_id'] = $project_id;
		
				//Get Total Messages
				$this->load->model('messages_model');
				$message_conditions = array('messages.project_id'=>$project_id);
				$this->outputData['totalMessages']	    =  $this->messages_model->getTotalMessages($message_conditions);
			   
			}
		}//If - Form Submission End
		$this->load->view('project/postBid',$this->outputData);
	} //Function create End
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Categories page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function category()
	{	
		//Load Language
		$this->lang->load('enduser/listProjects', $this->config->item('language_code'));	
		
		if($this->input->post('customizeDisplay'))
		{
			//Get Customize data fields

			$this->session->set_userdata('show_cat',$this->input->post('show_cat',true));

			$this->session->set_userdata('show_budget',$this->input->post('show_budget',true));

			$this->session->set_userdata('show_bids',$this->input->post('show_bids',true));
			
			$this->session->set_userdata('show_avgbid',$this->input->post('show_avgbid',true));

			$this->session->set_userdata('show_status',$this->input->post('show_status',true));

			$this->session->set_userdata('show_date',$this->input->post('show_date',true));
			
			$this->session->set_userdata('show_desc',$this->input->post('show_desc',true));
			
			$this->session->set_userdata('show_num',$this->input->post('show_num',true));

		}
		else{
			$this->session->set_userdata('show_cat','1');

			$this->session->set_userdata('show_budget','1');

			$this->session->set_userdata('show_bids','1');
			
			$this->session->set_userdata('show_num','5');
		}
		
		//Get Category Id
		$category_name = urldecode($this->uri->segment(3,'0'));
		//echo urldecode($category_name);exit;
		$category_name = replaceUnderscoreWithSpace($category_name);
		
		//Get current page
		$page = $this->uri->segment(4,'0');
		
		//Get Sorting order
		$field = $this->uri->segment(5,'0');
		
		$order = $this->uri->segment(6,'0');
		$this->outputData['order']	=  $order;
		$this->outputData['field']	=  $field;
		
		$orderby = array();
		if($field)
		$orderby = array($field,$order);
				
		//pr($orderby);exit;
		if(isset($page)===false or empty($page)){
			$page = 1;
		}
		$page_rows = $this->session->userdata('show_num');

		$max = array($page_rows,($page - 1) * $page_rows);
		
		//Get Category Info
		//Set Conditions
		 $conditions = array('category_name'=>$category_name);
		 $categories = $this->skills_model->getCategories($conditions); 
		 
		 if($categories->num_rows()>0)
			$category	=  $categories->row(); 
			
		$this->outputData['category_id']	=  $category->id;
		$this->outputData['category_name']	=  $category->category_name;
		
		//Projects List
		$like  = array('project_categories' => $category_name);
		$this->outputData['projects']	   =  $this->skills_model->getProjects(NULL,NULL,$like,$max,$orderby);
		$projects 						   =  $this->skills_model->getProjects(NULL,NULL,$like);
		
		//Pagination
		$this->load->library('pagination');

		$config['base_url'] 	= site_url('project/category/'.$category_name);

		$config['total_rows'] 	= $projects->num_rows();		
		
		$config['per_page'] = $page_rows; 
		
		$config['cur_page'] = $page;
		
		$this->outputData['page']	=  $page;

		$this->pagination->initialize($config);		

		$this->outputData['pagination']   = $this->pagination->create_links(false,'project');

	    $this->load->view('project/listProjects',$this->outputData);
	} //Function category End
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads project view page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function view()
	{	
		//Load Language
		$this->lang->load('enduser/viewProject', $this->config->item('language_code'));
		
		//Debug Tool
	   //$this->output->enable_profiler=true;
		
		//Get Project Id
		$project_id	 = $this->uri->segment(3,'0');
		$conditions = array('projects.id'=>$project_id);
		$this->outputData['projects']  =  $this->skills_model->getProjects($conditions);
		//pr($this->outputData['projects']->result());exit;
		if($this->outputData['projects']->num_rows() == 0){
		//Notification message
		 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('project_not_available')));
		 redirect('info');
		}
		
		//Get Total Messages
		$this->load->model('messages_model');
		$message_conditions = array('messages.project_id'=>$project_id);
		$this->outputData['totalMessages']	    =  $this->messages_model->getTotalMessages($message_conditions);	
	   
	    $this->load->view('project/viewProject',$this->outputData);
	   
	} //Function view End
	
	
	/**
	 * Loads project view page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function draftView()
	{	
		
		//pr($_POST);
		//Load Language
		$this->lang->load('enduser/viewProject', $this->config->item('language_code'));
		$this->outputData['groupsWithCategories']	=	$this->skills_model->getGroupsWithCategory();
		//Debug Tool
	   //$this->output->enable_profiler=true;
		
		//Get Project Id
		$project_id	 = $this->input->post('draftId');
		$this->outputData['draftProjectsid'] = $project_id;
		$conditions = array('draftprojects.id'=>$project_id);
		$this->outputData['projects']  =  $this->skills_model->getDraft($conditions);
		if($this->input->post('draftId') == 'clear')
		 {
		 	redirect('project/create');
		 }
		if($this->input->post('draftId') == 'savedraft')
		 {
		 	redirect('project/create');
		 } 
		if($this->outputData['projects']->num_rows() == 0){
		//Notification message
		  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('project_not_available')));
		  redirect('info');
		}
		
		//Get Total Messages
		$this->load->model('messages_model');
		$message_conditions = array('messages.project_id'=>$project_id);
		$this->outputData['totalMessages']	    =  $this->messages_model->getTotalMessages($message_conditions);	
	   
	    $this->load->view('project/draftProject',$this->outputData);
	   
	} //Function view End
	
	
	/**
	 * Loads project view page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function previewProject()
	{	
		//Load Language
		$this->lang->load('enduser/viewProject', $this->config->item('language_code'));
		
		//Debug Tool
	   //$this->output->enable_profiler=true;
		
		//Get Project Id
		$project_id	 = $this->uri->segment(3,'0');
		//pr($project_id);
		//exit;
		$conditions = array('projects.id'=>$project_id);
		$this->outputData['projects']  =  $this->skills_model->getProjects($conditions);
		
		//Get Total Messages
		$this->load->model('messages_model');
		$message_conditions = array('messages.project_id'=>$project_id);
		$this->outputData['totalMessages']	    =  $this->messages_model->getTotalMessages($message_conditions);	
	   
	    $this->load->view('project/viewProject',$this->outputData);
	   
	} //Function view End
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Categories page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function postBid()
	{	
		//Load Language
		$this->lang->load('enduser/postBid', $this->config->item('language_code'));
		
		//Check For Programmer Session
		if(!isSeller())
		{
        	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('You must be logged in as a programmer to place a bid')));
			redirect('info');
		}
		
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		//Get Project Id
		$project_id	 = $this->uri->segment(3,'0');
		$conditions = array('bids.user_id'=>$this->loggedInUser->id,'bids.project_id'=>$project_id);
		$this->outputData['bid']  =  $this->skills_model->getBids($conditions);
		
		$conditions1 = array('projects.id'=>$project_id);
		$this->outputData['projects']  =  $this->skills_model->getProjects($conditions1);
		$this->outputData['project_id'] = $project_id;
		//pr($this->outputData['projects']->result());exit;
		
		//Get Total Messages
		$this->load->model('messages_model');
		$message_conditions = array('messages.project_id'=>$project_id);
		$this->outputData['totalMessages']	    =  $this->messages_model->getTotalMessages($message_conditions);	
	   
	    //Get the favourite usersList
		$favourite_condition           = array('user_list.creator_id'=>$this->loggedInUser->id);
		$this->outputData['favourite'] = $this->user_model->getFavourite($favourite_condition);
	   
	   $this->load->view('project/postBid',$this->outputData);
	   
	} //Function view End
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads attachment_check for buyer
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function attachment_check()
	{
		if(isset($_FILES) and $_FILES['attachment']['name']=='')				
			return true;
		
		$config['upload_path'] 		='files/project_attachment/';
		$config['allowed_types'] 	='jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|zip|ZIP|RAR|rar|doc|DOC|txt|TXT|xls|XLS|ppt|PPT|pdf|PDF';
		$config['max_size'] 		= $this->config->item('max_upload_size');
		$config['encrypt_name'] 	= TRUE;
		$config['remove_spaces'] 	= TRUE;
		$this->load->library('upload', $config);
		
		if ($this->upload->do_upload('attachment'))
		{
			$this->data['file'] = $this->upload->data();			
			return true;			
		} else {
			$this->form_validation->set_message('attachment_check', $this->upload->display_errors($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag')));
			return false;
		}//If end 
	
	}//Function attachment_check End
	
	// --------------------------------------------------------------------
	
	/**
	 * List bids on the particular project
	 *
	 * @access	public
	 * @param	project id
	 * @return	contents
	 */ 
	function showBids()
	{
		//Load Language
		$this->lang->load('enduser/viewProject', $this->config->item('language_code'));
		
		$this->load->helper('users_helper');
		
		//Get Project Id
		$project_id	 = $this->uri->segment(3,'0');
		
		//Get Project details
		$conditions = array('projects.id'=>$project_id);
		$project = $this->skills_model->getProjects($conditions);
		$this->outputData['projectRow'] = $project->row();
		
		$this->outputData['creatorInfo'] = getUserInfo($this->outputData['projectRow']->creator_id);
		//pr($this->outputData['projectRow']);exit;
		
		$conditions = array('bids.project_id'=>$project_id,'projects.is_hide_bids' => 0);
		
		$order = $this->uri->segment(4,'0');
		$field = $this->uri->segment(5,'0');
		if($order != 0)
			$orderby = array($field,$order);
		else
			$orderby = array();
		
		$this->outputData['bids']  =  $this->skills_model->getBids($conditions,'',array(),array(),$orderby);

		$this->outputData['ord'] = $order;
		
		$this->outputData['field'] = $field;
		
		$this->outputData['projectId'] = $project_id;
		
		if(isset($this->loggedInUser->id)){
		
			$conditions = array('bids.user_id' => $this->loggedInUser->id,'bids.project_id'=>$project_id);
			
			$totbid  =  $this->skills_model->getBids($conditions);
			
			$this->outputData['tot'] = $totbid->row();
		}
		else
			$this->outputData['tot'] = array();
		//pr($this->outputData['bids']->result());exit;
		$this->load->view('project/showBids',$this->outputData);
	
	}//Function showBids End
	
	// --------------------------------------------------------------------
	
	/**
	 * List bids on the particular project to pick a Programmer
	 *
	 * @access	public
	 * @param	project id
	 * @return	contents
	 */ 
	function pickProvider()
	{
		//Load Language
		$this->lang->load('enduser/pickProvider', $this->config->item('language_code'));
		
		//Debug Tool
	   //$this->output->enable_profiler=true;
		
		//Get Project Id
		$project_id	 = $this->uri->segment(3,'0');
		
		$conditions = array('bids.project_id'=>$project_id);
		
		$order = $this->uri->segment(4,'0');
		
		//Get the favourite usersList
		$favourite_condition           = array('user_list.creator_id'=>$this->loggedInUser->id);
		$this->outputData['favourite'] = $this->user_model->getFavourite($favourite_condition);
		
		if(isset($order))
		$orderby = array('bid_amount',$order);
		else
		$orderby = array();
		
		$this->outputData['bids']  =  $this->skills_model->getBids($conditions,'',array(),array(),$orderby);
		
		$this->outputData['ord'] = $order;
		
		$this->load->view('project/pickProvider',$this->outputData);
	
	}//Function showBids End
	
	// --------------------------------------------------------------------
	
	/**
	 * List all projects
	 *
	 * @access	public
	 * @param	project id
	 * @return	contents
	 */ 
	function viewAllProjects()
	{
		//Load Language
		$this->lang->load('enduser/featuredProjects', $this->config->item('language_code'));
		
		//Debug Tool
	   //$this->output->enable_profiler=true;
	   
	   if($this->input->post('customizeDisplay'))
		{
			//Get Customize data fields

			$this->session->set_userdata('show_cat',$this->input->post('show_cat',true));

			$this->session->set_userdata('show_budget',$this->input->post('show_budget',true));

			$this->session->set_userdata('show_bids',$this->input->post('show_bids',true));
			
			$this->session->set_userdata('show_avgbid',$this->input->post('show_avgbid',true));

			$this->session->set_userdata('show_status',$this->input->post('show_status',true));

			$this->session->set_userdata('show_date',$this->input->post('show_date',true));
			
			$this->session->set_userdata('show_desc',$this->input->post('show_desc',true));
			
			$this->session->set_userdata('show_num',$this->input->post('show_num',true));

		}
		else{
			$this->session->set_userdata('show_cat','1');

			$this->session->set_userdata('show_budget','1');

			$this->session->set_userdata('show_bids','1');
			
			$this->session->set_userdata('show_num','5');
		}
		$type = $this->uri->segment(3,'0');
		
		if($type == 'is_feature')
		$this->outputData['pName'] = 'Featured Projects';
		if($type == 'is_urgent')
		$this->outputData['pName'] = 'Urgent Projects';
		if($type == 'all')
		$this->outputData['pName'] = 'Projects';
		
		$page = $this->uri->segment(4,'0');
		
		//Get Sorting order
		$field = $this->uri->segment(5,'0');
		
		$order = $this->uri->segment(6,'0');
		$this->outputData['order']	=  $order;
		$this->outputData['field']	=  $field;
		$this->outputData['type']	=  $type;
		$this->outputData['page']	=  $page;
		
		//pr($page);exit;
		if(isset($page)===false or empty($page)){
			$page = 1;
		}
		$page_rows = $this->session->userdata('show_num');
		//$page_rows = 1;
		//echo $page_rows;

		$max = array($page_rows,($page - 1) * $page_rows);
	   
	    if($type == 'all')
		$feature_conditions = array('projects.project_status' => '0');
		else
		$feature_conditions = array($type =>1,'projects.project_status' => '0');
		$projects1 = $this->skills_model->getProjects($feature_conditions,NULL,NULL,$max);
		$projects = $this->skills_model->getProjects($feature_conditions);
		$this->outputData['featureProjects'] = $projects1;

		$this->load->library('pagination');

		$config['base_url'] 	= site_url('project/viewAllProjects/'.$type);

		$config['total_rows'] 	= $projects->num_rows();		
		
		$config['per_page'] = $page_rows; 
		
		$config['cur_page'] = $page;

		$this->pagination->initialize($config);		

		$this->outputData['pagination']   = $this->pagination->create_links(false,'project');
		
		$this->load->view('project/viewAllProjects',$this->outputData);
	
	}//Function showBids End
	
	// --------------------------------------------------------------------
	
	/**
	 * List bids on the particular project to pick a Programmer
	 *
	 * @access	public
	 * @param	project id
	 * @return	contents
	 */ 
	function awardBid()
	{
		//Load Language
		$this->lang->load('enduser/pickProvider', $this->config->item('language_code'));
		
		if($this->input->post('pickBid'))
		{
			$bidid = $this->input->post('bidid');
			$conditions = array('bids.id'=>$bidid);
			$up = $this->skills_model->awardProject($conditions);
			//echo $up;exit;
			
			if($up == 1){
				//Load Model For Mail
					$this->load->model('email_model');
					
				//Send Mail
				$conditionUserMail = array('email_templates.type'=>'awardBid');
				$result            = $this->email_model->getEmailSettings($conditionUserMail);
				
				$rowUserMailConent = $result->row();
				
				$bidres = $this->skills_model->getProjectByBid(array('bids.id'=>$bidid));
				$bidres = $bidres->row();
				
				$splVars = array("!project_title" => $bidres->project_name, "!bid_url" => site_url('project/acceptProject/'.$bidres->id."/".$bidres->checkstamp),"!deny_url" => site_url('project/denyProject/'.$bidres->id."/".$bidres->checkstamp), "!contact_url" => site_url('contact'));
				$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
				$mailContent = strtr($rowUserMailConent->mail_body, $splVars);
				$toEmail = $bidres->email;
				$fromEmail = $this->config->item('site_admin_mail');
				//echo $mailContent;exit;
				$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
				
				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('You have successfully awarded the project')));
				//redirect('info/index/success');
				redirect('buyer/viewMyProjects');
			}
		}
	
	}//Function acceptProject End
	
	// --------------------------------------------------------------------
	
	/**
	 * Accept project from Buyer who accepted your bid
	 *
	 * @access	public
	 * @param	project id and checkstamp
	 * @return	contents
	 */ 
	function acceptProject()
	{
		//Load Language
		$this->lang->load('enduser/acceptProject', $this->config->item('language_code'));
		
		//Check For Programmer Session
		if(!isSeller())
		{
        	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged in as a programmer to accept projects')));
			redirect('users/login');
		}
		
		
		$project_id	 = $this->uri->segment(3,'0');
		$checkstamp = $this->uri->segment(4,'0');
		
		$conditions = array('projects.id'=>$project_id,'projects.checkstamp'=>$checkstamp,'projects.project_status' => '1','projects.programmer_id' => $this->loggedInUser->id);
		$project = $this->skills_model->getProjects($conditions);
		$projectRow = $project->row();
		
		if(!is_object($projectRow)){
		$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You cannot accept this project')));
		redirect('info');
		}
		
		
		$buyerId = $projectRow->creator_id;
		$programmerId = $projectRow->programmer_id;
		
		$conditions2 = array('users.id' => $buyerId);
		$buyer = $this->user_model->getUsers($conditions2);
		$buyerRow = $buyer->row();
		
		$conditions3 = array('users.id' => $programmerId);
		$programmer = $this->user_model->getUsers($conditions3);
		$programmerRow = $programmer->row();
				
		$updateKey = array(
					'projects.id' => $project_id,
					'projects.checkstamp' => $checkstamp,
					'projects.programmer_id' => $programmerId
			   		);
		$updateData = array('projects.project_status'=> '2');
		$upProject = $this->skills_model->accpetProject($updateKey,$updateData);
		
		if($upProject == 1){
			//Load Model For Mail
			$this->load->model('email_model');
				
			//Send Mail to Buyer
			$conditionUserMail = array('email_templates.type'=>'project_accepted_buyer');
			$result            = $this->email_model->getEmailSettings($conditionUserMail);
			
			$rowUserMailConent = $result->row();
			
			$splVars = array("!programmer_username" => $programmerRow->user_name, "!project_title" => $projectRow->project_name, "!programmer_email" => $programmerRow->email,"!contact_url" => site_url('contact'));
			$mailSubject = $this->lang->line($rowUserMailConent->mail_subject);
			$mailContent = strtr($rowUserMailConent->mail_body, $splVars);
			$toEmail = $buyerRow->email;
			$fromEmail = $this->config->item('site_admin_mail');

			$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
			
			//Send Mail to Programmer
			$conditionUserMail2 = array('email_templates.type'=>'project_accepted_programmer');
			$result2           = $this->email_model->getEmailSettings($conditionUserMail2);
			
			$rowUserMailConent2 = $result2->row();
			
			$splVars2 = array("!project_title" => $projectRow->project_name, "!buyer_username" => $buyerRow->user_name, "!buyer_email" => $buyerRow->email,"!contact_url" => site_url('contact'));
			$mailSubject2 = $this->lang->line($rowUserMailConent2->mail_subject);
			$mailContent2 = strtr($rowUserMailConent2->mail_body, $splVars2);
			$toEmail2 = $programmerRow->email;
			$fromEmail2 = $this->config->item('site_admin_mail');
			//echo $mailContent;exit;
			$this->email_model->sendHtmlMail($toEmail2,$fromEmail2,$mailSubject2,$mailContent2);
			
			//Notification message
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('You have successfully accepted the project')));
			redirect('info/index/success');
		}
	
	}//Function acceptProject End
	
	// --------------------------------------------------------------------
	
	/**
	 * Accept project from Buyer who accepted your bid
	 *
	 * @access	public
	 * @param	project id and checkstamp
	 * @return	contents
	 */ 
	function denyProject()
	{
		//Load Language
		$this->lang->load('enduser/denyProject', $this->config->item('language_code'));
		
		
		$project_id	 = $this->uri->segment(3,'0');
		$checkstamp = $this->uri->segment(4,'0');
		
		$conditions = array('projects.id'=>$project_id,'projects.checkstamp'=>$checkstamp,'projects.project_status' => '1','projects.programmer_id' => $this->loggedInUser->id);
		$project = $this->skills_model->getProjects($conditions);
		$projectRow = $project->row();
		
		if(!is_object($projectRow)){
		$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You cannot deny this project')));
		redirect('info');
		}
		
		$buyerId = $projectRow->creator_id;
		$programmerId = $projectRow->programmer_id;
		
		$conditions2 = array('users.id' => $buyerId);
		$buyer = $this->user_model->getUsers($conditions2);
		$buyerRow = $buyer->row();
		
		$conditions3 = array('users.id' => $programmerId);
		$programmer = $this->user_model->getUsers($conditions3);
		$programmerRow = $programmer->row();
				
		$updateKey = array(
					'projects.id' => $project_id,
					'projects.checkstamp' => $checkstamp,
					'projects.programmer_id' => $programmerId
			   		);
		$updateData = array('projects.project_status'=> '0','projects.programmer_id' => '0');
		$upProject = $this->skills_model->accpetProject($updateKey,$updateData);
		
		if($upProject == 1){
			//Load Model For Mail
			$this->load->model('email_model');
				
			//Send Mail to Buyer
			$conditionUserMail = array('email_templates.type'=>'project_denied_buyer');
			$result            = $this->email_model->getEmailSettings($conditionUserMail);
			
			$rowUserMailConent = $result->row();
			
			$splVars = array("!provider_username" => $programmerRow->user_name, "!project_title" => $projectRow->project_name,"!contact_url" => site_url('contact'));
			$mailSubject = $this->lang->line($rowUserMailConent->mail_subject);
			$mailContent = strtr($rowUserMailConent->mail_body, $splVars);
			$toEmail = $buyerRow->email;
			$fromEmail = $this->config->item('site_admin_mail');

			$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
			
			//Notification message
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('You have successfully denied the project')));
			redirect('info/index/success');
		}
	
	}//Function acceptProject End
	
	// --------------------------------------------------------------------
	
	/**
	 * Accept project from Buyer who accepted your bid
	 *
	 * @access	public
	 * @param	project id and checkstamp
	 * @return	contents
	 */ 
	function postProject()
	{
		if($this->loggedInUser->role_id)
		  {
		  	if($this->loggedInUser->role_id == '2')
			  {
			  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged in as a buyer to post projects')));
			    redirect('info');
			  }
		  }
		$project_id    =    $this->uri->segment(3,0);
		//Initital payment settings for projects
		$paymentSettings = $this->settings_model->getSiteSettings();
  	    $this->outputData['feature_project']   = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
		$this->outputData['urgent_project']    = $paymentSettings['URGENT_PROJECT_AMOUNT'];
		$this->outputData['hide_project']      = $paymentSettings['HIDE_PROJECT_AMOUNT'];
		//Get the project details for post similar projects
		
		$conditions   = array('projects.id'=>$project_id);
		$postSimilar    = $this->skills_model->getUsersproject($conditions);
		//pr($postSimilar->result());
		$this->outputData['postSimilar']   =  $postSimilar;
		//getUsersproject
		
		//Laod the categories into the view page
		$this->outputData['groupsWithCategories']	=	$this->skills_model->getGroupsWithCategory();
		$this->load->view('project/postProject',$this->outputData);
	
	}//Function acceptProject End
	
	// --------------------------------------------------------------------
	
	/**
	 * Accept project from Buyer who accepted your bid
	 *
	 * @access	public
	 * @param	project id and checkstamp
	 * @return	contents
	 */ 
	function manageProject()
	{
		//Load Language
		$this->lang->load('enduser/createProject', $this->config->item('language_code'));
		
		if($this->loggedInUser->role_id)
		  {
		  	if($this->loggedInUser->role_id == '2')
			  {
			  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged in as a buyer to post projects')));
			    redirect('info');
			  }
		  }
		
		$project_id    =    $this->uri->segment(3,0);
		
		//Initital payment settings for projects
		$paymentSettings = $this->settings_model->getSiteSettings();
  	    $this->outputData['feature_project']   = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
		$this->outputData['urgent_project']    = $paymentSettings['URGENT_PROJECT_AMOUNT'];
		$this->outputData['hide_project']      = $paymentSettings['HIDE_PROJECT_AMOUNT'];
		//Get the project details for post similar projects
		
		$conditions   = array('projects.id'=>$project_id,'projects.creator_id'=>$this->loggedInUser->id);
		$postSimilar    = $this->skills_model->getUsersproject($conditions);
		$res = $postSimilar->num_rows();
		if($res <= 0)
		  {
		    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Your are not allow to manage this project')));
			redirect('project/view/'.$project_id);
		  }
		$this->outputData['postSimilar']   =  $postSimilar;
		$this->outputData['update']        =  'update';
		//getUsersproject
		
		//Laod the categories into the view page
		$this->outputData['groupsWithCategories']	=	$this->skills_model->getGroupsWithCategory();
		$this->load->view('project/manageProject',$this->outputData);
	
	}//Function acceptProject End
	
	// --------------------------------------------------------------------
	
	/**
	 * Accept project from Buyer who accepted your bid
	 *
	 * @access	public
	 * @param	project id and checkstamp
	 * @return	contents
	 */ 
	function postReport()
	{
		
		//Load Language
		$this->lang->load('enduser/createProject', $this->config->item('language_code'));
		$project_id   =  $this->uri->segment(3,0);
		$conditions   = array('projects.id'=>$project_id/*,'projects.creator_id'=>$this->loggedInUser->id*/);
		$postSimilar    = $this->skills_model->getUsersproject($conditions);
		$this->outputData['postSimilar']   =   $postSimilar;
		$res = $postSimilar->num_rows();
		if($this->input->post('submitReport'))
		  {
		  	$insertData['id']    =  '';
			$insertData['project_id']    =  $project_id;
			$insertData['project_name']  =  $this->input->post('projectname');
			$insertData['post_id']       =  $this->loggedInUser->id;
			$insertData['post_name']     =  $this->loggedInUser->user_name;
			$insertData['comment']       =  $this->input->post('report');
			$insertData['date']          =  '';
			
			//insert the report contents into the project_reports table
			$this->skills_model->insertReport($insertData);
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your report has been send successfully')));
			redirect('project/postReport/'.$project_id);
		  }
	    $this->load->view('project/projectReport',$this->outputData);
	}//Function acceptProject End
	
	// --------------------------------------------------------------------
	
	/**
	 * Create invoice report for the logged user
	 *
	 * @access	private
	 * @param	project id and checkstamp
	 * @return	contents
	 */ 
	function invoice()
	{
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		$check = '0';
		//Check will assign to 1 while the invoice submittion
		if($this->input->post('invoice'))
		 {
			 $check = '1';
		 }
		
		//Load Language
		$this->lang->load('enduser/createProject', $this->config->item('language_code'));
		$this->lang->load('enduser/invoice', $this->config->item('language_code'));
		if($check == '0')
		  {
			$project_id    =    $this->uri->segment(3,0);
			//Get the project details for post similar projects
			
			$conditions   = array('projects.creator_id'=>$this->loggedInUser->id,'projects.project_status'=>'2','projects.project_paid'=>'1');
			$postSimilar    = $this->skills_model->getUsersproject($conditions);
			$this->outputData['postSimilar']   =   $postSimilar;
			$count = $postSimilar->num_rows();
			$res = $postSimilar->num_rows();
			if($res <= 0 )
			  {
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your report has been send successfully')));
			  }
			
		 } 
		//Load the view for the invoice
		if($check == '0')
		  {
		    
			$this->load->view('project/projectInvoice',$this->outputData);
		  }	
		else
		   { 
			 $this->outputData['project_name']    = $this->input->post('project_name');
			 $this->outputData['user_name']       = $this->input->post('user_name');
			 $this->outputData['bidsProjects']    = $this->input->post('invoice');
			 $this->outputData['invoice_no']      = $this->input->post('invoice_no');
			 $this->outputData['bidsProjects']    = $this->skills_model->getBidsproject();
			 $this->load->view('project/invoice',$this->outputData);  
			 //pr($this->outputData['bidsProjects']->result());
		   }	 
	
	}//Function acceptProject End
	
	// --------------------------------------------------------------------
	
	/**
	 * Create invite report for the logged user
	 *
	 * @access	private
	 * @param	project id and checkstamp
	 * @return	contents
	 */ 
	function inviteUser()
	{
		//Load Language
		$this->lang->load('enduser/userlist', $this->config->item('language_code'));
		
		if($this->loggedInUser)
		  {
			$userid =  $this->loggedInUser->id;
			$condition = array('projects.creator_id'=>$userid);
			$res = $this->skills_model->getUsersproject($condition);
		    if($res->num_rows() > 0)
			   {	
			  	  $condition                            = array('user_list.creator_id'=>$this->loggedInUser->id);
				  $this->outputData['favouriteList']    =   $this->user_model->getFavourite($condition);
				  //pr($this->outputData['favouriteList']);
				  $this->load->view('buyer/inviteProgrammer',$this->outputData); 
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
	
	}//Function acceptProject End
	
	// --------------------------------------------------------------------
	
	/**
	 * Check and close the projects if their bidding end date is expired.
	 *
	 * @access	private
	 * @param	project id and checkstamp
	 * @return	contents
	 */ 
	function biddingEndCheck()
	{
		$projects = $this->skills_model->getProjects();
		foreach($projects->result() as $res){
			$diff = $res->enddate-time();
			if($diff <= 0){
				$updateKey = array('projects.id' => $res->id);
				$updateData = array('projects.project_status' => '3');
				$this->skills_model->updateProjects(NULL,$updateData,$updateKey);
			}
		}
		redirect('home');
	}//Function biddingEndCheck End
	
	// --------------------------------------------------------------------
	
	/**
	 * Extend project bid
	 *
	 * @access	private
	 * @param	project id and checkstamp
	 * @return	contents
	 */ 
	function extendBid()
	{
		//Load Language
		$this->lang->load('enduser/viewProject', $this->config->item('language_code'));
		
		if($this->input->post('extend')){
			
			$condition2 = array('projects.id' => $this->input->post('projectid'));
			$res = $this->skills_model->getProjects($condition2);
			$row = $res->row();
			//pr($row);exit;
			if($row->project_status == 3){
				$enddate = get_est_time() + ($this->input->post('openDays') * 86400);
				$updateKey = array('projects.id' => $this->input->post('projectid'));
				$updateData = array('projects.enddate' => $enddate,'projects.project_status' => '0');
				$this->skills_model->updateProjects(NULL,$updateData,$updateKey);
				redirect('buyer/viewMyProjects');
			}
		}
		
		$prjid = $this->uri->segment(3,'0');
		$condition = array('projects.id' => $prjid);
		$this->outputData['project']	= $this->skills_model->getProjects($condition);
		
		$this->load->view('buyer/extend',$this->outputData);
	}//Function extendBid End
	
	function mailSend(){
		
		//Load Model For Mail
		$this->load->model('email_model');
		
		//Send Mail
		
		$mailSubject = "Cron testing";
		
		$mailContent = "Cron testing";		
		
		
		
		$toEmail = 'sathick@cogzidel.com';
		
		
		
		$fromEmail = 'a.sathick@gmail.com';
		
		//echo $mailContent;exit;
		
		$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
	}
	
} //End  Project Class

/* End of file Project.php */ 
/* Location: ./app/controllers/Project.php */
?>