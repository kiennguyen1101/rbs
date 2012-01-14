<?php     
/**
 * Reverse bidding system Mail Class
 *
 * Seller related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Buyer 
 * @author		
 * @version		
 * @created		Feburary 04 2009
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
class BookMark extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */ 
	function BookMark()
	{
	   parent::Controller();
	    
	   //Get Config Details From Db
		$this->config->db_config_fetch();
	   
	   //Manage site Status 
		if($this->config->item('site_status') == 1)
		redirect('offline');
	  
	   //Debug Tool
	   //$this->output->enable_profiler=true;		
		
		//Load Models required for this controller
		$this->load->model('common_model');
		$this->load->model('skills_model');
		$this->load->model('bookmark_model');
		
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		
		//Get Footer content
		$this->outputData['pages']	= $this->common_model->getPages();	
		
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		$this->lang->load('enduser/bookMark', $this->config->item('language_code'));
	
	
	} //Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Logged user can bookmark the particular project for feature reference
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function index()
	{	
		//Get the bookmark project details
		$project_id = $this->uri->segment(2);
		$conditions   = array('bookmark.project_id'=>$project_id,'bookmark.creator_id'=>$this->loggedInUser->id);
		$bookMarks    = $this->bookmark_model->getBookmark($conditions);
		$res  =  $bookMarks->num_rows();
		
		if($res <= 0)
		{
			$conditions   = array('projects.id'=>$project_id);
			$projectList  = $this->skills_model->getUsersproject($conditions);
			foreach($projectList->result() as $res)
			  {
				$insertData['id']               = '';
				$insertData['creator_id']       = $this->loggedInUser->id;
				$insertData['creator_name']     = $this->loggedInUser->user_name; 
				$insertData['project_creator']  = $res->creator_id;
				$insertData['project_id']       = $res->id;
				$insertData['project_name']     = $res->project_name;			
				$this->bookmark_model->createBookmark($insertData);
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success','The Project "'.$res->project_name.'" is bookmarked successfully'));
				redirect('project/view/'.$project_id);
			  }
        }
		else
		{
			
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','Project already Bookmarked'));
			redirect('project/view/'.$project_id);
		}
	   redirect('project/view/'.$project_id);
	} //Function index End
	
	
	
}

//End  bookmark Class

/* End of file bookMark.php */ 
/* Location: ./app/controllers/bookMark.php */