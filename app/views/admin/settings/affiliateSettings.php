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
      <h3><?php echo $this->lang->line('Affiliate Settings'); ?></h3>
	  
     <form method="post" name="affiliateForm" action="">
	  <input type="hidden" name="id" value="<?php if(isset($affiliates['id'])) echo $affiliates['id']; ?>" />
	  <table class="table1" border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td><b><?php echo $this->lang->line('Regular Project'); ?> </b></td>
          <td><table class="table4" border="0">
            <tr>
              <td><?php echo $this->lang->line('Buyer Affiliate Fee'); ?> :</td>
              <td><input class="clsTextBox" type="text" name="buyer_affiliate_fee" value="<?php if(isset($affiliates['buyer_affiliate_fee'])) echo $affiliates['buyer_affiliate_fee']; else echo $this->input->post('buyer_affiliate_fee'); ?>"/><div><?php echo form_error('buyer_affiliate_fee'); ?></div></td>
            </tr>
            <tr>
              <td><?php echo $this->lang->line('Buyer Minimum Amount'); ?> :</td>
              <td><input class="clsTextBox" type="text" name="buyer_min_amount" value="<?php if(isset($affiliates['buyer_min_amount'])) echo $affiliates['buyer_min_amount']; else echo $this->input->post('buyer_min_amount'); ?>"/>
			  <div><?php echo form_error('buyer_min_amount'); ?></div></td>
            </tr>
            <tr>
              <td><?php echo $this->lang->line('Buyer Minimum Payout'); ?> :</td>
              <td><input class="clsTextBox" type="text" name="buyer_min_payout" value="<?php if(isset($affiliates['buyer_min_payout'])) echo $affiliates['buyer_min_payout']; else echo $this->input->post('buyer_min_payout'); ?>"/>
			  <div><?php echo form_error('buyer_min_payout'); ?></div></td>
            </tr>
            <tr>
              <td><?php echo $this->lang->line('Buyer Maximum Payout'); ?> :</td>
              <td><input class="clsTextBox" type="text" name="buyer_max_payout" value="<?php if(isset($affiliates['buyer_max_payout'])) echo $affiliates['buyer_max_payout']; else echo $this->input->post('buyer_max_payout'); ?>"/>
			  <div><?php echo form_error('buyer_max_payout'); ?></div></td>
            </tr>
          </table></td>
          <td><table class="table4"  border="0">
            <tr>
              <td><?php echo $this->lang->line('Programmer Affiliate Fee'); ?> :</td>
              <td><input class="clsTextBox" type="text" name="programmer_affiliate_fee" value="<?php if(isset($affiliates['programmer_affiliate_fee'])) echo $affiliates['programmer_affiliate_fee']; else echo $this->input->post('programmer_affiliate_fee'); ?>"/><div><?php echo form_error('programmer_affiliate_fee'); ?></div></td>
            </tr>
            <tr>
              <td><?php echo $this->lang->line('Programmer Minimum Amount'); ?> :</td>
              <td><input class="clsTextBox" type="text" name="programmer_min_amount" value="<?php if(isset($affiliates['programmer_min_amount'])) echo $affiliates['programmer_min_amount']; else echo $this->input->post('programmer_min_amount'); ?>"/><div><?php echo form_error('programmer_min_amount'); ?></div></td>
            </tr>
            <tr>
              <td><?php echo $this->lang->line('Programmer Minimum Payout'); ?> : </td>
              <td><input class="clsTextBox" type="text" name="programmer_min_payout" value="<?php if(isset($affiliates['programmer_min_payout'])) echo $affiliates['programmer_min_payout']; else echo $this->input->post('programmer_min_payout'); ?>"/><div><?php echo form_error('programmer_min_payout'); ?></div></td>
            </tr>
            <tr>
              <td><?php echo $this->lang->line('Programmer Maximum Payout'); ?> :</td>
              <td><input class="clsTextBox" type="text" name="programmer_max_payout" value="<?php if(isset($affiliates['programmer_max_payout'])) echo $affiliates['programmer_max_payout']; else echo $this->input->post('programmer_max_payout'); ?>"/><div><?php echo form_error('programmer_max_payout'); ?></div></td>
            </tr>
          </table></td>
        </tr>
        
        <tr>
          <td colspan="3" style="padding-left:500px;"><input type="submit" name="regularProject" class="clsSubmitBt1" value="Submit" /></td>
          </tr>
        <tr>
          <td><b><?php echo $this->lang->line('Featured Project'); ?> </b></td>
          <td><table border="0" class="table4">
            <tr>
              <td><?php echo $this->lang->line('Featured Project Fee for Buyer'); ?>:</td>
              <td><input class="clsTextBox" type="text" name="buyer_project_fee" value="<?php if(isset($affiliates['buyer_project_fee'])) echo $affiliates['buyer_project_fee']; else echo $this->input->post('buyer_project_fee'); ?>"/><div><?php echo form_error('buyer_project_fee'); ?></div></td>
            </tr>
          </table></td>
          <td><table class="table4"  border="0">
            <tr>
              <td><?php echo $this->lang->line('Featured Project Fee for Programmer'); ?>:</td>
              <td><input class="clsTextBox" type="text" name="programmer_project_fee" value="<?php if(isset($affiliates['programmer_project_fee'])) echo $affiliates['programmer_project_fee']; else echo $this->input->post('programmer_project_fee'); ?>"/><div><?php echo form_error('programmer_project_fee'); ?></div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="3" style="padding-left:500px;"><input type="submit" name="featuredProject" class="clsSubmitBt1" value="Submit" /></td>
          </tr>
      </table>
	  </form>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>

<script>
function isChecked() {
	if(document.affiliateForm.buyer.checked) {
		document.affiliateForm.programmer.checked = false;
	} 		
}

function isChecked1() {
	if(document.affiliateForm.programmer.checked) {
		document.affiliateForm.buyer.checked = false;
	} 
}
</script>