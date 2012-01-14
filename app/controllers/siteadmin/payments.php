<?php
/**
 * Reverse bidding system SiteSettings Class
 *
 * Permits admin to set the payement settings (ie.Paypal)
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Settings 
 * @author		
 * @version		
 * @created		December 22 2008
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
class Payments extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	   
	/**
	* Constructor 
	*
	* Loads language files and models needed for this controller
	*/ 
	function Payments()
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
		$this->lang->load('admin/common',$this->config->item('language_code'));
		$this->lang->load('admin/setting',$this->config->item('language_code'));
		$this->lang->load('admin/validation',$this->config->item('language_code'));
		
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		$this->load->model('payment_model');
		$this->load->model('user_model');
		$this->load->model('transaction_model');
		$this->load->model('settings_model');
		
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		
		//language file
		$this->lang->load('admin/common', $this->config->item('language_code'));
		$this->lang->load('admin/payments', $this->config->item('language_code'));
		
		//Load users list
		$usersList    =     $this->user_model->getUserslist();
		$this->outputData['usersList']   =   $usersList;

		//Load users roles
		$roles        =     $this->user_model->getRoles();
		$this->outputData['roles']   =   $roles;
		
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		$paymentGateways = $this->payment_model->getPaymentSettings();
		$this->outputData['paymentGateways'] = $paymentGateways; 


	}//Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Add new transaction
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function addTransaction()
	{	
	//print_r($_POST);
//exit;
		$result = FALSE;
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		$this->outputData['transactionDescription']   = $this->input->post('transactionDescription',TRUE);
		$this->outputData['from_username']            = $this->input->post('from_username',TRUE);
		$this->outputData['to_username']              = $this->input->post('to_username',TRUE);
		$this->outputData['from_usertype']            = $this->input->post('from_usertype',TRUE);
		$this->outputData['to_usertype']              = $this->input->post('to_usertype',TRUE);
		$this->outputData['amount']                   = $this->input->post('amount',TRUE);
		
		if($this->input->post('addTransaction'))
		{	
			//Set rules
			$this->form_validation->set_rules('transactionDescription','lang:Transaction Description','required|trim|xss_clean');
			$this->form_validation->set_rules('from_usertype','lang:usertype','required|trim|xss_clean');
			$this->form_validation->set_rules('from_username','lang:username','required|trim|xss_clean');
			$this->form_validation->set_rules('to_usertype','lang:usertype','required|trim|xss_clean');
			$this->form_validation->set_rules('to_username','lang:username','required|trim|xss_clean');
			$this->form_validation->set_rules('amount','lang:Amount','required|trim|xss_clean|numeric');
			
			if($this->form_validation->run())
			{	
				  //Set Payment Id
							  
				  $insertData                  	  	= array();	
			      $insertData['id']  				= '';
				  $insertData['type']    			= 'Transfer';	 
				  $insertData['creator_id']    		= $this->input->post('from_username',TRUE);
				  $insertData['buyer_id']    		= '';
				  $insertData['provider_id']    	= '';
				  $insertData['transaction_time']    	        = get_est_time();
				  $insertData['amount']    	        = $this->input->post('amount',TRUE);
				  $insertData['status']    	        = 'Completed';
				  $insertData['description']    	= 'Tansfer Amount Through Paypal';
				  $insertData['paypal_address']    	= '';
				  $insertData['user_type']    	    = 'admin';
				  $insertData['reciever_id']    	= $this->input->post('to_username',TRUE);
				  $insertData['project_id']    	    = '';
				  
				  $min_amount                  = $this->config->item('payment_settings');
				  //Check User Balance
				  $condition_balance 		        = array('user_balance.user_id'=>$this->input->post('from_username',TRUE));
				  $results 	 			            = $this->transaction_model->getBalance($condition_balance);
				  
				  
                    $condition_balance1 		        = array('user_balance.user_id'=>$this->input->post('to_username',TRUE));
				  $results1 	 			            = $this->transaction_model->getBalance($condition_balance1);
				  
				  
				  if($this->input->post('to_username',TRUE) ==  $this->input->post('from_username',TRUE))
				    {
					$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('You can not make Transaction for same person')));
					redirect_admin('payments/addTransaction');
					}
					//If Record already exists
				  if($results->num_rows()>0)
					{
						//get balance detail
						$rowBalance = $results->row();
						$balance   =  $rowBalance->amount -  ( $min_amount + $this->input->post('amount',TRUE) );
						
						if($balance > 0)
						{
							//Update Amount	
							$updateKey 			  = array('user_balance.user_id'=>$this->input->post('from_username',TRUE));	
							$updateData 		  = array();
							$updateData['amount'] = $rowBalance->amount   -   $this->input->post('amount',TRUE);
							$Update_Amount	 	  = $this->transaction_model->updateBalance($updateKey,$updateData);
		                    if($results1->num_rows()>0)
							{
							$rowBalance1 = $results1->row();
							//Update Amount	
							$updateKey 			  = array('user_balance.user_id'=>$this->input->post('to_username',TRUE));	
							$updateData 		  = array();
							$updateData['amount'] = $rowBalance1->amount   +   $this->input->post('amount',TRUE);
							$Update_Amount	 	  = $this->transaction_model->updateBalance($updateKey,$updateData);
							//pr($insertData);
							$Update_transaction   = $this->transaction_model->addTransaction($insertData);
							redirect_admin('payments/viewTransaction');
							}
						}
						else
						{
				   		   $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('You are not having Sufficient Balance')));
						   $result = TRUE;
						   redirect_admin('payments/addTransaction');
						}	
					} // balance If end here
		 	   } //validation if end here
		  } //If - Form Submission End
	   	   $this->load->view('admin/payments/addTransaction',$this->outputData);
	}//End of addTransaction function
	
	
	/**
	 * searchTransaction
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function searchTransaction()
	{	
		$transactionId = $this->input->post('trasactionid',TRUE);
		if($this->input->post('trasactionid',TRUE))
		  {
			//Get the inbox mail list 
			$page_rows         					 =  $this->config->item('mail_limit');
			
			//Get Transaction Information
			$this->load->model('transaction_model');
			$transactions1 	 = $this->transaction_model->getTransactions();
			$this->outputData['transactions1'] = $transactions1;
			
			$condition 		 = array('transactions.id'=>$transactionId);
			$transactions 	 = $this->transaction_model->getTransactions($condition);
			$this->outputData['transactions'] = $transactions;
	
			$this->load->view('admin/payments/viewTransaction',$this->outputData);
		  }
	    else
		  {
		  	 $this->load->view('admin/payments/searchTransaction',$this->outputData);
		  }	  	

	}//End of searchTransaction function	
	
	
	/**
	 * viewTransaction
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewTransaction()
	{	
		$start = $this->uri->segment(4,0);
		if($start > 0)
		  $start = $start;
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		//Get Transaction Information
		$this->load->model('transaction_model');
		$transactions1 	 = $this->transaction_model->getTransactions();
		$this->outputData['transactions1'] = $transactions1;
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            = 'asc';
		//$condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id);
		$transactions 	 = $this->transaction_model->getTransactions(NULL,NULL,NULL,$limit,$order);
		$this->outputData['transactions'] = $transactions;
		
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('payments/viewTransaction');
		$config['total_rows'] 	 = $transactions1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'viewTransaction');
		$this->outputData['totaltransactions'] =  count($transactions->result());
 	    $this->load->view('admin/payments/viewTransaction',$this->outputData);
	}//End of viewTransaction function	
	
	
	
	
	/**
	 * viewEscrow Transaction  
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewEscrow()
	{	
		$start = $this->uri->segment(4,0);
		if($start > 0)
		  $start = $start ;
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		//Get Transaction Information
		$this->load->model('transaction_model');
		$condition 		 = array('transactions.type'=>'Escrow Transfer');
		$transactions1 	 = $this->transaction_model->getTransactions($condition);
		$this->outputData['transactions1'] = $transactions1;
		//pr($transactions1->result());
		$limit[0]			 = $page_rows;
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            = 'asc';
		$condition 		 = array('transactions.type'=>'Escrow Transfer');
		$transactions 	 = $this->transaction_model->getTransactions($condition,NULL,NULL,$limit,$order);
		$this->outputData['transactions'] = $transactions;
		
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('payments/viewEscrow');
		$config['total_rows'] 	 = $transactions1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'viewEscrow');
		$this->outputData['totaltransactions'] =  count($transactions->result());
 	    $this->load->view('admin/payments/viewEscrow',$this->outputData);

	}//End of viewTransaction function	
	
	
	/**
	 * viewEscrow Transaction  
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function releaseEscrow()
	{	
		$start = $this->uri->segment(4,0);
		if($start > 0)
		  $start = $start ;
		//Get the inbox mail list 
     $page_rows         					 =  $this->config->item('mail_limit');
		
		//Get Transaction Information
		$this->load->model('transaction_model');
		$condition 		 = array('transactions.type'=>'Escrow Transfer','transactions.status'=>strtolower('Pending'));
		$transactions1 	 = $this->transaction_model->getTransactions($condition);
		$this->outputData['transactions1'] = $transactions1;
		//echo 'transactio'.$transactions1->num_rows();		
		$limit[0]			 = $page_rows;
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            = 'asc';
		$condition 		 = array('transactions.type'=>'Escrow Transfer','transactions.status'=>strtolower('Pending'));
		$transactions 	 = $this->transaction_model->getTransactions($condition,NULL,NULL,$limit,$order);
		$this->outputData['transactions'] = $transactions;
		
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('payments/releaseEscrow');
		$config['total_rows'] 	 = $transactions1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'releaseEscrow');
		$this->outputData['totaltransactions'] =  count($transactions->result());
 	    $this->load->view('admin/payments/releaseEscrow',$this->outputData);

	}//End of viewTransaction function	
	
	
	
	/**
	 * Add new transaction
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function viewWithdraw()
	{	
		$start = $this->uri->segment(4,0);
		if($start > 0)
		  $start = $start  ;
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		//Get Transaction Information
		$this->load->model('transaction_model');
		$condition 		 = array('transactions.type'=>'Withdraw');
		$transactions1 	 = $this->transaction_model->getTransactions($condition);
		$this->outputData['transactions1'] = $transactions1;
		
		$limit[0]			 = $page_rows;
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            = 'asc';
		$condition 		 = array('transactions.type'=>'Withdraw');
		$transactions 	 = $this->transaction_model->getTransactions($condition,NULL,NULL,$limit,$order);
		$this->outputData['transactions'] = $transactions;
		
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('payments/viewWithdraw');
		$config['total_rows'] 	 = $transactions1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'viewWithdraw');
		$this->outputData['totaltransactions'] =  count($transactions->result());
 	    $this->load->view('admin/payments/viewWithdraw',$this->outputData);

	}//End of viewTransaction function	
	/**
	 * viewEscrow Transaction  
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function releaseWithdraw()
	{	
		$start = $this->uri->segment(4,0);
		if($start > 0)
		  $start = $start ;
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		//Get Transaction Information
		$this->load->model('transaction_model');
		$condition 		 = array('transactions.type'=>'Withdraw','transactions.status'=>strtolower('Pending'));
		$transactions1 	 = $this->transaction_model->getTransactions($condition);
		$this->outputData['transactions1'] = $transactions1;
		
		$limit[0]			 = $page_rows;
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            = 'asc';
		$condition 		 = array('transactions.type'=>'Withdraw','transactions.status'=>strtolower('Pending'));
		$transactions 	 = $this->transaction_model->getTransactions($condition,NULL,NULL,$limit,$order);
		$this->outputData['transactions'] = $transactions;
		
		  //Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('payments/viewWithdraw');
		$config['total_rows'] 	 = $transactions1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination_outbox']   = $this->pagination->create_links2(false,'viewWithdraw');
		$this->outputData['totaltransactions'] =  count($transactions->result());
 	    $this->load->view('admin/payments/releaseWithdraw',$this->outputData);

	}//End of viewTransaction function	
	
	/**
	 * successWithdraw
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function successWithdraw()
	{	
		$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('You are not having Sufficient Balance')));
		redirect('info');
	}
	
	/**
	 * Add new transaction
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function acceptEscrow()
	{	
		
		$id = $this->uri->segment(4,0);
				
		//Get Transaction Information
		$this->load->model('transaction_model');
		$condition 		 = array('transactions.id'=>$id,'transactions.status'=>'Pending');
		$transactions1 	 = $this->transaction_model->getTransactions($condition);
		$transactions1   = $transactions1->row();
		//pr($transactions1);
		
		//Check User Balance
		$condition_balance 		 = array('user_balance.user_id'=>$transactions1->reciever_id);
		$results 	 			 = $this->transaction_model->getBalance($condition_balance);
        //pr($results->result());
		//If Record already exists
		if($results->num_rows()>0)
		{
			//get balance detail
			$rowBalance = $results->row();
			
		    //Update Amount	
			$updateKey 			  = array('user_balance.user_id'=>$transactions1->reciever_id);	
			$updateData 		  = array();
			$updateData['amount'] = $rowBalance->amount   +   $transactions1->amount;
			$Update_Amount	 	  = $this->transaction_model->updateBalance($updateKey,$updateData);
			
			//Update transaction	
			$updateKey 			  = array('transactions.id'=>$id);	
			$updateData 		  = array();
			$updateData['status'] = 'Completed';
			$Update_transaction   = $this->transaction_model->updateTransaction($updateKey,$updateData);
			
			//Check User Balance
   		    $condition 		      = array('escrow_release_request.transaction_id'=>$id);
		    $results 	 		  = $this->transaction_model->deleteEscrowrelease($condition);

		}


 	    redirect('siteadmin/payments/releaseEscrow');
		

	}//End of viewTransaction function	
	
	/**
	 * Add new transaction
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deniedEscrow()
	{	
		
		$id = $this->uri->segment(4,0);
				
		//Get Transaction Information
		$this->load->model('transaction_model');
		$condition 		 = array('transactions.id'=>$id);
		$transactions1 	 = $this->transaction_model->getTransactions($condition);
		$transactions1   = $transactions1->row();
		//pr($transactions1);
		
		//Check User Balance
		$condition_balance 		 = array('user_balance.user_id'=>$transactions1->creator_id);
		$results 	 			 = $this->transaction_model->getBalance($condition_balance);
        //pr($results->result());
		//If Record already exists
		if($results->num_rows()>0)
		{
			//get balance detail
			$rowBalance = $results->row();
			
		    //Update Amount	
			$updateKey 			  = array('user_balance.user_id'=>$transactions1->creator_id);	
			$updateData 		  = array();
			$updateData['amount'] = $rowBalance->amount   +   $transactions1->amount;
			$Update_Amount	 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
			
			//Update transaction	
			$updateKey 			  = array('transactions.id'=>$id);	
			$updateData 		  = array();
			$updateData['status'] = 'Cancelled';
			$Update_transaction 			  = $this->transaction_model->updateTransaction($updateKey,$updateData);
		}
 	    redirect('siteadmin/payments/releaseEscrow');
		

	}//End of viewTransaction function	
	
	/**
	 * Add new transaction
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function acceptWithdraw()
	{	
		
		$id = $this->uri->segment(4,0);
		//Get Transaction Information
		$this->load->model('transaction_model');
		$condition 		 = array('transactions.id'=>$id);
		$transactions1 	 = $this->transaction_model->getTransactions($condition);
		$transactions1   = $transactions1->row();
		$condition_balance 		 = array('user_balance.user_id'=>$transactions1->creator_id);
		$results 	 			 = $this->transaction_model->getBalance($condition_balance);
			
		if($results->num_rows()>0)
		{
			//get balance detail
			$rowBalance = $results->row();
	$newamount 			  =	$rowBalance->amount   -   $transactions1->amount;
		
		$min_amount                  = $this->config->item('payment_settings');
		if($newamount	>= 	$min_amount)
		{	
			$updateKey 			  = array('user_balance.user_id'=>$transactions1->creator_id);	
			$updateData 		  = array();
	  		$updateData['amount'] = $newamount;
			$Update_Amount	 	  = $this->transaction_model->updateBalance($updateKey,$updateData);
				
			//Update transaction	
			$updateKey 			  = array('transactions.id'=>$id);	
			$updateData 		  = array();
			$updateData['status']	='Completed';
			$Update_transaction   = $this->transaction_model->updateTransaction($updateKey,$updateData);
				$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Withdraw Status Changed Succesfully')));
				redirect('siteadmin/payments/releaseWithdraw');
			 }
			 else
			 {
			 	$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Minimum Balance Required')));
				redirect('siteadmin/payments/releaseWithdraw');
			 }
			} 
	}//End of viewTransaction function	
	
	
	/**
	 * Add new transaction
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function acceptReturn()
	{	
		if($this->input->post('payment_status') == 'Completed')
		{
			$creator_id              = $this->input->post('creator_id');
			$transaction_id          = $this->input->post('transaction_id');
			$custom                  = $this->input->post('custom');
				//Update transaction	
				$updateKey 			  = array('transactions.id'=>$custom);	
				$updateData 		  = array();
				$updateData['status'] = $this->input->post('payment_status');
				$Update_transaction   = $this->transaction_model->updateTransaction($updateKey,$updateData);
			redirect('siteadmin/payments/viewWithdraw');
		}
		else
		{
			//$this->load->view('admin/payments/withdrawAmount',$this->outputData);
		}

	}//End of viewTransaction function	
	
	
	/**
	 * Add new transaction
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deniedWithdraw()
	{	
		
		$id = $this->uri->segment(4,0);
				
		//Get Transaction Information
		$this->load->model('transaction_model');
		$condition 		 = array('transactions.id'=>$id);
		$transactions1 	 = $this->transaction_model->getTransactions($condition);
		$transactions1   = $transactions1->row();
		//Check User Balance
		$condition_balance 		 = array('user_balance.user_id'=>$transactions1->creator_id);
		$results 	 			 = $this->transaction_model->getBalance($condition_balance);
		//If Record already exists
		if($results->num_rows()>0)
		{
			//get balance detail
			$rowBalance = $results->row();
			//Update transaction	
			$updateKey 			  = array('transactions.id'=>$id);	
			$updateData 		  = array();
			$updateData['status'] = 'Cancelled';
			$Update_transaction 			  = $this->transaction_model->updateTransaction($updateKey,$updateData);
		}
 	    redirect('siteadmin/payments/releaseWithdraw');
		

	}//End of viewTransaction function	
	
	
	
	/* *****************************************************
	 * FUNCTION NAME :  load_users
	 * USAGE		 :	This function used to get the users
	 * ******************************************************/
	function load_users()
	{
		
		if($this->uri->segment('4'))
		{
			//Here the code for select if user choosed in project combo box
			
       
		$user = $this->uri->segment('4');		
			//Get the users detail
	    $this->load->model('user_model');
		$condition    = array('users.role_id'=>$user,'users.user_status'=>1);	
		$usersname	   =  $this->user_model->getUserslist($condition);
		$this->outputData['usersname'] =  $usersname->result();	
		
		   
		 $data ='';
		if($usersname)
		{
		 	
			 
			
			foreach($usersname->result() as $users)
			{
				$data .='<option value="'.$users->id.'" > '. $users->user_name.'</option>';
         			
			}
		} 
			$data .='</select>';
			echo $data;
		} 
		
		
	
	} //Function Load_Category
	
	function load_users1()
	{
		if($this->uri->segment('4'))
		{
			//Here the code for select if user choosed in project combo box
			
       
		$user = $this->uri->segment('4');		
		//Get the users details
	    $this->load->model('user_model');
		$condition    = array('users.role_id'=>$user,'users.user_status'=>1);	
		$usersname	   =  $this->user_model->getUserslist($condition);
		$this->outputData['usersname'] =  $usersname->result();	
		$data =  '';
		if($usersname)
		{
			foreach($usersname->result() as $users)
			{
				$data.= '<option value="'.$users->id.'" >'.$users->user_name.'</option>';	
			}
		} 
		$data .='</select>';
		echo $data;
		} 
		
	
	} //Function Load_Category
	
}
//End  PaymentSettings Class

/* End of file paymentSettings.php */ 
/* Location: ./app/controllers/admin/paymentSettings.php */
?>