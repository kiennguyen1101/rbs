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
	 	<h3 align="left"><?php echo $this->lang->line('add_group'); ?></h3>
      <table width="700" class="table">
	   <form method="post" action="<?php echo admin_url('skills/addGroup')?>">
         <tr><td>
          <label><?php echo $this->lang->line('group_name'); ?><span class="clsRed">*</span></label></td><td>
          <input class="clsTextBox" type="text" name="group_name" value="<?php echo set_value('group_name'); ?>"/>
          <?php echo form_error('group_name'); ?> </td></tr>
        <tr><td>
          <label><?php echo $this->lang->line('descritpion'); ?></label></td><td>
		  <textarea name="descritpion" class="clsTextArea"><?php echo set_value('descritpion'); ?></textarea>
          <?php echo form_error('descritpion'); ?> </td></tr>
        <tr><td></td><td>
		  <input type="hidden" name="operation" value="add" />
          <input type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('submit'); ?>"  name="addGroup"/>
        </td></tr>
      </form>
	  </table>
    </div>
  </div>
  <!-- End of clsSettings -->
</div>
<!-- End Of Main -->
<?php $this->load->view('admin/footer'); ?>
