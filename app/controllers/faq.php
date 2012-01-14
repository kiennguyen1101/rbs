<?php
/**
 * Reverse bidding system faq Class
 *
 * Permits admin to handle the skills for this site
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Skills 
 * @author		
 * @version		
 * @created		December 22 2008
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
class Faq extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	   
	/**
	* Constructor 
	*
	* Loads language files and models needed for this controller
	*/
	function Faq()
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
		$this->load->model('faq_model');
		$this->load->model('contact_model');
		$this->load->model('skills_model');
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
		$this->lang->load('enduser/faq', $this->config->item('language_code'));

	}//Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Faqs settings page.
	 *
	 * @access	private
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
		
		//Get Form Data	
		if($this->input->post('faqPosts'))
		{	
			//Set rules
			$this->form_validation->set_rules('faq_email','lang:faq_email_id','required|trim|valid_email|xss_clean');
			$this->form_validation->set_rules('faq_subject','lang:faq_subject','required|trim|xss_clean');
			$this->form_validation->set_rules('faq_comments','lang:faq_comments','required|trim|xss_clean|min_length[25]');
			
			if($this->form_validation->run())
			{	
				//Insert the faq into table
				$enduser_id                       = $this->input->post('faq_email');
				$subject                          = $this->input->post('faq_subject');
				$comments                         = $this->input->post('faq_comments');
				$from 							  = $this->config->item('site_admin_mail');
					
				$insertData              		  = array();	
			    $insertData['email_id']    		  = $this->input->post('faq_email');
				$insertData['subject']   		  = $this->input->post('faq_subject');
				$insertData['comments']     	  = $this->input->post('faq_comments');
				$insertData['created'] 	 		  = get_est_time();
				  
				//Create User
				$this->contact_model->insertContactPost($insertData);
				
				$sent_email = $this->email_model->sendHtmlMail($from,$enduser_id,$subject,$comments);
				 
				//Set the Success Message
				$success_msg = $this->lang->line('confirmation_text');
				  
				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$success_msg));
				redirect('info');
		 	}  //Form Validation End
			
		} //If - Form Submission End
		
		//Get Frequent Asked Questions
		$conditions = array('is_frequent'=> 'Y');

		//Get Groups
		$this->outputData['frequentFaqs']	=	$this->faq_model->getFaqs($conditions);
		
		//Load View
	   	$this->load->view('faqs/viewFaqs',$this->outputData);
	   
	}//End of faq index function
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Faqs settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function view()
	{	
		//Get id of the group	
		$id = is_numeric($this->uri->segment(3))?$this->uri->segment(3):0;
		
		if($id==0)
		{
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');exit;
		}
		//Get Question and answer
		$conditions = array('faqs.id'=> $id);

		//Get a particular faq
		$this->outputData['faqs']	=	$this->faq_model->getFaqs($conditions);
		
		//Load View
	   	$this->load->view('faqs/viewFaq',$this->outputData);
	   
	}//End of view function
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Faqs settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function all()
	{	
		//Get Groups
		$this->outputData['FaqCategoriesWithFaqs']	=	$this->faq_model->getFaqCategoriesWithFaqs();
		
		//Load View
	   	$this->load->view('faqs/viewFaqByCategories',$this->outputData);
	   
	}//End of all function
	
	/**
	 * Loads Faqs for the search faq.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function search(){
	
		$keyword = $this->input->get('keywords');
		$match = $this->input->get('match');
		$like = array('faqs.question' => $keyword);
		$object = $this->faq_model->getFaqs(NULL,$like);
		$this->outputData['faqs']	=	$object;
		$this->outputData['keyword']	=	$keyword;
		//Load View
		$this->load->view('faqs/searchFaqs',$this->outputData);
		//exit;
	}//End of search function
	
}
//End  faq Class

/* End of file faq.php */ 
/* Location: ./app/controllers/faq.php */
?>