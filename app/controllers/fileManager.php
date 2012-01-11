<?php
/**
 * Reverse bidding system FilesManager Class
 *
 * Buyer related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Project 
 * @author		Cogzidel Dev Team
 * @version		Version 1.0
 * @created		December 31 2008
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
class File extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function File()
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
		
		//Get Latest Projects
		$limit_latest = $this->config->item('latest_projects_limit');
		$limit3 = array($limit_latest);
		$this->outputData['latestProjects']	= $this->skills_model->getLatestProjects($limit3);
		
		//Get logged user role
		   $this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		
		//Innermenu tab selection
		$this->outputData['innerClass6']   = '';
		$this->outputData['innerClass6']   = 'selected';
	
	} //Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Home page of the site.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */	
	function index()
	{	
				
		//Check Whether User Logged In Or Not
	    if(isLoggedIn()===false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Dont have rights to access this page')));
			redirect('info');
		}
		$this->load->view('listFiles',$this->outputData );
		
	} //Function index End
		
} //End  FilesManager Class

/* End of file Deposit.php */ 
/* Location: ./app/controllers/Deposit.php */