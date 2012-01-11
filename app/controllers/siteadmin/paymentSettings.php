<?php
/**
 * Reverse bidding system SiteSettings Class
 *
 * Permits admin to set the payement settings (ie.Paypal)
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
class PaymentSettings extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	   
	/**
	* Constructor 
	*
	* Loads language files and models needed for this controller
	*/ 
	function PaymentSettings()
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
		$this->lang->load('admin/common',$this->config->item('language_code'));
		$this->lang->load('admin/setting',$this->config->item('language_code'));
		$this->lang->load('admin/validation',$this->config->item('language_code'));
		
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		$this->load->model('payment_model');

	}//Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads payement settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function index()
	{	
		//Load model
		$this->load->model('settings_model');
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		$paymentGateways = $this->payment_model->getPaymentSettings();
		$this->outputData['paymentGateways'] = $paymentGateways; 
		
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('paypalPaymentSettings'))
		{	
			//Set rules
			$this->form_validation->set_rules('paypal_email_id','lang:paypal_email_id_validation','required|trim|valid_email|xss_clean');
			$this->form_validation->set_rules('paypal_url','lang:paypal_url_validation','required|trim|xss_clean|prep_url');
			$this->form_validation->set_rules('paypal_deposit_minimum','lang:paypal_deposit_minimum_validation','required|trim|xss_clean|is_natural');
			$this->form_validation->set_rules('paypal_commission','lang:paypal_commission_validation','required|trim|xss_clean|numeric');
			$this->form_validation->set_rules('paypal_withdraw_minimum','lang:paypal_withdraw_minimum_validation','required|trim|xss_clean|is_natural');
			$this->form_validation->set_rules('is_deposit_enabled','lang:is_deposit_enabled_validation','required|trim|xss_clean|is_natural');
			$this->form_validation->set_rules('is_withdraw_enabled','lang:is_withdraw_enabled_validation','required|trim|xss_clean|is_natural');
			$this->form_validation->set_rules('deposit_description','lang:deposit_description_id_validation','required|trim|xss_clean|min_length[10]');
			$this->form_validation->set_rules('withdraw_description','lang:withdraw_description_validation','required|trim|xss_clean|min_length[10]');
			
			
			if($this->form_validation->run())
			{	
				  //Set Payment Id
				  $updateKey 						= array('id'=>$this->input->post('id'));
				  
				  $updateData                  	  			= array();	
			      $updateData['mail_id']  					= $this->input->post('paypal_email_id');
				  $updateData['url']    					= $this->input->post('paypal_url');
				  $updateData['deposit_minimum']    		= $this->input->post('paypal_deposit_minimum');
				  $updateData['withdraw_minimum']    		= $this->input->post('paypal_withdraw_minimum');
				  $updateData['is_deposit_enabled']    		= $this->input->post('is_deposit_enabled');
				  $updateData['is_withdraw_enabled']    	= $this->input->post('is_withdraw_enabled');
				  $updateData['deposit_description']    	= $this->input->post('deposit_description');
				  $updateData['withdraw_description']    	= $this->input->post('withdraw_description');
				  $updateData['commission']    				= $this->input->post('paypal_commission');

				  //Update Site Settings
				  $this->payment_model->updatePaymentSettings($updateKey,$updateData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				  redirect_admin('paymentSettings');
		 	} 
		} //If - Form Submission End
		
	   $this->outputData['settings']	 = 	$this->payment_model->getPaymentSettings();
	   $this->load->view('admin/settings/paymentSettings',$this->outputData);
	   
	}//End of index function
	
}
//End  PaymentSettings Class

/* End of file paymentSettings.php */ 
/* Location: ./app/controllers/admin/paymentSettings.php */