<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<?php
		//Get Project Info
     	$project = $projects->row();
?>
<!--MAIN-->
<div id="main" class="">
	<form method="post" action="" >
		<input type="hidden" value="<?php echo $project->creator_id; ?>"  name="to"/>
		<input type="hidden" value="<?php echo $project->id; ?>"  name="project_id"/>
      <!--PROJECT MESSAGE BOARD-->
      <div id="selPMB" class="clsMarginTop">
	  <h2><?php echo $this->lang->line('Post Message'); ?></h2>
	  <p class="clsSitelinks"><?php echo $this->lang->line('You are currently logged in as'); ?> <a class="glow" href="#"><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></a> <?php echo $this->lang->line('(');?><a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout') ?></a><?php echo $this->lang->line(').');?>
</p>
	  <p class="clsClearFix"><span class="clsPmgLeft"><b><?php echo $this->lang->line('Project'); ?>:</b></span><span class="clsPmgRight"><a href="<?php echo site_url('project/view/'.$project->id); ?>"><?php echo $project->project_name; ?></a></span></p>	
	  
	  <p class="clsClearFix"><span class="clsPmgLeft"><b><?php echo $this->lang->line('To'); ?>:</b></span><span class="clsPmgRight"><?php echo $project->user_name; ?></span></p>
	  
	  <p class="clsClearFix"><span class="clsPmgLeft"><b><?php echo $this->lang->line('Message:');?></b></span><span class="clsPmgRight"><textarea rows="10" name="message" cols="60"><?php echo set_value('message'); ?></textarea><?php echo $this->lang->line('Tip: You can post programming code by placing it within [code] and [/code] tags.'); ?>
 </span>
</p>
	<?php echo form_error('message'); ?>

	  <p class="clsClearFix"><span class="clsPmgLeft">&nbsp;</span><span class="clsPmgRight"><input class="clsSmall" type="submit" value="<?php echo $this->lang->line('Submit');?>" name="postMessage"/> <input  class="clsSmall" type="submit" value="<?php echo $this->lang->line('Preview');?>" name="submit"/></span></p>	  
      </div>
      <!--END OF PROJECT MESSAGE BOARD-->
	  </form>
    </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>