<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
 <!--MAIN-->
    <div id="main">
      <!--COL-RIGHT-->
     
      <!--CONTENT-->
      <div class="content">
        <h2><b><?php echo $this->lang->line('Dashboard'); ?></b></h2>
        <h3 class="clsNoborder"><b><?php echo $this->lang->line('Latest Activity'); ?></b></h3>
        <div id="selLatest">
          <div class="selQuickStatus clearfix">
            <div class="selQuickStatusleft clsFloatLeft">
      <p><img src="<?php echo image_url('chat.gif'); ?>" height="40" width="45" alt="img" /></p>
            </div>         
            <div class="selQuickStatusRight clsFloatRight">
              <h2><b><span style="padding-left:20px; float:right;"><?php echo $this->lang->line('Admin Balance'); ?>:$<?php if(isset($adminBalance)) echo sprintf("%01.2f",$adminBalance); else echo '0.00'; ?></span> </b><?php echo $this->lang->line('Quick Status'); ?></h2>
             <ul class="clearfix">
             <li class="clsMember clear"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('Total Users'); ?> :</td> <td width="10%"><?php if(isset($buyers)) echo $buyers+$programmers; ?></td> <td width="30%"><a href="<?php echo admin_url('users/viewUsers'); ?>"><?php echo $this->lang->line('Members'); ?></a></td></tr></table></li>
			 
             <li class="clspros"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('Total Buyers'); ?> :</td> <td width="10%"><?php if(isset($buyers)) echo $buyers; ?></td><td width="30%"><a href="<?php echo admin_url('users/viewBuyeruser'); ?>"> <?php echo $this->lang->line('Members'); ?></a></td></tr></table></li>
			 
             <li class="clspros clear"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('Total Providers'); ?> :</td> <td width="10%"> <?php if(isset($programmers)) echo $programmers; ?></td><td width="30%"><a href="<?php echo admin_url('users/viewProgrammeruser'); ?>"> <?php echo $this->lang->line('Members'); ?></a></td></tr></table></li>
             
			  <li class="clsToday"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('Today'); ?> : </td> <td width="10%"><?php if(isset($today)) echo $today; ?></td><td width="30%"><a href="<?php echo admin_url('skills/todayProjects'); ?>">  <?php echo $this->lang->line('Projects'); ?></a></td></tr></table></li>
			  
                <li class="clsWeek clear"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('This Week'); ?> :</td> <td width="10%"> <?php if(isset($week)) echo $week; ?></td><td width="30%"><a href="<?php echo admin_url('skills/thisWeek'); ?>"> <?php echo $this->lang->line('Projects'); ?> </a></td></tr></table></li>
                 <li class="clsMonth"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('This Month'); ?> :</td> <td width="10%"><?php if(isset($month)) echo $month; ?></td><td width="30%"><a href="<?php echo admin_url('skills/thisMonth'); ?>"><?php echo $this->lang->line('Projects'); ?></a></td></tr></table></li> 
				          
				<li class="clsYear clear"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('This Year'); ?> : </td> <td width="10%"><?php if(isset($year)) echo $year; ?></td><td width="30%"><a href="<?php echo admin_url('skills/thisYear'); ?>">  <?php echo $this->lang->line('Projects'); ?></a></td></tr></table></li>
				
                <li class="clsOpenPros"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('Total Open Projects'); ?> :</td> <td width="10%"> <?php if(isset($open_projects)) echo $open_projects; ?></td><td width="30%"><a href="<?php echo admin_url('skills/openProjects'); ?>"><?php echo $this->lang->line('Projects'); ?></a></td></tr></table></li>
				 
                 <li class="clsClosedprojects clear"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('Total Closed Projects'); ?> : </td> <td width="10%"><?php if(isset($closed_projects)) echo $closed_projects; ?></td><td width="30%"><a href="<?php echo admin_url('skills/closedProjects'); ?>"> <?php echo $this->lang->line('Projects'); ?> </a></td></tr></table></li>

	              	 <li class="clsWidthdraw"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('Latest Open Projects'); ?> : </td> <td width="10%"><?php if(isset($open)) echo $open; ?></td><td width="30%"><a href="<?php echo admin_url('skills/todayOpen'); ?>"> <?php echo $this->lang->line('Projects'); ?></a></td></tr></table></li>
				 
                 <li class="clsLatestClosed clear"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('Latest Closed projects'); ?> :</td> <td width="10%"> <?php if(isset($closed)) echo $closed; ?></td><td width="30%"><a href="<?php echo admin_url('skills/todayClosed'); ?>"><?php echo $this->lang->line('Projects'); ?></a></td></tr></table></li>

                <li class="clsReport"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('Report Violation'); ?> :</td> <td width="10%"> <?php if(isset($reportViolation)) echo $reportViolation; ?></td><td width="30%"><a href="<?php echo admin_url('skills/reportViolation'); ?>"> <?php echo $this->lang->line('Projects'); ?></a></td></tr></table></li>			
              	
				 
                 <li class="clsOpenPros clear"><table width="300"><tr><td width="60%"><?php echo $this->lang->line('Withdrawal Request'); ?> : </td> <td width="10%"><?php if(isset($withdraw)) echo $withdraw; ?></td><td width="30%"><a href="<?php echo admin_url('payments/releaseWithdraw'); ?>"><?php echo $this->lang->line('View'); ?></a></td></tr></table></li>

				 </ul>
            </div>
          </div>
        </div>
		<div class="clsBottom clearfix"> 
		<div class="clsBottomleft clsFloatLeft">
		<h3 class="clsNoborder"><?php echo $this->lang->line('Version'); ?></h3>
		<ul>
		<li><a href="#"><?php echo $this->lang->line('Installed Version'); ?> - 1.6</a></li>
		</ul>
		</div>
		<div class="clsBottomRight clsFloatRight">
		</div>
      </div>
    </div>
    <!--END OF CONTENT-->
  </div>
  <!--END OF MAIN-->
<?php $this->load->view('admin/footer'); ?>