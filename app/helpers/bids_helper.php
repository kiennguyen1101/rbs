<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
/**
 * getBidsInfo
 *
 * Will return the average bid value a the project
 *
 * @access	public
 * @param	string
 * @return	string
 */
	function getBidsInfo($projectId=NULL)
	{
		$CI 	=& get_instance();
		$mod 	= $CI->load->model('skills_model');
		$conditions = array('bids.project_id'=>$projectId);
		$result = $CI->skills_model->getSumBids($conditions);
		$num = $CI->skills_model->getNumBids($conditions);
		if($result->num_rows()>0)
		{
			$data = $result->row();	
			$amt = $data->bid_amount;
			if($num != 0)
			return "$".round($amt/$num);
			else
			return "N/A";
		}
		else 
		return "N/A";
	} //End of getBidsInfo function
	
	// ------------------------------------------------------------------------

	/**
	 * getNumBid
	 *
	 * Returns number of bids posted on a project
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function getNumBid($projectId=NULL)
	{
		$CI 	=& get_instance();
		$mod 	= $CI->load->model('skills_model');
		$conditions = array('bids.project_id'=>$projectId);
		$num = $CI->skills_model->getNumBids($conditions);
		return $num;
	} //End of getNumBid function
	
	// ------------------------------------------------------------------------

	/**
	 * getLowestBid
	 *
	 * Returns number of bids posted on a project
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function getLowestBid($projectId=NULL,$providerId=NULL)
	{
		$CI 	=& get_instance();
		$mod 	= $CI->load->model('skills_model');
		$conditions = array('bids.project_id'=>$projectId,'bids.user_id' => $providerId);
		$result = $CI->skills_model->getLowestBid($conditions,NULL,NULL,array('1'),array('bids.bid_amount','ASC'));
		$row = $result->row();
		if(is_object($row))
		return $row->bid_amount;
		else
		return "N/A";
	} //End of getLowestBid function
	
	// ------------------------------------------------------------------------

	/**
	 * getCategoryLinks
	 *
	 * Returns the links
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function getCategoryLinks($categories=NULL)
	{
		$cat = explode(",",$categories);
		$cnt = count($cat);
		$links = '';
		$i = 1;
		foreach($cat as $cate){	
			if($i != $cnt)
			$comma = ",";
			else
			$comma = "";
			$links .= "<a href='".site_url('project/category/'.urlencode($cate))."'>".$cate."</a>".$comma;
			$i++;
		}
		return $links;
	} //End of getLowestBid function
	
	// ------------------------------------------------------------------------

	/**
	 * getLowBid
	 *
	 * Returns lowest bid on the project
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function getLowBid($projectId=NULL)
	{
		$CI 	=& get_instance();
		$mod 	= $CI->load->model('skills_model');
		$conditions = array('bids.project_id'=>$projectId);
		$result = $CI->skills_model->getLowestBid($conditions,NULL,NULL,array('1'),array('bids.bid_amount','ASC'));
		$row = $result->row();
		if(is_object($row))
			return $row->bid_amount;
		else
			return "N/A";
	} //End of getLowestBid function
	
	// ------------------------------------------------------------------------

	/**
	 * getProjectStatus
	 *
	 * Returns status of the project
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function getProjectStatus($status=NULL)
	{
		$CI =& get_instance();
		$CI->lang->load('enduser/viewProject');
		if($status == 0)
		$stat = $CI->lang->line('Open'); 
		if($status == 1)
		$stat = $CI->lang->line('Frozen'); 
		if($status == 2)
		$stat = $CI->lang->line('Closed');
		if($status == 3)
		$stat = $CI->lang->line('Cancelled');
		return $stat;
	} //End of getLowestBid function
	
	function getCurrentStatus($status=NULL,$programmer=NULL,$project_id=NULL)

                {

                                $rating='';

                                $CI =& get_instance();

                                $CI->lang->load('enduser/viewProject');

                                if($status == 0)

                                {$stat['status'] = $CI->lang->line('Open');

                                 //$disp_message=        

                                }

                                if($status == 1)

                                {

                                                $stat['status'] = $CI->lang->line('Frozen');

                                                $conditions = array('users.id'=>$programmer);

                                                $result = $CI->user_model->getUsers($conditions,'users.user_name');

                                                $result= $result->row();

                                                $stat['message'] = '('.$CI->lang->line('Choosen Programmer').':'.$result->user_name.' , '.$CI->lang->line('Waiting For Accept').')';

                                }             

                                if($status == 2)

                                {

                                                $stat['status'] = $CI->lang->line('Closed');

                                                $conditions = array('users.id'=>$programmer);

                                               

                                                $result = $CI->user_model->getUsers($conditions,'users.user_name,users.role_id,users.id');

                                                $result= $result->row();

                                               

                                                $condition2 = array('reviews.provider_id' => $result->id,'reviews.review_type' => '2','reviews.hold' => '0','reviews.project_id'=>$project_id);

                                                $reviewDetails = $CI->skills_model->getReviews($condition2,'reviews.rating');

                                                $reviewDetails=$reviewDetails->row();

                                               

                                                if(isset($reviewDetails->rating))

                                                                $rating= ' , Rated '.$reviewDetails->rating.' out of 10';

                                               

                                                $conditions = array('transactions.project_id'=>$project_id,'transactions.type'=>'Transfer','transactions.status'=>'Completed');

                                                $payement_status = $CI->transaction_model->getTransactions($conditions);

                                                if($payement_status->num_rows()>0)

                                                { $stat['message'] = '('.$CI->lang->line('Choosen Programmer').':'.$result->user_name.' , '.$CI->lang->line('paid').$rating.')'; }

                                                else

                                                                $stat['message'] = '('.$CI->lang->line('Choosen Programmer').':'.$result->user_name.' , '.$CI->lang->line('unpaid').')';

                                }             

                                if($status == 3)

                                $stat['status'] = $CI->lang->line('Cancelled');

                                return $stat;

                               

                }
				
				

/* End of file users_helper.php */
/* Location: ./app/helpers/users_helper.php */
?>