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
		<div class="clsTop clsClearFixSub">
          <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('users/addUsers');?>"><b><?php echo $this->lang->line('Add users'); ?></b></a></li>
			<li><a href="<?php echo admin_url('users/searchUsers');?>"><b><?php echo $this->lang->line('search_user'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('users/viewUsers');?>"><b><?php echo $this->lang->line('View users'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('View users'); ?></h3>
        </div>
		
      </div>
	  
      </div>
       <table width="700" class="table">
        <thead>
		  <tr>
		  <th>&nbsp;</th>
            <th><?php echo $this->lang->line('Sl.No');?></th>
			<th><?php echo $this->lang->line('Username');?></th>
            <th><?Php echo $this->lang->line('Name/Company');?></th>
            <th><?php echo $this->lang->line('Email');?></th>
			<th><?php echo $this->lang->line('User Type');?></th>
			<th><?php echo $this->lang->line('Balance');?></th>
			<th><?php echo $this->lang->line('Options');?></th>
          <!--  <th colspan="2"><span class="functions text-center" id="tip" style="opacity: 1;"> </span></th>-->
          </tr>
        </thead>
        <tbody>
		<?php $no=1;
		if(isset($userDetails) and $userDetails->num_rows()>0)
		{
		foreach($userDetails->result() as $userDetail)
			{
		?>
		<form name="manageuserdetail" action="" method="post" >
          <tr>
		    <td><input type="checkbox" class="clsNoborder" name="userlist[]" id="userlist[]" value="<?php echo $userDetail->id; ?>"  /> </td>
            <td><?php echo $no++;?></td>
			<td><?php echo $userDetail->user_name;?></td>
            <td><?php echo $userDetail->name;?></td>
			<td><?php echo $userDetail->email;?></td>
			<td><?php if($userDetail->role_id == 1) echo "Buyer"; else echo "Provider";?></td>
			<td><?php echo $userDetail->amount;//print_r($userDetail);?></td>
            <td class="functions">
			<a title="Edit" class="icon edit tip" href="<?php echo admin_url('users/editUser/'.$userDetail->id);?>"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a>
			<a title="Delete" href="<?php echo admin_url('users/deleteUser/'.$userDetail->id);?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a> </td>
          </tr>
		  <?php }
		  }
		  else{ ?>
		   <tr>
            <td colspan="5"><?php echo $this->lang->line('No users found');?></td></tr>
		  <?php }
		  ?>
        </tbody>
      </table>
	  </form>
	
	  <div class="clscenter clearfix">
	  <div id="selLeftAlign">
	<?php echo $this->lang->line('With Selected'); ?>
	<a name="delete" href="javascript: document.manageuserdetail.action='<?php echo admin_url('users/deleteUser'); ?>'; document.manageuserdetail.submit();" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a></div>
	</div>
	</div>
	   <!--PAGING-->
	  		<?php if(isset($pagination)) echo $pagination;?>
	 <!--END OF PAGING-->
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>