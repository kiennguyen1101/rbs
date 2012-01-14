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
class Info extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Info()
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
	
	} //Controller End 
	// --------------------------------------------------------------------
	
	/**
	 * Loads Buyer signUp page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function index()
	{	
		//language file
		$this->lang->load('enduser/info', $this->config->item('language_code'));
	    $this->load->view('displayMessage',$this->outputData);
	      
	} //Function index End
	
	// Puhal changes Start. For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2)
	
	function terms()
	{
	   // get the page uri name	   
	   $like = array('page.url'=>'%ter%');
	   $like1 = array('page.url'=>'%cond%');
	   $this->outputData['page_content']	=	$this->page_model->getPages(NULL,$like,$like1);	
		
		/*	
	  pr($this->outputData['page_content']);
	  exit();
	  */
	   //Load View	
	   $this->load->view('termspage',$this->outputData);
	} //function end 
	
	function privacy()
	{
	   // get the page uri name	   
	   $like = array('page.url'=>'%privacy%');
	   $this->outputData['page_content']	=	$this->page_model->getPages(NULL,$like,NULL);	
		
	   $this->load->view('termspage',$this->outputData);
	} //function end 

// Puhal changes End. For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2)
	
	
} //End  info Class

/* End of file info.php */ 
/* Location: ./app/controllers/info.php */