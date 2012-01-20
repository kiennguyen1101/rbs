<?php
		$this->outputData['current_page'] = 'post_project';

		$result = '0';
		$manage = '1';
		$this->outputData['showPreview']			= false;
		
		//Load Language
		$this->lang->load('enduser/withdrawMoney', $this->config->item('language_code'));
		$this->lang->load('enduser/createBids', $this->config->item('language_code'));
		$this->lang->load('enduser/createProject', $this->config->item('language_code'));
		
		$this->outputData['created']          = get_est_time();
		$this->outputData['enddate']          = get_est_time() + ($this->input->post('openDays') * 86400);
		
		//Check For Buyer Session
		if(!isBuyer())
		{
        	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged in as a buyer to post projects')));
			redirect('info');
		}	
		 if($this->loggedInUser->suspend_status==1)
		 {
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Suspend Error')));
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
			
			 if($this->input->post('projectName'))
			  {	
				$draft_name = $this->input->post('projectName');
				$condition  =  array('draftprojects.project_name'=>$draft_name);
				$draft = $this->skills_model->getDraft($condition);
				
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
					   $insertData['private_users']=$this->input->post('private_list');
					}   
					$insertData['creator_id']       	= $this->loggedInUser->id;
					$insertData['created']       		= get_est_time();
					$insertData['enddate']       		= get_est_time() + ($this->input->post('openDays') * 86400);		  
					
					if($this->input->post('categories'))
					{
						$categories = $this->input->post('categories');
						//pr($categories);
						
						//Work With Project Categories
						$project_categoriesNameArray 	           = $this->skills_model->convertCategoryIdsToName($categories);
						$project_categoriesNameString              = implode(',',$project_categoriesNameArray);
						$insertData['project_categories']          = $project_categoriesNameString;
					}
					
					if($insertData)            
					  {
						$this->skills_model->draftProject($insertData);
					  } 
				  }
			  else
			   {  	
				$draft = $draft->row();
				$updateDraft              		  	= array();	
				$updateDraft['project_name']  	  	= $this->input->post('projectName');
				
				$updateDraft['description']      	= $this->input->post('description');
				//Set budget min value
				if($this->input->post('budget_min',TRUE))
					$updateDraft['budget_min']    	= $this->input->post('budget_min');
				else
					$updateDraft['budget_min']    	= '';	
				//Set budget max value				
				if($this->input->post('budget_max',TRUE))
					$updateDraft['budget_max']    	= $this->input->post('budget_max');
				else
					$updateDraft['budget_max']    	= '';	
				$updateDraft['is_feature']       	= $this->input->post('is_feature');
				$updateDraft['is_urgent']         	= $this->input->post('is_urgent');
				$updateDraft['is_hide_bids']        = $this->input->post('is_hide_bids');
				if($this->input->post('is_private'))
				{
				   $updateDraft['is_private']   = $this->input->post('is_private');
				   $updateDraft['private_users']=$this->input->post('private_list');
				}   
				$updateDraft['creator_id']       	= $this->loggedInUser->id;
				$updateDraft['created']       		= get_est_time();
				$updateDraft['enddate']       		= get_est_time() + ($this->input->post('openDays') * 86400);		  
				
				if($this->input->post('categories'))
				{
					$categories = $this->input->post('categories');
					
					//Work With Project Categories
					$project_categoriesNameArray 	           = $this->skills_model->convertCategoryIdsToName($categories);
					$project_categoriesNameString              = implode(',',$project_categoriesNameArray);
					$updateDraft['project_categories']         = $project_categoriesNameString;
				}
				if($updateDraft)            
				  {
					$condition = array('draftprojects.id'=>$draft->id);
					$this->skills_model->updateDraftProject($updateDraft,$condition);
					 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your project has been saved as Draft')));

				  }  
			   }
			   $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your project has been saved as Draft')));
		   redirect('info/index/success');
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
		
		if($this->uri->segment(3,0))
		   $project_id    =    $this->uri->segment(3,0);
		else   
		   $project_id    =    $this->input->post('projectid'); 
		//Get the project details for post similar projects
		$conditions   = array('projects.id'=>$project_id,'projects.creator_id'=>$this->loggedInUser->id);
		$postSimilar    = $this->skills_model->getUsersproject($conditions);
		$this->outputData['postSimilar']   =  $postSimilar;  
		
		//Get Form Data	
		if($this->input->post('createProject') or $this->input->post('preview_project'))
		{	
		 	
			//Set rules
			// Puhal Changes Start Following validations are to verify the post of Email address and Phone number (Sep 17 Issue 1)	-------------------------------------------		
		
$this->form_validation->set_rules('projectName','lang:project_name_validation',							'required|trim|min_length[5]|xss_clean|alpha_space|callback__emailpresent_projectname_check|callback__phonenumber_projectname_check');

// Puhal Changes End Following validations are to verify the post of Email address and Phone number (Sep 17 Issue 1)	-------------------------------------------		
			$this->form_validation->set_rules('description','lang:description_validation','required|min_length[25]|trim|xss_clean|callback__emailpresent_check|callback__phonenumber_check');
			$this->form_validation->set_rules('attachment','lang:attachment_validation','callback_attachment_check');
			$this->form_validation->set_rules('categories[]','lang:categories_validation','required');
			$this->form_validation->set_rules('is_feature','lang:is_feature_validation','trim');
			$this->form_validation->set_rules('is_private','lang:is_private_validation','trim');
			$this->form_validation->set_rules('is_urgent','lang:is_urgent_validation','trim');
			$this->form_validation->set_rules('is_hide_bids','lang:is_hide_bids_validation','trim');
			$this->form_validation->set_rules('budget_min','lang:budget_min_validation','trim|integer|is_natural|abs|xss_clean');
			$this->form_validation->set_rules('budget_max','lang:budget_max_validation','trim|integer|is_natural|abs|xss_clean|callback__maxvalcheck');   
			$this->form_validation->set_rules('categories[]','lang:categories_validation','trim|integer|is_natural|abs|xss_clean|callback__maxvalcheckcat');   
			if($this->input->post('is_private'))
			{		
					$this->form_validation->set_rules('private_list','lang:private_list','required'); 
			}
			
			
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
				 if($this->input->post('update') != '0')
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
				  //Puhal Changes Start for downloading the Project attachment file (Sep 20 Isssue 17)
			
				if(isset($this->data['file']))
				{	 $insertData['attachment_url']=$this->data['file']['file_name'];  $insertData['attachment_name']=$this->data['file']['orig_name']; }	
				  
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
				  $insertData['flag']=0;
				  if($this->input->post('is_private'))
					{
					   $insertData['is_private']    = $this->input->post('is_private');
					} 
				  $insertData['creator_id']       	= $this->loggedInUser->id;
				  $insertData['created']       		= get_est_time();
				  $insertData['enddate']       		= get_est_time() + ($this->input->post('openDays') * 86400);
				  $result                           = '0';
				  
				  //Project Preview
				  if($this->input->post('preview_project'))
		          {
					   $this->outputData['showPreview']			= true;
					   $result                                  = '1';
					   $outputData['project_status']  	= 'Pending';
					   $outputData['project_name']  	  	= $this->input->post('projectName');
					   //Update additional information for projects
					   if($this->input->post('update') == '0')
					      {
					   	   $outputData['description']    	= $this->input->post('description').'<br>';
						   $outputData['description']    	.= $this->input->post('add_description');
						  } 
					   else
					       $outputData['description']    	= $this->input->post('description');	   
						
					   $outputData['budget_min']    	  	= $this->input->post('budget_min');
					   $outputData['budget_max']       	= $this->input->post('budget_max');
					   $outputData['is_feature']       	= $this->input->post('is_feature');
					   $outputData['is_urgent']       	= $this->input->post('is_urgent');
					   $outputData['is_hide_bids']        = $this->input->post('is_hide_bids');
					   if($this->input->post('is_private'))
						{
						   $insertData['is_private']            = $this->input->post('is_private');
						} 
					   $outputData['creator_id']       	= $this->loggedInUser->id;
					   $outputData['created']       		= get_est_time();
					   $outputData['enddate']       		= get_est_time() + ($this->input->post('openDays') * 86400);
					  if($this->input->post('categories'))
					  {
						   $categories = $this->input->post('categories');
						  
						  //Work With Project Categories
						  $project_categoriesNameArray 	           = $this->skills_model->convertCategoryIdsToName($categories);
						  $project_categoriesNameString            = implode(',',$project_categoriesNameArray);
						  $outputData['project_categories']  = $project_categoriesNameString;
					  }
					  //Insert the preview project details
					 
					  $this->skills_model->previewProject($outputData);
		         }
				 
				 //Project Submit
				 //check the condition for view the preview about the project
				 if($result == '0' )
				 { 
				 	$this->loggedInUser					= $this->common_model->getLoggedInUser();
		         	$this->outputData['loggedInUser'] 	= $this->loggedInUser;
				 	$login_user=$this->loggedInUser; 
					$condition=array('subscriptionuser.username'=>$this->loggedInUser->id);
			    	$userlists= $this->certificate_model->getCertificateUser($condition);
					
					if($userlists->num_rows() > 0)
					{
				 		// get the validity
						 $validdate=$userlists->row();
						 $end_date=$validdate->valid; 
						 $created_date=$validdate->created;
						 $valid_date=date('d/m/Y',$created_date);
						
						 $next=$created_date+($end_date * 24 * 60 * 60);
						 $next_day= date('d/m/Y', $next) ."\n";
	
						 if(time()<=$next)
						 {
							$paymentSettings = $this->settings_model->getSiteSettings();
							$feature_project = $this->config->item('featured_project_amount_cm');
							$urgent_project  = $paymentSettings['URGENT_PROJECT_AMOUNT_CM'];
							$hide_project    = $paymentSettings['HIDE_PROJECT_AMOUNT_CM'];
							$private_project  = $paymentSettings['PRIVATE_PROJECT_AMOUNT_CM'];
							$this->outputData['feature_project']  = $feature_project;
							$this->outputData['urgent_project']   = $urgent_project;
							$this->outputData['hide_project']     = $hide_project;
							$this->outputData['private_project']   =$private_project; 
							$this->outputData['created']          = get_est_time();
							$this->outputData['enddate']          = get_est_time() + ($this->input->post('openDays') * 86400);
						}
				
						else
						{
							 //Get the values from settings table
							$paymentSettings = $this->settings_model->getSiteSettings();
							$feature_project = $this->config->item('featured_project_amount');
							$urgent_project  = $paymentSettings['URGENT_PROJECT_AMOUNT'];
							$hide_project    = $paymentSettings['HIDE_PROJECT_AMOUNT'];
							$private_project  = $paymentSettings['PRIVATE_PROJECT_AMOUNT'];
							$this->outputData['feature_project']  = $feature_project;
							$this->outputData['urgent_project']   = $urgent_project;
							$this->outputData['hide_project']     = $hide_project;
							$this->outputData['private_project']   =$private_project; 
							$this->outputData['created']          = get_est_time();
							$this->outputData['enddate']          = get_est_time() + ($this->input->post('openDays') * 86400);
						}
					}
					else{
						$paymentSettings = $this->settings_model->getSiteSettings();
						$feature_project = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
						$urgent_project  = $paymentSettings['URGENT_PROJECT_AMOUNT'];
						$hide_project    = $paymentSettings['HIDE_PROJECT_AMOUNT'];
						$private_project  = $paymentSettings['PRIVATE_PROJECT_AMOUNT'];
					}
					
					if($this->input->post('createProject'))
					  {
						
						 	//initial value set for check the featured , urgent, hide projects
						 	$settingAmount=0;
						 
						 	//check the values for featured, urgent, hide projects
							if($this->input->post('is_feature'))
							{
								$settingAmount=$settingAmount+$feature_project;
							}
						 	if($this->input->post('is_urgent'))
							{
								$settingAmount=$settingAmount+$urgent_project;
							}
						 	if($this->input->post('is_hide_bids'))
							{
								$settingAmount=$settingAmount+$hide_project;
							}
							 if($this->input->post('is_private'))
							{
								$settingAmount=$settingAmount+$private_project; 
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
							}
							
							if($this->input->post('is_hide_bids',TRUE) or $this->input->post('is_urgent',TRUE) or $this->input->post('is_feature',TRUE) or  $this->input->post('is_private',TRUE)) 
							{
								$withdrawvalue = $rowBalance->amount - ( $settingAmount + $paymentSettings['PAYMENT_SETTINGS'] );
								
								if($rowBalance->amount == 0)
								{
								   $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('your not having sufficient balance')));
								   redirect('info');
								}
								else if( $withdrawvalue < 0 )
								{
									$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('your not having sufficient balance')));
									redirect('info');
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
									$insertTransaction['description']='';
									if($this->input->post('is_feature'))
									{
										$insertTransaction['description'] = $this->lang->line('Project Fee for Featured Project');
									}
									if($this->input->post('is_urgent'))
									{
									   if(($insertTransaction['description'])!='')
									   {
									   $insertTransaction['description'] .=$this->lang->line('plus');
										}
									 $insertTransaction['description'] .= $this->lang->line('Project Fee for Urgent Project');
									}
									if($this->input->post('is_hide_bids'))
									{
										if(($insertTransaction['description'])!='')
										   {
										   $insertTransaction['description'] .=$this->lang->line('plus');
											}
										$insertTransaction['description'] .= $this->lang->line('Project Fee for hide bids Project');
									}
									if($this->input->post('is_private'))
									{
									    if(($insertTransaction['description'])!='')
									      {
									     $insertTransaction['description'] .=$this->lang->line('plus');
										 }
										$insertTransaction['description'].= $this->lang->line('Project Fee for Private Project');
									}
									
									$insertTransaction['description'].= $this->lang->line('Project Fee');
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
									  $this->load->model('transaction_model');
									  $this->transaction_model->addTransaction($insertTransaction);	
								}
							}
					 }
					 		
					//Get payment settings for check minimum balance from settings table
					  $this->outputData['paymentSettings']	     = $paymentSettings;	
					  $this->outputData['PAYMENT_SETTINGS']       = $paymentSettings['PAYMENT_SETTINGS'];
					  if($this->input->post('categories'))	
					  {
					  $categories = $this->input->post('categories');
					  
					  //Work With Project Categories
					  $project_categoriesNameArray 	   = $this->skills_model->convertCategoryIdsToName($categories);
					  $project_categoriesNameString    = implode(',',$project_categoriesNameArray);
					  $insertData['project_categories'] = $project_categoriesNameString;
					  }
					 
					  if($this->input->post('createProject'))
					  {
						    	
						    // insert the projects details into project table
						    $this->skills_model->createProject($insertData);
							$projectid=$this->db->insert_id();
							
							if($this->input->post('is_private'))
							{
																
								$private_users=$this->input->post('private_list',TRUE);
								
								if($private_users!='')
								{	
									$private_users_array=explode("\n",$private_users);
									$condition='`role_id`=2';
									foreach($private_users_array as $val)
									{
										$private_users_array1[]=" `user_name`='".$val."'";
									}
									$private_users_str1=implode(' OR ',$private_users_array1);
									$private_users_cond=$condition.' AND ('.$private_users_str1.')';
									//$sel_users=$this->user_model->getUsersfromusername($condition=array(),$private_users_array,NULL);
									$sel_users=$this->user_model->getUsersfromusername($private_users_cond);
									//pr($sel_users->result());
									if($sel_users->num_rows()>0)
									{
									  foreach($sel_users->result() as $users)
									  {
									  	$pusers[]=$users->id;
									  }
									  $pusers=array_unique($pusers);
									  $pusers1=implode(',',$pusers);
									  $data=array('private_users'=>$pusers1);
									  $condition=array('id'=>$projectid);
									  $table='projects';
									  
									  $this->common_model->updateTableData($table,NULL,$data,$condition);
									  //insert project_invitation table for private users
									  $insertprivate=array('project_id'=>$projectid,'sender_id'=>$this->loggedInUser->id,'invite_date'=>get_est_time(),'notification_status'=>'0');	
									  $invitetable='project_invitation';
									  foreach($pusers as $val)
									  {
									  	$insertprivate['receiver_id']=$val;
										$this->common_model->insertData($invitetable,$insertprivate);
									  }
									}	
								}	
								
							}							
							
							if($this->input->post('is_private'))	
							{
							   	//Send Mail
								$conditionProviderMail     = array('email_templates.type'=>'private_project_provider');
								$resultProvider            = $this->email_model->getEmailSettings($conditionProviderMail);
							    $resultProvider				= $resultProvider->row();
																
								$projectpage=site_url('project/view/'.$projectid);
										
								$splVars_provider = array("!site_name" => $this->config->item('site_title'),"!projectname" => $insertData['project_name'],"!creatorname" => $this->loggedInUser->user_name,"!profile" => $project_categoriesNameString, "!projectid" => $projectid,"!date"=>get_datetime(time()),"!projecturl"=>$projectpage,);
							
							   
							   //pr($sel_users->result());
							   //sending emailto all the providers
							   if($private_users!='')
								{
							   
							    if($sel_users->num_rows()>0)
								{
								  foreach($sel_users->result() as $users)
								  {
								  		$insertMessageData['project_id']  	  	=  $projectid;
				 						$insertMessageData['to_id']      		= $users->id;
				  						$insertMessageData['from_id']    	  	= $this->loggedInUser->id;
										$insertMessageData['message']       	= "Private Project Notification --> You are Invited for the private project<br/>Follow the link given below to view the project<br/>".site_url('project/view/'.$projectid);
				  						$insertMessageData['created']       	= get_est_time();	
										//pr($insertMessageData); exit;
										$this->messages_model->postMessage($insertMessageData);	
										
								  	 if($users->email!='')
									 {
									 	$toEmail_provider = $users->email;
										$fromEmail_provider = $this->config->item('site_admin_mail');
										
										
										$selusernames[]=$users->user_name;
										$splVars_provider['!username']=$users->user_name;
										$mailSubject_provider = strtr($resultProvider->mail_subject, $splVars_provider);
										$mailContent_provider = strtr($resultProvider->mail_body, $splVars_provider);	
										$this->email_model->sendHtmlMail($toEmail_provider,$fromEmail_provider,$mailSubject_provider,$mailContent_provider);
							
									 }
								}
							  }	
							  
							  }
						   }
						   if($this->input->post('is_private'))	
							{
								$conditionUserMail = array('email_templates.type'=>'privateproject_post');
							$result            = $this->email_model->getEmailSettings($conditionUserMail);
							$rowUserMailConent = $result->row();
							$splVars = array("!site_name" => $this->config->item('site_title'),"!projectname" => $insertData['project_name'],"!username" => $this->loggedInUser->user_name,"!profile" => $project_categoriesNameString, "!projectid" => $projectid,"!projectid" => $projectid,"!date"=>get_datetime(time()));
							if($private_users!='')
							{
							if($sel_users->num_rows()>0)
							{
								$selusernamesstr=implode(",",$selusernames);
						    }		
							else
							{
								$selusernamesstr='';
							}
							}
							else
							{
								$selusernamesstr='';
							}
							$splVars['!privateproviders']=$selusernamesstr;
							$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
							$mailContent = strtr($rowUserMailConent->mail_body, $splVars);	
							
							$toEmail = $this->loggedInUser->email;
							$fromEmail = $this->config->item('site_admin_mail');
							$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
							}
							else
							{
							//Send Mail
							$conditionUserMail = array('email_templates.type'=>'projectpost_notification');
							$result            = $this->email_model->getEmailSettings($conditionUserMail);
							$rowUserMailConent = $result->row();
							$splVars = array("!site_name" => $this->config->item('site_title'),"!projectname" => $insertData['project_name'],"!username" => $this->loggedInUser->user_name,"!profile" => $project_categoriesNameString, "!projectid" => $this->db->insert_id(),"!projectid" => $this->db->insert_id(),"!date"=>get_datetime(time()));
							$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
							$mailContent = strtr($rowUserMailConent->mail_body, $splVars);		

							$toEmail = $this->loggedInUser->email;
							$fromEmail = $this->config->item('site_admin_mail');
							$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
							}
							/* New version as of June 09 2009 */
							$tuser = $this->config->item('twitter_username');
							$tpass = $this->config->item('twitter_password');
							$twit_msg = "<".$this->loggedInUser->user_name."> ".$insertData['project_name']." : ".site_url('project/view/'.$this->db->insert_id());
						    $twit_content= $this->skills_model->tinyUrl(site_url('project/view/'.$this->db->insert_id()));
							$this->skills_model->sendTwitter($twit_content,$tuser,$tpass);
							
							//Send instant notification mail to providers
							$conditions = array('users.role_id' => '2','users.user_status' => '1','user_categories.user_categories !=' => '','users.project_notify' => 'Instantly');
			
							$users = $this->user_model->getUsersWithCategories($conditions);
							
							foreach($users->result() as $user)
							{
								$cate = explode(",",$user->user_categories);
	
								$inter = array_intersect($cate, $categories);
								
								//Check if categories are matched to send notification
								if(count($inter) > 0){
					
									$mailSubject = $this->config->item('site_title')." Project Notice";
									$mailContent = "The following project was recently added to ".$this->config->item('site_title')." and match your expertise:";
					
									$condition3 = array('projects.id' => $this->db->insert_id());
									$mpr = $this->skills_model->getProjects($condition3);
									$prj = $mpr->row();
									$mailContent .= $prj->project_name." (Posted by ".$prj->user_name.", ".get_datetime($prj->created).", Job type:".$prj->project_categories.")"." ".site_url('project/view/'.$prj->id);
					
									//Send mail
									$toEmail = $user->email;
									$fromEmail = $this->config->item('site_admin_mail');
									$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
								}
							}
							/* end of new vesrion */
							
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
		   $condition = array('projects_preview.id'=>$this->db->insert_id());
		   $preview   = $this->skills_model->getpreviewProjects($condition);
		   $this->outputData['preview'] = $preview;
		   //pr($preview);
		   
		 $this->load->view('project/createProject',$this->outputData);		
		 }