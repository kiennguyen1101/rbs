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
            <li class="clsNoBorder"><a href="<?php echo admin_url('skills/searchProjects')?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('View Projects'); ?></h3>
        </div>
      </div>
    
       <table class="table" cellpadding="2" cellspacing="0">
        <th></th>
		<th><?php echo $this->lang->line('Sl.No'); ?></th>
        <th><?php echo $this->lang->line('Project Id'); ?></th>
		<th><?php echo $this->lang->line('Project Name'); ?> </th>
        <th><?php echo $this->lang->line('Post By'); ?> </th>
		<th><?php echo $this->lang->line('Start Date'); ?> </th>
		<th><?php echo $this->lang->line('End Date'); ?> </th>
       <form action="" name="manageProject" method="post">
	  <?php
			if(isset($projects) and $projects->num_rows()>0)
			{  $i=0;
				foreach($projects->result() as $project)
				{
				
		?>
		
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="projectList[]" id="projectList[]" value="<?php echo $project->id; ?>"  /> </td>
			  <td><?php echo $i=$i+1; ?> </td>
			  <td><?php echo $project->id; ?> </td>
			  <td><?php echo $project->project_name; ?>  </td>
			  <td><?php foreach($users->result() as $user) if($user->id == $project->creator_id) echo $user->user_name; ?> </td>
			  <td><?php echo date('Y-m-d',$project->created); ?> </td>	
			  <td><?php echo date('Y-m-d',$project->enddate); ?></td>
        	</tr>
		  
        <?php
				}//Foreach End 
			?>
			 <?php 	
			}//If End
			else
			{ 			
			  echo '<tr><td colspan="5">'.$this->lang->line('No Projects Found').'</td></tr>'; 
			}
		?>
	</form> 
		</table>
		<br />
    <div class="clscenter clearfix">
	  <div id="selLeftAlign">
	<?php echo $this->lang->line('With Selected'); ?>
		   <a name="edit" href="javascript: document.manageProject.action='<?php echo admin_url('skills/manageProjects'); ?>'; document.manageProject.submit();"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a>
           <a name="delete" href="javascript: document.manageProject.action='<?php echo admin_url('skills/deleteProjects'); ?>'; document.manageProject.submit();" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a></div>
	<!--PAGING-->
	  	<?php if(isset($pagination)) echo $pagination;?>
	 <!--END OF PAGING-->
      <!-- End clsTable-->
    </div>
	</div>
    <!-- End clsMainSettings -->
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
