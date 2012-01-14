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
class Contact extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
		
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Contact()
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
		$this->load->model('skills_model');
		$this->load->model('faq_model');
		$this->load->model('contact_model');
		$this->load->model('email_model');
		
		
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		
	    //Get Footer content
		$this->outputData['pages']	= $this->common_model->getPages();	
		
			
		//Get Latest Projects
		$limit_latest = $this->config->item('latest_projects_limit');
		$limit3 = array($limit_latest);
		$this->outputData['latestProjects']	= $this->skills_model->getLatestProjects($limit3);

		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		$this->lang->load('enduser/rss', $this->config->item('language_code'));
       $this->lang->load('enduser/contact', $this->config->item('language_code'));
		
	}//Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Contact page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function index()
	{	
	
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		//pr($_POST);exit;
		
		//Get Form Data	
		if($this->input->post('postContact'))
		{	
			//pr($_POST);exit;
			//Set rules
			$this->form_validation->set_rules('c_email','lang:contact_email_id','required|trim|valid_email|xss_clean');
			$this->form_validation->set_rules('c_subject','lang:contact_subject','required|trim|xss_clean');
			$this->form_validation->set_rules('c_comments','lang:contact_comments','required|trim|xss_clean|min_length[25]');
			
			if($this->form_validation->run())
			{	
				//Insert the contacts contents
				$enduser_id                       = $this->input->post('c_email');
				$subject                          = $this->input->post('c_subject');
				$comments                         = $this->input->post('c_comments');
				$from 							  = $this->config->item('site_admin_mail');
					//echo $enduser_id;exit;
				$insertData              		  = array();	
			    $insertData['email_id']    		  = $this->input->post('c_email');
				$insertData['subject']   		  = $this->input->post('c_subject');
				$insertData['comments']     	  = $this->input->post('c_comments');
				$insertData['created'] 	 		  = get_est_time();
				  
				//Create User
				$this->contact_model->insertContactPost($insertData);
				
				$sent_email = $this->email_model->sendHtmlMail($from,$enduser_id,$subject,$comments);
				 
				//Set the Success Message
				$success_msg = 'Your details sent successfully';
				 //echo $success_msg;exit;
				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$success_msg));
				redirect('info');
		 	}  //Form Validation End
			
		} //If - Form Submission End
		
		//Get Frequently Asked Questions
		$conditions = array('is_frequent'=> 'Y');
		$this->outputData['frequentFaqs']	=	$this->faq_model->getFaqs($conditions);	
		
		//Load View	
	    $this->load->view('contact',$this->outputData);
	}//End of function
}
//End contact Class

/* End of file contact.php */ 
/* Location: ./app/controllers/contact.php */
?>