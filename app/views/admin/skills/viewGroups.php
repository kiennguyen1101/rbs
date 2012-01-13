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
            <li class="clsNoBorder"><a href="<?php echo admin_url('skills/addGroup')?>"><?php echo $this->lang->line('create_new_group'); ?></a></li>
          </ul>
        </div>
		   <div class="clsTitle">
          <h3><?php echo $this->lang->line('view_group'); ?></h3>
        </div>
      </div>
	     <table class="table" cellpadding="2" cellspacing="0">
		 <th></th>
        <th><?php echo $this->lang->line('Sl.No'); ?></th>
        <th><?php echo $this->lang->line('group_name'); ?></th>
        <th><?php echo $this->lang->line('created'); ?></th>
		<th><?php echo $this->lang->line('action'); ?></th>
    
	  <?php
			if(isset($groups) and $groups->num_rows()>0)
			{
				foreach($groups->result() as $group)
				{
	?>
	 <form action="" name="manageGroup" method="post" >
      <tr>
	   <td><input type="checkbox" class="clsNoborder" name="grouplist[]" id="grouplist[]" value="<?php echo $group->id; ?>"  /> </td>
        <td><?php echo $group->id; ?></td>
        <td><?php echo $group->group_name; ?></td>
        <td><?php echo date('Y-m-d',$group->created); ?></td>
		<td><a href="<?php echo admin_url('skills/editGroup/'.$group->id)?>"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a>
		<a href="<?php echo admin_url('skills/deleteGroup/'.$group->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a>
		</td>
      </tr>
	<?php
			}//Foreach End
		}//If End
		else
		{
		echo '<tr><td colspan="5">'.$this->lang->line('No Groups Found').'</td></tr>'; 
		}
	?>
	</table>
	</form>
	 <div class="clscenter clearfix">
	  <div id="selLeftAlign">
	<?php echo $this->lang->line('With Selected'); ?>
	<a name="delete" href="javascript: document.manageGroup.action='<?php echo admin_url('skills/deleteGroup'); ?>'; document.manageGroup.submit();" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a></div>
    </div>
	</div>
	 <!--PAGING-->
	  	<?php if(isset($pagination)) echo $pagination;?>
	 <!--END OF PAGING-->
      <!-- End clsTable-->

    </div>
    <!-- End clsMainSettings -->
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
