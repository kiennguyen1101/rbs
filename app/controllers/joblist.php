<?php 
/**
 * Reverse bidding system Buyer Class
 *
 * Joblist related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	JobList 
 * @author		Cogzidel Dev Team
 * @version		Version 1.6
 * @created		April 22  2010
 * @created By  Saradha.P 
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
class Joblist extends Controller {
 
	//Global variable  
    public $outputData;	
	public $outputjobData;	//Holds the output data for each view
	public $loggedInUser;   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Joblist()
	{
		parent::Controller();
		
		//Get Config Details From Db
		$this->config->db_config_fetch();
		
		//Manage site Status 
		if($this->config->item('site_status') == 1)
		redirect('offline');
		
		
		//Load the helper file reviews
		$this->load->helper('reviews');	
		
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		$this->load->model('skills_model');
		$this->load->model('user_model');
		$this->load->model('transaction_model');
		$this->load->model('settings_model');
		$this->load->model('file_model');
		$this->load->model('email_model');
		$this->load->model('messages_model');	 
		$this->load->model('certificate_model');
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		$this->lang->load('enduser/project', $this->config->item('language_code'));
		$this->lang->load('enduser/createProject', $this->config->item('language_code'));
		
		
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
		$this->outputData['project_period']    =    $this->config->item('project_period');
		
		//Get draft Projects
		if(isset($this->loggedInUser->id))
		{
			$condition = array('draftprojects.creator_id' => $this->loggedInUser->id);
			$this->outputData['draftProjects']	= $this->skills_model->getDraft($condition);
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
		
		if($this->loggedInUser)
		{
			$condition=array('subscriptionuser.username'=>$this->loggedInUser->id);
			$userlists= $this->certificate_model->getCertificateUser($condition);
		
			if($userlists->num_rows()>0)
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
					$this->outputData['feature_project']   = $paymentSettings['FEATURED_PROJECT_AMOUNT_CM'];
					$this->outputData['urgent_project']    = $paymentSettings['URGENT_PROJECT_AMOUNT_CM'];
					$this->outputData['hide_project']      = $paymentSettings['HIDE_PROJECT_AMOUNT_CM'];
					$this->outputData['private_project']   = $paymentSettings['PRIVATE_PROJECT_AMOUNT_CM'];
				}
			
				else
				{
					//Initital payment settings for projects
					$paymentSettings = $this->settings_model->getSiteSettings();
					$this->outputData['feature_project']   = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
					$this->outputData['urgent_project']    = $paymentSettings['URGENT_PROJECT_AMOUNT'];
					$this->outputData['hide_project']      = $paymentSettings['HIDE_PROJECT_AMOUNT'];
					$this->outputData['private_project']   = $paymentSettings['PRIVATE_PROJECT_AMOUNT'];
				}
			}
			else
			{
				$paymentSettings = $this->settings_model->getSiteSettings();
				$this->outputData['feature_project']   = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
				$this->outputData['urgent_project']    = $paymentSettings['URGENT_PROJECT_AMOUNT'];
				$this->outputData['hide_project']      = $paymentSettings['HIDE_PROJECT_AMOUNT'];
				$this->outputData['private_project']   = $paymentSettings['PRIVATE_PROJECT_AMOUNT'];
			}
		}
	} //Constructor End 
	// --------------------------------------------------------------------
	
	
		/**
	 * Post new job list by buyer
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function create()
	{	
	
		$this->outputData['current_page'] = 'post_project';
		

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
		 
		//Save the Project
		if($this->input->post('postProject')=="Project") 
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
					$insertData['flag']=0;		  
					
					if($this->input->post('categories'))
					{
						$categories = $this->input->post('categories');
						
						
						//Work With Project Categories
						$project_categoriesNameArray 	           = $this->skills_model->convertCategoryIdsToName($categories);
						$project_categoriesNameString              = implode(',',$project_categoriesNameArray);
						$insertData['project_categories']          = $project_categoriesNameString;
					}
					
					if($insertData)            
					  {
						$this->skills_model->draftProject($insertData);
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your project has been saved as Draft')));
		   redirect('info/index/success');	
					  }
					  else
					  {
					   $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You cannot save this project as Draft')));
		           redirect('info');	
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
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your project has been updated as Draft')));
		   redirect('info/index/success');	
				  } 
				  else
				  {
				   $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You cannot update this project as Draft')));
		           redirect('info');	
				   } 
			    }
			  }
		 }
			   // Save the Joblist in the draft
			   else
			   {
			    if($this->input->post('projectName'))
			    {
				$draft_name = $this->input->post('projectName');
				$condition  =  array('draftprojects.project_name'=>$draft_name);
				$draft = $this->skills_model->getDraft($condition);
				$paymentSettings = $this->settings_model->getSiteSettings(); 
				$joblistvalidity  = $paymentSettings['JOBLIST_VALIDITY_LIMIT'];
				  if($draft->num_rows() <= 0)
				  {
					$insertJobData              		  	= array();	
				    $insertJobData['project_name']  	  	= $this->input->post('projectName');
					$insertJobData['description']      	= $this->input->post('description');
					$insertJobData['creator_id']       	= $this->loggedInUser->id;
					$insertJobData['created']       		= get_est_time();
					$insertJobData['enddate']       		=get_est_time() + ($joblistvalidity * 86400);
					$insertJobData['contact']  	  	= $this->input->post('contactinfo');
					$insertJobData['salary']  	  	= $this->input->post('salary');
					$insertJobData['salarytype']  	  	= $this->input->post('salarytype');
					
					$insertJobData['flag']=1;	
					
					
					if($this->input->post('categories'))
					{
						$categories = $this->input->post('categories');
						
						
						//Work With Project Categories
						$project_categoriesNameArray 	           = $this->skills_model->convertCategoryIdsToName($categories);
						$project_categoriesNameString              = implode(',',$project_categoriesNameArray);
						$insertJobData['project_categories']          = $project_categoriesNameString;
					}
					
					if($insertJobData)            
					  {
						$this->skills_model->draftProject($insertJobData);
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Job has been saved as Draft')));
		   redirect('info/index/success');	
					  }
					  else
					  {
					   $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You cannot save this Job as Draft')));
		           redirect('info');	
					  } 
				  }
			  else
			   { 
				$draft = $draft->row();
				$updatejobDraft              		  	= array();	
				$updatejobDraft['project_name']  	  	= $this->input->post('projectName');
				
				$updatejobDraft['description']      	= $this->input->post('description');
				//Set budget min value
				
				$updatejobDraft['creator_id']       	= $this->loggedInUser->id;
				$updatejobDraft['created']       		= get_est_time();
				//$updatejobDraft['enddate']       		= get_est_time() + ($this->input->post('openDays') * 86400);
				$updatejobDraft['contact']  	  	= $this->input->post('contactinfo');
				$updatejobDraft['salary']  	  	= $this->input->post('salary');
			    $updatejobDraft['salarytype']  	  	= $this->input->post('salarytype');		  
				
				if($this->input->post('categories'))
				{
					$categories = $this->input->post('categories');
					
					//Work With Project Categories
					$project_categoriesNameArray 	           = $this->skills_model->convertCategoryIdsToName($categories);
					$project_categoriesNameString              = implode(',',$project_categoriesNameArray);
					$updatejobDraft['project_categories']         = $project_categoriesNameString;
				}
				if($updatejobDraft)            
				  {
					$condition = array('draftprojects.id'=>$draft->id);
					$this->skills_model->updateDraftProject($updatejobDraft,$condition);
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Job has been updated as Draft')));
		   redirect('info/index/success');	
				  } 
				  else
				  {
				   $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You cannot update this Job as Draft')));
		           redirect('info');	
				   } 
			   }
			 } 	 
		  }
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
		  $type=$this->input->post('postProject');
		 	
			//Set rules
			// Puhal Changes Start Following validations are to verify the post of Email address and Phone number (Sep 17 Issue 1)	-------------------------------------------		
		
$this->form_validation->set_rules('projectName','lang:project_name_validation',							'required|trim|min_length[5]|xss_clean|alpha_space|callback__emailpresent_projectname_check|callback__phonenumber_projectname_check');

// Puhal Changes End Following validations are to verify the post of Email address and Phone number (Sep 17 Issue 1)	-------------------------------------------	
            if($type=="Project")	
			{
			$this->form_validation->set_rules('description','lang:description_validation','required|min_length[25]|trim|xss_clean|callback__emailpresent_check|callback__phonenumber_check');
			}
			else
			{
			$this->form_validation->set_rules('description','lang:description_validation','required|min_length[25]|trim|xss_clean');
			$this->form_validation->set_rules('contactinfo','lang:contactinfo_validation','required');
			}
			$this->form_validation->set_rules('attachment','lang:attachment_validation','callback_attachment_check');
			$this->form_validation->set_rules('categories[]','lang:categories_validation','required');
			$this->form_validation->set_rules('is_feature','lang:is_feature_validation','trim');
			$this->form_validation->set_rules('is_private','lang:is_private_validation','trim');
			$this->form_validation->set_rules('is_urgent','lang:is_urgent_validation','trim');
			$this->form_validation->set_rules('is_hide_bids','lang:is_hide_bids_validation','trim');
			$this->form_validation->set_rules('budget_min','lang:budget_min_validation','trim|integer|is_natural|abs|xss_clean');
			$this->form_validation->set_rules('budget_max','lang:budget_max_validation','trim|integer|is_natural|abs|xss_clean');  
		    
			$this->form_validation->set_rules('salary','lang:salary_validation','|trim|integer|xss_clean');
			$this->form_validation->set_rules('salarytype','lang:salarytype_validation','|trim|xss_clean');
			
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
						   redirect('joblist/postProject/'.$project);
						}
					}
					
			     //insert project data	
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
				  
				  //insert joblist data
				  $insertJobData              		  	= array();	
			      $insertJobData['project_name']  	  	= $this->input->post('projectName');
				  $insertJobData['description']      	= $this->input->post('description');
				  //Puhal Changes Start for downloading the Project attachment file (Sep 20 Isssue 17)
			
				if(isset($this->data['file']))
				{	 $insertJobData['attachment_url']=$this->data['file']['file_name'];  $insertJobData['attachment_name']=$this->data['file']['orig_name']; }	
				  
				  if($this->input->post('update') == '0')
					{
					  $insertJobData['description']    	= $this->input->post('description').'<br/>';
					  $insertJobData['description']    	.= $this->input->post('add_description');
					} 
				  else
				     $insertJobData['description']    	= $this->input->post('description');
					 if($this->input->post('categories'))
					{
						$categories = $this->input->post('categories');
						//pr($categories);
						
						//Work With Project Categories
						$project_categoriesNameArray 	           = $this->skills_model->convertCategoryIdsToName($categories);
						$project_categoriesNameString              = implode(',',$project_categoriesNameArray);
						$insertJobData['project_categories']          = $project_categoriesNameString;
					}	
                  $paymentSettings = $this->settings_model->getSiteSettings(); 
				  $joblistvalidity  = $paymentSettings['JOBLIST_VALIDITY_LIMIT'];
				  $insertJobData['flag']=1;
				  $insertJobData['creator_id']       	= $this->loggedInUser->id;
				  $insertJobData['created']       		= get_est_time();
				  $insertJobData['enddate']       		=get_est_time() + ($joblistvalidity * 86400);
				  $insertJobData['contact']=$this->input->post('contactinfo');
				  $insertJobData['salary']=$this->input->post('salary');
				  $insertJobData['salarytype']=$this->input->post('salarytype');
				  $insertJobData['flag']=1;
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
					   $outputData['flag']='0' ;
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
					  //joblist data
					   $this->outputjobData['showPreview']			= true;
					   $result                                  = '1';
					   $outputjobData['project_status']  	= 'Pending';
					   $outputjobData['project_name']  	  	= $this->input->post('projectName');
					   $paymentSettings = $this->settings_model->getSiteSettings(); 
				$joblistvalidity  = $paymentSettings['JOBLIST_VALIDITY_LIMIT'];
					   //Update additional information for Joblist
					   if($this->input->post('update') == '0')
					      {
					   	   $outputjobData['description']    	= $this->input->post('description').'<br>';
						   $outputjobData['description']    	.= $this->input->post('add_description');
						  } 
					   else
					   $outputjobData['description']    	= $this->input->post('description');	   
					   $outputjobData['creator_id']       	= $this->loggedInUser->id;
					   $outputjobData['created']       		= get_est_time();
					   $outputjobData['enddate']       		= get_est_time() + ($joblistvalidity * 86400);
					   $outputjobData['contact']       	= $this->input->post('contactinfo');
					   $outputjobData['salary']       	= $this->input->post('salary');
					   $outputjobData['salarytype']       	= $this->input->post('salarytype');
					   $outputjobData['flag']='1' ;
					   
					  if($this->input->post('categories'))
					  {
					   $categories = $this->input->post('categories');
					  
					  //Work With Project Categories
						  $project_categoriesNameArray 	           = $this->skills_model->convertCategoryIdsToName($categories);
						  $project_categoriesNameString            = implode(',',$project_categoriesNameArray);
						  $outputjobData['project_categories']  = $project_categoriesNameString;
					  }
					  
					   if($this->input->post('postProject')=="Joblist")
							{
							// insert the jobs details into project table
							
						   $this->skills_model->previewProject($outputjobData);
							}
							else
							{
							// insert the projects details into project table
						    $this->skills_model->previewProject($outputData);
							}	
					 
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
								 $this->outputData['private_project']   = 
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
								if($this->input->post('is_feature'))
									{
										$insertTransaction['description'] = $this->lang->line('Project Fee for Feature Project');
									}
									elseif($this->input->post('is_urgent'))
									{
										$insertTransaction['description'] = $this->lang->line('Project Fee for Urgent Project');
									}
									elseif($this->input->post('is_hide_bids'))
									{
										$insertTransaction['description'] = $this->lang->line('Project Fee for hide bids Project');
									}
									elseif($this->input->post('is_private'))
									{
										$insertTransaction['description'] = $this->lang->line('Project Fee for Private Project');
									}
				
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
						
						    if($this->input->post('postProject')=="Joblist")
							{
							//payment Checking
							$joblistsettings=0;
							$paymentSettings = $this->settings_model->getSiteSettings();
							$joblistamount  = $paymentSettings['JOBLISTING_PROJECT_AMOUNT']; 
							$joblistsettings=$joblistsettings+$joblistamount;
							
							 $condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);
						     $results = $this->transaction_model->getBalance($condition_balance);
							 $results->num_rows();
								 if($results->num_rows()>0)
							       {
								//get balance detail
								$rowBalance = $results->row();
								
								
								$this->outputData['userAvailableBalance'] = $rowBalance->amount;
							}	
							 
							 if($rowBalance->amount <=$joblistamount )
							{
							
							   $this->skills_model->draftProject($insertJobData); 
							  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('your not having sufficient balance')));
							   redirect('joblist/create');
							}
							  else
							  {
							  //Check User Balance
								 //Update Amount	
								$updateKey 			  = array('user_balance.user_id'=>$this->loggedInUser->id);	
								$updateData 		  = array();
								$updateData['amount'] = $rowBalance->amount   -   $joblistsettings;
								$results 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
								
									 //Insert transaction for post projects
								$insertTransaction = array(); 
								$insertTransaction['creator_id']  = $this->loggedInUser->id;
								$insertTransaction['type'] 		 = $this->lang->line('Project Fee');
								$insertTransaction['amount'] 	 = $joblistsettings;
								$insertTransaction['transaction_time'] 	 	 = get_est_time();
								$insertTransaction['status'] 	 = 'Completed'; //Can Be success,failed,pending
								$insertTransaction['description'] = $this->lang->line('Job Fee');
				
									if($this->loggedInUser->role_id == '1')
									  {
										$insertTransaction['buyer_id']   = $this->loggedInUser->id;
										$insertTransaction['user_type']  = $this->lang->line('Job Fee');
									  }
									if($this->loggedInUser->role_id == '2')
									  {
										$insertTransaction['provider_id'] = $this->loggedInUser->id;
										$insertTransaction['user_type']   = $this->lang->line('Job Fee');
									  }
								  $this->load->model('transaction_model');
								  $this->transaction_model->addTransaction($insertTransaction);	
								  // insert the jobs details into project table
								  $this->skills_model->createProject($insertJobData);
								   $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Job has been Posted Successfully')));
						   redirect('joblist/viewAlljoblists/flag');
							  }
							}
							else
							{
							// insert the projects details into project table
						    $this->skills_model->createProject($insertData);
							 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Project has been Posted Successfully')));
						  
							}	
						    
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
						 // redirect('buyer/viewMyProjects');
						}  
				  redirect('info/index/success');
				}
				
			}//Form Validation End
		}//If - Form Submission End
	
		//Get Groups
		$this->outputData['groupsWithCategories']	=	$this->skills_model->getGroupsWithCategory();   
	    if($result == '0' )
		  {
	        $this->load->view('joblist/createjoblist',$this->outputData);
	      }
	   else
		 {
		
		   $condition = array('projects_preview.id'=>$this->db->insert_id());
		   $preview   = $this->skills_model->getpreviewProjects($condition);
		   $this->outputData['preview'] = $preview;
		   if($this->input->post('postProject')=="Joblist")
		   {
		   $this->load->view('joblist/createjoblist',$this->outputData);	
		   }
		   else
		   {
		   $this->load->view('joblist/createjoblist',$this->outputData);	
		   }
		   //$this->load->view('joblist/createjoblist',$this->outputData);		
		 }
	} //Function create End
	// --------------------------------------------------------------------

/**
	 * view project by buyer or programmer
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 	
	
	
	function view()
	{	
	
		//Load Language
		$this->lang->load('enduser/viewProject', $this->config->item('language_code'));
		$this->load->helper('users_helper');
		
		//Check Whether User Logged In Or Not
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be login access to this page')));
		    redirect('info');
		  }
		
		//Get Project Id
		if($this->uri->segment(3))
		   {
		  $project_id	 = $this->uri->segment(3,'0');
		   }
		else
		   {
		     $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
			 redirect('info');
		   }
		if(!is_numeric($this->uri->segment(3)))  
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
			 redirect('info');
		  } 
		if(isset($project_id) and isset($this->loggedInUser->id))
		  {
			$updateKey                         = array('project_invitation.project_id'=>$project_id,'project_invitation.receiver_id'=>$this->loggedInUser->id);
		    $updateData['notification_status'] = '1';
		    $this->user_model->updateProgrammerInvitation($updateKey,$updateData);
		  }
		  
		$conditions = array('projects.id'=>$project_id);
		$this->outputData['projects']  =  $this->skills_model->getProjects($conditions);
		
		$this->outputData['projectRow'] = $this->outputData['projects']->row();
		//pr($this->outputData['projectRow']);exit;
		
		if($this->outputData['projects']->num_rows() == 0)
		{
			//Notification message
			 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('project_not_available')));
			 redirect('info');
		}

		$this->outputData['creatorInfo'] = getUserInfo($this->outputData['projectRow']->creator_id);
		
		$projects = $this->outputData['projects']->row();
		
		if(isset($this->loggedInUser->id) and $projects->creator_id==$this->loggedInUser->id)
			$conditions = array('bids.project_id'=>$project_id);	 
		else
			$conditions = array('bids.project_id'=>$project_id,'projects.is_hide_bids' => 0);

		$this->outputData['bids']  =  $this->skills_model->getBids($conditions);
		//pr($this->outputData['bids']->result());exit;

		$this->outputData['projectId'] = $project_id;
		
		if(isset($this->loggedInUser->id))
		 {
			$conditions = array('bids.user_id' => $this->loggedInUser->id,'bids.project_id'=>$project_id);
			$totbid  =  $this->skills_model->getBids($conditions);
			$this->outputData['tot'] = $totbid->row();
		 }
		else
			$this->outputData['tot'] = array();
		
		//Get Total Messages
		$this->load->model('messages_model');
		$message_conditions = array('messages.project_id'=>$project_id);
		$this->outputData['totalMessages']	    =  $this->messages_model->getTotalMessages($message_conditions);	
	    $this->load->view('project/viewProject',$this->outputData);
	} //Function view End
	
	
	/**
	 * view all the jobs by buyer or programmer 
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	//View Joblist
	function viewAlljoblists()
	{
		//Load Language
		$this->lang->load('enduser/featuredProjects', $this->config->item('language_code'));
		$this->lang->load('enduser/editProfile', $this->config->item('language_code'));
		
		 
	    if($this->input->post('customizeDisplay'))
		 {
			//Get Customize data fields
			$this->session->set_userdata('show_cat',$this->input->post('show_cat',true));
			$this->session->set_userdata('show_budget',$this->input->post('show_budget',true));
			$this->session->set_userdata('show_bids',$this->input->post('show_bids',true));
			$this->session->set_userdata('show_avgbid',$this->input->post('show_avgbid',true));
			$this->session->set_userdata('show_status',$this->input->post('show_status',true));
			$this->session->set_userdata('show_date',$this->input->post('show_date',true));
			$this->session->set_userdata('show_closedate',$this->input->post('show_closedate',true));
			$this->session->set_userdata('show_desc',$this->input->post('show_desc',true));
			$this->session->set_userdata('show_num',$this->input->post('show_num',true));
			
		}
		else{
			$this->session->set_userdata('show_cat','1');
			$this->session->set_userdata('show_budget','1');
			$this->session->set_userdata('show_bids','1');
			$this->session->set_userdata('show_num','5');
		}
		//pr($this->session->userdata);
	  $type = $this->uri->segment(3,'0');
		
		if($type == 'flag')
			$this->outputData['pName'] = 'Job Listing';
			$page = $this->uri->segment(4,'0');
			//Get Sorting order
			$field = $this->uri->segment(5,'0');
			$order = $this->uri->segment(6,'0');
			$this->outputData['order']	=  $order;
			$this->outputData['field']	=  $field;
			$this->outputData['type']	=  $type;
			$this->outputData['page']	=  $page;
			
		if(isset($page)===false or empty($page))
		  {
			$page = 1;
		  }
		
		$page_rows = $this->session->userdata('show_num');
		$max = array($page_rows,($page - 1) * $page_rows);

	   //Check list only Joblist project
	   $project_falg=1;
	   $projectstatus=0;
	    if($type == 'flag')
		{	
		$order=array('projects.id','desc');
		$joblist_conditions =array('projects.flag'=>'1','projects.project_status'=>'0');
		$joblist = $this->skills_model->getProjects($joblist_conditions,NULL,NULL,$max,$order);
		$jobs = $this->skills_model->getProjects($joblist_conditions);
		
		}
		else
		{
		$order=array('projects.id','desc');
		$joblist_conditions =array('projects.flag'=>'1','projects.project_status'=>'0');
		$joblist = $this->skills_model->getProjects($joblist_conditions,NULL,NULL,$max);
		$jobs = $this->skills_model->getProjects($joblist_conditions);
		 $this->outputData['pName'] = 'Job Listing';
		}
		//$projects = $this->admin_model->getProjects($joblist_conditions);
		
		$this->outputData['joblistprojects'] =$joblist;
		$this->load->library('pagination');
		$config['base_url'] 	= site_url('joblist/viewAlljoblists/'.$type);
		$config['total_rows'] 	= $jobs->num_rows();		
		$config['per_page'] = $page_rows; 
		$config['cur_page'] = $page;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links(false,'project');
		
		$this->load->view('joblist/viewJoblist',$this->outputData);
	}//Function viewAlljoblists End
	// --------------------------------------------------------------------
	
/**
	 * preview the job by buyer
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 	
	function previewjoblistProject()
	{	
		//Load Language
		$this->lang->load('enduser/viewProject', $this->config->item('language_code'));
		
		//Get Project Id
		$project_id	 = $this->uri->segment(3,'0');
		$conditions = array('projects_preview.id'=>$project_id);
		$this->outputData['projects']  =  $this->skills_model->getpreviewProjects($conditions);
		
	    $this->load->view('joblist/previewjoblist',$this->outputData);
	} //Function previewjoblistProject End
	// --------------------------------------------------------------------

	
/**
	 * Post the similar project by buyer
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 	
	function postProject()
	{
	 //language file
		$this->lang->load('enduser/review', $this->config->item('language_code'));
		
		//Check for Login details.
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be login access to this page')));
		    redirect('info');
		  }
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
		$this->outputData['postSimilar']   =  $postSimilar;
		
		//Laod the categories into the view page
		$this->outputData['groupsWithCategories']	=	$this->skills_model->getGroupsWithCategory();
		$this->load->view('joblist/postjoblist',$this->outputData);
	}//Function postProject End
	// --------------------------------------------------------------------
	
	
/**
	 * view the draft project by buyer
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 	
	function draftView()
	{	
		
	    $projectid	 = $this->input->post('projectid1');
		//Load Language
		$this->lang->load('enduser/viewProject', $this->config->item('language_code'));
		$this->outputData['groupsWithCategories']	=	$this->skills_model->getGroupsWithCategory();
		
		//Get Project Id
		$project_id	 = $this->input->post('draftId');
		$this->outputData['draftProjectsid'] = $project_id;
		$conditions = array('draftprojects.id'=>$project_id);
		$this->outputData['projects']  =  $this->skills_model->getDraft($conditions);
		if($this->input->post('draftId') == 'clear')
		 {
		 	redirect('joblist/deleteDraft/'.$projectid);
		 }
		if($this->input->post('draftId') == 'savedraft')
		 {
		 	redirect('joblist/create');
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
	    $this->load->view('joblist/draftjoblist',$this->outputData);
	} //Function draftview End
	//----------------

/**
	 * discard draft Job and project by buyer
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 	
	
	function deleteDraft()
	{	
		//echo $this->input->post('projectid');
		if($this->uri->segment(3))
		{
			$condition = array('draftprojects.id'=>$this->uri->segment(3));
			$this->skills_model->deletedraftprojects($condition);
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Draft Project Deleted Successfully')));
			redirect('joblist/create');
		}	 
	}//Function deleteDraft End
	
	
/**
	 * post the similar job by buyer
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 	
	function postJoblist()
	{
	 //language file
		$this->lang->load('enduser/review', $this->config->item('language_code'));
		
		//Check for Login details.
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be login access to this page')));
		    redirect('info');
		  }
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
		$this->outputData['postSimilar']   =  $postSimilar;
		
		//Laod the categories into the view page
		$this->outputData['groupsWithCategories']	=	$this->skills_model->getGroupsWithCategory();
		$this->load->view('joblist/postjoblist',$this->outputData);
	}//Function postJoblist End
	// --------------------------------------------------------------------
	
	/**
	 * manage Job from Buyer who post project
	 *
	 * @access	public for buyer
	 * @param	project id 
	 * @return	contents
	 */ 
	function manageJoblist()
	{
		
		//Load Language
		$this->lang->load('enduser/createProject', $this->config->item('language_code'));
		$this->lang->load('enduser/project', $this->config->item('language_code'));
		$this->lang->load('enduser/review', $this->config->item('language_code'));
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Check for Login details.
		if(!isset($this->loggedInUser->id))
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be login access to this page')));
			redirect('info');
		}
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->loggedInUser->role_id)
		{
			if($this->loggedInUser->role_id == '2')
			{
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged in as a buyer to post projects')));
				redirect('info');
			}
		}
        //Get Groups
		$this->outputData['groupsWithCategories']	=	$this->skills_model->getGroupsWithCategory();

		if($this->uri->segment(3,0))
	   		$project_id    =    $this->uri->segment(3,0);
		else   
	   		$project_id    =    $this->input->post('projectid'); 
	   $update    =    $this->input->post('update'); 
	  
		//Get the project details for post similar projects
		$conditions   = array('projects.id'=>$project_id,'projects.creator_id'=>$this->loggedInUser->id);
		//pr($conditions);
		$postSimilar    = $this->skills_model->getUsersproject($conditions);
		$this->outputData['postSimilar']   =  $postSimilar;  
		$res = $postSimilar->num_rows();
			
		if($res <= 0)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Your are not allow to manage this project')));
			redirect('project/view/'.$project_id);
		}
		$this->form_validation->set_rules('attachment','lang:attachment_validation','callback_attachment_check');
		$this->form_validation->set_rules('categories[]','lang:categories_validation','trim|integer|is_natural|abs|xss_clean|callback__maxvalcheckcat');   

		
		if($this->form_validation->run())
		{
			$res = $postSimilar->num_rows();
			//pr($postSimilar->result());
				if($res <= 0)
				{
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Your are not allow to manage this project')));
					redirect('project/view/'.$project_id);
				}
			
			//Create job before it for update the projects datas for manage option	   
			
				if($this->input->post('update') == '0')
				{
					if($this->input->post('projectid'))
					{
						$insertData              		  	= array();	
						$insertData['project_name']  	  	= $this->input->post('projectName');
						$insertData['description']      	= $this->input->post('description');			 
						if(isset($this->data['file']))
						{	 
							$insertData['attachment_url']=$this->data['file']['file_name'];  $insertData['attachment_name']=$this->data['file']['orig_name']; 
					  	}
				    	if($this->input->post('categories'))	
					  	{
					  		$categories = $this->input->post('categories');
							//Work With Project Categories
							$project_categoriesNameArray 	   = $this->skills_model->convertCategoryIdsToName($categories);
							$project_categoriesNameString    = implode(',',$project_categoriesNameArray);
							$insertData['project_categories'] = $project_categoriesNameString;
					  	}	
							
						if($this->input->post('update') == '0')
						{
							$insertData['description']    	= $this->input->post('description').'<br/>';
							$insertData['description'].= $this->input->post('add_description');
						} 
						else
							$insertData['description']    	= $this->input->post('description');	
							
						$paymentSettings = $this->settings_model->getSiteSettings(); 
						$joblistvalidity  = $paymentSettings['JOBLIST_VALIDITY_LIMIT'];
						$insertData['creator_id']       	= $this->loggedInUser->id;
						$insertData['created']       		= get_est_time();
						$insertData['enddate']       		= get_est_time() + ($joblistvalidity * 86400);
						$result                           = '0';
								
						if($this->input->post('categories'))
						{
							$categories = $this->input->post('categories');
							
							//Work With Project Categories
							$project_categoriesNameArray 	           = $this->skills_model->convertCategoryIdsToName($categories);
							$project_categoriesNameString              = implode(',',$project_categoriesNameArray);
							$insertData['project_categories']          = $project_categoriesNameString;
						}
								
						//Update the data
						$project = $this->input->post('projectid');
						$condition 		 = array('projects.id'=>$project);
						
						
						$this->skills_model->manageProjects($insertData,$condition);
					
						//Notification message
						//Load Model For Mail
						$this->load->model('email_model');
								
						//Send Mail
						$conditionUserMail = array('email_templates.type'=>'projectpost_notification');
						$result            = $this->email_model->getEmailSettings($conditionUserMail);
						$rowUserMailConent = $result->row();
						$splVars = array("!site_name" => $this->config->item('site_title'), "!username" => $this->loggedInUser->user_name,"!projectid" => $this->db->insert_id(),"!date"=>get_datetime(time()));
						$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
						$mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
						$toEmail = $this->loggedInUser->email;
						$fromEmail = $this->config->item('site_admin_mail');
						
						$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
						
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Job has been Updated Successfully')));
						redirect('joblist/viewAlljoblists/flag');
					}
				}
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Job has been Updated Successfully')));
			redirect('joblist/viewAlljoblists/flag');
		}
		$this->load->view('joblist/managejoblist',$this->outputData);
			
	}//Function acceptProject End
	// --------------------------------------------------------------------
	
	
	// For Description field (Check for Phone number) 
	function _phonenumber_check()
	{
		$description=$_POST['description'];
		//$reg = '/(\d)?(\s|-)?(\()?(\d){3}(\))?(\s|-){1}(\d){3}(\s|-){1}(\d){4}/';
		$reg="/\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{3}/";
		//$reg="/^(083|086|085|086|087)\d{7}$/";

  		 if(preg_match($reg, $description)) {   
	                  $this->form_validation->set_message('_phonenumber_check','Phone numbers Not Allowed');
			  return FALSE;
		}
		else
		{
          	return TRUE;
         }
       
  	}
	
	
	// For Description field (Check for Email Address) 	
	 function _emailpresent_check()
	{	
		$description=$_POST['description'];
		$reg = '/[\s]*[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/';

		if(preg_match($reg, $description)) {

		$this->form_validation->set_message('_emailpresent_check','Emails Not Allowed');
		return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	function _maxvalcheckcat()
{

 $max=$this->input->post('categories'); 
if(count($max)<6)
{
return true;
}
else
{
$this->form_validation->set_message('categories[]', $this->lang->line('Job Type: (Make up to 5 selections.)'));
return false;

}
}	
	
}
//End  Joblist Class
/* End of file joblist.php */ 
/* Location: ./app/controllers/joblist.php */
?>