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
class Escrow extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Escrow()
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
		$this->load->model('transaction_model');
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
		$this->lang->load('enduser/escrow', $this->config->item('language_code'));
		
		if($this->loggedInUser)
		{
			$user_id     =$this->loggedInUser->id;  	
			$conditions = array('projects.creator_id'=>$user_id);
			$postuserslist	   =  $this->skills_model->getUsersproject($conditions);
			
			//Get logged user role
 		    $this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		}
		
		//Get the users details
		$usersList	   =  $this->user_model->getUserslist();
		$this->outputData['usersList'] =  $usersList->result();	
				
		//Get the projects details
		$projectList	   =  $this->skills_model->getUsersproject();
		$this->outputData['projectList'] =  $projectList->result();	
		
		//Innermenu tab selection
		$this->outputData['innerClass5']   = '';
		$this->outputData['innerClass5']   = 'selected';
	
	} //Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads deposit index page of the site.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */	
	function index()
	{	
	
		//pr($_POST);
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		
		//Check Whether User Logged In Or Not
	    if(isLoggedIn()===false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Dont have rights to access this page')));
			redirect('info');
		}
			
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
        
		//Load escrow transaction
		//Load helper file
		$this->load->helper('transaction');
		$creator_condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id);
		$transaction_condition 	 = array('transactions.creator_id'=>$this->loggedInUser->id,'type'=>'Escrow Transfer' );
		$url                     = 'escrow/index'; 
		$page                    = $this->uri->segment(3,0); 
		$escrow =  loadTransaction($creator_condition,$transaction_condition,$url,$page);

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
		
					
		//Get all the projects details
		//$status1=1;
		//$status2=2;
		
		$cond="projects.project_status in('1','2')";
		$projectList   =   $this->skills_model->getUsersproject_with($cond);
		//echo $this->db->last_query();
		$this->outputData['projectList']    =   $projectList;
		//pr($projectList);
		//Get Form Data	
		if($this->input->post('transferMoney'))
		{
			//Set Validation Rules
			$this->form_validation->set_rules('total','lang:total_validation','required|trim|integer|xss_clean|abs');
			
			if($this->form_validation->run() and $this->input->post('type_id') != '0')
			{
				  //redirect it to appropriate payment method
				  if($this->input->post('total') <= '0')
				  {
				  	//echo $this->input->post('amount');
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Transfer Amount should not be empty')));
			        redirect('escrow');
				  }
				  //Get the Minimum Balance amount	
				  $this->load->model('settings_model');
				  $paymentSettings = $this->settings_model->getSiteSettings();
				  $paymentSettings['PAYMENT_SETTINGS'];
				  $bal_amount = $avail_balance  -  ( $paymentSettings['PAYMENT_SETTINGS'] +  $this->input->post('total') );
				  
				  
				    if( $bal_amount < 0)
				    {
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You are not having Sufficient Balance to Transfer')));
			            redirect('escrow');
					}
				  else
				    {
					  if($this->input->post('paymentMethod')=='paypal')
					  {
					   $view	=  'escrowDeposit';
					   $method  =  'Paypal';
					  }
					  $method  =  'Paypal';
					  $condition = array('projects.id'=>$this->input->post('type_id'));
					  $projectdata   =   $this->skills_model->getUsersproject($condition);
		              $usersdata    =   $projectdata->row();
					  
					  $this->outputData['amount']  	  		= $this->input->post('total');
					  //Register Transaction
					  $insertData = array(); 
					  $insertData['creator_id']   			= $this->loggedInUser->id;
					  $insertData['reciever_id'] 			= $usersdata->seller_id;
					  $insertData['provider_id'] 			= $this->input->post('prog_id');
					  $insertData['buyer_id']   			= $this->loggedInUser->id;	
					  $insertData['project_id']  	  		= $this->input->post('type_id');
					  $insertData['type'] 		 			= 'Escrow Transfer';
					  $insertData['amount'] 				= $this->input->post('total');
					  $insertData['transaction_time'] 	 	= get_est_time();
					  $insertData['status'] 				= 'Pending'; //Can Be success,failed,pending
					  $insertData['description'] 			= $this->lang->line('Escrow Amount Tansfer Through');
					 
									  
					  $this->load->model('transaction_model');
					  $res = $this->transaction_model->addTransaction($insertData);
					  
					  
					    if(getSuspendStatus($usersdata->seller_id))
					  {
					  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('The user you are trying to Transfer is currently Suspended')));
			            redirect('transfer');
					  }		
					  
					  
					  
                      //Check User Balance
					  $condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);
					  $results 	 			 = $this->transaction_model->getBalance($condition_balance);
							
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
						  
						  $projects_condition  =  array('projects.id'=>$insertData['project_id']); 
						  $projects            =  $this->skills_model->getUsersproject($projects_condition);
						  $projects            =  $projects->row();
						  
						 //Send email to the user after made payments
						  $this->load->model('email_model');
						  $conditionUserMail = array('email_templates.type'=>'transaction');
						  $result            = $this->email_model->getEmailSettings($conditionUserMail);
						  $rowUserMailConent = $result->row();
						  	
						  $splVars = array("!site_name" => $this->config->item('site_title'),"!username" => $this->loggedInUser->user_name,"!siteurl" => site_url(),"!amount"=>$this->input->post('total'),"!type"=>'Escrow',"!others1"=>'Project Name   :'.$projects->project_name, "!contact_url" => site_url('contact'));
						 
						  $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
						  $mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
						  $toEmail     = $this->loggedInUser->email;
						  $fromEmail   = $this->config->item('site_admin_mail');
						  $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
						 
						}	
										  
					  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Transaction successfully Completed')));
					  redirect('account');
				    }
			} //Validation end
			
		}//If End - Check For Form Submission
		
		$this->load->view('transaction/escrowDeposit',$this->outputData);
	} //Function index End

    /*
	// Insert escrow release request to escrow_release_request table
	// Param transaction_id
	//return nil
	*/
	function releaseEscrow()
	{
		$request_id =  $this->uri->segment(3,0);
		if($this->uri->segment(3,0))
		  {
		  	//Get the request id to cancel the escrow
			$insertData['id'] = '';
			$insertData['transaction_id']  = $request_id ;
			$insertData['request_date']   = get_est_time();
			$insertData['status']         = 'Release';
			$this->transaction_model->addescorwRelease($insertData);
			
			//Update transaction	
			$updateKey 			  = array('transactions.id'=>$request_id);	
			$updateData 		  = array();
			$updateData['status'] = 'Completed';
			$Update_transaction   = $this->transaction_model->updateTransaction($updateKey,$updateData);
			
			$condition            = array('transactions.id'=>$request_id);	
			$transaction          = $this->transaction_model->getallTransactions($condition);
			$transaction          = $transaction->row();
		    $seller_id        = $transaction->provider_id; 
			$ammount              = $transaction->amount;     
			$condition_balance 	  = array('user_balance.user_id'=>$seller_id);
			$seller_balance 	 			 = $this->transaction_model->getBalance($condition_balance);
			

			if($seller_balance->num_rows()>0)
			{
				$progBalance = $seller_balance->row();	
				$avail_balance_prog =  $progBalance->amount;
			
				$updateKey 			  = array('user_balance.user_id'=>$seller_id);	
				$updateData 		  = array();
								
						
						  $updateData['amount'] = $avail_balance_prog   +   $ammount;
						  $results1 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
									
			}
		
			$condition            =  array('users.id'=>$transaction->reciever_id); 
			$registerusers        =  $this->user_model->getUsers($condition); 
			$registerusers        =  $registerusers->row();
					  
			$projects_condition   =  array('projects.id'=>$transaction->project_id); 
			$projects             =  $this->skills_model->getUsersproject($projects_condition);
			$projects             =  $projects->row();
						  
			//Send email to the user after escrow release
			$this->load->model('email_model');
			$conditionUserMail = array('email_templates.type'=>'transaction');
			$result            = $this->email_model->getEmailSettings($conditionUserMail);
			$rowUserMailConent = $result->row();
						  	
			$splVars = array("!site_name" => $this->config->item('site_title'),"!username" => $this->loggedInUser->user_name,"!siteurl" => site_url(),"!amount"=>$transaction->amount,"!type"=>'Escrow Release',"!others"=>'Receiver Name   :'.$registerusers->user_name,"!others1"=>'Project Name   :'.$projects->project_name, "!contact_url" => site_url('contact'));
						 
			$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
			$mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
			$toEmail     = $this->loggedInUser->email;
			$fromEmail   = $this->config->item('site_admin_mail');
			$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
			redirect('account');
		 }
	}//Function end here
	
//------------------------------------------------------------------------//

 /*
	// Insert escrow release request to escrow_release_request table
	// Param transaction_id
	//return nil
	*/
	function cancelEscrow()
	{
		$request_id =  $this->uri->segment(3,0);
		if($this->uri->segment(3))
		  {
		  	//Get the escrow cancel request details
			$insertData['id'] = '';
			$insertData['transaction_id']  = $request_id ;
			$insertData['request_date']   = get_est_time();
			$insertData['status']         = 'Cancel';
			$this->transaction_model->addescorwRelease($insertData);
			
			//Update transaction	
			$updateKey 			  = array('transactions.id'=>$request_id);	
			$updateData 		  = array();
			$updateData['status'] = 'Cancelled';
			$Update_transaction   = $this->transaction_model->updateTransaction($updateKey,$updateData);
			
			$condition            = array('transactions.id'=>$request_id);	
			$transaction          = $this->transaction_model->getallTransactions($condition);
			$transaction          = $transaction->row();
			
			$condition            =  array('users.id'=>$transaction->reciever_id); 
			$registerusers        =  $this->user_model->getUsers($condition); 
			$registerusers        =  $registerusers->row();
					  
			$projects_condition   =  array('projects.id'=>$transaction->project_id); 
			$projects             =  $this->skills_model->getUsersproject($projects_condition);
			$projects             =  $projects->row();
						  
			//Send email to the user after escrow cancel
			$this->load->model('email_model');
			$conditionUserMail = array('email_templates.type'=>'transaction');
			$result            = $this->email_model->getEmailSettings($conditionUserMail);
			$rowUserMailConent = $result->row();
						  
			$splVars = array("!site_name" => $this->config->item('site_title'),"!username" => $this->loggedInUser->user_name,"!siteurl" => site_url(),"!amount"=>$transaction->amount,"!type"=>'Escrow Cancel',"!others"=>'Receiver Name   :'.$registerusers->user_name,"!others1"=>'Project Name   :'.$projects->project_name, "!contact_url" => site_url('contact'));
			$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
			$mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
			$toEmail     = $this->loggedInUser->email;
			$fromEmail   = $this->config->item('site_admin_mail');
			$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
			
			redirect('account');
		 }
	}	 
	//Function end here
	
	function escrow_project()
	{ 
		$reply='';
		$project_id=$this->uri->segment(3);
		
		// Terminate the process if the project id is 0
		if(!isset($project_id) || $project_id==0)
		{
			echo ' ';
			exit();
		}
		
		// Count the escrow transactions of the project
		
		$condition=array('transactions.type'=>'Escrow Transfer', 'transactions.project_id'=>$project_id);
		$transactions=$this->transaction_model->getTransactions($condition);
		$no_of=$transactions->num_rows();
		
		// Get the escrow due value
		$condition=array('projects.id'=>$project_id);
		$escrow_due_rec=$this->skills_model->getProjects($condition, 'projects.escrow_due, projects.seller_id');
		$escrow_due_row=$escrow_due_rec->row();
		$escrow_due=$escrow_due_row->escrow_due;
		
		$escrow_due=($escrow_due==0)?0:($escrow_due-1);	
		
		// Get the project bid amount
		$condition=array('bids.project_id'=>$project_id, 'bids.user_id'=>$escrow_due_row->seller_id);
		$condition;
		$bids_res=$this->skills_model->getBids($condition);
		$bids=$bids_res->row();
		
	    $project_bid_amount=$bids->bid_amount;	
		
		// Get the paid amount of the project
		$project_paid_amount=0;
		$qry="SELECT SUM(amount) AS project_paid_amount FROM transactions WHERE project_id=$project_id AND (type='Escrow Transfer' OR type='Transfer') AND status='Completed' GROUP BY project_id";
		$transactions_rec=$this->db->query($qry);
		if($transactions_rec->num_rows()>0)
		{
			$transactions=$transactions_rec->row();
			$project_paid_amount=$transactions->project_paid_amount;
		}
		$project_rem_amount=($project_bid_amount-$project_paid_amount);
		$project_rem_amount=($project_rem_amount<0)?0:$project_rem_amount;
		$project_rem_amount=sprintf('%.2f', $project_rem_amount);
		
		$reply=$project_bid_amount.'#'.$project_rem_amount.'#'.$no_of.'#'.$escrow_due;
		
		
		echo $reply;
	}
	
	
} //End  Escrow Class
	
//------------------------------------------------------------------------//

    /* *****************************************************
	 * FUNCTION NAME :  load_users
	 * USAGE		 :	This function used to get the users for made payment for the particular projects
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
				{  ?>
				   <option value="<?php echo $users->id; ?>"> <?php echo $users->user_name; ?></option> <?php				
				}
			} 
	
		} else {
			if($this->input->post('type_id') == '0')
			  {
				 //Get logged user role
				   $this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
				   $role                                  =  $this->loggedInUser->role_id;
				   ?>
				<?php 
				if($role == '1')
				{ ?>
					<option value="0"> <?php echo '<b>-- '.$this->lang->line('Select Seller').' --</b>'; ?></option><?php  
				}
				if($role == '2')
				{ ?>
					<option value="0"> <?php echo '<b>-- '.$this->lang->line('Select Buyer').' --</b>'; ?></option>	<?php   
				 }
				   
			  }
		} 
		exit;
	} //Function load_users
 //End  Transfer Class
	
	/* End of file escrow.php */ 
/* Location: ./app/controllers/escrow.php */