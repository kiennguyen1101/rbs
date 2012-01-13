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
	  <?php
	  	//Content of a group
		if(isset($groups) and $groups->num_rows()>0)
		{
			$group = $groups->row();
	  ?>
	 	<h3><?php echo $this->lang->line('edit_group'); ?></h3>
       <table width="700" class="table">
		 <form method="post" action="<?php echo admin_url('skills/editGroup/'.$group->id)?>">
        <tr><td>
          <label><?php echo $this->lang->line('group_name'); ?><span class="clsRed">*</span></label></td><td>
          <input class="clsTextBox" type="text" name="group_name" value="<?php echo $group->group_name; ?>"/>
          <?php echo form_error('group_name'); ?> </td></tr>
        <tr><td>
          <label><?php echo $this->lang->line('descritpion'); ?></label></td><td>
		  <textarea name="descritpion" class="clsTextArea"><?php echo $group->descritpion; ?></textarea>
          <?php echo form_error('descritpion'); ?> </td></tr>
        <tr><td></td><td>
		  <input type="hidden" name="operation" value="edit" />
		  <input type="hidden" name="id"  value="<?php echo $group->id; ?>"/>
          <input type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('submit'); ?>"  name="editGroup"/>
        </td></tr>
      </form>
	  </table>
	  <?php
	  }
	  ?>
    </div>
  </div>
  <!-- End of clsSettings -->
</div>
<!-- End Of Main -->
<?php $this->load->view('admin/footer'); ?>
