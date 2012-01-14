<?php
/**
 * Reverse bidding system Transfer Class
 *
 * Handle Transfering Amount Between Users.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Project 
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
class Transfer extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Transfer()
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
		$this->load->model('messages_model');
		$this->load->model('certificate_model');
		
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
		
		//Load Language
		$this->lang->load('enduser/transferMoney', $this->config->item('language_code'));
		if($this->loggedInUser)
		{
			$user_id           =  $this->loggedInUser->id;  	
			$conditions        =  array('projects.creator_id'=>$user_id);
			$postuserslist	   =  $this->skills_model->getUsersproject($conditions);
		
		//Get logged user role
		   $this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		}
		//Get the users details
		$usersList	   =  $this->user_model->getUserslist();
		$this->outputData['usersList'] =  $usersList->result();	
		
		//Get the projects details
		$condition=array('projects.project_status'=>'2');
		$projectList	   =  $this->skills_model->getUsersproject($condition);
		//pr($projectList->result());
		$this->outputData['projectList'] =  $projectList->result();	
		
		//Innermenu tab selection
		$this->outputData['innerClass3']   = '';
		$this->outputData['innerClass3']   = 'selected';
		
	} //Controller End 
	// --------------------------------------------------------------------
	
	/**
	 * Loads deposit page of the site.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */	
	function index()
	{	
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		  if($this->loggedInUser->suspend_status==1)
		 {
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Suspend Error')));
			redirect('info');
		 }	
		//Check Whether User Logged In Or Not
	    if(isLoggedIn()===false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Dont have rights to access this page')));
			redirect('info');
		}
		$condition=array('projects.project_status'=>'2');
		$projectList	   =  $this->skills_model->getUsersproject($condition);
		$this->outputData['projectList_tranferamount'] =  $projectList->result();	
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Load escrow transaction
		//Load helper file
		$this->load->helper('transaction');
		$creator_condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id);
		$transaction_condition 	 = array('transactions.creator_id'=>$this->loggedInUser->id,'type'=>'Transfer' );
		$url                     = 'transfer/index'; 
		$page                    = $this->uri->segment(3,0); 
		$escrow                  =  loadTransaction($creator_condition,$transaction_condition,$url,$page);
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
        
		//Check User Balance
		$this->load->model('transaction_model');
		$condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);
		$results 	 			 = $this->transaction_model->getBalance($condition_balance);
		if($results->num_rows()>0)
		{
		  //get balance detail
		  $rowBalance = $results->row();		
		  $this->outputData['userAvailableBalance'] = $rowBalance->amount;
		  $avail_balance                            = $rowBalance->amount;
		}			
		
		//Get Form Data	
		if($this->input->post('transferMoney'))
		{
			//Set Validation Rules
			$this->form_validation->set_rules('total','lang:total_validation','required|trim|integer|xss_clean|abs');
			$this->form_validation->set_rules('type_id','lang:buyer_id_validation','required|trim|xss_clean|abs');
			
			if($this->form_validation->run() and $this->input->post('type_id') != '0')
			{
				  //redirect it to appropriate payment method
				  if($this->input->post('total') <= '0')
				  {
				  	//echo $this->input->post('amount');
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Transfer Amount should not be empty')));
			        redirect('transfer');
				  }
				  //Get the Minimum Balance amount	
				  $this->load->model('settings_model');
				  $paymentSettings = $this->settings_model->getSiteSettings();
				  $paymentSettings['PAYMENT_SETTINGS'];
				  $bal_amount = $avail_balance  -  ( $paymentSettings['PAYMENT_SETTINGS'] +  $this->input->post('total') );
				  if( $bal_amount < 0)
				    {
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You are not having Sufficient Balance to Transfer')));
			            redirect('transfer');
					}
				  else
				    {
					  if($this->input->post('paymentMethod')=='paypal')
					  {
					   $view	=  'paypalDeposit';
					   $method  =  'Paypal';
					  }
					  $method  =  'Paypal';
					  $this->outputData['amount']  	  		= $this->input->post('total');
					  //Register Transaction
					  $insertData = array(); 
					  $insertData['creator_id']   			= $this->loggedInUser->id;
					  $insertData['reciever_id'] 			= $this->input->post('users_load');
					  $insertData['project_id']  	  		= $this->input->post('type_id');
					  $insertData['type'] 		 			= 'Transfer';
					  $insertData['amount'] 				= $this->input->post('total');
					  $insertData['transaction_time'] 	 	= get_est_time();
					  $insertData['status'] 				= 'Completed'; //Can Be success,failed,pending
					  $insertData['description'] 			= 'Transfer Amount for';
					  //Check User Balance
					  $condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);
					  $results 	 			     = $this->transaction_model->getBalance($condition_balance);
							
					  if(getSuspendStatus($this->input->post('users_load')))
					  {
					  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('The user you are trying to Transfer is currently Suspended')));
			            redirect('transfer');
					  }		
							
					 //If Record already exists
					  if($results->num_rows()>0)
						{
						 //get balance detail
					 	 $rowBalance = $results->row();
								
						 //Update Amount	
						  $updateKey 			  = array('user_balance.user_id'=>$this->loggedInUser->id);	
						  $updateData 		  = array();
								
						  $updateData['amount'] = $rowBalance->amount   -   $this->input->post('total');
						  $results1 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
						 
						  $condition           =  array('users.id'=>$insertData['reciever_id']); 
						  $registerusers       =  $this->user_model->getUsers($condition); 
						  $registerusers       =  $registerusers->row();
						  
						  //Update Amount to the receiver id	
						  $updateKey 			  = array('user_balance.user_id'=>$insertData['reciever_id']);	
						  $updateData 		     = array();
								
								
								
								
						// Getting the account balance of the receiver ---->stat
							
		  	   				$condition_balance_receiver 		 = array('user_balance.user_id'=>$insertData['reciever_id']);
		     				$results_receiver 	 			     = $this->transaction_model->getBalance($condition_balance_receiver);	
						 
					    	 if($results_receiver->num_rows()>0)		
							 {
								 $rowBalance_receiver=$results_receiver->row();  			  
								 $updateData['amount'] = $rowBalance_receiver->amount   +   $this->input->post('total');
						  	     $results1 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
                             }
							 
						 //  Getting the account balance of the receiver -->End		
															
						//  $updateData['amount'] = $rowBalance->amount   +   $this->input->post('total');
						//  $results1 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
						  
						  $projects_condition  =  array('projects.id'=>$insertData['project_id']); 
						  $projects            =  $this->skills_model->getUsersproject($projects_condition);
						  $projects            =  $projects->row();
						  
						 //Send email to the user after registration
						  $this->load->model('email_model');
						  $conditionUserMail = array('email_templates.type'=>'transaction');
						  $result            = $this->email_model->getEmailSettings($conditionUserMail);
						  $rowUserMailConent = $result->row();
						  $splVars = array("!site_name" => $this->config->item('site_title'),"!username" => $this->loggedInUser->user_name,"!siteurl" => site_url(),"!amount"=>$insertData['amount'],"!type"=>'Transfer',"!others"=>'Receiver Name   :'.$registerusers->user_name,"!others1"=>'Project Name   :'.$projects->project_name, "!contact_url" => site_url('contact'));
						  $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
						  $mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
						  $toEmail     = $this->loggedInUser->email;
						  $fromEmail   = $this->config->item('site_admin_mail');
						  $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
						}	
					  $this->load->model('transaction_model');
					  $res = $this->transaction_model->addTransaction($insertData);
					  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Transaction successfully Completed')));
					  redirect('account');
				    }
			} //Validation Failed
			
		}//If End - Check For Form Submission
		
		$this->load->view('transaction/transferMoney',$this->outputData);
	} //Function index End

   /* *****************************************************
	 * FUNCTION NAME :  load_users
	 * USAGE		 :	This function used to get the users
	 * ******************************************************/
	function load_users()
	{
		if($this->input->post('type_id'))
		{
		//Here the code for select if user choosed in project combo box
		$user_id     =$this->loggedInUser->id;  	
		
		//Get logged user role
		$role   =  $this->loggedInUser->role_id;
		$project_id = $this->input->post('type_id');		
		
		//Get the users details
	    $condition    = array('projects.id'=>$project_id);	
		$usersProject	   =  $this->skills_model->getUsersproject($condition);
		$this->outputData['usersProject'] =  $usersProject->result();	
		foreach($usersProject->result() as $res)
		  {
		    
		  	if($role == '1')
			   $userid = $res->seller_id;
			if($role == '2')
			   $userid = $res->creator_id;
		  }
		
		//Get the users details
	    $this->load->model('user_model');
		$condition    = array('users.id'=>$userid);	
		$usersname	   =  $this->user_model->userProjectdata($condition);
		$this->outputData['usersname'] =  $usersname->result();	
		if($usersname)
		{
			foreach($usersname->result() as $users)
			{
			?>
          <option value="<?php echo $users->id; ?>"> <?php echo $users->user_name; ?></option>
          <?php				
			}
		} 
		?>
			<?php
		} else {
			if($this->input->post('type_id') == '0')
			  {
				//Get logged user role
				   $this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
				   $role                                  =  $this->loggedInUser->role_id;
				   ?>
				<select id='users_load' name="users_load">  
				<?php 
				if($role == '1')
				{ ?>
					<option value="0"> <?php echo '<b>-- '.$this->lang->line('Select Seller').' --</b>'; ?></option>	<?php 
				}
				if($role == '2')
				{ ?>
					<option value="0"> <?php echo '<b>-- '.$this->lang->line('Select Buyer').' --</b>'; ?></option>	<?php  
				}
				  
			  } 
		}
		exit;
	} //Function Load_user
	
	
	
	 /* *****************************************************
	 * FUNCTION NAME :  load_users
	 * USAGE		 :	This function used to get the users
	 * ******************************************************/
	function load_users1()
	{
	//pr($this->uri->segment_array());exit;
		if($this->input->post('type_id') or $this->uri->segment(3))
		{
		//Here the code for select if user choosed in project combo box
		$user_id     =$this->loggedInUser->id;  	
		
		//Get logged user role
		$role   =  $this->loggedInUser->role_id;
		$project_id = $this->uri->segment(3);		
		
		//Get the users details
	    if($this->uri->segment(3))
		   $condition    = array('projects.id'=>$project_id);	
		$usersProject	   =  $this->skills_model->getUsersproject($condition);
		$this->outputData['usersProject'] =  $usersProject->result();	
		foreach($usersProject->result() as $res)
		  {
		    
		  	if($role == '1')
			   $userid = $res->seller_id;
			if($role == '2')
			   $userid = $res->creator_id;
		  }
		
		//Get the users details
	    $this->load->model('user_model');
		$condition    = array('users.id'=>$userid);	
		$usersname	   =  $this->user_model->userProjectdata($condition);
		$this->outputData['usersname'] =  $usersname->result();	
		$default='Everyone';
		?><?php 
		if($usersname)
		{
			$data='<select name="prog_id">';
			foreach($usersname->result() as $users)
			{
			    
				$data.= '<option value="'.$users->id.'">'.$users->user_name.'</option>';
				         		
			}
			$data.='<option value="0">'.$default.'</option>';
			$data.='</select>';
			echo $data;
		} 
		?>
			<?php
		} else {
			if($this->input->post('type_id') == '0')
			  {
				
				//Get logged user role
				$this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
				$role                                  =  $this->loggedInUser->role_id;
				echo 'Please Choose Project';
				  
			  } 
		} 
		exit;
	} //Function Load_Category
	
	function load_users2()
	{
	//pr($this->uri->segment_array());exit;
		if($this->input->post('type_id') or $this->uri->segment(3))
		{
		//Here the code for select if user choosed in project combo box
		$user_id     =$this->loggedInUser->id;  	
		
		//Get logged user role
		$role   =  $this->loggedInUser->role_id;
		$project_id = $this->uri->segment(3);		
		
		//Get the users details
	    if($this->uri->segment(3))
		   $condition    = array('projects.id'=>$project_id);	
		$usersProject	   =  $this->skills_model->getUsersproject($condition);
		$this->outputData['usersProject'] =  $usersProject->result();	
		foreach($usersProject->result() as $res)
		  {
		    
		  	if($role == '1')
			   $userid = $res->seller_id;
			if($role == '2')
			   $userid = $res->creator_id;
		  }
		
		//Get the users details
	    $this->load->model('user_model');
		$condition    = array('users.id'=>$userid);	
		$usersname	   =  $this->user_model->userProjectdata($condition);
		$this->outputData['usersname'] =  $usersname->result();	
		$default='No Buyer';
		?><?php 
		if($usersname)
		{
			$data='<select name="prog_id">';
			
			foreach($usersname->result() as $users)
			{
			   if($users->user_name!='')
			   {
			   $data.= '<option value="'.$users->id.'">'.$users->user_name.'</option>';
			   }
			    else
				{
				 $data.='<option value="0" selected="selected">'.$default.'</option>';
				}
				
				         		
			}
			//$data.='<option value="0">'.$default.'</option>';
			$data.='</select>';
			echo $data;
		} 
		?>
			<?php
		} else {
			if($this->input->post('type_id') == '0')
			  {
				
				//Get logged user role
				$this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
				$role                                  =  $this->loggedInUser->role_id;
				echo 'Please Choose Project';
				  
			  } 
		} 
		exit;
	} //Function Load_Category
	
	
	
}  //End  Transfer Class 
	
	
	/* End of file Transfer.php */ 
/* Location: ./app/controllers/Transfer.php */
?>