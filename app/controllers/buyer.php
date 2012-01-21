<?php

/**
 * Reverse bidding system Buyer Class
 *
 * Buyer related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Buyer 
 * @author		
 * @version		
 * @created		
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
class Buyer extends Controller {

    //Global variable  
    public $outputData;  //Holds the output data for each view
    public $loggedInUser;

    /**
     * Constructor 
     *
     * Loads language files and models needed for this controller
     */
    function Buyer() {
        parent::Controller();

        //Get Config Details From Db
        $this->config->db_config_fetch();


        //Manage site Status 
        if ($this->config->item('site_status') == 1)
            redirect('offline');

        //Debug Tool
        //$this->output->enable_profiler=true;		
        //Load Models Common to all the functions in this controller
        $this->load->model('common_model');
        $this->load->model('user_model');
        $this->load->model('skills_model');
        $this->load->model('page_model');
        $this->load->model('email_model');
        $this->load->model('certificate_model');
		
		$this->load->library('validation');
        $this->load->helper('recaptcha');
		
        //Page Title and Meta Tags
        $this->outputData = $this->common_model->getPageTitleAndMetaData();

        //Get Logged In user
        $this->loggedInUser = $this->common_model->getLoggedInUser();
        $this->outputData['loggedInUser'] = $this->loggedInUser;

        //Get Footer content
        $conditions = array('page.is_active' => 1);
        $this->outputData['pages'] = $this->page_model->getPages($conditions);

        //Get Latest Projects
        $limit_latest = $this->config->item('latest_projects_limit');
        $limit3 = array($limit_latest);
        $this->outputData['latestProjects'] = $this->skills_model->getLatestProjects($limit3);

        //language file
        $this->lang->load('enduser/common', $this->config->item('language_code'));

        $this->outputData['current_page'] = 'buyer';

        //Load helpers
        $this->load->helper('users');
        $this->load->helper('file');
    }

//Controller End 
    // --------------------------------------------------------------------

    /**
     * Loads the top buyers.
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function getBuyersreview() {
        //language file
        $this->lang->load('enduser/review', $this->config->item('language_code'));

        //Load the top buyers
        $this->outputData['topBuyers'] = $this->skills_model->getTopBuyers();

        $this->outputData['getUsers'] = $this->user_model->getUsers();


        $this->load->view('buyer/topBuyer', $this->outputData);
    }

//End of top buyers review 

    /**
     * Loads Buyer signUp page.
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function signUp() {
        //language file
        $this->lang->load('enduser/buyerSignup', $this->config->item('language_code'));

        //load validation library
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
        //Get Form Data	
        if ($this->input->post('buyerSignup', TRUE)) {
            //Set rules
            $this->form_validation->set_rules('email', 'lang:buyer_email_validation', 'required|trim|valid_email|xss_clean|callback__check_buyer_email');
			$this->validation->set_rules('recaptcha_challenge_field','required|recaptcha_matches');
			$fields['recaptcha_challenge_field'] = 'answer to the security question';
			$this->validation->set_fields($fields);
			
			if ($this->validation->run() == FALSE)
			{
				
			}
			
            if ($this->form_validation->run()) {
                //Check for cross site request forgery
                if (check_form_token() === false) {
                    $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('token_error')));
                    redirect('buyer/signUp');
                }
                $insertData = array();
                $insertData['email'] = $this->input->post('email', TRUE);
                $insertData['role_id'] = $this->user_model->getRoleId('buyer');
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
                $conditionUserMail = array('email_templates.type' => 'buyers_signup');
                $result = $this->email_model->getEmailSettings($conditionUserMail);

                if ($result->num_rows() > 0) {
                    $rowUserMailConent = $result->row();

                    $splVars = array("!site_name" => $this->config->item('site_title'), "!activation_url" => site_url('buyer/confirm/' . $insertData['activation_key']), "!contact_url" => site_url('contact'));
                    $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                    $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                    $toEmail = $this->input->post('email', TRUE);
                    $fromEmail = $this->config->item('site_admin_mail');
                    $name = 'Admin';
                    $header = "From: " . $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                    $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                    //print_r($mailContent);
                }
                //exit;
                //Set the Success Message
                $success_msg = $this->lang->line('confirmation_text') . $insertData['email'] . $this->lang->line('follow_the_link');

                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $success_msg));
                redirect('buyer/signUp');
            }  //Form Validation End
        } //If - Form Submission End	

        $this->load->view('buyer/buyerSignup', $this->outputData);
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
        $this->lang->load('enduser/buyerSignup', $this->config->item('language_code'));

        //load validation library
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        //Get Form Data	
        if ($this->input->post('resend', TRUE)) {
            //Set rules
            $this->form_validation->set_rules('email2', 'lang:buyer_email_validation', 'required|trim|valid_email|xss_clean|callback__check_resendbuyer_email');

            if ($this->form_validation->run()) {
                $email = $this->input->post('email2', TRUE);
                //Conditions
                $conditions = array('users.email' => $email, 'users.role_id' => $this->user_model->getRoleId('buyer'));
                $query = $this->user_model->getUsers($conditions);
                $userRow = $query->row();

                //Load Model For Mail
                $this->load->model('email_model');

                //Send Mail
                $conditionUserMail = array('email_templates.type' => 'buyers_signup');
                $result = $this->email_model->getEmailSettings($conditionUserMail);

                $rowUserMailConent = $result->row();

                $splVars = array("!site_name" => $this->config->item('site_title'), "!activation_url" => site_url('buyer/confirm/' . $userRow->activation_key), "!contact_url" => site_url('contact'));
                $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                $toEmail = $email;
                $fromEmail = $this->config->item('site_admin_mail');
                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);

                //Set the Success Message
                $success_msg = $this->lang->line('confirmation_text') . $userRow->email . $this->lang->line('follow_the_link');

                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $success_msg));
                redirect('buyer/signUp');
            }
        }
        $this->load->view('buyer/buyerSignup', $this->outputData);
    }

    // --------------------------------------------------------------------

    /**
     * Loads confirm page for buyer
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function confirm() {
        //language file
        $this->lang->load('enduser/buyerConfirm', $this->config->item('language_code'));

        //Load Models - for this function
        $this->load->model('skills_model');

        //load validation libraray
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        //Get Form Data	
        if ($this->input->post('buyerConfirm', TRUE)) {
            //Set rules
            $this->form_validation->set_rules('username', 'lang:buyer_name_validation', 'required|trim|min_length[5]|xss_clean|callback__check_username|alpha_space');
            $this->form_validation->set_rules('password', 'lang:password', 'required|trim|min_length[5]|max_length[16]|xss_clean|matches[ConfirmPassword]');
            $this->form_validation->set_rules('ConfirmPassword', 'ConfirmPassword', 'required|trim|min_length[5]|max_length[16]|xss_clean');
            $this->form_validation->set_rules('name', 'lang:name_validation', 'trim|min_length[5]|xss_clean');
            $this->form_validation->set_rules('logo', 'lang:logo_validation', 'callback__logo_check');
            $this->form_validation->set_rules('country', 'lang:country_validation', 'required|xss_clean');
            $this->form_validation->set_rules('state', 'lang:state_validation', 'trim|xss_clean');
            $this->form_validation->set_rules('city', 'lang:city_validation', 'trim|xss_clean');
            $this->form_validation->set_rules('signup_agree_terms', 'lang:signup_agree_terms_validation', 'required');
            $this->form_validation->set_rules('signup_agree_contact', 'lang:signup_agree_contact_validation', 'required');
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
                $updateData['user_name'] = $this->input->post('username', TRUE);
                $updateData['password'] = md5($this->input->post('password', TRUE));
                $updateData['name'] = $this->input->post('name', TRUE);
                $updateData['bid_notify'] = $this->input->post('notify_bid', TRUE);
                $updateData['message_notify'] = $this->input->post('notify_message', TRUE);
                $updateData['country_symbol'] = $this->input->post('country', TRUE);
                $updateData['state'] = $this->input->post('state', TRUE);
                $updateData['city'] = $this->input->post('city', TRUE);
                $updateData['user_status'] = '1';


                if ((isset($this->outputData['file']))) {

                    $updateData['logo'] = $this->outputData['file']['file_name'];
                    $thumb1 = $this->outputData['file']['file_path'] . $this->outputData['file']['raw_name'] . "_thumb" . $this->outputData['file']['file_ext'];
                    GenerateThumbFile($this->outputData['file']['full_path'], $thumb1, 49, 48);
                }



                //Create User
                $updateKey = array('activation_key' => $this->input->post('confirmKey', TRUE));
                // print_r($updateData);
                $this->user_model->updateUser($updateKey, $updateData);

                $this->session->unset_userdata('refId');


                $user = $this->user_model->getUsers($updateKey);
                $userDetails = $user->row();

                $contacts = array();
                $contacts['msn'] = $this->input->post('contact_msn', TRUE);
                $contacts['gtalk'] = $this->input->post('contact_gtalk', TRUE);
                $contacts['yahoo'] = $this->input->post('contact_yahoo', TRUE);
                $contacts['skype'] = $this->input->post('contact_skype', TRUE);
                $contacts['user_id'] = $userDetails->id;
                $this->user_model->insertUserContacts($contacts);

                if (count($userDetails) > 0) {
                    //Get the last insert username
                    $condition = array('users.activation_key' => $this->uri->segment(3));
                    $registerusers = $this->user_model->getUsers($condition);

                    $registerusers = $registerusers->row();
                    //Send email to the user after registration
                    $conditionUserMail = array('email_templates.type' => 'registration');
                    $result = $this->email_model->getEmailSettings($conditionUserMail);

                    $rowUserMailConent = $result->row();

                    $splVars = array("!site_name" => $this->config->item('site_title'), "!username" => $updateData['user_name'], "!password" => $this->input->post('password'), "!usertype" => 'Buyer', "!siteurl" => site_url(), "!contact_url" => site_url('contact'));
                    $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                    $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                    $toEmail = $registerusers->email;
                    $fromEmail = $this->config->item('site_admin_mail');
                    $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);
                    $insertData = array();
                    $insertData['username'] = $this->input->post('username');
                    $insertData['password'] = md5($this->input->post('password'));
                    $expire = 60 * 60 * 24 * 100;
                    $this->auth_model->setUserCookie('user_name', $insertData['username'], $expire);
                    $this->auth_model->setUserCookie('user_password', $insertData['password'], $expire);
                    redirect('users/login');
                }

                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('buyer_confirm_success')));
                redirect('info/index/success');
            }  //Form Validation End
        } //If - Form Submission End	
        //Get Countries
        $this->outputData['countries'] = $this->common_model->getCountries();

        //Get Activation Key
        $activation_key = $this->uri->segment(3, '0');

        //Conditions
        $conditions = array('users.role_id' => '1', 'users.activation_key' => $activation_key);

        $query = $this->user_model->getUsers($conditions);

        if ($query->num_rows == 1) {
            $row = $query->row();
        } else {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('buyer_activationkey_error')));
            redirect('info');
        }

        //Puhal changes To get the Privacy Policy Contents
        $like = array('page.url' => '%privacy%');
        $this->outputData['page_content'] = $this->page_model->getPages(NULL, $like, NULL);

        //Puhal Chnages To get the company and conditions Contents

        $like = array('page.url' => '%ter%');
        $like1 = array('page.url' => '%cond%');
        $this->outputData['page_content1'] = $this->page_model->getPages(NULL, $like, $like1);

        $this->outputData['confirmed_mail'] = $row->email;

        $this->load->view('buyer/buyerConfirm', $this->outputData);
    }

//Function confirm End
    // --------------------------------------------------------------------

    /**
     * editProfile for both buyer and seller for edit his profile
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


        //load validation libraray
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        if ($this->input->post('updateBuyerProfile', TRUE)) {
            //Set rules
            $this->form_validation->set_rules('logo', 'lang:logo_validation', 'callback__logo_check');
            $this->form_validation->set_rules('name', 'lang:seller_name_validation', 'required|trim|min_length[5]|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|min_length[5]|xss_clean');
            $this->form_validation->set_rules('country', 'lang:country_validation', 'required|trim');
            $this->form_validation->set_rules('state', 'lang:state_validation', 'required|trim|xss_clean');
            $this->form_validation->set_rules('city', 'lang:city_validation', 'required|trim|xss_clean');
            if ($this->form_validation->run()) {
                $updateData = array();
                if ($this->input->post('pwd', TRUE) != '') {
                    $updateData['password'] = md5($this->input->post('pwd', TRUE));
                }
                $updateData['name'] = $this->input->post('name', TRUE);
                $updateData['email'] = $this->input->post('email', TRUE);
                $updateData['profile_desc'] = $this->input->post('profile', TRUE);
                $updateData['project_notify'] = $this->input->post('notify_project', TRUE);
                $updateData['message_notify'] = $this->input->post('notify_message', TRUE);
                //echo $this->loggedInUser->logo;exit;
                if (($this->loggedInUser->logo != '') and (isset($this->outputData['file']['file_name']))) {
                    $filepath = $this->config->item('basepath') . 'files/logos/' . $this->loggedInUser->logo;
                    //pr($this->outputData['file']);exit;
                    @unlink($filepath);
                    if (isset($this->outputData['file']['file_name'])) {

                        $updateData['logo'] = $this->outputData['file']['file_name'];

                        $thumb1 = $this->outputData['file']['file_path'] . $this->outputData['file']['raw_name'] . "_thumb" . $this->outputData['file']['file_ext'];

                        GenerateThumbFile($this->outputData['file']['full_path'], $thumb1, 49, 48);
                    }
                } else {

                    if (isset($this->outputData['file']['file_name'])) {
                        $updateData['logo'] = $this->outputData['file']['file_name'];
                        $thumb1 = $this->outputData['file']['file_path'] . $this->outputData['file']['raw_name'] . "_thumb" . $this->outputData['file']['file_ext'];
                        GenerateThumbFile($this->outputData['file']['full_path'], $thumb1, 49, 48);
                    }
                }

                $updateData['country_symbol'] = $this->input->post('country', TRUE);
                $updateData['state'] = $this->input->post('state', TRUE);
                $updateData['city'] = $this->input->post('city', TRUE);

                //Create User
                $updateKey = array('id' => $this->loggedInUser->id);
                $this->user_model->updateUser($updateKey, $updateData);

                $updateKey1 = array('users.activation_key' => $this->input->post('confirmKey'));
                $query = $this->user_model->getUsers($updateKey1);
                $row = $query->row();
                $userid = $row->id;
                $updateKey2 = array('user_contacts.user_id' => $this->loggedInUser->id);
                $query2 = $this->user_model->getUserContacts($updateKey2);
                $userDetails = $query2->row();

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
                //Send email to the buyer with the update profile details
                if ($this->input->post('pwd', TRUE))
                    $data1 = 'Password :' . $this->input->post('pwd') . '\n';
                else
                    $data1 = '';

                if ($this->input->post('name', TRUE))
                    $data2 = 'Company Name :' . $this->input->post('name', TRUE) . '\n';
                else
                    $data2 = '';

                if ($this->input->post('email', TRUE))
                    $data3 = 'Email Id :' . $this->input->post('email') . '\n';
                else
                    $data3 = '';

                if ($this->input->post('profile', TRUE))
                    $data4 = 'Profile Description :' . $this->input->post('profile') . '\n';
                else
                    $data4 = '';

                if ($this->input->post('notify_project', TRUE))
                    $data5 = 'Project Notify :' . $this->input->post('notify_project') . '\n';
                else
                    $data5 = '';

                if ($this->input->post('notify_message', TRUE))
                    $data6 = 'Message Notify :' . $this->input->post('notify_message') . '\n';
                else
                    $data6 = '';

                if ($this->input->post('country', TRUE)) {
                    $condition = array('country.country_symbol' => $this->input->post('country'));
                    $country = $this->common_model->getCountries($condition);
                    $country = $country->row();
                    $data7 = 'Country             :' . $country->country_name . '\n';
                }
                else
                    $data7 = '';

                if ($this->input->post('city', TRUE))
                    $data8 = 'City :' . $this->input->post('city') . '\n';
                else
                    $data8 = '';

                if ($this->input->post('state', TRUE))
                    $data9 = 'State :' . $this->input->post('state') . '\n';
                else
                    $data9 = '';

                if ($this->input->post('contact_msn', TRUE))
                    $data10 = 'MSN ID :' . $this->input->post('contact_msn') . '\n';
                else
                    $data10 = '';

                if ($this->input->post('contact_gtalk', TRUE))
                    $data11 = 'Gtalk ID :' . $this->input->post('contact_gtalk') . '\n';
                else
                    $data11 = '';

                if ($this->input->post('contact_yahoo', TRUE))
                    $data12 = 'Yahoo Id :' . $this->input->post('contact_yahoo') . '\n';
                else
                    $data12 = '';

                if ($this->input->post('contact_skype', TRUE))
                    $data12 = 'Skype Id :' . $this->input->post('contact_skype') . '\n';
                else
                    $data12 = '';

                //Send email to the user after update profile
                $conditionUserMail = array('email_templates.type' => 'profile_update');
                $result = $this->email_model->getEmailSettings($conditionUserMail);
                $rowUserMailConent = $result->row();

                $splVars = array("!site_name" => $this->config->item('site_title'), "!username" => $this->loggedInUser->user_name, "!siteurl" => site_url(), "!contact_url" => site_url('contact'), "!data1" => $data1, "!data2" => $data2, "!data3" => $data3, "!data4" => $data4, "!data5" => $data5, "!data6" => $data6, "!data7" => $data7, "!data8" => $data8, "!data9" => $data9, "!data10" => $data10, "!data11" => $data11, "!data12" => $data12);
                $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailConent->mail_body, $splVars);
                $toEmail = $this->loggedInUser->email;
                $fromEmail = $this->config->item('site_admin_mail');
                $this->email_model->sendHtmlMail($toEmail, $fromEmail, $mailSubject, $mailContent);

                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('update_buyer_confirm_success')));
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

        $conditions = array('user_contacts.user_id' => $this->loggedInUser->id);
        $this->outputData['userContactInfo'] = $this->user_model->getUserContacts($conditions);
        $this->outputData['userContact'] = $this->outputData['userContactInfo']->row();

        $this->load->view('buyer/editBuyerProfile', $this->outputData);
    }

//Function editProfile End
    // --------------------------------------------------------------------

    /**
     * Loads _logo_check for uploading
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function _logo_check() {
        if (isset($_FILES) and $_FILES['logo']['name'] == '')
            return true;

        $config['upload_path'] = 'files/logos/';
        $config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF';
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

//Function _logo_check End
    // --------------------------------------------------------------------

    /**
     * Check for buyer mail id
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function _check_buyer_email($mail) {

        //language file
        $this->lang->load('enduser/buyerSignup', $this->config->item('language_code'));
        //Get Role Id For Buyers
        $role_id = $this->user_model->getRoleId('buyer');

        //Conditions
        $conditions = array('users.email' => $mail, 'users.role_id' => $role_id);
        $result = $this->user_model->getUsers($conditions);

        $conditions2 = array('bans.ban_value' => $mail, 'bans.ban_type' => 'EMAIL');
        $result2 = $this->user_model->getBans($conditions2);
        if ($result->num_rows() == 0 && $result2->num_rows() == 0) {
            return true;
        } else {
            $this->form_validation->set_message('_check_buyer_email', $this->lang->line('buyer_email_check'));
            return false;
        }//If end 
    }

//Function _check_buyer_email End
    // --------------------------------------------------------------------

    /**
     * Check for buyer mail id
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function _check_resendbuyer_email($mail) {

        //language file
        $this->lang->load('enduser/buyerSignup', $this->config->item('language_code'));
        //Get Role Id For Buyers
        $role_id = $this->user_model->getRoleId('buyer');

        //Conditions
        $conditions = array('users.email' => $mail, 'users.role_id' => $role_id, 'users.user_status' => '0');
        $result = $this->user_model->getUsers($conditions);
        $conditionsmail = array('users.email' => $mail, 'users.role_id' => $role_id);
        $resultmail = $this->user_model->getUsers($conditionsmail);

        $conditions2 = array('bans.ban_value' => $mail, 'bans.ban_type' => 'EMAIL');
        $result2 = $this->user_model->getBans($conditions2);
        //print_r($result2->num_rows() );
        if ($result2->num_rows() == 0 && $result->num_rows() == 1) {
            return true;
        } else if ($result2->num_rows() == 0 && $resultmail->num_rows() != 0) {
            $this->form_validation->set_message('_check_resendbuyer_email', $this->lang->line('buyer_email_ban'));
            return false;
        } else if ($result2->num_rows() != 0 || $resultmail->num_rows() == 0) {
            $this->form_validation->set_message('_check_resendbuyer_email', $this->lang->line('not_registered'));
            return false;
        }//If end 
    }

//Function _check_resendbuyer_email End
    // --------------------------------------------------------------------

    function _check_username($username) {
        //language file
        $this->lang->load('enduser/buyerSignup', $this->config->item('language_code'));

        //Get Role Id For Buyers
        $role_id = $this->user_model->getRoleId('buyer');

        //Conditions
        $conditions = array('users.user_name' => $username, 'users.role_id' => $role_id);
        $result = $this->user_model->getUsers($conditions);

        $conditions2 = array('bans.ban_value' => $username, 'bans.ban_type' => 'USERNAME');
        $result2 = $this->user_model->getBans($conditions2);

        if ($result->num_rows() == 0 && $result2->num_rows() == 0) {
            return true;
        } else {

            $this->form_validation->set_message('_check_username', $this->lang->line('username_unique'));

            return false;
        }//If end 
    }

//Function  _check_usernam End
    // --------------------------------------------------------------------

    /**
     * Loads _check_activation_key for buyer
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
            $this->form_validation->set_message('_check_activation_key', $this->lang->line('activation_key_validation'));
            return false;
        }
    }

//Function _check_activation_key End
    // --------------------------------------------------------------------

    /**
     * View projects posted by a buyer
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function viewMyProjects() {
        $this->load->helper('reviews');
        //Load Language
        $this->lang->load('enduser/viewProject', $this->config->item('language_code'));

        //Check For Buyer Session
        if (!isBuyer()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a buyer to view projects')));
            redirect('info');
        }


        $page = $this->uri->segment(3, '0');

        if (isset($page) === false or empty($page)) {
            $page = '1';
        }


        $page_rows = $this->config->item('listing_limit');

        $max = array($page_rows, ($page - 1) * $page_rows);

        //Get Sorting order
        $field = $this->uri->segment(4, '0');
        $order = $this->uri->segment(5, '0');

        $orderby = array();
        if ($field)
            $orderby = array($field, $order);

        $this->outputData['order'] = $order;
        $this->outputData['field'] = $field;
        $this->outputData['page'] = $page;


        //Get buyer id
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

            //Conditions
            $order = array('projects.id', 'desc');
            $conditions = array('projects.creator_id' => $buyer_id, 'projects.project_status !=' => '2');
            $this->outputData['myProjects'] = $this->skills_model->getProjects($conditions, NULL, NULL, $max, $order);
            $created = $this->skills_model->getProjects($conditions);

            $conditions = array('projects.creator_id' => $buyer_id, 'projects.project_status =' => '2');
            $this->outputData['closedProjects'] = $this->skills_model->getProjectsByProvider($conditions);

            //Pagination
            $this->load->library('pagination');
            $config['base_url'] = site_url('buyer/viewMyProjects/');
            $config['total_rows'] = $created->num_rows();
            $config['per_page'] = $page_rows;
            $config['cur_page'] = $page;
            $this->pagination->initialize($config);
            $this->outputData['pagination'] = $this->pagination->create_links(false, 'project');
        }
        $this->load->view('buyer/myProjects', $this->outputData);
    }

//Function viewMyProjects End
    // --------------------------------------------------------------------

    function bookmarkProjects() {


        $buyer_id = $this->loggedInUser->id;

        //Get bookmark projects
        $condition_bookmark = array('bookmark.creator_id' => $buyer_id);
        $bookMark1 = $this->skills_model->getBookmark($condition_bookmark);

        //Get all users
        $this->outputData['getUsers'] = $this->user_model->getUsers();


        //pagination limit
        $page_rows = $this->config->item('mail_limit');
        $start = $this->uri->segment(3, 0);

        $limit[0] = $page_rows;
        $limit[1] = ($start - 1 ) * $page_rows;

        //Get all message trasaction with some limit
        $bookMark = $this->skills_model->getBookmark($condition_bookmark, NULL, NULL, $limit);
        $this->outputData['bookMark'] = $bookMark;


        //Pagination
        $this->load->library('pagination');
        $config['base_url'] = site_url('buyer/bookmarkProjects');
        $config['total_rows'] = $bookMark1->num_rows();
        $config['per_page'] = $page_rows;
        $config['cur_page'] = $start;
        $this->pagination->initialize($config);
        $this->outputData['pagination1'] = $this->pagination->create_links2(false, 'bookmarkProjects');

        $page = '0';

        $page_rows = $this->config->item('listing_limit');

        $max = array($page_rows, (1) * $page_rows);

        //Get Sorting order
        $field = $this->uri->segment(4, '0');
        $order = $this->uri->segment(5, '0');

        $orderby = array();
        if ($field)
            $orderby = array($field, $order);

        $this->outputData['order'] = $order;
        $this->outputData['field'] = $field;
        $this->outputData['page'] = $page;

        //Conditions
        $conditions = array('projects.creator_id' => $buyer_id, 'projects.project_status !=' => '2');
        $this->outputData['myProjects'] = $this->skills_model->getProjects($conditions, NULL, NULL, $max, $orderby);
        $created = $this->skills_model->getProjects($conditions);
        $conditions = array('projects.creator_id' => $buyer_id, 'projects.project_status =' => '2');
        $this->outputData['closedProjects'] = $this->skills_model->getProjectsByProvider($conditions);

        //Pagination
        $this->load->library('pagination');
        $config['base_url'] = site_url('buyer/viewMyProjects/');
        $config['total_rows'] = $created->num_rows();
        $config['per_page'] = $page_rows;
        $config['cur_page'] = 0;
        $this->pagination->initialize($config);
        $this->outputData['pagination'] = $this->pagination->create_links(false, 'project');

        $this->load->view('buyer/myProjects', $this->outputData);
    }

    /**
     * View buyer's profile
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function viewProfile() {
        //$userid=$this->uri->segment(3);	
        //$condition = array('portfolio.user_id' => $userid);
        if (isset($this->loggedInUser->id)) {
            $condition = array('portfolio.user_id' => $this->loggedInUser->id);
            $this->outputData['portfolio'] = $this->user_model->getPortfolio($condition);

            //Load Language
        } elseif (!is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You can not access to this page')));
            redirect('info');
        }
        $this->lang->load('enduser/viewProfile', $this->config->item('language_code'));
        $buyerId = $this->uri->segment(3, '0');

        $conditions = array('users.id' => $buyerId);

        $conditions2 = array('user_contacts.user_id' => $buyerId);

        $user = $this->user_model->getUsers($conditions);

        if ($user->num_rows() == 0) {
            //Notification message
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('user_not_available')));
            redirect('info');
        }

        $this->outputData['userDetails'] = $user;
        $urow = $user->row();
        $this->outputData['userContacts'] = $this->user_model->getUserContacts($conditions2);
        $country = $this->common_model->getCountries(array('country_symbol' => $urow->country_symbol));
        $this->outputData['country'] = $country->row();

        $conditions3 = array('projects.project_status' => '0', 'projects.creator_id' => $urow->id);
        $openProjects = $this->skills_model->getProjects($conditions3);
        $this->outputData['openProjects'] = $openProjects;

        $conditions4 = array('projects.project_status' => '2', 'projects.creator_id' => $urow->id);
        $closedProjects = $this->skills_model->getProjects($conditions4);
        $this->outputData['closedProjects'] = $closedProjects;

        $conditions5 = array('projects.project_status' => '3', 'projects.creator_id' => $urow->id);

        $cancelledProjects = $this->skills_model->getProjects($conditions5);

        $this->outputData['cancelledProjects'] = $cancelledProjects;
        $this->load->view('buyer/viewProfile', $this->outputData);
    }

//Function _check_activation_key End
    // --------------------------------------------------------------------

    /**
     * review Sellers
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function reviewSeller() {

        //Load Language
        $this->lang->load('enduser/review', $this->config->item('language_code'));

        //Check For Buyer Session
        if (!isBuyer()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a buyer to review seller')));
            redirect('info');
        }

        if ($this->input->post('reviewSeller')) {
            $insertData = array();
            $insertData['comments'] = $this->input->post('comment', TRUE);
            $insertData['rating'] = $this->input->post('rate', TRUE);
            $insertData['review_type'] = '2';
            $insertData['review_time'] = get_est_time();
            $insertData['project_id'] = $this->input->post('projectid', TRUE);
            $insertData['buyer_id'] = $this->loggedInUser->id;
            $insertData['provider_id'] = $this->input->post('providerid', TRUE);

            //Create Review
            $reviewId = $this->skills_model->createReview($insertData);

            //Update projects
            $this->skills_model->updateProjects($insertData['project_id'], array('provider_rated' => '1'));

            $condition = array('reviews.project_id' => $insertData['project_id']);
            $rev = $this->skills_model->getReviews($condition);

            //Send Mail
            $conditionUserMail = array('email_templates.type' => 'buyer_review');
            $result = $this->email_model->getEmailSettings($conditionUserMail);
            $rowUserMailConent = $result->row();

            //Get Project details
            $condition = array('projects.id' => $insertData['project_id']);
            $projectDetails = $this->skills_model->getProjects($condition, 'projects.project_name');
            $prjRow = $projectDetails->row();

            //Get User details
            $getuser = $this->user_model->getUsers(array('users.id' => $insertData['provider_id']));
            $user = $getuser->row();

            $splVars = array("!buyer_name" => $this->loggedInUser->user_name, "!project_name" => $prjRow->project_name, "!site_name" => base_url(), '!site_title' => $this->config->item('site_title'));
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

                //Update Provider
                $this->skills_model->updateUsers($insertData['provider_id'], array('user_rating' => $rating, 'num_reviews' => $num_reviews, 'tot_rating' => $tot_rating2));

                //Get buyer details
                $getHold = $this->skills_model->getRatingHold(array('rating_hold.user_id' => $this->loggedInUser->id, 'rating_hold.project_id' => $insertData['project_id']));
                $holdRow = $getHold->row();

                if ($getHold->num_rows() > 0) {

                    //Get Provider details
                    $getuser = $this->user_model->getUsers(array('users.id' => $this->loggedInUser->id), 'users.user_rating,users.num_reviews');
                    $buyerRow = $getuser->row();

                    //Rating
                    if ($buyerRow->user_rating == 0)
                        $rating = $holdRow->rating;
                    else
                        $rating = ($buyerRow->user_rating + $holdRow->rating) / 2;

                    //Increase number of reviews
                    $num_reviews = ($buyerRow->num_reviews) + 1;

                    $tot_rating = ($rating * $num_reviews);

                    //Update buyer
                    $this->skills_model->updateUsers($this->loggedInUser->id, array('user_rating' => $rating, 'num_reviews' => $num_reviews, 'tot_rating' => $tot_rating));

                    $condition2 = array('reviews.project_id' => $insertData['project_id'], 'reviews.buyer_id' => $this->loggedInUser->id, 'reviews.review_type' => '1');
                    $getrev = $this->skills_model->getReviews($condition2, 'reviews.id');
                    $revRow = $getrev->row();

                    $this->skills_model->updateReviews($revRow->id, array('reviews.hold' => '0'));
                }
            }
            elseif ($rev->num_rows() == 1) {

                $insertData2 = array();

                $insertData2['rating'] = $insertData['rating'];

                $insertData2['user_id'] = $insertData['provider_id'];

                $insertData2['project_id'] = $insertData['project_id'];

                $this->skills_model->insertRatingHold($insertData2);

                $this->skills_model->updateReviews($reviewId, array('reviews.hold' => '1'));
            }

            //Notification message
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success', $this->lang->line('review_added')));
            redirect('info/index/success');
        }

        if (!is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You can not access to this page')));
            redirect('info');
        }

        //Get project id
        $projectid = $this->uri->segment(3, '0');

        //Get Project details
        $condition = array('projects.id' => $projectid);
        $projectDetails = $this->skills_model->getProjects($condition);
        $this->outputData['projectDetails'] = $projectDetails;
        $prjRow = $projectDetails->row();

        //Get provider details
        $condition3 = array('users.id' => $prjRow->seller_id);
        $providerDetails = $this->user_model->getUsers($condition3);
        $this->outputData['providerDetails'] = $providerDetails->row();

        //Get review details
        $condition2 = array('reviews.project_id' => $projectid, 'reviews.provider_id' => $prjRow->seller_id, 'reviews.review_type' => '2');
        $this->outputData['reviewDetails'] = $this->skills_model->getReviews($condition2);

        $this->load->view('buyer/reviewSeller', $this->outputData);
    }

//Function reviewSeller End
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

        //Get reviews
        $condition2 = array('reviews.buyer_id' => $urow->id, 'reviews.review_type' => '1', 'reviews.hold' => '0');
        $this->outputData['reviewDetails'] = $this->skills_model->getReviews($condition2);

        $this->load->view('buyer/reviews', $this->outputData);
    }

//Function review End

    function remove() {
        $project_id = $this->uri->segment(3);
        $conditions = array('bookmark.project_id' => $project_id, 'bookmark.creator_id' => $this->loggedInUser->id);
        $bookMarks = $this->common_model->deleteTableData('bookmark', $conditions);
        $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', 'Bookmark deleted successfully'));
        redirect('buyer/viewMyProjects/');
    }

}

//End  Buyer Class

/* End of file Buyer.php */
/* Location: ./app/controllers/Buyer.php */
?>
