<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
	 <div class="clsTop clsClearFixSub">
          <div class="clsNav">
        </div>
		</div>
		<div class="clsTitle">
          <h3><?php echo 'Edit Bans'. $this->lang->line('Edit_bans'); ?></h3>
		  <?php
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
	  	?>
        </div>
      </div> 	   
           <table width="400" class="table">
        <thead>
		  <tr>
		  <th></th>
		  <th><?php echo $this->lang->line('S.No');?></th>
            <th><?php echo $this->lang->line('Ban Type');?></th>
            <th><?php echo $this->lang->line('Ban Value');?></th>
            <th><?php echo $this->lang->line('Options');?></th>
            <!--<th colspan="2"><span class="functions text-center" id="tip" style="opacity: 1;"> </span></th>-->
          </tr>
        </thead>
        <tbody>
		<?php 
		if(isset($banDetails) and $banDetails->num_rows()>0)
		{$i=0;
		foreach($banDetails->result() as $banDetail)
			{
		?>
		<form action="" name="managebans" method="post" >
            <tr>
			 <td><input type="checkbox" class="clsNoborder" name="banlist[]" id="banlist[]" value="<?php echo $banDetail->id; ?>"  /> </td>
			<td><?php echo $i=$i+1; ?> </td>
            <td><?php echo $banDetail->ban_type;?></td>
            <td><?php echo $banDetail->ban_value;?></td>
            <td class="functions">
			<a title="Edit" class="icon edit tip" href="<?php echo admin_url('users/viewBan/'.$banDetail->id);?>"><img src="<?php echo image_url('edit-new.png'); ?>" height="20" width="20"/></a> 
			<a name="delete" href="javascript: document.managebans.action='<?php echo admin_url('users/deleteBan/'.$banDetail->id); ?>'; document.managebans.submit();" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a>
			
			</td>
          </tr>
		  <?php }
		  }
		  else
		  {
		  echo '<tr><td colspan="5">'.$this->lang->line('No Ban Users Found').'</td></tr>'; 
		  }
		  ?>
        </tbody>
      </table>
	  <?php if(isset($pagination)) echo $pagination;?>
	  </form>
	  <br />
    <div class="clscenter clearfix">
	  <div id="selLeftAlign">
	<?php echo $this->lang->line('With Selected'); ?>
	<a name="delete" href="javascript: document.managebans.action='<?php echo admin_url('users/deleteBan'); ?>'; document.managebans.submit();" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a>
	
	</div>
	</div>
	  

  </div>
</div>
</div>
<?php $this->load->view('admin/footer'); ?>