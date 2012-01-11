	  <!--POST PROJECT-->
<div align="right"><b><font color="#6b80a1"><?php echo $this->lang->line('Local time');?> </font><?php echo show_date(get_est_time()); ?></b></div>
      <div class="slidetabsmenu">
	  <?php if(!isset($innerClass)) { $innerClass=''; } ?>
		<ul>
		 	<li class="<?php if(isset($innerClass1)) { echo $innerClass1;}?>"><a href="<?php  echo site_url('messages/viewMail'); ?>"><span><?php echo $this->lang->line('Mail');?></span></a></li>
		 	<li class="<?php if(isset($innerClass2)) { echo $innerClass2;}?>"><a href="<?php echo site_url('deposit'); ?>"><span><?php echo $this->lang->line('Deposit Money');?></span></a></li>
			<li class="<?php if(isset($innerClass3)) { echo $innerClass3;}?>"><a href="<?php echo site_url('transfer'); ?>"><span><?php echo $this->lang->line('Transfer Money');?></span></a></li>
		 	<li class="<?php if(isset($innerClass4)) { echo $innerClass4;}?>"><a href="<?php echo site_url('withdraw'); ?>"><span><?php echo $this->lang->line('Withdraw Money');?></span></a></li>
			<li class="<?php if(isset($innerClass9)) { echo $innerClass9;}?>"><a href="<?php echo site_url('affiliate/manageAffiliates'); ?>"><span><?php echo $this->lang->line('Affiliate Center');?></span></a></li>				
		 	
			 <?php  if($loggedInUser->role_id == '1')	 
			 { ?>
		      <li class="<?php if(isset($innerClass5)) { echo $innerClass5;}?>"><a href="<?php echo site_url('escrow'); ?>"><span><?php echo $this->lang->line('Escrow');?></span></a></li>	
		    <?php } ?>
			
		 	<li class="<?php if(isset($innerClass6)) { echo $innerClass6;}?>"><a href="<?php echo site_url('file'); ?>"><span><?php echo $this->lang->line('File Manager');?></span></a></li>									
		 	<li class="<?php if(isset($innerClass7)) { echo $innerClass7;}?>"><a href="<?php echo site_url('userList'); ?>"><span><?php echo $this->lang->line('User List');?></span></a></li>			
		 	<li class="<?php if(isset($innerClass8)) { echo $innerClass8;}?>"><a href="<?php echo site_url('project/invoice'); ?>"><span><?php echo $this->lang->line('Invoice');?></span></a></li>						
		 </ul>
		</div>
