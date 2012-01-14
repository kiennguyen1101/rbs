<?php
/**
 * Reverse bidding system Home Class
 *
 * Permits admin to set the site settings like site title,site mission,site offline status.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Common Display 
 * @author		
 * @version		
 * @created		December 30 2008
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
class Offline extends Controller {

	//Global variable  
    public $outputData;		//Holds the output data for each view
	public $loggedInUser;
   
    /**
	 * Constructor 
	 *
	 * Loads language files and models needed for this controller
	 */
	function Offline()
	{
		parent::Controller();
		//Get Config Details From Db
		$this->config->db_config_fetch();
		
		//Debug Tool
	   	//$this->output->enable_profiler=true;
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
		//Site Offline Message
		$this->outputData['message']	=	$this->config->item('offline_message');	
		$this->load->view('offline',$this->outputData);
	} //Function Index End
	
	
}//End  Buyer Class

/* End of file offline.php */
/* Location: ./system/application/controllers/offline.php */
?>