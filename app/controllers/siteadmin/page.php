<?php
/**
 * Reverse bidding system page Class
 *
 * Permits admin to handle the static pages of the site
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
class Page extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	   
	/**
	* Constructor 
	*
	* Loads language files and models needed for this controller
	*/
	function Page()
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
	    
		$this->lang->load('admin/validation',$language_code);
		
		
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		
		//load model
		$this->load->model('page_model');
		

	}//Controller End 
	
		// --------------------------------------------------------------------
	
	/**
	 * Loads Faqs settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function addPage()
	{
		//load language
		$this->lang->load('admin/page');
		
		
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('addPage'))
		{	
			//Set rules
			$this->form_validation->set_rules('page_title','lang:page_title_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('page_url','lang:page_url_validation','required|trim|xss_clean|callback_pageUrlCheck|callback_pageUrlValid');
			$this->form_validation->set_rules('page_name','lang:page_name_validation','required|trim|xss_clean|callback_pageNameCheck');
			$this->form_validation->set_rules('page_content','lang:page_content_validation','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{	
				  //prepare insert data
				  $insertData                  	  	= array();
				  $insertData['name']  	     	    = $this->input->post('page_name');	
			      $insertData['page_title'] 		= $this->input->post('page_title');
				  $insertData['url']  		       	= $this->input->post('page_url');
				  $insertData['content']  	     	= $this->input->post('page_content');
				  $insertData['created']			= get_est_time();

				  //Add Groups
				  $this->page_model->addpage($insertData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('added_success')));
				   redirect_admin('page/viewPages');
		 	} 
		} //If - Form Submission End
	
		//Get Faq Categories
		$this->outputData['addPages']	=	$this->page_model->getPages();
		
		//Load View
	   	$this->load->view('admin/page/addPage',$this->outputData);
	
	}//Function addPage End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Manage Static Pages View.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewPages()
	{	
        
		//load language
		$this->lang->load('admin/page');
		
		//load model
		$this->load->model('page_model');
		
		//Get Groups
		$this->outputData['pages']	=	$this->page_model->getPages();
			
		
		//Load View
	   	$this->load->view('admin/page/viewPages',$this->outputData);
	   
	}//End of 	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	
	/**
	 * delete Faq.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deletePage()
	{	
		$id = $this->uri->segment(4,0);
		
	if($id==0)	
	{
		$getpages	=	$this->page_model->getPages();
		$pagelist  =   $this->input->post('pagelist');
		if(!empty($pagelist))
		{	
				foreach($pagelist as $res)
				 {
					
					$condition = array('page.id'=>$res);
					$this->page_model->deletePage(NULL,$condition);
				 }
			} 
		else
		{
		$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please select Page')));
	    redirect_admin('page/viewPages');
		}
	}
	else
	{
	$condition = array('page.id'=>$id);
	$this->page_model->deletePage(NULL,$condition);
	}		
		//Notification message
	    $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('delete_success')));
	    redirect_admin('page/viewPages');
	}
	//Function end
	
	/**
	 * Loads Manage Static Pages View.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editPage()
	{	
        
		//load language
		$this->lang->load('admin/page');
		
		//load model
		$this->load->model('page_model');
		
				//Get id of the category	
	   $id = is_numeric($this->uri->segment(4))?$this->uri->segment(4):0;
		
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		if($this->input->post('editPage'))
		{	
           	//Set rules
			$this->form_validation->set_rules('page_title','lang:page_title_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('page_content','lang:page_content_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('page_name','lang:page_content_validation','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{	
				  //prepare update data
				  $updateData                  	  	= array();	
			      $updateData['page_title']  		= $this->input->post('page_title');
				   $updateData['name']  			= $this->input->post('page_name');
				  $updateData['content']  			= $this->input->post('page_content');
				  
				  //Edit Faq Category
				  $updateKey 							= array('page.id'=>$this->uri->segment(4));
				  
				  $this->page_model->updatePage($updateKey,$updateData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				  redirect_admin('page/viewPages');
		 	} 
		} //If - Form Submission End
		
		//Set Condition To Fetch The Faq Category
		$condition = array('page.id'=>$id);
			
	   //Get Groups
		$this->outputData['pages']	=	$this->page_model->getPages($condition);
		
			//Load View
	   	$this->load->view('admin/page/editPage',$this->outputData);
   
	}//End of editPage
	
	/**
	   pageNameCheck
	   
	 * checks whether page name already exists or not.
	 *
	 * @access	private
	 * @param	string name of category
	 * @return	bool true or false
	 */
	function pageNameCheck()
	{
		//Condition to check
		
		if($this->input->post('page_operation')!==false and $this->input->post('page_operation')=='edit')
			$condition = array('page.name'=>$this->input->post('page_name'),'page.url'=>$this->input->post('page_url'));
		else
			$condition = array('page.name'=>$this->input->post('page_name'));
		
		//Check with table
		$resultPageName = $this->page_model->getPages($condition);
		
		if ($resultPageName->num_rows()>0)
		{
			$this->form_validation->set_message('pageNameCheck', $this->lang->line('page_unique'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}//End of pageNameCheck function
	
	/**
	 * checks whether page url already exists or not.
	 *
	 * @access	private
	 * @param	string name of category
	 * @return	bool true or false
	 */
	function pageUrlCheck()
	{
		//Condition to check
		if($this->input->post('page_operation')!==false and $this->input->post('page_operation')=='edit')
			$condition = array('page.url'=>$this->input->post('page_url'));
		else
			$condition = array('page.url'=>$this->input->post('page_url'));
		
		//Check with table
		$resultPageName = $this->page_model->getPages($condition);
		
		if ($resultPageName->num_rows()>0)
		{
			$this->form_validation->set_message('pageUrlCheck', $this->lang->line('url_unique'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}//End of pageUrlValid function
	
	/**
	 * checks whether the url is in correct format or not.
	 *
	 * @access	private
	 * @param	string name of category
	 * @return	bool true or false
	 */
	function pageUrlValid()
	{
		//Condition to check the url
		if($this->input->post('page_operation')!==false and $this->input->post('page_operation')=='add')
		{
		    $str = $this->input->post('page_url');
			$pattern = '/^([-a-z0-9_])+$/i';
			if(!preg_match($pattern,$str))
			  {
			   $this->form_validation->set_message('pageUrlValid', $this->lang->line('page_url_check'));
			   return false;
			  }else
				{
					return TRUE;
				}
					
			}
	   
	}//End of pageUrlValid function
}
//End  Page Class

/* End of file Page.php */ 
/* Location: ./app/controllers/admin/Page.php */