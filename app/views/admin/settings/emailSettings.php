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
			  <p class="clsClearFixSub">
				<label><?php echo $this->lang->line('pay_pal_emailid'); ?><span class="clsRed">*</span></label>
				<input class="clsTextBox" type="text" name="paypal_email_id" value="<?php  if(isset($settings['PAYPAL_EMAIL_ID'])) echo $settings['PAYPAL_EMAIL_ID'] ?>"/>
				<?php echo form_error('paypal_email_id'); ?></p>
			  <p>
				<label><?php echo $this->lang->line('pay_pal_url'); ?><span class="clsRed">*</span></label>
				<input class="clsTextBox" type="text" name="paypal_url" value="<?php if(isset($settings['PAYPAL_URL'])) echo $settings['PAYPAL_URL']; ?>"/>
				<?php echo form_error('paypal_url'); ?> </p>
			  <p class="clsSubmitBlock">
				<input type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('submit'); ?>"  name="paymentSettings"/>
			  </p>
			</form>
		</div>
  	</div>
</div>
<?php $this->load->view('admin/footer'); ?>
