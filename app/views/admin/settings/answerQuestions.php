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
	      <div class="clsTop clsClearFixSub">
        
        <div class="clsNav">
          
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('Answer Affiliates Questions'); ?></h3>
        </div>
      </div>
	
    <div class="clsMidWrapper">
      <!--MID WRAPPER-->
  
      <div class="clsTable">
        
     <table width="700" class="table">
    	<form action="<?php echo admin_url('affiliateSettings/replay/'.$this->uri->segment(4)); ?>" name="anserAffiliates" id="anserAffiliates" method="post">
		
			  <tr><td><label><b><?php echo $this->lang->line('Email'); ?></b></label></td><td><input type="text" name="email" value="<?php if(isset($email)) echo $email; else echo $this->input->post('email'); ?>" readonly="yes" /></td></tr>
			  <tr><td><label><b><?php echo 'Subject'; ?></b></label></td><td><input type="text" name="subject" value="<?php if(isset($subject)) echo "Fwd: ".$subject; else echo $this->input->post('subject'); ?>" readonly="yes" /></td></tr>
			  <tr><td><label><b><?php echo 'Questions'; ?></b></label></td><td><input type="text" name="questions" value="<?php if(isset($questions)) echo $questions; else echo $this->input->post('questions'); ?>" readonly="yes" /></td></tr>
			  <tr><td><label><b><?php echo $this->lang->line('Answers'); ?></b></label></td><td> <textarea class="clsTextArea" name="comments"></textarea><div style="color:#FF0000"><?php echo form_error('comments'); ?></div></td></td>

		 <tr><td></td><td><input type="submit" name="ansAffiliates" id="ansAffiliates" value="<?php echo $this->lang->line('Submit');?>" class="clsSubmitBt1" /></td></tr>
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
	document.anserAffiliates.submit();
	//document.manageBids.action='<?php //echo admin_url('skills/manageBids'); ?>'; document.manageBids.submit();
}
</script>
