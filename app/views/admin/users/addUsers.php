<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
	<div class="clsTop clsClearFixSub">
          <div class="clsNav">
          <ul>
           <li><a href="<?php echo admin_url('users/addUsers');?>"><b><?php echo $this->lang->line('Add users'); ?></b></a></li>
			<li><a href="<?php echo admin_url('users/searchUsers');?>"><b><?php echo $this->lang->line('search_user'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('users/viewUsers');?>"><b><?php echo $this->lang->line('View users'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
            <h3><?php echo $this->lang->line('add_user'); ?></h3>
        </div>
      </div>
	     <?php
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
	  	?>
		<form method="post" action="">
	  <table class="table1" cellpadding="2" cellspacing="0">
		<tbody>
      
               <tr>
            <td class="clsName"><?php echo $this->lang->line('Username');?> </td>
            <td class="clsMailIds">:
			<input name="username" type="text" id="username" value="<?php echo set_value('username'); ?>">
			<?php echo form_error('username'); ?>
		    </td>
          </tr>
         
          <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Password');?></span></strong></td>
            <td width="55%">:
                <input name="password" type="password" class="" id="password" value="">
				<?php echo form_error('password'); ?>
				
				</td>
				<?php echo form_error('value'); ?>
          </tr>
		  <tr>
            <td width="25%"><?php echo $this->lang->line('User Type');?></td>
            <td width="55%">:
                <select name="type" class="usertype" >
                  <option value="1">Buyer</option>
				  <option value="2">Provider</option>
                </select></td>
          </tr>
		  <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Email');?></span></strong></td>
            <td width="55%">:
                <input name="email" type="text" class="textbox" id="email" value="<?php echo set_value('email'); ?>">
				<?php echo form_error('email'); ?>
				</td>
          </tr>
		  <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Name/Company');?></span></strong></td>
            <td width="55%">:
                <input name="name" type="text" class="textbox" id="name" value="<?php echo set_value('name'); ?>">
				<?php echo form_error('name'); ?>
				</td>
          </tr>
             <tr id="bansubmit" >
            <td></td>
            <td height="30" style="padding-left:6px;"><input name="addUser" type="submit" class="clsSub" value="<?php echo $this->lang->line('Submit');?>">
			&nbsp;
            <input name="Reset" type="reset" class="clsSub" value="<?php echo $this->lang->line('Reset');?>">
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>