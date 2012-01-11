<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
		<!--TOP TITLE & RESET-->
      <div class="clsTop clsClearFixSub">
          <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('faq/viewFaqCategories')?>"><?php echo $this->lang->line('view_faq_categories'); ?></a></li>
			<li><a href="<?php echo admin_url('faq/addFaqCategory')?>"><?php echo $this->lang->line('add_faq_category'); ?></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('faq/addFaq')?>"><?php echo $this->lang->line('add_faq'); ?></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('view_faq_categories'); ?></h3>
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
	  	//Content of a Email Setting
		if(isset($emailSettings) and $emailSettings->num_rows()>0)
		{
			$emailSetting = $emailSettings->row();

	  ?>
	 	<h3><?php echo $this->lang->line('edit_group'); ?></h3>
     <table width="700" class="table">
		<form method="post" action="<?php echo admin_url('emailSettings/Edit/'.$emailSetting->id)?>">
       <tr><td width="20%">
          <label><?php echo $this->lang->line('email_title'); ?><span class="clsRed">*</span></label></td><td width="50%">
          <input class="clsTextBox" type="text" name="email_title" value="<?php echo $emailSetting->title; ?>" readonly/>
          <?php echo form_error('email_title'); ?></td><td width="20%"></td></tr>
      <tr><td>
          <label><?php echo $this->lang->line('email_subject'); ?><span class="clsRed">*</span></label></td><td>
          <input class="clsTextBox" type="text" name="email_subject" value="<?php echo $emailSetting->mail_subject ; ?>"/>
          <?php echo form_error('email_subject'); ?> </td><td></td></tr>
	 <tr><td>
          <label><?php echo $this->lang->line('email_body'); ?><span class="clsRed">*</span></label></td><td>
		  <textarea  name="email_body"  class="clsTextArea" rows="10" cols="35"><?php echo $emailSetting->mail_body  ;?></textarea>
		  <?php $str = array(); $str = explode(' ',$emailSetting->mail_body) ?>
          <?php echo form_error('email_body'); ?> </td>
		  <td><?php $i=0;
		      foreach($str as $res)
	   			{
	   	 			$string = strchr($res,'!');
						
					if(isset($string) !='') {  echo $string.'    '; };
	   			} ?>
	      </td></tr>
		
       <tr><td></td><td>
		  <input type="hidden" name="id"  value="<?php echo $emailSetting->id; ?>"/>
          <input class="clsSubmitBt1" value="<?php echo $this->lang->line('Submit');?>" name="editEmailSetting" type="submit">
		</td><td></td></tr>  
        
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
