<?php 
/**
 * Reverse bidding system Buyer Class
 *
 * Certificate related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	JobList 
 * @author		Cogzidel Dev Team
 * @version		Version 1.6
 * @created		April 22  2010
 * @created By  Saradha.P 
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
class Certificate extends Controller {
	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
	
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Certificate()
	{
	   parent::Controller();
	   
	   	//Get Config Details From Db
		$this->config->db_config_fetch();

	   //Manage site Status 
		if($this->config->item('site_status') == 1)
		redirect('offline');
	   
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		$this->load->model('user_model');
		$this->load->model('skills_model');
		$this->load->model('page_model');
	    $this->load->model('email_model');
		$this->load->model('package_model');
	    $this->load->model('certificate_model');
		$this->load->model('transaction_model');
		
		//Page Title and Meta Tags
		$this->outputData 			= $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		
		//Get Latest Projects
		$limit_latest = $this->config->item('latest_projects_limit');
		$limit3 = array($limit_latest);
		$this->outputData['latestProjects']	= $this->skills_model->getLatestProjects($limit3);
		
		//Get Footer content
		$conditions = array('page.is_active'=> 1);
		$this->outputData['pages']	=	$this->page_model->getPages($conditions);	
		
		
		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		$this->lang->load('enduser/certificateMember', $this->config->item('language_code'));
		
		//Load helpers
		$this->load->helper('users');
		$this->load->helper('file');

	} //Controller End 
	// --------------------------------------------------------------------
	
/**
	 * view the certified details by Members
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function viewcontent()
	{	
	
	
		$certificate_programmer=array();
		$certificate_buyer=array();
		
		//$condition=array('subscriptionuser.username'=>$this->loggedInUser->id);
     $userlists= $this->certificate_model->getCertificateUser();
		
	if($userlists->num_rows()>0)
	{
		// get the certified member  validity
				foreach($userlists->result() as $certificate)
				{
					$user_id=$certificate->username;
					$id=$certificate->id;
					$condition=array('subscriptionuser.flag'=>1,'subscriptionuser.id'=>$id);
					$userlists= $this->certificate_model->getCertificateUser($condition);
			         
					$validdate=$userlists->row();
		            $end_date=$validdate->valid; 
		            $created_date=$validdate->created;
		            $valid_date=date('d/m/Y',$created_date);
		
		            $next=$created_date+($end_date * 24 * 60 * 60);
		            $next_day= date('d/m/Y', $next) ."\n";
					if(time()<=$next)
					{
						$condition2 = array('users.id' => $user_id);
					    $user_role= $this->user_model->getUsers($condition2);
						foreach($user_role->result() as $user_roleid)
						{
							$roleid=$user_roleid->role_id;
								if($roleid==1)
								{
									$condition2 = array('users.id' => $user_id);
									$certificate_buyer[]= $this->user_model->getUsers($condition2);
								}
								else
								{
									$condition2 = array('users.id' => $user_id);
									$certificate_programmer[] = $this->user_model->getUsers($condition2);
								}
						}
				 }
			}
		}  
		$this->outputData['certificatebuyer'] =$certificate_buyer;
		$this->outputData['certificateprogrammer']=$certificate_programmer;
		
		$this->load->view('certificate/viewcertificate',$this->outputData);
		  
	}//Function Viewcontent End
    //--------------------------------------------------------------------------------------	
	
/**
	 * view the all packages  by buyer
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 	
	function viewallpackage()
	 {
	 
		//Get the Package details.
		$condition=array('packages.end_date >='=>time());
		
		$this->outputData['packagesList'] = $this->package_model->getPackages($condition);
		
		$this->load->view('certificate/viewallpackage',$this->outputData);
		
		}//Function viewallpackage End
 //--------------------------------------------------------------------------------------	
 
 /**
	 *Buy the new package from  buyer
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
		
	function addpackage()
	{
	  //Check the User Login or Not
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be login access to this page')));
		    redirect('info');
		  }
		  
		// User can Buy the Package
	    if($this->input->post('selectpackage'))
			{
				//get Package id
				$package_id=$this->input->post('selectpackage');
				foreach($package_id as $packageid)
				{
				$condition=array('packages.id'=>$packageid);
				}
				// Get Package details.
				$package_list=$this->package_model->getPackages($condition);
				//$package_list=$this->outputData['packages'];
				if($package_list->num_rows()>0)
				{
					$packages=$package_list->row();
					//pr($packages);
					$id=$packages->id;
					$totaldays=$packages->total_days;
					$amount=$packages->amount;
					$enddate=$packages->end_date;
				}
			
				$this->loggedInUser		= $this->common_model->getLoggedInUser();
				$login_user=$this->loggedInUser;
				// $user_id= $login_user->id;
					if(isset($package_list) and $package_list->num_rows()>0)
					{
						foreach($package_list->result() as $packagesLists)
						{
						  $id=$packagesLists->id;
						}
					}
			
					//check for already user buy the package or not.
					$condition=array('subscriptionuser.username'=>$this->loggedInUser->id);
					$userlists= $this->certificate_model->getCertificateUser($condition);
					
					if($userlists->num_rows()>0)
					{
					foreach($userlists->result() as $certificate)
					 {
					$user_id=$certificate->username;
					$id=$certificate->id;
					$condition=array('subscriptionuser.flag'=>1,'subscriptionuser.id'=>$id);
					$userlists= $this->certificate_model->getCertificateUser($condition);
					// get the validity
					$validdate=$userlists->row();
					$end_date=$validdate->valid; 
					$created_date=$validdate->created;
					$valid_date=date('d/m/Y',$created_date);
					
					$next=$created_date+($end_date * 24 * 60 * 60);
					$next_day= date('d/m/Y', $next) ."\n";
					
						if(time()<=$next)
						{
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Already You have Package')));
						redirect('info');
						}
			        }
			       } 
			
					$insertData=array();
					$insertData['username']=$login_user->id;
					$insertData['package_id']=$id;
					$insertData['valid']=$totaldays;
					$insertData['amount']=$amount;
					$insertData['flag']=1;
					$insertData['created']=get_est_time();
					$insertData['updated_date']=get_est_time();
			
					//Check user balance
					$condition_balance 		 = array('user_balance.user_id'=>$this->loggedInUser->id);
					$results 	 			 = $this->transaction_model->getBalance($condition_balance);
					
					if($results->num_rows()>0)
					{
						//get balance detail
						$rowBalance = $results->row();
						$this->outputData['userAvailableBalance'] = $rowBalance->amount;
					    $withdrawvalue=$rowBalance->amount-$amount;
			        }
					
				    
						if($rowBalance->amount == 0)
						{
							$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('your not having sufficient balance')));
						redirect('info');
						}
						else if( $withdrawvalue < 0 )
						{
							$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('your not having sufficient balance')));
						redirect('info');
						}
			       else
						{
						//Check User Balance
						//Update Amount	
						$updateKey 			  = array('user_balance.user_id'=>$this->loggedInUser->id);	
						$updateData 		  = array();
						$updateData['amount'] = $rowBalance->amount - $amount;
						$results 			  = $this->transaction_model->updateBalance($updateKey,$updateData);
						
						//Insert transaction for post projects
						$insertTransaction = array(); 
						$insertTransaction['creator_id']  = $this->loggedInUser->id;
						$insertTransaction['type'] 		 = $this->lang->line('Package Fee');
						$insertTransaction['amount'] 	 = $amount;
						$insertTransaction['transaction_time'] 	 	 = get_est_time();
						$insertTransaction['status'] 	 = 'Completed'; //Can Be success,failed,pending
						$insertTransaction['description'] = $this->lang->line('Package Fee');
						$insertTransaction['package_id'] =$packagesLists->id;
					
							if($this->loggedInUser->role_id == '1')
							{
								$insertTransaction['buyer_id']   = $this->loggedInUser->id;
								$insertTransaction['user_type']  = $this->lang->line('Package Fee');
							}
							if($this->loggedInUser->role_id == '2')
							{
								$insertTransaction['provider_id'] = $this->loggedInUser->id;
								$insertTransaction['user_type']   = $this->lang->line('Package Fee');
							}
						$this->load->model('transaction_model');
						$this->transaction_model->addTransaction($insertTransaction);	
						$this->certificate_model->createPackageUser($insertData);
						
						$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('Your Package has been Posted Successfully')));
						redirect('info'); 
						}			
			}
				else
				{
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Please Select the Package')));
							redirect('info'); 
				}
		
			$this->load->view('certificate/viewallpackage',$this->outputData);
	  }//Function addpackage End
 //--------------------------------------------------------------------------------------	
	  		
}//End  certified Class
/* End of file certified.php */ 
/* Location: ./app/controllers/certified.php */	
?>