<?php

/**
 * Reverse bidding system Home Class
 *
 * Permits admin to set the site settings like site title,site mission,site offline status.
 *
 * @package		Reverse bidding system
 * @subpackage	Controllers
 * @category	Common Display 

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

 */
class Home extends Controller {

    //Global variable  
    public $outputData;  //Holds the output data for each view
    public $loggedInUser;

    /**
     * Constructor 
     *
     * Loads language files and models needed for this controller
     */
    function Home() {
        parent::Controller();

        //Get Config Details From Db
        $this->config->db_config_fetch();
        //Manage site Status 
        if ($this->config->item('site_status') == 1)
            redirect('offline');
        $this->load->model('certificate_model');

        //Load Models Common to all the functions in this controller
        $this->load->model('common_model');
        $this->load->model('skills_model');
        $this->load->model('page_model');

        //Page Title and Meta Tags
        $this->outputData = $this->common_model->getPageTitleAndMetaData();

        //Get Logged In user
        $this->loggedInUser = $this->common_model->getLoggedInUser();
        $this->outputData['loggedInUser'] = $this->loggedInUser;

        //Get Latest Projects
        $limit_latest = $this->config->item('latest_projects_limit');
        $limit3 = array($limit_latest);
        $this->outputData['latestProjects'] = $this->skills_model->getLatestProjects($limit3);

        //language file
        $this->lang->load('enduser/common', $this->config->item('language_code'));
        $this->outputData['current_page'] = 'home';
        $this->load->helper('file');
        $this->load->helper('users');

        $categories = $this->skills_model->getCategories();
        $this->outputData['categories'] = $categories;
    }

//Controller End 
    // --------------------------------------------------------------------

    /**
     * Loads Home page of the site.
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function index() {

        if ($this->input->post('change')) {   //theme change in footer
            $seltheme = $this->input->post('seltheme');
            $themeurl['theme1'] = 'http://demo.cogzidel.in/rbs/';
            $themeurl['theme2'] = 'http://demo.cogzideltemplates.com/free/rbs/rbs1.6/template001/script/skin001/';
            $themeurl['theme3'] = 'http://demo.cogzideltemplates.com/free/rbs/rbs1.6/template002/script/skin002/';

            $this->outputData['themename'] = $seltheme;
            $this->outputData['themeurl'] = $themeurl;

            if ($seltheme != 'theme1') {
                $this->load->view('iframe', $this->outputData);
            } else {
                redirect('home');
            }
        } else {


            //Load Language File For this
            $this->lang->load('enduser/home', $this->config->item('language_code'));

            //Get Categories
            $this->outputData['categories'] = $this->skills_model->getCategories();

            //Get Featured Projects
            $feature_conditions = array('is_feature' => 1);
            $this->outputData['featureProjects'] = $this->skills_model->getProjects($feature_conditions);

            //Get Urgent Projects
            $urgent_conditions = array('is_urgent' => 1);
            $this->outputData['urgentProjects'] = $this->skills_model->getProjects($urgent_conditions);

            $this->outputData['groups'] = $this->skills_model->getGroups();
            $this->outputData['groups_num'] = $this->outputData['groups']->num_rows();
            $this->outputData['groups_row'] = $this->outputData['groups']->row();

            $limit = array('4');
            $this->outputData['topProviders'] = $this->skills_model->getTopsellers();
            $this->outputData['topBuyers'] = $this->skills_model->getTopBuyers();

            //Get Footer content
            $conditions = array('page.is_active' => 1);
            $this->outputData['pages'] = $this->page_model->getPages($conditions);
            $this->load->view('home', $this->outputData);
        }
    }

//Function Index End
    // --------------------------------------------------------------------

    /**
     * Listing projects
     *
     * @access	public
     * @param	nil
     * @return	void
     */
    function listProjects() {
        //Load Language File For this
        $this->lang->load('enduser/home', $this->config->item('language_code'));
        $type = $this->uri->segment('3');
        if ($type == '')
            $type = 'latest';
        if ($type == 'latest') {
            //Get Latest Projects
            $limit_latest = $this->config->item('latest_projects_limit');
            $limit3 = array($limit_latest);
            $this->outputData['listProjects'] = $this->skills_model->getLatestProjects($limit3);
            $this->outputData['title'] = 'Latest Projects';
            $this->outputData['viewall'] = 'all';
        } elseif ($type == 'featured') {
            //Get Featured Projects
            $feature_conditions = array('is_feature' => 1, 'project_status' => '0');
            $this->outputData['listProjects'] = $this->skills_model->getProjects($feature_conditions);
            $this->outputData['title'] = 'Featured Projects';
            $this->outputData['viewall'] = 'is_feature';
        } elseif ($type == 'urgent') {
            //Get Urgent Projects
            $urgent_conditions = array('is_urgent' => 1, 'project_status' => '0');
            $this->outputData['listProjects'] = $this->skills_model->getProjects($urgent_conditions);
            $this->outputData['title'] = 'Urgent Projects';
            $this->outputData['viewall'] = 'is_urgent';
        } elseif ($type == 'high') {
            //Get Urgent Projects
            $urgent_conditions = array('projects.project_status' => '0', 'budget_max >=' => '500');
            $order = array('budget_max', 'DESC');
            $this->outputData['listProjects'] = $this->skills_model->getProjects($urgent_conditions, NULL, NULL, NULL, $order);
            $this->outputData['title'] = 'High Budget Projects';
            $this->outputData['viewall'] = 'high_budget';
        }
        $this->load->view('listProjects', $this->outputData);
    }

//listProjects end
//-----------------------------------------------------------------------------------

    /* Function find
     *
     * Access Private
     * Parem search keyword
     *
     */

    function checkFind() {

        $type = $this->uri->segment('3');

        switch ($type) {
            case 'work':
                $urgent_conditions = array('project_status' => '0');
                $openProjects = $this->skills_model->getProjects($urgent_conditions);
                $this->outputData['numProjects'] = $openProjects->num_rows();
                $this->outputData['popular'] = $this->skills_model->getPopularSearch('work');
                $this->load->view('findWork', $this->outputData);
                break;
            case 'prof':
                $conditions = array('users.role_id' => '2');
                $providers = $this->user_model->getUsers($conditions);
                $this->outputData['numProviders'] = $providers->num_rows();
                $this->outputData['popular'] = $this->skills_model->getPopularSearch('user');
                $this->load->view('findProf', $this->outputData);
                break;
            case 's_buyer':
                echo "find buyers in the same area";
                break;
            case 's_seller':
                echo "find sellers in the same area";
                break;
        }
    }

// Function end
//---------------------------------------------------------------------------------------	

    /* Get categories
     * access private
     * param keyword
     */

    function getCate() {
        $catid = $this->uri->segment('3', '0');
        $conditions = array('categories.group_id' => $catid);
        $this->outputData['categories'] = $this->skills_model->getCategories($conditions);
        $this->load->view('categoryList', $this->outputData);
    }

}

//End  Buyer Class
//------------------------------------------------------------------------------

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
?>
