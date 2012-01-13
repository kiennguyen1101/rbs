<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
	 <div class="clsTop clsClearFixSub">
          <div class="clsNav">
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('edit_user'); ?></h3>
        </div>
      </div>
		     <?php
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
			$userDetails = $userDetails->row();
			if(is_object($userDetails)){
			
	  	?>
    
   
      <form method="post" action="">
       <table class="table1" cellpadding="0" cellspacing="2" border="0">
          <tr>
            <td width="25%"><?php echo $this->lang->line('Username');?> </td>
            <td width="55%">:
			<input name="username" type="text" class="textbox" id="username" value="<?php echo $userDetails->user_name; ?>">
			<?php echo form_error('username'); ?>
		    </td>
          </tr>
          <tr>
            <td width="25%"><span id="valuen"><?php echo $this->lang->line('Password');?></span></td>
            <td width="55%">
                <div id="show1" <?php if(form_error('password')){?> style="display:block"<?php }?> style="display:none">
						: <input name="password" type="password" class="textbox" id="password" value="<?php echo $this->input->post('password');?>"  >&nbsp;
						<input name="passwordold" type="hidden" class="textbox" value="<?php echo $userDetails->password;?>" >
						<?php //echo form_error('password'); ?>
						<a href="#" onclick="return cancel();" >cancel</a>
						</div>
						<div id="change" >
						: <a href="#" onclick="return passwordchange();" >change password</a>
                    </div>

				<?php echo form_error('password'); ?>
				
				</td>
				<?php echo form_error('value'); ?>
          </tr>
		  <tr>
            <td width="25%"><?php echo $this->lang->line('User Type');?></td>
            <td width="55%">:
                <select name="type" class="textbox" style="width:45.5%;">
                  <option value="1" <?php if($userDetails->role_id == 1) echo "selected"; ?>>Buyer</option>
				  <option value="2" <?php if($userDetails->role_id == 2) echo "selected"; ?>>Provider</option>
                </select></td>
          </tr>
		  <tr>
            <td width="25%"><span id="valuen"><?php echo $this->lang->line('Email');?></span></td>
            <td width="55%">:
                <input name="email" type="text" class="textbox" id="email" value="<?php echo $userDetails->email; ?>">
				<?php echo form_error('email'); ?>
				</td>
          </tr>
		  <tr>
            <td width="25%"><span id="valuen"><?php echo $this->lang->line('Name/Company');?></span></td>
            <td width="55%">:
                <input name="name" type="text" class="textbox" id="name" value="<?php echo $userDetails->name; ?>">
				<?php echo form_error('name'); ?>
				</td>
          </tr>
		    <tr>
            <td width="25%"><span id="valuen"><?php echo $this->lang->line('Balance Amount');?></span></td>
            <td width="55%">:
                <input name="balamount" type="text" class="textbox" id="balamount" value="<?php echo $userDetails->amount; ?>">
				<?php echo form_error('balamount'); ?>
				</td>
				<!-- <td height="30" id="bannosubmit" style="display:none;"><input name="editUser1" id="editUser1" type="submit" class="clsSubmitBt1" value="Submit" />
                 <input type="hidden" name="userid" value="<?php echo $userDetails->id;?>" /></td>
-->
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr id="bansubmit" >
            <td></td>
            <td height="30" style="padding-left:6px;"><input name="editUser" type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('Submit');?>">
			<input type="hidden" name="userid" value="<?php echo $userDetails->id;?>" />
            </td>
		<script type="text/javascript">
			function passwordchange()
			{
			document.getElementById('show1').style.display='block';
			document.getElementById('change').style.display='none';
			
			}
			function cancel()
			{
			document.getElementById('change').style.display='block';
			document.getElementById('show1').style.display='none';
			
			}
		</script>
          </tr>
        </table>
      </form>
	  <?php } ?>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
</div>
