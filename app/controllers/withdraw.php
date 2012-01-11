<?php  
/**
 * Reverse bidding system Withdraw Class
 *
 * This function used to withdraw money.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Project 
 * @author		Cogzidel Dev Team
 * @version		Version 1.0
 * @created		Feburary 03 2009
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
class Withdraw extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */  
	function Withdraw()
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
	    $this->load->model('transaction_model');
		$this->load->model('settings_model');
		$this->load->model('certificate_model');
		
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		
		//Get payment settings for check minimum balance from settings table
		$paymentSettings = $this->settings_model->getSiteSettings();
		$this->outputData['paymentSettings']	= $paymentSettings;	
		$this->outputData['PAYMENT_SETTINGS']   =   $paymentSettings['PAYMENT_SETTINGS'];
		
		if($this->loggedInUser)
		{
			//Get logged user role
			$this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		}
		//Get Footer content
		$this->outputData['pages']	= $this->common_model->getPages();
		
		//Get Latest Projects
		$this->outputData['latestProjects']	= $this->skills_model->getProjects();
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		
		//Load Language
		$this->lang->load('enduser/withdrawMoney', $this->config->item('language_code'));
		
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		//Innermenu tab selection
		$this->outputData['innerClass4']   = '';
		$this->outputData['innerClass4']   = 'selected';
	
	} //Controller End 
	// --------------------------------------------------------------------
	
	/**
	 * Loads Withdraw page lauput
	 *
	 * @access	private
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
		//Load payment model
		$this->load->model('payment_model');
		
		//Load payment settings
		$this->load->model('payment_model');
		$paymentGateways = $this->payment_model->getPaymentSettings();
		$this->outputData['paymentGateways']	= $paymentGateways;	
		
		//Check Whether User Logged In Or Not
	    if(isLoggedIn()===false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Dont have rights to access this page')));
			redirect('info');
		}
	    /*Load payment settings
		$this->load->model('payment_model');
		$paymentGateways = $this->payment_model->getPaymentSettings();
		$this->outputData['paymentGateways']	= $paymentGateways;	
		$conditions=array('payments.id'=>1);
		$payamount = $this->payment_model->getPayment($conditions); 
		foreach($payamount->result() as $res)
		{
	    $this->outputData['paypal_commission']=$res->commission;
		$this->outputData['paypal_withdraw_description']=$res->withdraw_description;
		}
		$conditions=array('payments.id'=>3);
		$payamount = $this->payment_model->getPayment($conditions); 
	
		foreach($payamount->result() as $res)
		{
		
	   $this->outputData['mb_commission']=$res->commission;
	   $this->outputData['mb_withdraw_description']=$res->withdraw_description;
		}*/
							
		//Check User Balance
		$condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);
		$results 	 			 = $this->transaction_model->getBalance($condition_balance);
		
		//If Record already exists
		if($results->num_rows()>0)
		{
			//get balance detail
			$rowBalance = $results->row();		
			$this->outputData['userAvailableBalance'] = $rowBalance->amount;
		}	
	    
		//Load escrow transaction
		//Load helper file
		$this->load->helper('transaction');
		$creator_condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id);
		$transaction_condition 	 = array('transactions.creator_id'=>$this->loggedInUser->id,'type'=>'Withdraw' );
		$url                     = 'withdraw/index'; 
		$page                    = $this->uri->segment(3,0); 
		$escrow =  loadTransaction($creator_condition,$transaction_condition,$url,$page);

		//Get Form Data	
		if($this->input->post('withdrawMoney'))
		{
			//Set Validation Rules for amount
			$this->form_validation->set_rules('total','lang:total_validation','required|trim|integer|xss_clean|abs');
			$this->form_validation->set_rules('paymentMethod','lang:paymentMethod_validation','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{
			    //get the minimum withdraw value
				if(isset($paymentGateways))
				{
					foreach($paymentGateways as $res)
					{
						$withdraw_minimum   =    $res['withdraw_minimum'];
						$this->outputData['withdraw_minimum']  = $withdraw_minimum;
					}
					
					//check the balance amount for withdraw
					if($rowBalance->amount < $this->input->post('total') )
					{
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('your not having sufficient balance')));
						redirect('withdraw');
					} 
				}
				//check the minimum withdraw amount
				if( $withdraw_minimum > $this->input->post('total'))
				{
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Minimum withdraw amount :').$withdraw_minimum.'.00'));
					redirect('withdraw');
				}
				$paymentSettings = $this->settings_model->getSiteSettings();
				$withdrawvalue = $rowBalance->amount - ( $this->input->post('total') + $paymentSettings['PAYMENT_SETTINGS'] );
				if( $withdrawvalue < 0 )
				{
				  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('your not having sufficient balance').$withdraw_minimum.'.00'));
				  redirect('withdraw');
				}
				
				 //redirect it to appropriate payment method			      
				  $this->outputData['total']  	  	  = $this->input->post('total');
				  $this->outputData['creator_id']     = $this->loggedInUser->id;				  
				  
				  if($this->input->post('paymentMethod')=='paypal')
				  {
				  	   $view	=  'paypalWithdraw';
					   $method  =  'Paypal';
					   $this->outputData['paymentMethod']  = $method;					  
				  }	
				
				/*  if($this->input->post('paymentMethod')=='mb')

				 {

				   $view	=  'moneybookerWithdraw';

				   $method  =  'mb';
				   $this->outputData['paymentMethod']  = $method;	

				 }	*/

			  
				  //Load Corresponding View Based On Payment Method
				  $this->load->view('transaction/'.$view,$this->outputData);				  
			} else {
			  	$this->load->view('transaction/withdrawMoney',$this->outputData);
			} //If Check For Validation
		} else {			
			$this->load->view('transaction/withdrawMoney',$this->outputData);
		}//If End - Check For Form Submission
	} //Function index End
	// --------------------------------------------------------------------
	
	/**
	 * Loads withdraw amount details for the loggedInUser
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */	
	 
	 function withDrawAmount()
	 {
	 			
		//Load payment settings
		$this->load->model('payment_model');
		$paymentGateways = $this->payment_model->getPaymentSettings();
		$this->outputData['paymentGateways']	= $paymentGateways;	
		
		//store the values
		$this->outputData['total']  	  	= $this->input->post('total',true);
		$this->outputData['creator_id']  	= $this->input->post('creator_id',true);
		$this->outputData['withdraw']  	  	= $this->input->post('withdraw',true);
		$this->outputData['paymentMethod']  = $this->input->post('paymentMethod',true);
		$this->outputData['withdraw_minimum']  = $this->input->post('withdraw_minimum',true);
		$this->outputData['userAvailableBalance']  = $this->input->post('userAvailableBalance',true);
		
		if($this->input->post('email'))
		{
			//Set Validation Rules
			$this->form_validation->set_rules('email','lang:email','required|trim|valid_email|xss_clean');
			if($this->form_validation->run())
			{
				if(strtolower($this->input->post('paymentMethod'))==strtolower('Paypal'))
				  {
				  	   $view	=  'paypalWithdraw';
					   $method  =  'Paypal';
				  }
				
				//Load usermodel for get the userinformation to store buyer or programmer 
				$this->load->model('user_model');
				//get user details
				$conditions		= array('users.id'=>$this->outputData['creator_id']);
				$query 			= $this->user_model->getUsers($conditions);
		      
				foreach($query->result() as $row)
				{
					$role = $row->role_name;
					$role_id=$row->role_id;
				}
				//Register Transaction
				  $insertData = array(); 
				  $insertData['creator_id']  = $this->loggedInUser->id;
				  $insertData['type'] 		 = 'Withdraw';
				  $insertData['amount'] 	 = $this->outputData['total'];
				  $insertData['transaction_time'] 	 	 = get_est_time();
				  $insertData['status'] 	 = 'Pending'; //Can Be success,failed,pending
				  $insertData['description'] = $this->lang->line('Withdraw Amount From').' '.$method;
				  $insertData['paypal_address'] 	 = $this->input->post('email');
				  $insertData['user_type'] 		 = $role;				  
				  if($role_id == '1')
				  {
				  	$insertData['buyer_id'] = $this->loggedInUser->id;
				  }
				  
				  if($role_id == '2')
				  {
				  	$insertData['provider_id'] = $this->loggedInUser->id;
				  }
				  $this->load->model('transaction_model');
				  $res=$this->transaction_model->addTransaction($insertData);
				
				  //Check User Balance
				  $condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);
				  $results 	 			 = $this->transaction_model->getBalance($condition_balance);
							
				  //If Record already exists
				  if($results->num_rows()>0)
					{
					 //get balance detail
				 	 $rowBalance = $results->row();
					 //Update Amount	
					 /*
					 $updateKey 			  = array('user_balance.user_id'=>$this->loggedInUser->id);	
					 $updateData 		  = array();
					 $updateData['amount'] = $rowBalance->amount   -   $this->input->post('total');
					 $results1 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
					 */
					 
					 //Send email to the user after registration
					  $this->load->model('email_model');
					  $conditionUserMail = array('email_templates.type'=>'transaction');
					  $result            = $this->email_model->getEmailSettings($conditionUserMail);
					  $rowUserMailConent = $result->row();
					  $splVars = array("!site_name" => $this->config->item('site_title'),"!username" => $this->loggedInUser->user_name,"!siteurl" => site_url(),"!amount"=>$insertData['amount'],"!type"=>'Withdraw',"!others"=>'',"!others1"=>'', "!contact_url" => site_url('contact'));
					  $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
					  $mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
					  $toEmail     = $this->loggedInUser->email;
					  $fromEmail   = $this->config->item('site_admin_mail');
					  $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
					}	
				  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Withdraw success')));
		          redirect('info');
				  //Get Transaction id and set this in to transactions custom field
				  $this->outputData['transactionId']	= $this->db->insert_id();	
			}
			else
			{
				//load if email is invalid
				$this->load->view('transaction/paypalWithdraw',$this->outputData);
			}
		}	
		else
		{
			//load text is empty
			$this->load->view('transaction/paypalWithdraw',$this->outputData);
		}	
	 }
	 
	/* function mbWithdraw(){
	 	
		//Load payment settings

		$this->load->model('payment_model');

		$paymentGateways = $this->payment_model->getPaymentSettings();
		$this->outputData['paymentGateways']	= $paymentGateways;	
		
		$this->outputData['paymentGateways']	= $paymentGateways;	

		//store the values

		$this->outputData['total']  	  	= $this->input->post('total',true);

		$this->outputData['creator_id']  	= $this->input->post('creator_id',true);

		$this->outputData['withdraw']  	  	= $this->input->post('withdraw',true);

		$this->outputData['paymentMethod']  = $this->input->post('paymentMethod',true);

		$this->outputData['withdraw_minimum']  = $this->input->post('withdraw_minimum',true);

		$this->outputData['userAvailableBalance']  = $this->input->post('userAvailableBalance',true);

		

		if($this->input->post('email'))

		{

			//Set Validation Rules

			$this->form_validation->set_rules('email','lang:email','required|trim|valid_email|xss_clean');

			if($this->form_validation->run())

			{

				if(strtolower($this->input->post('paymentMethod'))==strtolower('mb'))

				  {

				  	   $view	=  'moneybookerWithdraw';

					   $method  =  'mb';

				  }

				

				//Load usermodel for get the userinformation to store buyer or programmer 

				$this->load->model('user_model');

				//get user details

				$conditions		= array('users.id'=>$this->outputData['creator_id']);

				$query 			= $this->user_model->getUsers($conditions);

		      

				foreach($query->result() as $row)

				{

					$role = $row->role_name;

					$role_id=$row->role_id;

				}

				//Register Transaction

				  $insertData = array(); 

				  $insertData['creator_id']  = $this->loggedInUser->id;

				  $insertData['type'] 		 = 'Withdraw';

				  $insertData['amount'] 	 = $this->outputData['total'];

				  $insertData['transaction_time'] 	 	 = get_est_time();

				  $insertData['status'] 	 = 'Pending'; //Can Be success,failed,pending

				  $insertData['description'] = $this->lang->line('Withdraw Amount From').' '.$method;

				  $insertData['paypal_address'] 	 = $this->input->post('email');

				  $insertData['user_type'] 		 = $role;				  

				  if($role_id == '1')

				  {

				  	$insertData['buyer_id'] = $this->loggedInUser->id;

				  }

				  

				  if($role_id == '2')

				  {

				  	$insertData['provider_id'] = $this->loggedInUser->id;

				  }

				  $this->load->model('transaction_model');

				  $res=$this->transaction_model->addTransaction($insertData);

				

				  //Check User Balance

				  $condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);

				  $results 	 			 = $this->transaction_model->getBalance($condition_balance);

							

				  //If Record already exists

				  if($results->num_rows()>0)

					{

					 //get balance detail

				 	 $rowBalance = $results->row();

					
					 //Send email to the user after registration

					  $this->load->model('email_model');

					  $conditionUserMail = array('email_templates.type'=>'transaction');

					  $result            = $this->email_model->getEmailSettings($conditionUserMail);

					  $rowUserMailConent = $result->row();

					  $splVars = array("!site_name" => $this->config->item('site_title'),"!username" => $this->loggedInUser->user_name,"!siteurl" => site_url(),"!amount"=>$insertData['amount'],"!type"=>'Withdraw',"!others"=>'',"!others1"=>'', "!contact_url" => site_url('contact'));

					  $mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);

					  $mailContent = strtr($rowUserMailConent->mail_body, $splVars);		

					  $toEmail     = $this->loggedInUser->email;

					  $fromEmail   = $this->config->item('site_admin_mail');

					  $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);

					}	

				  $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Withdraw success')));

		          redirect('info');

				  //Get Transaction id and set this in to transactions custom field

				  $this->outputData['transactionId']	= $this->db->insert_id();	

			}

			else

			{

				//load if email is invalid

				$this->load->view('transaction/moneybookerWithdraw',$this->outputData);

			}

		}	

		else

		{

			//load text is empty

			$this->load->view('transaction/moneybookerWithdraw',$this->outputData);

		}	

	 
	 } */

	 
	 
} //End  Withdraw Class

/* End of file Withdraw.php */ 
/* Location: ./app/controllers/Withdraw.php */