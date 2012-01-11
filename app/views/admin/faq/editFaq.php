<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
	   <!--TOP TITLE & RESET-->
      <div class="clsTop clsClearFixSub">
       
        <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('users/addAdmin');?>"><b><?php echo $this->lang->line('Add Admin'); ?></b></a></li>
			<li><a href="<?php echo admin_url('users/searchAdmin');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('users/viewAdmin');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
		 <div class="clsTitle">
          <h3><?php echo $this->lang->line('Edit FAQ'); ?></h3>
        </div>
      </div>
      <!--END OF TOP TITLE & RESET-->
      <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}		
	  ?>
	  <?php
	  	//Content of a group
		if(isset($faqs) and $faqs->num_rows()>0)
		{
			$faq = $faqs->row();
	  ?>
	 	<h3><?php echo $this->lang->line('edit_group'); ?></h3>
		<table class="table" cellpadding="2" cellspacing="0">
		 <form method="post" action="#">
		  <tr><td  class="clsName">
          <?php echo $this->lang->line('faq_category'); ?><span class="clsRed">*</span></td><td>
          <select name="faq_category_id" class="usertype">		  	<option value=""><?php echo $this->lang->line('select_category'); ?></option>
			<?php
				if(isset($faqCategories) and $faqCategories->num_rows()>0)
				{
					foreach($faqCategories->result() as $faqCategory)
					{
			?>
						<option value="<?php echo $faqCategory->id; ?>" <?php if($faq->faq_category_id==$faqCategory->id) echo 'selected="selected"'; ?> ><?php echo $faqCategory->category_name; ?></option>
       		<?php
					}//Foreach End
				}//If End
			?>
		  </select></td></tr>
          <?php echo form_error('faq_category_id'); ?> </p>
         <tr><td class="clsName">
          <?php echo $this->lang->line('question'); ?><span class="clsRed">*</span></td><td>
		  <textarea class="clsTextArea" name="question"><?php echo $faq->question; ?></textarea>
          <?php echo form_error('question'); ?> <br />
          </td></tr>
	      <tr><td class="clsName">
          <?php echo $this->lang->line('answer'); ?><span class="clsRed">*</span></td><td>
		  <textarea class="clsTextArea" name="answer"><?php echo $faq->answer; ?></textarea>
          <?php echo form_error('answer'); ?> <br />
          </td></tr>
	      <tr><td class="clsName">
          <?php echo $this->lang->line('Is frequently asked question?');?></td><td>
		  <input name="is_frequent" value="Y" class="clsNoborder clsRadioBut" type="checkbox"  <?php if($faq->is_frequent=='Y') echo 'checked="checked"'; ?>/><?php echo $this->lang->line('Yes');?>
          </td></tr>
          <tr><td></td><td>         
		  <input type="hidden" name="id"  value="<?php echo $faq->id; ?>"/>
          <input type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('submit'); ?>"  name="editFaq"/></td></tr>
       
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
