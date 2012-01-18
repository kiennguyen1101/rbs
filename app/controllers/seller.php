<?php

/**
 * Reverse bidding system Seller Class
 *
 * Seller related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Seller 


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
class Seller extends Controller {

    //Global variable  
    public $outputData;  //Holds the output data for each view
    public $loggedInUser;/**
     * Constructor 
     *
     * Loads language files and models needed for this controller
     */

    function Seller() {
        parent::Controller();

        //Get Config Details From Db
        $this->config->db_config_fetch();


        //Manage site Status 
        if ($this->config->item('site_status') == 1)
            redirect('offline');
        //Debug Tool
        //$this->output->enable_profiler=true;		
        //Load Models required for this controller
        $this->load->model('common_model');
        $this->load->model('user_model');
        $this->load->model('skills_model');
        $this->load->model('email_model');
        $this->load->model('certificate_model');

        //Page Title and Meta Tags
        $this->outputData = $this->common_model->getPageTitleAndMetaData();

        //Get Logged In user
        $this->loggedInUser = $this->common_model->getLoggedInUser();
        $this->outputData['loggedInUser'] = $this->loggedInUser;

        //Get Footer content
        $this->outputData['pages'] = $this->common_model->getPages();

        //Get Latest Projects
        $limit_latest = $this->config->item('latest_projects_limit');
        $limit3 = array($limit_latest);
        $this->outputData['latestProjects'] = $this->skills_model->getLatestProjects($limit3);

        //language file
        $this->lang->load('enduser/common', $this->config->item('language_code'));
        $this->lang->load('enduser/sellerConfirm', $this->config->item('language_code'));
        $this->outputData['current_page'] = 'provider';

        //Load helpers
        $this->load->helper('users');
        $this->load->helper('file');
    }

//Controller End 
    // --------------------------------------------------------------------

    /**
     * Loads Seller signUp page.
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function signUp() {
        //language file - Change this file to do display text modification
        $this->lang->load('enduser/sellerSignup', $this->config->item('language_code'));

        //load validation libraray
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        //Get Form Data	
        if ($this->input->post('sellerSignup')) {
            //Set rules
            $this->form_validation->set_rules('email', 'lang:seller_email_validation', 'required|trim|valid_email|xss_clean|callback__check_seller_email');
            if ($this->form_validation->run()) {
                if (check_form_token() === false) {
                    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('token_error')));
                    redirect('seller/signUp');
                }
                $insertData = array();
                $insertData['email'] = $this->input->post('email');
                $insertData['role_id'] = $this->user_model->getRoleId('seller');
                $insertData['activation_key'] = md5(time());
                $insertData['created'] = get_est_time();
                //Create User
                $this->user_model->createUser($insertData);

                //Create user balance
                $insertBalance['id'] = '';
                $insertBalance['user_id'] = $this->db->insert_id();
                $insertBalance['amount'] = '0';
                $this->user_model->createUserBalance($insertBalance);

                //Load Model For Mail
                $this->load->model('email_model');

                //Send Mail
                $conditionUserMail = array('email_templates.type' => 'sellers_signup');
                $result = $this->email_model->getEmailSettings($conditionUserMail);
                $rowUserMailConent = $result->row();
                $activation_url = site_url('seller/confirm/' . $insertData['activation_key']);
                $activation_link = "<a href='$activation_url'>$activation_url</a>";
                $contact_url = site_url('contact');
                $contact_link = "<a href='$contact_url'>$contact_url</a>";
                $splVars = array("!site_title" => $this->config->item('site_title'), "!contact_link" =>  $contact_link, "!activation_link" => $activation_link);
                $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                $toEmail = $insertData['email'];
                $fromEmail = $this->config->item('site_admin_mail');
                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);

                //Set the Success Message
                $success_msg = $this->lang->line('confirmation_text') . $insertData['email'] . $this->lang->line('follow_the_link');

                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $success_msg));
                redirect('seller/signUp');
            }  //Form Validation End
        } //If - Form Submission End	
        $this->load->view('seller/sellerSignup', $this->outputData);
    }

//Function signUp End
    // --------------------------------------------------------------------
    /**
     * Resending activation link 
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function resendActLink() {
        //language file
        $this->lang->load('enduser/sellerSignup', $this->config->item('language_code'));

        //load validation library
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        //Get Form Data	
        if ($this->input->post('resend', TRUE)) {
            //Set rules
            $this->form_validation->set_rules('email2', 'lang:seller_email_validation', 'required|trim|valid_email|xss_clean|callback__check_resendseller_email');
            if ($this->form_validation->run()) {
                $email = $this->input->post('email2', TRUE);
                //Conditions
                $conditions = array('users.email' => $email, 'users.role_id' => $this->user_model->getRoleId('seller'));
                $query = $this->user_model->getUsers($conditions);
                $userRow = $query->row();

                //Load Model For Mail
                $this->load->model('email_model');

                //Send Mail
                $conditionUserMail = array('email_templates.type' => 'sellers_signup');
                $result = $this->email_model->getEmailSettings($conditionUserMail);
                $rowUserMailConent = $result->row();
                $splVars = array("!site_title" => $this->config->item('site_title'), "!activation_url" => site_url('seller/confirm/' . $userRow->activation_key), "!contact_url" => site_url('contact'));
                $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                $toEmail = $email;
                $fromEmail = $this->config->item('site_admin_mail');
                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);

                //Set the Success Message
                $success_msg = $this->lang->line('confirmation_text') . $userRow->email . $this->lang->line('follow_the_link');

                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $success_msg));
                redirect('seller/signUp');
            }
        }
        $this->load->view('seller/sellerSignup', $this->outputData);
    }

    // --------------------------------------------------------------------

    /**
     * Loads confirm page for seller
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function confirm() {
        //language file
        $this->lang->load('enduser/sellerConfirm', $this->config->item('language_code'));
        $check_key = $this->uri->segment(3, 0);

        //load validation libraray
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        //Get Form Data	
        if ($this->input->post('sellerConfirm')) {
            //Set rules
            $this->form_validation->set_rules('username', 'lang:seller_name_validation', 'required|trim|min_length[5]|xss_clean|callback__check_username|alpha_space');
            $this->form_validation->set_rules('pwd', 'lang:password_validation', 'required|trim|min_length[5]|max_length[16]|xss_clean|matches[ConfirmPassword]');
            $this->form_validation->set_rules('ConfirmPassword', 'ConfirmPassword', 'required|trim|xss_clean');
            $this->form_validation->set_rules('name', 'lang:name_confirm_validation', 'trim|min_length[5]|xss_clean');
            $this->form_validation->set_rules('rate', 'lang:rate_validation', 'required|trim|is_natural_no_zero|xss_clean|abs');
            $this->form_validation->set_rules('profile', 'lang:profile_validation', 'min_length[25]|trim|xss_clean');
            $this->form_validation->set_rules('logo', 'lang:logo_validation', 'callback__logo_check');
            $this->form_validation->set_rules('country', 'lang:country_validation', 'required');
            $this->form_validation->set_rules('state', 'lang:state_validation', 'trim|xss_clean');
            $this->form_validation->set_rules('city', 'lang:city_validation', 'trim|xss_clean');
            $this->form_validation->set_rules('categories[]', 'lang:categories_validation', 'required');
            $this->form_validation->set_rules('signup_agree_terms', 'lang:signup_agree_terms_validation', 'required');
            //$this->form_validation->set_rules('signup_agree_contact','lang:signup_agree_contact_validation','required');
            $this->form_validation->set_rules('confirmKey', 'Confirmation Key', 'callback__check_activation_key');
            $this->form_validation->set_rules('msn', 'msn', 'trim|xss_clean');
            $this->form_validation->set_rules('gtalk', 'gtalk', 'trim|xss_clean');
            $this->form_validation->set_rules('yahoo', 'yahoo', 'trim|xss_clean');
            $this->form_validation->set_rules('skype', 'skype', 'trim|xss_clean');

            if ($this->form_validation->run()) {
                if (check_form_token() === false) {
                    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('token_error')));

                    redirect('info');
                }


                $updateData = array();
                $updateData['refid'] = $this->session->userdata('refId');
                $updateData['user_name'] = $this->input->post('username');
                $updateData['password'] = md5($this->input->post('pwd'));
                $updateData['name'] = $this->input->post('name');
                $updateData['profile_desc'] = $this->input->post('profile');
                $updateData['rate'] = $this->input->post('rate');
                $updateData['project_notify'] = $this->input->post('notify_project');
                $updateData['message_notify'] = $this->input->post('notify_message');
                $updateData['country_symbol'] = $this->input->post('country');
                $updateData['state'] = $this->input->post('state');
                $updateData['city'] = $this->input->post('city');
                $updateData['user_status'] = '1';

                if (isset($this->outputData['file'])) {
                    $updateData['logo'] = $this->outputData['file']['file_name'];
                    $thumb1 = $this->outputData['file']['file_path'] . $this->outputData['file']['raw_name'] . "_thumb" . $this->outputData['file']['file_ext'];
                    GenerateThumbFile($this->outputData['file']['full_path'], $thumb1, 49, 48);
                }

                //Create User

                $updateKey = array('activation_key' => $this->input->post('confirmKey'));
                $this->user_model->updateUser($updateKey, $updateData);
                $this->session->unset_userdata('refId');

                $condition = array('users.activation_key' => $check_key);
                $users = $this->user_model->getUserslist($condition);
                $users = $users->row();
                $conditions = array('users.role_id' => '2', 'users.activation_key' => $this->input->post('confirmKey'));
                $query = $this->user_model->getUsers($conditions);
                $row = $query->row();

                //Work With Project Categories

                $categories = $this->input->post('categories');

                $ids = implode(',', $categories);

                $insertData['user_categories'] = $ids;
                $insertData['user_id'] = $users->id;
                $insertData['user_id'] = $row->id;




                $this->user_model->insertUserCategories($insertData);

                $contacts = array();
                $contacts['msn'] = $this->input->post('contact_msn', TRUE);
                $contacts['gtalk'] = $this->input->post('contact_gtalk', TRUE);
                $contacts['yahoo'] = $this->input->post('contact_yahoo', TRUE);
                $contacts['skype'] = $this->input->post('contact_skype', TRUE);
                $contacts['user_id'] = $row->id;

                $this->user_model->insertUserContacts($contacts);

                if (count($row) > 0) {
                    //Get the last insert username
                    $condition = array('users.activation_key' => $this->uri->segment(3));
                    $registerusers = $this->user_model->getUsers($condition);

                    $registerusers = $registerusers->row();
                    //Send email to the user after registration
                    $conditionUserMail = array('email_templates.type' => 'registration');
                    $result = $this->email_model->getEmailSettings($conditionUserMail);

                    $rowUserMailConent = $result->row();

                    $splVars = array("!site_name" => $this->config->item('site_title'), "!username" => $updateData['user_name'], "!password" => $this->input->post('pwd'), "!usertype" => 'Seller', "!siteurl" => site_url(), "!contact_url" => site_url('contact'));
                    $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                    $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                    $toEmail = $registerusers->email;
                    $fromEmail = $this->config->item('site_admin_mail');
                    //echo $mailContent;exit;
                    $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                    $insertData = array();
                    $insertData['username'] = $this->input->post('username');
                    $insertData['password'] = md5($this->input->post('pwd'));
                    $expire = 60 * 60 * 24 * 100;
                    $this->auth_model->setUserCookie('user_name', $insertData['username'], $expire);
                    $this->auth_model->setUserCookie('user_password', $insertData['password'], $expire);
                    redirect('users/login');
                }


                //Notification message
                /*
                  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('seller_confirm_success')));

                  redirect('info/index/success'); */
            } //Form Validation End
        } //If - Form Submission End	
        //Get Categories
        $this->outputData['categories'] = $this->skills_model->getCategories();

        //Get Countries
        $this->outputData['countries'] = $this->common_model->getCountries();

        //Get Activation Key
        $activation_key = $this->uri->segment(3, '0');

        //Conditions
        $conditions = array('users.role_id' => '2', 'users.activation_key' => $activation_key);

        $query = $this->user_model->getUsers($conditions);

        //pr($query->row());exit;
        if ($query->num_rows == 1) {
            $row = $query->row();
        } else {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('seller_activationkey_error')));
            redirect('seller/signUp');
        }

        //Puhal changes To get the Privacy Policy Contents
        $like = array('page.url' => '%privacy%');
        $this->outputData['page_content'] = $this->page_model->getPages(NULL, $like, NULL);

        //Puhal Chnages To get the company and conditions Contents

        $like = array('page.url' => '%ter%');
        $like1 = array('page.url' => '%cond%');
        $this->outputData['page_content1'] = $this->page_model->getPages(NULL, $like, $like1);

        $this->outputData['confirmed_mail'] = $row->email;

        $this->load->view('seller/sellerConfirm', $this->outputData);
    }

//Function confirm End
    // --------------------------------------------------------------------

    /**
     * Loads confirm page for buyer
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function _logo_check() {
        if (isset($_FILES) and $_FILES['logo']['name'] == '')
            return true;

        $config['upload_path'] = 'files/logos/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG';
        $config['max_size'] = $this->config->item('max_upload_size');
        $config['encrypt_name'] = TRUE;
        $config['remove_spaces'] = TRUE;

        $this->load->library('upload', $config);
        if ($this->upload->do_upload('logo')) {
            $this->outputData['file'] = $this->upload->data();
            return true;
        } else {
            $this->form_validation->set_message('_logo_check', $this->upload->display_errors($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag')));
            return false;
        }//If end 
    }

//Function logo_check End
    // --------------------------------------------------------------------

    /**
     * Loads confirm page for buyer
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function _check_activation_key($activation_key=0) {
        //Conditions
        $conditions = array('users.activation_key' => $activation_key);
        $query = $this->user_model->getUsers($conditions);
        if ($query->num_rows == 1) {
            return true;
        } else {
            $this->form_validation->set_message('check_activation_key', $this->lang->line('activation_key_validation'));
            return false;
        }
    }

//Function check_activation_key End
    // --------------------------------------------------------------------

    /**
     * Loads ediit Seller Profile .
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function editProfile() {
        
        //language file
        $this->lang->load('enduser/editProfile', $this->config->item('language_code'));
        
        //Check Whether User Logged In Or Not
        if (!isset($this->loggedInUser->id)) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be login access to this page')));
            redirect('info');
        }

        //Check Whether User Logged In Or Not
        if (isLoggedIn() === false) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('not_access')));
            redirect('info');
        }

        //load validation library
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        // Azeem modifiyed
        // feb 13.2009
        if ($this->input->post('updateSellerConfirm')) {
            //Set rules
            $this->form_validation->set_rules('logo', 'lang:logo_validation', 'callback__logo_check');
            $this->form_validation->set_rules('name', 'lang:seller_name_validation', 'required|trim|min_length[5]|xss_clean');
            $this->form_validation->set_rules('categories[]', 'lang:categories_validation', 'xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|min_length[5]|xss_clean');
            $this->form_validation->set_rules('rate', 'lang:rate_validation', 'trim|integer|xss_clean|abs');
            $this->form_validation->set_rules('country', 'lang:country_validation','required|trim');
            $this->form_validation->set_rules('state', 'lang:state_validation', 'required|min_length[3]|trim|xss_clean');
            $this->form_validation->set_rules('city', 'lang:city_validation', 'required||min_length[2]trim|xss_clean');

            if ($this->form_validation->run()) {
                $updateData = array();
                if ($this->input->post('pwd') != '') {
                    //echo md5($this->input->post('pwd'));
                    $updateData['password'] = md5($this->input->post('pwd'));
                }

                $updateData['name'] = $this->input->post('name', TRUE);
                $updateData['email'] = $this->input->post('email', TRUE);
                $updateData['profile_desc'] = $this->input->post('profile', TRUE);
                $updateData['project_notify'] = $this->input->post('notify_project', TRUE);
                $updateData['message_notify'] = $this->input->post('notify_message', TRUE);

                if (($this->loggedInUser->logo != '') and (isset($this->outputData['file']['file_name']))) {
                    $filepath = $this->config->item('basepath') . 'files/logos/' . $this->loggedInUser->logo;
                    //echo $filepath;exit;
                    @unlink($filepath);
                    if (isset($this->outputData['file']['file_name']))
                        $updateData['logo'] = $this->outputData['file']['file_name'];
                    $thumb1 = $this->outputData['file']['file_path'] . $this->outputData['file']['raw_name'] . "_thumb" . $this->outputData['file']['file_ext'];
                    GenerateThumbFile($this->outputData['file']['full_path'], $thumb1, 49, 48);
                }
                else {
                    if (isset($this->outputData['file']['file_name'])) {
                        $updateData['logo'] = $this->outputData['file']['file_name'];
                        $thumb1 = $this->outputData['file']['file_path'] . $this->outputData['file']['raw_name'] . "_thumb" . $this->outputData['file']['file_ext'];
                        GenerateThumbFile($this->outputData['file']['full_path'], $thumb1, 49, 48);
                    }
                }

                $updateData['country_symbol'] = $this->input->post('country', TRUE);
                $updateData['state'] = $this->input->post('state', TRUE);
                $updateData['city'] = $this->input->post('city', TRUE);
                $updateData['rate'] = $this->input->post('rate', TRUE);
                
                

                //update data's in userContacts table
                $userContacts['msn'] = $this->input->post('contact_msn', TRUE);
                $userContacts['gtalk'] = $this->input->post('contact_gtalk', TRUE);
                $userContacts['yahoo'] = $this->input->post('contact_yahoo', TRUE);
                $userContacts['skype'] = $this->input->post('contact_skype', TRUE);

                //
                $userCategoryId = $this->loggedInUser->id;

                //Get Activation Key
                $activation_key = $this->uri->segment(3, '0');

                //Create User
                $updateKey = array('id' => $this->loggedInUser->id);

                // Update process for users table
                $this->user_model->updateUser($updateKey, $updateData);
                $updateKey1 = array('users.activation_key' => $this->input->post('confirmKey'));

                $query = $this->user_model->getUsers($updateKey1);
                $row = $query->row();
                $userid = $row->id;
                $updateKey2 = array('user_contacts.user_id' => $this->loggedInUser->id);
                $query2 = $this->user_model->getUserContacts($updateKey2);
                $userDetails = $query2->row();

                //pr($query2->num_rows());exit;
                if ($query2->num_rows() == 0) {
                    $insertData = array();
                    $insertData['user_id'] = $this->loggedInUser->id;
                    $insertData['msn'] = $this->input->post('contact_msn', TRUE);
                    $insertData['gtalk'] = $this->input->post('contact_gtalk', TRUE);
                    $insertData['yahoo'] = $this->input->post('contact_yahoo', TRUE);
                    $insertData['skype'] = $this->input->post('contact_skype', TRUE);
                    $this->user_model->insertUserContacts($insertData);
                } else {
                    //update data's in userContacts table
                    $userContacts['msn'] = $this->input->post('contact_msn', TRUE);
                    $userContacts['gtalk'] = $this->input->post('contact_gtalk', TRUE);
                    $userContacts['yahoo'] = $this->input->post('contact_yahoo', TRUE);
                    $userContacts['skype'] = $this->input->post('contact_skype', TRUE);

                    // update process for Content	  
                    $this->user_model->updateUserContacts($userContacts, $updateKey2);
                }
                // user categories 

                if ($this->input->post('categories') != '') {


                    $userid = $this->loggedInUser->id;
                    $updateKey3 = array('user_categories.user_id' => $userid);
                    $query3 = $this->user_model->getUserCategories($updateKey3);
                    $userDetails = $query3->row();
                    $area_expertice = $this->input->post('categories', TRUE);
                    $ids = implode(',', $area_expertice);

                    $i = 0;
                    //pr($area_expertice);
                    foreach ($area_expertice as $cat) {

                        $conditions = array('categories.id' => $cat);
                        $categories = $this->skills_model->getCategories($conditions);
                        $categories = $categories->row();
                        $category[$i++] = $categories->category_name;
                    }
                    $category = implode(',', $category);


                    $userCategories['user_categories'] = $ids;

                    if ($query3->num_rows() == 0) {
                        $insertData1 = array();
                        $insertData1['user_id'] = $this->loggedInUser->id;
                        $insertData1['user_categories'] = $ids;
                        $this->user_model->insertUserCategories($insertData1);
                    } else {
                        $userid = $this->loggedInUser->id;
                        $area_expertice = $this->input->post('categories', TRUE);
                        $ids = implode(',', $area_expertice);
                        $userCategories['user_categories'] = $ids;

                        // update process for Content	  
                        $this->user_model->updateCategories(array('user_categories.user_id' => $userid), $userCategories);
                    }
                }
                if ($this->input->post('pwd', TRUE))
                    $data1 = '<b>Password            :</b>' . $this->input->post('pwd') . '<br>';
                else
                    $data1 = '';

                if ($this->input->post('name', TRUE))
                    $data2 = '<b>Company Name        :</b>' . $this->input->post('name', TRUE) . '<br>';
                else
                    $data2 = '';

                if ($this->input->post('email', TRUE))
                    $data3 = '<b>Email Id            :</b>' . $this->input->post('email') . '<br>';
                else
                    $data3 = '';

                if ($this->input->post('profile', TRUE))
                    $data4 = '<b>Profile Description :</b>' . $this->input->post('profile') . '<br>';
                else
                    $data4 = '';

                if ($this->input->post('notify_project', TRUE))
                    $data5 = '<b>Project Notify      :</b>' . $this->input->post('notify_project') . '<br>';
                else
                    $data5 = '';

                if ($this->input->post('notify_message', TRUE))
                    $data6 = '<b>Message Notify      :</b>' . $this->input->post('notify_message') . '<br>';
                else
                    $data6 = '';

                if ($this->input->post('country', TRUE)) {
                    $condition = array('country.country_symbol' => $this->input->post('country'));
                    $country = $this->common_model->getCountries($condition);
                    $country = $country->row();
                    $data7 = '<b>Country             :</b>' . $country->country_name . '<br>';
                }
                else
                    $data7 = '';

                if ($this->input->post('city', TRUE))
                    $data8 = '<b>City                :</b>' . $this->input->post('city') . '<br>';
                else
                    $data8 = '';

                if ($this->input->post('state', TRUE))
                    $data9 = '<b>State               :</b>' . $this->input->post('state') . '<br>';
                else
                    $data9 = '';

                if ($this->input->post('contact_msn', TRUE))
                    $data10 = '<b>MSN ID             :</b>' . $this->input->post('contact_msn') . '<br>';
                else
                    $data10 = '';

                if ($this->input->post('contact_gtalk', TRUE))
                    $data11 = '<b>Gtalk ID           :</b>' . $this->input->post('contact_gtalk') . '<br>';
                else
                    $data11 = '';

                if ($this->input->post('contact_yahoo', TRUE))
                    $data12 = '<b>Yahoo Id           :</b>' . $this->input->post('contact_yahoo') . '<br>';
                else
                    $data12 = '';

                if ($this->input->post('contact_skype', TRUE))
                    $data12 = '<b>Skype Id           :</b>' . $this->input->post('contact_skype') . '<br>';
                else
                    $data12 = '';

                if (isset($ids))
                    $data12 .= '<b>Area of Expertise     :</b>' . $category . '<br>';

                //Send email to the user after update profile
                $conditionUserMail = array('email_templates.type' => 'profile_update');
                $result = $this->email_model->getEmailSettings($conditionUserMail);
                $rowUserMailConent = $result->row();

                $splVars = array("!site_name" => $this->config->item('site_title'), "!username" => $this->loggedInUser->user_name, "!siteurl" => site_url(), "!contact_url" => site_url('contact'), "!data1" => $data1, "!data2" => $data2, "!data3" => $data3, "!data4" => $data4, "!data5" => $data5, "!data6" => $data6, "!data7" => $data7, "!data8" => $data8, "!data9" => $data9, "!data10" => $data10, "!data11" => $data11, "!data12" => $data12);
                $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                $toEmail = $this->loggedInUser->email;
                $fromEmail = $this->config->item('site_admin_mail');
                //echo $mailContent;exit;
                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                //Notification message

                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('update_seller_confirm_success')));

                redirect('info/index/success');
            }  //Form Validation End
        } //If - Form Submission End	
        //Get Categories
        $this->outputData['categories'] = $this->skills_model->getCategories();

        //Get Countries
        $this->outputData['countries'] = $this->common_model->getCountries();

        //Conditions
        $conditions = array('users.id' => $this->loggedInUser->id);
        $this->outputData['userInfo'] = $this->user_model->getUsers($conditions);

        // get Users Categories  from user Categories  table

        $conditions = array('user_categories.user_id' => $this->loggedInUser->id);
        $this->outputData['userCategories'] = $this->user_model->getUserCategories($conditions);

        // pr($this->outputData['userCategories']->result());exit;
        // get Users Contact Informations from user Contacts  table

        $conditions = array('user_contacts.user_id' => $this->loggedInUser->id);
        $this->outputData['userContactInfo'] = $this->user_model->getUserContacts($conditions);

        $this->load->view('seller/editSellerProfile', $this->outputData);
    }

//Function ediitProfile End
    // --------------------------------------------------------------------

    /**
     * Check for seller mail id
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function _check_seller_email($mail) {

        //language file
        $this->lang->load('enduser/sellerSignup', $this->config->item('language_code'));

        //Get Role Id For Buyers
        $role_id = $this->user_model->getRoleId('seller');

        //Conditions
        $conditions = array('users.email' => $mail, 'users.role_id' => $role_id);
        $result = $this->user_model->getUsers($conditions);
        $conditions2 = array('bans.ban_value' => $mail, 'bans.ban_type' => 'EMAIL');
        $result2 = $this->user_model->getBans($conditions2);
        if ($result->num_rows() == 0 && $result2->num_rows() == 0) {
            return true;
        } else {
            $this->form_validation->set_message('_check_seller_email', $this->lang->line('seller_email_check'));
            return false;
        }//If end 
    }

//Function  _check_usernam End
    // --------------------------------------------------------------------

    /**
     * Check for buyer mail id
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function _check_resendseller_email($mail) {

        //language file
        $this->lang->load('enduser/sellerSignup', $this->config->item('language_code'));
        //Get Role Id For Buyers
        $role_id = $this->user_model->getRoleId('seller');

        //Conditions
        $conditions = array('users.email' => $mail, 'users.role_id' => $role_id, 'users.user_status' => '0');
        $result = $this->user_model->getUsers($conditions);
        $conditionsmail = array('users.email' => $mail, 'users.role_id' => $role_id);
        $resultmail = $this->user_model->getUsers($conditionsmail);
        $conditions2 = array('bans.ban_value' => $mail, 'bans.ban_type' => 'EMAIL');
        $result2 = $this->user_model->getBans($conditions2);
        //pr($result->num_rows());exit;
        if ($result2->num_rows() == 0 && $result->num_rows() == 1) {
            return true;
        } else if ($result2->num_rows() == 0 && $resultmail->num_rows() != 0) {
            $this->form_validation->set_message('_check_resendseller_email', $this->lang->line('buyer_email_ban'));
            return false;
        } else if ($result2->num_rows() != 0 || $resultmail->num_rows() == 0) {
            $this->form_validation->set_message('_check_resendseller_email', $this->lang->line('not_registered'));
            return false;
        }//If end 
    }

//Function _check_resendbuyer_email End
    // --------------------------------------------------------------------



    function _check_username($username) {

        //language file
        $this->lang->load('enduser/sellerSignup', $this->config->item('language_code'));

        //Get Role Id For Buyers
        $role_id = $this->user_model->getRoleId('seller');

        //Conditions
        $conditions = array('users.user_name' => $username, 'users.role_id' => $role_id);
        $result = $this->user_model->getUsers($conditions);
        $conditions2 = array('bans.ban_value' => $username, 'bans.ban_type' => 'USERNAME');
        $result2 = $this->user_model->getBans($conditions2);
        if ($result->num_rows() == 0 && $result2->num_rows() == 0) {
            return true;
        } else {
            $this->form_validation->set_message('_check_username', $this->lang->line('seller_username_check'));
            return false;
        }//If end 
    }

//Function  _check_usernam End
    // --------------------------------------------------------------------

    /**
     * View seller's profile
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function viewProfile() {


        //Load Language
        $this->lang->load('enduser/viewProfile', $this->config->item('language_code'));
        if (!is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You can not access to this page')));
            redirect('info');
        }
        $sellerId = $this->uri->segment(3, '0');

        //Get user details
        $conditions = array('users.id' => $sellerId);
        $user = $this->user_model->getUsers($conditions);
        $this->outputData['userDetails'] = $user;
        $urow = $user->row();

        //Get Portfolio
        $condition = array('portfolio.user_id' => $sellerId);
        $this->outputData['portfolio'] = $this->user_model->getPortfolio($condition);

        //Get user contacts
        $conditions2 = array('user_contacts.user_id' => $sellerId);



        $this->outputData['userContacts'] = $this->user_model->getUserContacts($conditions2);

        $country = $this->common_model->getCountries(array('country_symbol' => $urow->country_symbol));
        $this->outputData['country'] = $country->row();

        // get Users Categories  from user Categories  table

        $conditions = array('user_categories.user_id' => $sellerId);

        $this->outputData['userCategories'] = $this->user_model->getUserCategories($conditions);

        //Get Categories
        $this->outputData['categories'] = $this->skills_model->getCategories();
        $this->load->view('seller/viewProfile', $this->outputData);
    }

//Function _check_activation_key End
    // --------------------------------------------------------------------

    /**
     * View projects bidding by a seller
     *
     * @access	Private
     * @param	nil
     * @return	void
     */
    function viewMyProjects() {
        $this->load->helper('reviews');
        //Load Language
        $this->lang->load('enduser/viewProject', $this->config->item('language_code'));
        //language file

        $this->lang->load('enduser/editProfile', $this->config->item('language_code'));

        //Check For Buyer Session
        if (!isSeller()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a Seller')));
            redirect('info');
        }

        //Check Whether User Logged In Or Not
        if (isLoggedIn() === false) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('Dont have rights to access this page')));
            redirect('info');
        }
        //Get buyer id
        $seller_id = $this->loggedInUser->id;

        $page = $this->uri->segment(3, '0');

        //Get Sorting order
        $field = $this->uri->segment(4, '0');

        $order = $this->uri->segment(5, '0');

        $this->outputData['order'] = $order;
        $this->outputData['field'] = $field;

        $orderby = array();
        if ($field)
            $orderby = array($field, $order);

        //pr($page);exit;
        if (isset($page) === false or empty($page)) {
            $page = 1;
        }

        //
        if ($this->loggedInUser) {
            $buyer_id = $this->loggedInUser->id;

            //Get bookmark projects
            $condition_bookmark = array('bookmark.creator_id' => $buyer_id);
            $bookMark1 = $this->skills_model->getBookmark($condition_bookmark);

            //Get all users
            $this->outputData['getUsers'] = $this->user_model->getUsers();

            //pagination limit
            $page_rows1 = $this->config->item('mail_limit');

            $limit1[0] = $page_rows1;
            $limit1[1] = '0';

            //Get all message trasaction with some limit
            $bookMark = $this->skills_model->getBookmark($condition_bookmark, NULL, NULL, $limit1);
            $this->outputData['bookMark'] = $bookMark;

            //Pagination
            $this->load->library('pagination');
            $config['base_url'] = site_url('buyer/bookmarkProjects');
            $config['total_rows'] = $bookMark1->num_rows();
            $config['per_page'] = $page_rows1;
            $config['cur_page'] = '0';
            $this->pagination->initialize($config);
            $this->outputData['pagination1'] = $this->pagination->create_links2(false, 'bookmarkProjects');
        }
        $this->outputData['page'] = $page;

        $page_rows = $this->config->item('listing_limit');

        $max = array($page_rows, ($page - 1) * $page_rows);

        //Conditions
        $conditions2 = array('bids.user_id ' => $seller_id, 'projects.project_status !=' => '2');
        $bids = $this->skills_model->getProjectByBid($conditions2, NULL, NULL, $max, $orderby);
        $bids2 = $this->skills_model->getProjectByBid($conditions2);

        $this->outputData['biddingProjects'] = $bids;
        $this->outputData['seller_id'] = $seller_id;
        $conditions3 = array('bids.user_id ' => $seller_id, 'projects.project_status =' => '2', 'projects.seller_id' => $seller_id);

        $wonbids = $this->skills_model->getProjectByBid($conditions3);
        $this->outputData['wonBids'] = $wonbids;

        //Pagination
        $this->load->library('pagination');
        $config['base_url'] = site_url('seller/viewMyProjects');
        $config['total_rows'] = $bids2->num_rows();
        $config['per_page'] = $page_rows;
        $config['cur_page'] = $page;

        $this->pagination->initialize($config);
        $this->outputData['pagination'] = $this->pagination->create_links(false, 'project');

        //pr($bids->result());exit;

        $this->load->view('seller/myProjects', $this->outputData);
    }

//Function viewMyProjects End
    // --------------------------------------------------------------------

    /**
     * Retract bids by Seller
     *
     * @access	priate
     * @param	nil
     * @return	void
     */
    function retractBid() {
        //Load Language
        $this->lang->load('enduser/viewProject', $this->config->item('language_code'));

        //Get bid id
        $bidid = $this->uri->segment(3, '0');
        $this->outputData['bidid'] = $bidid;

        if ($this->input->post('retractBid')) {

            $bid = $this->input->post('bidId');

            //Condition
            $conditions = array('bids.id ' => $bid);
            $this->skills_model->deleteBid($conditions);
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('Your bid has been removed')));
            redirect('seller/viewMyProjects');
        }

        //pr($bids->result());exit;
        $this->load->view('seller/retractBid', $this->outputData);
    }

//Function viewMyProjects End
    // --------------------------------------------------------------------

    /**
     * review buyers
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function reviewBuyer() {
        //Load Language
        $this->lang->load('enduser/review', $this->config->item('language_code'));

        //Check For Buyer Session
        if (!isSeller()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a Seller to review Buyer')));
            redirect('info');
        }

        if ($this->input->post('reviewBuy')) {
            $insertData = array();
            $insertData['comments'] = $this->input->post('comment', true);
            $insertData['rating'] = $this->input->post('rate', true);
            $insertData['review_type'] = '1';
            $insertData['review_time'] = get_est_time();
            $insertData['project_id'] = $this->input->post('pid', true);
            $insertData['buyer_id'] = $this->input->post('bid', true);
            $insertData['provider_id'] = $this->loggedInUser->id;

            //Create Review
            $reviewId = $this->skills_model->createReview($insertData);

            //Update projects
            $this->skills_model->updateProjects($insertData['project_id'], array('buyer_rated' => '1'));

            $condition = array('reviews.project_id' => $insertData['project_id']);
            $rev = $this->skills_model->getReviews($condition);
            //pr($rev->result());exit;
            //Send Mail
            $conditionUserMail = array('email_templates.type' => 'seller_review');
            $result = $this->email_model->getEmailSettings($conditionUserMail);
            $rowUserMailConent = $result->row();

            //Get Project details
            $condition = array('projects.id' => $insertData['project_id']);
            $projectDetails = $this->skills_model->getProjects($condition, 'projects.project_name');
            $prjRow = $projectDetails->row();

            //Get User details
            $getuser = $this->user_model->getUsers(array('users.id' => $insertData['buyer_id']));
            $user = $getuser->row();

            $splVars = array("!seller_name" => $this->loggedInUser->user_name, "!project_name" => $prjRow->project_name, "!site_name" => site_url(''), '!site_title' => $this->config->item('site_title'));
            $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
            $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
            $toEmail = $user->email;
            $fromEmail = $this->config->item('site_admin_mail');

            //Send mail
            $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);

            if ($rev->num_rows() == 2) {

                //Increase number of reviews	
                $num_reviews = ($user->num_reviews) + 1;

                //Rating	
                if ($user->user_rating == 0)
                    $rating = $insertData['rating'];
                else
                    $rating = ($user->user_rating + $insertData['rating']) / 2;

                $tot_rating2 = ($rating * $num_reviews);

                //Update buyer

                $this->skills_model->updateUsers($insertData['buyer_id'], array('user_rating' => $rating, 'num_reviews' => $num_reviews, 'tot_rating' => $tot_rating2));

                //Get Provider details
                $getHold = $this->skills_model->getRatingHold(array('rating_hold.user_id' => $this->loggedInUser->id, 'rating_hold.project_id' => $insertData['project_id']));
                $holdRow = $getHold->row();

                if ($getuser->num_rows() > 0) {

                    //Get Provider details
                    $getuser = $this->user_model->getUsers(array('users.id' => $this->loggedInUser->id), 'users.user_rating,users.num_reviews');
                    $providerRow = $getuser->row();

                    //Rating
                    if ($providerRow->user_rating == 0)
                        $rating = $holdRow->rating;
                    else
                        $rating = ($providerRow->user_rating + $holdRow->rating) / 2;

                    //Increase number of reviews
                    $num_reviews = ($providerRow->num_reviews) + 1;

                    $tot_rating = ($rating * $num_reviews);

                    //Update Provider
                    $this->skills_model->updateUsers($this->loggedInUser->id, array('user_rating' => $rating, 'num_reviews' => $num_reviews, 'tot_rating' => $tot_rating));

                    $condition2 = array('reviews.project_id' => $insertData['project_id'], 'reviews.provider_id' => $this->loggedInUser->id, 'reviews.review_type' => '2');
                    $getrev = $this->skills_model->getReviews($condition2, 'reviews.id');
                    $revRow = $getrev->row();
                    //echo $reviewId;exit;
                    $this->skills_model->updateReviews($revRow->id, array('reviews.hold' => '0'));
                }
            }
            if ($rev->num_rows() == 1) {

                $insertData2 = array();
                $insertData2['rating'] = $insertData['rating'];
                $insertData2['user_id'] = $insertData['buyer_id'];
                $insertData2['project_id'] = $insertData['project_id'];
                $this->skills_model->insertRatingHold($insertData2);
                $this->skills_model->updateReviews($reviewId, array('reviews.hold' => '1'));
            }

            //Notification message
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('review_added')));
            redirect('info/index/success');
        }

        //Get project id

        $projectid = $this->uri->segment(3, '0');
        $condition = array('projects.id' => $projectid);
        $projectDetails = $this->skills_model->getProjects($condition);
        $this->outputData['projectDetails'] = $projectDetails;
        $prjRow = $projectDetails->row();

        $condition2 = array('reviews.project_id' => $projectid, 'reviews.buyer_id' => $prjRow->creator_id, 'reviews.review_type' => '1');
        $this->outputData['reviewDetails'] = $this->skills_model->getReviews($condition2);

        //pr($this->outputData['reviewDetails']->result());exit;		

        $this->load->view('seller/reviewBuyer', $this->outputData);
    }

//Function reviewBuyer End
    // --------------------------------------------------------------------

    /**
     * Lists review of a provider
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function review() {
        //Load Language
        $this->lang->load('enduser/review', $this->config->item('language_code'));

        //Load helper
        $this->load->helper('reviews');

        if (!is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You can not access to this page')));
            redirect('info');
        }

        $userId = $this->uri->segment(3, '0');

        //Get user details
        $conditions = array('users.id' => $userId);
        $user = $this->user_model->getUsers($conditions);
        $urow = $user->row();
        $this->outputData['userDetails'] = $urow;

        //pr($urow);exit;
        //Get reviews
        $condition2 = array('reviews.provider_id' => $urow->id, 'reviews.review_type' => '2', 'reviews.hold' => '0');
        $this->outputData['reviewDetails'] = $this->skills_model->getReviews($condition2);
        //pr($this->outputData['reviewDetails']->result());exit;
        $this->load->view('seller/review', $this->outputData);
    }

//Function review End
    // --------------------------------------------------------------------

    /**
     * Get top sellers
     *
     * Returns all sellers rating reviews
     *
     * @access	private
     * @param	string
     * @return	string
     */
    function getSellersreview() {

        //language file
        $this->lang->load('enduser/review', $this->config->item('language_code'));

        //Get reviews
        $result = $this->skills_model->getTopsellers();
        $this->outputData['getSellers'] = $result;
        $this->load->view('seller/topSellers', $this->outputData);
    }

//End of getBuyerReview function
    // --------------------------------------------------------------------

    /**
     * Manage potfolio of providers
     *
     * Returns all sellers rating reviews
     *
     * @access	private
     * @param	string
     * @return	string
     */
    function managePortfolio() {

        //language file
        $this->lang->load('enduser/editProfile', $this->config->item('language_code'));

        /* //Check For Buyer Session
          if(!isSeller())
          {
          $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged in as a Seller')));
          redirect('info');
          } */

        //load validation libraray
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        //Get Form Data	

        if ($this->input->post('createPortfolio')) {

            //Set rules
            $this->form_validation->set_rules('title', 'lang:portfolio_title_validation', 'required|trim|xss_clean');
            $this->form_validation->set_rules('description', 'lang:portfolio_description_validation', 'required|trim|xss_clean');
            $this->form_validation->set_rules('categories[]', 'lang:portfolio_categories_validation', 'required');
            $this->form_validation->set_rules('thumbnail', 'lang:portfolio_thumbnail_validation', 'callback__thumbnail_check');
            $this->form_validation->set_rules('attachment1', 'lang:portfolio_attachment1_validation', 'callback__attachment1_check');
            $this->form_validation->set_rules('attachment2', 'lang:portfolio_attachment2_validation', 'callback__attachment2_check');

            if ($this->form_validation->run()) {
                if (check_form_token() === false) {
                    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('token_error')));
                    redirect('info');
                }

                //pr($this->outputData['file']);exit;
                $categories = $this->input->post('categories');
                $ids = implode(',', $categories);
                $insertData = array();
                $insertData['title'] = $this->input->post('title');
                $insertData['description'] = $this->input->post('description');
                $insertData['categories'] = $ids;
                $insertData['user_id'] = $this->loggedInUser->id;
                $insertData['main_img'] = $this->outputData['file']['file_name'];

                if (isset($this->outputData['file1'])) {

                    $insertData['attachment1'] = $this->outputData['file1']['file_name'];
                    $thumb1 = $this->outputData['file1']['file_path'] . $this->outputData['file1']['raw_name'] . "_thumb" . $this->outputData['file1']['file_ext'];
                    GenerateThumbFile($this->outputData['file1']['full_path'], $thumb1, 120, 90);
                }

                if (isset($this->outputData['file2'])) {
                    $insertData['attachment2'] = $this->outputData['file2']['file_name'];
                    $thumb2 = $this->outputData['file2']['file_path'] . $this->outputData['file2']['raw_name'] . "_thumb" . $this->outputData['file2']['file_ext'];
                    GenerateThumbFile($this->outputData['file2']['full_path'], $thumb2, 120, 90);
                }


                //Create Portfolio
                $this->user_model->insertPortfolio($insertData);

                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('provider_portfolio_success')));
                redirect('seller/managePortfolio');
            }  //Form Validation End
        } //If - Form Submission End	
        //Get Categories
        $this->outputData['categories'] = $this->skills_model->getCategories();

        //pr($this->outputData['categories']);exit;
        //Get Portfolio
        if ($this->loggedInUser) {
            $condition = array('portfolio.user_id' => $this->loggedInUser->id);
            $this->outputData['portfolio'] = $this->user_model->getPortfolio($condition);
            $condition2 = array('portfolio.id' => $this->uri->segment(3));
            $this->outputData['editPortfolio'] = $this->user_model->getPortfolio($condition2);

            //Get Categories
            $this->outputData['categories'] = $this->skills_model->getCategories();
        }


        //pr($this->outputData['getPortfolio']->result());exit;

        $this->load->view('seller/managePorfolio', $this->outputData);
    }

//End of getBuyerReview function
    // --------------------------------------------------------------------

    /**
     * Edit potfolio of providers
     *
     * Returns all sellers rating reviews
     *
     * @access	private
     * @param	string
     * @return	string
     */
    function editPortfolio() {

        //language file
        $this->lang->load('enduser/editProfile', $this->config->item('language_code'));

        //Check For Buyer Session
        if (!isSeller()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a Seller')));
            redirect('info');
        }

        //load validation libraray
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');


        //Intialize values for library and helpers
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));


        //Get Form Data	
        if ($this->input->post('editPortfolio')) {
            //Set rules			
            //echo $_FILES['attachment1']['name'];exit;

            $this->form_validation->set_rules('title', 'lang:portfolio_title_validation', 'required|trim|xss_clean');
            $this->form_validation->set_rules('description', 'lang:portfolio_description_validation', 'required|trim|xss_clean');
            $this->form_validation->set_rules('categories[]', 'lang:portfolio_categories_validation', 'required');
            if ($_FILES['thumbnail']['name'] != '')
                $this->form_validation->set_rules('thumbnail', 'lang:portfolio_thumbnail_validation', 'callback__thumbnail_check');
            if ($_FILES['attachment1']['name'] != '')
                $this->form_validation->set_rules('attachment1', 'lang:portfolio_attachment1_validation', 'callback__attachment1_check');
            if ($_FILES['attachment2']['name'] != '')
                $this->form_validation->set_rules('attachment2', 'lang:portfolio_attachment2_validation', 'callback__attachment2_check');

            if ($this->form_validation->run()) {
                if (check_form_token() === false) {
                    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('token_error')));
                    redirect('info');
                }

                //pr($this->outputData['file']);exit;
                $categories = $this->input->post('categories');
                $ids = implode(',', $categories);
                $updateData = array();
                $updateData['title'] = $this->input->post('title');
                $updateData['description'] = $this->input->post('description');
                $updateData['categories'] = $ids;
                $updateData['user_id'] = $this->loggedInUser->id;
                $condition2 = array('portfolio.id' => $this->input->post('portid'));
                $port = $this->user_model->getPortfolio($condition2);
                $folio = $port->row();
                $path = $this->config->item('basepath') . 'files/portfolios/';
                if (isset($this->outputData['file'])) {

                    $files = array($folio->main_img);
                    //delete image files from server

                    delete_file($path, $files);
                    $updateData['main_img'] = $this->outputData['file']['file_name'];
                }

                if (isset($this->outputData['file1'])) {

                    $files = array($folio->attachment1);

                    //delete image files from server
                    delete_file($path, $files);

                    $updateData['attachment1'] = $this->outputData['file1']['file_name'];

                    $thumb1 = $this->outputData['file1']['file_path'] . $this->outputData['file1']['raw_name'] . "_thumb" . $this->outputData['file1']['file_ext'];

                    //createthumb($this->outputData['file1']['full_path'],$thumb1,120,90);

                    GenerateThumbFile($this->outputData['file1']['full_path'], $thumb1, 120, 90);

                    //$this->skills_model->cr_thumb($this->outputData['file1']['full_path']);
                }

                if (isset($this->outputData['file2'])) {

                    $files = array($folio->attachment2);

                    //delete image files from server

                    delete_file($path, $files);

                    $updateData['attachment2'] = $this->outputData['file2']['file_name'];

                    $thumb2 = $this->outputData['file2']['file_path'] . $this->outputData['file2']['raw_name'] . "_thumb" . $this->outputData['file2']['file_ext'];

                    GenerateThumbFile($this->outputData['file2']['full_path'], $thumb2, 120, 90);

                    //$this->skills_model->cr_thumb($this->outputData['file2']['full_path']);
                }

                $updateKey = array('portfolio.id' => $this->input->post('portid'));

                //Edit Portfolio
                $this->user_model->updatePortfolio($updateKey, $updateData);

                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('provider_portfolio_success')));

                redirect('seller/managePortfolio');
            }  //Form Validation End
        } //If - Form Submission End	
        //Get Categories
        $this->outputData['categories'] = $this->skills_model->getCategories();

        //Get Portfolio
        $condition = array('portfolio.user_id' => $this->loggedInUser->id);
        $this->outputData['portfolio'] = $this->user_model->getPortfolio($condition);

        $condition2 = array('portfolio.id' => $this->uri->segment(3));
        $this->outputData['editPortfolio'] = $this->user_model->getPortfolio($condition2);


        //Get Categories
        $this->outputData['categories'] = $this->skills_model->getCategories();

        //pr($this->outputData['getPortfolio']->result());exit;
        $this->load->view('seller/managePorfolio', $this->outputData);
    }

//End of editPortfolio function
    // --------------------------------------------------------------------

    /**
     * Edit potfolio of providers
     *
     * Returns all sellers rating reviews
     *
     * @access	public
     * @param	string
     * @return	string
     */
    function viewPortfolio() {
        //language file
        $this->lang->load('enduser/editProfile', $this->config->item('language_code'));

        if (!is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You can not access to this page')));
            redirect('info');
        }

        $condition2 = array('portfolio.id' => $this->uri->segment(3));
        $this->outputData['portfolio'] = $this->user_model->getPortfolio($condition2);


        //Get Categories
        $this->outputData['categories'] = $this->skills_model->getCategories();

        //pr($this->outputData['portfolio']->row());exit;
        $this->load->view('seller/viewPortfolio', $this->outputData);
    }

    // --------------------------------------------------------------------

    /**
     * Loads confirm page for buyer
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function _thumbnail_check() {
        //pr($_FILES);exit;		

        if ($_FILES['thumbnail']['name'] == '') {
            $this->form_validation->set_message('_thumbnail_check', $this->lang->line('portfolio_thumb_check'));
            return false;
        }

        $config['upload_path'] = 'files/portfolios/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF';
        $config['max_size'] = $this->config->item('max_upload_size');
        $config['encrypt_name'] = TRUE;
        $config['remove_spaces'] = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('thumbnail')) {
            $this->outputData['file'] = $this->upload->data();

            //pr($this->outputData['file']);exit;

            $this->skills_model->cr_thumb($this->outputData['file']['full_path']);

            return true;
        } else {
            $this->form_validation->set_message('_thumbnail_check', $this->lang->line('portfolio_thumb_check'));
            return false;
        }//If end 
    }

//Function logo_check End
    // --------------------------------------------------------------------

    /**
     * deletePortfolio function
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function deletePortfolio() {

        $pid = $this->uri->segment(3, '0');
        $condition = array('portfolio.id' => $pid);
        $port = $this->user_model->getPortfolio($condition);
        $folio = $port->row();

        //Main image paths
        $path = $this->config->item('basepath') . 'files/portfolios/';
        $filepath = $folio->main_img;
        $attachment1 = $folio->attachment1;
        $attachment2 = $folio->attachment2;
        $files = array($filepath, $attachment1, $attachment2);

        //delete image files from server
        delete_file($path, $files);
        $this->user_model->deletePortfolio($condition);
        redirect('seller/managePortfolio');
    }

//Function deletePortfolio End
    // --------------------------------------------------------------------

    /**
     * Loads confirm page for buyer
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function _attachment1_check() {
        if (isset($_FILES) and $_FILES['attachment1']['name'] == '')
            return true;

        $config['upload_path'] = 'files/portfolios/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF';
        $config['max_size'] = $this->config->item('max_upload_size');
        $config['encrypt_name'] = TRUE;
        $config['remove_spaces'] = TRUE;
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('attachment1')) {

            $this->outputData['file1'] = $this->upload->data();
            //pr($this->outputData['file1']);exit;
            //exit;
            return true;
        } else {

            $this->form_validation->set_message('_attachment1_check', $this->lang->line('portfolio_attach_check'));
            return false;
        }//If end 
    }

//Function logo_check End
    // --------------------------------------------------------------------

    /**
     * Loads confirm page for buyer
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function _attachment2_check() {

        if (isset($_FILES) and $_FILES['attachment2']['name'] == '')
            return true;

        $config['upload_path'] = 'files/portfolios/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF';
        $config['max_size'] = $this->config->item('max_upload_size');
        $config['encrypt_name'] = TRUE;
        $config['remove_spaces'] = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('attachment2')) {
            $this->outputData['file2'] = $this->upload->data();
            return true;
        } else {
            $this->form_validation->set_message('_attachment2_check', $this->lang->line('portfolio_attach_check'));
            return false;
        }//If end 
    }

//Function logo_check End
    // --------------------------------------------------------------------
    /**
     * Remove portfolio attachments
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function removeAttachment() {
        //language file
        $this->lang->load('enduser/editProfile', $this->config->item('language_code'));

        //Check For Buyer Session
        if (!isSeller()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a Seller')));
            redirect('info');
        }

        $portid = $this->uri->segment(4);
        $type = $this->uri->segment(3);
        $path = $this->config->item('basepath') . 'files/portfolios/';
        $condition2 = array('portfolio.id' => $portid);
        $port = $this->user_model->getPortfolio($condition2);
        $folio = $port->row();
        $att = "attachment" . $type;
        $files = array($folio->$att);

        //delete image files from server
        delete_file($path, $files);

        $updateData['attachment' . $type] = '';

        $updateKey = array('portfolio.id' => $portid);

        //Edit Portfolio
        $this->user_model->updatePortfolio($updateKey, $updateData);

        $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', 'Attachment deleted successfully'));
        redirect('seller/managePortfolio/' . $portid);
    }

//Function removeAttachment End
    // --------------------------------------------------------------------
    /**

     * Remove Profile image
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function removePhoto() {
        //language file
        $this->lang->load('enduser/editProfile', $this->config->item('language_code'));

        //Check For Buyer Session
        if ($this->uri->segment(4) == '2') {
            if (!isSeller()) {
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a Seller')));
                redirect('info');
            }
        } elseif ($this->uri->segment(4) == '1') {
            if (!isBuyer()) {
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', 'You must be logged in as a Buyer'));
                redirect('info');
            }
        }

        $userid = $this->uri->segment(3);
        $path = $this->config->item('basepath') . 'files/logos/';
        $condition2 = array('users.id' => $userid);
        $port = $this->user_model->getUsers($condition2);
        $folio = $port->row();
        //$arr = explode(".",$folio->logo);
        //$thumb = $arr[0]."_thumb.".$arr[1];
        $files = array($folio->logo);
        delete_file($path, $files);
        $updateData['users.logo'] = '';
        $updateKey = array('users.id' => $userid);

        //Edit Portfolio
        $this->user_model->updateUser($updateKey, $updateData);

        $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', 'Profile photo deleted successfully'));
        if ($this->uri->segment(4) == '2')
            redirect('seller/editProfile/');
        elseif ($this->uri->segment(4) == '1')
            redirect('buyer/editProfile/');
    }

//Function removeAttachment End

    function remove() {
        $project_id = $this->uri->segment(3);
        $conditions = array('bookmark.project_id' => $project_id, 'bookmark.creator_id' => $this->loggedInUser->id);
        $bookMarks = $this->common_model->deleteTableData('bookmark', $conditions);
        $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', 'Bookmark deleted successfully'));
        redirect('seller/viewMyProjects/');
    }

}

//End  Seller Class

/* End of file Seller.php */

/* Location: ./app/controllers/Seller.php */
?>