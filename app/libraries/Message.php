<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The Message library (originally based on http://codeigniter.com/wiki/Message/)
 * is a fairly straightforward Codeigniter library that makes it easier
 * to set different type of messages such as errors and notifications. Traditionally you'd
 * use $this->session->set_flashdata() but this can result in a lot of code for something
 * so simple. The Message library in many ways works similar to the session library but is
 * meant to be easier to use and reduce the amount of code.
 *
 * A basic example of using the Message library looks like the following (this should be
 * executed in a controller):
 *
 *   $this->load->library('message');
 *   $this->message->set('info', 'Hello, world!');
 *
 * I've skipped the configuration part (for now) but as you can see it only takes 2 lines
 * of code to set a message. Retrieving this message is quite easy too and looks like the
 * following (this goes into a view):
 *
 *   <?php if ( $this->message->display() ) { echo $this->message->display(); } ?>
 *
 * For more information you should check the README.
 *
 * @author  Jeroen vd. Gulik, Isset Internet Professionals
 * @link    http://isset.nl/
 * @package Message Library
 * @version 1.2
 * @license MIT License
 *
 * Copyright (C) 2010 - 2011, Isset Internet Professionals
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
class Message
{
	/**
	 * Variable containing a reference to the Codeigniter instance.
	 *
	 * @access private
	 * @var    object
	 */
	private $CI;

	/**
	 * Array containing all messages that were set using the set() method.
	 *
	 * @access private
	 * @var    array
	 */
	private $messages = array();

	/**
	 * Prefix to use for all messages.
	 *
	 * @access private
	 * @var    string
	 */
	private $message_prefix	= '';

	/**
	 * Suffix to use for all messages.
	 *
	 * @access private
	 * @var    string
	 */
	private $message_suffix	= '';

	/**
	 * The directory in which all messages are stored.
	 *
	 * @access private
	 * @var    string
	 */
	private $message_folder	= '';

	/**
	 * The view for the current message.
	 *
	 * @access private
	 * @var    string
	 */
	private $message_view	= '';

	/**
	 * The prefix for the messages container.
	 *
	 * @access private
	 * @var    string
	 */
	private $wrapper_prefix	= '';

	/**
	 * The suffix for the messages container.
	 *
	 * @access private
	 * @var    string
	 */
	private $wrapper_suffix	= '';

	/**
	 * Constructor method, called whenever the library is loaded using $this->load->library()
	 *
	 * @access	public
	 * @param   array $config Associative array containing all configuration options.
	 * @return  object
	 */
	public function __construct($config = array())
	{
		$this->CI =& get_instance();
		$this->CI->load->library('session');

		if ($this->CI->session->userdata('_messages'))
		{
			$this->messages = $this->CI->session->userdata('_messages');
		}

		if ( count($config) > 0 )
		{
			$this->initialize($config);
		}

		log_message('debug', "Message Class Initialized");
	}

	/**
	 * Initializes the library by setting all custom configuration options based
	 * on the specified array.
	 *
	 * @example
	 *  $this->load->library('message');
	 *  $this->message->initialize(array(
	 *      'wrapper_prefix' => 'container_'
	 *  ));
	 *
	 * @access public
	 * @param  array $config Associative array containing all user-defined configuration options.
	 * @return void
	 */
	public function initialize($config = array())
	{
		foreach ( $config as $key => $val )
		{
			if ( isset($this->$key) )
			{
				$this->$key = $val;
			}
		}
	}

	/**
	 * Adds a new message to the internal storage. The first argument is either a string
	 * or an array of message groups (e.g. "error" or "info") and the second argument
	 * a value for each of these messages. If the first argument is set as an array
	 * the second argument will be ignored.
	 *
	 * @example
	 *  $this->message->set('error', 'Bummer! Somebody sat on the tubes!');
	 *  $this->message->set(array(
	 *    'error' => 'Woops, seems something is broken!',
	 *    'info'  => 'Hello, world!' 
	 *  ));
	 *
	 * @access	public
	 * @param	array/string $groups Either a string containing the group name or an
	 * array of key/value combinations of each group and it's value.
	 * @param	string $message The message to display whenever the first argument
	 * was a string.
	 * @return	void
	 */
	public function set($groups, $message = NULL)
	{
		if ( is_string($groups) )
		{
			$groups = array($groups => $message);
		}

		if ( count($groups) > 0 )
		{
			foreach ( $groups as $group => $value )
			{
				// Let's skip empty messages
				if ( empty($value) )
				{
					continue;
				}

				// Technically not always required but it ensures the group is always there.
				if ( !isset($this->messages[$group]) )
				{
					$this->messages[$group] = array();
				}

				$this->messages[$group][] = $value;
			}

			$this->CI->session->set_userdata('_messages', $this->messages);
		}
	}

	/**
	 * Fetches all messages for the specified group. If no group was found this
	 * method will return FALSE instead of an array.
	 *
	 * @example
	 *  $this->library->get('error');
	 *
	 * @access	public
	 * @param	string $group The name of the group you want to retrieve.
	 * @return	array/boolean
	 */
	public function get($group = FALSE)
	{
		// Do we have something to show?
		if ( count($this->messages) == 0 )
		{
			return FALSE;
		}

		// If a group is specified we'll return it, otherwise we'll return all items
		if ( isset($group) AND !empty($group) )
		{
			if ( isset($this->messages[$group]) )
			{
				return $this->messages[$group];
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return $this->messages;
		}
	}

	/**
	 * Retrieves all messages and formats them using the corresponding views for each message.
	 * If you want to return the raw messages use the get() method instead.
	 *
	 * @example
	 *  echo $this->message->display('error');
	 *
	 * @access	public
	 * @param	string $group The name of the group you want to output.
	 * @return	string
	 */
	public function display($group = FALSE)
	{
		// do we have something to show?
		if ( $this->get($group) === FALSE) 
		{
			return FALSE;
		}

		// Let's format the data
		$output = $this->format_output($group);

		// Clear our message cache
		$this->CI->session->unset_userdata('_messages');

		return $output;
	}

	/**
	 * Formats a group of messages based on the corresponding view.
	 *
	 * @access	private
	 * @param	string $by_group The name of the group to format.
	 * @return	string
	 */
	private function format_output($by_group = FALSE)
	{
		$output = NULL;

		// loop through the groups and cascade through format options
		foreach ( $this->messages as $group => $messages )
		{
			// was a group set? if so skip all groups that do not match
			if ( $by_group !== FALSE && $group != $by_group )
			{
				continue;
			}

			// does a view partial exist?
			if ( file_exists(APPPATH.'views/'.$this->message_folder.$group.'_view' . EXT) )
			{
				$output .= $this->CI->load->view($this->message_folder.$group.'_view', array('messages'=>$messages), TRUE);
			}
			// does a default view partial exist?
			elseif ( file_exists(APPPATH.'views/'.$this->message_folder.$this->message_view.'_view' . EXT) )
			{
				$output .= $this->CI->load->view($this->message_folder.$this->message_view.'_view', array('messages'=>$messages), TRUE);
			}
			// fallback to default values (possibly set by config)
			else
			{
				$output .= $this->wrapper_prefix . PHP_EOL;

				foreach ( $messages as $msg )
				{
					$output .= $this->message_prefix . $msg . $this->message_suffix . PHP_EOL;
				}

				$output .= $this->wrapper_suffix . PHP_EOL;
			}
		}

		return $output;
	}
}

/* End of file Message.php */
/* Location: ./application/libraries/Message.php */