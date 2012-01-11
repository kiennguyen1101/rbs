<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// ------------------------------------------------------------------------


/**
 * escrow trasaction
 *
 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */
	//Some common fucntion to load escrow only 		
	function loadTransaction($creator_condition,$transaction_condition,$url,$page)
	{
		   
			//Get Transaction Information
			$CI 	=& get_instance();
			$CI->load->model('transaction_model');			
			$CI->load->model('user_model');			
			$transactions 	 = $CI->transaction_model->getTransactions($creator_condition);
			$CI->outputData['transactions'] = $transactions;
			//echo $transactions->num_rows();
			//Get Transaction Information
			 $start = $page;
			 
			 //Get all the site settings
			 
			 //$CI->outputData['escrow_limit']       =   $CI->config->item('escrow_page_limit');
			 //$CI->outputData['transaction_limit']  =   $CI->config->item('transaction_page_limit'); 
			 
			
			 $page_rows         =  $CI->config->item('transaction_page_limit'); 
			 
			 if($start > 0)
				$start = ($start-1) * $page_rows;
				
			 //escrow without limit
			 
			 $escrow_transactions 	 = $CI->transaction_model->getTransactions($transaction_condition);
			 $CI->outputData['transactions1'] = $escrow_transactions;
			 
			 //escrow trasaction with some limit	 		 
			 
			 $limit[0]			 = $page_rows;
			 $limit[1]			 = $start;
			 
			 $transactions1 	 = $CI->transaction_model->getTransactions($transaction_condition,NULL,NULL,$limit);
			 $CI->outputData['transactions1'] = $transactions1;
			 $transactions1->num_rows();
		
			  //Pagination
			 $CI->load->library('pagination');
			 $config['base_url'] 	 = site_url($url);
			 $config['total_rows'] 	 = $escrow_transactions->num_rows();		
			 $config['per_page']     = $page_rows; 
			 $config['cur_page']     = $start;
			 $CI->pagination->initialize($config);		
			 $CI->outputData['pagination']   = $CI->pagination->create_links2(false,'accounts');
			
			 //Get all users info
			 $usersList   =   $CI->user_model->getUserslist();
			 $CI->outputData['usersList']    =   $usersList;
			
			 //Get all the projects details
			 $projectList   =   $CI->skills_model->getUsersproject();
			 $CI->outputData['projectList']    =   $projectList;
			return $CI->outputData;
	}
	
	function getSuspendStatus($userid)  
	{
		$CI 	=& get_instance();
		$CI->load->model('common_model');
		$condition =array('users.id'=>$userid);
		$sus_status= $CI->common_model->getTableData('users',$condition,'users.suspend_status');
		$sus_status = $sus_status->row();
		return $sus_status->suspend_status;
	}

/* End of file users_helper.php */
/* Location: ./app/helpers/users_helper.php */
?>