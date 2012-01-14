<?php

/**
 * Reverse bidding system EmailSettings Class
 *
 * Permits admin to set the payement settings (ie.Paypal)
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Settings 
 * @author		
 * @version		
 * @created		January 30 2009
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
class EmailSettings extends controller {

    //Global variable  
    public $outputData;  //Holds the output data for each view

    function EmailSettings() {
        parent::controller();

        //Check For Admin Logged in
        if (!isAdmin())
            redirect_admin('login');

        //Get Config Details From Db
        $this->config->db_config_fetch();

        //Debug Tool
        //$this->output->enable_profiler=true;
        // loading the lang files
        $this->lang->load('admin/common', $this->config->item('language_code'));
        $this->lang->load('admin/setting', $this->config->item('language_code'));
        $this->lang->load('admin/validation', $this->config->item('language_code'));

        //Load Models Common to all the functions in this controller
        $this->load->model('common_model');
    }

    /**
     * Loads Email settings page.
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function index() {
        //Load model
        $this->load->model('email_model');

        //Get All Email Termplates List
        $this->outputData['email_settings'] = $this->email_model->getEmailSettings();

        $this->load->view('admin/settings/listEmailSettings', $this->outputData);
    }

//End of index function

    function editTemplate() {
        $this->load->model('emailtemplatemodel');
        $outputData['emailTemplates_list'] = false;
        $outputData['emailTemplates_edit'] = true;
        $template_id = $this->uri->segment(4);
        $this->load->library('validation');
        $this->_emailtemplatesFrm();
        if (!isset($_POST['email_template'])) {
            $outputData['templates'] = $this->emailtemplatemodel->readEmailTemplate($template_id);
            if ($outputData['templates'] != false)
                $outputData['templatesArr'] = $outputData['templates'];
        }
        if (isset($_POST['cancel_template']))
            redirect('admin/emailTemplates');
        if ($this->validation->run() == false)
            $outputData['validationError'] = $this->validation->error_string;
        else {
            if (isset($_POST['email_template'])) {
                $this->emailtemplatemodel->updateEmailTemplate($_POST);
                //Set the flash data
                $this->session->set_flashdata('successMsg', $this->lang->line('emailtemplates_success_msg'));
                redirect('admin/emailTemplates/editTemplate/' . $_POST['template_key']);
            }
        }
        $this->smartyextended->view('../admin/emailtemplates', $outputData);
    }

    function _emailtemplatesFrm() {
        $rules['template_subject'] = 'trim|required|alphanumeric';
        $rules['template_content'] = 'trim|required|alphanumeric';
        $fields['template_subject'] = $this->lang->line('emailtemplates_mail_subject');
        $fields['template_content'] = $this->lang->line('emailtemplates_mail_content');
        $this->validation->set_rules($rules);
        $this->validation->set_fields($fields);
    }

    // --------------------------------------------------------------------

    /**
     * Edit EmailSettings.
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function edit() {
        //Get id of the category	
        $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        //Load model
        $this->load->model('email_model');

        //load validation library
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        if ($this->input->post('editEmailSetting')) {

            //Set rules
            $this->form_validation->set_rules('email_subject', 'lang:email_subject_validation', 'required|trim|xss_clean');
            $this->form_validation->set_rules('email_body', 'lang:email_body_validation', 'required|trim|xss_clean');

            if ($this->form_validation->run()) {

                //prepare update data
                $updateData = array();
                $updateData['mail_subject '] = $this->input->post('email_subject');
                $updateData['mail_body'] = $this->input->post('email_body');



                //Update Email Settings
                $this->email_model->updateEmailSettings($id, $updateData);
                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success', $this->lang->line('updated_success')));
                redirect_admin('emailSettings');
            }
        } //If - Form Submission End
        //Set Condition To Fetch The Email Settings info
        $condition = array('id' => $id);

        //Get Email Settings
        $this->outputData['emailSettings'] = $this->email_model->getEmailSettings($condition);


        //Load View
        $this->load->view('admin/settings/editEmailSettings', $this->outputData);
    }

//End of editEmailSettings function

    /* Add new email settings

      /**
     * delete EmailSettings.
     *
     * @access	private
     * @param	nil
     * @return	void
     */

    function delete() {
        //Load model
        $this->load->model('email_model');
        //Get id of the category	
        $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $condition = array('email_templates.id' => $id);
        $this->email_model->deleteEmailSettings($condition);
        //Notification message
        $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success', $this->lang->line('delete_success')));
        redirect_admin('emailSettings');
    }

//function end	
//---------------------------------------------

    /**
     * add EmailSettings.
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function addemailSettings() {
        //Load model
        $this->load->model('email_model');

        //load validation library
        $this->load->library('form_validation');

        //Load Form Helper
        $this->load->helper('form');

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        if ($this->input->post('addEmailSettings')) {

            //Set rules
            $this->form_validation->set_rules('email_title', 'lang:email_title_validation', 'required|trim|xss_clean|callback_categoryNameCheck');
            $this->form_validation->set_rules('email_subject', 'lang:email_subject_validation', 'required|trim|xss_clean');
            $this->form_validation->set_rules('email_body', 'lang:email_body_validation', 'required|trim|xss_clean');

            if ($this->form_validation->run()) {

                //prepare update data
                $insertData = array();
                $insertData['id'] = '';
                $insertData['type'] = $this->input->post('email_type');
                $insertData['title'] = $this->input->post('email_title');
                $insertData['mail_subject '] = $this->input->post('email_subject');
                $insertData['mail_body'] = $this->input->post('email_body');

                //add Email Settings
                $this->email_model->addEmailSettings($insertData);
                //Notification message
                $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success', $this->lang->line('updated_success')));
                redirect_admin('emailSettings');
            }
        } //If - Form Submission End				
        //Load View
        $this->load->view('admin/settings/addEmailSettings', $this->outputData);
    }

}

//End  EmailSettings Class

/* End of file EmailSettings.php */ 
/* Location: ./app/controllers/admin/EmailSettings.php */