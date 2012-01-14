<?php  
/** 
 * Reverse bidding system File Class
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
class File extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;

   
	/**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function File()
	{
	   parent::Controller();
	   
	    //Get Config Details From Db
		$this->config->db_config_fetch();
		
	   //Manage site Status 
		if($this->config->item('site_status') == 1)
		redirect('offline');
	   

	   //Increase the uplaod file size	
	   ini_set('upload_max_filesize','100M');
	   
	    //Debug Tool
	   	//$this->output->enable_profiler=true;		
		
		//Load Models Common to all the functions in this controller
		$this->load->model('common_model');
		$this->load->model('skills_model');
		$this->load->model('file_model');
		$this->load->model('certificate_model');
	
        //Get Site Logo
	    //$res123=  $this->common_model->getSitelogo();
		//pr($res123['site_logo'] );
			  		
		//Page Title and Meta Tags
		$this->outputData = $this->common_model->getPageTitleAndMetaData();
		
		//Get Logged In user
		$this->loggedInUser					= $this->common_model->getLoggedInUser();
		$this->outputData['loggedInUser'] 	= $this->loggedInUser;
		
		//Get Footer content
		$this->outputData['pages']	= $this->common_model->getPages();
		
		//Get Latest Projects
		$limit_latest = $this->config->item('latest_projects_limit');
		$limit3 = array($limit_latest);
		$this->outputData['latestProjects']	= $this->skills_model->getLatestProjects($limit3);
		
		if($this->loggedInUser)
		{
		//Get logged user role
		   $this->outputData['logged_userrole']   =  $this->loggedInUser->role_id;
		}
		//language file
		$this->lang->load('enduser/fileManager', $this->config->item('language_code'));
		$this->lang->load('enduser/common', $this->config->item('language_code'));
	
	    //Post the maximum size of memory limit
		$maximum        = $this->config->item('upload_limit');
 	    $this->outputData['maximum_size'] = $maximum;
		
		//Innermenu tab selection
		$this->outputData['innerClass6']   = '';
		$this->outputData['innerClass6']   = 'selected';
		
	} //Controller End 
	
	// --------------------------------------------------------------------
	
	/**
	 * Loads fileManager page.
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */	
	function index()
	{	
				
		if(!isset($this->loggedInUser->id))
		  {
		  	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You can not access to this page')));
		    redirect('info');
		  }
		
		//Check Whether User Logged In Or Not
	    if(isLoggedIn()===false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Dont have rights to access this page')));
			redirect('info');
		}
		
		//load validation library
		$this->load->library('form_validation');
		
		//Load Form Helper
		$this->load->helper('form');
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
	   
		if($this->input->post('uploadFile'))
		{	
			//Set rules
			$this->form_validation->set_rules('attachment','Upload File','callback_upload_file');
			if($this->form_validation->run() and isset($this->outputData['file']))
			{	
				$condition      = array('files.user_id'=>$this->loggedInUser->id);
				$totalSize      = $this->file_model->getFileSize($condition);
				$totalsize      = $totalSize->row();
				$oldSize        = $totalsize->totalsize;
				
				//Get the file size for uplaod file
				$size = $_FILES['attachment']['size'];
				
				//Get the Maximum Limit
				$maximum        = $this->config->item('upload_limit');
				$this->outputData['maximum_size'] = $maximum;
				
				//Convert the maximum limit to Bytes
				$maximum       = $maximum * 1024; 
				$balance_size  = $maximum - $oldSize; 
				$check_size    = $balance_size - $size ;
				
				if($check_size < 0)
				  {
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('File size is Exceed')));  	
					 redirect('file/index');
				  }
				
				//Get Uploaded file data
			 	$file = $this->outputData['file']['file_name'];
				
				$type = $this->outputData['file']['file_type'];
				//pr($this->outputData['file']);
				$insertData              		  = array();	
				$insertData['location']    		  = $file;
				$insertData['user_id']			  = $this->loggedInUser->id;
				$insertData['description']		  = $this->input->post('files_desc');
				$insertData['key']                = md5(time());
				$insertData['file_size']          = $size;
				$insertData['file_type']          = $type;
				$insertData['created']  		  = get_est_time();
				$insertData['delete']  		      = get_est_time()+3;
				
				$insertData['original_name']      = $this->outputData['file']['orig_name'];
				$this->file_model->postFile($insertData);
		
				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('update_confirm_success')));
				 redirect('file/index');
		 	}  //Form Validation End
		
		}
		
		//Conditions
		 $conditions							= array('files.user_id'=>$this->loggedInUser->id);
		 $this->outputData['fileInfo'] 			= $this->file_model->getFile($conditions);
		 
		 //load view
   		 $this->load->view('fileManager/listFiles',$this->outputData );
		
	} //Function index End
	
	// --------------------------------------------------------------------
	
	/**
	 * upload_file for both buyer and seller
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */ 
	function upload_file()
	{
		// check 
		if(isset($_FILES) and $_FILES['attachment']['name']=='')				
		  {
			return true;
		  }	
		// intialize the config items
		$config['upload_path'] 		='files/tempFiles/';
		$config['max_size'] 		= $this->config->item('max_upload_size');
		$config['encrypt_name'] 	= TRUE;
		$config['remove_spaces'] 	= TRUE;
		$config['allowed_types'] 	='jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|zip|ZIP|RAR|rar|doc|DOC|txt|TXT|xlsx|XLSX|xls|XLS|ppt|PPT|pdf|PDF|docx|DOCX|PPTX|pptx|CSV|csv';
		$this->load->library('upload', $config);
		
		// check if the file is upload or not.
		if ($this->upload->do_upload('attachment'))
		{
			    $this->outputData['file'] = $this->upload->data();
			    $condition      = array('files.user_id'=>$this->loggedInUser->id);
				$totalSize      = $this->file_model->getFileSize($condition);
				$totalsize      = $totalSize->row();
				$oldSize        = $totalsize->totalsize;
				
				//Get the file size for uplaod file
				$size = $this->outputData['file']['file_size'];
				
				//Get the Maximum Limit
				$maximum        = $this->config->item('upload_limit');
				$this->outputData['maximum_size'] = $maximum;
				
				//Convert the maximum limit to Bytes
				$maximum       = $maximum * 1024; 
				$balance_size  = $maximum - $oldSize; 
				$check_size    = $balance_size - $size ;
				
				if($check_size < 0)
				  {
					$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('File size is Exceed')));  	
					 redirect('file/index');
				  }
				
				//Get Uploaded file data
			 	$file = $this->outputData['file']['file_name'];
				
				$type = $this->outputData['file']['file_type'];
				//pr($this->outputData['file']);
				$insertData              		  = array();	
				$insertData['location']    		  = $this->outputData['file']['file_name'];
				$insertData['user_id']			  = $this->loggedInUser->id;
				$insertData['description']		  = $this->input->post('files_desc');
				$insertData['key']                = md5(time());
				$insertData['file_size']          = $this->outputData['file']['file_size'];
				$insertData['file_type']          = $this->outputData['file']['file_ext'];
				$insertData['created']  		  = get_est_time();
				$insertData['delete']  		      = get_est_time()+3;
				
				$insertData['original_name']      = $this->outputData['file']['orig_name'];
				$this->file_model->postFile($insertData);
		
				//Notification message
				$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('update_confirm_success')));
				 redirect('file/index');
			
			
			
			
						
			return true;			
		} else {
			$this->form_validation->set_message('upload_file', $this->upload->display_errors($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag')));
			return false;
		}//If end 
		
	}//Function upload_file End
	
	// --------------------------------------------------------------------
	
	/**
	 * View for list the file details which is uploaded
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	
	
	function view()
	{
		$key = $_GET['key'];

        //load Helpers
		$this->load->helpers('users');
		
		//Check Whether User Logged In Or Not
	    if(isLoggedIn()===false)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Dont have rights to access this page')));
			redirect('info');
		}
		
		//If Admin try to access this url...redirect him
		if(isAdmin() === true)
		{
			$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('Dont have rights to access this page')));
			redirect('info');
		}
		
		 //Conditions
		 $conditions							= array('files.key'=>$key);
		
		 $this->outputData['fileView'] 			= $this->file_model->getFile($conditions);
		 // load view 
	     $this->load->view('fileManager/viewFiles',$this->outputData );
	
	}
    // --------------------------------------------------------------------
	
	/**
	 * download for download the file which is uploaded
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function download()
	{
	
	    // load the helper and library files
	    $this->load->library('zip');
		$this->load->helper('users');
		$this->load->helper('download');
		// initiallize the data variable in to array
		$this->data = array();
		
	    // get the key value 
		$key=$this->input->get('key',0);
	    // Assign the base path.
		$base_path = base_url().'files/';
		
		// get the informations of the file from db.
		$conditions		  = array('files.key'=>$key);
		$file             =$this->file_model->getFile($conditions);
		 foreach($file->result() as $value)
			{
					
				// Read the file's contents
				$data = file_get_contents_curl($base_path.'tempFiles/'.$value->location); 
				$name = $value->location;
				// Apply the download function
				force_download($name, $data);
				
				
				
			}
		}

	 // --------------------------------------------------------------------
	
	/**
	 * delete for delete the file which is uploaded
	 *
	 * @access	private
	 * @param	nil
	 * @return	void
	 */
	function delete()
	{
		//Form Submitted
		if($this->input->post('files_upload_remove'))
		{
		     // get the check box values
		     $this->input->post('chkFile');
		
			 $quotes_ids	 = $this->input->post('chkFile');
			
			 // separate the values
			 $ids 		 = implode(',',$quotes_ids);
			 // Delete query		
			 $sql 		 = 'DELETE FROM files WHERE id IN ('.$ids.')';	
			 $this->db->query($sql);
			
		}//If 	End	
		// success message
		$this->session->set_flashdata('flash_message', $this->common_model->flash_message('success',$this->lang->line('update_delete_success')));
	    redirect('file/index');
	} //Function delete end
	

		
} //End  File Class

/* End of file file.php */ 
/* Location: ./app/controllers/file.php */