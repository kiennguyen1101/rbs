
<div align="right"><b><font color="#6b80a1"><?php echo $this->lang->line('Local time');?> </font><?php echo show_date(get_est_time()); ?></b></div>
      <div class="slidetabsmenu">
	  <?php if(!isset($innerClass)) { $innerClass=''; } ?>
		<ul>
		 	<li class="<?php if(isset($innerClass0)) { echo $innerClass0;}?>"><a href="<?php  echo site_url('affiliate'); ?>"><span><?php echo $this->lang->line('About');?></span></a></li>
		 	<li class="<?php if(isset($innerClass1)) { echo $innerClass1;}?>"><a href="<?php echo site_url('affiliate/textlink'); ?>"><span><?php echo $this->lang->line('Text Link');?></span></a></li>
			<li class="<?php if(isset($innerClass2)) { echo $innerClass2;}?>"><a href="<?php echo site_url('affiliate/banners'); ?>"><span><?php echo $this->lang->line('Banners');?></span></a></li>
		 	<li class="<?php if(isset($innerClass3)) { echo $innerClass3;}?>"><a href="<?php echo site_url('affiliate/projectList'); ?>"><span><?php echo $this->lang->line('Javascript Project List');?></span></a></li>
			<li class="<?php if(isset($innerClass4)) { echo $innerClass4;}?>"><a href="<?php echo site_url('affiliate/textFeed'); ?>"><span><?php echo $this->lang->line('Developer Text Feed');?></span></a></li>	
			<li class="<?php if(isset($innerClass5)) { echo $innerClass5;}?>"><a href="<?php echo site_url('affiliate/rssFeeds'); ?>"><span><?php echo $this->lang->line('RSS Feeds');?></span></a></li>										
		 </ul>
		</div>
