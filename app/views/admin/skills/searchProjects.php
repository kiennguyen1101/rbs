<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
      <?php
	   //if(isset($usersList)) pr($usersList);
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
        <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('skills/searchProjects');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('skills/viewProjects');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
		        <div class="clsTitle">
          <h3><?php echo $this->lang->line('Search Projects'); ?></h3>
        </div>

      </div>
      <!--END OF TOP TITLE & RESET-->
	  <table width="700" class="table">
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
	  <div id="searchform">
	   <!-- The keyword Field will display here -->
	  </div>
     </table>
	  
	  <!--PAGING-->
	  	<?php if(isset($pagination_outbox)) echo $pagination_outbox;?>
	 <!--END OF PAGING-->
    </div>
    <!--END OF MID WRAPPER-->
  </div>
  <!-- End of clsSettings -->
</div>
<!-- End Of Main -->
<?php $this->load->view('admin/footer'); ?>
<script type="text/javascript">
function searchtype()
{
	alert('hi');
	var url = '<?php echo $admin_url('skills/searchProjects/search'); ?>';
	new Ajax.Updater('searchform', url, {  method     : 'post',
									parameters :$('search')
					}); //Ajax Object Creation End
}
</script>
