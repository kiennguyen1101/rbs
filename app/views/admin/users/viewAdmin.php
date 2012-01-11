<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
      <?php
	   //if(isset($projects)) pr($projects->result());
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	  ?>
    </div>
    <div class="clsMidWrapper">
      <!--MID WRAPPER-->
      <!--TOP TITLE & RESET-->
      <div class="clsTop clsClearFixSub">
          <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('users/addADmin');?>"><b><?php echo $this->lang->line('Add Admin'); ?></b></a></li>
			<li><a href="<?php echo admin_url('users/searchAdmin');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('users/viewAdmin');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('view_admin_users'); ?></h3>
        </div>
		
      </div>
	     <!--END OF TOP TITLE & RESET-->
	  
      <div class="clsTab">
        <table class="table" cellpadding="2" cellspacing="0">
		  <th>&nbsp;</th>	
          <th><?php echo $this->lang->line('Admin id'); ?></th>
          <th><?php echo $this->lang->line('username'); ?></th>
		  <th><?php echo $this->lang->line('password'); ?></th>
		  <th><?php echo $this->lang->line('Options'); ?></th>
        
		<form action="<?php echo admin_url('users/manageAdmin'); ?>" name="manageadmin" id="manageadmin" method="post">
		<?php $i=0;
			if(isset($admin) and count($admin)>0)
			{
				foreach($admin as $admin)
				{?>
			 <tr>
			  <td width=""><input type="checkbox" name="adminList[]" id="adminList[]" class="clsNoborder" value="<?php echo $admin->id; ?>"  /></td>
			  <td><?php echo $admin->id; ?></td>
			  <td><?php echo $admin->admin_name ; ?></td>
			  <td><?php echo $admin->password; ?></td>
			   <td class="functions">
			 <a name="edit" href="javascript: document.manageadmin.action='<?php echo admin_url('users/manageAdmin/'.$admin->id); ?>'; document.manageadmin.submit();"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a>
           <a name="delete" href="javascript: document.manageadmin.action='<?php echo admin_url('users/deleteAdmin/'.$admin->id); ?>'; document.manageadmin.submit();" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a></td>
        	</tr>
		  
        <?php
				}//Foreach End 
			?>
			 <?php 	
			}//If End
			else
			{ 			
			  echo '<br>'.$this->lang->line('No Users Found').'<br/><br>'; 
			}
		?>
	
		</table>
		</div>
      </div>
	  <br />
	 	   <div id="selLeftAlign">
	  <?php echo $this->lang->line('With Selected'); ?>
		  <!-- <a name="edit" href="javascript: document.manageBids.action='<?php echo admin_url('users/manageAdmin'); ?>'; document.manageBids.submit();"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a>-->
           <a name="delete" href="javascript: document.manageadmin.action='<?php echo admin_url('users/deleteAdmin'); ?>'; document.manageadmin.submit();" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a> </div>
	
 </form>	
   <!--PAGING-->
     	<?php if(isset($pagination)) echo $pagination;?>
	 <!--END OF PAGING-->
	
    </div>
    <!--END OF MID WRAPPER-->
  </div>
  <!-- End of clsSettings -->
</div>
<!-- End Of Main -->
<?php $this->load->view('admin/footer'); ?>
<script type="text/javascript">
function formSubmit()
{
	document.manageBids.submit();
	//document.manageBids.action='<?php //echo admin_url('skills/manageBids'); ?>'; document.manageBids.submit();
}
</script>