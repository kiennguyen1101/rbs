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
          <h3><?php echo $this->lang->line('Edit Affiliates Sales'); ?></h3>
        </div>
        <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('affiliateSettings/searchAffiliateSales');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('affiliateSettings/sales');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
      </div>
	  <br />
      <!--END OF TOP TITLE & RESET-->
	  
      <div class="clsTable">
        
     <table width="700" class="table">
    	<form action="<?php echo admin_url('affiliateSettings/editAffiliateSales'); ?>" name="manageSales" id="manageSales" method="post">  <?Php 
		
		foreach($list as $res) 
		 {
			foreach($res->result() as $rec)
			  { ?>
			  
			  <tr><td><label><b><?php echo $this->lang->line('Id'); ?></b></label></td><td><input type="text" name="saleid[]" value="<?php echo $rec->id; ?>" readonly="yes" /></td></tr>
			  <tr><td><label><b><?php echo 'Ref Id'; ?></b></label></td><td><input type="text" name="refid[]" value="<?php echo $rec->refid; ?>" readonly="yes" /></td></tr>
			  <tr><td><label><b><?php echo $this->lang->line('IpAddress'); ?></b></label></td><td> <input type="text" name="ipaddress[]" value=" <?php echo $rec->ipaddress; ?>"> </td></td>
			  <tr><td><label><b><?php echo $this->lang->line('Payment'); ?></b></label></td><td> <input type="text" name="payment[]" value=" <?php echo $rec->payment; ?>"> </td></td>
			  <?php 
			  } 
		 } ?>
		 <tr><td></td><td><input type="submit" name="editClicks" id="editClicks" value="<?php echo $this->lang->line('Submit');?>" class="clsSubmitBt1" /></td></tr>
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
	document.manageSales.submit();
	//document.manageBids.action='<?php //echo admin_url('skills/manageBids'); ?>'; document.manageBids.submit();
}
</script>
