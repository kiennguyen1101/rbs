<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Get buyer's review
	 *
	 * Returns number of bids posted on a project
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function getAvgReview($buyerId=NULL,$type=NULL)
	{
		$CI 	=& get_instance();
		$mod 	= $CI->load->model('skills_model');
		$tid = '1';
		if($type == 'provider_id')
		$tid = '2';
		//Get reviews
		$condition2 = array('reviews.'.$type => $buyerId,'reviews.review_type' => $tid,'reviews.hold' => '0');
		$result = $CI->skills_model->getReviews($condition2);
		//Get sum reviews
		$res2 = $CI->skills_model->getSumReviews($condition2);
		
		$row = $result->num_rows();

		//Get avg
		if(isset($res2))
		$avg = round($res2/$row);
		else
		$avg = 0;
		return $avg;
	} //End of getBuyerReview function
	
	// --------------------------------------------------------------------
	
	/**
	 * Get buyer's review
	 *
	 * Returns number of bids posted on a project
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function getNumReviews($buyerId=NULL,$type=NULL)
	{
		$CI 	=& get_instance();
		$mod 	= $CI->load->model('skills_model');
		
		if($type == 'provider_id')
		$ntype = '2';
		if($type == 'buyer_id')
		$ntype = '1';
		//Get reviews
		$condition2 = array('reviews.'.$type => $buyerId,'reviews.review_type' => $ntype,'reviews.hold' => '0');
		$result = $CI->skills_model->getReviews($condition2);
				
		$row = $result->num_rows();
		return $row;
	} //End of getBuyerReview function


   function getReviewStatus($projectid,$programmer_id)
   {
   		$CI 	=& get_instance();
		$mod 	= $CI->load->model('skills_model');
		$condition2 = array('reviews.project_id' => $projectid,'reviews.provider_id' => $programmer_id,'reviews.review_type' => '2');
		
		$reviewDetails = $CI->skills_model->getReviews($condition2);
		return $reviewDetails; 
   }
   
   function getReviewStatusProgrammer($projectid,$programmer_id)
   {
   		$CI 	=& get_instance();
		$mod 	= $CI->load->model('skills_model');
		$condition2 = array('reviews.project_id' => $projectid,'reviews.provider_id' => $programmer_id,'reviews.review_type' => '1');
		$reviewDetails = $CI->skills_model->getReviews($condition2);
				
		return $reviewDetails; 
   }
   
   
/* End of file users_helper.php */
/* Location: ./app/helpers/users_helper.php */
?>