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
      <h3 align="left"><?php echo $this->lang->line('add_category'); ?></h3>
     <table width="700" class="table">

	  <form method="post" action="<?php echo admin_url('skills/addCategory')?>">
        <tr><td>
          <label><?php echo $this->lang->line('category_name'); ?><span class="clsRed">*</span></label></td><td>
          <input class="clsTextBox" type="text" name="category_name" value="<?php echo set_value('category_name'); ?>"/>
          <?php echo form_error('category_name'); ?> </td></tr>
        <tr><td>
          <label><?php echo $this->lang->line('group'); ?><span class="clsRed">*</span></label></td><td>
          <select name="group_id">
		  	<option value="" <?php echo set_select('group_id', '', TRUE); ?>><?php echo $this->lang->line('select_group'); ?></option>
			<?php
				if(isset($groups) and $groups->num_rows()>0)
				{
					foreach($groups->result() as $group)
					{
			?>
						<option value="<?php echo $group->id; ?>" <?php echo set_select('group_id',$group->id); ?> ><?php echo $group->group_name; ?></option>
       		<?php
					}//Foreach End
				}//If End
			?>
		  </select>
          <?php echo form_error('group_id'); ?> </td></tr>
		<tr><td>
          <label><?php echo $this->lang->line('is_active'); ?><span class="clsRed">*</span></label></td><td>
          <select name="is_active">
		  	<option value="0" <?php echo set_select('is_active', '0', TRUE); ?>><?php echo $this->lang->line('no'); ?></option>
			<option value="1" <?php echo set_select('is_active', '1'); ?>><?php echo $this->lang->line('yes'); ?></option>
		  </select>
          <?php echo form_error('is_active'); ?> </td></tr>
        <tr><td>
          <label><?php echo $this->lang->line('descritpion'); ?><span class="clsRed">*</span></label></td><td>
		  <textarea class="clsTextArea" name="description"><?php echo set_value('description'); ?></textarea>
          <?php echo form_error('description'); ?> </td></tr>
		<tr><td>
          <label><?php echo $this->lang->line('page_title'); ?><span class="clsRed">*</span></label></td><td>
          <input class="clsTextBox" type="text" name="page_title" value="<?php echo set_value('page_title'); ?>"/>
          <?php echo form_error('page_title'); ?> </td></tr>
		<tr><td>
          <label><?php echo $this->lang->line('meta_keywords'); ?><span class="clsRed">*</span></label></td><td>
          <textarea class="clsTextArea" name="meta_keywords"><?php echo set_value('meta_keywords'); ?></textarea>
          <?php echo form_error('meta_keywords'); ?> </td></tr>
        <tr><td>
          <label><?php echo $this->lang->line('meta_description'); ?><span class="clsRed">*</span></label></td><td>
          <textarea class="clsTextArea" name="meta_description"><?php echo set_value('meta_description'); ?></textarea>
          <?php echo form_error('meta_description'); ?> </td></tr>
       <tr><td></td><td>
          <input class="clsSubmitBt1" value="<?php echo $this->lang->line('Submit');?>" name="addCategory" type="submit"></td></tr>
         </form>
	  </table>	 
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
