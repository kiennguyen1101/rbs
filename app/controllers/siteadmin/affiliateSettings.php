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
class AffiliateSettings extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	   
	/**
	* Constructor 
	*
	* Loads language files and models needed for this controller
	*/ 
	function AffiliateSettings()
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
		$this->load->model('affiliate_model');
		
		$this->load->helper('cookie');
		

	}//Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads payement settings page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function index()
	{	
		//Load model
		$this->load->model('settings_model');
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		
		//Get Affiliates
		//$condition = array('affiliate_payment.user_type' => $this->input->post('buyer'));	

		$affiliate_result = $this->affiliate_model->getAffiliatePayment();
		$this->outputData['affiliates'] = $affiliate_result;
		
		if($this->input->post('regularProject'))
		{	
			//Set rules
			$this->form_validation->set_rules('buyer_affiliate_fee','lang:buyer_affiliate_fee_validation','required|trim|xss_clean|is_natural');
			$this->form_validation->set_rules('buyer_min_amount','lang:buyer_min_amount_validation','required|trim|xss_clean|is_natural');
			$this->form_validation->set_rules('buyer_min_payout','lang:buyer_min_payout_validation','required|trim|xss_clean|is_natural');
			$this->form_validation->set_rules('buyer_max_payout','lang:buyer_max_payout_validation','required|trim|xss_clean');
			$this->form_validation->set_rules('seller_affiliate_fee','lang:seller_affiliate_fee_validation','required|trim|xss_clean|is_natural');
			$this->form_validation->set_rules('seller_min_amount','lang:seller_min_amount_validation','required|trim|xss_clean|is_natural');
			$this->form_validation->set_rules('seller_min_payout','lang:seller_min_payout_validation','required|trim|xss_clean|is_natural');
			$this->form_validation->set_rules('seller_max_payout','lang:seller_max_payout_validation','required|trim|xss_clean');
			
			$this->outputData['buyer_affiliate_fee'] 		= $this->input->post('buyer_affiliate_fee');
			$this->outputData['buyer_min_amount'] 			= $this->input->post('buyer_min_amount');
			$this->outputData['buyer_min_payout'] 			= $this->input->post('buyer_min_payout');
			$this->outputData['buyer_max_payout'] 			= $this->input->post('buyer_max_payout');
			$this->outputData['seller_affiliate_fee'] 	= $this->input->post('seller_affiliate_fee');
			$this->outputData['seller_min_amount'] 		= $this->input->post('seller_min_amount');
			$this->outputData['seller_min_payout'] 		= $this->input->post('seller_min_payout');
			$this->outputData['seller_max_payout'] 		= $this->input->post('seller_max_payout');
			
			if($this->form_validation->run())
			{	
				  if(!isset($affiliate_result['num_rows'])) {
					  //prepare insert data
					  $insertData                  	  			= array();	
					  $insertData['buyer_affiliate_fee']  		= $this->input->post('buyer_affiliate_fee');
					  $insertData['buyer_min_amount']  			= $this->input->post('buyer_min_amount');
					  $insertData['buyer_min_payout']  			= $this->input->post('buyer_min_payout');
					  $insertData['buyer_max_payout']  			= $this->input->post('buyer_max_payout');
					  $insertData['seller_affiliate_fee']  	= $this->input->post('seller_affiliate_fee');
					  $insertData['seller_min_amount']  	= $this->input->post('seller_min_amount');
					  $insertData['seller_min_payout']  	= $this->input->post('seller_min_payout');
					  $insertData['seller_max_payout']  	= $this->input->post('seller_max_payout');
					  
					  //print_r($insertData);
	
					  //Add Category
					  $this->affiliate_model->addAffiliatePayment($insertData);
				  } else {
					  //Set Payment Id
					  $updateKey 								= array('id'=>$this->input->post('id'));
					  
					  $updateData                  	  			= array();	
					  $updateData['buyer_affiliate_fee']  		= $this->input->post('buyer_affiliate_fee');
					  $updateData['buyer_min_amount']    		= $this->input->post('buyer_min_amount');
					  $updateData['buyer_min_payout']   		= $this->input->post('buyer_min_payout');
					  $updateData['buyer_max_payout']    		= $this->input->post('buyer_max_payout');
					  $updateData['seller_affiliate_fee']   = $this->input->post('seller_affiliate_fee');
					  $updateData['seller_min_amount']   	= $this->input->post('seller_min_amount');
					  $updateData['seller_min_payout']    	= $this->input->post('seller_min_payout');
					  $updateData['seller_max_payout']    	= $this->input->post('seller_max_payout');
					  //Update Site Settings
					  $this->affiliate_model->updateAffiliateSettings($updateKey,$updateData);				  
				  }
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				  redirect_admin('affiliateSettings');
		 	} 

		} // Form Submission End
		
		
		if($this->input->post('featuredProject'))
		{	
			//Set rules
			$this->form_validation->set_rules('buyer_project_fee','lang:buyer_project_fee_validation','required|trim|xss_clean|is_natural');
			$this->form_validation->set_rules('seller_project_fee','lang:seller_project_fee_validation','required|trim|xss_clean|is_natural');
			
			$this->outputData['buyer_project_fee'] 				= $this->input->post('buyer_project_fee');
			$this->outputData['seller_project_fee'] 		= $this->input->post('seller_project_fee');
			
			if($this->form_validation->run())
			{	
				  if(!isset($affiliate_result['num_rows'])) {
					  //prepare insert data
					  $insertData                  	  			= array();	
					  $insertData['buyer_project_fee']  		= $this->input->post('buyer_project_fee');
					  $insertData['seller_project_fee']  	= $this->input->post('seller_project_fee');
					  
					  //print_r($insertData);
	
					  //Add Category
					  $this->affiliate_model->addAffiliatePayment($insertData);
				  } else {
					  //Set Payment Id
					  $updateKey 								= array('id'=>$this->input->post('id'));
					  
					  $updateData                  	  			= array();	
					  $updateData['buyer_project_fee']  		= $this->input->post('buyer_project_fee');
					  $updateData['seller_project_fee']    	= $this->input->post('seller_project_fee');

					  //Update Site Settings
					  $this->affiliate_model->updateAffiliateSettings($updateKey,$updateData);				  
				  }
				  
				  //Notification message
				  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('updated_success')));
				  redirect_admin('affiliateSettings');
		 	} 

		} // Form Submission End
		
		
		//Get Affiliates
		$affiliate_result = $this->affiliate_model->getAffiliatePayment();		
		$this->outputData['affiliates'] = $affiliate_result;			
		$this->load->view('admin/settings/affiliateSettings',$this->outputData);
	   
	}//End of index function
	
	function clickThroughs()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get Projects
		$click_throughs1	=	$this->affiliate_model->getClickThroughs();
		
		$start =  $this->uri->segment(4,0); 
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		$click_throughs 	         = $this->affiliate_model->getClickThroughs(NULL,NULL,NULL,$limit,$order);  
		
		$this->outputData['click_throughs'] = $click_throughs;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('affiliateSettings/clickThroughs');  
		$config['total_rows'] 	 = $click_throughs1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'clickThroughs');
			
		//Load View
		$this->load->view('admin/settings/viewClickThroughs',$this->outputData);
	   
	}//End of index function
	
	
	
	/**
	 * manage Click Throughs
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function manageClickThroughs()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get bidProjects
		$click_throughs1	=	$this->affiliate_model->getClickThroughs();
		//$this->outputData['projects']	=	$this->affiliate_model->getProjects();
		if($this->input->post('clickThroughList'))
		{
			$clickThroughList  =   $this->input->post('clickThroughList');
			
			$list = array();
			$i=0;
			foreach($clickThroughList as $res)
			 {
				//echo $res;
				$condition = array('clickthroughs.id'=>$res);
				$result =  $this->affiliate_model->getClickThroughs($condition);
				//pr($result->result());
				$list[$i] = $result;
				$i = $i+1;
				//$list[$i] = $list->result();
			 }
			
			 $this->outputData['list'] = $list;

			//pr($_POST);
			//exit;
			//Load View
			$this->load->view('admin/settings/editClickThroughs',$this->outputData);
		}
		else
		  {
		     $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Please choose the clickthroughs to edit')));
			 redirect_admin('affiliateSettings/viewClickThroughs');
		  }	 
		
	   
	}//End of addGroup function
	
	/**
	 * manageBids to edit the bid amounts
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editClickThroughs()
	{	
		//Load model
		$this->load->model('affiliate_model');
			
		//Get bidProjects
		$count =  count($this->input->post('clickid'));
		
		$clickid = $this->input->post('clickid',TRUE);
		$refid = $this->input->post('refid',TRUE);
		$ipaddress = $this->input->post('ipaddress',TRUE);
		for($i=0;$i<$count;$i++)
		  {
		  	$updateKey['refid']    = $refid[$i]; 
			$updateKey['ipaddress']  = $ipaddress[$i];
			$condition = array('clickthroughs.id'=>$clickid[$i]);
			$this->affiliate_model->updateClickThroughs(NULL,$updateKey,$condition);
		  }
        $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));

		redirect_admin('affiliateSettings/clickThroughs');
	   
	}//End of addGroup function
	
	
	/**
	 * deleteBids
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteClickThroughs()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get bidProjects
		$click_throughs1	=	$this->affiliate_model->getClickThroughs();
		//$this->outputData['projects']	=	$this->affiliate_model->getProjects();
		$clickThroughList  =   $this->input->post('clickThroughList');
		foreach($clickThroughList as $res)
		 { 
			//update the amount value
			$condition = array('clickthroughs.id'=>$res);
		 	$this->affiliate_model->deleteClickThroughs(NULL,$condition);
		 }
       $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));
	   redirect_admin('affiliateSettings/clickThroughs');
	   
	}//End of addGroup function	
	

	/**
	 * search clickthroughs
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function searchClickThroughs()
	{	
		if($this->input->post('clickid'))
		  {
			  //Load model
			$this->load->model('affiliate_model');
			
			//Get bidProjects
			$this->outputData['click_throughs']	=	$this->affiliate_model->getClickThroughs();
			
			$clickid  =   $this->input->post('clickid');
			
			$condition = array('clickthroughs.id'=>$clickid);
			$list =  $this->affiliate_model->getClickThroughs($condition);
			$count = count($list);
			if($count > 0)
			  $this->outputData['click_throughs'] = $list;
			//Load View
			$this->load->view('admin/settings/viewClickThroughs',$this->outputData);
		  }
		 else
		  { 
	    	//Load View
		    $this->load->view('admin/settings/searchClickThroughs',$this->outputData);
		  }	
	}//End of addGroup function	
	
	function sales()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get Projects
		$affiliate_sales1	=	$this->affiliate_model->getAffiliateSales();
		
		$start =  $this->uri->segment(4,0); 
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		$affiliate_sales 	         = $this->affiliate_model->getAffiliateSales(NULL,NULL,NULL,$limit,$order);   
		
		$this->outputData['affiliate_sales'] = $affiliate_sales;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('affiliateSettings/sales');  
		$config['total_rows'] 	 = $affiliate_sales1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'sales');
			
		//Load View
		$this->load->view('admin/settings/viewSales',$this->outputData);
			   
	}//End of index function
	
	
	
	/**
	 * manage Click Throughs
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function manageSales()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get bidProjects
		$affiliate_sales1	=	$this->affiliate_model->getAffiliateSales();
		//$this->outputData['projects']	=	$this->affiliate_model->getProjects();
		if($this->input->post('salesList'))
		{
			$salesList  =   $this->input->post('salesList');
			
			$list = array();
			$i=0;
			foreach($salesList as $res)
			 {
				//echo $res;
				$condition = array('sales.id'=>$res);
				$result =  $this->affiliate_model->getAffiliateSales($condition);
				//pr($result->result());
				$list[$i] = $result;
				$i = $i+1;
				//$list[$i] = $list->result();
			 }
			
			 $this->outputData['list'] = $list;

			//pr($_POST);
			//exit;
			//Load View
			$this->load->view('admin/settings/editAffiliateSales',$this->outputData);
		}
		else
		  {
		     $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Please choose the clickthroughs to edit')));
			 redirect_admin('affiliateSettings/viewSales');
		  }	 
		
	   
	}//End of addGroup function
	
	/**
	 * manage affiliate sales to edit the sales amounts
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editAffiliateSales()
	{	
		//Load model
		$this->load->model('affiliate_model');
			
		//Get bidProjects
		$count =  count($this->input->post('saleid'));
		
		$saleid = $this->input->post('saleid',TRUE);
		$refid = $this->input->post('refid',TRUE);
		$ipaddress = $this->input->post('ipaddress',TRUE);
		$payment = $this->input->post('payment',TRUE);
		for($i=0;$i<$count;$i++)
		  {
		  	$updateKey['refid']    = $refid[$i]; 
			$updateKey['ipaddress']  = $ipaddress[$i];
			$updateKey['payment']  = $payment[$i];
			$condition = array('sales.id'=>$saleid[$i]);
			$this->affiliate_model->updateAffiliateSales(NULL,$updateKey,$condition);
		  }
        $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));

		redirect_admin('affiliateSettings/sales');
	   
	}//End of addGroup function
	
	
	/**
	 * delete affiliate sales
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteSales()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get bidProjects
		$affiliate_sales1	=	$this->affiliate_model->getAffiliateSales();
		//$this->outputData['projects']	=	$this->affiliate_model->getProjects();
		$salesList  =   $this->input->post('salesList');
		foreach($salesList as $res)
		 { 
			//update the amount value
			$condition = array('sales.id'=>$res);
		 	$this->affiliate_model->deleteAffiliateSales(NULL,$condition);
		 }
       $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));
	   redirect_admin('affiliateSettings/sales');
	   
	}//End of addGroup function	
	
	/**
	 * search affiliates sales
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function searchAffiliateSales()
	{	
		if($this->input->post('saleid'))
		  {
			  //Load model
			$this->load->model('affiliate_model');
			
			//Get bidProjects
			$this->outputData['affiliate_sales']	=	$this->affiliate_model->getAffiliateSales();
			
			$saleid  =   $this->input->post('saleid');
			
			$condition = array('sales.id'=>$saleid);
			$list =  $this->affiliate_model->getAffiliateSales($condition);
			$count = count($list);
			if($count > 0)
			  $this->outputData['affiliate_sales'] = $list;
			//Load View
			$this->load->view('admin/settings/viewSales',$this->outputData);
		  }
		 else
		  { 
	    	//Load View
		    $this->load->view('admin/settings/searchAffiliateSales',$this->outputData);
		  }	
	}//End of addGroup function	
	
	
	
	
	
	function questions()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get Projects
		$affiliate_guest1	=	$this->affiliate_model->getAffiliateQuestions();
		
		$start =  $this->uri->segment(4,0); 
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		$affiliate_guest 	         = $this->affiliate_model->getAffiliateQuestions(NULL,NULL,NULL,$limit,$order);   
		
		$this->outputData['affiliate_guest'] = $affiliate_guest;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('affiliateSettings/questions');  
		$config['total_rows'] 	 = $affiliate_guest1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'questions');
			
		//Load View
		$this->load->view('admin/settings/viewQuestions',$this->outputData);
			   
	}//End of index function
	
	
	
	/**
	 * manage Affiliate Questions
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function manageQuestions()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get bidProjects
		$affiliate_guest1	=	$this->affiliate_model->getAffiliateQuestions();
		//$this->outputData['projects']	=	$this->affiliate_model->getProjects();
		if($this->input->post('questionsList'))
		{
			$questionsList  =   $this->input->post('questionsList');
			
			$list = array();
			$i=0;
			foreach($questionsList as $res)
			 {
				//echo $res;
				$condition = array('affiliate_questions.id'=>$res);
				$result =  $this->affiliate_model->getAffiliateQuestions($condition);
				//pr($result->result());
				$list[$i] = $result;
				$i = $i+1;
				//$list[$i] = $list->result();
			 }
			
			 $this->outputData['list'] = $list;

			//pr($_POST);
			//exit;
			//Load View
			$this->load->view('admin/settings/editAffiliateQuestions',$this->outputData);
		}
		else
		  {
		     $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please choose the questions to edit')));
			 redirect_admin('affiliateSettings/questions');
		  }	 
		
	   
	}//End of addGroup function
	
	/**
	 * manage affiliate sales to edit the sales amounts
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editQuestions()
	{	
		//Load model
		$this->load->model('affiliate_model');
			
		//Get bidProjects
		$count =  count($this->input->post('questionid'));
		
		$questionid = $this->input->post('questionid',TRUE);
		$email = $this->input->post('email',TRUE);
		$subject = $this->input->post('subject',TRUE);
		$questions = $this->input->post('questions',TRUE);

		for($i=0;$i<$count;$i++)
		  {
		  	$updateKey['id']    = $questionid[$i]; 
			$updateKey['email']  = $email[$i];
			$updateKey['subject']  = $subject[$i];
			$updateKey['questions']  = $questions[$i];
			
			$condition = array('affiliate_questions.id'=>$questionid[$i]);
			$this->affiliate_model->updateAffiliateGuest(NULL,$updateKey,$condition);
		  }
        $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));

		redirect_admin('affiliateSettings/questions');
	   
	}//End of addGroup function
	
	
	/**
	 * delete affiliate sales
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteQuestions()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get bidProjects
		$affiliate_guest1	=	$this->affiliate_model->getAffiliateQuestions();
		//$this->outputData['projects']	=	$this->affiliate_model->getProjects();
		$questionsList  =   $this->input->post('questionsList');
		foreach($questionsList as $res)
		 { 
			//update the amount value
			$condition = array('affiliate_questions.id'=>$res);
		 	$this->affiliate_model->deleteAffiliateGuest(NULL,$condition);
		 }
       $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));
	   redirect_admin('affiliateSettings/questions');
	   
	}//End of addGroup function	
	
	
	// get affiliate archives
	function archives()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get Projects
		$affiliate_archive1	=	$this->affiliate_model->getAffiliateArchives();
		
		$start =  $this->uri->segment(4,0); 
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		$affiliate_archive 	         = $this->affiliate_model->getAffiliateArchives(NULL,NULL,NULL,$limit,$order);   
		
		$this->outputData['affiliate_archive'] = $affiliate_archive;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('affiliateSettings/archives');  
		$config['total_rows'] 	 = $affiliate_archive1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'archives');
			
		//Load View
		$this->load->view('admin/settings/viewArchives',$this->outputData);
			   
	}//End of index function
	
	
	/**
	 * archive affiliate questions
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function archiveQuestions()
	{	
		//Load model
		$this->load->model('affiliate_model');

		//$this->outputData['projects']	=	$this->affiliate_model->getProjects();
		if($this->input->post('questionsList'))
		{
		$questionsList  =   $this->input->post('questionsList');
		foreach($questionsList as $res)
		 { 
			//update the amount value
			$condition = array('affiliate_questions.id'=>$res);
//		 	$this->affiliate_model->deleteAffiliateGuest(NULL,$condition);
			$affiliate_guest1	=	$this->affiliate_model->getAffiliateQuestions($condition);
			foreach($affiliate_guest1->result() as $row)
			{
				$insertData                  	  	= array();	
				$insertData['email']  				= $row->email;
				$insertData['subject']  			= $row->subject;
				$insertData['questions']  			= $row->questions;
			
				//Add Archives
				$this->affiliate_model->addAffiliateArchives($insertData);
				
				$condition1 = array('affiliate_questions.id'=>$row->id);
				$this->affiliate_model->deleteAffiliateGuest(NULL,$condition1);
			}
		 }
		 $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));
	   redirect_admin('affiliateSettings/questions');
		 }
		 else
		 {
		 $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please choose the Affiliate questions')));
			 redirect_admin('affiliateSettings/questions');
		 }
		 
       
	   
	}//End of addGroup function	
	
	/**
	 * manage Affiliate Questions
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function manageArchives()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get bidProjects
		$affiliate_guest1	=	$this->affiliate_model->getAffiliateArchives();
		//$this->outputData['projects']	=	$this->affiliate_model->getProjects();
		if($this->input->post('archivesList'))
		{
			$archivesList  =   $this->input->post('archivesList');
			
			$list = array();
			$i=0;
			foreach($archivesList as $res)
			 {
				//echo $res;
				$condition = array('affiliate_archive.id'=>$res);
				$result =  $this->affiliate_model->getAffiliateArchives($condition);
				//pr($result->result());
				$list[$i] = $result;
				$i = $i+1;
				//$list[$i] = $list->result();
			 }
			
			 $this->outputData['list'] = $list;

			//pr($_POST);
			//exit;
			//Load View
			$this->load->view('admin/settings/editArchivedQuestions',$this->outputData);
		}
		else
		  {
		     $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please choose the archives to edit')));
			 redirect_admin('affiliateSettings/archives');
		  }	 
		
	   
	}//End of addGroup function
	
	/**
	 * manage affiliate sales to edit the sales amounts
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editArchives()
	{	
		//Load model
		$this->load->model('affiliate_model');
			
		//Get bidProjects
		$count =  count($this->input->post('archiveid'));
		
		$archiveid = $this->input->post('archiveid',TRUE);
		$email = $this->input->post('email',TRUE);
		$subject = $this->input->post('subject',TRUE);
		$questions = $this->input->post('questions',TRUE);

		for($i=0;$i<$count;$i++)
		  {
		  	$updateKey['id']    = $archiveid[$i]; 
			$updateKey['email']  = $email[$i];
			$updateKey['subject']  = $subject[$i];
			$updateKey['questions']  = $questions[$i];
			
			$condition = array('affiliate_archive.id'=>$archiveid[$i]);
			$this->affiliate_model->updateArchivedQuestions(NULL,$updateKey,$condition);
		  }
        $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));

		redirect_admin('affiliateSettings/archives');
	   
	}//End of addGroup function
	
	
	/**
	 * delete affiliate sales
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteArchives()
	{	
		//Load model
		$this->load->model('affiliate_model');
if( $this->input->post('archivesList'))
{
		$archivesList  =   $this->input->post('archivesList');
		$list = array();
			$i=0;
		foreach($archivesList as $res)
		 { 
			$condition = array('affiliate_archive.id'=>$res);
		 	$result = $this->affiliate_model->deleteArchivedQuestions(NULL,$condition);
			$list[$i] = $result;
				$i = $i+1;
		 }
		 $this->outputData['archivesList '] = $list;
		 }
		 else
		  {
		     $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('error',$this->lang->line('Please choose the archives to delete')));
			 redirect_admin('affiliateSettings/archives');
		  }	 
		  
       $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));
	   redirect_admin('affiliateSettings/archives');
	   
	}//End of addGroup function	
	
	/**
	 * search affiliates sales
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function searchQuestions()
	{	
		if($this->input->post('questionid'))
		  {
			  //Load model
			$this->load->model('affiliate_model');
			
			//Get bidProjects
			$this->outputData['affiliate_guest']	=	$this->affiliate_model->getAffiliateQuestions();
			
			$questionid  =   $this->input->post('questionid');
			
			$condition = array('affiliate_questions.id'=>$questionid);
			$list =  $this->affiliate_model->getAffiliateQuestions($condition);
			$count = count($list);
			if($count > 0)
			  $this->outputData['affiliate_guest'] = $list;
			//Load View
			$this->load->view('admin/settings/viewQuestions',$this->outputData);
		  }
		 else
		  { 
	    	//Load View
		    $this->load->view('admin/settings/searchAffiliateQuestions',$this->outputData);
		  }	
	}//End of addGroup function	
	
	/**
	 * Replay affiliate questions
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function replay()
	{	
		if($this->uri->segment(3))
		  {
			//Load model
			$this->load->model('settings_model');
			
			//load validation library
			$this->load->library('form_validation');
			
			//Load Form Helper
			$this->load->helper('form');			  
			
			//Load model
			$this->load->model('affiliate_model');
			
			//Get affiliate questions
			if($this->uri->segment(4)) {
				$condition				 =  array('affiliate_questions.id' => $this->uri->segment(4));
				$affiliate_answer  		 =	$this->affiliate_model->getAffiliateQuestions($condition);
				$affiliate_answer_rs  	 = 	$affiliate_answer->result();
				//pr($affiliate_answer_rs);
				//exit;
				$arr_result = (array)$affiliate_answer->row();
				
				$this->outputData['email']		= $arr_result['email'];
				$this->outputData['subject']	= $arr_result['subject'];
				$this->outputData['questions']	= $arr_result['questions'];				
			}
			
			//Load Model For Mail
			$this->load->model('email_model');
			$this->load->model('affiliate_model');
			
			//Set rules
			$this->form_validation->set_rules('comments','lang:comments_validation','required|trim|xss_clean');
			
			if($this->form_validation->run())
			{	
					//Send mail
					$toEmail = $this->input->post('email');
					
					$fromEmail = $this->config->item('site_admin_mail');
					
					$mailSubject = $this->input->post('subject');
					
					$mailContent = $this->input->post('comments');
					
					$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
					
					// Get Affiliate Questions
					$condition = array('affiliate_questions.id'=>$this->uri->segment(4));
					$affiliate_guest1	=	$this->affiliate_model->getAffiliateQuestions($condition);
					foreach($affiliate_guest1->result() as $row)
					{
						$insertData                  	  	= array();	
						$insertData['email']  				= $row->email;
						$insertData['subject']  			= $row->subject;
						$insertData['questions']  			= $row->questions;
						$insertData['answer']  				= $this->input->post('comments');
					
						//Add Archives
						$this->affiliate_model->addAffiliateArchives($insertData);
						
						$condition1 = array('affiliate_questions.id'=>$row->id);
						$this->affiliate_model->deleteAffiliateGuest(NULL,$condition1);
						
						$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));

						redirect_admin('affiliateSettings/questions');				
					}

			}
			
		    $this->load->view('admin/settings/answerQuestions',$this->outputData);
		}
	}//End of addGroup function	
	
	
	function releasePayment()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get Projects
		$release_payments1	=	$this->affiliate_model->getUnReleasePayments();
		
		$start =  $this->uri->segment(4,0); 
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='asc';
		
		$release_payments 	         = $this->affiliate_model->getUnReleasePayments(NULL,NULL,NULL,$limit,$order);
		
		$condition  					= array('affiliate_unreleased_payments.is_released' => 0);
		$unrelease_payments 	         = $this->affiliate_model->getUnReleasePayments($condition);  
		 
		$release_payments1	=	$this->affiliate_model->getUnReleasePayments();   
		
		$this->outputData['release_payments'] = $unrelease_payments;
		
		//Pagination
		$this->load->library('pagination');
		$config['base_url'] 	 = admin_url('affiliateSettings/releasePayment');  
		$config['total_rows'] 	 = $release_payments1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'releasePayment');
			
		//Load View
		$this->load->view('admin/settings/viewReleasePayments',$this->outputData);
			   
	}//End of index function
	
	
	
	/**
	 * manage affiliate relaese payments
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function manageReleasePayments()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get bidProjects
		$release_payments1	=	$this->affiliate_model->getUnReleasePayments();
		//$this->outputData['projects']	=	$this->affiliate_model->getProjects();
		if($this->input->post('releaseList'))
		{
			$releaseList  =   $this->input->post('releaseList');
			
			$list = array();
			$i=0;
			foreach($releaseList as $res)
			 {
				//echo $res;
				$condition = array('affiliate_unreleased_payments.id'=>$res);
				$result =  $this->affiliate_model->getUnReleasePayments($condition);
				//pr($result->result());
				$list[$i] = $result;
				$i = $i+1;
				//$list[$i] = $list->result();
			 }
			
			 $this->outputData['list'] = $list;

			//pr($_POST);
			//exit;
			//Load View
			$this->load->view('admin/settings/editReleasePayments',$this->outputData);
		}
		else
		  {
		     $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Please choose the clickthroughs to edit')));
			 redirect_admin('affiliateSettings/viewReleasePayments');
		  }	 
		
	   
	}//End of addGroup function
	
	
	/**
	 * manage affiliate payments to edit the release amount
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function editReleasePayments()
	{	
		//Load model
		$this->load->model('affiliate_model');
			
		//Get bidProjects
		$count =  count($this->input->post('releaseid'));
		
		$releaseid = $this->input->post('releaseid',TRUE);
		$refid = $this->input->post('refid',TRUE);
		$account_type = $this->input->post('account_type',TRUE);
		$payment = $this->input->post('payment',TRUE);

		for($i=0;$i<$count;$i++)
		  {
		  	$updateKey['id']    = $releaseid[$i]; 
			$updateKey['refid']    = $refid[$i]; 
			$updateKey['account_type']  = $account_type[$i];
			$updateKey['payment']  = $payment[$i];

			$condition = array('affiliate_unreleased_payments.refid'=>$refid[$i], 'affiliate_unreleased_payments.is_released' => '0');
			$this->affiliate_model->updateUnReleasedPayments($refid,$updateKey,$condition);
		  }
        $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));

		redirect_admin('affiliateSettings/releasePayment');
	   
	}//End of addGroup function
	
	
	/**
	 * manage affiliate sales to edit the release amounts
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function releasedPayment()
	{	
		//Load model
		$this->load->model('affiliate_model');
		
		$refid = $this->uri->segment(4);

		$condition = array('sales.refid'=>$refid);
		$result =  $this->affiliate_model->getReleasePayments($condition);
		$result1 = $result->row();
	
		// get user
		$condition = array('users.refid'=>$refid);
		$user_data = $this->user_model->getUsers($condition);					  
		//$user_data_result = $user_data->result();
		$user_data_row = $user_data->row();

		 $user_id = $user_data_row->id;
		
		$total =  $result1->total;
	    $refid = $result1->refid;
		$account_type = $result1->account_type;
		
		
	  if($this->uri->segment(4)) {
		//prepare insert data
	  $insertData                  	= 		array();	
	  $insertData['refid']  		= 		$refid;
	  $insertData['account_type']  	= 		$account_type;
	  $insertData['payment']  	    = 		$total;
	  $insertData['user_id']  	    = 		$user_id;
	  $insertData['created_date']  	    = 		get_est_time();
				  
				$date_with_time = show_date($insertData['created_date']);
				$arr_str = explode(',',$date_with_time);
				$arr = explode(" ",trim($arr_str[1]));
				
				for($i=0; $i<count($arr); $i++) {
				$mon = $arr[1];
				$year = $arr[2];
				}
				
				$created_date_forrmat = $mon.", ".$year;	  
	  $insertData['created_date_forrmat ']  	    = 		$created_date_forrmat;
	 

	  //Add Category
	  $this->affiliate_model->addReleasedPayments($insertData);	
	  
	  $updateKey['is_released']    = 1; 
	  $cond = array('affiliate_unreleased_payments.refid' => $refid);
	  $result =  $this->affiliate_model->updateUnReleasedPayments(TRUE,$updateKey,$cond);
	 // $condition=array('users.username'=>$refid)
	    $condition = array('users.user_name'=>$refid);
		$user_data = $this->user_model->getUsers($condition);					  
		//$user_data_result = $user_data->result();
		$user_data_row = $user_data->row();

		$userid = $user_data_row->id;
	    $resultbalence=$this->common_model->getTableData('user_balance',array('user_id'=>$userid));
	  
	  $usrblnce=$resultbalence->row();
	 
	  $updblnce=$usrblnce->amount +$total;
	  $this->common_model->updateTableData('user_balance',$usrblnce->id,array('amount'=>$updblnce));
	  
	  }					  

        $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));

		redirect_admin('affiliateSettings/releasePayment');
	   
	}//End of addGroup function
	
	
	/**
	 * delete affiliate sales
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function deleteReleasePayments()
	{	
		//Load model
		$this->load->model('affiliate_model');
		//Get bidProjects
		$release_payments1	=	$this->affiliate_model->getUnReleasePayments();
		//$this->outputData['projects']	=	$this->affiliate_model->getProjects();
		$releaseList  =   $this->input->post('releaseList');
		foreach($releaseList as $res)
		 { 
			//update the amount value
			$condition = array('affiliate_unreleased_payments.id'=>$res);
		 	$this->affiliate_model->deleteUnReleasedPayments(NULL,$condition);
		 }
       $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success',$this->lang->line('Update Succesfully Completed')));
	   redirect_admin('affiliateSettings/releasePayment');
	   
	}//End of addGroup function	
	
	
	
	/**
	 * search affiliates sales
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function searchReleasePayments()
	{	
		if($this->input->post('saleid'))
		  {
			  //Load model
			$this->load->model('affiliate_model');
			
			//Get bidProjects
			$this->outputData['affiliate_sales']	=	$this->affiliate_model->getAffiliateSales();
			
			$saleid  =   $this->input->post('saleid');
			
			$condition = array('sales.id'=>$saleid);
			$list =  $this->affiliate_model->getAffiliateSales($condition);
			$count = count($list);
			if($count > 0)
			  $this->outputData['affiliate_sales'] = $list;
			//Load View
			$this->load->view('admin/settings/viewSales',$this->outputData);
		  }
		 else
		  { 
	    	//Load View
		    $this->load->view('admin/settings/searchAffiliateSales',$this->outputData);
		  }	
	}//End of addGroup function	
	
	

}
//End  PaymentSettings Class

/* End of file paymentSettings.php */ 
/* Location: ./app/controllers/admin/paymentSettings.php */
?>
