<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**

 * Admin URL

 *

 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the

 * first parameter either as a string or an array.

 *

 * @access	public

 * @param	string

 * @return	string

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

if ( ! function_exists('get_est_time'))
{
	function get_est_time()

	{
		$now = time();

		$gmt = local_to_gmt($now);
		
		$timezone = 'UM4';
		$daylight_saving = FALSE;
		
		$tt = gmt_to_local($gmt, $timezone, $daylight_saving);
		
		return $tt;
	}

}

if ( ! function_exists('get_datetime'))
{
	function get_datetime($timestamp)

	{
		$CI =& get_instance();
		$CI->lang->load('enduser/common');
		if(date('d/M/Y') == date('d/M/Y',$timestamp))
		$date = $CI->lang->line('Today at')." ".date('H:i',$timestamp)." EST";
		else
		$date = date('d-M-Y H:i',$timestamp)." EST";		
		return $date;
	}

}

if ( ! function_exists('get_date'))
{
	function get_date($timestamp)

	{
		$CI =& get_instance();
		$CI->lang->load('enduser/common');
		if(date('d/M/Y') == date('d/M/Y',$timestamp))
		$date = $CI->lang->line('Today at')." ".date('H:i',$timestamp)." EST";
		else 
		$date = date('d-M-Y',$timestamp)." EST";		
		return $date;
	}

}

if ( ! function_exists('current_date'))
{
	function current_date($timestamp)

	{
		$CI =& get_instance();
		$CI->lang->load('enduser/common');
		if(date('d/M/Y') == date('d/M/Y',$timestamp))
		$date = date('d/M/Y',$timestamp);
		else 
		$date = date('d-M-Y',$timestamp)." EST";		
		return $date;
	}

}


if ( ! function_exists('show_date'))
{
	function show_date($timestamp)
	{
		$CI =& get_instance();
		$CI->lang->load('enduser/common');
		if(date('d/M/Y') == date('d/M/Y',$timestamp))
		    $date = date('D, d M Y H:i:s',$timestamp)." EST";
		else 
		   $date = date('D, d M Y H:i:s',$timestamp)." EST";		
		return $date;
	}

}


if ( ! function_exists('days_left'))
{
	function days_left($endtime,$prjid)
	{
		//echo date('d-m-Y',$endtime);exit;
		$CI =& get_instance();
		$CI->lang->load('enduser/viewProject');
		$mod = $CI->load->model('skills_model');
		$today = get_est_time();
		$lastday = $endtime;
		$left = $lastday - $today;
		//echo $left;exit;
		if($left >= 0)
		{
			
			//$val = date('j',$left);
			$val =  ceil($left / 86400);
			if(date('d-m-Y',time()) == date('d-m-Y',$endtime))
				$rem = 'Ending today';
			else{
			if($val > 1) 
				$rem = $val." ".$CI->lang->line('days'); 
			else 
				$rem = $val." ".$CI->lang->line('day');
			}
			
			return $rem." ".$CI->lang->line('left');
		}
		else{
			$conditions =array('projects.project_status'=>'3');
			$CI->skills_model->updateProjects($prjid,$conditions);
			return $CI->lang->line('Closed');
		}
		
	}

}

if ( ! function_exists('dispute_time_left'))
{
	function dispute_time_left($time,$hrs)
	{
		//echo date('d-m-Y',$time);exit;
		$CI =& get_instance();
		$CI->lang->load('enduser/cancelProject');
		$mod = $CI->load->model('skills_model');
		$today = get_est_time();
		$lastday = $time;
		$difference = $today - $lastday;
		
		$day = floor($difference / 84600);
		$difference -= 84600 * floor($difference / 84600);
		$hours = floor($difference / 3600);
		$difference -= 3600 * floor($difference / 3600);
		$min = floor($difference / 60);
		$sec = $difference -= 60 * floor($difference / 60);
		//echo $min;
		if($day == 0 && $hours == 0){
		$resp_mins = $hrs * 60 ;
		$rem = ($resp_mins - $min ) +0.1;
			return "<b>".round_up($rem/60,2)." ".$CI->lang->line('hours left to respond')."</b>";
		}
		elseif($day == 0 && $hours < $hrs){
			$rem = $hrs - $hours;
			return "<b>".round_up($rem,2)." ".$CI->lang->line('hours left to respond')."</b>";
		}
		else
			return "<b>".$CI->lang->line('response time').' ('.$hrs.' hrs) '.$CI->lang->line('is over')."</b>";
		
		//return "$day days $hours hours $min minutes, and $difference seconds ago.";

		
	}

}

if ( ! function_exists('count_days'))
{
	function count_days($starttime,$endtime)

	{
		$CI =& get_instance();
		$CI->lang->load('enduser/viewProject');
		$today = $starttime;
		$lastday = $endtime;
		$left = $lastday - $today;
		if($left >= 0)
		{
			$val = date('j',$left);
			if($val > 1) 
				$rem = $val; 
			else 
				$rem = $val;
			return $rem;
		}
		else
		   return $CI->lang->line('Closed');
		
	}

}

function round_up($value, $precision = 0) {

	$sign = (0 <= $value) ? +1 : -1;
    $amt = explode('.', $value);
    $precision = (int) $precision;
   
    if (strlen($amt[1]) > $precision) {
        $next = (int) substr($amt[1], $precision);
        $amt[1] = (float) (('.'.substr($amt[1], 0, $precision)) * $sign);
       
        if (0 != $next) {
            if (+1 == $sign) {
                $amt[1] = $amt[1] + (float) (('.'.str_repeat('0', $precision - 1).'1') * $sign);
            }
        }
    }
    else {
        $amt[1] = (float) (('.'.$amt[1]) * $sign);
    }
   
    return $amt[0] + $amt[1];
}

function get_project_name($project_id)
{
	$CI =& get_instance();
	$mod 	= $CI->load->model('common_model');
	$conditions = array('projects.id'=>$project_id);
	$result = $CI->common_model->getTableData('projects',$conditions,'projects.project_name');
	$result = $result->row();
	if(isset($result->project_name))
	{	$project_name = $result->project_name; return $project_name; }
	
	
}
/* End of file MY_url_helper.php */

/* Location: ./app/helpers/MY_url_helper.php */

?>