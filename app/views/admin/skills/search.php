 <div class="clsTable">
 <form name="searchTransaction" action="<?php echo admin_url('skills/searchProjects');?>" method="post">
    <input type="hidden" name="name" id="name" />
	 <tr><td><label><?php echo $this->lang->line('Enter Project Id'); ?></label></td><td><input type="text" name="projectid" id="projectid" /></td></tr>
	 <tr><td></td><td><input type="submit" name="search" value="<?php echo $this->lang->line('search');?>" class="clsSubmitBt1" /></td></tr>
</form>
		
</div>
<div class="clsTable">
 <form name="searchTransaction" action="<?php echo admin_url('skills/searchProjects');?>" method="post">
     <input type="hidden" name="id" id="id" />
	 <tr><td><label><?php echo $this->lang->line('Enter Project Name'); ?></label></td><td><input type="text" name="projectname" id="projectname" /></td></tr>
     <tr><td></td><td><input type="submit" name="search" value="<?php echo $this->lang->line('search');?>" class="clsSubmitBt1" /></td></tr>
</form>
</div>