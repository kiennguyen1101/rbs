 <!--SIDE BAR-->

    <div id="sidebar">
	 <?php 
	 	 //Load Member Login Block
		 if(isLoggedIn() === false || $this->session->userdata('role') == 'admin' || $this->session->userdata('role')=='')
		 {	 
	 	 	$this->load->view('sideBlocks/membersLogin');
		 }	
		  //Load Naigation Block For Buyers - If user logged in
		  if(is_object($loggedInUser) and $loggedInUser->role_name == 'buyer')
		  {
				$this->load->view('sideBlocks/buyerNavigationBlock');
		  }
		  //Load Naigation Block For Programmer - If user logged in
		  if(is_object($loggedInUser) and $loggedInUser->role_name == 'programmer')
		  {
				$this->load->view('sideBlocks/programmerNavigationBlock');
		  }
		  
		 //Load services and features Block
	 	 $this->load->view('sideBlocks/servicesAndFeatures');
		 $this->load->view('sideBlocks/latestProjects');
	 ?>
    </div>
    <!--END OF SIDE BAR-->