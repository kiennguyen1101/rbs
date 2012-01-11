<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
	 <!--TOP TITLE & RESET-->
        <div class="clsTop clsClearFixSub">
		<div class="clsNav3">
          <ul>
            <li class="clsNoBorder"><a href="<?php echo admin_url('emailSettings/addemailSettings')?>"><b><?php echo $this->lang->line('Add Email Settings'); ?></b></a></li>
          </ul>
		  <div class="clsTitle1">
          <h3><?php echo $this->lang->line('Email Settings'); ?></h3>
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
    </div>
    <div class="clsMidWrapper">
      <!--MID WRAPPER-->
     
      
        <table class="table2" cellpadding="2" cellspacing="0" align="left">
		  <tr>
          <th><?php echo $this->lang->line('email_template_title'); ?></th>
		  <th><?php echo $this->lang->line('action'); ?></th></tr>
		        
		<?php
		   	if(isset($email_settings))
			{
				foreach($email_settings->result() as $email_setting)
				{ 
					
		?>
			 <tr>
			  <td><?php echo ucfirst($email_setting->title); ?></td>
		
			  <td><a href="<?php echo admin_url('emailSettings/edit/'.$email_setting->id)?>"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a>
			  <a href="<?php echo admin_url('emailSettings/delete/'.$email_setting->id)?>"; onclick="return confirm('Are you sure want to delete??');"> <img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" />
			  </a></td>
        	</tr>
        <?php
				}//Foreach End
			}//If End
		?>
		</table>
     
    </div>
    <!--END OF MID WRAPPER-->
  </div>
  <!-- End of clsSettings -->
</div>
</div>
<!-- End Of Main -->
<?php $this->load->view('admin/footer'); ?>
