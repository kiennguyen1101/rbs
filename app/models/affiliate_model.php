<?php
class Affiliate_model extends Model {

   /**
	* Constructor 
	*
	*/

	function Affiliate_model() {
		parent::Model();
	} //Controller End
	
	
	/**
	 * Add affiliate payment
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	function addAffiliatePayment($insertData=array()) {
		//echo $insertData;
		$this->db->insert('affiliate_payment',$insertData);
		
	}
	
	 // --------------------------------------------------------------------
	 
	/**
	 * Add affiliate questions
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	function addAffiliateQuestions($insertData=array()) {
		//echo $insertData;
		$this->db->insert('affiliate_questions',$insertData);
		
	}
	
	 // --------------------------------------------------------------------
	 
	 
	/**
	 * Add affiliate archive
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	function addAffiliateArchives($insertData=array()) {
		//echo $insertData;
		$this->db->insert('affiliate_archive',$insertData);
		
	}
	
	 // --------------------------------------------------------------------	 

	 
	/**
	 * Add affiliate clicks
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void 
	 */
	function addClickThroughs($insertData=array()) {
		//echo $insertData;
		$this->db->insert('clickthroughs',$insertData); 
		
	}
	
	 // --------------------------------------------------------------------	
	 
	 
	/**
	 * Add affiliate sales
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void 
	 */
	function addAffiliateSales($insertData=array()) {
		//echo $insertData;
		$this->db->insert('sales',$insertData); 
		
	}
	 	 
	 		
	/**
	 * Get Affiliate payment
	 *
	 * @access	private
	 * @param	array	an associative array of get values
	 * @return	void
	 */
	 function getAffiliatePayment($conditions=array())
	 {
	 	$affiliates = array();
	 	//Check For Conditions
	 	if(count($conditions)>0)		
	 	$this->db->where($conditions);
		
		$this->db->from('affiliate_payment');
		
	 	$this->db->select('affiliate_payment.id,
							affiliate_payment.buyer_affiliate_fee,
							affiliate_payment.buyer_min_amount,
							affiliate_payment.buyer_min_payout, 
							affiliate_payment.buyer_max_payout, 
							affiliate_payment.seller_affiliate_fee,
							affiliate_payment.seller_min_amount, 
							affiliate_payment.seller_min_payout, 
							affiliate_payment.seller_max_payout,
							affiliate_payment.buyer_project_fee,
							affiliate_payment.seller_project_fee'
							);
			
		$affiliate_result = $this->db->get();

		if($affiliate_result->num_rows()>0)
		{
			foreach($affiliate_result->result() as $k) {
			
				$affiliates['id'] 						=  $k->id;
				$affiliates['buyer_affiliate_fee'] 		=  $k->buyer_affiliate_fee;
				$affiliates['buyer_min_amount'] 		=  $k->buyer_min_amount;
				$affiliates['buyer_min_payout'] 		=  $k->buyer_min_payout ;
				$affiliates['buyer_max_payout'] 		=  $k->buyer_max_payout;
				$affiliates['seller_affiliate_fee'] =  $k->seller_affiliate_fee; 
				$affiliates['seller_min_amount'] 	=  $k->seller_min_amount; 
				$affiliates['seller_min_payout'] 	=  $k->seller_min_payout; 
				$affiliates['seller_max_payout'] 	=  $k->seller_max_payout; 
				$affiliates['buyer_project_fee'] 		=  $k->buyer_project_fee; 
				$affiliates['seller_project_fee'] 	=  $k->seller_project_fee;
				
				if($affiliate_result->num_rows() > 0 ) {
					$affiliates['num_rows'] 			=  $affiliate_result->num_rows();                				               
				}
			}
		}
		
		return $affiliates;
		 
	 }//End of addPopularSearch Function
	 
	// --------------------------------------------------------------------
	 
	 /**
	 * Update affiliate payment settings information.
	 *
	 * @access	private
	 * @param	array	update information related to affiliate payment
	 * @return	void
	 */
	 function updateAffiliateSettings($updateKey=array(),$updateData=array())
	 {
		$this->db->update('affiliate_payment',$updateData,$updateKey);
		
	 }//End of updatePaymentSettings Function
	 
	 
	/**
	 * Check Affiliate user email
	 *
	 * @access	private
	 * @param	array	an associative array of checking emails
	 * @return	void
	 */
	function checkUserEmail($conditions=array()) {
	
	 	$affiliate_email_result = array();
	 	//Check For Conditions
	 	if(count($conditions)>0)		
	 	$this->db->where($conditions);
		
		$this->db->from('users');
		
	 	$this->db->select('users.email');
			
		$arr_result = $this->db->get();

		if($arr_result->num_rows()>0)
		{
			foreach($arr_result->result() as $k) {
			
				$affiliate_email_result['email'] 		=  $k->email;
				
				if($arr_result->num_rows() > 0 ) {
					$affiliate_email_result['num_rows'] =  $arr_result->num_rows();                				               
				}
			}
		}
		
		return $affiliate_email_result;
		
	}
	
	 // --------------------------------------------------------------------
	 
	 
	/**
	 * Get Affiliate guest 
	 *
	 * @access	private
	 * @param	array	an associative array of checking emails
	 * @return	void
	 */
	function getAffiliateGuest($conditions=array()) {
	
	 	$affiliate_email_result = array();
	 	//Check For Conditions
	 	if(count($conditions)>0)		
	 	$this->db->where($conditions);
		
		$this->db->from('affiliate_questions');
		
	 	$this->db->select('affiliate_questions.id,
							affiliate_questions.email,
							affiliate_questions.subject,
							affiliate_questions.questions'
							);
			
		$arr_result = $this->db->get();
		
		$affiliate_email_result = $arr_result->result();
		
		return $affiliate_email_result;
		
	}
	
	 // --------------------------------------------------------------------
	 
	/**
	 * Get Userslist
	 *
	 * @access	private
	 * @param	nil
	 * @return	object	object with result set
	 */
	 function getAffiliateUsers($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->from('users');
			
	 	$this->db->select('users.id,users.user_name,
		users.name,
		users.role_id,
		users.country_symbol,
		users.message_notify,
		users.password,
		users.email,
		users.city,
		users.state,
		users.profile_desc,
		users.rate,
		users.project_notify,
		users.user_status,
		users.activation_key,
		users.created');
		 
		$result = $this->db->get();
		return $result;
		
	 }//End of getUsers Function
	 
	 // --------------------------------------------------------------------	
	 
	/**
	 * Get Affiliate Referels
	 *
	 * @access	private
	 * @param	nil
	 * @return	object	object with result set
	 */
	 function getAffiliateReferels($custom='')
	 {
		if($custom != '')		
			$this->db->where($custom,null,true);
			
		$this->db->from('users');
			
	 	$this->db->select('users.id,users.user_name,
		users.name,
		users.role_id,
		users.country_symbol,
		users.message_notify,
		users.password,
		users.email,
		users.city,
		users.state,
		users.profile_desc,
		users.rate,
		users.project_notify,
		users.user_status,
		users.activation_key,
		users.created');
		 
		$result = $this->db->get();
		return $result;
		
	 }//End of getUsers Function
	 
	 // --------------------------------------------------------------------	 
	 
	 
 	// --------------------------------------------------------------------
		
	/**
	 * Get clickthroughs
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getClickThroughs($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array())
	 {
	 	//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		
		//Check For like statement
	 	if(is_array($like) and count($like)>0)		
	 		$this->db->like($like);	
			
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}	
		//pr($orderby);
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
		
		
		$this->db->from('clickthroughs');
		
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('clickthroughs.id,		
			clickthroughs.refid,
			clickthroughs.created_date,
			clickthroughs.time,
			clickthroughs.browser,
			clickthroughs.ipaddress,
			clickthroughs.refferalurl,
			clickthroughs.buy
			');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of Click Throughs Function
	 
	 // --------------------------------------------------------------------
	 
	/**
	 * Update clickthroughs
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateClickThroughs($id=0,$updateData=array(),$conditions=array())
	 {
	 //pr($conditions);exit;
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->update('clickthroughs', $updateData);
		 
	 }//End of update clickthroughs Function
	 
	 // --------------------------------------------------------------------	
	 
	 
	/**
	 * delete clickthroughs
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteClickThroughs($id=0,$conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->delete('clickthroughs');
		 
	 }//End of clickthroughs Function
	 
	 // --------------------------------------------------------------------
	 
	 /**
	 * Update user ref id.
	 *
	 * @access	private
	 * @param	array	update information related to affiliate payment
	 * @return	void
	 */
	 function updateUserRefId($updateKey=array(),$updateData=array())
	 {
		$this->db->update('users',$updateData,$updateKey);
		
	 }//End of updatePaymentSettings Function
	 
 	// --------------------------------------------------------------------
		
	/**
	 * Get clickthroughs
	 *	
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getAffiliateSales($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array(),$custom='')
	 {
	 	//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		
		if($custom != '')		
			$this->db->where($custom,null,true);
		
		//Check For like statement
	 	if(is_array($like) and count($like)>0)		
	 		$this->db->like($like);	
			
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}	
		//pr($orderby);
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
		
		
		$this->db->from('sales');
		
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('sales.id,		
			sales.refid,
			sales.referral,
			sales.account_type,
			sales.created_date,
			sales.signup_date,
			sales.signup_date_format,
			sales.created_time,
			sales.browser,
			sales.ipaddress,
			sales.payment
			');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of Click Throughs Function
	 
	 
	/**
	 * Update affiliate sales
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateAffiliateSales($id=0,$updateData=array(),$conditions=array())
	 {
	 //pr($conditions);exit;
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->update('sales', $updateData);
		 
	 }//End of update clickthroughs Function
	 
	 // --------------------------------------------------------------------	
	 
	/**
	 * delete affiliate sales
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteAffiliateSales($id=0,$conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->delete('sales');
		 
	 }//End of clickthroughs Function
	 
	 // --------------------------------------------------------------------
	 
	 function getAffiliateQuestions($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array())
	 {
	 	//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		
		//Check For like statement
	 	if(is_array($like) and count($like)>0)		
	 		$this->db->like($like);	
			
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}	
		//pr($orderby);
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
		
		
		$this->db->from('affiliate_questions');
		
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('affiliate_questions.id,		
			affiliate_questions.email,
			affiliate_questions.subject,
			affiliate_questions.questions,
			');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of Click Throughs Function
	 
	/**
	 * delete affiliate questions
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteAffiliateGuest($id=0,$conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->delete('affiliate_questions');
		 
	 }//End of clickthroughs Function
	 
	 // --------------------------------------------------------------------
	 
	/**
	 * Update affiliate questions
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateAffiliateGuest($id=0,$updateData=array(),$conditions=array())
	 {
	 //pr($conditions);exit;
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->update('affiliate_questions', $updateData);
		 
	 }//End of update clickthroughs Function
	 
	 // --------------------------------------------------------------------	
	 
	/**
	 * Update Archived Questions
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateArchivedQuestions($id=0,$updateData=array(),$conditions=array())
	 {
	 //pr($conditions);exit;
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->update('affiliate_archive', $updateData);
		 
	 }//End of update clickthroughs Function
	 
	 function deleteArchivedQuestions($id=0,$conditions=array())
	 {
	 //pr($conditions);exit;
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->delete('affiliate_archive');
		 
	 }//End of update clickthroughs Function
	 
	 
	 // --------------------------------------------------------------------	
	 
	/**
	 * Get Userslist
	 *
	 * @access	private
	 * @param	nil
	 * @return	object	object with result set
	 */
	 function getSalesTotal($conditions=array(),$custom='')
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		
		if($custom != '')		
			$this->db->where($custom,null,true);
			
		$this->db->from('sales');
			
	 	$this->db->select('SUM(payment) as total');
		 
		$result = $this->db->get();
		return $result;
		
	 }//End of getUsers Function
	 
	 // --------------------------------------------------------------------	
	 
	/**
	 * Add affiliate welcome messages
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	function addAffiliateWelcomeMsg($insertData=array()) {
		//echo $insertData;
		$this->db->insert('affiliate_welcome_msg',$insertData);
		
	}
	
	 // --------------------------------------------------------------------
	 
	/**
	 * Get Affiliate guest 
	 *
	 * @access	private
	 * @param	array	an associative array of checking emails
	 * @return	void
	 */
	function getAffiliateWelcomeMsg($conditions=array()) {
	
	 	$affiliate_email_result = array();
	 	//Check For Conditions
	 	if(count($conditions)>0)		
	 	$this->db->where($conditions);
		
		$this->db->from('affiliate_welcome_msg');
		
	 	$this->db->select('affiliate_welcome_msg.id,
							affiliate_welcome_msg.refid,
							affiliate_welcome_msg.referel,
							affiliate_welcome_msg.welcome_msg,
							affiliate_welcome_msg.msg_status'
							);
			
		$arr_result = $this->db->get();
		
		return $arr_result;
		
	}
	
	 // --------------------------------------------------------------------
	 
	 /**
	 * Update affiliate welcome message.
	 *
	 * @access	private
	 * @param	array	update information related to affiliate welcome message
	 * @return	void
	 */
	 function updateAffiliateWelcomeMeg($updateKey=array(),$updateData=array())
	 {
		$this->db->update('affiliate_welcome_msg',$updateData,$updateKey);
		
	 }//End of updatePaymentSettings Function
	 
	/**
	 * Get Release Paymenst
	 *
	 * @access	private
	 * @param	nil
	 * @return	object	object with result set
	 */
	 function getReleasePayments($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->from('sales');
		
		$this->db->group_by('refid');
			
	 	$this->db->select('SUM(payment) as total, refid, account_type, id');
		 
		$result = $this->db->get();
		//pr($result->result());
		return $result;
		
	 }//End of getUsers Function
	 
	 // -------------------------------------------------------------------- 
	 
	 
	/**
	 * Get Release Paymenst
	 *
	 * @access	private
	 * @param	nil
	 * @return	object	object with result set
	 */
	 function getReleasedPayments($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->from('affiliate_released_payments');
		
		$this->db->group_by('refid');
			
	 	$this->db->select('SUM(payment) as total, refid, account_type');
		 
		$result = $this->db->get();
		return $result;
		
	 }//End of getUsers Function
	 
	 // --------------------------------------------------------------------  
	 
	/**
	 * Add affiliate released payments
	 *
	 * @access	private
	 * @param	array	an associative array of insert values   
	 * @return	void
	 */
	function addReleasedPayments($insertData=array()) {
		//echo $insertData;
		$this->db->insert('affiliate_released_payments',$insertData);
		
	}
	
	 // --------------------------------------------------------------------
	 
	/**
	 * Add affiliate un released payments
	 *
	 * @access	private
	 * @param	array	an associative array of insert values   
	 * @return	void
	 */
	function addUnReleasedPayments($insertData=array()) {
		//echo $insertData;
		$this->db->insert('affiliate_unreleased_payments',$insertData);
		
	}
	
	 // --------------------------------------------------------------------
	 
	/**
	 * Get Release Paymenst
	 *
	 * @access	private
	 * @param	nil
	 * @return	object	object with result set
	 */
	 function getUnReleasePayments($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->from('affiliate_unreleased_payments');
		
		$this->db->group_by('refid');
			
	 	$this->db->select('SUM(payment) as total, refid, account_type,id');
		 
		$result = $this->db->get();
		//pr($result->result() );
		return $result;
		
	 }//End of getUsers Function
	 
	 // --------------------------------------------------------------------  
	 
	 
	/**
	 * delete unreleased payments
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteUnReleasedPayments($id=0,$conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->delete('affiliate_unreleased_payments');
		 
	 }//End of clickthroughs Function
	 
	 // --------------------------------------------------------------------
	 
	 
	 	 /**
	 * Update affiliate payment settings information.
	 *
	 * @access	private
	 * @param	array	update information related to affiliate payment
	 * @return	void
	 */
	 function updateUnReleasedPayments($id=0,$updateData=array(),$conditions=array())
	 {
	 //pr($conditions);exit;
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('refid', $id);
	 	 $this->db->update('affiliate_unreleased_payments', $updateData);
		 
	 }//End of update clickthroughs Function
	 
	/**
	 * Get Affiliate Archives
	 *
	 * @access	private
	 * @param	nil
	 * @return	object	object with result set
	 */
	 function getAffiliateArchives($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array())
	 {
	 	//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		
		//Check For like statement
	 	if(is_array($like) and count($like)>0)		
	 		$this->db->like($like);	
			
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}	
		//pr($orderby);
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
		
		
		$this->db->from('affiliate_archive');
		
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('affiliate_archive.id,		
			affiliate_archive.email,
			affiliate_archive.subject,
			affiliate_archive.questions,
			affiliate_archive.answer,
			');
			
		$result = $this->db->get();
		//pr($result->result());exit;
		return $result;
		
	 }//End of Click Throughs Function
	 
	 	 
	//End of addAffiliatePayment Function
	}
	
?>
