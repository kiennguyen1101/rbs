<?php 
/** 
 * Reverse bidding system Account Class
 *
 * Account related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Buyer 
 * @author		Cogzidel Dev Team
 * @version		Version 1.0
 * @created		February 02 2009
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
class Account extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Account()
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
		$this->load->model('account_model');
		$this->load->model('messages_model');
		$this->load->model('certificate_model');
		
		
		  
		//Page Title and Meta Tags
		$this->outputData 			= $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		
		if($this->loggedInUser)
		{
			//Get logged user role
			$this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
			
			//Check User Balance
			$this->load->model('transaction_model');
			$condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);
			$results 	 			 = $this->transaction_model->getBalance($condition_balance);
	
			//If Record already exists
			if($results->num_rows()>0)
			{
				//get balance detail
				$rowBalance = $results->row();
				
				//check balance Amount	
				$updateKey 			  = array('user_balance.user_id'=>$this->loggedInUser->id);	
				$updateData 		  = array();
				$this->outputData['userAvailableBalance'] = $rowBalance->amount;
			}
		}
		//Get Latest Projects
		$limit_latest = $this->config->item('latest_projects_limit');
		$limit3 = array($limit_latest);
		$this->outputData['latestProjects']	= $this->skills_model->getLatestProjects($limit3);
		
		//Get all the site settings
		 $this->outputData['escrow_limit']  =   $this->config->item('escrow_page_limit');
	     $this->outputData['transaction_limit']  =   $this->config->item('transaction_page_limit'); 
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		$this->lang->load('enduser/account', $this->config->item('language_code'));
		
		$this->load->helper('file');
	
	} //Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Account page.
	 *
	 * @access	Private
	 * @param	nil
	 * @return	void
	 */ 
	function index()
	{	
	
	//Load the package_model
	$this->load->model('package_model');
	
//Load Language File
		$this->lang->load('enduser/account', $this->config->item('language_code'));
		$this->lang->load('enduser/viewProject', $this->config->item('language_code'));
		
		//Load helper file
		$this->load->helper('transaction');
		$this->load->helper('reviews');
		
		//If Admin try to access this url...redirect him
		/*if(isAdmin() === true)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Dont have rights to access this page')));
			redirect('info');
		}
	    */
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		// check Certificate User
		if($this->loggedInUser)	
		{
			//Get Transaction Information
		   $condition_username='subscriptionuser.username='.$this->loggedInUser->id;
			$condition_flag='subscriptionuser.flag='.'1';
			
			$condition 		 =$condition_username.'  AND  '.$condition_flag;
			$query='SELECT * FROM `subscriptionuser` WHERE '.$condition;
			$result=$this->db->query($query);
			$certificate_user=$result;
			
			foreach($certificate_user->result() as $cetificateuser)
			{
		      $user_package=$cetificateuser->package_id;
			
			 $condition1='packages.id ='.$user_package;
			 $condition2='packages.end_date>='.time();
			 $condition=$condition1.' AND '.$condition2;
			 
			 $query='SELECT * FROM packages WHERE '.$condition;
			 $result=$this->db->query($query);
		
			 $packagesList= $result;
			  $this->outputData['packagesList']= $packagesList;
			}
			
			
			
			}
		
		if($this->loggedInUser)	
		{
			//Get Transaction Information
			$condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id,'transactions.reciever_id'=>$this->loggedInUser->id);
			$transactions 	 = $this->transaction_model->getTransactions($condition);
			$this->outputData['transaction'] = $transactions;
			//pr($transactions->result());
			//Get the review
			$condition2 = array('users.id' => $this->loggedInUser->id);
		    $this->outputData['reviewDetails'] = $this->user_model->getUsers($condition2);
		}
		
		//Load escrow 
		if($this->loggedInUser->role_id == '1')
			{
			$this->outputData['topBuyers'] = $this->skills_model->getTopBuyers(NULL);
			$creator_condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id);
			$transaction_condition 	 = array('transactions.creator_id'=>$this->loggedInUser->id,'type'=>'Escrow Transfer' );
			
			//Get the project messages and mail list
			$condition    =  array('messages.to_id'=>$this->loggedInUser->id,'messages.notification_status'=>'0');
			$this->outputData['mailList']  =  $this->messages_model->getProjectMessages($condition);
			}
		if($this->loggedInUser->role_id == '2')
		    {
			
			//Get bookmark projects
			$condition_bookmark =array('bookmark.creator_id'=>$this->loggedInUser->id);
			$bookMark1 = $this->skills_model->getBookmark($condition_bookmark);
			//$this->outputData['bookMark'] = $bookMark;
			
			
			//pagination limit
			$page_rows         					 =  $this->config->item('mail_limit');
			$start = $this->uri->segment(3,0);
	
			$limit[0]			 = $page_rows;
			$limit[1]			 = ($start)* $page_rows;
			 
			//Get all message trasaction with some limit
			$bookMark = $this->skills_model->getBookmark($condition_bookmark,NULL,NULL,$limit);
			$this->outputData['bookMark'] = $bookMark;
	
				
			//Pagination
			$this->load->library('pagination');
			$config['base_url'] 	 = site_url('buyer/bookmarkProjects');
			$config['total_rows'] 	 = $bookMark1->num_rows();		
			$config['per_page']     = $page_rows; 
			$config['cur_page']     = $start;
			$this->pagination->initialize($config);		
			$this->outputData['pagination1']   = $this->pagination->create_links2(false,'bookmarkProjects');

			
			//Get all users
			$this->outputData['getUsers']	= $this->user_model->getUsers();	
			
			//Get the Projects details
			$creator_condition 		             = array('transactions.reciever_id'=>$this->loggedInUser->id);
			$transaction_condition 	             = array('transactions.reciever_id'=>$this->loggedInUser->id,'type'=>'Escrow Transfer' );
			
			$condition                           = array('projects.notification_status'=>'0'); 
			$this->outputData['awardProjects']   =  $this->skills_model->getProjects($condition);
			
			
			
			$result     = $this->skills_model->getTopprogrammers();
  		    $this->outputData['getProgrammers'] =  $result;	

			//Get the suers categories
			//Laod bookmark model
			$this->load->model('bookmark_model');
			$condition                           = array('user_categories.user_id'=>$this->loggedInUser->id); 
			$projectNotification                 = $this->bookmark_model->getUserCategories($condition);
			$categoryid                          =  $projectNotification->result();

			//Get the category string value for the id value
			$i='0';
			if($categoryid)
			{
			$categoryid = explode(',',$categoryid[$i]->user_categories);
			//Laod skills_model 
			$this->load->model('skills_model');
			$categoryname[$i++] =  $this->skills_model->convertCategoryIdsToName($categoryid);

			$this->outputData['categoryname']  = $categoryname;			   
			}
			//Get the project invitation from the buyer
			$condition    =  array('project_invitation.receiver_id'=>$this->loggedInUser->id,'project_invitation.notification_status'=>'0');
			$this->outputData['projectInvitation']  = $this->user_model->getProgrammerInvitation($condition);

			//Get the project messages and mail list
			$condition    =  array('messages.to_id'=>$this->loggedInUser->id,'messages.notification_status'=>'0');
			$this->outputData['mailList']  =  $this->messages_model->getProjectMessages($condition);
			}
		
			$url                     = 'account/index'; 
			$page                    = $this->uri->segment(3,0); 
			$escrow                  =  loadTransaction($creator_condition,$transaction_condition,$url,$page);
	
	     $page_rows = $this->config->item('listing_limit');

		$max = array($page_rows,($page - 1) * $page_rows);
		
		//Get Sorting order
		$field = $this->uri->segment(4,'0');
		$order = $this->uri->segment(5,'0');
		
		$orderby = array();
		if($field)
			$orderby = array($field,$order);
		
		$this->outputData['order']	=  $order;
		$this->outputData['field']	=  $field;
		$this->outputData['page']	=  $page;
		
		
		if(isSeller()){
			$provider_id	 = $this->loggedInUser->id;
			
			$conditions		= array('projects.programmer_id'=>$provider_id,'projects.project_status !=' => '2');
			$this->outputData['myProjects']  =  $this->skills_model->getProjects($conditions,NULL,NULL/*,$max,$orderby*/);
			$created = $this->skills_model->getProjects($conditions);
			
			$conditions		= array('projects.programmer_id'=>$provider_id,'projects.project_status =' => '2');
			$this->outputData['closedProjects']  =  $this->skills_model->getProjectsByProvider($conditions);
			
			
		}
		else
		{
			$buyer_id	 = $this->loggedInUser->id;
			//Conditions
			$conditions		= array('projects.creator_id'=>$buyer_id,'projects.project_status !=' => '2');
			$this->outputData['myProjects']  =  $this->skills_model->getProjects($conditions,NULL,NULL/*,$max,$orderby*/);
			$created = $this->skills_model->getProjects($conditions);
			
			$conditions		= array('projects.creator_id'=>$buyer_id,'projects.project_status =' => '2');
			$this->outputData['closedProjects']  =  $this->skills_model->getProjectsByProvider($conditions);
			$review_type = $this->outputData['closedProjects']->row();
		}
		$this->outputData['pages']	= $this->common_model->getPages();
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	= site_url('buyer/viewMyProjects/');
		$config['total_rows'] 	= $created->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $page;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links(false,'project');
		
		// get user
		$condition = array('users.id'=>$this->loggedInUser->id);
		$user_data = $this->user_model->getUsers($condition);					  
		//$user_data_result = $user_data->result();
		$user_data_row = $user_data->row();
		//pr($user_data_row->refid);
		// load affilate model
		$this->load->model('affiliate_model');
		if(isset($user_data_row->refid)) 
		{
			$condition = array('affiliate_welcome_msg.refid' => $user_data_row->refid, 'referel' => '');
			$affiliate_welcome = $this->affiliate_model->getAffiliateWelcomeMsg($condition);
			
			$condition1 = array('affiliate_welcome_msg.refid' => $user_data_row->refid, 'referel' => $this->loggedInUser->user_name, 'msg_status' => 1);
			$affiliate_welcome1 = $this->affiliate_model->getAffiliateWelcomeMsg($condition1);
			$welcome_result1 = $affiliate_welcome1->row();
			
			$welcome_result = $affiliate_welcome->row();
			//pr($welcome_result);
			if(!empty($welcome_result)) {
				
				if(empty($welcome_result1)) {
				if($welcome_result->msg_status == 0 and $welcome_result->referel == '' and $welcome_result->refid != $this->loggedInUser->user_name) {
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$welcome_result->welcome_msg));
					//Set message Id
					$updateKey 								= array('affiliate_welcome_msg.refid'=>$user_data_row->refid, 'id' => $welcome_result->id);
					
					$updateData                  	  		= array();	
					$updateData['msg_status ']  			= 1;
					$updateData['referel']  				= $this->loggedInUser->user_name;
					//Update Site Settings
					$this->affiliate_model->updateAffiliateWelcomeMeg($updateKey,$updateData);
					
					$insertData1                  			= 		array();	
					$insertData1['refid']  					= 		$user_data_row->refid;
					$insertData1['welcome_msg']  			= 		$welcome_result->welcome_msg;
					
					//Add Category
					$this->affiliate_model->addAffiliateWelcomeMsg($insertData1);	
										
					redirect('info');
				} 	
				}
					
				
			}
			//exit;
		}
		
		// checking the role of the user
		if($this->loggedInUser->role_name == 'programmer')
		 {
		 //Set the user role
		 $this->outputData['role']  =  '2';
		 // pr($this->outputData['transaction']->result());
		  //Load Programmer Account View	
	     $this->load->view('programmer/programmerAccountManage',$this->outputData);
		 
		 }
		if($this->loggedInUser->role_name == 'buyer')
		 {
		  //Load Buyer Account View	
		$this->load->view('buyer/buyerAccountManage',$this->outputData);
		 }
		
	} //Function index End
	
	
	
	/**
	 * Loads Account page.
	 *
	 * @access	Private
	 * @param	nil
	 * @return	void
	 */ 
	function accounts()
	{	
		//Load Language File
		$this->lang->load('enduser/account', $this->config->item('language_code'));
		
		//Get all the site settings
		 $this->outputData['escrow_limit']  =   $this->config->item('escrow_page_limit');
	     $this->outputData['transaction_limit']  =   $this->config->item('transaction_page_limit'); 
	 
		//Get Transaction Information
		$condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id);
		$transactions 	 = $this->transaction_model->getTransactions($condition);
		$this->outputData['transactions'] = $transactions;
		
		//Get Transaction Information
		$condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id);
		$transactions 	 = $this->transaction_model->getTransactions($condition);
		$this->outputData['transactions'] = $transactions;
		//Get Transaction Information
		 $start = $this->uri->segment(2,0);
		 
		 //Get all the site settings
		 $this->outputData['escrow_limit']  =   $this->config->item('escrow_page_limit');
		 $this->outputData['transaction_limit']  =   $this->config->item('transaction_page_limit'); 
		 $page_rows         =  $this->config->item('escrow_page_limit'); 
		 if($start > 0)
			$start = ($start-1) * $page_rows;
		 
		  //escrow without limit
		 $condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id,'transactions.reciever_id'=>$this->loggedInUser->id );
		 $escrow_transactions 	 = $this->transaction_model->getTransactions($condition);
		 $this->outputData['transactions1'] = $escrow_transactions;
		 
		 //escrow trasaction with some limit
		 		 
		 $condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id,'transactions.reciever_id'=>$this->loggedInUser->id );
		 $limit[0]			 = $page_rows;
		 $limit[1]			 = $start;
		 
		 $transactions1 	 = $this->transaction_model->getTransactions($condition,NULL,NULL,$limit);
		 $this->outputData['transactions1'] = $transactions1;
	
		  //Pagination
		 $this->load->library('pagination');
		 $config['base_url'] 	 = site_url('account/accounts');
		 $config['total_rows'] 	 = $escrow_transactions->num_rows();		
		 $config['per_page']     = $page_rows; 
		 $config['cur_page']     = $start;
		 $this->pagination->initialize($config);		
		 $this->outputData['pagination']   = $this->pagination->create_links2(false,'accounts');
		
		//Get all the projects details
		$projectList   =   $this->skills_model->getUsersproject();
		$this->outputData['projectList']    =   $projectList;
	
		// checking the role of the user
        if($this->loggedInUser->role_name == 'programmer')
		 {
		 //Set the user role
		 $this->outputData['role']  =  '2';
		  //Load Programmer Account View
		 
	     $this->load->view('programmer/programmerAccountManage',$this->outputData);
		 
		 }
		if($this->loggedInUser->role_name == 'buyer')
		 {
		  //Load Buyer Account View	
	     $this->load->view('buyer/buyerAccountManage',$this->outputData);
		 
		 }
		
	} //Function accounts End
	
	/**
	 * Loads Account page.
	 *
	 * @access	Private
	 * @param	nil
	 * @return	void
	 */ 
	function transaction()
	{	
    
	 if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
	 	 
	 //Get Transaction Information
	 $start = $this->uri->segment(3,0);
	 
	 //Get all the site settings
	 $this->outputData['escrow_limit']  	 =   $this->config->item('escrow_page_limit');
	 $this->outputData['transaction_limit']  =   $this->config->item('transaction_page_limit'); 
	 
	 $escrow_limit     						 =  $this->outputData['escrow_limit'];
	 $page_rows         					 =  $this->outputData['transaction_limit']; 
	 
	 if($start > 0)
	   $start = ($start-1) * $page_rows;
	 
	 //new test transaction
	 $condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id,'transactions.reciever_id'=>$this->loggedInUser->id);
	 $transactions 	 = $this->transaction_model->getallTransactions($condition);
	 
	 
	 $this->outputData['transactions'] = $transactions;
	 $this->outputData['users']        = $this->user_model->getUsers();
	 $this->outputData['total_records'] = $transactions->num_rows();
	 
	 //Get the transaction values from the particular limit
 	 $condition 		 = array('transactions.creator_id'=>$this->loggedInUser->id,'transactions.reciever_id'=>$this->loggedInUser->id);
	 $limit[0]			 = $page_rows;
	 $limit[1]			 = $start;
	 
	 //Get all escrow trasaction with some limit
	 $transactions1 	 = $this->transaction_model->getallTransactions($condition,NULL,NULL,$limit);
	 $this->outputData['transactions1'] = $transactions1;

      //Pagination
	 $this->load->library('pagination');
	 $config['base_url'] 	 = site_url('account/transaction');
   	 $config['total_rows'] 	 = $transactions->num_rows();		
  	 $config['per_page']     = $page_rows; 
	 $config['cur_page']     = $start;
 	 $this->pagination->initialize($config);		
	 $this->outputData['pagination']   = $this->pagination->create_links2(false,'transaction');

	 //Get all the projects details
	$projectList   =   $this->skills_model->getUsersproject();
	$this->outputData['projectList']    =   $projectList;
	if($this->loggedInUser->role_id)
       $this->load->view('buyer/buyerTransaction',$this->outputData);
	else
	   $this->load->view('buyer/programmerTransaction',$this->outputData);   
	} //Function viewall transaction end
} //End  Account Class

/* End of file Account.php */ 
/* Location: ./app/controllers/Account.php */
?>