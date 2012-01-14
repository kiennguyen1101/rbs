<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');





// ------------------------------------------------------------------------



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

    

     

 

 */

if ( ! function_exists('admin_url'))

{

	function admin_url($uri = '')

	{

		

		$CI =& get_instance();

		$admin_folder_name	=  $CI->config->config['admin_controllers_folder'];

		$uri = $admin_folder_name.'/'.$uri ;

		return $CI->config->site_url($uri);

	}

}



// ------------------------------------------------------------------------



/**

 * Images URL

 *

 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the

 * first parameter either as a string or an array.

 *

 * @access	public

 * @param	string

 * @return	string

 */

if ( ! function_exists('image_url'))

{

	function image_url($image_name = '')

	{

		

		$CI =& get_instance();
		$uri = str_replace($CI->config->item('index_page'),"",$CI->config->site_url()).'app/css/images/'.$image_name;
		//echo $uri;exit;
		return $uri;

	}

}



// ------------------------------------------------------------------------



/**

 * Portfolio Images URL

 *

 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the

 * first parameter either as a string or an array.

 *

 * @access	public

 * @param	string

 * @return	string

 */

if ( ! function_exists('pimage_url'))

{

	function pimage_url($image_name = '')

	{

		

		$CI =& get_instance();

		$uri = str_replace($CI->config->item('index_page'),"",$CI->config->site_url()).'files/portfolios/'.$image_name;

		return $uri;

	}

}



// ------------------------------------------------------------------------



/**

 * User Images URL

 *

 * Create a admin URL based on the admin folder path mentioned in config file. Segments can be passed via the

 * first parameter either as a string or an array.

 *

 * @access	public

 * @param	string

 * @return	string

 */

if ( ! function_exists('uimage_url'))

{

	function uimage_url($image_name = '')

	{

		

		$CI =& get_instance();

		$uri = str_replace($CI->config->item('index_page'),"",$CI->config->site_url()).'files/logos/'.$image_name;

		return $uri;

	}

}


if ( ! function_exists('prfile_url'))

{

	function prfile_url($image_name = '')

	{

		

		$CI =& get_instance();

		$uri = str_replace($CI->config->item('index_page'),"",$CI->config->site_url()).'files/project_attachment/'.$image_name;

		return $uri;

	}

}


// ------------------------------------------------------------------------



/**

 * Header Redirect Admin

 *

 * Header redirect in two flavors

 * For very fine grained control over headers, you could use the Output

 * Library's set_header() function.

 *

 * @access	public

 * @param	string	the URL

 * @param	string	the method: location or redirect

 * @return	string

 */

if ( ! function_exists('redirect_admin'))

{

	function redirect_admin($uri = '', $method = 'location', $http_response_code = 302)

	{

		switch($method)

		{

			

			case 'refresh'	: header("Refresh:0;url=".admin_url($uri));

				break;

			default			: header("Location: ".admin_url($uri), TRUE, $http_response_code);

				break;

		}

		exit;

	}

}



// ------------------------------------------------------------------------



/**

 * Header Redirect Admin

 *

 * Header redirect in two flavors

 * For very fine grained control over headers, you could use the Output

 * Library's set_header() function.

 *

 * @access	public

 * @param	string	the URL

 * @param	string	the method: location or redirect

 * @return	string

 */

if ( ! function_exists('replaceSpaceWithUnderscore'))

{

	function replaceSpaceWithUnderscore($text='')

	{

		$text = str_replace(' ','_',$text);

		return $text;

		

	} //Function replaceSpaceWithUnderscore End

}



// ------------------------------------------------------------------------



/**

 * Header Redirect Admin

 *

 * Header redirect in two flavors

 * For very fine grained control over headers, you could use the Output

 * Library's set_header() function.

 *

 * @access	public

 * @param	string	the URL

 * @param	string	the method: location or redirect

 * @return	string

 */

if ( ! function_exists('replaceUnderscoreWithSpace'))

{

	function replaceUnderscoreWithSpace($text = '')

	{

		$text = str_replace('_',' ',$text);

		return $text;

	}//Function replaceUnderscoreWithSpace End

}



// ------------------------------------------------------------------------



/**

 * Header Redirect Admin

 *

 * Header redirect in two flavors

 * For very fine grained control over headers, you could use the Output

 * Library's set_header() function.

 *

 * @access	public

 * @param	string	the URL

 * @param	string	the method: location or redirect

 * @return	string

 */

if ( ! function_exists('linksToCategories'))

{

	function linksToCategories($string='')

	{

		if($string!='')

		{

			$categories = explode(',',$string);

			if(count($categories)>0)

			{

					

			}

			

		} 

		return false;

		

	}

}



/* End of file MY_url_helper.php */

/* Location: ./app/helpers/MY_url_helper.php */

?>