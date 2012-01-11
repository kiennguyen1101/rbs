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
            <li><a href="<?php echo admin_url('skills/searchBids');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('skills/viewBids');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('View Bid Projects'); ?></h3>
        </div>
      </div>
	 
      <!--END OF TOP TITLE & RESET-->
	  
       <table class="table" cellpadding="2" cellspacing="0">
		  <th>&nbsp;</th>
          <th><?php echo $this->lang->line('Sl.No'); ?></th>
          <th><?php echo $this->lang->line('Project Id'); ?></th>
		  <th><?php echo $this->lang->line('Project Name'); ?></th>
		  <th><?php echo $this->lang->line('User Id'); ?></th>
		  <th><?php echo $this->lang->line('User Name'); ?></th>
	      <th><?php echo $this->lang->line('Amount'); ?></th>
	      <th><?php echo $this->lang->line('Date'); ?></th>
          
      <form action="<?php echo admin_url('skills/manageBids'); ?>" name="manageBids" id="manageBids" method="post">
		<?php
			if(isset($bidProjects) and $bidProjects->num_rows()>0)
			{
				foreach($bidProjects->result() as $bids)
				{
		?>
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="projectList[]" id="projectList[]" value="<?php echo $bids->id; ?>"  /></td>
			  <td><?php echo $bids->id; ?></td>
			  <td><?php echo $bids->project_id; ?></td>
			  <td>
			    <?php //Show the project name 
				   foreach($projects->result() as $project)
				     {
					 	if($project->id == $bids->project_id)
						   echo $project->project_name;
					 }
			    ?>
			  </td>
			  <td><?php echo $bids->user_id; ?> </td>
			  <td><?php echo $bids->user_name; ?> </td>
			  <td><?php echo $bids->bid_amount; ?> </td>
			  <td><?php echo date('Y-m-d',$bids->bid_time); ?> </td>
        	</tr>
		  
        <?php
				}//Foreach End 
			?>
			<?php 		
			}//If End
			else
			{ 			
			  echo '<br>'.$this->lang->line('No Projects Found').'<br/><br>'; 
			}
		?>
		</form>
		</table>
	       <div id="selLeftAlign">
	      <?php echo $this->lang->line('With Selected'); ?>

		   <a name="edit" href="javascript: document.manageBids.action='<?php echo admin_url('skills/manageBids'); ?>'; document.manageBids.submit();"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a>
           <a name="delete" href="javascript: document.manageBids.action='<?php echo admin_url('skills/deleteBids'); ?>'; document.manageBids.submit();" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a> </div>

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