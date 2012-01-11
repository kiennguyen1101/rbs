<?php 
/**
 * Reverse bidding system Login Class
 *
 * Permits to login to back end of the system.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Access Controll
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
class Home extends Controller {
	public $outputData;
	public $loggedInUser;

	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Home()
	{
		parent::Controller();
		
		//Check For Admin Logged in
		if(!isAdmin())
			redirect_admin('login');
		
		//Get Config Details From Db
		$this->config->db_config_fetch();
			
		//Load the language file
		$this->lang->load('admin/common', $this->config->item('language_code'));	
		$this->lang->load('admin/login', $this->config->item('language_code'));
		$this->lang->load('admin/validation',$this->config->item('language_code'));	
		
		//load models required
		$this->load->model('common_model');
		$this->load->model('auth_model');
		$this->load->model('skills_model');
		$this->load->model('admin_model');
		
	} //Controller Login End
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads admin login interface.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */
	function index()
	{
		if(!isAdmin())
			redirect_admin('login');
		else
		    $this->outputData['adminlogin'] = '1';
		    	
		
		//Get total buyers 
		$buyer_condtition = array('users.role_id'=>'1');
		$buyer      = $this->admin_model->getUsers($buyer_condtition);
		$this->outputData['buyers'] =  $buyer->num_rows();
		
		//Get total programmer
		$programmer_condtition = array('users.role_id'=>'2');
		$programmer      = $this->admin_model->getUsers($programmer_condtition);
		$this->outputData['programmers'] =  $programmer->num_rows();
		
		//Get total open projects
		//$this->load->model('skills_model');
		$openproject_condition = array('projects.project_status'=>'0');
		$open_projects  =  $this->skills_model->getProjects($openproject_condition);
		$this->outputData['open_projects']   = $open_projects->num_rows();
		
		//Get total closed projects
		$closedproject_condition = array('projects.project_status'=>'2');
		$closed_projects  =  $this->skills_model->getProjects($closedproject_condition);
		$this->outputData['closed_projects']   = $closed_projects->num_rows();
		
		//Get total users 
		$this->outputData['users']      = $this->admin_model->getUsers();
					
		$days=date('Y-m-d',get_est_time());
		$cond1 = '%Y-%m-%d';
		$cond2 = $days;
		$res   = $this->admin_model->gettodayProjects();
		$this->outputData['today']  = $res->num_rows();
	
		//Get total projects for this week
		$days1 = date( 'W,m,Y', time() );
		$cond11 = '%u,%m,%Y';
		$cond21 = $days1;
		$res1 = $this->admin_model->getProjects($cond11,$cond21);
		$this->outputData['week']  = $res1->num_rows();
		
		//Get total projects for this week
		$days2= date( 'm,Y', time() );
		$cond12 = '%m,%Y';
		$cond22 = $days2;
		$res2   = $this->admin_model->getProjects($cond12,$cond22);
		$this->outputData['month']  = $res2->num_rows();
		
		//Get total projects for this week
		$days3 = date( 'Y', time() );
		$cond13 = '%Y';
		$cond23 = $days3;
		$res3   = $this->admin_model->getProjects($cond13,$cond23);
		$this->outputData['year']  = $res3->num_rows();
		
		//Get total projects
		$days4 = date( 'd,m,Y', time() );
		$cond14 = '%d,%m,%Y';
		$cond24 = $days4;
		$status = '0';
		$projects1   = $this->admin_model->getProjectsdetails1($cond14,$cond24,NULL,$status );
		$this->outputData['open'] = $projects1->num_rows();
		
		//Get total projects
		$days5 = date( 'd,m,Y', time() );
		$cond15 = '%d,%m,%Y';
		$cond25 = $days5;
		$status = '2';
		$projects2   = $this->admin_model->getProjectsdetails1($cond15,$cond25,NULL,$status);
		$this->outputData['closed'] = $projects2->num_rows();
		
		//Get the users Balance
		$this->load->model('account_model');
		$res6 = $this->account_model->adminBalance();
		$res6 = $res6->row();
		$this->outputData['adminBalance'] = $res6->amount;
		
		//Get Transaction Information
		$this->load->model('transaction_model');
		$condition 		 = array('transactions.type'=>'Withdraw','transactions.status'=>strtolower('Pending'));
		$transactions1 	 = $this->transaction_model->getTransactions($condition);
		$this->outputData['withdraw'] = $transactions1->num_rows();
		
		//Get total Report Violation
		$reports = $this->admin_model->getReports();
		$this->outputData['reportViolation'] = $reports->num_rows();
		
		//Get total projects
		$this->outputData['projects']      = $this->skills_model->getProjects();
		
		$this->load->view('admin/home',$this->outputData);
		
	} //Function Index End
	
}
//Class Login End 

/* End of file login.php */
/* Location: ./system/application/controllers/admin/login.php */