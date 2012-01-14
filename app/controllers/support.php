<?php
/**
 * Reverse bidding system Buyer Class
 *
 * Buyer related functions are handled by this controller.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Buyer 
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
class Support extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
		
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Support()
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
		$this->load->model('faq_model');
		$this->load->model('contact_model');
		$this->load->model('email_model');
		
		
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		
	    //Get Footer content
		$this->outputData['pages']	= $this->common_model->getPages();	
		
		//load validation libraray
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
			
		//Get Latest Projects
		$limit_latest = $this->config->item('latest_projects_limit');
		$limit3 = array($limit_latest);
		$this->outputData['latestProjects']	= $this->skills_model->getLatestProjects($limit3);

		//language file
		$this->lang->load('enduser/common', $this->config->item('language_code'));
		$this->lang->load('enduser/rss', $this->config->item('language_code'));
       $this->lang->load('enduser/contact', $this->config->item('language_code'));
		
	}//Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads Contact page.
	 *
	 * @access	public
	 * @param	nil
	 * @return	void
	 */ 
	function index()
	{	
	
				
		//Loading the lang files
		$language_code = $this->config->item('language_code');
		$this->lang->load('enduser/common',$language_code);
		$this->lang->load('enduser/support',$language_code);
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		//pr($_POST);exit;
		
		//load model
		$this->load->model('common_model');
		
		$conditons=array('user_id'=>$this->loggedInUser->id);
		$likeconditons=array();
		if($this->input->post('setext')!='')
		{
			 $setext=$this->input->post('setext');
			 $secondition	=$this->input->post('secondition');
			
			$this->outputData['setext']				 =$setext;
			$this->outputData['secondition']		     =$secondition;
			
			if($secondition!='callid')
			{
				$likeconditons=array($secondition=>'%'.$setext.'%');
			}
			else
			{
				$conditons=array($secondition=>$setext);
			}	
		}
		elseif($this->uri->segment(3)!='' and $this->uri->segment(4)!='')
		{
			 
			 $setext=$this->uri->segment(3);
			 $secondition	=$this->uri->segment(4);
			 
			 $this->outputData['setext']				 =$setext;
			$this->outputData['secondition']		     =$secondition;
			
			if($secondition!='callid')
			{
				$likeconditons=array($secondition=>'%'.$setext.'%');
			}
			else
			{
				$conditons=array($secondition=>$setext);
			}	
		}		
		else
		{
			$setext='';
			$secondition='';
		}
		
		/*
		elseif($this->input->get('hesecondition') && $this->input->get('hesetext'))
		{
			$setext=$this->input->get('hesecondition');
			$secondition	=$this->input->get('hesetext');
					
			$this->outputData['setext']				 =$setext;
			$this->outputData['secondition']		     =$secondition;
			
			if($secondition!='callid')
			{
				$likeconditons=array($secondition=>'%'.$setext.'%');
			}
			else
			{
				$conditons=array($secondition=>$setext);
			}	
	
		}
		*/
		
		
		//Get Groups
		$support1	=	$this->common_model->getTableData('support',$conditons,NULL,$likeconditons);
				
				
		if($this->uri->segment(3)!='' and $this->uri->segment(4)!='')
		{		
		   $start =  $this->uri->segment(5,0);  
		 }
		 else
		 {
		 	$start =  $this->uri->segment(3,0);  
		 }  
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		//$page_rows =2;
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='desc';
		
				
		$support 	         = $this->common_model->getTableData('support',$conditons,NULL,$likeconditons,$limit,$order);   
		
		$this->outputData['support'] = $support;
		
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('support/index/'.$setext.'/'.$secondition);  
		$config['total_rows'] 	 = $support1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'support');	
		
		//Load View
	  // 	$this->load->view('support/main',$this->outputData);
		
		
		//Get Frequently Asked Questions
		$conditions = array('is_frequent'=> 'Y');
		$this->outputData['frequentFaqs']	=	$this->faq_model->getFaqs($conditions);	
		
		//Load View	
	    $this->load->view('support/main',$this->outputData);
	}//End of function
	
	
		function open()
	{	
	
				
		//Loading the lang files
		$language_code = $this->config->item('language_code');
		$this->lang->load('enduser/common',$language_code);
		$this->lang->load('enduser/support',$language_code);
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		//pr($_POST);exit;
		
		
		//load model
		$this->load->model('common_model');
		
		
		$opencodition=array('user_id'=>$this->loggedInUser->id,'status'=>'0');
		//Get Groups
		$support1	=	$this->common_model->getTableData('support',$opencodition);
		
		
				
		$start =  $this->uri->segment(4,0);  
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		//$page_rows =1;
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='desc';
		
		$support 	         = $this->common_model->getTableData('support',$opencodition,NULL,NULL,$limit,$order);   
		
		$this->outputData['support'] = $support;
		
		
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('support/open');  
		$config['total_rows'] 	 = $support1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'support');	
		
		//Load View
	  // 	$this->load->view('support/main',$this->outputData);
		
		
		//Get Frequently Asked Questions
		$conditions = array('is_frequent'=> 'Y');
		$this->outputData['frequentFaqs']	=	$this->faq_model->getFaqs($conditions);	
		
		//Load View	
	    $this->load->view('support/main',$this->outputData);
	}//End of function
	
	
	function close()
	{	
	
				
		//Loading the lang files
		$language_code = $this->config->item('language_code');
		$this->lang->load('enduser/common',$language_code);
		$this->lang->load('enduser/support',$language_code);
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
		//pr($_POST);exit;
		
		
		//load model
		$this->load->model('common_model');
		
		
		$closecodition=array('user_id'=>$this->loggedInUser->id,'status'=>'1');
		//Get Groups
		$support1	=	$this->common_model->getTableData('support',$closecodition);
		
		
				
		$start =  $this->uri->segment(4,0);  
		//Get the inbox mail list 
     	$page_rows         					 =  $this->config->item('mail_limit');
		//$page_rows =1;
		
		$limit[0]			 = $page_rows;
		if($start > 0)
		   $limit[1]			 = ($start-1) * $page_rows;
		else
		    $limit[1]			 = $start * $page_rows;  
		
		$order[0]            ='id';
		$order[1]            ='desc';
		
		$support 	         = $this->common_model->getTableData('support',$closecodition,NULL,NULL,$limit,$order);   
		
		$this->outputData['support'] = $support;
		
		
		$this->load->library('pagination');
		$config['base_url'] 	 = site_url('support/close');  
		$config['total_rows'] 	 = $support1->num_rows();		
		$config['per_page']     = $page_rows; 
		$config['cur_page']     = $start;
		$this->pagination->initialize($config);		
		$this->outputData['pagination']   = $this->pagination->create_links2(false,'support');	
		
		//Load View
	  // 	$this->load->view('support/main',$this->outputData);
		
		
		//Get Frequently Asked Questions
		$conditions = array('is_frequent'=> 'Y');
		$this->outputData['frequentFaqs']	=	$this->faq_model->getFaqs($conditions);	
		
		//Load View	
	    $this->load->view('support/main',$this->outputData);
	}//End of function
	
	function postticket()
	{
		
						
		//Loading the lang files
		$language_code = $this->config->item('language_code');
		$this->lang->load('enduser/common',$language_code);
		$this->lang->load('enduser/support',$language_code);
		
		
		if($this->input->post('postticket'))
		{
			
			$this->form_validation->set_rules('subject','lang:subject','required|trim|xss_clean');
		$this->form_validation->set_rules('description','lang:comments','required|trim|xss_clean');
		if($this->form_validation->run())
		{							
			      $inputData['priority']   			= $this->input->post('priority',TRUE);
				 $inputData['category']  				= $this->input->post('category',TRUE);
				 $inputData['subject']    		 	= $this->input->post('subject',TRUE);
				 $inputData['description']    		    = $this->input->post('description',TRUE);
				 $inputData['user_id']				 = $this->loggedInUser->id;
				 $inputData['status']    		    = '0';
				 $inputData['callid']    		    = 't'.time();
				  
				 $this->common_model->insertData('support',$inputData);
				 
				
			 if($inputData['priority']==1) { 
			 $priority=$this->lang->line('urgent');
			  }
			  elseif($inputData['priority']==2) { 
			 $priority= $this->lang->line('high');
			  }
			   elseif($inputData['priority']==3) { 
			 $priority= $this->lang->line('normal');
			  }
			    elseif($inputData['priority']==4) { 
			 $priority=  $this->lang->line('low');
			  }elseif($inputData['priority']==5) { 
			$priority=  $this->lang->line('very low');
			  }
			  
			  if($inputData['category']==1) { 
			 $category=$this->lang->line('general');
			  }
			  elseif($inputData['category']==2) { 
			$category=$this->lang->line('general');
			  }
			   elseif($inputData['category']==3) { 
			$category=$this->lang->line('general');
			  }
			    elseif($inputData['category']==4) { 
		$category=$this->lang->line('general');
			  }elseif($inputData['category']==5) { 
		$category=$this->lang->line('general');
		}
			
				 
				 $conditionPostticketMail  	 	   = array('email_templates.type'=> 'ticket_post');
				 $resultPostticketMail        	   = $this->email_model->getEmailSettings($conditionPostticketMail);
				 $resultPostticketMail			   = $resultPostticketMail->row(); 
				 $user_name					       = $this->loggedInUser->user_name;
				 $toemail						   = $this->loggedInUser->email;	
				 $fromEmail						   = $this->config->item('site_admin_mail');
				 
				 $splVars_postticket = array("!callid" => $inputData['callid'],"!category" => $category,"!subject" =>  $inputData['subject'],"!description" =>  $inputData['description'], "!priority" => $priority,"!status"=>'Open',"!site_name" => $this->config->item('site_title'),"!username " => $this->loggedInUser->user_name); 
				 
				 $mailSubject = strtr($resultPostticketMail->mail_subject, $splVars_postticket);
				 $mailContent = strtr($resultPostticketMail->mail_body, $splVars_postticket);	
				 $this->email_model->sendHtmlMail($toemail,$fromEmail,$mailSubject,$mailContent);
			     redirect('support');
				  
		}
		}
	
		// $this->load->view('support/postticket',$this->outputData);
		  $this->load->view('support/postticket',$this->outputData);
	}
}
//End contact Class

/* End of file contact.php */ 
/* Location: ./app/controllers/contact.php */
?>