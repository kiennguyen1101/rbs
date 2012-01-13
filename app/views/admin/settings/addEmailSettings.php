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
      <h3><?php echo $this->lang->line('add_email_settings'); ?></h3>
     <table width="700" class="table">
     <form method="post" action="<?php echo admin_url('emailSettings/addemailSettings')?>">
      <tr><td>
          <label><?php echo $this->lang->line('email_type'); ?><span class="clsRed">*</span></label></td><td>
          <input class="clsTextBox" type="text" name="email_type" value="<?php echo set_value('email_type'); ?>"/>
          <?php echo form_error('email_type'); ?></td></tr>
     <tr><td>
          <label><?php echo $this->lang->line('email_title'); ?><span class="clsRed">*</span></label></td><td>
          <input class="clsTextBox" type="text" name="email_title" value="<?php echo set_value('email_title'); ?>"/>
          <?php echo form_error('email_title'); ?> </td></tr>
		
		<tr><td>
          <label><?php echo $this->lang->line('email_subject'); ?><span class="clsRed">*</span></label></td><td>
          <input class="clsTextBox" type="text" name="email_subject" value="<?php echo set_value('email_subject'); ?>"/>
          <?php echo form_error('email_subject'); ?></td> </tr>

        <tr><td>
          <label><?php echo $this->lang->line('email_body'); ?><span class="clsRed">*</span></label></td><td>
          <textarea class="clsTextArea" name="email_body"><?php echo set_value('email_body'); ?></textarea>
          <?php echo form_error('email_body'); ?> </td></tr>
       <tr><td></td><td>
          <input class="clsSubmitBt1" value="<?php echo $this->lang->line('Submit');?>" name="addEmailSettings" type="submit">
        </td></tr>
      </form>
	  </table>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
