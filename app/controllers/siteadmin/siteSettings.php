<?php
/**
 * Reverse bidding system SiteSettings Class
 *
 * Permits admin to set the site settings like site title,site mission,site offline status.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Settings 
 * @author		Cogzidel Dev Team
 * @version		Version 1.0
 * @created		December 22 2008
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
class SiteSettings extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function SiteSettings()
	{
	   parent::Controller();
	   
	   //Check For Admin Logged in
		if(!isAdmin())
			redirect_admin('login');
	   
	     //Get Config Details From Db
		$this->config->db_config_fetch();
	   
	    //Debug Tool
	   	//$this->output->enable_profiler=true;
	   	
		// loading the lang files
		$this->lang->load('admin/common', $this->config->item('language_code'));
		$this->lang->load('admin/setting', $this->config->item('language_code'));
		$this->lang->load('admin/validation',$this->config->item('language_code'));
		
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		$this->load->model('skills_model');
	} //Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads site settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function index()
	{	
		$this->load->model('settings_model');
		
		//pr($_POST);
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
 
		//Get Form Details
		if($this->input->post('siteSettings'))
		{	
			//Set rules
			$this->form_validation->set_rules('site_title','lang:site_title_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('site_slogan','lang:site_slogan_validation','required|alpha_space|trim|xss_clean');
			$this->form_validation->set_rules('site_admin_mail','lang:site_admin_mail_validation','required|trim|valid_email|xss_clean');
			$this->form_validation->set_rules('site_language','lang:site_language_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('offline_message','lang:offline_message_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('offline_message','lang:offline_message_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('forced_escrow','lang:forced_escrow_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('forced_escrow','lang:forced_escrow_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('payment_settings','lang:payment_settings_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('featured_projects_limit','lang:featured_projects_limit_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('urgent_projects_limit','lang:urgent_projects_limit_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('latest_projects_limit','lang:latest_projects_limit_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('provider_commission_amount','lang:provider_commission_amount_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('featured_projects_amount','lang:featured_projects_amount_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('urgent_projects_amount','lang:urgent_projects_amount_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('hide_projects_amount','lang:hide_projects_amount_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('joblist_projects_amount','lang:joblist_projects_amount_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('joblist_validity_days','lang:joblist_validity_days_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('file_manager_limit','lang:file_manager_limit_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('base_url','lang:base_url_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('featured_projects_amount_cm','lang:featured_projects_amount_cm_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('urgent_projects_amount_cm','lang:urgent_projects_amount_cm_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('hide_projects_amount_cm','lang:hide_projects_amount_cm_validation','numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('private_projects_amount_cm','lang:private_projects_amount_cm_validation','numeric|required|trim|xss_clean');
			
			$this->form_validation->set_rules('twitter_username','lang:twitter_username_validation','alpha_numeric|trim|xss_clean');
			$this->form_validation->set_rules('twitter_password','lang:twitter_password_validation','alpha_numeric|trim|xss_clean');
			//$this->form_validation->set_rules('file','Upload File','callback_upload_file');
			
			$this->form_validation->set_rules('private_project_amount','lang:private_project_amount_validation','numeric|required|trim|xss_clean');
			if($this->form_validation->run())
			{	
			    
				  $updateData                   	= array();
			      $updateData['site_title']     	= $this->input->post('site_title');
				  $updateData['site_language']=$this->input->post('site_language');
				  $updateData['twitter_username']   = $this->input->post('twitter_username');
				  $updateData['twitter_password']   = $this->input->post('twitter_password');
				  $updateData['site_slogan']    	= $this->input->post('site_slogan');
				  $updateData['site_admin_mail']   	= $this->input->post('site_admin_mail');
				  $updateData['site_status']    	= $this->input->post('site_status');
				  $updateData['offline_message'] 	= $this->input->post('offline_message');
				  $updateData['forced_escrow']      =$this->input->post('forced_escrow');
				  $updateData['payment_settings'] 	= $this->input->post('payment_settings');
				  $updateData['featured_projects_limit'] = $this->input->post('featured_projects_limit');
				  $updateData['urgent_projects_limit'] 	= $this->input->post('urgent_projects_limit');
				  $updateData['latest_projects_limit'] 	= $this->input->post('latest_projects_limit');
				  $updateData['provider_commission_amount'] 	= $this->input->post('provider_commission_amount');
				  $updateData['featured_projects_amount'] 	= $this->input->post('featured_projects_amount');
				  $updateData['urgent_projects_amount'] 	= $this->input->post('urgent_projects_amount');
				  $updateData['joblist_projects_amount'] 	= $this->input->post('joblist_projects_amount');
				  $updateData['joblist_validity_days'] 	= $this->input->post('joblist_validity_days');
				  $updateData['hide_projects_amount'] 	= $this->input->post('hide_projects_amount');
				  $updateData['private_project_amount'] 	= $this->input->post('private_project_amount');
				  $updateData['featured_projects_amount_cm'] 	= $this->input->post('featured_projects_amount_cm');
				  $updateData['urgent_projects_amount_cm'] 	= $this->input->post('urgent_projects_amount_cm');
				  $updateData['hide_projects_amount_cm'] 	= $this->input->post('hide_projects_amount_cm');
				  $updateData['private_project_amount_cm'] 	= $this->input->post('private_projects_amount_cm');
				  
				  $updateData['file_manager_limit'] 	= $this->input->post('file_manager_limit');
				  $updateData['base_url'] 	         = $this->input->post('base_url');
				  $updateData['created']        	= get_est_time();
				   if(isset($this->outputData['file']))
				  {
					$this->load->helper('file');
					$Data['file']    		  = $this->outputData['file']['file_name'];
					$thumb1 = $this->outputData['file']['file_path'].'h1_logo.jpg';
					createLogo($this->outputData['file']['full_path'],$thumb1,276,79);
				  }
				 // pr($updateData);
				  //Update Site Settings
				
				  $this->settings_model->updateSiteSettings($updateData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				  redirect_admin('siteSettings');
		 	} 
		} //If - Form Submission End
		
	   $this->outputData['settings']	 = 	$this->settings_model->getSiteSettings();
	   $this->load->view('admin/settings/siteSettings',$this->outputData);
	   
	}//End of index Function
	
	// --------------------------------------------------------------------
	
	
	/**
	 * upload_file for both buyer and programmer
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function upload_file()
	{
		//pr($_FILES);
		$config['upload_path'] = 'app/css/images';
		$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		
		$this->load->library('upload', $config);
	
		if($this->upload->do_upload('file'))
		{
			$this->outputData['file'] = $this->upload->data();
	return true;			
		} else {
			$this->form_validation->set_message('upload_file', $this->upload->display_errors($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag')));
			return false;
		}//If end 
	}//Function upload_file End
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Get database backup.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function dbBackup()
	{	
		
		$this->load->dbutil();
		$this->load->helper(array('file', 'download'));
			
		$prefs = array(
						'format'      => 'zip',
						'filename'    => 'db_backup_' . date ("Ymd") . '.zip',
					);

		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup($prefs);
		write_file('temp/db/' . $prefs['filename'], $backup);
		force_download($prefs['filename'], $backup);
	}//End of database_backup Function
	
}	
//End  SiteSettings Class

/* End of file siteSettings.php */ 
/* Location: ./app/controllers/admin/siteSettings.php */
?>