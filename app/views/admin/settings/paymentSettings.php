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
      <h3><?php echo $this->lang->line('payment_settings'); ?></h3>
	  
     <form method="post" action="<?php echo admin_url('paymentSettings/index')?>">
	  <table class="table1" cellpadding="2" cellspacing="0">
	
		<tbody>
        <input type="hidden" name="id" value="<?php echo $paymentGateways['paypal']['id']; ?>" />
       <tr>
         <td class="clsName"><?php echo $this->lang->line('pay_pal_emailid'); ?><span class="clsRed">*</span></td>
          <td class="clsMailId"><input class="clsTextBox" type="text" name="paypal_email_id"  value="<?php  echo $paymentGateways['paypal']['mail_id']; ?>"/>
          <?php echo form_error('paypal_email_id'); ?></td></tr>
       <tr>
          <td><?php echo $this->lang->line('pay_pal_url'); ?><span class="clsRed">*</span></td>
          <td><input class="clsTextBox" type="text" name="paypal_url" value="<?php  echo $paymentGateways['paypal']['url'] ?>"/>
          <?php echo form_error('paypal_url'); ?> </td></tr>
       <tr>
          <td><?php echo $this->lang->line('Minimum Deposit Amount'); ?><span class="clsRed">*</span></td>
          <td><input class="clsTextBox" type="text" name="paypal_deposit_minimum" value="<?php  echo $paymentGateways['paypal']['deposit_minimum'] ?>"/>
          <?php echo form_error('paypal_deposit_minimum'); ?> </td></tr>
        <tr>
          <td><?php echo $this->lang->line('Minimum Withdraw Amount'); ?><span class="clsRed">*</span></td>
          <td><input class="clsTextBox" type="text" name="paypal_withdraw_minimum" value="<?php  echo $paymentGateways['paypal']['withdraw_minimum'] ?>"/>
          <?php echo form_error('paypal_withdraw_minimum'); ?> </td></tr>
		  <tr>
          <td><?php echo $this->lang->line('paypal commission'); ?><span class="clsRed">*</span></td>
          <td><input class="clsTextBox" type="text" name="paypal_commission" value="<?php  echo $paymentGateways['paypal']['commission'] ?>"/>
          <?php echo form_error('paypal_commission'); ?> </td></tr>
        <tr>
          <td><?php echo $this->lang->line('Paypal Deposit'); ?><span class="clsRed">*</span></td>
          <td><input type="radio" class="clsRadioBut" name="is_deposit_enabled" value="1"  <?php if($paymentGateways['paypal']['is_deposit_enabled']==1)  echo 'checked="checked"'; ?>/>
          <?php echo $this->lang->line('On');?>
          <input type="radio"  class="clsRadioBut" name="is_deposit_enabled"  value="0"  <?php if($paymentGateways['paypal']['is_deposit_enabled']==0)  echo 'checked="checked"'; ?>/>
          <?php echo $this->lang->line('Off');?> <?php echo form_error('is_deposit_enabled'); ?></td></tr>
		 
        <tr>
          <td><?php echo $this->lang->line('Paypal Withdraw'); ?><span class="clsRed">*</span></td>
          <td><input type="radio" class="clsRadioBut"  name="is_withdraw_enabled"  value="1"  <?php if($paymentGateways['paypal']['is_withdraw_enabled']==1)  echo 'checked="checked"'; ?>/>
          <?php echo $this->lang->line('On');?>
          <input type="radio" class="clsRadioBut"  name="is_withdraw_enabled"  value="0"  <?php if($paymentGateways['paypal']['is_withdraw_enabled']==0)  echo 'checked="checked"'; ?>/>
          <?php echo $this->lang->line('Off');?> <?php echo form_error('is_withdraw_enabled'); ?></td></tr>
		<tr>
          <td><?php echo $this->lang->line('Paypal Deposit Description'); ?><span class="clsRed">*</span></td>
          <td><textarea class="clsTextArea" name="deposit_description"><?php  echo $paymentGateways['paypal']['deposit_description'] ?></textarea>
          <?php echo form_error('deposit_description'); ?> </td></tr>
		<tr>
          <td><?php echo $this->lang->line('Paypal Withdraw Description'); ?><span class="clsRed">*</span></td>
          <td><textarea class="clsTextArea" name="withdraw_description"><?php  echo $paymentGateways['paypal']['withdraw_description'] ?></textarea>
          <?php echo form_error('withdraw_description'); ?></td></tr>
     <tr align="center">
	 <td></td>
          <td><input type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('submit'); ?>"  name="paypalPaymentSettings"/></td>
        </tr>
    
	  </tbody></table> </form>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
