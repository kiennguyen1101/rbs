<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* This is an extension of the core Pagination class designed to allow use of "real" page numbers
* instead of offset values.  The majority of the code is the original create_links() function
* but slightly modified.
*/

//require_once BASEPATH . 'libraries/Pagination.php';


class MY_Form_validation extends CI_Form_validation {

   function MY_Form_validation()
   {
      parent::CI_Form_validation();
   }
   
   function set_errors($fields)
   {
      if (is_array($fields) and count($fields))
      {
         foreach($fields as $key => $val)
         {
            $error = $key.'_error';
            if (isset($this->$error) and isset($this->$key) and $this->$error != '')
            {
               $old_error = $this->$error;
               $new_error = $this->_error_prefix.sprintf($val, $this->$key).$this->_error_suffix;
               $this->error_string = str_replace($old_error, $new_error, $this->error_string);
               $this->$error = $new_error;
            }
         }
      }     
   }
   
}
//Class End 
