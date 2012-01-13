<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
      <?php
	   //if(isset($admin)) pr($admin);
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	  ?>
    </div>
    <div class="clsMidWrapper">
      <!--MID WRAPPER-->
      <!--TOP TITLE & RESET-->
      <div class="clsTop clsClearFixSub">
        <div class="clsTitleeditadmin">
		
          <h3><?php echo $this->lang->line('Edit Admin'); ?></h3>
        </div>
        <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('users/addAdmin');?>"><b><?php echo $this->lang->line('Add Admin'); ?></b></a></li>
			<li><a href="<?php echo admin_url('users/searchAdmin');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('users/viewAdmin');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
		<br /><br />
      </div>

      <!--END OF TOP TITLE & RESET-->
	  <table class="table" cellpadding="2" cellspacing="0">
	  <form name="admin" action="<?php echo admin_url('users/manageAdmin'); ?>" method="post"> <?php
	 
	  foreach($admin as $res )
	    { 
		  ?>
		 <tr><td><label><b><?php echo $this->lang->line('Admin id'); ?></b></label></td><td><input type="text" name="id[]" value="<?php echo $res->id;?>" readonly="Yes"  /></td></tr>
		  <tr><td><label><b><?php echo $this->lang->line('username'); ?></b></label></td><td><input type="text" name="username[]" value="<?php echo $res->admin_name;?>"  /></td></tr>
		  <tr><td><label><b><?php echo $this->lang->line('password'); ?></b></label></td><td><input type="password" name="password[]" value="<?php echo $res->password;?>" /></td></tr>
		    <?php
	    } echo '<br>';
	  ?>
	  <tr><td></td><td><input type="submit" name="manageAdmin" value="<?php echo $this->lang->line('Submit');?>" class="clsSubmitBt1" /></td></tr>
	  </form>
	  </table>
	  <!--PAGING-->
	  	<?php if(isset($pagination)) echo $pagination;?>
	 <!--END OF PAGING-->
	 

    </div>
    <!--END OF MID WRAPPER-->
  </div>
  <!-- End of clsSettings -->
</div>
<!-- End Of Main -->
<?php $this->load->view('admin/footer'); ?>
<script type="text/javascript">
function formSubmit()
{
	document.manageBids.submit();
	//document.manageBids.action='<?php //echo admin_url('skills/manageBids'); ?>'; document.manageBids.submit();
}
</script>