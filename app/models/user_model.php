<?php

/**
 * Reverse bidding system User_model Class
 *
 * helps to achieve common tasks related to the site like flash message formats,pagination variables.
 *
 * @package		Reverse bidding system
 * @subpackage	Models
 * @category	Common_model 

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
class User_model extends Model {

    /**
     * Constructor 
     *
     */
    function User_model() {
        parent::Model();
    }

//Controller End
    // --------------------------------------------------------------------

    /**
     * Set Style for the flash messages
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
    function getRoleId($role_name='') {

        $conditions = array('role_name' => $role_name);
        $this->db->where($conditions);
        $this->db->select('id');
        $query = $this->db->get('roles');
        $row = $query->row();
        return $row->id;
    }

//End of flash_message Function
    // --------------------------------------------------------------------

    /**
     * Set Style for the flash messages
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
    function getRoles() {

        $this->db->select('id,role_name');
        $query = $this->db->get('roles');
        return $query->result();
    }

//End of flash_message Function
    // --------------------------------------------------------------------

    /**
     * create user
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
    function createUser($insertData=array()) {
        $this->db->insert('users', $insertData);
    }

//End of createUser Function
    // --------------------------------------------------------------------

    /**
     * insert User Contacts
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
    function insertUserContacts($insertData=array()) {
        $this->db->insert('user_contacts', $insertData);
    }

//End of insertUserContacts Function
    // --------------------------------------------------------------------

    /**
     * insert User details for invitation
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
    function insertSellerInvitation($insertData=array()) {
        $this->db->insert('project_invitation', $insertData);
    }

//End of insertUserContacts Function
    // --------------------------------------------------------------------

    /**
     * Update SellerInvitation
     *
     * @access	private
     * @param	array	an associative array of insert values
     * @return	void
     */
    function updateSellerInvitation($updateKey=array(), $updateData=array()) {
        $this->db->update('project_invitation', $updateData, $updateKey);
    }

//End of updateSellerInvitation Function 
    // --------------------------------------------------------------------

    /**
     * getSellerInvitation
     *
     * @access	private
     * @param	nil
     * @return	object	object with result set
     */
    function getSellerInvitation($conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);

        $this->db->from('project_invitation');
        $this->db->select('project_invitation.id,project_invitation.project_id,project_invitation.sender_id,project_invitation.receiver_id,project_invitation.invite_date');

        $result = $this->db->get();
        return $result;
    }

//End of getSellerInvitation Function
    // --------------------------------------------------------------------

    /**
     * insert Portfolios
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
    function insertPortfolio($insertData=array()) {
        $this->db->insert('portfolio', $insertData);
    }

//End of insertUserContacts Function
    // --------------------------------------------------------------------

    /**
     * Update Portfolio
     *
     * @access	private
     * @param	array	an associative array of insert values
     * @return	void
     */
    function updatePortfolio($updateKey=array(), $updateData=array()) {
        $this->db->update('portfolio', $updateData, $updateKey);
    }

//End of updatePortfolio Function 
    // --------------------------------------------------------------------

    /**
     * insert User Categorys
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
    function insertUserCategories($insertData=array()) {
        $this->db->insert('user_categories', $insertData);
    }

//End of insertUserContacts Function
    // --------------------------------------------------------------------

    /**
     * create userBalanceAccount 
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
    function createUserBalance($insertBalance=array()) {
        $this->db->insert('user_balance', $insertBalance);
    }

//End of createUser Function
    // --------------------------------------------------------------------

    /**
     * Update users
     *
     * @access	private
     * @param	array	an associative array of insert values
     * @return	void
     */
    function updateUser($updateKey=array(), $updateData=array()) {
        $this->db->update('users', $updateData, $updateKey);
    }

//End of editGroup Function 
    // --------------------------------------------------------------------

    /**
     * Update bans
     *
     * @access	private
     * @param	array	an associative array of insert values
     * @return	void
     */
    function updateBan($updateKey=array(), $updateData=array()) {
        $this->db->update('bans', $updateData, $updateKey);
    }

//End of editGroup Function
    // --------------------------------------------------------------------

    /**
     * Update usersCategories
     *
     * @access	private
     * @param	array	an associative array of insert values
     * @return	void
     */
    function updateCategories($updateKey=array(), $updateData1=array()) {
        $this->db->update('user_categories', $updateData1, $updateKey);
    }

//End of editGroup Function 

    /**
     * Update usersContent
     *
     * @access	private
     * @param	array	an associative array of insert values
     * @return	void
     */
    function updateUserContacts($userContacts=array(), $updateKey2) {
        //pr($userContacts);exit;
        $this->db->update('user_contacts', $userContacts, $updateKey2);
    }

//End of editGroup Function 
    // --------------------------------------------------------------------

    /**
     * Get Userslist
     *
     * @access	private
     * @param	nil
     * @return	object	object with result set
     */
    function getUserslist($conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);

        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');

        $this->db->select('users.id,roles.role_name,users.user_name,users.name,users.role_id,users.country_symbol,users.message_notify,users.password,users.email,users.city,users.state,users.profile_desc,users.rate,users.project_notify,users.user_status,users.activation_key,users.created');
        $result = $this->db->get();
        return $result;
    }

//End of getUsers Function
    // --------------------------------------------------------------------

    /**
     * Get Users
     *
     * @access	private
     * @param	array	conditions to fetch data
     * @return	object	object with result set
     */
    function getUsers($conditions=array(), $fields='') {

        if (count($conditions) > 0)
            $this->db->where($conditions);

        //print_r($conditions);

        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('users.id,roles.role_name,users.user_name,users.name,users.role_id,users.country_symbol,users.message_notify,users.password,users.email,users.city,users.state,users.profile_desc,users.rate,users.project_notify,users.user_status,users.activation_key,users.created,users.last_activity,users.num_reviews,users.user_rating,users.logo,users.refid');

        $result = $this->db->get();
        return $result;
    }

//End of getUsers Function

    function getUsers_bal($conditions=array(), $fields='') {
        if (count($conditions) > 0)
            $this->db->where($conditions);


        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');
        $this->db->join('user_balance', 'users.id = user_balance.user_id', 'left');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('users.id,roles.role_name,users.user_name,users.name,users.role_id,users.country_symbol,users.message_notify,users.password,users.email,users.city,users.state,users.profile_desc,users.rate,users.project_notify,users.user_status,users.activation_key,users.created,users.last_activity,users.num_reviews,users.user_rating,users.logo,user_balance.amount,users.refid');

        $result = $this->db->get();
        return $result;
    }

    function getUsers_balance($conditions=array(), $fields='', $like=array(), $limit=array(), $orderby = array()) {
        if (is_array($conditions) and count($conditions) > 0)
            $this->db->where($conditions);

        //Check For like statement
        if (is_array($like) and count($like) > 0)
            $this->db->like($like);


        //Check For Limit	
        if (is_array($limit)) {
            if (count($limit) == 1)
                $this->db->limit($limit[0]);
            else if (count($limit) == 2)
                $this->db->limit($limit[0], $limit[1]);
        }
        //pr($orderby);
        //Check for Order by
        if (is_array($orderby) and count($orderby) > 0)
            $this->db->order_by($orderby[0], $orderby[1]);


        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');
        $this->db->join('user_balance', 'users.id = user_balance.user_id', 'left');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('users.id,roles.role_name,users.user_name,users.name,users.role_id,users.country_symbol,users.message_notify,users.password,users.email,users.city,users.state,users.profile_desc,users.rate,users.project_notify,users.user_status,users.activation_key,users.created,users.last_activity,users.num_reviews,users.user_rating,users.logo,user_balance.amount');

        $result = $this->db->get();
        return $result;
    }

    // --------------------------------------------------------------------

    /**
     * Get Portfolio
     *
     * @access	private
     * @param	array	conditions to fetch data
     * @return	object	object with result set
     */
    function getPortfolio($conditions=array(), $fields='') {
        if (count($conditions) > 0)
            $this->db->where($conditions);


        $this->db->from('portfolio');
        $this->db->join('users', 'users.id = portfolio.user_id', 'left');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('users.id as userid,users.user_name,portfolio.title,portfolio.id,portfolio.description,portfolio.main_img,portfolio.attachment1 ,portfolio.categories,portfolio.attachment2');

        $result = $this->db->get();
        return $result;
    }

//End of getUsers Function
    // --------------------------------------------------------------------

    /**
     * Get Bans
     *
     * @access	private
     * @param	array	conditions to fetch data
     * @return	object	object with result set
     */
    function getBans($conditions=array(), $fields='') {
        if (count($conditions) > 0)
            $this->db->where($conditions);


        $this->db->from('bans');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('bans.ban_type,bans.ban_value,bans.id');

        $result = $this->db->get();
        return $result;
    }

//End of getBans Function

    function getBansuser($conditions=array(), $fields='', $like=array(), $limit=array(), $orderby = array()) {
        //Check For Conditions
        if (is_array($conditions) and count($conditions) > 0)
            $this->db->where($conditions);

        //Check For like statement
        if (is_array($like) and count($like) > 0)
            $this->db->like($like);

        //Check For Limit	
        if (is_array($limit)) {
            if (count($limit) == 1)
                $this->db->limit($limit[0]);
            else if (count($limit) == 2)
                $this->db->limit($limit[0], $limit[1]);
        }
        //pr($orderby);
        //Check for Order by
        if (is_array($orderby) and count($orderby) > 0)
            $this->db->order_by($orderby[0], $orderby[1]);


        $this->db->from('bans');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('bans.ban_type,bans.ban_value,bans.id');

        $result = $this->db->get();
        return $result;
    }

//End of getBans Function

    function getSuspenduser($conditions=array(), $fields='', $like=array(), $limit=array(), $orderby = array()) {
        //Check For Conditions
        if (is_array($conditions) and count($conditions) > 0)
            $this->db->where($conditions);

        //Check For like statement
        if (is_array($like) and count($like) > 0)
            $this->db->like($like);

        //Check For Limit	
        if (is_array($limit)) {
            if (count($limit) == 1)
                $this->db->limit($limit[0]);
            else if (count($limit) == 2)
                $this->db->limit($limit[0], $limit[1]);
        }
        //pr($orderby);
        //Check for Order by
        if (is_array($orderby) and count($orderby) > 0)
            $this->db->order_by($orderby[0], $orderby[1]);


        $this->db->from('suspend');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('suspend.suspend_type,suspend.suspend_value,suspend.id');

        $result = $this->db->get();
        return $result;
    }

//End of getBans Function

    function getSuspend($conditions=array(), $fields='') {
        if (count($conditions) > 0)
            $this->db->where($conditions);


        $this->db->from('suspend');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('suspend.suspend_type,suspend.suspend_value,suspend.id');

        $result = $this->db->get();
        return $result;
    }

//End of getBans Function	 
    // --------------------------------------------------------------------

    /**
     * Get User Contacts
     *
     * @access	private
     * @param	array	conditions to fetch data
     * @return	object	object with result set
     */
    function getUserContacts($conditions=array(), $fields='') {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        $this->db->from('user_contacts');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('user_contacts.id,user_contacts.msn,user_contacts.gtalk,user_contacts.yahoo,user_contacts.skype');

        $result = $this->db->get();
        return $result;
    }

//End of getUserContacts Function
    //---------------------------------------------------------------------------------------------------------------//

    /**
     * Get User Categories
     *
     * @access	private
     * @param	array	conditions to fetch data
     * @return	object	object with result set
     */
    function getUserCategories($conditions=array(), $fields='') {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        $this->db->from('user_categories');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('user_categories.user_categories');

        $result = $this->db->get();
        return $result;
    }

//End of getUserContacts Function
    //---------------------------------------------------------------------------------------------------------------//

    /**
     * Get User Categories
     *
     * @access	private
     * @param	array	conditions to fetch data
     * @return	object	object with result set
     */
    function getUsersWithCategories($conditions=array(), $fields='') {
        if (count($conditions) > 0)
            $this->db->where($conditions);

        $this->db->from('users');
        $this->db->join('user_categories', 'user_categories.user_id = users.id', 'left');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('users.id,users.email,user_categories.user_categories,users.user_name');

        $result = $this->db->get();
        return $result;
    }

//End of getUserContacts Function
    // --------------------------------------------------------------------

    /**
     * Get Users
     *
     * @access	private
     * @param	array	conditions to fetch data
     * @return	object	object with result set
     */
    function allowToPostProject($creator_id = false) {
        
    }

//End of getCategories Function	 

    /**
     * Loads userslist for transfer money
     *
     * @access	private
     * @param	nil
     * @return	void
     */
    function userProjectdata($conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);

        $this->db->select('users.id,users.user_name,users.role_id');
        $result = $this->db->get('users');
        return $result;
    }

//Function logout End
    /**
     * 
     * Get the favourite and blocked users list from user_list atable
     * @access	private
     * @return	favourite and blocked users list
     */

    function getFavourite($conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        $this->db->from('user_list');
        $this->db->select('user_list.id,user_list.creator_id,user_list.user_id,user_list.user_name,user_list.user_role');
        $result = $this->db->get();
        //pr($result);

        return $result;
    }

//End of flash_message Function
    // --------------------------------------------------------------------

    /**
     * insert User details for favourite users
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
    function addFavourite($insertData=array()) {
        $this->db->insert('user_list', $insertData);
    }

//End of insertUserContacts Function 

    /**
     * Update user_list for favourite users and blockedusers
     *
     * @access	private
     * @param	array	an associative array of update values
     * @return	void
     */
    function updateFavourite($updateData=array(), $conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        $this->db->update('user_list', $updateData);
    }

//End of editGroup Function 
    // --------------------------------------------------------------------

    /**
     * delete from user_list for favourite users and blockedusers
     *
     * @access	private
     * @param	array	an associative array of delete values
     * @return	void
     */
    function deleteFavourite($conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        $this->db->delete('user_list');
    }

//End of editGroup Function 
    // --------------------------------------------------------------------

    /**
     * delete portfolios
     *
     * @access	private
     * @param	array	an associative array of delete values
     * @return	void
     */
    function deletePortfolio($conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        $this->db->delete('portfolio');
    }

//End of editGroup Function 
    // --------------------------------------------------------------------

    /**
     * delete ban list
     *
     * @access	private
     * @param	array	an associative array of delete values
     * @return	void
     */
    function deleteBan($id=0, $conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        else
            $this->db->where('id', $id);
        $this->db->delete('bans');
    }

//End of deleteBan Function 

    function deleteSuspend($id=0, $conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        else
            $this->db->where('id', $id);
        $this->db->delete('suspend');
    }

//End of deleteBan Function 
    // --------------------------------------------------------------------

    /**
     * delete user
     *
     * @access	private
     * @param	array	an associative array of delete values
     * @return	void
     */
    function deleteUser($id=0, $conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        else
            $this->db->where('id', $id);
        $this->db->delete('users');
    }

//End of editGroup Function

    function deleteBookmark($id=0, $conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        else
            $this->db->where('id', $id);
        $this->db->delete('bookmark');
    }

//End of editGroup Function

    function deleteFile($id=0, $conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        else
            $this->db->where('id', $id);
        $this->db->delete('files');
    }

//End of editGroup Function

    function deleteBalance($id=0, $conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        else
            $this->db->where('id', $id);
        $this->db->delete('user_balance');
    }

//End of editGroup Function

    function deleteCategory($id=0, $conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        else
            $this->db->where('id', $id);
        $this->db->delete('user_categories');
    }

//End of editGroup Function

    function deleteContact($id=0, $conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        else
            $this->db->where('id', $id);
        $this->db->delete('user_contacts');
    }

//End of editGroup Function

    function deleteUserlist($id=0, $conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        else
            $this->db->where('id', $id);
        $this->db->delete('user_list');
    }

//End of editGroup Function
    // --------------------------------------------------------------------

    /**
     * create ban
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
    function insertBan($insertData=array()) {
        $this->db->insert('bans', $insertData);
    }

//End of createUser Function
    // --------------------------------------------------------------------

    /**
     * 
     * Get the favourite and blocked users list from user_list atable
     * @access	private
     * @return	favourite and blocked users list
     */
    function getNumUsersByMonth($mon, $year, $rid) {
        $query = "SELECT count(*) as cnt FROM users WHERE role_id = $rid AND FROM_UNIXTIME(created, '%c,%Y') = '$mon,$year' ";
        $que = $this->db->query($query);

        $res = $que->row();

        return $res->cnt;
    }

//End of flash_message Function
    // --------------------------------------------------------------------

    /**
     * select from user_list from admin
     *
     * @access	private
     * @param	array	an associative array of delete values
     * @return	void
     */
    function viewAdmin($conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        $this->db->from('admins');
        $this->db->select('admins.id,admins.admin_name,admins.password');
        $result = $this->db->get();

        return $result->result();
    }

//End of Function 

    function viewAdminuser($conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        $this->db->from('admins');
        $this->db->select('admins.id,admins.admin_name,admins.password');
        $result = $this->db->get();

        return $result;
    }

//End of Function 

    function viewAdmins($conditions=array(), $fields='', $like=array(), $limit=array(), $orderby = array()) {
        //Check For Conditions
        if (is_array($conditions) and count($conditions) > 0)
            $this->db->where($conditions);

        //Check For like statement
        if (is_array($like) and count($like) > 0)
            $this->db->like($like);


        //Check For Limit	
        if (is_array($limit)) {
            if (count($limit) == 1)
                $this->db->limit($limit[0]);
            else if (count($limit) == 2)
                $this->db->limit($limit[0], $limit[1]);
        }
        //pr($orderby);
        //Check for Order by
        if (is_array($orderby) and count($orderby) > 0)
            $this->db->order_by($orderby[0], $orderby[1]);

        $this->db->from('admins');
        $this->db->select('admins.id,admins.admin_name,admins.password');
        $result = $this->db->get();
        return $result->result();
    }

//End of Function 
    // --------------------------------------------------------------------

    /**
     * insert User details for admin
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
    function addAdmin($insertData=array()) {
        $result = $this->db->insert('admins', $insertData);
        return $result;
    }

//End of Function 

    function getAdmin($conditions=array(), $fields='') {
        if (count($conditions) > 0)
            $this->db->where($conditions);


        $this->db->from('admins');

        if ($fields != '')
            $this->db->select($fields);
        else
            $this->db->select('admins.id,admins.admin_name,admins.password');

        $result = $this->db->get();
        return $result;
    }

//End of getBans Function

    /**
     * Update user_list for admin
     *
     * @access	private
     * @param	array	an associative array of update values
     * @return	void
     */
    function updateAdmin($conditions=array(), $updateData=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        $result = $this->db->update('admins', $updateData);
        return $result;
    }

//End of Function 
    // --------------------------------------------------------------------

    /**
     * delete from user_list for admin
     *
     * @access	private
     * @param	array	an associative array of delete values
     * @return	void
     */
    function deleteAdmin($conditions=array()) {
        if (count($conditions) > 0)
            $this->db->where($conditions);
        $result = $this->db->delete('admins');
        return $result;
    }

//End of Function 
    // --------------------------------------------------------------------

    function getUsersfromusername($condition='') {

        $query = 'SELECT * FROM `users` WHERE ' . $condition;
        //$this->db->where($condition);
        //$this->db->select('id,email,user_name');
        //$this->db->from('users');
        $result = $this->db->query($query);
        return($result);
    }

    function addRemerberme($insertData=array(), $expire) {

        $this->auth_model->setUserCookie('uname', $insertData['username'], $expire);
        $this->auth_model->setUserCookie('pwd', $insertData['password'], $expire);
    }

    function removeRemeberme() {

        $this->auth_model->clearUserCookie(array('uname', 'pwd'));
    }

    /**
     * create user
     *
     * @access	public
     * @param	string	the type of the flash message
     * @param	string  flash message 
     * @return	string	flash message with proper style
     */
}

// End User_model Class

/* End of file User_model.php */
/* Location: ./app/models/User_model.php */
?>