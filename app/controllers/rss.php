<?php
/**
 * Reverse bidding system Rss Class
 *
 * Rss Feed related tasks will handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Rss 
 * @author		Cogzidel Dev Team
 * @version		Version 1.0
 * @created		January 17 2008
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
class Rss extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
		
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Rss()
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
		$this->lang->load('enduser/rss', $this->config->item('language_code'));
		$this->outputData['current_page'] = 'rss';
		
		//Rss Feed Limit - can be modified by user input
		$this->outputData['limit_feed'] = 15;
	}//End Constructor
	// --------------------------------------------------------------------
	
	/**
	 * Loads Rss Home page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function index()
	{
		//Get Categories
		$this->outputData['categories']	=	$this->skills_model->getCategories();
	 	$this->load->view('rss/rssHome',$this->outputData);
	}//Function End
	// --------------------------------------------------------------------
	
	/**
	 * Loads Rss Home page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function show()
	{
		//Get Category Id
		$category_id 	= $this->input->get('cat', TRUE);
		$conditions = array('categories.id'=>$category_id);
		$categories = $this->skills_model->getCategories($conditions);
		 if($categories->num_rows()>0)
			$category	=  $categories->row(); 
		
		//Get Type
		$this->outputData['type']	   		= $this->input->get('type', TRUE);
		
		//Projects List
		$like  = array('project_categories' => $category->category_name);
		$limit = array($this->outputData['limit_feed']);
		$this->outputData['projects']	   =  $this->skills_model->getProjects(NULL,NULL,$like,$limit);
		$this->load->view('rss/listFeeds',$this->outputData);	
	}
	// --------------------------------------------------------------------
	
	/**
	 * Loads Rss Home page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function all()
	{
		$category_name = ' ';
		$this->outputData['rss_title']			= $this->config->item('site_title').'  '.$this->lang->line('Projects');
		
		//Set Limit
		$limit = array($this->outputData['limit_feed']);
		$this->outputData['projects']	   		= $this->skills_model->getProjects(NULL,NULL,NULL,$limit);
		//pr($this->outputData['projects']->result());exit;
		
		$this->outputData['type']	   			= $this->input->get('type', TRUE);
		$this->outputData['rss_description']	= $this->lang->line('The newest projects posted on').$category_name.$this->config->item('site_title');
		$this->load->view('rss/listFeeds',$this->outputData);	
	}//function end 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Rss Home page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function getCustom()
	{
		$this->outputData['rss_title']			= $this->config->item('site_title').'  '.$this->lang->line('Projects');
		$this->outputData['rss_description']	= $this->lang->line('The newest projects posted on').$this->config->item('site_title');
		$limit = array($this->input->get('show',TRUE));
		$key = $this->input->get('key',TRUE);
		$featured = $this->input->get('f',TRUE);
		$cat = $this->input->get('category',TRUE);
		$urgent = $this->input->get('u',TRUE);
		
		$like = array();
		if($key)
		    $like  = array('project_name' => $key,'description' => $key);
		
		$condition = array();
		if($featured)
		$condition = array('projects.is_feature' => $featured);
		elseif($urgent)
		$condition = array('projects.is_urgent' => $urgent);
		elseif($featured && $urgent)
		$condition = array('projects.is_feature' => $featured,'projects.is_urgent' => $urgent);
		//Get Type
		$this->outputData['type']	   		= $this->input->get('d', TRUE);
		
		$cg = array();
		if(is_array($cat)){
			$i = 0;
			foreach($cat as $cate){
				$conditions = array('categories.id'=>$cate);
				$categories = $this->skills_model->getCategories($conditions);
			 
				if($categories->num_rows()>0){
					$category	=  $categories->row();
					$cg[$i] = $category->category_name;
				}
				$i++;
			}
		}
		$this->outputData['projects']	   		= $this->skills_model->getRssProjects($condition,NULL,$like,$limit,NULL,$cg);
		$this->load->view('rss/listFeeds',$this->outputData);
	}//function end
	
} //End  Rss Class

/* End of file Rss.php */ 
/* Location: ./app/controllers/Rss.php */
?>