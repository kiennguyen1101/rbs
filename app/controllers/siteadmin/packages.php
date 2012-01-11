<?php 

class Packages extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Packages()
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
		$this->load->model('package_model');
		$this->outputData['login'] = 'TRUE';
		
		//Load Packages
		$package        =     $this->package_model->getPackages();
		$this->outputData['package']   =   $package;
		
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
		redirect_admin('packages');
	   
	}//End of index Function
	
	// --------------------------------------------------------------------
	
	
	function addPackages()
	{	
	
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		
		//Intialize values for library and helpers	
		
		if($this->input->post('addPackage')){
		
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		$this->form_validation->set_rules('package','lang:package_name','required|trim|xss_clean|callback_packageCheck');
			$this->form_validation->set_rules('description','lang:description','required|trim|xss_clean');
			$this->form_validation->set_rules('start_date','lang:start_date','required|trim|xss_clean');
			$this->form_validation->set_rules('end','lang:end_date','required|trim|xss_clean');
			$this->form_validation->set_rules('is_active','lang:is_active','required|trim|xss_clean');
			$this->form_validation->set_rules('duration','lang:duration','required|trim|xss_clean');
			$this->form_validation->set_rules('amount','lang:amount','required|trim|xss_clean');
		
			//Set rules
			
			
			
			
			
			if($this->form_validation->run())
			{
			$pack1=$this->input->post('package');
			$conditions		= array('packages.package_name'=>$pack1);
		
		   $result = $this->package_model->getpackages($conditions);
		  
				
			 if ($result->num_rows()>0)
		       {
			   
			    $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('pack_ckeck')));
			   redirect_admin('packages/addPackages');
			   }
			   else
			   {
			      $insertData                      = array();	
			      $insertData['package_name']     	= $this->input->post('package');
				  $insertData['description']    	= $this->input->post('description');
				  $insertData['start_date']     	=strtotime($this->input->post('start_date'));
				  $insertData['end_date']    	= strtotime($this->input->post('end'));
				  $insertData['isactive']    	= $this->input->post('is_active');
				  $insertData['total_days']    	= $this->input->post('duration');
				  $insertData['amount']    	= $this->input->post('amount');
				  $insertData['created_date']			= get_est_time();
				  $insertData['updated_date']			= get_est_time();
				  //pr($insertData  );exit;
				  //Insert User
				  $this->package_model->createPackage($insertData);
				  
				/* //Create user balance
				 $insertBalance['id']              = '';
				 $insertBalance['user_id']         = $this->db->insert_id();
				 $insertBalance['amount']          = '0';	
				 $this->user_model->createUserBalance($insertBalance);*/
				  
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('added_success')));
				  redirect_admin('packages/viewPackage');
			}
			
			
			}
			else
			{
			   $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('pack_ckeck')));
			   redirect_admin('packages/addPackages');
			}
			
		}
		$this->load->view('admin/package/addpackages',$this->outputData);
	}//End of add package Function
	
	
	
	
		//Get Role Id For Buyers

	  	
		//echo $this->input->post('userid');
			
		//Conditions
		
		
		
		
		//pr($result->row());exit;
		
	//Function  _check_usernam End
	
	function viewPackage()
	{
		
		$package_details = $this->package_model->getPackages();
		
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
	    $package=	$this->package_model->getPackage(NULL,NULL,NULL,$limit,$order);
		$this->outputData['packageDetails'] = $package;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('packages/viewPackage');
		$config['total_rows'] 	 = $package_details->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewPackage');
		
		$this->load->view('admin/package/viewpackage',$this->outputData);
	}
	
	function searchPackage()
	{	
	
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		if($this->input->post('searchUsers')){
		
			//pr($_POST);exit;
			//Set rules
			
				    $from = $this->input->post('from');
			    	$to = $this->input->post('to');
					
					$from_date = $new_date = strtotime($from);
				    $to_date = $new_date = strtotime($to);
					$conditions		= 'end_date>='."'".$from_date."'";
					$condition      ='end_date<='."'".$to_date."'";
					$searchpackage_condition=$conditions.' AND '.$condition;	
					$query='SELECT * FROM `packages` WHERE '.$searchpackage_condition;
					$result=$this->db->query($query);
					//$result 		= $this->user_model->getPackages($conditions);
					//pr($result->row());exit;
					$this->outputData['packageDetails'] = $result;
					
					$this->load->view('admin/package/viewpackage',$this->outputData);
				
		}
		
		$this->load->view('admin/package/searchpackage',$this->outputData);
	}
	// --------------------------------------------------------------------
	
	function managePackage()
	{	
		//Load model
		$this->load->model('package_model');
	  $package_id=$this->uri->segment(4,'0');
		$condition = array('packages.id'=>$package_id);
		$result =  $this->package_model->getPackages($condition);
	    $this->outputData['packagelist'] = $result;
	 $this->load->view('admin/package/editpackage',$this->outputData);	  
	}//End of addGroup function
	
	function deletePackage()
	{	
		//Load model
		$this->load->model('package_model');
		
		//Get packages
		
		
		if($this->input->post('selectpackage'))
		{
		$packageList  =   $this->input->post('selectpackage');
			
			$list = array();
			$i=0;
			foreach($packageList as $pack)
			 {
				//echo $res;
				$condition = array('packages.id'=>$pack);
				$result =  $this->package_model->deletePackages($condition);
				//pr($result->result());
				$list[$i] = $result;
				$i = $i+1;
				//$list[$i] = $list->result();
			 }
			
			 $this->outputData['packagelist'] = $list;
		}
		else
		  {
		     $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please choose the package to delete')));
			 redirect_admin('packages/viewpackage');
		  }	 
		  
       $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Deleted Succesfully Completed')));
	  redirect_admin('packages/viewpackage');
	   
	}
	
	/**
	 * manageBids to edit the bid amounts
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editPackage()
	{	
		//Load model
		$this->load->model('package_model');
		//Get packages
		
		$this->outputData['packages']	=	$this->package_model->getPackages();
			
		//Get bidProjects
		 $package_id  =   $this->input->post('packageid');
		 $package_name  =   $this->input->post('package');
		
		$pack_descr=$this->input->post('description');
		$start_date=    $this->input->post('start_date');
		$end=     $this->input->post('end');
		$isactive=$this->input->post('is_active');
		$duration=$this->input->post('duration');
		$amount=$this->input->post('amount');
		$updatedData=array();
		$updatedData['updated_date']	= get_est_time();
		$count = count($package_id);
		
		for($i=0;$i<$count;$i++)
		 {
			//update the amount value
			$condition = array('packages.id'=>$package_id[$i]);
			$updateKey = array('packages.package_name'=>$package_name[$i]);
			$this->package_model->updatePackages(NULL,$updateKey,$condition);
			$updateKey = array('packages.description'=>$pack_descr[$i]);
			$this->package_model->updatePackages(NULL,$updateKey,$condition);
			$updateKey = array('packages.start_date'=>strtotime($start_date[$i]));
			$this->package_model->updatePackages(NULL,$updateKey,$condition);
			$updateKey = array('packages.end_date'=>strtotime($end[$i]));
			$this->package_model->updatePackages(NULL,$updateKey,$condition);
			$updateKey = array('packages.isactive'=>$isactive[$i]);
			$this->package_model->updatePackages(NULL,$updateKey,$condition);
			$updateKey = array('packages.total_days'=>$duration[$i]);
			$this->package_model->updatePackages(NULL,$updateKey,$condition);
			$updateKey = array('packages.amount'=>$amount[$i]);
			$this->package_model->updatePackages(NULL,$updateKey,$condition);
			
			$this->package_model->updatePackages(NULL,$updatedData,$condition);
		 }
        $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));
		redirect_admin('packages/viewpackage');
	   
	}//End of addGroup function
	
	//add Subscription User
	
	function addsubscription()
	{
	//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		if($this->input->post('addsubscrber'))
		{	
			//Set rules
			 $this->form_validation->set_rules('username','lang:username','required|trim|xss_clean');
			 $this->form_validation->set_rules('package_name','lang:package_name','required|trim|xss_clean');
		     $this->form_validation->set_rules('valid','lang:valid','required|trim|xss_clean');
			
			$this->form_validation->set_rules('amount','lang:amount','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{	
				  //prepare insert data
				  $insertData                  	  	= array();	
			      $insertData['username']  		= $this->input->post('username');
				  $insertData['package_id']  		= $this->input->post('package_name');
				  $insertData['valid']			 = strtotime($this->input->post('valid'));
				
				  $insertData['Amount']			    = $this->input->post('amount');
				  $insertData['created']			= get_est_time();
				  $insertData['updated_date']=get_est_time();

				  //Add Groups
				  $this->package_model->addsubscription($insertData);
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('added_success')));
				   redirect_admin('packages/viewsubscriptionuser');
		 	} 
		} //If - Form Submission End
		
		// Get the Package details.
		$this->outputData['packages']	=	$this->package_model->getPackages();
		
		$this->load->view('admin/package/addsubscription',$this->outputData);

	}
	
	function viewsubscriptionuser()
	{   
	  $subscription_user= $this->package_model->getSubscriptionUser();
	   $start = $this->uri->segment(4,0);
		//Get the inbox mail list 
     	 $page_rows         					 =  $this->config->item('mail_limit');

		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
			
			//echo 'hihiihihi'.$limit;
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		//Get Groups
	    $subscription=	$this->package_model->getSubscription_User(NULL,NULL,NULL,$limit,$order);
		$this->outputData['subscriberdetails'] = $subscription;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('packages/viewsubscriptionuser');
		$config['total_rows'] 	 = $subscription_user->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewsubscriptionuser');
		
		$this->load->view('admin/package/viewsubscriptionuser',$this->outputData);
	}
	
	
	function searchSubscription()
	{	
	
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		if($this->input->post('search')){
		
			//pr($_POST);exit;
			//Set rules
			
				    $sub_userid = $this->input->post('subuserid');
			    	$condition=array('subscriptionuser.id'=>$sub_userid);
					$result=$this->package_model->getSubscriptionUser($condition);
					$this->outputData['subscriberdetails'] = $result;
					$this->load->view('admin/package/viewsubscriptionuser',$this->outputData);
				
		}
		else
		{
		
		$this->load->view('admin/package/searchsubscriptionuser',$this->outputData);
		}
	}
	// --------------------------------------------------------------------
	
	function managesubscriptionuser()
	{	
		//Load model
		$this->load->model('package_model');
		//load validation library
		$this->load->library('form_validation');
		
		$this->outputData['subscriptionuser']	=	$this->package_model->getSubscriptionUser();
		if($this->input->post('selectsubscriptionuser'))
		{
			$subscriptionuserList  =   $this->input->post('selectsubscriptionuser');
			
			$list = array();
			$i=0;
			foreach($subscriptionuserList as $subuser)
			 {
				//echo $res;
				
			     
				$condition = array('subscriptionuser.id'=>$subuser);
				
				$result =  $this->package_model->getSubscriptionUser($condition);
				//pr($result->result());
				$list[$i] = $result;
				$i = $i+1;
				//$list[$i] = $list->result();
			 }
			 
			 $this->outputData['packages']	=	$this->package_model->getPackages();
			 
			 $this->outputData['subscriptionuserList'] = $list;
			
			//pr($_POST);
			//exit;
			//Load View
			
			
			
			$this->load->view('admin/package/editsubscriptionuser',$this->outputData);
		}
		else
		  {
		     $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Please choose the package to edit')));
			redirect_admin('packages/viewsubscriptionuser');
		  }	 
		
	   
	}//End of addGroup function
	
	function editsubscriptionuser()
	{	
		//Load model
		$this->load->model('package_model');
		
		//load validation library
		$this->load->library('form_validation');
		
		
		$this->outputData['subscriptionuser']	=	$this->package_model->getSubscriptionUser();
		
	
		
		     $username  =   $this->input->post('username');
			 $package_name  =$this->input->post('package_name');		
		     $valid=$this->input->post('valid');
			
			 $amount=$this->input->post('amount');
			 $subscriptionuser_id=$this->input->post('subscriptionuser_id');
			 $updatedData['updated_date']	= get_est_time();
		     $sub_count = count($subscriptionuser_id);
		
		for($i=0;$i<$sub_count;$i++)
		 {
			//update the amount value
			$condition = array('subscriptionuser.id'=>$subscriptionuser_id[$i]);
			$updateKey = array('subscriptionuser.username'=>$username[$i]);
			
			$this->package_model->updateSubscritionUser(NULL,$updateKey,$condition);
			$updateKey = array('subscriptionuser.package_id'=>$package_name[$i]);
			
			$this->package_model->updateSubscritionUser(NULL,$updateKey,$condition);
			$updateKey = array('subscriptionuser.valid'=>$valid[$i]);
			$this->package_model->updateSubscritionUser(NULL,$updateKey,$condition);
			
			$updateKey = array('subscriptionuser.amount'=>$amount[$i]);
			$this->package_model->updateSubscritionUser(NULL,$updateKey,$condition);
			$this->package_model->updateSubscritionUser(NULL,$updatedData,$condition);
			
		 }
        $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));
		redirect_admin('packages/viewsubscriptionuser');
	  
	}//End of addGroup function
	
	function deleteSubscriptionUser()
	{	
		//Load model
		$this->load->model('package_model');
		//Get packages
		
	if($this->input->post('selectsubscriptionuser'))
		{
		$subscriptionList=$this->input->post('selectsubscriptionuser');
		$list = array();
			$i=0;
			foreach($subscriptionList as $user)
			 {
				
				$condition = array('subscriptionuser.id'=>$user);
				$result =  $this->package_model->deleteSubscription($condition);
				//pr($result->result());
				$list[$i] = $result;
				$i = $i+1;
				//$list[$i] = $list->result();
			 }
			
			 $this->outputData['subscriberdetails'] = $list;
		}
		else
		  {
		     $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please choose the package to delete')));
			 redirect_admin('packages/viewpackage');
		  }	  
		  
       $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Deleted Succesfully Completed')));
	  redirect_admin('packages/viewsubscriptionuser');
	   
	}
	
	function viewsubscriptionpayment()
	{
	$start = $this->uri->segment(4,0);
		if($start > 0)
		  $start = $start;
		//Get the inbox mail list 
     	$page_rows     =$this->config->item('mail_limit');   
		
		$subscriptionuser= $this->package_model->getSubscriptionUser();
		 		
	 //$query='SELECT * FROM subscriptionuser';
	 //$subscriptionuser=$this->db->query($query);
	 
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            = 'asc';
    //$subscriptionuser=$this->package_model->getSubscriptionallUser();
	
	  //foreach($subscriptionuser->result() as $subscriptionpayment)
	  // {
		//$package_id=$subscriptionuser->package_id;
		//$conditions=array('transactions.package_id'=>'subscriptionuser.package_id');
		$subscription_payment= $this->package_model->getSubscriptionpayment();
		
		//}
		

         
		$subscription_payment1= $this->package_model->getSubscriptionpayment(NULL,$limit,$order);
		$this->outputData['subscription_payment']=$subscription_payment1;
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('packages/viewsubscriptionpayment');
		$config['total_rows'] 	 = $subscription_payment->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'viewsubscriptionpayment');
		

	
		$this->load->view('admin/package/viewsubscriptionpayment',$this->outputData);
		
	}
	function searchSubscriptionpayment()
	{	
	
		//load validation library
		$this->load->library('form_validation');		
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

		if($this->input->post('searchUsers')){
		  
				    $username = $this->input->post('username');
			    $id='';
					$conditions		=array('user_name'=>$username); 
					$user_list=$this->package_model->getUsers($conditions);
					$user_id=$user_list->result();
						foreach($user_id as $userid )
						{
						$id=$userid->id;
						}
					
					$package_conditions	= 'creator_id='."'".$id."'" .' AND '.'package_id!=0';
					
					$query='SELECT * FROM `transactions` WHERE '.$package_conditions;
					$package_details=$this->db->query($query);
					
					$packagedetails=$package_details->result();
					$package_id='';
					foreach($packagedetails as $package)
					{
					$package_id= $package->package_id;
			    	
					//pr($subscriptionuser_details);
					}
				$condition=array('id'=>$package_id);
				$subscriptionuser_details= $this->package_model->getSubscriptionpayment($conditions);
                $this->outputData['subscription_payment']= $subscriptionuser_details;
				$this->load->view('admin/package/viewsubscriptionpayment',$this->outputData);	
		}
	else
	{
		
	$this->load->view('admin/package/searchsubscriptionpayment',$this->outputData);	
	 }
	}
	// --------------------------------------------------------------------
	
}


?>
