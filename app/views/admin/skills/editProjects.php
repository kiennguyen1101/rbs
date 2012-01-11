<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
      <?php
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
	  ?>
      <div class="clsTop clsClearFixSub">
          <div class="clsNav">
          <ul>
            <li class="clsNoBorder"><a href="<?php echo admin_url('skills/viewProjects')?>"><?php echo $this->lang->line('View All'); ?></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('Edit Projects'); ?></h3>
        </div>
      </div>
	     <table class="table" cellpadding="0" cellspacing="2" border="0">
     <form action="<?php echo admin_url('skills/editProjects'); ?>" name="editProjects" method="post">
	 <?php $i=0;
	 foreach($projects as $res)
	   {
		 foreach($res->result() as $rec) 
		  {  ?>
			<tr><td class="class"><?php echo $this->lang->line('Project Id'); ?></td><td><input type="text" name="projectid[]" readonly="yes" value="<?php echo $rec->id; ?>" /></td></tr>
			 <tr><td class="clsName"><?php echo $this->lang->line('Project Status'); ?></td><td>
				<select name="projectstatus[]">
				   <option value="0" <?php if($rec->project_status == '0') echo 'selected'; ?>><?php echo $this->lang->line('Open'); ?></option> 
				   <option value="1" <?php if($rec->project_status == '1') echo 'selected'; ?>><?php echo $this->lang->line('Close'); ?></option> 
				   <option value="2" <?php if($rec->project_status == '2') echo 'selected'; ?>><?php echo $this->lang->line('Cancel'); ?></option> 
				   <option value="3" <?php if($rec->project_status == '3') echo 'selected'; ?>><?php echo $this->lang->line('Dispute'); ?></option> 
				</select>
			 </td></tr>
			 <tr><td class="clsName"><?php echo $this->lang->line('Project Name'); ?></td><td><input type="text" name="projectname[]" value="<?php echo $rec->project_name; ?>" /></td></tr>
			 <tr><td class="clsName"><?php echo $this->lang->line('Project Description'); ?></td><td><textarea name="projectdescription[]" class="clsTextArea" rows="7" cols="40"><?php echo $rec->description; ?> </textarea></td></tr>
		
			 
			 <tr><td class="clsName"><?php echo $this->lang->line('Project Min'); ?></td><td><input type="text" name="projectmin[]" value="<?php echo $rec->budget_min; ?>" /></td></tr>
			 <tr><td class="clsName"><?php echo $this->lang->line('Project Max'); ?></td><td><input type="text" name="projectmax[]" value="<?php echo $rec->budget_max; ?>" /></td></tr>
			 <tr><td class="clsName"><?php echo $this->lang->line('Project Featured'); ?></td><td>
			 
			 <input type="hidden" name="today" value="<?php if(isset($today)) echo $today; ?>" />
			 <input type="hidden" name="todayOpen" value="<?php if(isset($todayOpen)) echo $todayOpen; ?>" />
			 <input type="hidden" name="todayClosed" value="<?php if(isset($todayClosed)) echo $todayClosed; ?>" />
			 <input type="hidden" name="thisWeek" value="<?php if(isset($thisWeek)) echo $thisWeek; ?>" />
			 <input type="hidden" name="thisMonth" value="<?php if(isset($thisMonth)) echo $thisMonth; ?>" />
			 <input type="hidden" name="thisYear" value="<?php if(isset($thisYear)) echo $thisYear; ?>" />
			 
			 <input type="radio"  class="clsRadioBut" name="projectfeatured[<?php echo $i; ?>][]" value="1" <?php if($rec->is_feature == '1') echo 'checked';?>/><?php echo $this->lang->line('yes');?>
			 <input type="radio"   class="clsRadioBut" name="projectfeatured[<?php echo $i; ?>][]" value="0" <?php if($rec->is_feature == '0') echo 'checked';?> /><?php echo $this->lang->line('no');?>
			 </td></tr>
			 <tr><td class="clsName"><?php echo $this->lang->line('Project Urgent'); ?></td><td>
			 <input type="radio"  class="clsRadioBut" name="projecturgent[<?php echo $i; ?>][]" value="1" <?php if($rec->is_urgent == '1') echo 'checked';?>/><?php echo $this->lang->line('yes');?>
			 <input type="radio"   class="clsRadioBut" name="projecturgent[<?php echo $i; ?>][]" value="0" <?php if($rec->is_urgent == '0') echo 'checked';?> /><?php echo $this->lang->line('no');?>
			 </td></tr>
			 <tr><td class="clsName"><?php echo $this->lang->line('Project Hidden'); ?></td><td>
			 <input type="radio"  class="clsRadioBut"  name="projecthidden[<?php echo $i; ?>][]" value="1" <?php if($rec->is_hide_bids == '1') echo 'checked';?>/><?php echo $this->lang->line('yes');?>
			 <input type="radio"   class="clsRadioBut" name="projecthidden[<?php echo $i; ?>][]" value="0" <?php if($rec->is_hide_bids == '0') echo 'checked';?> /><?php echo $this->lang->line('no');?>
			 </td></tr>
			  <?php $i=$i+1;
	      } 
	   }      ?>
	   <tr><td></td><td><input type="submit" name="manageProject" value="<?php echo $this->lang->line('Submit');?>" class="clsSubmitBt1" /></td></tr>
	 </form>
	 </table>
	<!--PAGING-->
	  	<?php if(isset($pagination)) echo $pagination;?>
	 <!--END OF PAGING-->
      <!-- End clsTable-->
    </div>
    <!-- End clsMainSettings -->
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
