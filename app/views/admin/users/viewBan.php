<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
	    <!--TOP TITLE & RESET-->
      <div class="clsTop clsClearFixSub">
        
        <div class="clsNav">
          <ul>
            <!--<li><a href="<?php echo admin_url('skills/searchBids');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>-->
			<li class="clsNoBorder"><a href="<?php echo admin_url('users/editBans');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
            <h3><?php echo $this->lang->line('website_settings'); ?></h3>
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
    
   
      <form method="post" action="">
       <table class="table1"  cellpadding="0" cellspacing="2" border="0">
          <tr>
            <td width="25%"><strong><?php echo $this->lang->line('Ban Type');?> </strong></td>
            <td width="55%">:
			<select name="type" class="usertype">
				  <option value="">Select Type</option>
                  <option value="EMAIL" <?php if($banDetails->ban_type == 'EMAIL') echo "selected";?>>Email Address</option>
				  <option value="USERNAME" <?php if($banDetails->ban_type == 'USERNAME') echo "selected";?>>Username</option>
                </select>
			   </td>
          </tr>
         
          <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Ban Value');?></span></strong></td>
            <td width="55%">:
                <input name="value" type="text" class="textbox" id="value" value="<?php echo $banDetails->ban_value;?>"></td>
				<?php echo form_error('value'); ?>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr id="bansubmit" >
            <td></td>
            <td height="30" style="padding-left:6px;"><input name="addBan" type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('Submit');?>">
			<input type="hidden" name="banid" value="<?php echo $banDetails->id;?>" />
            </td>
          </tr>
        </table>
        </p>
      </form>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>