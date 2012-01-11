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
	  
     <form method="post" action="<?php echo admin_url('affiliateSettings/getAffiliatePayments')?>">
	  <table class="table1" cellpadding="2" cellspacing="0">
	
		<tbody>
        <input type="hidden" name="id" value="<?php if(isset($affiliates['id'])) echo $affiliates['id']; ?>" />
       <tr>
         <td class="clsName"><?php echo $this->lang->line('user_type');?><span class="clsRed">*</span></td>
          <td class="clsTextBox"><input class="clsTextBox" type="text" name="user_type" value="<?php  if(isset($affiliates['user_type'])) echo $affiliates['user_type']; else echo $this->input->post('usertype'); ?>" readonly="true"/>
          <?php echo form_error('user_type'); ?></td></tr>
       <tr>
          <td><?php echo $this->lang->line('regular_project');?><span class="clsRed">*</span></td>
          <td><input class="clsTextBox" type="text" name="regular_project" value="<?php if(isset($affiliates['regular_project'])) echo $affiliates['regular_project']; ?>"/>
          <?php echo form_error('regular_project'); ?> </td></tr>
       <tr>
          <td><?php echo $this->lang->line('featured_project');?><span class="clsRed">*</span></td>
          <td><input class="clsTextBox" type="text" name="featured_project" value="<?php if(isset($affiliates['featured_project'])) echo $affiliates['featured_project']; ?>"/>
          <?php echo form_error('featured_project'); ?> </td></tr>
        <tr>
          <td><?php echo $this->lang->line('min_payout');?><span class="clsRed">*</span></td>
          <td><input class="clsTextBox" type="text" name="min_payout" value="<?php  if(isset($affiliates['min_payout'])) echo $affiliates['min_payout']; ?>"/>
          <?php echo form_error('min_payout'); ?> </td></tr>
        <tr>
          <td><?php echo $this->lang->line('max_payout');?><span class="clsRed">*</span></td>
          <td><input class="clsTextBox" type="text" name="max_payout" value="<?php  if(isset($affiliates['max_payout'])) echo $affiliates['max_payout'];?>"/>
          <?php echo form_error('max_payout'); ?> </td></tr>
     <tr align="center">
	 		<td></td>
          <td><input type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('submit'); ?>"  name="featuredProjectSettings"/></td>
        </tr>
    
	  </tbody></table> </form>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
