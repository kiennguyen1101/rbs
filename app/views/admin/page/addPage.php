<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
<?php //include('fckeditor/fckeditor.php');  ?>
<script type="text/javascript" src="<?php echo base_url() ?>tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});
</script>
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
      <h3><?php echo $this->lang->line('add_Page'); ?></h3>
	  <table class="table" cellpadding="2" cellspacing="0">
      <form method="post" action="<?php echo admin_url('page/addPage')?>">
        
		  <tr>
		     <td class="clsName"><?php echo $this->lang->line('page_name'); ?><span class="clsRed">*</span></td>
		     <td class="clsMailID"><input type="text" name="page_name" value="<?php echo set_value('page_name'); ?>"><?php echo form_error('page_name'); ?></td>
		  </tr>
          <tr>
		     <td class="clsName"><?php echo $this->lang->line('page_title'); ?><span class="clsRed">*</span></td>
		     <td><input type="text" name="page_title" value="<?php echo set_value('page_title'); ?>"> <?php echo form_error('page_title'); ?> </td>
		  </tr>
          <tr>
		     <td class="clsName"><?php echo $this->lang->line('page_url'); ?><span class="clsRed">*</span></td>
		     <td><input type="text" name="page_url" value="<?php echo set_value('page_url'); ?>"><?php echo form_error('page_url'); ?></td>
		  </tr>	 
         <tr><td class="clsName"> 
		   
         <?php echo $this->lang->line('page_content'); ?><span class="clsRed">*</span></td><td>
		 <textarea id="elm1" name="page_content" rows="15" cols="80" style="width: 80%"></textarea>
		
      <?php //if(isset($this->validation->page_content_error))echo $this->validation->content_error; 
	  echo form_error('page_content');?></td></tr>
	     <tr><td></td>    
          <td><input type="hidden" name="page_operation" value="add"  />
		  <input class="clsSubmitBt1" value="<?php echo $this->lang->line('Submit');?>" name="addPage" type="submit"></td></tr>
        
		
      </form></table>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
