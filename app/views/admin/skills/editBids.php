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
    </div>
    <div class="clsMidWrapper">
      <!--MID WRAPPER-->
      <!--TOP TITLE & RESET-->
      <div class="clsTop clsClearFixSub">
        <div class="clsTitle">
          <h3><?php echo $this->lang->line('page'); ?></h3>
        </div>
        <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('skills/searchBids');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('skills/viewBids');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
      </div>
	  <br />
      <!--END OF TOP TITLE & RESET-->
	  
      <div class="clsTable">
        
     <table width="700" class="table">
    	<form action="<?php echo admin_url('skills/editBids'); ?>" name="manageBids" id="manageBids" method="post">  <?Php 
		
		foreach($list as $res) 
		 {
			foreach($res->result() as $rec)
			  { ?>
			  
			  <tr><td><label><b><?php echo $this->lang->line('Bid Id'); ?></b></label></td><td><input type="text" name="bidid[]" value="<?php echo $rec->id; ?>" readonly="yes" /></td></tr>
			  <tr><td><label><b><?php echo 'Project Id'; ?></b></label></td><td><input type="text" name="project[]" value="<?php echo $rec->project_id; ?>" readonly="yes" /></td></tr>
			  <tr><td><label><b><?php echo $this->lang->line('Bid Amount'); ?></b></label></td><td> <input type="text" name="amount[]" value=" <?php echo $rec->bid_amount; ?>"> </td></td>
			  <?php 
			  } 
		 } ?>
		 <tr><td></td><td><input type="submit" name="editBid" id="editBid" value="<?php echo $this->lang->line('Submit');?>" class="clsSubmitBt1" /></td></tr>
		</form>
		</table>
      </div>
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