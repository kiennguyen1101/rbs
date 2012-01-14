<?php
/**
 * Reverse bidding system Users Class
 *
 * Permits admin to manage users and bans.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Settings 
 * @author		
 * @version		
 * @created		Feb 19 2009
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
class Users extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Users()
	{
	   parent::Controller();
	   
	   //Check For Admin Logged in
		if(!isAdmin())
			redirect_admin('login');
	   
	    //Debug Tool
	   	//$this->output->enable_profiler=true;
	   	
		//Get Config Details From Db
		$this->config->db_config_fetch();
		
		// loading the lang files
		$this->lang->load('admin/common', $this->config->item('language_code'));
		$this->lang->load('admin/setting', $this->config->item('language_code'));
		$this->lang->load('admin/validation',$this->config->item('language_code'));
		$this->lang->load('admin/login',$this->config->item('language_code'));
		
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		$this->outputData['login'] = 'TRUE';
		
	} //Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads site settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function index()
	{	
		redirect_admin('users');
	   
	}//End of index Function
	
	// --------------------------------------------------------------------
	
	/**
	 * Add bans for users
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function addBans()
	{	
			//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		if($this->input->post('addBan'))
		{
		
			//Set rules
			
			
			if($this->input->post('type')=='EMAIL')
			$this->form_validation->set_rules('value','lang:ban_value_validation','required|trim|valid_email|xss_clean|callback__check_email|callback__check_email_ban ');
			
				
			if($this->input->post('type')=='USERNAME')
						$this->form_validation->set_rules('value','lang:ban_value_validation','required|trim|xss_clean|callback__check_email|callback__check_username_ban');
			
			
			if($this->form_validation->run())
			{	
			      $insertData                   = array();	
			      $insertData['ban_type']     	= $this->input->post('type');
				  $insertData['ban_value']    	= $this->input->post('value');
				  $insertData['ban_time']   	= get_est_time();
				  if(strtolower($insertData['ban_type']) == 'email')
				    {
				     $condition   =  array('users.email'=>$insertData['ban_value']);
					 $user        =  $this->user_model->getUsers($condition); 
					 $type        =  $insertData['ban_type'];
					} 
				  if(strtolower($insertData['ban_type']) == 'username')
				    {
				     $condition   =  array('users.user_name'=>$insertData['ban_value']);
					 $user        =  $this->user_model->getUsers($condition); 
					 $type        =  $insertData['ban_type'];
					} 	
				  if(isset($user) and count($user->result()) > 0)	
				     {
				    $user           =  $user->row();	
					$conditionUserMail = array('email_templates.type'=>'email_banned');
					$this->load->model('email_model');
					$result            = $this->email_model->getEmailSettings($conditionUserMail);
					$rowUserMailConent = $result->row();
					//echo $records;
					//Update the details 
					$splVars = array("!username" => $user->user_name,"!contact_url" => site_url('contact'),"!site_url" => site_url(),"!type" => $type,'!site_name' => $this->config->item('site_title'));
					//pr($splVars);
					$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
					$mailContent = strtr($rowUserMailConent->mail_body, $splVars);
					$toEmail     = $user->email;
					$fromEmail   = $this->config->item('site_admin_mail');
					$mailContent;
					$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
					 } 
				  //Insert bans
				  $this->user_model->insertBan($insertData);
				  
				 if(strtolower($insertData['ban_type']) == 'username')
				 {
				 	$condition   =  array('users.user_name'=>$insertData['ban_value']);
					$data=array('users.ban_status'=>'1');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
				 }
				 else if(strtolower($insertData['ban_type']) == 'email')
				 {
				 	$condition   =  array('users.email'=>$insertData['ban_value']);
					$data=array('users.ban_status'=>'1');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
				 }
			
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('added_success')));
				  redirect_admin('users/editBans');
			}
		}
		$this->load->view('admin/users/addBans',$this->outputData);
	}//End of addBans Function
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Edit bans for users
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editBans()
	{	
	
		//load validation library
		$this->load->library('form_validation');		
		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Get Groups
		$getbanuser	=	$this->user_model->getBans();
		
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
	    $banduser=	$this->user_model->getBansuser(NULL,NULL,NULL,$limit,$order);
		$this->outputData['banDetails'] = $banduser;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('users/editBans');
		$config['total_rows'] 	 = $getbanuser->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'editBans');

		 //pr($this->outputData['banDetails']->result());exit;
		 $this->load->view('admin/users/editBans',$this->outputData);
	}//End of addBans Function
	
	// --------------------------------------------------------------------
	
	/**
	 * Edit bans for users
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewBan()
	{	
		
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		if($this->input->post('addBan')){
			//Set rules
			if($this->input->post('type')=='EMAIL')
			$this->form_validation->set_rules('value','lang:ban_value_validation','required|trim|valid_email|xss_clean|callback__check_email|callback__check_email_ban ');
			
				
			if($this->input->post('type')=='USERNAME')
						$this->form_validation->set_rules('value','lang:ban_value_validation','required|trim|xss_clean|callback__check_email|callback__check_username_ban');
			
			
			
			if($this->form_validation->run())
			{
				$updateData                   = array();	
				$updateData['ban_type']     	= $this->input->post('type',true);
				$updateData['ban_value']    	= $this->input->post('value',true);
				
				$condition = array('bans.id' => $this->input->post('banid',true));
				
				$suspend_before_update = $this->common_model->getTableData('bans',$condition,'ban_value');
				$suspend_before_update = $suspend_before_update->row();
				$sus_value_before = $suspend_before_update->ban_value;
				
				
				$updateKey = array('bans.id' => $this->input->post('banid',true));
				
				//pr($updateKey);exit;
				//Insert bans
				$this->user_model->updateBan($updateKey,$updateData);
				
				 if(strtolower($updateData['ban_type']) == 'username')
				 {
				 	
					$condition   =  array('users.user_name'=>$sus_value_before);
					$data=array('users.ban_status'=>'0');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
				 
				 	$condition   =  array('users.user_name'=>$updateData['ban_value']);
					$data=array('users.ban_status'=>'1');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
					
				 }
				 else if(strtolower($updateData['ban_type']) == 'email')
				 {
				 
				 	$condition   =  array('users.email'=>$sus_value_before);
					$data=array('users.ban_status'=>'0');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
				 
				 	$condition   =  array('users.email'=>$updateData['ban_value']);
					$data=array('users.ban_status'=>'1');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
					
				 }
				
				
				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				redirect_admin('users/editBans');
			}
		}
		$banid = $this->uri->segment(4,'0');
		$condition = array('bans.id' => $banid);
		 $bans = $this->user_model->getBans($condition);
		 $this->outputData['banDetails'] = $bans->row();
		 //pr($bans->num_rows());exit;
		$this->load->view('admin/users/viewBan',$this->outputData);
	}//End of addBans Function
	
	// --------------------------------------------------------------------
	
	/**
	 * Add users
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function addUsers()
	{	
	
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		if($this->input->post('addUser')){
			//Set rules
			$this->form_validation->set_rules('username','lang:username_validation','required|trim|xss_clean|callback__check_username');
			$this->form_validation->set_rules('password','lang:password_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('email','lang:email_validation','required|trim|valid_email|xss_clean|callback__check_email');
			$this->form_validation->set_rules('name','lang:name_validation','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{	
			      $insertData                   = array();	
			      $insertData['user_name']     	= $this->input->post('username');
				  $insertData['password']    	= md5($this->input->post('password'));
				  $insertData['email']     	= $this->input->post('email');
				  $insertData['name']    	= $this->input->post('name');
				  $insertData['role_id']    	= $this->input->post('type');
				  $insertData['created']   	= get_est_time();
				  $insertData['user_status'] ='1';
				  
				  //Insert User
				  $this->user_model->createUser($insertData);
				  
				 //Create user balance
				 $insertBalance['id']              = '';
				 $insertBalance['user_id']         = $this->db->insert_id();
				 $insertBalance['amount']          = '0';	
				 $this->user_model->createUserBalance($insertBalance);
				  
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('added_success')));
				  redirect_admin('users/viewUsers');
			}
		}
		$this->load->view('admin/users/addUsers',$this->outputData);
	}//End of addBans Function
	
	// --------------------------------------------------------------------
	
	/**
	 * View users
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewUsers()
	{	
	//$condition = array('users.user_name'=>!'');
		$userDetail	=	$this->user_model->getUsers_bal();
		   
		 $start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	 $page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		//echo 'hihihohoho'.$limit;
		$order[0]            ='id';
		$order[1]            ='asc';
		
		//Get Groups
	    $userbalance=	$this->user_model->getUsers_balance(NULL,NULL,NULL,$limit,$order);
		$this->outputData['userDetails'] = $userbalance;
	//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('users/viewUsers');
		$config['total_rows'] 	 = $userDetail->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewUsers');
	 $this->load->view('admin/users/viewUsers',$this->outputData);
	}//End of addBans Function
	
	// --------------------------------------------------------------------
	
	function viewBuyeruser()
	{
	 
		   $condition = array('users.role_id'=>1);
		    $userDetail= $this->user_model->getUsers_bal($condition);
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
	    $userbalance=	$this->user_model->getUsers_balance($condition,NULL,NULL,$limit,$order);
		$this->outputData['userDetails'] = $userbalance;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('users/viewBuyeruser');
		$config['total_rows'] 	 = $userDetail->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewBuyeruser');
	 
		$this->load->view('admin/users/viewUsers',$this->outputData);
		
	}
	
	function viewSelleruser()
	{
	 
		 $condition = array('users.role_id'=>2);
		 $userDetail= $this->user_model->getUsers_bal($condition);
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
	    $userbalance=	$this->user_model->getUsers_balance($condition,NULL,NULL,$limit,$order);
		$this->outputData['userDetails'] = $userbalance;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('users/viewSelleruser');
		$config['total_rows'] 	 = $userDetail->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewSelleruser');
	 
		$this->load->view('admin/users/viewUsers',$this->outputData);
		
	}
	
	function userDetails(){
		$uid =  $this->uri->segment(4,0);
		$condition = array('users.id'=>$uid);
		$this->outputData['userDetails'] = $this->user_model->getUsers_balance($condition,NULL,NULL);
		$this->load->view('admin/users/viewUsers',$this->outputData);
	}
	
	/**
	
	 * edit user
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editUser()
	{	
		//load validation library
		$this->load->library('form_validation');		
		//Load Form Helper
		$this->load->helper('form');
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		if($this->input->post('editUser')){
			//Set rules
			$this->form_validation->set_rules('username','lang:username_validation','required|trim|xss_clean|callback__check_username');
			if($this->input->post('password')!="")
			{
			$this->form_validation->set_rules('password','lang:password_validation','required|trim|min_length[5]|max_length[16]|xss_clean');
			}
			$this->form_validation->set_rules('email','lang:email_validation','required|trim|valid_email|xss_clean|callback__check_email');
			$this->form_validation->set_rules('name','lang:name_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('balamount','lang:balamount_validation','required|trim|xss_clean|integer|abs|is_natural');
			
			if($this->form_validation->run())
			{	
			      $updateData                   = array();	
			      $updateData['user_name']     	= $this->input->post('username');
				  if($this->input->post('password')!="")
				  {
				  $updateData['password']    	= md5($this->input->post('password'));
				  }
				  else
				  {
				   $updateData['password']    	=$this->input->post('passwordold');
				  }
				  $updateData['email']     	= $this->input->post('email');
				  $updateData['name']    	= $this->input->post('name');
				  $updateData['role_id']    	= $this->input->post('type');
				  $balamount    				= $this->input->post('balamount');
				  $updateData['last_activity']   	= get_est_time();
				  
				  $updateKey = array('users.id' => $this->input->post('userid',true));
				  
				 		   
				  //pr($updateData);exit;
				  //Edit user
				  $this->user_model->updateUser($updateKey,$updateData);
				  $this->common_model->updateTableData('user_balance',NULL,array('amount'=>$balamount),array('user_balance.user_id' => $this->input->post('userid',true)));
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				  redirect_admin('users/viewUsers');
			}
			else if($this->input->post('editUser1'))
				{	
			//Set rules
			$this->form_validation->set_rules('username','lang:username_validation','required|trim|xss_clean|callback__check_username');
			$this->form_validation->set_rules('password','lang:password_validation','trim|xss_clean|required');
			$this->form_validation->set_rules('email','lang:email_validation','required|trim|valid_email|xss_clean|callback__check_email');
			$this->form_validation->set_rules('name','lang:name_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('balamount','lang:balamount_validation','required|trim|xss_clean|integer|abs|is_natural');
			if($this->form_validation->run())
			{	
			      $updateData                   = array();	
			      $updateData['user_name']     	= $this->input->post('username');
				  if($this->input->post('password')!='')
				  {
				  $updateData['password']    	= md5($this->input->post('password'));
				  }
				  else
				  {
				  $updateData['password']    	=$this->input->post('passwordold');
				  }
				  $updateData['email']     	= $this->input->post('email');
				  $updateData['name']    	= $this->input->post('name');
				  $updateData['role_id']    	= $this->input->post('type');
				  $balamount    				= $this->input->post('balamount');
				  $updateData['last_activity']   	= get_est_time();
				  
				  $updateKey = array('users.id' => $this->input->post('userid',true));
				  //Edit user
				  $this->user_model->updateUser($updateKey,$updateData);
				  $this->common_model->updateTableData('user_balance',NULL,array('amount'=>$balamount),array('user_balance.user_id' => $this->input->post('userid',true)));
				  
				


  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				  redirect_admin('users/viewUsers');
			}
			}

		}
		
		$userid = $this->uri->segment(4,'0');
		$condition = array('users.id' => $userid);
		$this->outputData['userDetails'] = $this->user_model->getUsers_bal($condition);
		$this->load->view('admin/users/editUsers',$this->outputData);
	}//End of addBans Function
	
	// --------------------------------------------------------------------
	
	/**
	 * Search users
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function searchUsers()
	{	
	
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		if($this->input->post('searchUsers')){
			//Set rules
			if($this->input->post('email') == '')
			$this->form_validation->set_rules('username','lang:username_validation','required|trim|xss_clean');
			if($this->input->post('username') == '')
			$this->form_validation->set_rules('email','lang:email_validation','trim|xss_clean');
			if($this->form_validation->run())
			{
				$uname = $this->input->post('username');
				$email = $this->input->post('email');
				$role_id = $this->input->post('type');
				if($this->input->post('username')){
					$conditions		= array('users.role_id'=>$role_id);
					$like=array('users.user_name'=>$uname);
					$result 		= $this->user_model->getUsers_balance($conditions,NULL,$like,NULL,NULL);
					$this->outputData['userDetails'] = $result;
					$this->load->view('admin/users/viewUsers',$this->outputData);
				}
				elseif($this->input->post('email')){
				
					$conditions		= array('users.role_id'=>$role_id);
					$like=array('users.email'=>$email);
					$result 		= $this->user_model->getUsers_balance($conditions,NULL,$like,NULL,NULL);
					
					$this->outputData['userDetails'] = $result;
					$this->load->view('admin/users/viewUsers',$this->outputData);
				}
				
			}
			else{
			  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please select the Users')));
			  redirect_admin('users/searchUsers');
			}
		}
		else
		$this->load->view('admin/users/searchUsers',$this->outputData);
	}//End of addBans Function
	
	// --------------------------------------------------------------------
	
	/**
	 * Delete ban
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteBan()
	{	
		$this->load->helper('form');
		$userid = $this->uri->segment(4,'0');
	if($userid==0)
	{	
		$getbans	=	$this->user_model->getBans();
		$banlist  =   $this->input->post('banlist');
	if(!empty($banlist ))
	{	
		foreach($banlist as $res)
		 {
		
				$condition = array('bans.id' => $res);
			
				$fields=array('bans.ban_value','bans.ban_type');
			
				$suspend_before_update = $this->common_model->getTableData('bans',$condition,$fields);
				
				$suspend_before_update = $suspend_before_update->row();
				
						
				 if(strtolower($suspend_before_update->ban_type) == 'username')
				 {
			
					$condition   =  array('users.user_name'=>$suspend_before_update->ban_value);
					$data=array('users.ban_status'=>'0');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
				 
						
				 }
				 else if(strtolower($suspend_before_update->ban_type) == 'email')
				 {
				 
				 	$condition   =  array('users.email'=>$suspend_before_update->ban_value);
					$data=array('users.ban_status'=>'0');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
			
				 }
		$condition = array('bans.id' => $res);
		$this->user_model->deleteBan(NULL,$condition);
		  }
	  }
	 else
	 {
	  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please select the Users')));
		  redirect_admin('users/editBans');
	 } 	  
 }
	 else
	 {
	 $condition = array('bans.id' => $userid);
	$fields=array('bans.ban_value','bans.ban_type');
			
				$suspend_before_update = $this->common_model->getTableData('bans',$condition,$fields);
				
				$suspend_before_update = $suspend_before_update->row();
				
						
				 if(strtolower($suspend_before_update->ban_type) == 'username')
				 {
			
					$condition1   =  array('users.user_name'=>$suspend_before_update->ban_value);
					$data=array('users.ban_status'=>'0');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition1);
				 
						
				 }
				 else if(strtolower($suspend_before_update->ban_type) == 'email')
				 {
				 
				 	$condition1   =  array('users.email'=>$suspend_before_update->ban_value);
					$data=array('users.ban_status'=>'0');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition1);
			
				 }
	 }
		  $this->user_model->deleteBan(NULL,$condition);
		//Notification message
		  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('deleted_success')));
		  redirect_admin('users/editBans');
	}//End of deleteBan Function
	
	// --------------------------------------------------------------------
	
	/**
	 * Delete ban
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteUser()
	{	
		 $userid = $this->uri->segment(4,'0');
		 $getUser	=	$this->user_model->getUsers();
		 
		if($userid==0)
		{
			$getUsers	=	$this->user_model->getUsers();
			 $userlist  =   $this->input->post('userlist');
			 if(!empty($userlist))
			  {
				foreach($userlist as $res)
				 {
				  $condition = array('users.id' => $res);
		          $getUser	=	$this->user_model->getUsers($condition);
							$condition = array('users.id' =>$res);
							$this->user_model->deleteUser(NULL,$condition);
							
							$condition=array('bookmark.creator_id'=>$res);
							$this->user_model->deleteBookmark(NULL,$condition);
							
							$condition=array('files.user_id'=>$res);
							$this->user_model->deleteFile(NULL,$condition);
							
							$condition=array('user_balance.user_id'=>$res);
							$this->user_model->deleteBalance(NULL,$condition);
							
							$condition=array('user_categories.user_id'=>$res);
							$this->user_model->deleteCategory(NULL,$condition);
							
							$condition=array('user_contacts.user_id'=>$res);
							$this->user_model->deleteContact(NULL,$condition);
							
							$condition=array('user_list.user_id'=>$res);
							$this->user_model->deleteUserlist(NULL,$condition);		
					 
				 }
				
			}
			else
				{
				$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please select the Users')));
		  redirect_admin('users/viewUsers');
				}	 
	    }
		 else
		 {
		    $condition = array('users.id' => $userid);
		    $getUser	=	$this->user_model->getUsers($condition);
			
				$condition = array('users.id' => $userid);
				$this->user_model->deleteUser(NULL,$condition);
				
				$condition=array('bookmark.id'=>$userid);
				$this->user_model->deleteBookmark(NULL,$condition);
				
				$condition=array('files.user_id'=>$userid);
				$this->user_model->deleteFile(NULL,$condition);
				
				$condition=array('user_balance.user_id'=>$userid);
				$this->user_model->deleteBalance(NULL,$condition);
				
				$condition=array('user_categories.user_id'=>$userid);
				$this->user_model->deleteCategory(NULL,$condition);
				
				$condition=array('user_contacts.user_id'=>$userid);
				$this->user_model->deleteContact(NULL,$condition);
				
				$condition=array('user_list.user_id'=>$userid);
				$this->user_model->deleteUserlist(NULL,$condition); 
			  
			}
				 
		//Notification message
		   $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('deleted_success')));
			  redirect_admin('users/viewUsers'); 
	}//End of deleteBan Function
	
	// --------------------------------------------------------------------
	/**
	* Check Password
	*
	*
	*/

	
	// --------------------------------------------------------------------
	function _check_username($username)
	{

		$role_id = $this->input->post('type');
					
		//Conditions
		if($this->input->post('userid'))
		$conditions		= array('users.user_name'=>$username,'users.role_id'=>$role_id,'users.id !=' => $this->input->post('userid'));
		else
		$conditions		= array('users.user_name'=>$username,'users.role_id'=>$role_id);
		//pr($conditions);exit;
		$result 		= $this->user_model->getUsers($conditions);
		
		$conditions2		= array('bans.ban_value'=>$username,'bans.ban_type'=>'USERNAME');
		$result2 		= $this->user_model->getBans($conditions2);

		if ($result->num_rows()==0 && $result2->num_rows() == 0)
		{
			return true;			

		} else {

			$this->form_validation->set_message('_check_username', $this->lang->line('username_check'));

			return false;

		}//If end 

	}//Function  _check_usernam End
	
	// --------------------------------------------------------------------

	/**

	 * Check for seller mail id

	 *

	 * @access	public

	 * @param	nil

	 * @return	void

	 */ 

	function _check_email($mail)
	{
		//Get Role Id For Buyers

	  	$role_id = $this->input->post('type');
		//echo $this->input->post('userid');
			
		//Conditions
		if($this->input->post('userid'))
		$conditions		= array('users.email'=>$mail,'users.role_id'=>$role_id,'users.id !=' => $this->input->post('userid'));
		else
		$conditions		= array('users.email'=>$mail,'users.role_id'=>$role_id);
		//pr($conditions);exit;
		$result 		= $this->user_model->getUsers($conditions);
		
		$conditions2		= array('bans.ban_value'=>$mail,'bans.ban_type'=>'EMAIL');
		$result2 		= $this->user_model->getBans($conditions2);
		//pr($result->row());exit;
		if ($result->num_rows()==0 && $result2->num_rows() == 0)
		{
			return true;			

		} else {

			$this->form_validation->set_message('_check_email', $this->lang->line('email_check'));

			return false;

		}//If end 

	}//Function  _check_usernam End
	
	/**
	 * Add admin for users
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewAdmin()
	{	
	
		//load validation library
		$this->load->library('form_validation');		
		//Load Form Helper
		$this->load->helper('form');
		$this->load->model('user_model');
		
		$admin =  $this->user_model->viewAdminuser();
		//$this->outputData['admin'] =  $admin;
		
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
	    $admins=	$this->user_model->viewAdmins(NULL,NULL,NULL,$limit,$order);
		$this->outputData['admin'] = $admins;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('users/viewAdmin');
		$config['total_rows'] 	 = $admin->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewAdmin');
		
		$this->load->view('admin/users/viewAdmin',$this->outputData);
	}//End of addBans Function
	
	// --------------------------------------------------------------------
	
	/**
	 * manage admin 
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function manageAdmin()
	{	
	   
		//pr($_REQUEST);exit;
		//Load the admin user lists
	$adminid = $this->uri->segment(4,'0');
	if($adminid==0)
	{
		if($this->input->post('adminList'))
		  {
			 $admin =  $this->input->post('adminList'); 
			$adminList = array();
			for($i=0;$i<count($admin);$i++)
			{
		    	$condition = array('admins.id'=>$admin[$i]);
			    $adminList[$i] = $this->user_model->viewAdmin($condition);
			}
			$this->outputData['admin'] = $adminList;
		  }
		
		//Check the manae button click  	
		if($this->input->post('manageAdmin'))
		  {
			$id =  $this->input->post('id');
			$username =  $this->input->post('username');
			$password = $this->input->post('password');
			$count = count($id);
			for($i=0;$i<$count;$i++)
			{
				$condition = array('admins.id'=>$id[$i]);
				$updateKey['admin_name']     = $username[$i] ;
				$updateKey['password']       = md5($password[$i]) ;
				$this->outputData['admin']   = $this->user_model->updateAdmin($condition,$updateKey);
			}
			redirect_admin('users/viewAdmin');	 
			}	 
		else
		   {
		   	if(isset($this->outputData['admin']) && is_array($this->outputData['admin']))
	       	 $this->load->view('admin/users/editAdmin',$this->outputData);
			 else{
			 $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please select admin')));
			 redirect_admin('users/viewAdmin');
			 }
		   }
		} 	
	  else
	  {
	         $condition = array('admins.id'=>$adminid);
			 $this->outputData['admin']= $this->user_model->viewAdmin($condition);
			 $this->load->view('admin/users/editAdmin',$this->outputData);
	  }	
	}//End of Function
	
	// --------------------------------------------------------------------
	
	/**
	 * delete admin for users
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteAdmin()
	{
	    $this->load->helper('form');
		$adminid = $this->uri->segment(4,'0');
		
	if($adminid==0)
	{	
		$getadmin	=	$this->user_model->getAdmin();
		$adminlist  =   $this->input->post('adminList');
			if(!empty($adminlist))
			{	
				foreach($adminlist as $res)
				 {
						$condition = array('admins.id'=>$res);
						$this->outputData['admin'] = $this->user_model->deleteAdmin($condition);
					 }
		       }
			  else
			  {
			  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please select admin')));
			 redirect_admin('users/viewAdmin');
			  } 	 
		
		  }
	else{
		$condition = array('admins.id'=>$adminid);
		$this->outputData['admin'] = $this->user_model->deleteAdmin($condition);
		}
		$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('deleted_success')));
		redirect_admin('users/viewAdmin');
	}//End of addBans Function
	
	// --------------------------------------------------------------------
	
	/**
	 * search admin for users
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function searchAdmin()
	{
		
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		if($this->input->post('searchAdmin'))
		  {
			$this->form_validation->set_rules('id','lang:Id','required|trim|xss_clean|integer');

   			if($this->form_validation->run())
			{
				$id =  $this->input->post('id');
				$condition = array('admins.id'=>$id);
				$admin =  $this->user_model->viewAdmin($condition);
				$this->outputData['admin'] =  $admin;
				$this->load->view('admin/users/viewAdmin',$this->outputData);
			}
			else
			{
			   $this->load->view('admin/users/searchAdmin');
			}   
		  }
		else
		  {
		  	$this->load->view('admin/users/searchAdmin');
		  }  	
	
	}//End of addBans Function
	
	// --------------------------------------------------------------------
	/**
	/**
	 * Add admin for users
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function addAdmin()
	{	
	
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		if($this->input->post('addAdmin')){
			//Set rules
			$this->form_validation->set_rules('username','lang:username','required|trim|xss_clean');
			$this->form_validation->set_rules('password','lang:password','required|trim|xss_clean');
			if($this->form_validation->run())
			{	
			      $insertData                   = array();	
			      $insertData['id']     	    = '';
				  $insertData['admin_name']    = $this->input->post('username');
				  $insertData['password']    	= md5($this->input->post('password'));
				  
				  $admin =  $this->user_model->viewAdmin(array('admins.admin_name' => $this->input->post('username')));
				 
				  if(count($admin) == 0){
				  //Insert Admin
				  	$this->user_model->addAdmin($insertData);
				  }
				  else{
				  	$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('duplicate_admin')));
				  	redirect_admin('users/viewAdmin');
				  }
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('added_success')));
				  redirect_admin('users/viewAdmin');
			}
		}
      $this->load->view('admin/users/addAdmin',$this->outputData);
	}//End of addBans Function
	
	// --------------------------------------------------------------------
	function addSuspend()
	{
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		
		if($this->input->post('addBan'))
		{
			
			if($this->input->post('type')=='EMAIL')
				$this->form_validation->set_rules('value','lang:Suspend Value','required|trim|xss_clean|callback__check_email|callback__check_email_suspend');
			
			if($this->input->post('type')=='USERNAME')
						$this->form_validation->set_rules('value','lang:Suspend Value','required|trim|xss_clean|callback__check_email|callback__check_username_suspend');
			
			if($this->form_validation->run())
			{	
			      $insertData                   = array();	
			      $insertData['suspend_type']     	= $this->input->post('type');
				  $insertData['suspend_value']    	= $this->input->post('value');
				  $insertData['suspend_time']   	= get_est_time();
				  
				  	if(strtolower($insertData['suspend_type']) == 'email')
				    {
					   	 $condition   =  array('users.email'=>$insertData['suspend_value']);
						 $user        =  $this->user_model->getUsers($condition); 
						 $type        =  $insertData['suspend_type'];
					} 
				  	if(strtolower($insertData['suspend_type']) == 'username')
				    {
				    	 $condition   =  array('users.user_name'=>$insertData['suspend_value']);
						 $user        =  $this->user_model->getUsers($condition); 
						 $type        =  $insertData['suspend_type'];
					} 	
					
					if(isset($user) and count($user->result()) > 0)	
				   {
				    $user           =  $user->row();	
					$conditionUserMail = array('email_templates.type'=>'email_suspended');
					$this->load->model('email_model');
					$result            = $this->email_model->getEmailSettings($conditionUserMail);
					$rowUserMailConent = $result->row();
					//echo $records;
					//Update the details 
					$splVars = array("!username" => $user->user_name,"!contact_url" => site_url('contact'),"!site_url" => site_url(),"!type" => $type,'!site_name' => $this->config->item('site_title'));
					//pr($splVars);
					$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
					$mailContent = strtr($rowUserMailConent->mail_body, $splVars);
					$toEmail     = $user->email;
					$fromEmail   = $this->config->item('site_admin_mail');
					$mailContent;
					$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
					 } 	
					
					
				 $stat=$this->common_model->insertData('suspend',$insertData);
				 
				 if(strtolower($insertData['suspend_type']) == 'username')
				 {
				 	$condition   =  array('users.user_name'=>$insertData['suspend_value']);
					$data=array('users.suspend_status'=>'1');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
				 }
				 else if(strtolower($insertData['suspend_type']) == 'email')
				 {
				 	$condition   =  array('users.email'=>$insertData['suspend_value']);
					$data=array('users.suspend_status'=>'1');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
				 }
				 
				 
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('added_success')));
				  redirect_admin('users/editSuspend');
		
		  }
			
		
			
	
		}
		$this->load->view('admin/users/addSuspend',$this->outputData);
}	


	function editSuspend()
	{	
	
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		//Get Groups
		$getsuspend	=	$this->user_model->getSuspend();
		
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
	    $suspenduser=	$this->user_model->getSuspenduser(NULL,NULL,NULL,$limit,$order);
		$this->outputData['suspend'] = $suspenduser;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('users/editSuspend');
		$config['total_rows'] 	 = $getsuspend->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'editSuspend');

		//$this->outputData['suspend'] =$this->common_model->getTableData('suspend');
		
	   $this->load->view('admin/users/editSuspend',$this->outputData);
	}//End of addBans Function


	function deleteSuspend()
	{	
		
		
		//pr($condition);exit;
	$this->load->helper('form');
	$userid = $this->uri->segment(4,'0');
	
	if($userid==0)
   	{
		$getsuspend	=	$this->user_model->getSuspend();
		$suspendlist  =   $this->input->post('suspendlist');
	 if(!empty($suspendlist))
	 {	
		foreach($suspendlist as $res)
		 {
		
				$condition = array('suspend.id' => $res);
				$fields=array('suspend_value','suspend_type');
				$suspend_before_update = $this->common_model->getTableData('suspend',$condition,$fields);
				$suspend_before_update = $suspend_before_update->row();
				
				 if(strtolower($suspend_before_update->suspend_type) == 'username')
				 {
				 	
					$condition   =  array('users.user_name'=>$suspend_before_update->suspend_value);
					$data=array('users.suspend_status'=>'0');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
				 
						
				 }
				 else if(strtolower($suspend_before_update->suspend_type) == 'email')
				 {
				 
				 	$condition   =  array('users.email'=>$suspend_before_update->suspend_value);
					$data=array('users.suspend_status'=>'0');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
			
				 }
		
		$condition = array('suspend.id' => $res);
		$this->user_model->deleteSuspend(NULL,$condition);
		}
	}
	else
	{
	$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please select the Users')));
	redirect_admin('users/editSuspend');
	}	
 }
else
	{
	    $conditions = array('suspend.id' => $userid);
		$this->user_model->deleteSuspend(NULL,$conditions);
	}	
		//Notification message
		$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('deleted_success')));
		redirect_admin('users/editSuspend');
	}//End of deleteBan Function
	
	
	
	function viewSuspend()
	{	
		
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		if($this->input->post('suspend')){
			//Set rules
			$this->form_validation->set_rules('suspend_value','lang:ban_value_validation','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{
				$updateData                   = array();	
				$updateData['suspend_type']     	= $this->input->post('type',true);
				$updateData['suspend_value']    	= $this->input->post('suspend_value',true);
				
				$condition = array('suspend.id' => $this->input->post('banid',true));
				
				$suspend_before_update = $this->common_model->getTableData('suspend',$condition,'suspend_value');
				$suspend_before_update = $suspend_before_update->row();
				$sus_value_before = $suspend_before_update->suspend_value;
							
				$updateKey = array('suspend.id' => $this->input->post('banid',true));
				$this->common_model->updateTableData('suspend',NULL,$updateData,$updateKey);
				
				
				 if(strtolower($updateData['suspend_type']) == 'username')
				 {
				 	
					$condition   =  array('users.user_name'=>$sus_value_before);
					$data=array('users.suspend_status'=>'0');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
				 
				 	$condition   =  array('users.user_name'=>$updateData['suspend_value']);
					$data=array('users.suspend_status'=>'1');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
					
				 }
				 else if(strtolower($updateData['suspend_type']) == 'email')
				 {
				 
				 	$condition   =  array('users.email'=>$sus_value_before);
					$data=array('users.suspend_status'=>'0');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
				 
				 	$condition   =  array('users.email'=>$updateData['suspend_value']);
					$data=array('users.suspend_status'=>'1');
				 	$this->common_model->updateTableData('users',NULL,$data,$condition);
					
				 }
				
						
				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				redirect_admin('users/editSuspend');
			}
		}
		 $banid = $this->uri->segment(4,'0');
		 $condition = array('suspend.id' => $banid);
		 $bans = $this->user_model->getSuspend($condition);
		 $this->outputData['suspendDetails'] = $bans->row();
		 //pr($bans->num_rows());exit;
		$this->load->view('admin/users/viewSuspend',$this->outputData);
	}//End of addBans Function
	

function _check_username_suspend($username)
{
	
	$conditions=array('suspend_type'=>'USERNAME','suspend_value'=>$username);
	$result=$this->common_model->getTableData('suspend',$conditions);
	if($result->num_rows()>0)
	{	
		$this->form_validation->set_message('_check_username_suspend', $this->lang->line('username_check'));	
		return false; 
	
	}
	else
	 return true;		

}

function _check_username_ban($username)
{
	
	$conditions=array('ban_type'=>'USERNAME','ban_value'=>$username);
	$result=$this->common_model->getTableData('bans',$conditions);
	if($result->num_rows()>0)
	{	
		$this->form_validation->set_message('_check_username_ban', $this->lang->line('username_check'));	
		return false; 
	
	}
	else
	 return true;		

}
function _check_email_suspend($mail)
{
	$this->output->enable_profiler(TRUE);
	$conditions=array('suspend_type'=>'EMAIL','suspend_value'=>$mail);
	$result=$this->common_model->getTableData('suspend',$conditions);
	if($result->num_rows()>0)
	{
		$this->form_validation->set_message('_check_email_suspend', $this->lang->line('email_check'));
		return false;
		
	}	
	else
	 return true;		

}
function _check_email_ban($mail)
{
	$this->output->enable_profiler(TRUE);
	$conditions=array('ban_type'=>'EMAIL','ban_value'=>$mail);
	$result=$this->common_model->getTableData('ban',$conditions);
	if($result->num_rows()>0)
	{
		$this->form_validation->set_message('_check_email_ban', $this->lang->line('email_check'));
		return false;
		
	}	
	else
	 return true;		

}


}
//End  SiteSettings Class

/* End of file siteSettings.php */ 
/* Location: ./app/controllers/admin/siteSettings.php */					
?>