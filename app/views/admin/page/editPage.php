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
	  <?php
	  	//Content of a group
		if(isset($pages) and $pages->num_rows()>0)
		{
			$page = $pages->row();
	  ?>
	 	<h3><?php echo $this->lang->line('edit_group'); ?></h3>
         <table class="table" cellpadding="2" cellspacing="0">
		 <form method="post" action="<?php echo admin_url('page/editPage')?>/<?php echo $page->id;  ?>">
		  <tr><td class="clsName"><?php echo $this->lang->line('page_title'); ?><span class="clsRed">*</span></td><td>
		  <input class="clsNoborder" type="text" name="page_title" value="<?php echo $page->page_title; ?>"></td></tr>
		  <?php echo form_error('page_title'); ?> <br />
          <tr><td class="clsName"><?php echo $this->lang->line('page_name'); ?><span class="clsRed">*</span></td><td>
		  <input class="clsNoborder" type="text" name="page_name" value="<?php echo $page->name; ?>"></td></tr>
		  <?php echo form_error('page_name'); ?> <br />
      
	    <tr><td class="clsName"><?php echo $this->lang->line('page_content'); ?><span class="clsRed">*</span></td><td class="clsNoborder">
		<textarea id="elm1" name="page_content" rows="15" cols="80" style="width: 80%"><?php echo $page->content;?></textarea>
		<?php echo form_error('page_content');?>
       </td></tr>
	  
        <tr><td></td><td>
		 <input type="hidden" name="page_operation" value="edit" />
		  <input type="hidden" name="id"  value="<?php echo $page->id; ?>"/>
          <input type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('submit'); ?>"  name="editPage"/></td>
		</tr>  
        
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
