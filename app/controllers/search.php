<?php

/**
 * Reverse bidding system Search Class
 *
 * Project related tasks are handled by this controller.
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
class Search extends Controller {

    //Global variable  
    public $outputData;  //Holds the output data for each view
    public $loggedInUser;

    /**
     * Constructor 
     *
     * Loads language files and models needed for this controller
     */
    function Search() {
        parent::Controller();

        //Get Config Details From Db
        $this->config->db_config_fetch();

        //Manage site Status 
        if ($this->config->item('site_status') == 1)
            redirect('offline');


        //Debug Tool
        //$this->output->enable_profiler=true;		
        //Load Models Common to all the functions in this controller
        $this->load->model('common_model');
        $this->load->model('skills_model');
        $this->load->model('user_model');

        //Page Title and Meta Tags
        $this->outputData = $this->common_model->getPageTitleAndMetaData();

        //Get Logged In user
        $this->loggedInUser = $this->common_model->getLoggedInUser();
        $this->outputData['loggedInUser'] = $this->loggedInUser;

        //Get Footer content
        $this->outputData['pages'] = $this->common_model->getPages();

        //Get Latest Projects
        $limit_latest = $this->config->item('latest_projects_limit');
        $limit3 = array($limit_latest);
        $this->outputData['latestProjects'] = $this->skills_model->getLatestProjects($limit3);

        //language file
        $this->lang->load('enduser/common', $this->config->item('language_code'));

        $categories = $this->skills_model->getCategories();
        $this->outputData['categories'] = $categories;
    }

//Constructor End 
// --------------------------------------------------------------------

    /**
     * Loads Buyer signUp page.
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function index() {
        //Load Language
        $this->lang->load('enduser/searchProjects', $this->config->item('language_code'));

        //Load Models
        $this->load->model('search_model');

        //Get Search Parameters
        $keyword = $this->input->get('keyword', true);
        $category = $this->input->get('category', TRUE);
        $this->outputData['keyword'] = $keyword;
        $this->outputData['category'] = $category;
        $page = $this->input->get('p', true);
        if (isset($page) === false or empty($page)) {
            $page = 1;
        }
        $this->outputData['page'] = $page;

        //Get Sorting order
        $field = $this->input->get('field', true);
        $order = $this->input->get('sort', true);
        $this->outputData['order'] = $order;
        $orderby = array();
        if ($field)
            $orderby = array($field, $order);
        if ($this->input->post('customizeDisplay')) {
            //Get Customize data fields
            $this->session->set_userdata('show_cat', $this->input->post('show_cat', true));
            $this->session->set_userdata('show_budget', $this->input->post('show_budget', true));
            $this->session->set_userdata('show_bids', $this->input->post('show_bids', true));
            $this->session->set_userdata('show_avgbid', $this->input->post('show_avgbid', true));
            $this->session->set_userdata('show_status', $this->input->post('show_status', true));
            $this->session->set_userdata('show_date', $this->input->post('show_date', true));
            $this->session->set_userdata('show_desc', $this->input->post('show_desc', true));
            $this->session->set_userdata('show_num', $this->input->post('show_num', true));
        } else {
            $this->session->set_userdata('show_cat', '1');
            $this->session->set_userdata('show_budget', '1');
            $this->session->set_userdata('show_bids', '1');
            $this->session->set_userdata('show_num', '10');
        }
        $page_rows = $this->session->userdata('show_num');
        $max = array($page_rows, ($page - 1) * $page_rows);

        //Match With The Keywords

        if ($this->outputData['keyword'])
            $like = array('projects.description' => $keyword, 'projects.project_name' => $keyword);
        else
            $like = '';
        if ($this->outputData['category'])
            $like1 = array('projects.project_categories' => $this->outputData['category']);
        else
            $like1 = '';

        $projects = $this->search_model->getProjects(NULL, NULL, $like, $max, $orderby, $like1);
        $projects1 = $this->search_model->getProjects(NULL, NULL, $like, NULL, NULL, $like1);

        if ($projects1->num_rows() > 0) {
            $insertData = array();
            $insertData['keyword'] = $keyword;
            $insertData['type'] = 'work';
            $insertData['created'] = get_est_time();

            //Insert keyword for popular search
            $this->skills_model->addPopularSearch($insertData);

            //Page Title and Meta Tags
            $condition_key = array('categories.category_name' => $keyword);
            $result = $this->common_model->getPageTitle($condition_key);

            $result = $result->row();
            if (count($result) > 0) {
                $this->outputData['page_title'] = $this->config->item('site_title') . ' - ' . $result->page_title;
                $this->outputData['meta_keywords'] = $result->page_title;
                $this->outputData['meta_description'] = $result->page_title;
            }
        }
        $this->outputData['projects'] = $projects;
        $this->load->library('pagination');
        $config['base_url'] = $this->config->item('base_url') . "?category=" . $category . "&c=search&keyword=" . $keyword;
        $config['total_rows'] = $projects1->num_rows();
        $config['per_page'] = $page_rows;
        $config['cur_page'] = $page;
        $this->pagination->initialize($config);
        $this->outputData['base_url'] = $config['base_url'];
        $this->outputData['pagination'] = $this->pagination->create_links(false);
        $this->load->view('search/listProjects', $this->outputData);
    }

//Finction Index End
    // --------------------------------------------------------------------

    /**
     * Loads the professionals 
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function professional() {
        //Load Language
        $this->lang->load('enduser/searchProfessional', $this->config->item('language_code'));

        //Load Models
        $this->load->model('search_model');

        //Get Search Parameters
        if ($this->input->get('keyword', true))
            $keyword = $this->input->get('keyword', true);

        if ($this->input->get('category', true))
            $category = $this->input->get('category', true);

        if ($this->uri->segment(3))
            $keyword = $this->uri->segment(3);

        $this->outputData['category'] = $this->input->get('category', true);

        $page = $this->input->get('p', true);
        if (isset($page) === false or empty($page)) {
            $page = 1;
        }
        $this->outputData['page'] = $page;

        //Get Sorting order
        $field = $this->input->get('field', true);
        $order = $this->input->get('sort', true);
        $this->outputData['order'] = $order;
        $orderby = array();
        if ($field)
            $orderby = array($field, $order);
        if ($this->input->post('customizeDisplay')) {
            //Get Customize data fields
            $this->session->set_userdata('show_cat', $this->input->post('show_cat', true));
            $this->session->set_userdata('show_budget', $this->input->post('show_budget', true));
            $this->session->set_userdata('show_bids', $this->input->post('show_bids', true));
            $this->session->set_userdata('show_avgbid', $this->input->post('show_avgbid', true));
            $this->session->set_userdata('show_status', $this->input->post('show_status', true));
            $this->session->set_userdata('show_date', $this->input->post('show_date', true));
            $this->session->set_userdata('show_desc', $this->input->post('show_desc', true));
            $this->session->set_userdata('show_num', $this->input->post('show_num', true));
        } else {
            $this->session->set_userdata('show_cat', '1');
            $this->session->set_userdata('show_budget', '1');
            $this->session->set_userdata('show_bids', '1');
            $this->session->set_userdata('show_num', '10');
        }
        $page_rows = $this->session->userdata('show_num');
        $max = array($page_rows, ($page - 1) * $page_rows);

        //Match With The Keywords
        if ($this->input->get('keyword'))
            $like = array('users.user_name' => $this->input->get('keyword'));
        else
            $like = '';
        if ($this->input->get('category'))
            $like1 = array('user_categories.user_categories' => $this->input->get('category'));
        else
            $like1 = '';

        if ($this->uri->segment(3, 0))
            $conditions = array('users.role_id' => '2', 'users.user_name' => $this->uri->segment(3));
        else
            $conditions = array('users.role_id' => '2');
        $users = $this->search_model->getUsers($conditions, NULL, $like, $max, $orderby, $like1);
        $users1 = $this->search_model->getUsers($conditions, NULL, $like, NULL, NULL, $like1);

        if ($users1->num_rows() > 0 and $this->input->get('keyword', true)) {
            $insertData = array();
            $insertData['keyword'] = $this->input->get('keyword', true);
            $insertData['type'] = 'user';
            $insertData['created'] = get_est_time();

            //Insert keyword for popular search
            $this->skills_model->addPopularSearch($insertData);
        }
        $this->outputData['users'] = $users;
        $this->load->library('pagination');
        if (!isset($keyword))
            $keyword = '';
        if (!isset($category))
            $category = '';
        $config['base_url'] = $this->config->item('base_url') . "?c=search&keyword=" . $keyword . "&category=" . $category . '&m=professional';
        $config['total_rows'] = $users1->num_rows();
        $config['per_page'] = $page_rows;
        $config['cur_page'] = $page;
        $this->pagination->initialize($config);
        $this->outputData['base_url'] = $config['base_url'];
        $this->outputData['pagination'] = $this->pagination->create_links(false);
        $this->load->view('search/listProfessional', $this->outputData);
    }

//Function professional end
//--------------------------------------------------------------------------------------------

    /**
     * Loads Buyer signUp page.
     * @access	public
     * @param	nil
     * @return	void
     */
    function list1() {
        //Load Language
        $this->lang->load('enduser/searchProjects', $this->config->item('language_code'));
        pr($this->input->get('c'));
    }

//Finction list1 End
//----------------------------------------------------------------------------------------------

    /**
     * Loads the buyers 
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function buyer() {
        
         //Load Language
        $this->lang->load('enduser/searchBuyer', $this->config->item('language_code'));
        
        //Load Models
        $this->load->model('search_model');
        $this->load->model('user_model');
        
        //Get Search Parameters
        if ($this->input->get('keyword', true))
            $keyword = $this->input->get('keyword', true);

        if ($this->input->get('category', true))
            $category = $this->input->get('category', true);

        if ($this->uri->segment(3))
            $keyword = $this->uri->segment(3);

        $this->outputData['category'] = $this->input->get('category', true);

        $page = $this->input->get('p', true);
        if (isset($page) === false or empty($page)) {
            $page = 1;
        }
        $this->outputData['page'] = $page;

        //Get Sorting order
        $field = $this->input->get('field', true);
        $order = $this->input->get('sort', true);
        $this->outputData['order'] = $order;
        $orderby = array();
        if ($field)
            $orderby = array($field, $order);
        if ($this->input->post('customizeDisplay')) {
            //Get Customize data fields
            $this->session->set_userdata('show_cat', $this->input->post('show_cat', true));
            $this->session->set_userdata('show_budget', $this->input->post('show_budget', true));
            $this->session->set_userdata('show_bids', $this->input->post('show_bids', true));
            $this->session->set_userdata('show_avgbid', $this->input->post('show_avgbid', true));
            $this->session->set_userdata('show_status', $this->input->post('show_status', true));
            $this->session->set_userdata('show_date', $this->input->post('show_date', true));
            $this->session->set_userdata('show_desc', $this->input->post('show_desc', true));
            $this->session->set_userdata('show_num', $this->input->post('show_num', true));
        } else {
            $this->session->set_userdata('show_cat', '1');
            $this->session->set_userdata('show_budget', '1');
            $this->session->set_userdata('show_bids', '1');
            $this->session->set_userdata('show_num', '10');
        }
        $page_rows = $this->session->userdata('show_num');
        $max = array($page_rows, ($page - 1) * $page_rows);

        //Match With The Keywords
        if ($this->input->get('keyword'))
            $like = array('users.user_name' => $this->input->get('keyword'));
        else
            $like = '';
        if ($this->input->get('category'))
            $like1 = array('user_categories.user_categories' => $this->input->get('category'));
        else
            $like1 = '';
        
        if ($this->uri->segment(3, 0))
            $conditions = array('users.role_id' => '1', 'users.user_name' => $this->uri->segment(3));
        else
            $conditions = array('users.role_id' => '1');
        
        $same_area = $this->input->get('same_area',true);
        if (!is_null($same_area)) { 
            $cond = array('users.id' => $this->loggedInUser->id);
            $user_info = $this->user_model->getUsers($cond);
            $userData = $user_info->row(); 
            $cond = array(                    
                    'country_symbol' => $userData->country_symbol,
                    'state' => $userData->state,
                    'city' => $userData->city,
            );
            
            $conditions = array_merge((array)$conditions,(array)$cond);
            
        }   
        
        
        
        $users = $this->search_model->getUsers($conditions, NULL, $like, $max, $orderby, $like1);
        $users1 = $this->search_model->getUsers($conditions, NULL, $like, NULL, NULL, $like1);
        
        //convert country_symbol (ISO code) to name
        //i.e. US -> United States
        foreach ($users->result() as $user) {
             $country = $this->common_model->getCountries(array('country_symbol' => $user->country_symbol));
             $user->country_name = $country->row()->country_name;
        }
        
         foreach ($users1->result() as $user) {
             $country = $this->common_model->getCountries(array('country_symbol' => $user->country_symbol));
             $user->country_name = $country->row()->country_name;
        }
        
       
 
       
        if ($users1->num_rows() > 0 and $this->input->get('keyword', true)) {
            $insertData = array();
            $insertData['keyword'] = $this->input->get('keyword', true);
            $insertData['type'] = 'user';
            $insertData['created'] = get_est_time();

            //Insert keyword for popular search
            $this->skills_model->addPopularSearch($insertData);
        }
        
        //load data to view
        $this->outputData['users'] = $users;
        
        $this->load->library('pagination');
        if (!isset($keyword))
            $keyword = '';
        if (!isset($category))
            $category = '';
        $config['base_url'] = $this->config->item('base_url') . "?c=search&keyword=" . $keyword . "&category=" . $category . '&m=professional';
        $config['total_rows'] = $users1->num_rows();
        $config['per_page'] = $page_rows;
        $config['cur_page'] = $page;
        $this->pagination->initialize($config);
        $this->outputData['base_url'] = $config['base_url'];
        $this->outputData['pagination'] = $this->pagination->create_links(false);
        $this->load->view('search/listBuyer', $this->outputData);
      
        // $this->load->view('search/listProfessional', $this->outputData);
        
    }
    
    function seller() {
        
         //Load Language
        $this->lang->load('enduser/searchSeller', $this->config->item('language_code'));
        
        //Load Models
        $this->load->model('search_model');
        $this->load->model('user_model');
        
        //Get Search Parameters
        if ($this->input->get('keyword', true))
            $keyword = $this->input->get('keyword', true);

        if ($this->input->get('category', true))
            $category = $this->input->get('category', true);

        if ($this->uri->segment(3))
            $keyword = $this->uri->segment(3);

        $this->outputData['category'] = $this->input->get('category', true);

        $page = $this->input->get('p', true);
        if (isset($page) === false or empty($page)) {
            $page = 1;
        }
        $this->outputData['page'] = $page;

        //Get Sorting order
        $field = $this->input->get('field', true);
        $order = $this->input->get('sort', true);
        $this->outputData['order'] = $order;
        $orderby = array();
        if ($field)
            $orderby = array($field, $order);
        if ($this->input->post('customizeDisplay')) {
            //Get Customize data fields
            $this->session->set_userdata('show_cat', $this->input->post('show_cat', true));
            $this->session->set_userdata('show_budget', $this->input->post('show_budget', true));
            $this->session->set_userdata('show_bids', $this->input->post('show_bids', true));
            $this->session->set_userdata('show_avgbid', $this->input->post('show_avgbid', true));
            $this->session->set_userdata('show_status', $this->input->post('show_status', true));
            $this->session->set_userdata('show_date', $this->input->post('show_date', true));
            $this->session->set_userdata('show_desc', $this->input->post('show_desc', true));
            $this->session->set_userdata('show_num', $this->input->post('show_num', true));
        } else {
            $this->session->set_userdata('show_cat', '1');
            $this->session->set_userdata('show_budget', '1');
            $this->session->set_userdata('show_bids', '1');
            $this->session->set_userdata('show_num', '10');
        }
        $page_rows = $this->session->userdata('show_num');
        $max = array($page_rows, ($page - 1) * $page_rows);

        //Match With The Keywords
        if ($this->input->get('keyword'))
            $like = array('users.user_name' => $this->input->get('keyword'));
        else
            $like = '';
        if ($this->input->get('category'))
            $like1 = array('user_categories.user_categories' => $this->input->get('category'));
        else
            $like1 = '';
        
        if ($this->uri->segment(3, 0))
            $conditions = array('users.role_id' => '2', 'users.user_name' => $this->uri->segment(3));
        else
            $conditions = array('users.role_id' => '2');
        
        $same_area = $this->input->get('same_area',true);
        if (!is_null($same_area)) { 
            $cond = array('users.id' => $this->loggedInUser->id);
            $user_info = $this->user_model->getUsers($cond);
            $userData = $user_info->row(); 
            $cond = array(                    
                    'country_symbol' => $userData->country_symbol,
                    'state' => $userData->state,
                    'city' => $userData->city,
            );
            
            $conditions = array_merge((array)$conditions,(array)$cond);
            
        }   
        
        
        
        $users = $this->search_model->getUsers($conditions, NULL, $like, $max, $orderby, $like1);
        $users1 = $this->search_model->getUsers($conditions, NULL, $like, NULL, NULL, $like1);
        
        //convert country_symbol (ISO code) to name
        //i.e. US -> United States
        foreach ($users->result() as $user) {
             $country = $this->common_model->getCountries(array('country_symbol' => $user->country_symbol));
             $user->country_name = $country->row()->country_name;
        }
        
         foreach ($users1->result() as $user) {
             $country = $this->common_model->getCountries(array('country_symbol' => $user->country_symbol));
             $user->country_name = $country->row()->country_name;
        }
        
       
 
       
        if ($users1->num_rows() > 0 and $this->input->get('keyword', true)) {
            $insertData = array();
            $insertData['keyword'] = $this->input->get('keyword', true);
            $insertData['type'] = 'user';
            $insertData['created'] = get_est_time();

            //Insert keyword for popular search
            $this->skills_model->addPopularSearch($insertData);
        }
        
        //load data to view
        $this->outputData['users'] = $users;
        
        $this->load->library('pagination');
        if (!isset($keyword))
            $keyword = '';
        if (!isset($category))
            $category = '';
        $config['base_url'] = $this->config->item('base_url') . "?c=search&keyword=" . $keyword . "&category=" . $category . '&m=professional';
        $config['total_rows'] = $users1->num_rows();
        $config['per_page'] = $page_rows;
        $config['cur_page'] = $page;
        $this->pagination->initialize($config);
        $this->outputData['base_url'] = $config['base_url'];
        $this->outputData['pagination'] = $this->pagination->create_links(false);
        $this->load->view('search/listSeller', $this->outputData);
    }

}

//End  Project Class
/* End of file Project.php */
/* Location: ./app/controllers/Project.php */
?>