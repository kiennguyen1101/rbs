<?php
/**
 * Reverse bidding system Common_model Class
 *
 * helps to achieve common tasks related to the site like flash message formats,pagination variables.
 *
 * @package		Reverse bidding system
 * @subpackage	Models
 * @category	Common_model 
 * @author		Cogzidel Dev Team
 * @version		Version 1.0
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
	 class Common_model extends Model {
	 
	/**
	 * Constructor 
	 *
	 */
	  function Common_model() 
	  {
		parent::Model();
		
		//Load Neccessary Model
		$this->load->model('user_model');
		// load model
	   $this->load->model('page_model');
	    $this->load->model('auth_model');
				
      }//Controller End
	  
	// --------------------------------------------------------------------
	
	/**
	 * Set Style for the flash messages
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	 function flash_message($type,$message)
	 {
	 	switch($type)
		{
			case 'success':
					$data = '<div class="message"><div class="success">'.$message.'</div></div>';
					break;
			case 'error':
					$data = '<div class="message"><div class="error">'.$message.'</div></div>';
					break;		
		}
		return $data;
	 }//End of flash_message Function
	 
	 	 
 	// --------------------------------------------------------------------
	
	/**
	 * Set Style for the flash messages in admin section
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	 function admin_flash_message($type,$message)
	 {
	 	switch($type)
		{
			case 'success':
					$data = '<div class="message"><div class="success">'.$message.'</div></div>';
					break;
			case 'error':
					$data = '<div class="message"><div class="error">'.$message.'</div></div>';
					break;		
		}
		return $data;
	 }//End of flash_message Function
	 
	// --------------------------------------------------------------------
	
	/**
	 * Set page Title And Meta Tags For The Entire Site
	 *
	 * @access	public
	 * @param	nil
	 * @return	array	page title and meta tags content
	 */
	 function getPageTitleAndMetaData()
	 {
	 	$data['page_title'] 			= $this->config->item('site_title');
		$data['meta_keywords']			= 'Outsource your projects to freelance programmers and designers at cheap prices. ';
		$data['meta_description']		= 'Outsource your projects to freelance programmers and designers at cheap prices. Freelancers will compete for your business. Get programming done for your site in php, mysql, xml, perl/cgi, javascript, asp, plus web design, search engine optimization, marketing, writing, job listings and so much more.';	
		
		return $data;
	 }//End of getPageTitleAndMetaData Function
	 
	// --------------------------------------------------------------------
	
	
	/**
	 * Set page Title And Meta Tags For The Entire Site
	 *
	 * @access	public
	 * @param	nil
	 * @return	array	page title and meta tags content
	 */
	 function getPageTitle($condition)
	 {
	 			
		if(count($condition) > 0)		
	 		$this->db->where($condition);
		
		$this->db->from('categories');
	 	$this->db->select('categories.page_title,categories.meta_keywords,categories.meta_description');
		$result = $this->db->get();
		
		return $result;
	 }//End of getPageTitleAndMetaData Function
	 
	// --------------------------------------------------------------------
	
		
	/**
	 * Get Countries
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getCountries($conditions=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		
		$this->db->from('country');
	 	$this->db->select('country.id,country.country_symbol,country.country_name');
		$result = $this->db->get();
		return $result;
	 }//End of getCountries Function
	 
	// --------------------------------------------------------------------
	
	
		
	/**
	 * Get getEncryptedString
	 *
	 * @access	private
	 * @param	string 
	 * @return	object	object with result set
	 */
	 function getEncryptedString($string='')
	 {
		
		if($string!='')
			$string_hash = $this->encrypt->encode($string);	
				
		else 
			$string_hash = '';	
			
		return $string_hash;
	 }//End of getEncryptedString Function	
	 
	// --------------------------------------------------------------------
		
	/**
	 * Get DecryptedString
	 *
	 * @access	private
	 * @param	string	conditions to fetch data
	 * @return	object	object with result set
	 */
	  function getDecryptedString($string='')
	 {
		
		
		if($string!='')
		{
			$string_hash = $this->encrypt->decode($string);	
			//echo $string_hash;exit;
		}	
		else 
			$string_hash = '';	
		return $string_hash;
	 }//End of getDecryptedString Function
	 
	// --------------------------------------------------------------------
		
	/**
	 * Get getLoggedInUser
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	  function getLoggedInUser()
	 {

	 	$user = '';

		if($this->session->userdata('role'))
		{
			$condition = array('users.id'=>$this->session->userdata('user_id'));
			$fields    = 'users.id,roles.role_name,users.user_name,users.logo,users.name,users.role_id,users.email,users.created,users.user_rating,users.num_reviews,users.suspend_status';
			$query = $this->user_model->getUsers($condition,$fields);
			if($query->num_rows()>0)
			{
				$user = $query->row();				
			}			
		} //Switch End
			// Puhal Changes Start Function added for the Remenber me option  (Sep 17 Issue 3)
		elseif($this->auth_model->getUserCookie('user_name') && $this->auth_model->getUserCookie('user_password'))
		{
			 $this->auth_model->getUserCookie('user_name');
				$this->auth_model->getUserCookie('user_password');
				
				$conditions 		=  array('user_name'=>$this->auth_model->getUserCookie('user_name'),'password' => $this->auth_model->getUserCookie('user_password'),'users.user_status' => '1');
				
				$query				= $this->user_model->getUsers($conditions);
				
				//pr($query);
				if($query->num_rows() > 0)
				{
					$user =  $query->row();
					$this->auth_model->setUserSession($user);
				}
		}
		// Puhal Changes End Function added for the Remenber me option  (Sep 17 Issue 3)
		
		return $user;
	 }//End of getDecryptedString Function
	 // --------------------------------------------------------------------
	
		
	/**
	 * Get getPages
	 *
	 * @access	public
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getPages()
	 {
	   $conditions = array('page.is_active'=> 1);
	   $pages                      = array();
       $pages['staticPages']       =$this->page_model->getPages($conditions);
	   return $pages['staticPages'];
	   
	 }
	 
	 /**
	 * Get getPages
	 *
	 * @access	public
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getSitelogo()
	 {
	   $conditions = array('settings.code'=>'SITE_LOGO');
	   $data                      = array();
	   $this->db->where($conditions);
	   $this->db->from('settings');
	   $this->db->select('settings.string_value');
	   $result = $this->db->get();
       $data['site_logo']         =	$result->result();
	   return $data;
	   
	 }
	 	 
	 
	  function getTableData($table='',$conditions=array(),$fields='',$like=array(),$limit=array(),$orderby = array(),$like1=array(),$order = array(),$conditions1=array())
	 {
	 	//Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		
		//Check For Conditions
	 	if(is_array($conditions1) and count($conditions1)>0)		
	 		$this->db->or_where($conditions1);	
			
		//Check For like statement
	 	if(is_array($like) and count($like)>0)		
	 		$this->db->like($like);	
		
		if(is_array($like1) and count($like1)>0)

			$this->db->or_like($like1);	
			
		//Check For Limit	
		if(is_array($limit))		
		{
			if(count($limit)==1)
	 			$this->db->limit($limit[0]);
			else if(count($limit)==2)
				$this->db->limit($limit[0],$limit[1]);
		}	
		
		
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by('id', 'desc');
			
		//Check for Order by
		if(is_array($order) and count($order)>0)
			$this->db->order_by($order[0], $order[1]);	
			
		$this->db->from($table);
		
		//Check For Fields	 
		if($fields!='')
		 
				$this->db->select($fields);
		
		else 		
	 		$this->db->select();
			
		$result = $this->db->get();
		
	//pr($result->result());
		return $result;
		
	 }	 
	 
	 	 function deleteTableData($table='',$conditions=array())
	 {
	    //Check For Conditions
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
			
		$this->db->delete($table);
		return $this->db->affected_rows(); 
		 
 	 }//End of deleteTableData Function
	 
	 
	   function insertData($table='',$insertData=array())
	 {
	 	return $this->db->insert($table,$insertData);
	 }//End of insertData Function
	 
	 
	  function updateTableData($table='',$id=0,$updateData=array(),$conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
	 	$this->db->update($table, $updateData);
		 
	 }//End of updateTableData Function
		 
}
// End Common_model Class
   
/* End of file Common_model.php */ 
/* Location: ./app/models/Common_model.php */
?>