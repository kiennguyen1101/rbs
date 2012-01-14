<?php
/**
 * Reverse bidding system Deposit Class
 *
 * Buyer related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Project 
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
class Deposit extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Deposit()
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
		//Get Latest Projects
		$limit_latest = $this->config->item('latest_projects_limit');
		$limit3 = array($limit_latest);
		$this->outputData['latestProjects']	= $this->skills_model->getLatestProjects($limit3);
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
	
    	//Load Language
		$this->lang->load('enduser/depositMoney', $this->config->item('language_code'));
		
		//Innermenu tab selection
		$this->outputData['innerClass2']   = '';
		$this->outputData['innerClass2']   = 'selected';
		
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
		
		if(!isset($this->loggedInUser->role_id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		  if($this->loggedInUser->suspend_status==1)
		 {
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Suspend Error')));
			redirect('info');
		 }	
		//Load Language
		$this->lang->load('enduser/depositMoney', $this->config->item('language_code'));
		
		//Load payment settings
		$this->load->model('payment_model');
		$paymentGateways = $this->payment_model->getPaymentSettings();
		$this->outputData['paymentGateways']	= $paymentGateways;	
		
		if($this->input->post('amount'))
		  {
		  	$this->outputData['amount']  = $this->input->post('amount');
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
		
		//Check User Balance
		$condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);
		$results 	 			 = $this->transaction_model->getBalance($condition_balance);

        //Load transfer transaction
		//Load helper file
		$this->load->helper('transaction');
		$creator_condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id);
		$transaction_condition 	 = array('transactions.creator_id'=>$this->loggedInUser->id,'type'=>'Deposit' );
		$url                     = 'deposit/index'; 
		$page                    = $this->uri->segment(3,0); 
		$escrow =  loadTransaction($creator_condition,$transaction_condition,$url,$page);

		//If Record already exists
		if($results->num_rows()>0)
		{
			//get balance detail
			$rowBalance = $results->row();
			
		    //check balance Amount	
			$updateKey 			  = array('user_balance.user_id'=>$this->loggedInUser->id);	
			$updateData 		  = array();
			$this->outputData['userAvailableBalance'] = $rowBalance->amount;
								
			if($rowBalance->amount > $this->input->post('total') )
			  {
			  	$this->outputData['with_balance'] = $this->lang->line('check balance');
			  } 
		}
		//Get Form Data	
		if($this->input->post('depositMoney'))
		{
			//Set Validation Rules
			$this->form_validation->set_rules('total','lang:total_validation','required|trim|integer|xss_clean|abs|is_natural_no_zero');
			$this->form_validation->set_rules('paymentMethod','lang:paymentMethod_validation','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{

				  //redirect it to appropriate payment method
				$total = $this->input->post('total');
			    $this->outputData['total'] = $total;
				if($this->input->post('paymentMethod')=='paypal')
				 {
				   $view	=  'paypalDeposit';
				   $method  =  'Paypal';
				 }

				 //Load the minimum deposit value
				foreach($paymentGateways as $res)
				{
				 	$deposit_minimum   =    $res['deposit_minimum'];
					$commission = $res['commission'];
					$this->outputData['commission'] = $commission;
				}
				$total_comm = $total + ($total * ($commission/100));
				$this->outputData['total_with_commission'] = sprintf("%1\$.2f",$total_comm);
				if( $deposit_minimum > $this->input->post('total'))
				{
					
					$this->outputData['min_deposit'] ='0' ;
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Minimum deposit amount :').$deposit_minimum.'.00'));
					redirect('deposit');
				}  
                              
				$this->load->model('user_model');
				$this->loggedInUser->id;
				//Conditions
				$conditions		= array('users.id'=>$this->loggedInUser->id);
				
				$query 			= $this->user_model->getUsers($conditions);
				foreach($query->result() as $row)
					{
				 	$role = $row->role_name;
					$role_id=$row->role_id;
					}
				  //Register Transaction
				  $insertData = array(); 
				  $insertData['creator_id']  = $this->loggedInUser->id;
				  $insertData['type'] 		 = 'Deposit';
				  $insertData['amount'] 	 = $this->outputData['total'];
				  $insertData['transaction_time'] 	 	 = get_est_time();
				  $insertData['status'] 	 = 'Pending'; //Can Be success,failed,pending
				  $insertData['description'] = $this->lang->line('Amount Deposited Through').' '.$method;
				 
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
				  $res  =  $this->transaction_model->addTransaction($insertData);
				  
				  //Get Transaction id and set this in to transactions custom field
				  $this->outputData['transactionId']	= $this->db->insert_id();				  
				  //echo $this->outputData['total'];exit;
				  //Load Corresponding View Based On Payment Method
				  $this->load->view('transaction/'.$view,$this->outputData);
				  
			} else {
				$this->load->view('transaction/depositMoney',$this->outputData);	
			} //If Check For Validation
		} 
		else
		 { 			
			$this->load->view('transaction/depositMoney',$this->outputData);
		 }//If End - Check For Form Submission
			
	} //Function index End
	// --------------------------------------------------------------------
	
	/**
	 * Loads deposit cancel page
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */	
	function cancel(){
		$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','Payment is cancelled'));
		redirect('deposit');
	}
		
} //End  Deposit Class

/* End of file Deposit.php */ 
/* Location: ./app/controllers/Deposit.php */