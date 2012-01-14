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
	   
	/**
	* Constructor 
	*
	* Loads language files and models needed for this controller
	*/
	function Faq()
	{
	   parent::Controller();
	   
	   //Check For Admin Logged in
		if(!isAdmin())
			redirect_admin('login');
			
		//Get Config Details From Db
		$this->config->db_config_fetch();
	   
	    //Debug Tool
	   	//$this->output->enable_profiler=true;
		
		//Loading the lang files
		$language_code = $this->config->item('language_code');
		$this->lang->load('admin/common',$language_code);
		$this->lang->load('admin/faq',$language_code);
		$this->lang->load('admin/validation',$language_code);
		
		
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		$this->load->model('faq_model');

	}//Controller End 
	
		// --------------------------------------------------------------------
	
	/**
	 * Loads Faqs settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function addFaq()
	{
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('addFaq'))
		{	
			//Set rules
			$this->form_validation->set_rules('faq_category_id','lang:faq_category_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('question','lang:question_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('answer','lang:answer_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('is_frequent','lang:frequent_validation','xss_clean');
			
			if($this->form_validation->run())
			{	
				  //prepare insert data
				  $insertData                  	  	= array();	
			      $insertData['question']  			= $this->input->post('question');
				  $insertData['answer']  			= $this->input->post('answer');
				  $insertData['is_frequent']  		= $this->input->post('is_frequent','N');
				  $insertData['faq_category_id'] 	= $this->input->post('faq_category_id');
				  $insertData['created']			= get_est_time();

				  //Add Groups
				  $this->faq_model->addFaq($insertData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('added_success')));
				   redirect_admin('faq/viewFaqs');
		 	} 
		} //If - Form Submission End
	
		//Get Faq Categories
		$this->outputData['faqCategories']	=	$this->faq_model->getFaqCategories();
		
		//Load View
	   	$this->load->view('admin/faq/addFaq',$this->outputData);		
	}//End of addFaqs function
	
	// --------------------------------------------------------------------
	
	/**
	 * delete Faq.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteFaq()
	{	
		$id = $this->uri->segment(4,0);
		//delete Faq Category
	if($id==0)
	{
		$gerfaq	=	$this->faq_model->getFaqs();
		$faqlist  =   $this->input->post('faqlist');
		if(!empty($faqlist ))
		{	
				foreach($faqlist as $res)
				 {
					$condition = array('faqs.id'=>$res);
					$this->faq_model->deleteFaq(NULL,$condition);
				 }
		}
		else
		{
		$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please select FAQ')));
		redirect_admin('faq/viewFaqs');
		}
		 
	 }
	 else
	 {
	 $condition = array('faqs.id'=>$id);
	 $this->faq_model->deleteFaq(NULL,$condition);
	 }	 
		$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('delete_success')));
		redirect_admin('faq/viewFaqs');
	}
	//Function end
	
	
	function deleteFaqCat()
	{	
		$id = $this->uri->segment(4,0);
		//delete Faq Category
	if($id==0)
	{
		$gerfaqcat	=	$this->faq_model->getFaqCategory();
		$faqlist  =   $this->input->post('faqlist');
		if(!empty($faqlist ))
		{	
				foreach($faqlist as $res)
				 {
					$condition = array('faq_categories.id'=>$res);
					$condition1=array('faqs.faq_category_id'=>$res);
					$this->faq_model->deleteFaqCat(NULL,$condition,$condition1);
				 }
				/* foreach($faqlist as $res)
				 {
					$condition = array('faq_categories.id'=>$res);
				 $this->faq_model->deleteFaqCat1(NULL,$condition);
				 }*/
		}
		else
		{
		$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please select FAQ')));
		redirect_admin('faq/viewFaqCategories');
		}
		 
	 }
	 else
	 {
	 $condition = array('faq_categories.id'=>$id);
	 $condition1=array('faqs.faq_category_id'=>$id);
      $this->faq_model->deleteFaqCat(NULL,$condition,$condition1);
	 
	  //$this->faq_model->deleteFaqCat1(NULL,$condition);
	 }	 
		$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('delete_success')));
		redirect_admin('faq/viewFaqCategories');
	}
	
	
	
	/**
	 * Edit Faq.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editFaq()
	{	
		//Get id of the category	
		$id = is_numeric($this->uri->segment(4))?$this->uri->segment(4):0;
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('editFaq'))
		{	
				
			//Set rules
			$this->form_validation->set_rules('faq_category_id','lang:faq_category_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('question','lang:question_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('answer','lang:answer_validation','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{	
				  //prepare update data
				  $updateData                  	  	= array();	
			      $updateData['faq_category_id']  	= $this->input->post('faq_category_id');
				  $updateData['question']  			= $this->input->post('question');
				  $updateData['answer']  			= $this->input->post('answer');		
				  $updateData['is_frequent']  		= $this->input->post('is_frequent','N');			

				  //Edit Faq Category
				  $this->faq_model->updateFaq($this->input->post('id',true),$updateData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				  redirect_admin('faq/viewFaqs');
		 	} 
		} //If - Form Submission End
		
		//Set Condition To Fetch The Faq Category
		$condition = array('faqs.id'=>$id);
		
		//Get Categories
		$this->outputData['faqs']		=	$this->faq_model->getFaqs($condition);
		
		//Get Faq Categories
		$this->outputData['faqCategories']	=	$this->faq_model->getFaqCategories();
		
		//Load View
	   	$this->load->view('admin/faq/editFaq',$this->outputData);
	   
	}//End of editFaqCategory function
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Faqs settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewFaqs()
	{	
       //Load model
		$this->load->model('faq_model');
		$faqdetails=	$this->faq_model->getFaqs();
		
		//Get Groups
		//$this->outputData['faqs']	=	$this->faq_model->getFaqs();
	    $start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	 $page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		
		$faqdetail  = $this->faq_model->getFaq(NULL,NULL,NULL,$limit,$order);
		
		$this->outputData['faqs'] = $faqdetail;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('faq/viewFaqs');
		$config['total_rows'] 	 = $faqdetails->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewFaqs');
		//Load View
	   	$this->load->view('admin/faq/viewFaqs',$this->outputData);
	   
	}//End of groups function
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Faqs settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewFaqCategories()
	{	
		 //Load model
		$this->load->model('faq_model');
		$faqCategory=$this->faq_model->getFaqCategories();
		
		//Get Groups
		//$this->outputData['faqs']	=	$this->faq_model->getFaqs();
	    $start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	 $page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		//Get Groups
	    $faqcategory=	$this->faq_model->getFaqCategory(NULL,NULL,NULL,$limit,$order);
		$this->outputData['faqCategories'] = $faqcategory;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('faq/viewFaqCategories');
		$config['total_rows'] 	 = $faqCategory->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewFaqCategories');
		
		//Load View
	   	$this->load->view('admin/faq/viewFaqCategories',$this->outputData);
	   
	}//End of groups function
	
	// --------------------------------------------------------------------
	
	/**
	 * Add Faq Category.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function addFaqCategory()
	{	
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('addFaqCategory'))
		{	
			//Set rules
			$this->form_validation->set_rules('faq_category_name','lang:faq_category_name_validation','required|trim|xss_clean|callback_faqCategoryNameCheck');
			
			if($this->form_validation->run())
			{	
				  //prepare insert data
				  $insertData                  	  	= array();	
			      $insertData['category_name']  	= $this->input->post('faq_category_name');
				  $insertData['created']			= get_est_time();

				  //Add Category
				  $this->faq_model->addFaqCategory($insertData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('added_success')));
				  redirect_admin('faq/viewFaqCategories');
		 	} 
		} //If - Form Submission End
		
		//Load View
	   	$this->load->view('admin/faq/addFaqCategory',$this->outputData);
	   
	}//End of addFaqCategory function
	
	// --------------------------------------------------------------------
	
	/**
	 * Edit Faq Category.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editFaqCategory()
	{	
		//Get id of the category	
		$id = is_numeric($this->uri->segment(4))?$this->uri->segment(4):0;
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('editFaqCategory'))
		{	
				
			//Set rules
			$this->form_validation->set_rules('faq_category_name','lang:faq_category_name_validation','required|trim|xss_clean|callback_faqCategoryNameCheck');
			
			if($this->form_validation->run())
			{	
				  //prepare update data
				  $updateData                  	  	= array();	
			      $updateData['category_name']  	= $this->input->post('faq_category_name');

				  //Edit Faq Category
				  $this->faq_model->updateFaqCategory($this->input->post('id',true),$updateData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				  redirect_admin('faq/viewFaqCategories');
		 	} 
		} //If - Form Submission End
		
		//Set Condition To Fetch The Faq Category
		$condition = array('faq_categories.id'=>$id);
		
		//Get Categories
		$this->outputData['faqCategories']		=	$this->faq_model->getFaqCategories($condition);
		
		//Load View
	   	$this->load->view('admin/faq/editFaqCategory',$this->outputData);
	   
	}//End of editFaqCategory function
	
	// --------------------------------------------------------------------
	
	/**
	 * checks whether category name already exists or not.
	 *
	 * @access	private
	 * @param	string name of category
	 * @return	bool true or false
	 */
	function faqCategoryNameCheck($name)
	{
		//Condition to check
		if($this->input->post('operation')!==false and $this->input->post('operation')=='edit')
			$condition = array('faq_categories.category_name'=>$name,'faq_categories.id <>'=>$this->input->post('id'));
		else
			$condition = array('faq_categories.category_name'=>$name);
		
		//Check with table
		$resultCategoryName = $this->faq_model->getFaqCategories($condition);
		
		if ($resultCategoryName->num_rows()>0)
		{
			$this->form_validation->set_message('faqCategoryNameCheck', $this->lang->line('faq_category_name_unique'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}//End of groupNameCheck function
	
}
//End  skillSettings Class

/* End of file skillSettings.php */ 
/* Location: ./app/controllers/admin/skillSettings.php */