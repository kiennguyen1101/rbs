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
class Login extends Controller {

	public $outputData;
	public $loggedInUser;

	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Login()
	{
		parent::Controller();
		
		//Check For Admin Logged in
		//if(isAdmin())
			//redirect_admin('home');
		
		//Get Config Details From Db
		$this->config->db_config_fetch();
			
		//Load the language file
		$this->lang->load('admin/common', $this->config->item('language_code'));	
		$this->lang->load('admin/login', $this->config->item('language_code'));
		$this->lang->load('admin/validation',$this->config->item('language_code'));	
		
		//load models required
		$this->load->model('common_model');
		$this->load->model('auth_model');
		$this->load->model('admin_model');
		
		$this->load->model('admin_model');
		$buyer_condtition = array('users.role_id'=>'1');
		$buyer      = $this->admin_model->getUsers($buyer_condtition);
		$programmer_condtition = array('users.role_id'=>'2');
		$programmer      = $this->admin_model->getUsers($programmer_condtition);
		$this->outputData['$buyers'] =  $buyer->num_rows();
		$this->outputData['$programmers'] =  $programmer->num_rows();
		$this->outputData['login'] = 'TRUE';
		
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
	
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		//Get Form Details
		if($this->input->post('loginAdmin'))
		{	
			//Set rules
			$this->form_validation->set_rules('username','lang:username_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('pwd','lang:pwd_validation','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{	
				$username = $this->input->post('username');
				$password = md5($this->input->post('pwd'));
				
				$conditions = array('admin_name'=>$username,'password'=>$password);
				
				if($this->auth_model->loginAsAdmin($conditions))
				{
					//Set Session For Admin
					$this->auth_model->setAdminSession($conditions);
					redirect_admin('home');
				
				} else {
					//Log in attempt failed
				  	$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('login_failed')));
				 	redirect_admin('login');
				}				
			}//If End - Check For Form Validation
		} //IF End- Check For Form Submission	
		
		$this->load->view('admin/login',$this->outputData);
		
	} //Function Index End
	
}
//Class Login End 

/* End of file login.php */
/* Location: ./system/application/controllers/admin/login.php */