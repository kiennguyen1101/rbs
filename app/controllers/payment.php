<?php 
/**
 * Reverse bidding system payment Class
 *
 * Payment related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Project 
 * @author		Cogzidel Dev Team
 * @version		Version 1.0
 * @created		December 31 2008
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
class Payment extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	    
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Payment()
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
		$this->load->model('payment_model');
		$this->load->model('settings_model');
		
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		
		//Get payment settings for check minimum balance from settings table
			
		$paymentSettings = $this->settings_model->getSiteSettings();
		$this->outputData['paymentSettings']	= $paymentSettings;	
		$this->outputData['PAYMENT_SETTINGS']       = $paymentSettings['PAYMENT_SETTINGS'];
		
		//Get Latest Projects
		$this->outputData['latestProjects']	= $this->skills_model->getProjects();
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
	
		$this->lang->load('enduser/depositMoney', $this->config->item('language_code'));
		//Load the Library for google check out
		//$this->load->library('checkout/googlecart');	
		//$this->load->library('checkout/googleitem');
		$this->load->library('session');
	} //Controller End 
	// --------------------------------------------------------------------
	
	/**
	 * Loads Home page of the site.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */	
	function index()
	{		

	} 
	  //Function index End
	// --------------------------------------------------------------------
	
    function googlecheckoutresponsehandler()
	{
		  //chdir("..");
		  require_once('library/googleresponse.php');
		  require_once('library/googlemerchantcalculations.php');
		  require_once('library/googleresult.php');
		  require_once('library/googlerequest.php');
			
		  $this->load->model('payment_model');
		 $paymentGateways_google = $this->payment_model->getGooglecheckoutsettings();	
		
		  $base_url=$base_url();
		  define('RESPONSE_HANDLER_ERROR_LOG_FILE', $base_url.'googlelog/googleerror.log');
		  define('RESPONSE_HANDLER_LOG_FILE', $base_url.'googlelog/googlemessage.log');
		
		 /*
		  $merchant_id = "";  // Your Merchant ID
		  $merchant_key = "";  // Your Merchant Key
		  $server_type = "sandbox";  // change this to go live
		   */
			$merchant_id = $paymentGateways_google['googlecheckout']['merchant_id'];  // Your Merchant ID
				$merchant_key = $paymentGateways_google['googlecheckout']['merchant_key']; // Your Merchant Key
				$server_type = $paymentGateways_google['googlecheckout']['server_type']; 
		   
		  $currency = 'USD';  // set to GBP if in the UK
		
		  $Gresponse = new GoogleResponse($merchant_id, $merchant_key);
		
		  $Grequest = new GoogleRequest($merchant_id, $merchant_key, $server_type, $currency);
		
		  //Setup the log file
		  $Gresponse->SetLogFiles(RESPONSE_HANDLER_ERROR_LOG_FILE, 
												RESPONSE_HANDLER_LOG_FILE, L_ALL);
		
		  // Retrieve the XML sent in the HTTP POST request to the ResponseHandler
		  $xml_response = isset($HTTP_RAW_POST_DATA)?
							$HTTP_RAW_POST_DATA:file_get_contents("php://input");
		  if (get_magic_quotes_gpc()) {
			$xml_response = stripslashes($xml_response);
		  }
		  list($root, $data) = $Gresponse->GetParsedXML($xml_response);
		  $Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);
		
		  $status = $Gresponse->HttpAuthentication();
		  if(! $status) {
			die('authentication failed');
		  }
		
		  /* Commands to send the various order processing APIs
		   * Send charge order : $Grequest->SendChargeOrder($data[$root]
		   *    ['google-order-number']['VALUE'], <amount>);
		   * Send process order : $Grequest->SendProcessOrder($data[$root]
		   *    ['google-order-number']['VALUE']);
		   * Send deliver order: $Grequest->SendDeliverOrder($data[$root]
		   *    ['google-order-number']['VALUE'], <carrier>, <tracking-number>,
		   *    <send_mail>);
		   * Send archive order: $Grequest->SendArchiveOrder($data[$root]
		   *    ['google-order-number']['VALUE']);
		   *
		   */
		
		  switch ($root) {
			case "request-received": {
			  break;
			}
			case "error": {
			  break;
			}
			case "diagnosis": {
			  break;
			}
			case "checkout-redirect": {
			  break;
			}
			case "merchant-calculation-callback": {
			  // Create the results and send it
			  $merchant_calc = new GoogleMerchantCalculations($currency);
		
			  // Loop through the list of address ids from the callback
			  $addresses = get_arr_result($data[$root]['calculate']['addresses']['anonymous-address']);
			  foreach($addresses as $curr_address) {
				$curr_id = $curr_address['id'];
				$country = $curr_address['country-code']['VALUE'];
				$city = $curr_address['city']['VALUE'];
				$region = $curr_address['region']['VALUE'];
				$postal_code = $curr_address['postal-code']['VALUE'];
		
				// Loop through each shipping method if merchant-calculated shipping
				// support is to be provided
				if(isset($data[$root]['calculate']['shipping'])) {
				  $shipping = get_arr_result($data[$root]['calculate']['shipping']['method']);
				  foreach($shipping as $curr_ship) {
					$name = $curr_ship['name'];
					//Compute the price for this shipping method and address id
					$price = 12; // Modify this to get the actual price
					$shippable = "true"; // Modify this as required
					$merchant_result = new GoogleResult($curr_id);
					$merchant_result->SetShippingDetails($name, $price, $shippable);
		
					if($data[$root]['calculate']['tax']['VALUE'] == "true") {
					  //Compute tax for this address id and shipping type
					  $amount = 15; // Modify this to the actual tax value
					  $merchant_result->SetTaxDetails($amount);
					}
		
					if(isset($data[$root]['calculate']['merchant-code-strings']
						['merchant-code-string'])) {
					  $codes = get_arr_result($data[$root]['calculate']['merchant-code-strings']
						  ['merchant-code-string']);
					  foreach($codes as $curr_code) {
						//Update this data as required to set whether the coupon is valid, the code and the amount
						$coupons = new GoogleCoupons("true", $curr_code['code'], 5, "test2");
						$merchant_result->AddCoupons($coupons);
					  }
					 }
					 $merchant_calc->AddResult($merchant_result);
				  }
				} else {
				  $merchant_result = new GoogleResult($curr_id);
				  if($data[$root]['calculate']['tax']['VALUE'] == "true") {
					//Compute tax for this address id and shipping type
					$amount = 15; // Modify this to the actual tax value
					$merchant_result->SetTaxDetails($amount);
				  }
				  $codes = get_arr_result($data[$root]['calculate']['merchant-code-strings']
					  ['merchant-code-string']);
				  foreach($codes as $curr_code) {
					//Update this data as required to set whether the coupon is valid, the code and the amount
					$coupons = new GoogleCoupons("true", $curr_code['code'], 5, "test2");
					$merchant_result->AddCoupons($coupons);
				  }
				  $merchant_calc->AddResult($merchant_result);
				}
			  }
			  $Gresponse->ProcessMerchantCalculations($merchant_calc);
			  break;
			}
			case "new-order-notification": {
			  $Gresponse->SendAck();
			  break;
			}
			case "order-state-change-notification": {
			  $Gresponse->SendAck();
			  $new_financial_state = $data[$root]['new-financial-order-state']['VALUE'];
			  $new_fulfillment_order = $data[$root]['new-fulfillment-order-state']['VALUE'];
		
				$test=array('status'=>$new_financial_state);
				$this->common_model->insert('test',$test);
				
			  switch($new_financial_state) {
				case 'REVIEWING': {
				  break;
				}
				case 'CHARGEABLE': {
				  //$Grequest->SendProcessOrder($data[$root]['google-order-number']['VALUE']);
				  //$Grequest->SendChargeOrder($data[$root]['google-order-number']['VALUE'],'');
				  break;
				}
				case 'CHARGING': {
				  break;
				}
				case 'CHARGED': {
				  break;
				}
				case 'PAYMENT_DECLINED': {
				  break;
				}
				case 'CANCELLED': {
				  break;
				}
				case 'CANCELLED_BY_GOOGLE': {
				  //$Grequest->SendBuyerMessage($data[$root]['google-order-number']['VALUE'],
				  //    "Sorry, your order is cancelled by Google", true);
				  break;
				}
				default:
				  break;
			  }
		
			  switch($new_fulfillment_order) {
				case 'NEW': {
				  break;
				}
				case 'PROCESSING': {
				  break;
				}
				case 'DELIVERED': {
				  break;
				}
				case 'WILL_NOT_DELIVER': {
				  break;
				}
				default:
				  break;
			  }
			  break;
			}
			case "charge-amount-notification": {
			  //$Grequest->SendDeliverOrder($data[$root]['google-order-number']['VALUE'],
			  //    <carrier>, <tracking-number>, <send-email>);
			  //$Grequest->SendArchiveOrder($data[$root]['google-order-number']['VALUE'] );
			  $Gresponse->SendAck();
			  break;
			}
			case "chargeback-amount-notification": {
			  $Gresponse->SendAck();
			  break;
			}
			case "refund-amount-notification": {
			  $Gresponse->SendAck();
			  break;
			}
			case "risk-information-notification": {
			  $Gresponse->SendAck();
			  break;
			}
			default:
			  $Gresponse->SendBadRequestStatus("Invalid or not supported Message");
			  break;
		  }
		  /* In case the XML API contains multiple open tags
			 with the same value, then invoke this function and
			 perform a foreach on the resultant array.
			 This takes care of cases when there is only one unique tag
			 or multiple tags.
			 Examples of this are "anonymous-address", "merchant-code-string"
			 from the merchant-calculations-callback API
		  */
			}
			
			
			function get_arr_result($child_node) {
			$result = array();
			if(isset($child_node)) {
			  if(is_associative_array($child_node)) {
				$result[] = $child_node;
			  }
			  else {
				foreach($child_node as $curr_node){
				  $result[] = $curr_node;
				}
			  }
			}
			return $result;
  }
	
	
	/* Returns true if a given variable represents an associative array */
  function is_associative_array( $var ) {
    return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
	/****************************************/
	}	
	
	function googlecheckout()
		{		
			if($this->input->post('googlesubmit'))
			{
				
				$this->load->model('payment_model');
				$paymentGateways_google = $this->payment_model->getPaymentSettings();
		 
				$payment_amount  =  $this->input->post('item_price_1');
				$tr_id			 =  $this->input->post('trans');
				
				$this->session->set_userdata('tr_id',$tr_id);
				 $merchant_id = $paymentGateways_google['gch']['mail_id'];  // Your Merchant ID
				 $merchant_key = $paymentGateways_google['gch']['url']; // Your Merchant Key
				 if($paymentGateways_google['gch']['url_status']==0)
				 {
				  $server_type ="sandbox";
				 }
				 else
				 {
				  $server_type ="Live";
				 }
				  $currency = "USD";
				  $cart = new GoogleCart($merchant_id, $merchant_key, $server_type, $currency);
				  $total_count = 1;
	//  Check this URL for more info about the two types of digital Delivery
	//  http://code.google.com/apis/checkout/developer/Google_Checkout_Digital_Delivery.html
	
	  //Get the language details
	
	  
		$item_1 = new GoogleItem($this->config->item('site_name').'Account Deposit',      // Item name
								   ' Account Deposit ' , // Item description
								   '1', // Quantity
								   $payment_amount); // Unit price
			
	    
	 // $item_1->SetURLDigitalContent('http://example.com/download.php?id=15','S/N: 123.123123-3213', "Download Item1");
	  $cart->AddItem($item_1);
	    $cart->SetMerchantPrivateData(new MerchantPrivateData(array("transaction-id" => $tr_id))); 
	 // Specify "Return to xyz" link
	  $cart->SetContinueShoppingUrl(site_url('payment/checkoutSuccess'));
	  
	  // Request buyer's phone number
	  $cart->SetRequestBuyerPhone(true);
	
	// Add analytics data to the cart if its setted
	  if(isset($_POST['analyticsdata']) && !empty($_POST['analyticsdata'])){
		$cart->SetAnalyticsData($_POST['analyticsdata']);
	  }
	// This will do a server-2-server cart post and send an HTTP 302 redirect status
	// This is the best way to do it if implementing digital delivery
	// More info http://code.google.com/apis/checkout/developer/index.html#alternate_technique
	  list($status, $error) = $cart->CheckoutServer2Server();
	 
	  // if i reach this point, something was wrong
	  echo "An error had ocurred: <br />HTTP Status: " . $status. ":";
	  echo "<br />Error message:<br />";
	  echo $error;
//
	  }
	
	}
	
	
	function checkoutSuccess($transactionid = '', $payment_amount = ''){
	
	
		 $transactionid = base64_decode($transactionid);
		
		 $payment_amount = base64_decode($payment_amount); 

		//Load Payment
		$this->lang->load('enduser/payment', $this->config->item('language_code'));
		
		$paymentGateways = $this->payment_model->getPaymentSettings();
		
		$outputData['payment_result']=0; //Check the payment status
        if($this->session->userdata('tr_id'))
			{
		$transactionid=$this->session->userdata('tr_id');
		
		if($transactionid != '')
		{	
		//Get Transaction Information
		$condition 		 = array('transactions.id'=>$transactionid);
		
		$transactions 	 = $this->transaction_model->getTransactions($condition);
		
				
		$transactionInfo = $transactions->row();
		
		//Check User Balance
		
		$condition_balance 		 = array('user_balance.user_id'=>$transactionInfo->creator_id);
		
		$results 	 			 = $this->transaction_model->getBalance($condition_balance);
		
		//If Record already exists
		
		if($results->num_rows()>0)
		{
		//get balance detail
		
		$rowBalance = $results->row();
		
		//Update Amount	
		
		$updateKey 			  = array('user_balance.user_id'=>$transactionInfo->creator_id);	
		
		$updateData 		  = array();
		
		$paypal_commission = $paymentGateways['gch']['commission'];
		
	    $pm = $transactionInfo->amount;
		
	    $amt = $payment_amount - ($pm * ($paypal_commission/100));
		
		$updateData['amount'] = $rowBalance->amount + $amt;
		
		if($transactionInfo->update_flag != 1){
			
				//$results = $this->transaction_model->updateBalance($updateKey,$updateData);
			}
		}
		//Update Data For Tra       
		
		$updateData = array();
		
		$updateData['status'] = 'success';
		
		$updateData['update_flag'] = 1;
		
		//Update Key
		
		$updateKey 		= array('id'=>$transactionid);
		if($transactionInfo->update_flag != 1){
		
		//$this->transaction_model->updateTransaction($updateKey,$updateData);
		}
		
		$outputData['payment_result']=1;		//Holds the output data for each view
		
		$this->load->model('email_model');
		
		$conditionUserMail = array('email_templates.type'=>'transaction');
		
		$result            = $this->email_model->getEmailSettings($conditionUserMail);
		
		$rowUserMailConent = $result->row();
		
		$splVars = array("!site_name" => $this->config->item('site_title'),"!username" => $this->loggedInUser->user_name,"!siteurl" => site_url(),"!amount"=>$amt,"!type"=>'Deposit',"!others"=>'',"!others1"=>'', "!contact_url" => site_url('contact'));

		$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Transaction is completed successfully')));							

		}

		if($transactionid == '')

		     $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', 'Failed'));

		redirect('info');
	
	}}
	
		function mbipn()
		{
			$transactionid = $_POST['transaction_id'];
			$status=$_POST['status'];
			$payment_amount = $_POST['amount'];
			
			//Load Payment
			$this->lang->load('enduser/payment', $this->config->item('language_code'));
			
			$paymentGateways = $this->payment_model->getPaymentSettings();
			
			$outputData['payment_result']=0; //Check the payment status
			
			$this->common_model->updateTableData('moneybooker',NULL,array('status' => $_POST['status']),array('transaction_id'=>$transactionid));
			
		       if( $status=='2')
				{
				
				 if($transactionid != '')
					{	
					//Get Transaction Information
					$condition 		 = array('transactions.id'=>$transactionid);
					
					$transactions 	 = $this->transaction_model->getTransactions($condition);
					
					
					$transactionInfo = $transactions->row();
					
					//Check User Balance
					
					$condition_balance 		 = array('user_balance.user_id'=>$transactionInfo->creator_id);
					
					$results 	 			 = $this->transaction_model->getBalance($condition_balance);
					
					//If Record already exists
					
					if($results->num_rows()>0)
						{
						//get balance detail
						
						$rowBalance = $results->row();
						
						//Update Amount	
						
						$updateKey 			  = array('user_balance.user_id'=>$transactionInfo->creator_id);	
						
						$updateData 		  = array();
						
						$mb_commission = $paymentGateways['mb']['commission'];
						
						$pm = $transactionInfo->amount;
						
						$amt = $payment_amount - ($pm *($mb_commission/100));
						
						$updateData['amount'] = $rowBalance->amount + $amt;
						
						 if($transactionInfo->update_flag != 1)
							{
							
								$results 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
							}
						}
					//Update Data For Tra       
					
					$updateData = array();
					
					$updateData['status'] = 'success';
					
					$updateData['update_flag'] = 1;
					
					//Update Key
					
					    $updateKey 		= array('id'=>$transactionid);
						if($transactionInfo->update_flag != 1){
						
						$this->transaction_model->updateTransaction($updateKey,$updateData);
						}
					
						$outputData['payment_result']=1;		//Holds the output data for each view
						
						$this->load->model('email_model');
						
						$conditionUserMail = array('email_templates.type'=>'transaction');
						
						$result            = $this->email_model->getEmailSettings($conditionUserMail);
						
						$rowUserMailConent = $result->row();
						
						$splVars = array("!site_name" => $this->config->item('site_title'),"!username" => $this->loggedInUser->user_name,"!siteurl" => site_url(),"!amount"=>$amt,"!type"=>'Deposit',"!others"=>'',"!others1"=>'', "!contact_url" => site_url('contact'));
						$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
						$mailContent = strtr($rowUserMailConent->mail_body, $splVars);		
						$toEmail     = $this->loggedInUser->email;
						$fromEmail   = $this->config->item('site_admin_mail');
						$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
					}	
				}
				elseif($status=='-2')
				{
					$updateData = array();
					$updateData['status'] = 'failed';
					
					//Update Key
					$updateKey 		= array('id'=>$transactionId);
					$this->transaction_model->updateTransaction($updateKey,$updateData);
				
				}
		}

		function mbSuccess()
		{
		
		$tr_row=$this->common_model->getTableData('moneybooker',array('transaction_id'=>$this->session->userdata('transaction')));  
		
		
			if($tr_row->num_rows() > 0)
			{
				$tr_id=$tr_row->row();
					if($tr_id->status=='2')
					{
						 $this->session->set_flashdata('flash_message', $this->common_model->flash_message('success','Deposit was successful!'));
					
					}	
					if($tr_id->status=='0')
					{
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','Payment is Pending'));
					}
					if($tr_id->status=='-1')
					{
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','Payment is Cancelled'));
					}
					if($tr_id->status=='-2')
					{
					
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','Payment is Failed'));
					}
					if($tr_id->status=='-3')
					{
					
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error','Payment is Chargeback'));
					}
				
			}
		
		$this->session->unset_userdata('transaction');
		redirect('info');	
		
		}

	
	/**
	 * Handles the paypal instant payment notification
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */	
	function paypalIpn()
	{
	
		//Load Payment
		$this->lang->load('enduser/payment', $this->config->item('language_code'));
		$header='';
		$paymentGateways = $this->payment_model->getPaymentSettings();
		
		//$paymentGateways = $this->payment_model->getPaymentSettings();
		
		$x=ltrim($paymentGateways['paypal']['url'],'https://'); 
		$pos= strpos($x,'/');
		$paypal_url= substr($x,0,$pos);
		
		$outputData['payment_result']=0; //Check the payment status
		if($this->input->post('receiver_email',true)===false)
		{
			exit($this->lang->line('Error in payment notification'));
		}
		if($this->input->post('receiver_email',true)!= $paymentGateways['paypal']['mail_id'])
		{
				$method = 'paypal';
				mail($this->config->item('site_admin_mail'),'Paypal error in'.$this->config->item('site_title'),'Paypal error in '.$this->config->item('site_title').', address that the money was sent to does not match the settings');
				exit($this->lang->line('Error in payment notification'));
		}
		
		$req = 'cmd=_notify-validate';
		foreach ($_POST as $key => $value)
		{
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		// post back to PayPal system to validate
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		//$fp = fsockopen ($paymentGateways['paypal']['url'], 80, $errno, $errstr, 30);
			
		$fp = fsockopen ($paypal_url, 80, $errno, $errstr, 30);
		
		
		//Assign posted variables to local variables
		$item_name 			= $this->input->post('item_name',true);
		$item_number 		= $this->input->post('item_number',true);
		$payment_status 	= $this->input->post('payment_status',true);
		$payment_amount 	= $this->input->post('mc_gross',true);
		$payment_currency 	= $this->input->post('mc_currency',true);
		$txn_id 			= $this->input->post('txn_id',true);
		$receiver_email		= $this->input->post('receiver_email',true);
		$payer_email 		= $this->input->post('payer_email',true);
		$custom 			= $this->input->post('custom',true);
		$custom_arr = explode("#",$custom ); 
		//$payment_status     = 'Completed';
		//Update Data
		$updateData = array();
		$updateData['status'] = 'failed';
		
		//Update Key
		$updateKey 		= array('id'=>$custom_arr[0]);
		$this->transaction_model->updateTransaction($updateKey,$updateData);
		
		
		
					// check the payment_status is Completed
					// check that txn_id has not been previously processed
					// check that receiver_email is your Primary PayPal email
					// check that payment_amount and payment_currency are correct
					// process payment
					// Send a custom email to the value of payer_email
					// Set variables needed for email.
					//$payment_status='Completed';
					if($payment_status=='Completed')
					{	
							//Get Transaction Information
							$condition 		 = array('transactions.id'=>$custom);
							$transactions 	 = $this->transaction_model->getTransactions($condition);
							$transactionInfo = $transactions->row();
					
							//Check User Balance
							$condition_balance 		 = array('user_balance.user_id'=>$transactionInfo->creator_id);
							$results 	 			 = $this->transaction_model->getBalance($condition_balance);
							//pr($results);
							//If Record already exists
							if($results->num_rows()>0)
							{
								//get balance detail
								$rowBalance = $results->row();
								
								//Update Amount	
								$updateKey 			  = array('user_balance.user_id'=>$transactionInfo->creator_id);	
								$updateData 		  = array();
								

								$paypal_commission = $paymentGateways['paypal']['commission'];
								$pm = $transactionInfo->amount;
								$amt = $payment_amount - ($pm * ($paypal_commission/100));
								$updateData['amount'] = $rowBalance->amount + $amt;
								$results 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
							}
									
							//Update Data For Tra       
							$updateData = array();
							$updateData['status'] = 'success';
							
							//Update Key
							$updateKey 		= array('id'=>$custom);
							$this->transaction_model->updateTransaction($updateKey,$updateData);
							$outputData['payment_result']=1;		//Holds the output data for each view
								
							
							
							
							//Send email to the user after registration
							$this->load->model('email_model');
							$conditionUserMail = array('email_templates.type'=>'transaction');
							$result            = $this->email_model->getEmailSettings($conditionUserMail);
								
							$rowUserMailConent = $result->row();
							
							//$user_name = $this->loggedInUser->user_name;
							$user_name = $custom_arr[1];
								
							$splVars = array("!site_name" => $this->config->item('site_title'),"!username" => $user_name,"!siteurl" => site_url(),"!amount"=>$payment_amount,"!type"=>'Deposit',"!others"=>'',"!others1"=>'', "!contact_url" => site_url('contact'));
							$mailSubject = strtr($rowUserMailConent->mail_subject, $splVars);
							$mailContent = strtr($rowUserMailConent->mail_body, $splVars);	
							//$toEmail     = $this->loggedInUser->email;
							$toEmail     = $custom_arr[2];
							$fromEmail   = $this->config->item('site_admin_mail');
							
							$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);
					}	//If End
				
				
		if (!$fp) {
		// HTTP ERROR
		} else {
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {} else if (strcmp ($res, "INVALID") == 0) {
					// log for manual investigation
				}//If Else End
			}//While End
		fclose ($fp);
		}///If Else End 
	} //Function paypalIpn End
	
	function paypalIpncheck()
	{
		$custom 			= $this->uri->segment(3);
		//Get Transaction Information
		$condition 		 = array('transactions.id'=>$custom);
		$transactions 	 = $this->transaction_model->getTransactions($condition);
		$transactionInfo = $transactions->row();

		//Check User Balance
		$condition_balance 		 = array('user_balance.user_id'=>$transactionInfo->creator_id);
		$results 	 			 = $this->transaction_model->getBalance($condition_balance);
		
		//If Record already exists
		if($results->num_rows()>0)
		{
			//get balance detail
			$rowBalance = $results->row();
			
			//Update Amount	
			$updateKey 			  = array('user_balance.user_id'=>$transactionInfo->creator_id);	
			$updateData 		  = array();
			
			$updateData['amount'] = $rowBalance->amount+$transactionInfo->amount;
			$results 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
		}
				
		//Update Data For Tra       
		$updateData = array();
		$updateData['status'] = 'success';
		//Update Key
		$updateKey 		= array('id'=>$custom);
		$this->transaction_model->updateTransaction($updateKey,$updateData);
	}
	
	
	function paymentSuccess()
     {
		$custom_arr = explode("#",$_POST['custom']);
		$transactionid = $custom_arr[0];
		
		$payment_amount = $_POST['mc_gross'];

		//Load Payment
		$this->lang->load('enduser/payment', $this->config->item('language_code'));
		
		$paymentGateways = $this->payment_model->getPaymentSettings();
		
		$outputData['payment_result']=0; //Check the payment status

		if($transactionid != '')
		{	
		//Get Transaction Information
		$condition 		 = array('transactions.id'=>$transactionid);
		
		$transactions 	 = $this->transaction_model->getTransactions($condition);
		
				
		$transactionInfo = $transactions->row();
		
		//Check User Balance
		
		$condition_balance 		 = array('user_balance.user_id'=>$transactionInfo->creator_id);
		
		$results 	 			 = $this->transaction_model->getBalance($condition_balance);
		
		//If Record already exists
		
		if($results->num_rows()>0)
		{
		//get balance detail
		
		$rowBalance = $results->row();
		
		//Update Amount	
		
		$updateKey 			  = array('user_balance.user_id'=>$transactionInfo->creator_id);	
		
		$updateData 		  = array();
		
		$paypal_commission = $paymentGateways['paypal']['commission'];
		
		$pm = $transactionInfo->amount;
		
		$amt = $payment_amount - ($pm * ($paypal_commission/100));
		
		$updateData['amount'] = $rowBalance->amount + $amt;
		}
		//Update Data For Tra       
		
		$updateData = array();
		
		$updateData['status'] = 'success';
		
		$updateData['update_flag'] = 1;
		
		//Update Key
		
		$updateKey 		= array('id'=>$transactionid);
		$outputData['payment_result']=1;		//Holds the output data for each view
		
		$this->load->model('email_model');
		
		$conditionUserMail = array('email_templates.type'=>'transaction');
		
		$result            = $this->email_model->getEmailSettings($conditionUserMail);
		
		$rowUserMailConent = $result->row();
		
		//$splVars = array("!site_name" => $this->config->item('site_title'),"!username" => $this->loggedInUser->user_name,"!siteurl" => site_url(),"!amount"=>$amt,"!type"=>'Deposit',"!others"=>'',"!others1"=>'', "!contact_url" => site_url('contact'));

		$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Transaction is completed successfully')));							

		}

		if($transactionid == '')

		     $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', 'Failed'));

		redirect('info');
	
	
	}
	
} //End  Deposit Class

/* End of file payment.php */ 
/* Location: ./app/controllers/payment.php */