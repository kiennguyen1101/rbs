<?php      
/** 
 * Reverse bidding system Messages Class
 *
 * Project Messages related functions are handled by this controller.
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
class ProjectNotify extends Controller { 

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function ProjectNotify()
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
		$this->load->model('messages_model');
   		$this->load->model('user_model');

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
		$this->outputData['projects']	= $this->skills_model->getProjects();
		
		//Get USers details
		$this->outputData['Users']	= $this->user_model->getUserslist();
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		$this->lang->load('enduser/project', $this->config->item('language_code'));
	
		//load Helpers
		$this->load->helpers('users');
	    
		//Load the session liberary
		$this->load->library('session');
	} //constructor End 
	// --------------------------------------------------------------------
	
	/**
	 * Load projectNotify Messages Related To A Project
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function notify()
	{	
		//Update projects
		$projectId = $this->input->post('notifyData');
		$projectId = explode(',',$projectId);
		foreach($projectId as $res)
		  {
			$updateKey                         = array('projects.id'=>$res);
		    $updateData['notification_status'] = '1';
		    //$this->skills_model->updateProjects(NULL,$updateData,$updateKey);
		  }
		  
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		
		//projectNotify lists
		$projectNotify =  $this->input->post('notifyData');
		
		$projectNotify = explode(',',$projectNotify);
		$i = 0;
		foreach($projectNotify as $id)
		  {
			$condition = array('projects.id'=>$id);
			$notifyData = $this->skills_model->getProjects($condition);
			$notifyDatas[$i++]  = $notifyData->result(); 
		  }
		$this->outputData['title']             =  'Project Notification';
		$this->outputData['notification']      =  'notification';
     	$this->outputData['notifyData']        =  $notifyDatas; 
	    $this->load->view('messages/projectNotify',$this->outputData);
	} //Function notify  End
	// --------------------------------------------------------------------
	
	/**
	 * Load projectInvitation Messages Related To A Project
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function invitation()
	{	
		//Update projects
		$projectId = $this->input->post('projectInviteData');
		$projectId = explode(',',$projectId);
		foreach($projectId as $res)
		  {
			$updateKey                         = array('project_invitation.id'=>$res);
		  }
		
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		
		//projectInvitation lists
		$invitation =  $this->input->post('projectInviteData');
		$invitation = explode(',',$invitation);
		$i = 0;
		foreach($invitation as $id)
		  {
			$condition = array('projects.id'=>$id);
			$notifyData = $this->skills_model->getProjects($condition);
			$notifyDatas[$i++]  = $notifyData->result(); 
			
		  }
		$this->outputData['title']      =  'Project Invitation';
		$this->outputData['invitation'] =  'invitation';		
		$this->outputData['notifyData'] =  $notifyDatas; 
	    $this->load->view('messages/projectNotify',$this->outputData);	
	} //Function invitation End
	
	// --------------------------------------------------------------------
	
	/**
	 * Load Messages Related To A Project
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function awards()
	{	
		
		//Update projects
		$projectId = $this->input->post('awardsData');
		$projectId = explode(',',$projectId);
		//pr($projectId);
		foreach($projectId as $res)
		  {
			$updateKey                         = array('projects.id'=>$res);
		    $updateData['notification_status'] = '1';
		    //$this->skills_model->updateProjects(NULL,$updateData,$updateKey);
		  }
		//pr($this->input->post('awardsData'));
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		
		//projectAwards lists
		$projectAwards =  $this->input->post('awardsData');
		$projectAwards = explode(',',$projectAwards);
		$i = 0;
		foreach($projectAwards as $id)
		  {
		  	//echo $id;
			$condition = array('projects.id'=>$id);
			$notifyData = $this->skills_model->getProjects($condition);
			$notifyDatas[$i++]  = $notifyData->result(); 
			
		  }
		$this->outputData['title']      =  'Project Award Notification';
		$this->outputData['notifyData'] =  $notifyDatas;
		$this->outputData['awards']     =  'awards'; 
	    $this->load->view('messages/projectNotify',$this->outputData);
	    //$this->load->view('messages/projectMessages',$this->outputData);	
	   
	} //Function awards End
	
	// --------------------------------------------------------------------
	
	/**
	 * Load Messages Related To A Project
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function mailList()
	{	
		//Update projects
		if($this->input->post('newmail'))
	   $projectId = $this->input->post('mailData');
		 
		else if($this->input->post('unread'))
		  $projectId = $this->input->post('mailData1');
		    
		$this->outputData['mailnotifyid'] = $projectId;
		$projectId = explode(',',$projectId);
		$i=0;
		foreach($projectId as $res)
		  {
			$condition = array('messages.id'=>$res);
			$notifyData = $this->messages_model->getProjectMessages($condition);
			$notifyDatas[$i++]  = $notifyData->result(); 
		  }
		$this->outputData['notifyData'] =  $notifyDatas;   
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		//language file - Change this file to do display text modification
		$this->lang->load('enduser/projectMessages', $this->config->item('language_code'));
		
		$this->outputData['notifyData'] =  $notifyDatas; 
	    $this->load->view('messages/projectNotifymessages',$this->outputData);
	} //Function mailList End
	// --------------------------------------------------------------------
	
	/*Mail update for read mail list
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function mailupdate()
	{
		if($this->uri->segment(3))
		{
			$updateKey                         = array('messages.id'=>$this->uri->segment(3));;
			$updateData['notification_status'] = '1';
			$this->messages_model->updateMailnotification(NULL,$updateData,$updateKey);
		}
	} //Function mailupdate End
	// --------------------------------------------------------------------
	
	//Mail update for read mail list
	function invitationupdate()
	{
		if($this->uri->segment(3))
		{
			$updateKey                         = array('project_invitation.id'=>$this->uri->segment(3));
		    $updateData['notification_status'] = '1';
		    //$this->user_model->updateSellerInvitation(NULL,$updateData,$updateKey);
		}
	}//Function invitationupdate End
	// --------------------------------------------------------------------
}

//End  projectnotify Class

/* End of file projectnotify.php */ 
/* Location: ./app/controllers/projectnotify.php */
