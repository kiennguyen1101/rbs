<?php
/**
 * Reverse bidding system Faq_model Class
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
	 class Faq_model extends Model {
	 
	/**
	 * Constructor 
	 *
	 */
	  function Faq_model() 
	  {
		parent::Model();
				
      }//Controller End
	 
	// --------------------------------------------------------------------
		
	/**
	 * Get Users
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getFaqs($conditions=array(),$like=array())
	 {
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		
		//Check For like statement
	 	if(is_array($like) and count($like)>0)
			$this->db->or_like($like);	
		
		$this->db->from('faqs');
		$this->db->join('faq_categories', 'faq_categories.id = faqs.faq_category_id','left');	 
	 	$this->db->select('faqs.id,faqs.faq_category_id,faq_categories.category_name,faqs.question,faqs.is_frequent,faqs.answer,faqs.created');
		$result = $this->db->get();
		return $result;
		
	 }//End of getFaqs Function
	 
	function getFaq($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby=array())
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
			
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
		
		$this->db->from('faqs');
		$this->db->join('faq_categories', 'faq_categories.id = faqs.faq_category_id','left');	
		
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('faqs.id,faqs.faq_category_id,faq_categories.category_name,faqs.question,faqs.is_frequent,faqs.answer,faqs.created');
			 
	 	
		$result = $this->db->get();
		return $result;
		
	 }//End of getFaqs Function 
	 
	 
	// --------------------------------------------------------------------
		
	/**
	 * Get getFaqCategoriesWithFaqs
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getFaqCategoriesWithFaqs()
	 {
	 	//Get Faq Categories
		$query 							= $this->getFaqCategories();
		
		//Return Data
		$data 							=array();
		
	 	if($query->num_rows()>0)
		{
			$i=0;
			foreach($query->result() as $row)
			{
				$data[$i]['faq_category_id']			= $row->id;
				$data[$i]['faq_category_name']			= $row->category_name;
				$data[$i]['num_faqs']					= 0;
				
				$conditions  		= array('faq_category_id'=>$row->id);
				$query_faqs 	   = $this->getFaqs($conditions);
				
				//Check for query categories availability
				if($query_faqs->num_rows()>0)
				{
					$data[$i]['num_faqs']	= $query_faqs->num_rows();
					$data[$i]['faqs'] 	  = $query_faqs;
				} //If End - Checks For categories availability
				$i++;
			}
		}//If End - check for group avaliability
		return $data;
	 }//End of getFaqCategoriesWithFaqs Function
	 
	 // --------------------------------------------------------------------
		
	/**
	 * Add faq
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function addFaq($insertData=array())
	 {
	 	$this->db->insert('faqs', $insertData);
		 
	 }//End of addFaqCategory Function
	 
	// --------------------------------------------------------------------
	
	/**
	 * delete faq
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteFaq($id=0,$conditions=array())
	 {
	 	 if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
		 $this->db->delete('faqs');
		 
	 }//End of addFaqCategory Function
	 
	 
	 function deleteFaqCat($id=0,$conditions=array())
	 {
	 	 if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
			$this->db->from('faq_categories');
			$this->db->select('faq_categories.id,faq_categories.category_name,faq_categories.created');
			
		$query = $this->db->get();
		//Return Data
		$data 							=array();
		
	 	if($query->num_rows()>0)
		{
			$i=0;
			foreach($query->result() as $row)
			{
				$data[$i]['faq_category_id']			= $row->id;
				$data[$i]['faq_category_name']			= $row->category_name;
				$data[$i]['num_faqs']					= 0;
				
				$conditions		= array('faq_category_id'=>$row->id);
				$query_faqs 	   = $this->getFaqs($conditions);
				
				$this->db->where($conditions);
				
				 $this->db->delete('faqs');
				 	$i++;
		}
		
		}
				
		
		 
	 }//End of addFaqCategory Function
	 
	 function deleteFaqCat1($id=0,$conditions=array())
	 {
	 	 if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
		 $this->db->delete('faq_categories');
		 
	 }//End of addFa
	// --------------------------------------------------------------------
	
	
	/**
	 * Add faq category
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function addFaqCategory($insertData=array())
	 {
	 	$this->db->insert('faq_categories', $insertData);
		 
	 }//End of addFaqCategory Function
	 
	// --------------------------------------------------------------------
		
	/**
	 * Update faq category
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateFaqCategory($id=0,$updateData=array())
	 {
	 	$this->db->where('faq_categories.id', $id);
	 	$this->db->update('faq_categories', $updateData);
		 
	 }//End of editGroup Function 
	 
	// --------------------------------------------------------------------
		
	/**
	 * Update faq
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function updateFaq($id=0,$updateData=array())
	 {
	 	$this->db->where('faqs.id', $id);
	 	$this->db->update('faqs', $updateData);
		 
	 }//End of updateFaq Function 
	 
	 
	// --------------------------------------------------------------------
		
	/**
	 * Get Faq Categories
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
	 function getFaqCategories($conditions=array(),$fields='')
	 {
	 	//Check For Conditions
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		
		$this->db->from('faq_categories');
		
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('faq_categories.id,faq_categories.category_name,faq_categories.created');
			
		$result = $this->db->get();
		return $result;
		
	 }//End of getCategories Function
	 
	 function getFaqCategory($conditions=array(),$fields='',$like=array(),$limit=array(),$orderby=array())
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
			
		//Check for Order by
		if(is_array($orderby) and count($orderby)>0)
			$this->db->order_by($orderby[0], $orderby[1]);
			
			$this->db->from('faq_categories');
		//Check For Fields	 
		if($fields!='')
				$this->db->select($fields);
		else 		
	 		$this->db->select('faq_categories.id,faq_categories.category_name,faq_categories.created');
			
		$result = $this->db->get();
		return $result;
		
	 }//End of getCategories Function
}
// End Faq_model Class
   
/* End of file Faq_model.php */ 
/* Location: ./app/models/Faq_model.php */