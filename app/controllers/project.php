<?php

/**
 * Reverse bidding system Project Class
 *
 * Project related tasks are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Project 
 * @author		
 * @version		
 * @created		December 31 2008
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
class Project extends Controller {

    //Global variable  
    public $outputData;  //Holds the output data for each view
    public $loggedInUser;

    /**
     * Constructor 
     *
     * Loads language files and models needed for this controller
     */
    function Project() {
        parent::Controller();

        //Get Config Details From Db
        $this->config->db_config_fetch();

        //Manage site Status 
        if ($this->config->item('site_status') == 1)
            redirect('offline');


        //Load the helper file reviews
        $this->load->helper('reviews');

        //language file
        $this->lang->load('enduser/common', $this->config->item('language_code'));
        $this->lang->load('enduser/project', $this->config->item('language_code'));
        $this->lang->load('enduser/createProject', $this->config->item('language_code'));


        //Debug Tool
        // $this->output->enable_profiler=true;		
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
        //Page Title and Meta Tags
        $this->outputData = $this->common_model->getPageTitleAndMetaData();

        //Get Top sellers
        $topSellers = $this->common_model->getPageTitleAndMetaData();

        //Get Logged In user
        $this->loggedInUser = $this->common_model->getLoggedInUser();
        $this->outputData['loggedInUser'] = $this->loggedInUser;



        //Get Footer content
        $this->outputData['pages'] = $this->common_model->getPages();

        //Get Latest Projects
        $limit_latest = $this->config->item('latest_projects_limit');
        $limit3 = array($limit_latest);
        $this->outputData['latestProjects'] = $this->skills_model->getLatestProjects($limit3);

        //Get draft Projects
        if (isset($this->loggedInUser->id)) {
            $flag = 0;
            $condition = array('draftprojects.creator_id' => $this->loggedInUser->id, 'draftprojects.flag' => $flag);
            $this->outputData['draftProjects'] = $this->skills_model->getDraft($condition);

            $conditions = array('user_list.creator_id' => $this->loggedInUser->id, 'user_list.user_role' => '1');
            $this->outputData['favouriteUsers'] = $this->user_model->getFavourite($conditions);
            $this->outputData['project_period'] = $this->config->item('project_period');
        }

        //Post the maximum size of memory limit
        $maximum = $this->config->item('upload_limit');
        $this->outputData['maximum_size'] = $maximum;
        if ($this->loggedInUser) {
            //Conditions
            $conditions = array('files.user_id' => $this->loggedInUser->id);
            $this->outputData['fileInfo'] = $this->file_model->getFile($conditions);
        }

        //Get Certificate User 

        if ($this->loggedInUser) {

            $condition = array('subscriptionuser.username' => $this->loggedInUser->id);

            $userlists = $this->certificate_model->getCertificateUser($condition);

            if ($userlists->num_rows() > 0) {
                // get the validity
                $validdate = $userlists->row();
                $end_date = $validdate->valid;
                $created_date = $validdate->created;
                $valid_date = date('d/m/Y', $created_date);

                $next = $created_date + ($end_date * 24 * 60 * 60);
                $next_day = date('d/m/Y', $next) . "\n";

                if (time() <= $next) {
                    $paymentSettings = $this->settings_model->getSiteSettings();

                    $this->outputData['feature_project'] = $paymentSettings['FEATURED_PROJECT_AMOUNT_CM'];
                    $this->outputData['urgent_project'] = $paymentSettings['URGENT_PROJECT_AMOUNT_CM'];
                    $this->outputData['hide_project'] = $paymentSettings['HIDE_PROJECT_AMOUNT_CM'];
                    $this->outputData['private_project'] = $paymentSettings['PRIVATE_PROJECT_AMOUNT_CM'];
                } else {
                    //Initital payment settings for projects
                    $paymentSettings = $this->settings_model->getSiteSettings();
                    $this->outputData['feature_project'] = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
                    $this->outputData['urgent_project'] = $paymentSettings['URGENT_PROJECT_AMOUNT'];
                    $this->outputData['hide_project'] = $paymentSettings['HIDE_PROJECT_AMOUNT'];
                    $this->outputData['private_project'] = $paymentSettings['PRIVATE_PROJECT_AMOUNT'];
                }
            } else {
                $paymentSettings = $this->settings_model->getSiteSettings();
                $this->outputData['feature_project'] = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
                $this->outputData['urgent_project'] = $paymentSettings['URGENT_PROJECT_AMOUNT'];
                $this->outputData['hide_project'] = $paymentSettings['HIDE_PROJECT_AMOUNT'];
                $this->outputData['private_project'] = $paymentSettings['PRIVATE_PROJECT_AMOUNT'];
            }
        }
    }

//Constructor End 
    // --------------------------------------------------------------------

    /**
     * discard draft project by buyer
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function deleteDraft() {

        if ($this->uri->segment(3)) {
            $condition = array('draftprojects.id' => $this->uri->segment(3));
            $this->skills_model->deletedraftprojects($condition);
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('Draft Project Deleted Successfully')));
            redirect('project/create');
        }
    }

    /**
     * Post new projects by buyer
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function create() {


        $this->outputData['current_page'] = 'post_project';

        $result = '0';
        $manage = '1';
        $this->outputData['showPreview'] = false;

        //Load Language
        $this->lang->load('enduser/withdrawMoney', $this->config->item('language_code'));
        $this->lang->load('enduser/createBids', $this->config->item('language_code'));
        $this->lang->load('enduser/createProject', $this->config->item('language_code'));

        $this->outputData['created'] = get_est_time();
        $this->outputData['enddate'] = get_est_time() + (7 * 86400);

        //Check For Buyer Session
        if (!isBuyer()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a buyer to post projects')));
            redirect('info');
        }
        if ($this->loggedInUser->suspend_status == 1) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('Suspend Error')));
            redirect('info');
        }
        //load validation libraray
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        if ($this->input->post('projectid')) {
            $project = $this->input->post('projectid');
        }

        //Save the draft projects
        /* if($this->input->post('save_draft'))
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
          $insertData['enddate']       		= get_est_time() + (7 * 86400);

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
          $updateDraft['enddate']       		= get_est_time() + (7 * 86400);

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
          } */

        if ($this->uri->segment(3, 0))
            $project_id = $this->uri->segment(3, 0);
        else
            $project_id = $this->input->post('projectid');
        //Get the project details for post similar projects
        $conditions = array('projects.id' => $project_id, 'projects.creator_id' => $this->loggedInUser->id);
        $postSimilar = $this->skills_model->getUsersproject($conditions);
        $this->outputData['postSimilar'] = $postSimilar;

        //Get Form Data	
        if ($this->input->post('createProject') or $this->input->post('preview_project')) {

            //Set rules
            // Puhal Changes Start Following validations are to verify the post of Email address and Phone number (Sep 17 Issue 1)	-------------------------------------------		

            $this->form_validation->set_rules('projectName', 'lang:project_name_validation', 'required|trim|min_length[5]|xss_clean|alpha_space|callback__emailpresent_projectname_check|callback__phonenumber_projectname_check|callback__project_exist_check');

// Puhal Changes End Following validations are to verify the post of Email address and Phone number (Sep 17 Issue 1)	-------------------------------------------		
            $this->form_validation->set_rules('description', 'lang:description_validation', 'required|min_length[25]|trim|xss_clean|callback__emailpresent_check|callback__phonenumber_check');
            $this->form_validation->set_rules('attachment', 'lang:attachment_validation', 'callback_attachment_check');
            $this->form_validation->set_rules('categories[]', 'lang:categories_validation', 'required');
            //$this->form_validation->set_rules('is_feature','lang:is_feature_validation','trim');
            //$this->form_validation->set_rules('is_private','lang:is_private_validation','trim');
            //$this->form_validation->set_rules('is_urgent','lang:is_urgent_validation','trim');
            //$this->form_validation->set_rules('is_hide_bids','lang:is_hide_bids_validation','trim');
            //$this->form_validation->set_rules('budget_min','lang:budget_min_validation','trim|integer|is_natural|abs|xss_clean');
            //$this->form_validation->set_rules('budget_max','lang:budget_max_validation','trim|integer|is_natural|abs|xss_clean|callback__maxvalcheck');   
            $this->form_validation->set_rules('categories[]', 'lang:categories_validation', 'trim|integer|is_natural|abs|xss_clean|callback__maxvalcheckcat');
            /* if($this->input->post('is_private'))
              {
              $this->form_validation->set_rules('private_list','lang:private_list','required');
              } */
            $this->form_validation->set_message('required', 'This field can not be blank');

            if ($this->form_validation->run()) {
                //This is condition check for post similar project
                $conditions = array('projects.project_name' => $this->input->post('projectName'));
                $postSimilar = $this->skills_model->getUsersproject($conditions);
                $res = $postSimilar->num_rows();
                if ($res > 0) {
                    $sameProject = $postSimilar->row();
                    $project = $sameProject->id;
                }
                if ($this->input->post('update') != '0')
                    $manage = '1';
                else
                    $manage = '0';

                if ($manage != '0') {
                    if ($res > 0) {
                        $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('Project already Exists')));
                        redirect('project/postProject/' . $project);
                    }
                }

                $insertData = array();
                $insertData['project_name'] = $this->input->post('projectName');
                $insertData['description'] = $this->input->post('description');
                //Puhal Changes Start for downloading the Project attachment file (Sep 20 Isssue 17)

                if (isset($this->data['file'])) {
                    $insertData['attachment_url'] = $this->data['file']['file_name'];
                    $insertData['attachment_name'] = $this->data['file']['orig_name'];
                }

                if ($this->input->post('update') == '0') {
                    $insertData['description'] = $this->input->post('description') . '<br/>';
                    $insertData['description'] .= $this->input->post('add_description');
                }
                else
                    $insertData['description'] = $this->input->post('description');

                $insertData['budget_min'] = 0;
                $insertData['budget_max'] = 0;
                $insertData['is_feature'] = 0;
                $insertData['is_urgent'] = 0;
                $insertData['is_hide_bids'] = 0;
                $insertData['number_of_buyers'] = 1;
                $insertData['flag'] = 0;
                if ($this->input->post('is_private')) {
                    $insertData['is_private'] = $this->input->post('is_private');
                }
                $insertData['creator_id'] = $this->loggedInUser->id;
                $insertData['created'] = get_est_time();
                $insertData['enddate'] = get_est_time() + (7 * 86400);
                $insertData['project_status'] = '0';
                $result = '0';

                //Project Preview
                /* if($this->input->post('preview_project'))
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
                  $outputData['enddate']       		= get_est_time() + (7 * 86400);
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
                  } */

                //Project Submit
                //check the condition for view the preview about the project
                if ($result == '0') {
                    $this->loggedInUser = $this->common_model->getLoggedInUser();
                    $this->outputData['loggedInUser'] = $this->loggedInUser;
                    $login_user = $this->loggedInUser;
                    $condition = array('subscriptionuser.username' => $this->loggedInUser->id);
                    $userlists = $this->certificate_model->getCertificateUser($condition);

                    if ($userlists->num_rows() > 0) {
                        // get the validity
                        $validdate = $userlists->row();
                        $end_date = $validdate->valid;
                        $created_date = $validdate->created;
                        $valid_date = date('d/m/Y', $created_date);

                        $next = $created_date + ($end_date * 24 * 60 * 60);
                        $next_day = date('d/m/Y', $next) . "\n";

                        if (time() <= $next) {
                            $paymentSettings = $this->settings_model->getSiteSettings();
                            $feature_project = $this->config->item('featured_project_amount_cm');
                            $urgent_project = $paymentSettings['URGENT_PROJECT_AMOUNT_CM'];
                            $hide_project = $paymentSettings['HIDE_PROJECT_AMOUNT_CM'];
                            $private_project = $paymentSettings['PRIVATE_PROJECT_AMOUNT_CM'];
                            $this->outputData['feature_project'] = $feature_project;
                            $this->outputData['urgent_project'] = $urgent_project;
                            $this->outputData['hide_project'] = $hide_project;
                            $this->outputData['private_project'] = $private_project;
                            $this->outputData['created'] = get_est_time();
                            $this->outputData['enddate'] = get_est_time() + (7 * 86400);
                        } else {
                            //Get the values from settings table
                            $paymentSettings = $this->settings_model->getSiteSettings();
                            $feature_project = $this->config->item('featured_project_amount');
                            $urgent_project = $paymentSettings['URGENT_PROJECT_AMOUNT'];
                            $hide_project = $paymentSettings['HIDE_PROJECT_AMOUNT'];
                            $private_project = $paymentSettings['PRIVATE_PROJECT_AMOUNT'];
                            $this->outputData['feature_project'] = $feature_project;
                            $this->outputData['urgent_project'] = $urgent_project;
                            $this->outputData['hide_project'] = $hide_project;
                            $this->outputData['private_project'] = $private_project;
                            $this->outputData['created'] = get_est_time();
                            $this->outputData['enddate'] = get_est_time() + (7 * 86400);
                        }
                    } else {
                        $paymentSettings = $this->settings_model->getSiteSettings();
                        $feature_project = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
                        $urgent_project = $paymentSettings['URGENT_PROJECT_AMOUNT'];
                        $hide_project = $paymentSettings['HIDE_PROJECT_AMOUNT'];
                        $private_project = $paymentSettings['PRIVATE_PROJECT_AMOUNT'];
                    }

                    if ($this->input->post('createProject')) {

                        //initial value set for check the featured , urgent, hide projects
                        //$settingAmount=0;
                        //check the values for featured, urgent, hide projects
                        /* if($this->input->post('is_feature'))
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
                          } */

                        //Check User Balance
                        //$condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);
                        //$results 	 			 = $this->transaction_model->getBalance($condition_balance);
                        //If Record already exists
                        /* if($results->num_rows()>0)
                          {
                          //get balance detail
                          $rowBalance = $results->row();

                          $this->outputData['userAvailableBalance'] = $rowBalance->amount;
                          } */

                        /* if($this->input->post('is_hide_bids',TRUE) or $this->input->post('is_urgent',TRUE) or $this->input->post('is_feature',TRUE) or  $this->input->post('is_private',TRUE)) 
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
                          } */
                    }

                    //Get payment settings for check minimum balance from settings table
                    $this->outputData['paymentSettings'] = $paymentSettings;
                    $this->outputData['PAYMENT_SETTINGS'] = $paymentSettings['PAYMENT_SETTINGS'];
                    if ($this->input->post('categories')) {
                        $categories = $this->input->post('categories');

                        //Work With Project Categories
                        $project_categoriesNameArray = $this->skills_model->convertCategoryIdsToName($categories);
                        $project_categoriesNameString = implode(',', $project_categoriesNameArray);
                        $insertData['project_categories'] = $project_categoriesNameString;
                    }

                    if ($this->input->post('createProject')) {

                        // insert the projects details into project table
                        $this->skills_model->createProject($insertData);

                        $projectid = $this->db->insert_id();

                        $insert['user_id'] = $this->loggedInUser->id;
                        $insert['project_id'] = $projectid;
                        $this->skills_model->newWantList($insert);
                        /* if($this->input->post('is_private'))
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

                          } */

                        /* if($this->input->post('is_private'))	
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
                          } */
                        /* if($this->input->post('is_private'))	
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
                        /* $tuser = $this->config->item('twitter_username');
                          $tpass = $this->config->item('twitter_password');
                          $twit_msg = "<".$this->loggedInUser->user_name."> ".$insertData['project_name']." : ".site_url('project/view/'.$this->db->insert_id());
                          $twit_content= $this->skills_model->tinyUrl(site_url('project/view/'.$this->db->insert_id()));
                          $this->skills_model->sendTwitter($twit_content,$tuser,$tpass); */

                        //Send instant notification mail to providers
                        $conditions = array('users.role_id' => '2', 'users.user_status' => '1', 'user_categories.user_categories !=' => '', 'users.project_notify' => 'Instantly');

                        $users = $this->user_model->getUsersWithCategories($conditions);

                        foreach ($users->result() as $user) {
                            $cate = explode(",", $user->user_categories);

                            $inter = array_intersect($cate, $categories);

                            //Check if categories are matched to send notification
                            if (count($inter) > 0) {

                                $mailSubject = $this->config->item('site_title') . " Project Notice";
                                $mailContent = "The following project was recently added to " . $this->config->item('site_title') . " and match your expertise:";

                                $condition3 = array('projects.id' => $this->db->insert_id());
                                $mpr = $this->skills_model->getProjects($condition3);
                                $prj = $mpr->row();
                                $mailContent .= $prj->project_name . " (Posted by " . $prj->user_name . ", " . get_datetime($prj->created) . ", Job type:" . $prj->project_categories . ")" . " " . site_url('project/view/' . $prj->id);

                                //Send mail
                                $toEmail = $user->email;
                                $fromEmail = $this->config->item('site_admin_mail');
                                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                            }
                        }
                        /* end of new vesrion */

                        //$delete_condition   =  array('draftprojects.project_name'=>$this->input->post('projectName'));
                        //$this->skills_model->deletedraftprojects($delete_condition);
                        //Notification message
                        $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('Your Project has been Posted Successfully')));
                        redirect('buyer/viewMyProjects');
                    }
                    redirect('info/index/success');
                }
            }//Form Validation End
        }//If - Form Submission End
        //Get Groups
        $this->outputData['groupsWithCategories'] = $this->skills_model->getGroupsWithCategory();
        if ($result == '0') {
            $this->load->view('project/createProject', $this->outputData);
        } else {
            $condition = array('projects_preview.id' => $this->db->insert_id());
            $preview = $this->skills_model->getpreviewProjects($condition);
            $this->outputData['preview'] = $preview;
            //pr($preview);

            $this->load->view('project/createProject', $this->outputData);
        }
    }

//Function create End
    // --------------------------------------------------------------------
    //Puhal Changes Start for downloading the Project attachment file (Sep 20 Isssue 17)

    function download() {
        $this->load->library('zip');
        $this->load->helper('download');
        $this->load->helper('users');
        // initiallize the data variable in to array
        $this->data = array();

        // get the key value 
        $value = $this->uri->segment(3, 0);
        // Assign the base path.
        $base_path = base_url() . 'files/';
        $data = file_get_contents_curl($base_path . 'project_attachment/' . $value);
        $name = $value;
        // Apply the download function
        force_download($name, $data);
    }

//Puhal Changes End for downloading the Project attachment file (Sep 20 Isssue 17)

    /**
     * function create bid the seller will create bid for the project
     *
     * @access	public for seller
     * @param	nil
     * @return	void
     */
    function createBid() {

        //Load Language
        $this->lang->load('enduser/postBid', $this->config->item('language_code'));

        //Check For Buyer Session
        if (!isSeller()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a Seller to bid projects')));
            redirect('info');
        }

        //load validation libraray
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));



        //Get Form Data	
        if ($this->input->post('postBid')) {
            //Set rules
            $this->form_validation->set_rules('bidAmt', 'lang:Bid_Amount_validation', 'required|numeric|trim|xss_clean');
            $this->form_validation->set_rules('quantity', 'lang:quantity_validation', 'required|integer|trim|xss_clean');
            $this->form_validation->set_rules('message2', 'lang:description_validation', 'trim|xss_clean');
            
            //get project details
            $project_id = $this->input->post('project_id');
            $condition = array('projects.id' => $project_id);
            $project = $this->skills_model->getProjects($condition);
            $projectDetails = $project->row();
            
            if ($this->form_validation->run()) {
                $insertData = array();
                $insertData['project_id']   = $project_id;
                $insertData['user_id']      = $this->loggedInUser->id;        
                $insertData['quantity']     = $this->input->post('quantity');
                $insertData['bid_amount']   = $this->input->post('bidAmt');
                $insertData['bid_time']     = get_est_time();
                $insertData['bid_desc']     = $this->input->post('message2');
                
                if ($this->input->post('same_area'))
                    $insertData['same_area'] = $this->input->post('same_area');
                
                //Create bids
                $bid_id = $this->skills_model->createBids($insertData);
                
                //Create discounts
                if ($this->input->post('discount')) {
                    $this->skills_model->createDiscount($bid_id,$this->input->post('discount_value'),$this->input->post('discount_at'));
                }
                
                //Load Model For Mail
                $this->load->model('email_model');

                //Send Mail
                $conditionUserMail = array('email_templates.type' => 'bid_notice');
                $result = $this->email_model->getEmailSettings($conditionUserMail);
                $rowUserMailConent = $result->row();

                //User details
                $condition2 = array('users.id' => $projectDetails->creator_id);
                $user = $this->user_model->getUsers($condition2, 'users.user_name,users.email');
                $userDetails = $user->row();
                
                //Provider details
                $condition3 = array('users.id' => $insertData['user_id']);
                $provider = $this->user_model->getUsers($condition3, 'users.user_name');
                $providerDetails = $provider->row();
                
                $splVars = array("!project_name" => '<a href="' . site_url('project/view/' . $projectDetails->id) . '">' . $projectDetails->project_name . '</a>', "!user_name" => $userDetails->user_name, "!provider_name" => $providerDetails->user_name, "!contact_url" => site_url('contact'), '!site_name' => $this->config->item('site_title'), '!bid_time' => $btime, '!bid_amt' => "$" . $insertData['bid_amount']);
                $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                $toEmail = $userDetails->email;
                $fromEmail = $this->config->item('site_admin_mail');
                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);

				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your bid Has Been Posted Successfully')));
				  redirect('project/view/'.$insertData['project_id']);
                
            } else {                
                $this->outputData['projects'] = $project;
                $conditions = array('bids.user_id' => $this->loggedInUser->id, 'bids.project_id' => $project_id);
                $this->outputData['bid'] = $this->skills_model->getBids($conditions);

                $this->outputData['project_id'] = $project_id;
                //Get Total Messages
                $this->load->model('messages_model');
                $message_conditions = array('messages.project_id' => $project_id);
                $this->outputData['totalMessages'] = $this->messages_model->getTotalMessages($message_conditions);
            } //form validation end
        } //form submission end
       
        $this->load->view('project/postBid', $this->outputData);
    }

//Function create bid End
    // --------------------------------------------------------------------

    /**
     * Seller will edit the placed bid for the project
     *
     * @access	public for seller
     * @param	nil
     * @return	void
     */
    function editBid() {

        //Load Language
        $this->lang->load('enduser/postBid', $this->config->item('language_code'));

        //Check For Buyer Session
        if (!isSeller()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a Seller to bid projects')));
            redirect('info');
        }

        //load validation libraray
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));


        var_dump($this->input->post);

        $this->load->view('project/test', $this->outputData);
    }

//Function edit bid End
    // --------------------------------------------------------------------

    /**
     * Loads Categories page.
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function category() {
        //Load Language
        $this->lang->load('enduser/listProjects', $this->config->item('language_code'));
        $this->lang->load('enduser/editProfile', $this->config->item('language_code'));
        $this->lang->load('enduser/common', $this->config->item('language_code'));
        if ($this->input->post('customizeDisplay')) {
            //Get Customize data fields
            $this->session->set_userdata('show_cat', $this->input->post('show_cat', true));
            $this->session->set_userdata('show_budget', $this->input->post('show_budget', true));
            $this->session->set_userdata('show_bids', $this->input->post('show_bids', true));
            $this->session->set_userdata('show_avgbid', $this->input->post('show_avgbid', true));
            $this->session->set_userdata('show_status', $this->input->post('show_status', true));
            $this->session->set_userdata('show_date', $this->input->post('show_date', true));
            $this->session->set_userdata('show_desc', $this->input->post('show_desc', true));
            $this->session->set_userdata('show_num', $this->input->post('show_num', true));
        } else {
            $this->session->set_userdata('show_cat', '1');
            $this->session->set_userdata('show_budget', '1');
            $this->session->set_userdata('show_bids', '1');
            $this->session->set_userdata('show_num', '5');
        }

        //Get Category Id
        $category_name = urldecode($this->uri->segment(3, '0'));

        //Page Title and Meta Tagsc
        $condition_key = array('categories.category_name' => $category_name);
        $result = $this->common_model->getPageTitle($condition_key);
        $result = $result->row();

        if (count($result) > 0) {
            $this->outputData['page_title'] = $this->config->item('site_title') . $result->page_title;
            $this->outputData['meta_keywords'] = $result->page_title;
            $this->outputData['meta_description'] = $result->meta_description;
        }

        //$category_name = replaceUnderscoreWithSpace($category_name);
        //Get current page
        $page = $this->uri->segment(4, '0');

        //Get Sorting order
        $field = $this->uri->segment(5, '0');

        $order = $this->uri->segment(6, '0');
        $this->outputData['order'] = $order;
        $this->outputData['field'] = $field;

        $orderby = array();
        if ($field)
            $orderby = array($field, $order);

        if (isset($page) === false or empty($page)) {
            $page = 1;
        }
        $page_rows = $this->session->userdata('show_num');
        $max = array($page_rows, ($page - 1) * $page_rows);

        //Get Category Info
        //Set Conditions
        $conditions = array('category_name' => $category_name);
        $categories = $this->skills_model->getCategories($conditions);
        if ($categories->num_rows() > 0)
            $category = $categories->row();
        else {
            redirect('search/professional/' . $category_name);
        }

        $this->outputData['category_id'] = $category->id;
        $this->outputData['category_name'] = $category->category_name;

        //Projects List
        $like = array('project_categories' => $category_name);
        $this->outputData['projects'] = $this->skills_model->getProjects(NULL, NULL, $like, $max, $orderby);
        $projects = $this->skills_model->getProjects(NULL, NULL, $like);

        //Pagination
        $this->load->library('pagination');
        $config['base_url'] = site_url('project/category/' . $category_name);
        $config['total_rows'] = $projects->num_rows();
        $config['per_page'] = $page_rows;
        $config['cur_page'] = $page;
        $this->outputData['page'] = $page;
        $this->pagination->initialize($config);
        $this->outputData['pagination'] = $this->pagination->create_links(false, 'project');
        $this->load->view('project/listProjects', $this->outputData);
    }

//Function category End
    // --------------------------------------------------------------------

    /**
     * Loads project view page.
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function view() {
        //Load Language
        $this->lang->load('enduser/common', $this->config->item('language_code'));
        $this->lang->load('enduser/viewProject', $this->config->item('language_code'));
        $this->load->helper('users_helper');
        $this->load->library('table');
        //Get Project Id
        if ($this->uri->segment(3)) {
            $project_id = $this->uri->segment(3, '0');
            $conditions = array('projects.id' => $project_id);
            $this->outputData['projects'] = $this->skills_model->getProjects($conditions);
            $result = $this->outputData['projects'];
            $project_detail = $result->result();
            //Check for the Private Project view
            foreach ($project_detail as $private) {
                if (isset($private->is_private)) {
                    if ($private->is_private == 1 and !$this->loggedInUser) {
                        redirect('users/getProjectDetails/' . $project_id . '/' . $private->private_users . '/' . $private->creator_id);
                    } elseif (isset($this->loggedInUser)) {
                        if ($private->is_private == 1 and $this->loggedInUser->id != $private->private_users and $this->loggedInUser->id != $private->creator_id) {
                            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('This is not your private project')));
                            redirect('info');
                            //redirect('users/getProjectDetails/'.$project_id.'/'.$private->private_users.'/'.$private->creator_id);
                        }
                    }
                }
            }
        } else {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You can not access to this page')));
            redirect('info');
        }
        if (!is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You can not access to this page')));
            redirect('info');
        }
        if (isset($project_id) and isset($this->loggedInUser->id)) {
            $updateKey = array('project_invitation.project_id' => $project_id, 'project_invitation.receiver_id' => $this->loggedInUser->id);
            $updateData['notification_status'] = '1';
            $this->user_model->updateSellerInvitation($updateKey, $updateData);
        }

        $conditions = array('projects.id' => $project_id);
        $this->outputData['projects'] = $this->skills_model->getProjects($conditions);
        $result = $this->outputData['projects'];
        $this->outputData['projectRow'] = $this->outputData['projects']->row();
        //pr($this->outputData['projectRow']);exit;

        if ($this->outputData['projects']->num_rows() == 0) {
            //Notification message
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('project_not_available')));
            redirect('info');
        }

        //echo $this->outputData['projectRow']->creator_id;exit;
        $this->outputData['creatorInfo'] = getUserInfo($this->outputData['projectRow']->creator_id);

        $projects = $this->outputData['projects']->row();
        //pr($projects);exit;
        if (isset($this->loggedInUser->id) and $projects->creator_id == $this->loggedInUser->id)
            $conditions = array('bids.project_id' => $project_id);
        else
            $conditions = array('bids.project_id' => $project_id, 'projects.is_hide_bids' => 0);

        $this->outputData['bids'] = $this->skills_model->getBids($conditions);
        //pr($this->outputData['bids']->result());exit;

        $this->outputData['projectId'] = $project_id;

        if (isset($this->loggedInUser->id)) {
            $conditions = array('bids.user_id' => $this->loggedInUser->id, 'bids.project_id' => $project_id);
            $totbid = $this->skills_model->getBids($conditions);
            $this->outputData['tot'] = $totbid->row();
        }
        else
            $this->outputData['tot'] = array();

        //Get Total Messages
        $this->load->model('messages_model');
        $message_conditions = array('messages.project_id' => $project_id);
        $this->outputData['totalMessages'] = $this->messages_model->getTotalMessages($message_conditions);

        $this->load->view('project/viewProject', $this->outputData);
    }

//Function view End

    /**
     * Loads draft project view page.
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function draftView() {
        $projectid = $this->input->post('projectid1');

        //Load Language
        $this->lang->load('enduser/viewProject', $this->config->item('language_code'));
        $this->outputData['groupsWithCategories'] = $this->skills_model->getGroupsWithCategory();

        //Get Project Id
        $project_id = $this->input->post('draftId');

        $this->outputData['draftProjectsid'] = $project_id;
        $conditions = array('draftprojects.id' => $project_id);
        $this->outputData['projects'] = $this->skills_model->getDraft($conditions);

        if ($this->input->post('draftId') == 'clear') {
            redirect('project/deleteDraft/' . $projectid);
        }
        if ($this->input->post('draftId') == 'savedraft') {
            redirect('project/create');
        }
        if ($this->outputData['projects']->num_rows() == 0) {
            //Notification message
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('project_not_available')));
            redirect('info');
        }

        //Get Total Messages
        $this->load->model('messages_model');
        $message_conditions = array('messages.project_id' => $project_id);
        $this->outputData['totalMessages'] = $this->messages_model->getTotalMessages($message_conditions);
        $this->load->view('project/draftProject', $this->outputData);
    }

//Function draftview End
    //-------------------------------------------------------------------------------------

    /**
     * Loads project view page.
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function previewProject() {
        //Load Language
        $this->lang->load('enduser/viewProject', $this->config->item('language_code'));

        //Get Project Id
        $project_id = $this->uri->segment(3, '0');
        $conditions = array('projects_preview.id' => $project_id);
        $this->outputData['projects'] = $this->skills_model->getpreviewProjects($conditions);
        //print_r($this->outputData['projects']->result());
        $this->load->view('project/previewProject', $this->outputData);
    }

//Function previewProject End
    // --------------------------------------------------------------------

    /**
     * Loads postbid page.
     *
     * @access	public for seller
     * @param	nil
     * @return	void
     */
    function postBid() {
        //Load Language
        $this->lang->load('enduser/postBid', $this->config->item('language_code'));

        //Check For Seller Session
        if (!isSeller()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a seller to place a bid')));
            redirect('info');
        }
        if ($this->loggedInUser->suspend_status == 1) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('Suspend Error')));
            redirect('info');
        }
        //load validation libraray
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        //Get Project Id
        if ($this->uri->segment(3)) {
            $project_id = $this->uri->segment(3, '0');
            $conditions = array('bids.user_id' => $this->loggedInUser->id, 'bids.project_id' => $project_id);
            $this->outputData['bid'] = $this->skills_model->getBids($conditions);
        } else {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You can not access to this page')));
            redirect('info');
        }
        if (!is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You can not access to this page')));
            redirect('info');
        }

        $conditions1 = array('projects.id' => $project_id);
        $this->outputData['projects'] = $this->skills_model->getProjects($conditions1);
        $this->outputData['project_id'] = $project_id;

        //Get Total Messages
        $this->load->model('messages_model');
        $message_conditions = array('messages.project_id' => $project_id);
        $this->outputData['totalMessages'] = $this->messages_model->getTotalMessages($message_conditions);

        //Get the favourite usersList
        $favourite_condition = array('user_list.creator_id' => $this->loggedInUser->id);
        $this->outputData['favourite'] = $this->user_model->getFavourite($favourite_condition);
        $this->load->view('project/postBid', $this->outputData);
    }

//Function postBid End
    // --------------------------------------------------------------------

    /**
     * Loads attachment_check for buyer
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function attachment_check() {

        if (isset($_FILES) and $_FILES['attachment']['name'] == '')
            return true;

        $config['upload_path'] = 'files/project_attachment/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|zip|ZIP|RAR|rar|doc|DOC|txt|TXT|xls|XLS|ppt|PPT|pdf|PDF|docx|xlsx|pptx';
        $config['max_size'] = $this->config->item('max_upload_size');
        $config['encrypt_name'] = TRUE;
        $config['remove_spaces'] = TRUE;
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('attachment')) {

            $this->data['file'] = $this->upload->data();
            return true;
        } else {
            $this->form_validation->set_message('attachment_check', $this->upload->display_errors($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag')));
            return false;
        }//If end 
    }

//Function attachment_check End
    // --------------------------------------------------------------------
    // Puhal Changes Start Following validations are to verify the post of Email address and Phone number (Sep 17 Issue 1)	-------------------------------------------
// For Description field (Check for Phone number) 
    function _phonenumber_check() {
        $description = $_POST['description'];
        //$reg = '/(\d)?(\s|-)?(\()?(\d){3}(\))?(\s|-){1}(\d){3}(\s|-){1}(\d){4}/';
        $reg = "/\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{3}/";
        //$reg="/^(083|086|085|086|087)\d{7}$/";

        if (preg_match($reg, $description)) {
            $this->form_validation->set_message('_phonenumber_check', 'Phone numbers Not Allowed');
            return FALSE;
        } else {
            return TRUE;
        }
    }

// For project name  field (Check for Phone number) 		 
    function _phonenumber_projectname_check() {
        $projectName = $_POST['projectName'];
        //$reg = '/(\d)?(\s|-)?(\()?(\d){3}(\))?(\s|-){1}(\d){3}(\s|-){1}(\d){4}/';
        $reg = "/\(?[0-9]{1}\)?[-. ]?[0-9]{1}[-. ]?[0-9]{1}/";

        if (preg_match($reg, $projectName)) {

            $this->form_validation->set_message('_phonenumber_projectname_check', 'Phone numbers Not Allowed');
            return FALSE;
        } else {
            return TRUE;
        }
    }

// For Description field (Check for Email Address) 	
    function _emailpresent_check() {
        $description = $_POST['description'];
        $reg = '/[\s]*[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/';

        if (preg_match($reg, $description)) {

            $this->form_validation->set_message('_emailpresent_check', 'Emails Not Allowed');
            return FALSE;
        } else {
            return TRUE;
        }
    }

// For project name  field (Check for E-mail address) 	
    function _emailpresent_projectname_check() {
        $description = $_POST['projectName'];

        $reg = '/[\s]*[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/';

        if (preg_match($reg, $description)) {

            $this->form_validation->set_message('_emailpresent_projectname_check', 'Emails Not Allowed');
            return FALSE;
        } else {
            return TRUE;
        }
    }

// Puhal Changes End For the validation (Email and Phone number ) ----------------------------------------------------------

    /**
     * List bids on the particular project
     *
     * @access	public
     * @param	project id
     * @return	contents
     */
    function showBids() {
        //Load Language
        $this->lang->load('enduser/viewProject', $this->config->item('language_code'));
        $this->load->helper('users_helper');

        //Get Project Id
        $project_id = $this->uri->segment(3, '0');

        //Get Project details
        $conditions = array('projects.id' => $project_id);
        $project = $this->skills_model->getProjects($conditions);
        $this->outputData['projectRow'] = $project->row();

        $this->outputData['creatorInfo'] = getUserInfo($this->outputData['projectRow']->creator_id);
        $conditions = array('bids.project_id' => $project_id, 'projects.is_hide_bids' => 0);
        $order = $this->uri->segment(4, '0');
        $field = $this->uri->segment(5, '0');
        if ($order != 0)
            $orderby = array($field, $order);
        else
            $orderby = array();

        $this->outputData['bids'] = $this->skills_model->getBids($conditions, '', array(), array(), $orderby);
        //pr($this->outputData['bids']->result());exit;
        $this->outputData['ord'] = $order;
        $this->outputData['field'] = $field;
        $this->outputData['projectId'] = $project_id;

        if (isset($this->loggedInUser->id)) {
            $conditions = array('bids.user_id' => $this->loggedInUser->id, 'bids.project_id' => $project_id);
            $totbid = $this->skills_model->getBids($conditions);
            $this->outputData['tot'] = $totbid->row();
        }
        else
            $this->outputData['tot'] = array();
        $this->load->view('project/showBids', $this->outputData);
    }

//Function showBids End
    // --------------------------------------------------------------------

    /**
     * List bids on the particular project to pick a Seller
     *
     * @access	public for buyer to pick seller
     * @param	project id
     * @return	contents
     */
    function pickProvider() {
        //Load Language
        $this->lang->load('enduser/pickProvider', $this->config->item('language_code'));

        //Get Project Id
        $project_id = $this->uri->segment(3, '0');
        $conditions = array('bids.project_id' => $project_id);
        $order = $this->uri->segment(4, '0');

        //Get the favourite usersList
        $favourite_condition = array('user_list.creator_id' => $this->loggedInUser->id);
        $this->outputData['favourite'] = $this->user_model->getFavourite($favourite_condition);

        if (isset($order))
            $orderby = array('bid_amount', $order);
        else
            $orderby = array();

        $this->outputData['bids'] = $this->skills_model->getBids($conditions, '', array(), array(), $orderby);
        $this->outputData['ord'] = $order;
        $this->load->view('project/pickProvider', $this->outputData);
    }

//Function showBids End
    // --------------------------------------------------------------------

    /**
     * List all projects
     *
     * @access	public for buyer
     * @param	project id
     * @return	contents
     */
    function viewAllProjects() {
        //Load Language
        $this->lang->load('enduser/featuredProjects', $this->config->item('language_code'));
        $this->lang->load('enduser/common', $this->config->item('language_code'));
        $this->lang->load('enduser/editProfile', $this->config->item('language_code'));



        if ($this->input->post('customizeDisplay')) {
            //Get Customize data fields
            $this->session->set_userdata('show_cat', $this->input->post('show_cat', true));
            $this->session->set_userdata('show_budget', $this->input->post('show_budget', true));
            $this->session->set_userdata('show_bids', $this->input->post('show_bids', true));
            $this->session->set_userdata('show_avgbid', $this->input->post('show_avgbid', true));
            $this->session->set_userdata('show_status', $this->input->post('show_status', true));
            $this->session->set_userdata('show_date', $this->input->post('show_date', true));
            $this->session->set_userdata('show_desc', $this->input->post('show_desc', true));
            $this->session->set_userdata('show_num', $this->input->post('show_num', true));
        } else {
            $this->session->set_userdata('show_cat', '1');
            $this->session->set_userdata('show_budget', '1');
            $this->session->set_userdata('show_bids', '1');
            $this->session->set_userdata('show_num', '5');
        }
        //pr($this->session->userdata);
        $type = $this->uri->segment(3, '0');

        if ($type == 'is_feature')
            $this->outputData['pName'] = 'Featured Projects';
        if ($type == 'is_urgent')
            $this->outputData['pName'] = 'Urgent Projects';
        if ($type == 'all')
            $this->outputData['pName'] = 'Latest Projects';
        if ($type == 'high_budget')
            $this->outputData['pName'] = 'High Budget Projects';

        $page = $this->uri->segment(4, '0');
        //Get Sorting order
        $field = $this->uri->segment(5, '0');

        $order = $this->uri->segment(6, '0');
        $this->outputData['order'] = $order;
        $this->outputData['field'] = $field;
        $this->outputData['type'] = $type;
        $this->outputData['page'] = $page;
        if (isset($page) === false or empty($page)) {
            $page = 1;
        }
        $page_rows = $this->session->userdata('show_num');
        $max = array($page_rows, ($page - 1) * $page_rows);

        if ($type == 'all')
            $feature_conditions = array('projects.project_status' => '0');
        elseif ($type == 'high_budget')
            $feature_conditions = array('projects.project_status' => '0', 'budget_max >=' => '500');
        else
            $feature_conditions = array($type => 1, 'projects.project_status' => '0');
        $projects1 = $this->skills_model->getProjects($feature_conditions, NULL, NULL, $max);
        $projects = $this->skills_model->getProjects($feature_conditions);
        $this->outputData['featureProjects'] = $projects1;
        $this->load->library('pagination');
        $config['base_url'] = site_url('project/viewAllProjects/' . $type);
        $config['total_rows'] = $projects->num_rows();
        $config['per_page'] = $page_rows;
        $config['cur_page'] = $page;
        $this->pagination->initialize($config);
        $this->outputData['pagination'] = $this->pagination->create_links(false, 'project');
        $this->load->view('project/viewAllProjects', $this->outputData);
    }

//Function showBids End
    // --------------------------------------------------------------------

    /**
     * List bids on the particular project to pick a Seller
     *
     * @access	public
     * @param	project id
     * @return	contents
     */
    function awardBid() {
        //Load Language
        $this->lang->load('enduser/pickProvider', $this->config->item('language_code'));
        if ($this->input->post('pickBid') && $this->input->post('bidid') != '') {
            $bidid = $this->input->post('bidid');
            $conditions = array('bids.id' => $bidid);
            $up = $this->skills_model->awardProject($conditions);


            if ($up == 1) {
                //Load Model For Mail
                $this->load->model('email_model');
                $bidres = $this->skills_model->getProjectByBid(array('bids.id' => $bidid));
                $bidres = $bidres->row();

                //Get all user post bids 
                $condition = array('bids.project_id' => $bidres->id, 'bids.user_id !=' => $bidres->seller_id);
                $bids = $this->skills_model->getBids($condition);
                foreach ($bids->result() as $bids) {

                    $user_condition = array('users.id' => $bids->user_id);
                    $users = $this->user_model->getUsers($user_condition);
                    $users = $users->row();

                    //Send Mail
                    $conditionUserMail = array('email_templates.type' => 'project_end');
                    $result = $this->email_model->getEmailSettings($conditionUserMail);
                    $rowUserMailConent = $result->row();
                    $splVars = array("!projectname" => $bidres->project_name, "!sitetitle" => site_url(), "!contact_url" => site_url('contact'));
                    $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                    $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                    $toEmail = $users->email;
                    $fromEmail = $this->config->item('site_admin_mail');
                    $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                }
                //Update the notification status for the proejct to zero
                $updateKey = array('projects.id' => $bidres->id);
                $updateData['notification_status'] = '0';
                $this->skills_model->updateProjects(NULL, $updateData, $updateKey);

                //Send Mail
                $conditionUserMail = array('email_templates.type' => 'awardBid');
                $result = $this->email_model->getEmailSettings($conditionUserMail);
                $rowUserMailConent = $result->row();
                $splVars = array("!project_title" => $bidres->project_name, "!bid_url" => site_url('project/acceptProject/' . $bidres->id . "/" . $bidres->checkstamp), "!deny_url" => site_url('project/denyProject/' . $bidres->id . "/" . $bidres->checkstamp), "!contact_url" => site_url('contact'));
                $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                $toEmail = $bidres->email;
                $fromEmail = $this->config->item('site_admin_mail');
                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                //Required Escrow
                $condition = 'bids.id=' . $bidid;
                $query = 'SELECT * FROM bids WHERE ' . $condition;
                $result = $this->db->query($query);

                foreach ($result->result() as $escrowbids) {
                    if ($escrowbids->escrow_flag == 1) {

                        $projectRow_id = $escrowbids->project_id;
                        $condition = array('transactions.type' => 'Escrow Transfer', 'transactions.project_id' => $projectRow_id);
                        $transactions = $this->transaction_model->getTransactions($condition);
                        //$transactions->num_rows();
                        $transactionrow = $transactions->row();
                        if (!is_object($transactionrow)) {
                            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('Please add escrow to award the project')));
                            redirect('info');
                        } else {
                            $status = $transactionrow->status;
                            $escrow_projectid = $transactionrow->project_id;
                            if ($status = '' or $escrow_projectid == '') {
                                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('Please add escrow to award the project')));
                                redirect('info');
                            }
                        }
                    }
                }
                //Check for Escrow

                $paymentSettings = $this->settings_model->getSiteSettings();
                $forced_escrow = $this->config->item('forced_escrow');
                if ($forced_escrow == 1) {
                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('Please send the Escrow Amount')));

                    redirect('buyer/viewMyProjects');
                } else {
                    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('You have successfully awarded the project')));

                    redirect('buyer/viewMyProjects');
                }
            } else {
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('Please select the Provider')));

                redirect('buyer/viewMyProjects');
            }
        } else {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('Please select the Provider')));

            redirect('buyer/viewMyProjects');
        }
    }

//Function awardbid End
    // --------------------------------------------------------------------

    /**
     * Accept project from Buyer who accepted your bid
     *
     * @access	public
     * @param	project id and checkstamp
     * @return	contents
     */
    function acceptProject() {
        //Load Language
        $this->lang->load('enduser/acceptProject', $this->config->item('language_code'));

        //Check For Seller Session
        if (!isSeller()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a seller to accept projects')));
            redirect('users/login');
        }


        $project_id = $this->uri->segment(3, '0');
        $checkstamp = $this->uri->segment(4, '0');

        if (isset($project_id)) {
            $updateKey = array('projects.id' => $project_id);
            $updateData['notification_status'] = '1';
            $this->skills_model->updateProjects(NULL, $updateData, $updateKey);
        }

        $conditions = array('projects.id' => $project_id, 'projects.checkstamp' => $checkstamp, 'projects.project_status' => '1', 'projects.seller_id' => $this->loggedInUser->id);
        $project = $this->skills_model->getProjects($conditions);
        $projectRow = $project->row();

        if (!is_object($projectRow)) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You cannot accept this project')));
            redirect('info');
        }

        //Check Ecrow Released or not

        $paymentSettings = $this->settings_model->getSiteSettings();
        $forced_escrow = $this->config->item('forced_escrow');
        if ($forced_escrow == 1) {
            $projectRow_id = $project_id;
            $condition = array('transactions.type' => 'Escrow Transfer', 'transactions.project_id' => $projectRow_id);
            $transactions = $this->transaction_model->getTransactions($condition);
            //$transactions->num_rows();
            $transactionrow = $transactions->row();
            if (!is_object($transactionrow)) {
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You have not released the Escrow Amount')));
                redirect('info');
            } else {
                $status = $transactionrow->status;
                $escrow_projectid = $transactionrow->project_id;
                if ($status = '' or $escrow_projectid == '') {
                    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You have not released the Escrow Amount.')));
                    redirect('info');
                }
            }
        }
        //Check User Balance
        $this->load->model('transaction_model');
        $condition_balance = array('user_balance.user_id' => $this->loggedInUser->id);
        $results = $this->transaction_model->getBalance($condition_balance);
        if ($results->num_rows() > 0) {
            //get balance detail
            $rowBalance = $results->row();
            $bal = $rowBalance->amount;
            $min_bal = $this->config->item('payment_settings');
            $commission = $this->config->item('provider_commission_amount');
            // Puhal changes to get the % of the winning bid 

            $cond = array('project_id' => $project_id, 'user_id' => $this->loggedInUser->id);
            $bid_amount = $this->common_model->getTableData('bids', $cond, 'bid_amount');
            $bid_amount_fetch = $bid_amount->row();


            $commission = ($commission * $bid_amount_fetch->bid_amount) / 100;
            $rem = $bal - $commission;

            if ($bal < $min_bal || $rem < $min_bal) {
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', 'Your account balance is too low'));
                redirect('seller/viewMyProjects');
            }
            $updateKey = array('user_balance.user_id' => $this->loggedInUser->id);
            $updateData = array('amount' => $rem);
            $results1 = $this->transaction_model->updateBalance($updateKey, $updateData);
        } else {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', 'Your have no balance in your account to accept the projects'));
            redirect('seller/viewMyProjects');
        }

        $buyerId = $projectRow->creator_id;
        $sellerId = $projectRow->seller_id;

        $conditions2 = array('users.id' => $buyerId);
        $buyer = $this->user_model->getUsers($conditions2);
        $buyerRow = $buyer->row();

        $conditions3 = array('users.id' => $sellerId);
        $seller = $this->user_model->getUsers($conditions3);
        $sellerRow = $seller->row();

        $updateKey = array(
            'projects.id' => $project_id,
            'projects.checkstamp' => $checkstamp,
            'projects.seller_id' => $sellerId
        );
        $updateData = array('projects.project_status' => '2');
        $upProject = $this->skills_model->accpetProject($updateKey, $updateData);

        if (isset($project_id)) {

            //Load model
            $this->load->model('settings_model');
            $this->load->model('affiliate_model');

            // get projects for this user
            $condition = array('projects.id' => $project_id);
            $mpr = $this->skills_model->getProjects($condition);
            $prj = $mpr->row();


            // get user
            $condition = array('users.id' => $this->loggedInUser->id);
            $user_data = $this->user_model->getUsers($condition);

            //$user_data_result = $user_data->result();
            $user_data_row = $user_data->row();

            // get affiliate payments
            $affiliate_result = $this->affiliate_model->getAffiliatePayment();
            $buyer_affiliate_fee = $affiliate_result['buyer_affiliate_fee'];
            $seller_affiliate_fee = $affiliate_result['seller_affiliate_fee'];
            $buyer_min_payout = $affiliate_result['buyer_min_payout'];
            $seller_min_payout = $affiliate_result['seller_min_payout'];
            $buyer_project_fee = $affiliate_result['buyer_project_fee'];
            $seller_project_fee = $affiliate_result['seller_project_fee'];

            //get affiliate settings
            $settings = $this->settings_model->getSiteSettings();

            $provider_settings_fee = $settings['PROVIDER_COMMISSION_AMOUNT'];
            $featured_project_amount = $settings['FEATURED_PROJECT_AMOUNT'];

            if ($prj->project_status == 2 and $prj->seller_id == $this->loggedInUser->id and $prj->checkstamp == $checkstamp) {

                if (isset($user_data_row->refid) and $user_data_row->refid != "0") {
                    $refid = $user_data_row->refid;
                    $role_id = $user_data_row->role_id;
                    $referral = $user_data_row->user_name;
                    $signup_date = $user_data_row->created;

                    $date_with_time = show_date($signup_date);
                    $arr_str = explode(',', $date_with_time);
                    $arr = explode(" ", trim($arr_str[1]));

                    for ($i = 0; $i < count($arr); $i++) {
                        $mon = $arr[1];
                        $year = $arr[2];
                    }

                    $signup_date_format = $mon . ", " . $year;

                    $conditions = array('bids.user_id' => $prj->seller_id, 'bids.project_id' => $prj->id);
                    $totbid = $this->skills_model->getBids($conditions);
                    $result_bid = $totbid->row();

                    $bid_amount = $result_bid->bid_amount;

                    if ($role_id == 1) {

                        if ($prj->is_feature == 1) {
                            $featured_project_amount = $settings['FEATURED_PROJECT_AMOUNT'] * ($buyer_project_fee / 100);
                            $provider_percentage_amount = $bid_amount * ($provider_settings_fee / 100);
                            $commision = $provider_percentage_amount * ($buyer_project_fee / 100);
                            $commision_amount = $featured_project_amount + $commision;
                        } else {
                            $provider_percentage_amount = $bid_amount * ($provider_settings_fee / 100);
                            $commision_amount = $provider_percentage_amount * ($buyer_affiliate_fee / 100);
                        }



                        // insert affiliate sales 					  
                        $clientdate = $this->config->item('clientdate');
                        $clienttime = $this->config->item('clienttime');
                        $clientbrowser = $this->config->item('clientbrowser');
                        $clientip = $this->config->item('clientip');

                        //prepare insert data
                        $insertData = array();
                        $insertData['refid'] = $refid;
                        $insertData['created_date'] = "$clientdate";
                        $insertData['created_time'] = "$clienttime";
                        $insertData['browser'] = "$clientbrowser";
                        $insertData['ipaddress'] = "$clientip";
                        $insertData['payment'] = $commision_amount;
                        $insertData['referral'] = $referral;
                        $insertData['account_type'] = $role_id;
                        $insertData['signup_date'] = $signup_date;
                        $insertData['signup_date_format'] = $signup_date_format;
                        //$insertData['is_released']='0';
                        //Add Category
                        $this->affiliate_model->addAffiliateSales($insertData);
                    }

                    if ($role_id == 2) {
                        if ($prj->is_feature == 1) {
                            $featured_project_amount = $settings['FEATURED_PROJECT_AMOUNT'] * ($seller_project_fee / 100);
                            $provider_percentage_amount = $bid_amount * ($provider_settings_fee / 100);
                            $commision = $provider_percentage_amount * ($seller_project_fee / 100);
                            $commision_amount = $featured_project_amount + $commision;
                        } else {
                            $seller_percentage_amount = $bid_amount * ($provider_settings_fee / 100);
                            $commision_amount = $seller_percentage_amount * ($seller_affiliate_fee / 100);
                        }

                        // insert affiliate sales 					  
                        $clientdate = $this->config->item('clientdate');
                        $clienttime = $this->config->item('clienttime');
                        $clientbrowser = $this->config->item('clientbrowser');
                        $clientip = $this->config->item('clientip');

                        //prepare insert data
                        $insertData = array();
                        $insertData['refid'] = $refid;
                        $insertData['created_date'] = "$clientdate";
                        $insertData['created_time'] = "$clienttime";
                        $insertData['browser'] = "$clientbrowser";
                        $insertData['ipaddress'] = "$clientip";
                        $insertData['payment'] = $commision_amount;
                        $insertData['referral'] = $referral;
                        $insertData['account_type'] = $role_id;
                        $insertData['signup_date'] = $signup_date;
                        $insertData['signup_date_format'] = $signup_date_format;
                        // $insertData['is_released']='0';
                        //print_r($insertData);
                        //Add Category
                        $this->affiliate_model->addAffiliateSales($insertData);
                    }
                } else {

                    // get user
                    $condition1 = array('users.id' => $prj->creator_id);
                    $user_data1 = $this->user_model->getUsers($condition1);
                    //$user_data_result = $user_data->result();
                    $user_data_row1 = $user_data1->row();
                    if (isset($user_data_row1->refid)) {
                        $refid = $user_data_row1->refid;
                        $role_id = $user_data_row1->role_id;
                        $referral = $user_data_row1->user_name;
                        $signup_date = $user_data_row->created;

                        $date_with_time = show_date($signup_date);
                        $arr_str = explode(',', $date_with_time);
                        $arr = explode(" ", trim($arr_str[1]));

                        for ($i = 0; $i < count($arr); $i++) {
                            $mon = $arr[1];
                            $year = $arr[2];
                        }

                        $signup_date_format = $mon . ", " . $year;

                        $conditions2 = array('bids.user_id' => $prj->seller_id, 'bids.project_id' => $prj->id);
                        $totbid = $this->skills_model->getBids($conditions2);
                        $result_bid = $totbid->row();

                        $bid_amount = $result_bid->bid_amount;

                        if ($role_id == 1) {

                            if ($prj->is_feature == 1) {
                                $featured_project_amount = $settings['FEATURED_PROJECT_AMOUNT'] * ($buyer_project_fee / 100);
                                $provider_percentage_amount = $bid_amount * ($provider_settings_fee / 100);
                                $commision = $provider_percentage_amount * ($buyer_project_fee / 100);
                                $commision_amount = $featured_project_amount + $commision;
                            } else {
                                $provider_percentage_amount = $bid_amount * ($provider_settings_fee / 100);
                                $commision_amount = $provider_percentage_amount * ($buyer_affiliate_fee / 100);
                            }


                            // insert affiliate sales 					  
                            $clientdate = $this->config->item('clientdate');
                            $clienttime = $this->config->item('clienttime');
                            $clientbrowser = $this->config->item('clientbrowser');
                            $clientip = $this->config->item('clientip');

                            //prepare insert data
                            $insertData = array();
                            $insertData['refid'] = $refid;
                            $insertData['created_date'] = "$clientdate";
                            $insertData['created_time'] = "$clienttime";
                            $insertData['browser'] = "$clientbrowser";
                            $insertData['ipaddress'] = "$clientip";
                            $insertData['payment'] = $commision_amount;
                            $insertData['referral'] = $referral;
                            $insertData['account_type'] = $role_id;
                            $insertData['signup_date'] = $signup_date;
                            $insertData['signup_date_format'] = $signup_date_format;
                            // $insertData['is_released']='0';
                            //Add Category
                            $this->affiliate_model->addAffiliateSales($insertData);
                        }

                        if ($role_id == 2) {
                            if ($prj->is_feature == 1) {
                                $featured_project_amount = $settings['FEATURED_PROJECT_AMOUNT'] * ($seller_project_fee / 100);
                                $provider_percentage_amount = $bid_amount * ($provider_settings_fee / 100);
                                $commision = $provider_percentage_amount * ($seller_project_fee / 100);
                                $commision_amount = $featured_project_amount + $commision;
                            } else {
                                $seller_percentage_amount = $bid_amount * ($provider_settings_fee / 100);
                                $commision_amount = $seller_percentage_amount * ($seller_affiliate_fee / 100);
                            }


                            // insert affiliate sales 					  
                            $clientdate = $this->config->item('clientdate');
                            $clienttime = $this->config->item('clienttime');
                            $clientbrowser = $this->config->item('clientbrowser');
                            $clientip = $this->config->item('clientip');

                            //prepare insert data
                            $insertData = array();
                            $insertData['refid'] = $refid;
                            $insertData['created_date'] = "$clientdate";
                            $insertData['created_time'] = "$clienttime";
                            $insertData['browser'] = "$clientbrowser";
                            $insertData['ipaddress'] = "$clientip";
                            $insertData['payment'] = $commision_amount;
                            $insertData['referral'] = $referral;
                            $insertData['account_type'] = $role_id;
                            $insertData['signup_date'] = $signup_date;
                            $insertData['signup_date_format'] = $signup_date_format;
                            //$insertData['is_released']='0';
                            //Add Category
                            $this->affiliate_model->addAffiliateSales($insertData);
                        }
                    }
                }
            }
        }

        if ($upProject == 1) {
            //Load Model For Mail
            $this->load->model('email_model');

            //Send Mail to Buyer
            $conditionUserMail = array('email_templates.type' => 'project_accepted_buyer');
            $result = $this->email_model->getEmailSettings($conditionUserMail);
            $rowUserMailConent = $result->row();

            $splVars = array("!seller_username" => $sellerRow->user_name, "!project_title" => $projectRow->project_name, "!seller_email" => $sellerRow->email, "!contact_url" => site_url('contact'));
            $mailSubject = $this->lang->line($rowUserMailConent->mail_subject);
            $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
            $toEmail = $buyerRow->email;
            $fromEmail = $this->config->item('site_admin_mail');

            $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);

            //Send Mail to Seller
            $conditionUserMail2 = array('email_templates.type' => 'project_accepted_seller');
            $result2 = $this->email_model->getEmailSettings($conditionUserMail2);
            $rowUserMailConent2 = $result2->row();

            $splVars2 = array("!project_title" => $projectRow->project_name, "!buyer_username" => $buyerRow->user_name, "!buyer_email" => $buyerRow->email, "!contact_url" => site_url('contact'));
            $mailSubject2 = $this->lang->line($rowUserMailConent2->mail_subject);
            $mailContent2 = strtr($rowUserMailConent2->mail_body, $splVars2);
            $toEmail2 = $sellerRow->email;
            $fromEmail2 = $this->config->item('site_admin_mail');
            $this->email_model->sendHtmlMail($toEmail2, $fromEmail2, $mailSubject2, $mailContent2);

            //Notification message
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('You have successfully accepted the project')));
            redirect('info/index/success');
        }
    }

//Function acceptProject End
    // --------------------------------------------------------------------

    /**
     * deny project from seller 
     *
     * @access	public for seller
     * @param	project id and checkstamp
     * @return	contents
     */
    function denyProject() {
        //Load Language
        $this->lang->load('enduser/denyProject', $this->config->item('language_code'));
        $project_id = $this->uri->segment(3, '0');
        $checkstamp = $this->uri->segment(4, '0');

        if (isset($project_id)) {
            $updateKey = array('projects.id' => $project_id);
            $updateData['notification_status'] = '1';
            $this->skills_model->updateProjects(NULL, $updateData, $updateKey);
        }

        $conditions = array('projects.id' => $project_id, 'projects.checkstamp' => $checkstamp, 'projects.project_status' => '1', 'projects.seller_id' => $this->loggedInUser->id);
        $project = $this->skills_model->getProjects($conditions);
        $projectRow = $project->row();

        if (!is_object($projectRow)) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You cannot deny this project')));
            redirect('info');
        }

        $buyerId = $projectRow->creator_id;
        $sellerId = $projectRow->seller_id;

        $conditions2 = array('users.id' => $buyerId);
        $buyer = $this->user_model->getUsers($conditions2);
        $buyerRow = $buyer->row();

        $conditions3 = array('users.id' => $sellerId);
        $seller = $this->user_model->getUsers($conditions3);
        $sellerRow = $seller->row();

        $updateKey = array(
            'projects.id' => $project_id,
            'projects.checkstamp' => $checkstamp,
            'projects.seller_id' => $sellerId
        );
        $updateData = array('projects.project_status' => '0', 'projects.seller_id' => '0');
        $upProject = $this->skills_model->accpetProject($updateKey, $updateData);

        if ($upProject == 1) {
            //Load Model For Mail
            $this->load->model('email_model');

            //Send Mail to Buyer
            $conditionUserMail = array('email_templates.type' => 'project_denied_buyer');
            $result = $this->email_model->getEmailSettings($conditionUserMail);
            $rowUserMailConent = $result->row();

            $splVars = array("!provider_username" => $sellerRow->user_name, "!project_title" => $projectRow->project_name, "!contact_url" => site_url('contact'));
            $mailSubject = $this->lang->line($rowUserMailConent->mail_subject);
            $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
            $toEmail = $buyerRow->email;
            $fromEmail = $this->config->item('site_admin_mail');
            $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);

            //Notification message
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('You have successfully denied the project')));
            redirect('info/index/success');
        }
    }

//Function denyProject End
    // --------------------------------------------------------------------

    /**
     * Accept project from Buyer who accepted your bid
     *
     * @access	public
     * @param	project id and checkstamp
     * @return	contents
     */
    function postProject() {
        //language file
        $this->lang->load('enduser/review', $this->config->item('language_code'));

        //Check for Login details.
        if (!isset($this->loggedInUser->id)) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be login access to this page')));
            redirect('info');
        }
        if ($this->loggedInUser->role_id) {
            if ($this->loggedInUser->role_id == '2') {
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a buyer to post projects')));
                redirect('info');
            }
        }
        $project_id = $this->uri->segment(3, 0);

        //Initital payment settings for projects
        $paymentSettings = $this->settings_model->getSiteSettings();
        $this->outputData['feature_project'] = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
        $this->outputData['urgent_project'] = $paymentSettings['URGENT_PROJECT_AMOUNT'];
        $this->outputData['hide_project'] = $paymentSettings['HIDE_PROJECT_AMOUNT'];

        //Get the project details for post similar projects
        $conditions = array('projects.id' => $project_id);
        $postSimilar = $this->skills_model->getUsersproject($conditions);
        $this->outputData['postSimilar'] = $postSimilar;

        //Laod the categories into the view page
        $this->outputData['groupsWithCategories'] = $this->skills_model->getGroupsWithCategory();
        $this->load->view('project/postProject', $this->outputData);
    }

//Function acceptProject End
    // --------------------------------------------------------------------

    /**
     * manage project from Buyer who post project
     *
     * @access	public for buyer
     * @param	project id 
     * @return	contents
     */
    function manageProject() {

        //Load Language
        $this->lang->load('enduser/createProject', $this->config->item('language_code'));
        $this->lang->load('enduser/project', $this->config->item('language_code'));
        $this->lang->load('enduser/review', $this->config->item('language_code'));
        //load validation libraray
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Check for Login details.
        if (!isset($this->loggedInUser->id)) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be login access to this page')));
            redirect('info');
        }

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        if ($this->loggedInUser->role_id) {
            if ($this->loggedInUser->role_id == '2') {
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a buyer to post projects')));
                redirect('info');
            }
        }
        //Get Groups
        $this->outputData['groupsWithCategories'] = $this->skills_model->getGroupsWithCategory();

        if ($this->uri->segment(3, 0))
            $project_id = $this->uri->segment(3, 0);
        else
            $project_id = $this->input->post('projectid');
        //Get the project details for post similar projects
        $conditions = array('projects.id' => $project_id, 'projects.creator_id' => $this->loggedInUser->id);
        $postSimilar = $this->skills_model->getUsersproject($conditions);
        $this->outputData['postSimilar'] = $postSimilar;
        $res = $postSimilar->num_rows();
        //pr($postSimilar->result());
        if ($res <= 0) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('Your are not allow to manage this project')));
            redirect('project/view/' . $project_id);
        }

        if ($this->input->post('createProject')) {
            $this->form_validation->set_rules('budget_min', 'lang:budget_min_validation', 'trim|integer|is_natural|abs');
            $this->form_validation->set_rules('budget_max', 'lang:budget_max_validation', 'trim|integer|is_natural|integer|abs');
            $this->form_validation->set_rules('attachment', 'lang:attachment_validation', 'callback_attachment_check');
            $this->form_validation->set_rules('categories[]', 'lang:categories_validation', 'trim|integer|is_natural|abs|xss_clean|callback__maxvalcheckcat');

            if ($this->input->post('is_private')) {
                $this->form_validation->set_rules('private_list', 'lang:private_list', 'required|trim|');
            }
            if ($this->form_validation->run()) {
                //Initital payment settings for projects
                $paymentSettings = $this->settings_model->getSiteSettings();
                $this->outputData['feature_project'] = $feature_project = $paymentSettings['FEATURED_PROJECT_AMOUNT'];
                $this->outputData['urgent_project'] = $urgent_project = $paymentSettings['URGENT_PROJECT_AMOUNT'];
                $this->outputData['hide_project'] = $hide_project = $paymentSettings['HIDE_PROJECT_AMOUNT'];
                $private_project = $paymentSettings['PRIVATE_PROJECT_AMOUNT'];

                $res = $postSimilar->num_rows();

                if ($res <= 0) {
                    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('Your are not allow to manage this project')));
                    redirect('project/view/' . $project_id);
                }

                //Create Projects before it for update the projects datas for manage option	   
                if ($this->input->post('update') == '0') {
                    if ($this->input->post('projectid')) {

                        //initial value set for check the featured , urgent, hide projects
                        $settingAmount = 0;

                        //check the values for featured, urgent, hide projects
                        if ($this->input->post('is_feature')) {
                            $settingAmount = $settingAmount + $feature_project;
                        }
                        if ($this->input->post('is_urgent')) {
                            $settingAmount = $settingAmount + $urgent_project;
                        }
                        if ($this->input->post('is_hide_bids')) {
                            $settingAmount = $settingAmount + $hide_project;
                        }
                        if ($this->input->post('is_private')) {
                            $settingAmount = $settingAmount + $private_project;
                        }
                        //Check User Balance
                        $condition_balance = array('user_balance.user_id' => $this->loggedInUser->id);
                        $results = $this->transaction_model->getBalance($condition_balance);

                        //If Record already exists
                        if ($results->num_rows() > 0) {
                            //get balance detail
                            $rowBalance = $results->row();

                            $this->outputData['userAvailableBalance'] = $rowBalance->amount;
                        }
                        if ($this->input->post('is_hide_bids', TRUE) or $this->input->post('is_urgent', TRUE) or $this->input->post('is_feature', TRUE) or $this->input->post('is_private', TRUE)) {
                            $withdrawvalue = $rowBalance->amount - ( $settingAmount + $paymentSettings['PAYMENT_SETTINGS'] );

                            if ($rowBalance->amount == 0) {
                                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('your not having sufficient balance')));
                                redirect('info');
                            } else if ($withdrawvalue < 0) {
                                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('your not having sufficient balance')));
                                redirect('info');
                            } else {
                                //Check User Balance
                                //Update Amount	
                                $updateKey = array('user_balance.user_id' => $this->loggedInUser->id);
                                $updateData = array();
                                $updateData['amount'] = $rowBalance->amount - $settingAmount;
                                $results = $this->transaction_model->updateBalance($updateKey, $updateData);

                                //Insert transaction for post projects
                                $insertTransaction = array();
                                $insertTransaction['creator_id'] = $this->loggedInUser->id;
                                $insertTransaction['type'] = $this->lang->line('Project Fee');
                                $insertTransaction['amount'] = $settingAmount;
                                $insertTransaction['transaction_time'] = get_est_time();
                                $insertTransaction['status'] = 'Completed'; //Can Be success,failed,pending

                                if ($this->input->post('is_feature')) {
                                    $insertTransaction['description'] = $this->lang->line('Project Fee for Feature Project');
                                }
                                if ($this->input->post('is_urgent')) {
                                    if (($insertTransaction['description']) != '') {
                                        $insertTransaction['description'] .=$this->lang->line('plus');
                                    }
                                    $insertTransaction['description'] = $this->lang->line('Project Fee for Urgent Project');
                                }
                                if ($this->input->post('is_hide_bids')) {
                                    if (($insertTransaction['description']) != '') {
                                        $insertTransaction['description'] .=$this->lang->line('plus');
                                    }
                                    $insertTransaction['description'] = $this->lang->line('Project Fee for hide bids Project');
                                }
                                if ($this->input->post('is_private')) {
                                    if (($insertTransaction['description']) != '') {
                                        $insertTransaction['description'] .=$this->lang->line('plus');
                                    }
                                    $insertTransaction['description'] = $this->lang->line('Project Fee for Private Project');
                                }
                                $insertTransaction['description'].= $this->lang->line('Project Fee');

                                if ($this->loggedInUser->role_id == '1') {
                                    $insertTransaction['buyer_id'] = $this->loggedInUser->id;
                                    $insertTransaction['user_type'] = $this->lang->line('Project Fee for Bid');
                                }
                                if ($this->loggedInUser->role_id == '2') {
                                    $insertTransaction['provider_id'] = $this->loggedInUser->id;
                                    $insertTransaction['user_type'] = $this->lang->line('Project Fee for Bid');
                                }
                                $this->load->model('transaction_model');
                                $this->transaction_model->addTransaction($insertTransaction);
                            }
                        }

                        $insertData = array();
                        $insertData['project_name'] = $this->input->post('projectName');
                        $insertData['description'] = $this->input->post('description');

                        if (isset($this->data['file'])) {
                            $insertData['attachment_url'] = $this->data['file']['file_name'];
                            $insertData['attachment_name'] = $this->data['file']['orig_name'];
                        }

                        if ($this->input->post('update') == '0') {
                            $insertData['description'] = $this->input->post('description') . '<br/>';
                            $insertData['description'] .= $this->input->post('add_description');
                        }
                        else
                            $insertData['description'] = $this->input->post('description');

                        $insertData['budget_min'] = $this->input->post('budget_min');
                        $insertData['budget_max'] = $this->input->post('budget_max');
                        if ($this->input->post('is_feature'))
                            $insertData['is_feature'] = $this->input->post('is_feature');
                        if ($this->input->post('is_urgent'))
                            $insertData['is_urgent'] = $this->input->post('is_urgent');
                        if ($this->input->post('is_hide_bids'))
                            $insertData['is_hide_bids'] = $this->input->post('is_hide_bids');
                        if ($this->input->post('is_private')) {
                            $insertData['is_private'] = $this->input->post('is_private');
                        }
                        $insertData['creator_id'] = $this->loggedInUser->id;
                        $insertData['created'] = get_est_time();
                        $insertData['enddate'] = get_est_time() + (7 * 86400);
                        $result = '0';

                        if ($this->input->post('categories')) {
                            $categories = $this->input->post('categories');

                            //Work With Project Categories
                            $project_categoriesNameArray = $this->skills_model->convertCategoryIdsToName($categories);
                            $project_categoriesNameString = implode(',', $project_categoriesNameArray);
                            $insertData['project_categories'] = $project_categoriesNameString;
                        }

                        //Update the data
                        $project = $this->input->post('projectid');
                        $condition = array('projects.id' => $project);

                        $this->skills_model->manageProjects($insertData, $condition);

                        if ($this->input->post('is_private')) {

                            $private_users = $this->input->post('private_list', TRUE);

                            if ($private_users != '') {
                                $private_users_array = explode("\n", $private_users);
                                $condition = '`role_id`=2';
                                foreach ($private_users_array as $val) {
                                    $private_users_array1[] = " `user_name`='" . $val . "'";
                                }
                                $private_users_str1 = implode(' OR ', $private_users_array1);
                                $private_users_cond = $condition . ' AND (' . $private_users_str1 . ')';
                                //$sel_users=$this->user_model->getUsersfromusername($condition=array(),$private_users_array,NULL);
                                $sel_users = $this->user_model->getUsersfromusername($private_users_cond);
                                //pr($sel_users->result());
                                if ($sel_users->num_rows() > 0) {
                                    foreach ($sel_users->result() as $users) {
                                        $pusers[] = $users->id;
                                    }
                                    $pusers = array_unique($pusers);
                                    $pusers1 = implode(',', $pusers);
                                    $data = array('private_users' => $pusers1);
                                    $condition = array('id' => $project);
                                    $table = 'projects';

                                    $this->common_model->updateTableData($table, NULL, $data, $condition);
                                    //insert project_invitation table for private users
                                    $insertprivate = array('project_id' => $project, 'sender_id' => $this->loggedInUser->id, 'invite_date' => get_est_time(), 'notification_status' => '0');
                                    $invitetable = 'project_invitation';
                                    foreach ($pusers as $val) {
                                        $insertprivate['receiver_id'] = $val;

                                        $this->common_model->insertData($invitetable, $insertprivate);
                                    }
                                }
                            }
                        }

                        if ($this->input->post('is_private')) {
                            //Send Mail
                            $conditionProviderMail = array('email_templates.type' => 'private_project_provider');
                            $resultProvider = $this->email_model->getEmailSettings($conditionProviderMail);
                            $resultProvider = $resultProvider->row();

                            $projectpage = site_url('project/view/' . $project);

                            $splVars_provider = array("!site_name" => $this->config->item('site_title'), "!projectname" => $this->input->post('projectName'), "!creatorname" => $this->loggedInUser->user_name, "!profile" => $project_categoriesNameString, "!projectid" => $project, "!date" => get_datetime(time()), "!projecturl" => $projectpage,);


                            //pr($sel_users->result());
                            //sending emailto all the providers
                            if ($private_users != '') {

                                if ($sel_users->num_rows() > 0) {
                                    foreach ($sel_users->result() as $users) {
                                        $insertMessageData['project_id'] = $project;
                                        $insertMessageData['to_id'] = $users->id;
                                        $insertMessageData['from_id'] = $this->loggedInUser->id;
                                        $insertMessageData['message'] = "Private Project Notification --> You are Invited for the private project<br/>Follow the link given below to view the project<br/>" . site_url('project/view/' . $project);
                                        $insertMessageData['created'] = get_est_time();
                                        //pr($insertMessageData); exit;
                                        $this->messages_model->postMessage($insertMessageData);

                                        if ($users->email != '') {
                                            $toEmail_provider = $users->email;
                                            $fromEmail_provider = $this->config->item('site_admin_mail');


                                            $selusernames[] = $users->user_name;
                                            $splVars_provider['!username'] = $users->user_name;
                                            $mailSubject_provider = strtr($resultProvider->mail_subject, $splVars_provider);
                                            $mailContent_provider = strtr($resultProvider->mail_body, $splVars_provider);
                                            $this->email_model->sendHtmlMail($toEmail_provider, $fromEmail_provider, $mailSubject_provider, $mailContent_provider);
                                        }
                                    }
                                }
                            }
                        }
                        if ($this->input->post('is_private')) {
                            $conditionUserMail = array('email_templates.type' => 'privateproject_post');
                            $result = $this->email_model->getEmailSettings($conditionUserMail);
                            $rowUserMailConent = $result->row();
                            $splVars = array("!site_name" => $this->config->item('site_title'), "!projectname" => $this->input->post('projectName'), "!username" => $this->loggedInUser->user_name, "!profile" => $project_categoriesNameString, "!projectid" => $project, "!projectid" => $project, "!date" => get_datetime(time()));
                            if ($private_users != '') {
                                if ($sel_users->num_rows() > 0) {
                                    $selusernamesstr = implode(",", $selusernames);
                                } else {
                                    $selusernamesstr = '';
                                }
                            } else {
                                $selusernamesstr = '';
                            }
                            $splVars['!privateproviders'] = $selusernamesstr;
                            $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                            $mailContent = strtr($rowUserMailConent->mail_body, $splVars);

                            $toEmail = $this->loggedInUser->email;
                            $fromEmail = $this->config->item('site_admin_mail');
                            $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                        } else {
                            //Send Mail
                            $conditionUserMail = array('email_templates.type' => 'projectpost_notification');
                            $result = $this->email_model->getEmailSettings($conditionUserMail);
                            $rowUserMailConent = $result->row();
                            $splVars = array("!site_name" => $this->config->item('site_title'), "!projectname" => $insertData['project_name'], "!username" => $this->loggedInUser->user_name, "!profile" => $project_categoriesNameString, "!projectid" => $this->db->insert_id(), "!projectid" => $this->db->insert_id(), "!date" => get_datetime(time()));
                            $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                            $mailContent = strtr($rowUserMailConent->mail_body, $splVars);

                            $toEmail = $this->loggedInUser->email;
                            $fromEmail = $this->config->item('site_admin_mail');
                            $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                        }

                        //Notification message
                        //Load Model For Mail
                        $this->load->model('email_model');

                        //Send Mail
                        $conditionUserMail = array('email_templates.type' => 'projectpost_notification');
                        $result = $this->email_model->getEmailSettings($conditionUserMail);
                        $rowUserMailConent = $result->row();
                        $splVars = array("!site_name" => $this->config->item('site_title'), "!username" => $this->loggedInUser->user_name, "!projectid" => $this->db->insert_id(), "!date" => get_datetime(time()));
                        $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                        $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                        $toEmail = $this->loggedInUser->email;
                        $fromEmail = $this->config->item('site_admin_mail');

                        $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);

                        $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('Your Project has been Updated Successfully')));
                        redirect('buyer/viewMyProjects');
                    }
                }
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('Your Project has been Updated Successfully')));
                redirect('buyer/viewMyProjects');
            }
        }
        $this->load->view('project/manageProject', $this->outputData);
    }

//Function acceptProject End
    // --------------------------------------------------------------------

    /**
     * Post report regardign the project violation
     *
     * @access	public for seller
     * @param	project id
     * @return	contents
     */
    function postReport() {
        //Load Language
        $this->lang->load('enduser/createProject', $this->config->item('language_code'));
        $project_id = $this->uri->segment(3, 0);
        $conditions = array('projects.id' => $project_id);
        $postSimilar = $this->skills_model->getUsersproject($conditions);
        $this->outputData['postSimilar'] = $postSimilar;
        $res = $postSimilar->num_rows();
        if ($this->input->post('submitReport')) {
            $insertData['id'] = '';
            $insertData['project_id'] = $project_id;
            $insertData['project_name'] = $this->input->post('projectname');
            $insertData['post_id'] = $this->loggedInUser->id;
            $insertData['post_name'] = $this->loggedInUser->user_name;
            $insertData['comment'] = $this->input->post('report');
            $insertData['report_date'] = get_est_time();
            $insertData['report_type'] = 'Project Report';

            //insert the report contents into the project_reports table
            $this->skills_model->insertReport($insertData);
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('Your report has been send successfully')));
            redirect('project/postReport/' . $project_id);
        }
        $this->load->view('project/projectReport', $this->outputData);
    }

//Function acceptProject End
    // --------------------------------------------------------------------

    /**
     * Post project bid report violation
     *
     * @access	public for buyer
     * @param	project id 
     * @return	contents
     */
    function postBidReport() {
        //Load Language
        $this->lang->load('enduser/createProject', $this->config->item('language_code'));

        if ($this->uri->segment(3)) {
            $bid_id = $this->uri->segment(3);
            //Get the bids details
            $bid_condition = array('bids.id' => $bid_id);
            $getBids = $this->skills_model->getBids($bid_condition);
            $this->outputData['getBids'] = $getBids;
            $getBids = $getBids->row();

            //Get projects details
            $project_condition = array('projects.id' => $getBids->project_id);
            $postSimilar = $this->skills_model->getUsersproject($project_condition);
            $this->outputData['postSimilar'] = $postSimilar;

            //Get users details
            $user_condition = array('users.id' => $getBids->user_id);
            $getUsers = $this->user_model->getUsers($user_condition);
            $this->outputData['getUsers'] = $getUsers;

            $res = $postSimilar->num_rows();
        }
        if ($this->input->post('submitReport')) {
            $insertData['id'] = '';
            $project_id = $this->input->post('projectid');
            $insertData['project_id'] = $project_id;
            $insertData['project_name'] = $this->input->post('projectname');
            $insertData['post_id'] = $this->loggedInUser->id;
            $insertData['post_name'] = $this->loggedInUser->user_name;
            $insertData['comment'] = $this->input->post('report');
            $insertData['report_date'] = get_est_time();
            $insertData['report_type'] = 'Bid Report';
            //insert the report contents into the project_reports table
            $this->skills_model->insertReport($insertData);
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('Your report has been send successfully')));
        }
        $this->load->view('project/bidProjectReport', $this->outputData);
    }

//Function acceptProject End
    // --------------------------------------------------------------------

    /**
     * Create invoice report for the logged user
     *
     * @access	private
     * @param	project id and checkstamp
     * @return	contents
     */
    function invoice() {
        //Innermenu tab selection
        $this->outputData['innerClass8'] = '';
        $this->outputData['innerClass8'] = 'selected';

        if (!isset($this->loggedInUser->id)) {

            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You can not access to this page')));
            redirect('info');
        }
        $check = '0';
        //Check will assign to 1 while the invoice submittion
        if ($this->input->post('invoice')) {

            $check = '1';
        }

        //Load Language
        $this->lang->load('enduser/createProject', $this->config->item('language_code'));
        $this->lang->load('enduser/invoice', $this->config->item('language_code'));
        if ($check == '0') {
            $project_id = $this->uri->segment(3, 0);

            //Get the project details for post similar projects
            $conditions = array('projects.creator_id' => $this->loggedInUser->id, 'projects.project_status' => '2', 'projects.project_paid' => '1');
            $invoiceProject = $this->skills_model->getUsersproject($conditions);
            $this->outputData['invoiceProject'] = $invoiceProject;
            $this->outputData['postSimilar'] = $invoiceProject;
            $count = $invoiceProject->num_rows();
            $res = $invoiceProject->num_rows();
        }

        //Check User Balance
        $condition_balance = array('user_balance.user_id' => $this->loggedInUser->id);
        $results = $this->transaction_model->getBalance($condition_balance);

        //If Record already exists
        if ($results->num_rows() > 0) {
            //get balance detail
            $rowBalance = $results->row();
            $this->outputData['userAvailableBalance'] = $rowBalance->amount;
        }

        //Load the view for the invoice
        if ($check == '0') {
            $this->load->view('project/projectInvoice', $this->outputData);
        } else {
            $this->outputData['project_name'] = $this->input->post('project_name');
            $this->outputData['user_name'] = $this->input->post('user_name');
            $this->outputData['bidsProjects'] = $this->input->post('invoice');
            $this->outputData['invoice_no'] = $this->input->post('invoice_no');
            $this->outputData['bidsProjects'] = $this->skills_model->getBidsproject();
            $this->load->view('project/invoice', $this->outputData);
        }
    }

//Function invoice End
    // --------------------------------------------------------------------

    /**
     * Create invite report for the logged user
     *
     * @access	private
     * @param	project id and checkstamp
     * @return	contents
     */
    function inviteUser() {
        //Load Language
        $this->lang->load('enduser/userlist', $this->config->item('language_code'));
        if ($this->loggedInUser) {
            $userid = $this->loggedInUser->id;
            $condition = array('projects.creator_id' => $userid);
            $res = $this->skills_model->getUsersproject($condition);
            if ($res->num_rows() > 0) {
                $condition = array('user_list.creator_id' => $this->loggedInUser->id);
                $this->outputData['favouriteList'] = $this->user_model->getFavourite($condition);
                $this->load->view('buyer/inviteSeller', $this->outputData);
            } else {
                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be post project to invite sellers')));
                redirect('info');
            }
        } else {
            //Notification message
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged to invite sellers')));
            redirect('info');
        }
    }

//Function inviteUser End
    // --------------------------------------------------------------------

    /**
     * Check and close the projects if their bidding end date is expired.
     *
     * @access	private
     * @param	project id and checkstamp
     * @return	contents
     */
    function biddingEndCheck() {
        $projects = $this->skills_model->getProjects();
        foreach ($projects->result() as $res) {
            $diff = $res->enddate - get_est_time();
            if ($diff == 0) {
                $updateKey = array('projects.id' => $res->id);
                $updateData = array('projects.project_status' => '3');
                $this->skills_model->updateProjects(NULL, $updateData, $updateKey);

                //Load Model For Mail
                $this->load->model('email_model');

                //Send Mail
                $conditionUserMail = array('email_templates.type' => 'project_cancelled');
                $result = $this->email_model->getEmailSettings($conditionUserMail);
                $rowUserMailConent = $result->row();

                $splVars = array("!buyer_name" => $res->user_name, "!project_name" => $res->project_name, "!contact_url" => site_url('contact'));
                $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                $toEmail = $res->email;
                $fromEmail = $this->config->item('site_admin_mail');
                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
            }
        }
    }

//Function biddingEndCheck End
    // --------------------------------------------------------------------

    /**
     * Sending new project notifications to Providers
     *
     * @access	private
     * @param	project id and checkstamp
     * @return	contents
     */
    function newProjectsNotify() {
        //Load Models
        $this->load->model('search_model');

        $yesterday = date('j/n/Y', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));

        $conditions = array('users.role_id' => '2', 'users.user_status' => '1', 'user_categories.user_categories !=' => '');

        $users = $this->user_model->getUsersWithCategories($conditions);
        $prids = array();
        $i = 0;
        foreach ($users->result() as $user) {
            $cate = explode(",", $user->user_categories);
            //Get projects by categories
            foreach ($cate as $cat) {
                $cond = array('categories.id' => $cat);
                $res = $this->skills_model->getCategories($cond);
                $row = $res->row();
                $cname = $row->category_name;
                $like = array('projects.project_categories' => $cname);
                $conditions2 = array("FROM_UNIXTIME( projects.created, '%e/%c/%Y' ) = " => $yesterday, 'projects.project_status' => '0');
                $projects = $this->search_model->getProjects($conditions2, 'projects.id', $like);
                foreach ($projects->result() as $prid) {
                    $prids[$i] = $prid->id;
                    $i++;
                }
            }
            $prids = array_unique($prids);
            $mailSubject = $this->config->item('site_title') . " Project Notice";
            $mailContent = "The following " . count($prids) . " projects were recently added to " . $this->config->item('site_title') . " and match your expertise:<br><br>";
            foreach ($prids as $prj) {
                $condition3 = array('projects.id' => $prj);
                $mpr = $this->skills_model->getProjects($condition3);
                $prj = $mpr->row();
                $mailContent .= $prj->project_name . " (Posted by " . $prj->user_name . ", " . get_datetime($prj->created) . ", Job type:" . $prj->project_categories . ")" . "<br>" . site_url('project/view/' . $prj->id) . "<br><br>";
            }
            //Send mail
            $toEmail = $user->email;
            $fromEmail = $this->config->item('site_admin_mail');
            $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
        }
    }

//Function biddingEndCheck End
    // --------------------------------------------------------------------

    /**
     * Extend project bid
     *
     * @access	private
     * @param	project id and checkstamp
     * @return	contents
     */
    function extendBid() {
        //Load Language
        $this->lang->load('enduser/viewProject', $this->config->item('language_code'));
        if ($this->input->post('extend')) {
            $prjid = $this->input->post('projectid');
            $condition2 = array('projects.id' => $prjid);
            $res = $this->skills_model->getProjects($condition2);
            $row = $res->row();
            $left = days_left($row->enddate, $prjid);
            if ($left == 'Closed')
                $enddate = get_est_time() + (7 * 86400);
            else {
                $today = time();
                $lastday = $row->enddate;
                $left = $lastday - $today;
                $val = date('j', $left);
                $open = $val + 7;
                $enddate = get_est_time() + ($open * 86400);
            }

            $updateKey = array('projects.id' => $this->input->post('projectid'));
            $updateData = array('projects.enddate' => $enddate, 'projects.project_status' => '0');
            $this->skills_model->updateProjects(NULL, $updateData, $updateKey);

            //Notification message
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('Your project bid has been extended')));
            redirect('buyer/viewMyProjects');
        }
        $prjid = $this->uri->segment(3, '0');
        $condition = array('projects.id' => $prjid);
        $this->outputData['project'] = $this->skills_model->getProjects($condition);
        $this->load->view('buyer/extend', $this->outputData);
    }

//Function extendBid End
//------------------------------------------------------------------------------------------

    /**
     * calculate the consolidate bids details after 12 hr and send email to the buyer
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function bidConsolidate() {
        //Get all users details
        $projects = $this->skills_model->getProjects();
        foreach ($projects->result() as $res) {
            $records = '';
            $diff = count_days($res->created, get_est_time());
            if ($diff > 0) {
                $projectid = $res->id;
                $projectname = $res->project_name;
                //Get all bids details for the project
                $bid_condition = array('bids.project_id' => $projectid);
                $bids = $this->skills_model->getBids($bid_condition);
                if (isset($bids) and count($bids->result()) > 0) {
                    $i = 1;
                    $records .= '<table border="1"><tr><th align="center">Sl.No</th><th width="300">Project Name</th>	<th width="250">Username</th> <th width="100" align="center">Bid Amount</th> <th width="250" align="center">Bid Post Time</th></tr>';
                    foreach ($bids->result() as $bids) {
                        $user_condition = array('users.id' => $bids->user_id);
                        $users = $this->user_model->getUsers($user_condition);
                        $user = $users->row();
                        $records .= '<tr><td align="center">' . $i++ . '</td><td>' . $res->project_name . '</td><td>' . $user->user_name . '</td><td align="center">' . '$ ' . $bids->bid_amount . '</td><td align="center">' . get_datetime($bids->bid_time) . '</td></tr>';
                    }

                    $records .='</table>';

                    $user_condition = array('users.id' => $res->creator_id);
                    $creator = $this->user_model->getUsers($user_condition);
                    $creator = $creator->row();
                    //Send Mail to project creator
                    $conditionUserMail = array('email_templates.type' => 'consolidate_bids');
                    $result = $this->email_model->getEmailSettings($conditionUserMail);
                    $rowUserMailConent = $result->row();
                    //Update the details 
                    $splVars = array("!projectname" => '<a href="' . site_url('project/view/' . $res->id) . '">' . $res->project_name . '</a>', "!username" => $creator->user_name, "!contact_url" => site_url('contact'), "!site_url" => site_url(), '!site_name' => $this->config->item('site_title'), '!records' => $records);
                    $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                    $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                    $toEmail = $creator->email;
                    $fromEmail = $this->config->item('site_admin_mail');
                    $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                }
            }
        }
    }

//Function bidConsolidate end
    // --------------------------------------------------------------------

    /**
     * Sending new project notifications every hour to Providers
     *
     * @access	private
     * @param	project id and checkstamp
     * @return	contents
     */
    function hourlyProjectsNotify() {
        //Load Models
        $this->load->model('search_model');

        $prev_hour = date('j/n/Y H', get_est_time() - (60 * 60));

        $conditions = array('users.role_id' => '2', 'users.user_status' => '1', 'user_categories.user_categories !=' => '', 'users.project_notify' => 'Hourly');

        $users = $this->user_model->getUsersWithCategories($conditions);

        $prids = array();
        $i = 0;
        foreach ($users->result() as $user) {
            $cate = explode(",", $user->user_categories);
            //Get projects by categories
            foreach ($cate as $cat) {
                $cond = array('categories.id' => $cat);
                $res = $this->skills_model->getCategories($cond);
                $row = $res->row();
                $cname = $row->category_name;
                $like = array('projects.project_categories' => $cname);
                $conditions2 = array("FROM_UNIXTIME( projects.created, '%e/%c/%Y %H' ) = " => $prev_hour, 'projects.project_status' => '0');
                $projects = $this->search_model->getProjects($conditions2, 'projects.id', $like);
                //Get projects
                foreach ($projects->result() as $prid) {
                    $prids[$i] = $prid->id;
                    $i++;
                }
            }
            //Check if projects are available to send notifications
            if (count($prids) > 0) {
                $prids1 = array_unique($prids);
                $mailSubject = $this->config->item('site_title') . " Project Notice";
                $mailContent = "The following " . count($prids1) . " projects were recently added to " . $this->config->item('site_title') . " and match your expertise:<br><br>";
                foreach ($prids as $prj) {
                    $condition3 = array('projects.id' => $prj);
                    $mpr = $this->skills_model->getProjects($condition3);
                    $prj = $mpr->row();
                    $mailContent .= $prj->project_name . " (Posted by " . $prj->user_name . ", " . get_datetime($prj->created) . ", Job type:" . $prj->project_categories . ")" . "<br>" . site_url('project/view/' . $prj->id) . "<br><br>";
                }
                //Send mail
                $toEmail = $user->email;
                $fromEmail = $this->config->item('site_admin_mail');
                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
            }
        }
    }

//Function hourlyProjectsNotify End
    // -----------------------------------------------------------------------------------------------------------------
    function selProvider() {

        //Load Language
        $this->lang->load('enduser/pickProvider', $this->config->item('language_code'));
        if ($this->uri->segment(3, 0)) {
            $bidid = $this->uri->segment(3, 0);
            $conditions = array('bids.id' => $bidid);
            $up = $this->skills_model->awardProject($conditions);
            if ($up == 1) {
                //Load Model For Mail
                $this->load->model('email_model');
                $bidres = $this->skills_model->getProjectByBid(array('bids.id' => $bidid));
                $bidres = $bidres->row();

                //Get all user post bids 
                $condition = array('bids.project_id' => $bidres->id, 'bids.user_id !=' => $bidres->seller_id);
                $bids = $this->skills_model->getBids($condition);
                foreach ($bids->result() as $bids) {
                    $user_condition = array('users.id' => $bids->user_id);
                    $users = $this->user_model->getUsers($user_condition);
                    $users = $users->row();

                    //Send Mail
                    $conditionUserMail = array('email_templates.type' => 'project_end');
                    $result = $this->email_model->getEmailSettings($conditionUserMail);
                    $rowUserMailConent = $result->row();
                    $splVars = array("!projectname" => $bidres->project_name, "!sitetitle" => site_url(), "!contact_url" => site_url('contact'));
                    $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                    $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                    $toEmail = $users->email;
                    $fromEmail = $this->config->item('site_admin_mail');
                    $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                }
                //Update the notification status for the proejct to zero
                $updateKey = array('projects.id' => $bidres->id);
                $updateData['notification_status'] = '0';
                $this->skills_model->updateProjects(NULL, $updateData, $updateKey);

                //Send Mail
                $conditionUserMail = array('email_templates.type' => 'awardBid');
                $result = $this->email_model->getEmailSettings($conditionUserMail);
                $rowUserMailConent = $result->row();
                $splVars = array("!project_title" => $bidres->project_name, "!bid_url" => site_url('project/acceptProject/' . $bidres->id . "/" . $bidres->checkstamp), "!deny_url" => site_url('project/denyProject/' . $bidres->id . "/" . $bidres->checkstamp), "!contact_url" => site_url('contact'));
                $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                $toEmail = $bidres->email;
                $fromEmail = $this->config->item('site_admin_mail');
                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);

                $resuser = $this->user_model->getUsers(array('users.id' => $this->loggedInUser->id));
                $tuserdetails = $resuser->row();
                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('You have successfully awarded the project')));
                redirect('/project/view/' . $bidres->id);
            }
        }
    }

    /**
     * Buyer cencel the proejct only for open proejcts
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function cancelProject() {
        if ($this->uri->segment(3, 0)) {

            $projectid = $this->uri->segment(3, 0);
            $updatekey = array('projects.id' => $projectid);

            //Get the cancelled proejcts details
            $condition = array('projects.id' => $projectid);
            $projects = $this->skills_model->getProjects($condition);
            $this->outputData['projects'] = $projects;
            $projects = $projects->row();

            if ($this->input->post('delete')) {
                //Set the proejct status as cancel made by buyer
                $updateData = array('projects.project_status' => '3');
                $projects = $this->skills_model->updateProjects(NULL, $updateData, $updatekey);

                //Get all bid post users to the particular project
                $condition = array('projects.id' => $projectid);
                $getBids = $this->skills_model->getBids($condition);
                if ($getBids->num_rows() > 0) {
                    foreach ($getBids->result() as $user) {
                        $user_condition = array('users.id' => $user->user_id);
                        $usersList = $this->user_model->getUsers($user_condition);
                        $usersList = $usersList->row();
                        //Get projects details
                        $condition = array('projects.id' => $this->uri->segment(3, 0));
                        $projects = $this->skills_model->getProjects($condition);
                        $projects = $projects->row();
                        //Send Mail to project creator
                        $conditionUserMail = array('email_templates.type' => 'project_cancel');
                        $result = $this->email_model->getEmailSettings($conditionUserMail);
                        $rowUserMailConent = $result->row();
                        $splVars = array("!projectname" => '<a href="' . site_url('project/view/' . $projects->id) . '">' . $projects->project_name . '</a>', "!username" => $usersList->user_name, "!contact_url" => site_url('contact'), "!site_url" => site_url(), '!site_name' => $this->config->item('site_title'), "!projectid" => $projects->id, "!creatorname" => $this->loggedInUser->user_name);

                        $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                        $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                        $toEmail = $this->loggedInUser->email;
                        $fromEmail = $this->config->item('site_admin_mail');
                        $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                    }
                }

                //Get projects details
                $condition = array('projects.id' => $this->uri->segment(3, 0));
                $projects = $this->skills_model->getProjects($condition);
                $projects = $projects->row();
                //Send Mail to project creator
                $conditionUserMail = array('email_templates.type' => 'project_cancel');
                $result = $this->email_model->getEmailSettings($conditionUserMail);
                $rowUserMailConent = $result->row();
                $splVars = array("!projectname" => '<a href="' . site_url('project/view/' . $projects->id) . '">' . $projects->project_name . '</a>', "!username" => $this->loggedInUser->user_name, "!contact_url" => site_url('contact'), "!site_url" => site_url(), '!site_name' => $this->config->item('site_title'), "!projectid" => $projects->id, "!creatorname" => $this->loggedInUser->user_name);
                $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                $toEmail = $this->loggedInUser->email;
                $fromEmail = $this->config->item('site_admin_mail');
                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                redirect('buyer/viewMyProjects');
            } else if ($this->input->post('viewProject')) {
                redirect('buyer/viewMyProjects');
            } else {
                $this->load->view('project/deleteProject', $this->outputData);
            }
        }
    }

    //check max value
    function _maxvalcheck() {
        $min = $this->input->post('budget_min');
        $max = $this->input->post('budget_max');
        if ($min < $max) {
            return true;
        } else {
            $this->form_validation->set_message('_maxvalcheck', $this->lang->line('max_min_check'));
            return false;
        }
    }

    function _maxvalcheckcat() {

        $max = $this->input->post('categories');
        if (count($max) < 6) {
            return true;
        } else {
            $this->form_validation->set_message('categories[]', $this->lang->line('Job Type: (Make up to 5 selections.)'));
            return false;
        }
    }

    function _project_exist_check() {
        if ($this->input->post('projectName'))
            $projectName = $this->input->post('projectName');
        $this->db->where('project_name', $projectName);
        $this->db->select('project_name');
        $query = $this->db->get('projects');
        if ($query->num_rows() != 0) {
            $this->form_validation->set_message('_project_exist_check', 'This product has been posted by someone else');
            return FALSE;
        }
        else
            return TRUE;
    }
    
    public function discount_check($str) {
        var_dump($str);
        return false;
    }

}

//End  Project Class
/* End of file Project.php */
/* Location: ./app/controllers/Project.php */
?>