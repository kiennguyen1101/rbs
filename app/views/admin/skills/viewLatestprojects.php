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
      <div class="clsTable">
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
					  $date1 = time();
					  $date2 = $project->created;
					  $dateDiff = $date1 - $date2;
					  $days = floor($dateDiff/(60*60*24));
					  
					  //Get todays Post projects
					  if($days == 0)
						{ 
						  if(isset($today))
				           { ?>
								
								 <tr>
								  <td><input type="hidden" name="today" id="today" value="today" /> <input class="clsNoborder" type="checkbox" name="projectList[]" id="projectList[]" value="<?php echo $project->id; ?>"  /> </td>
								  <td><?php echo $i=$i+1; ?> </td>
								  <td><?php echo $project->id; ?> </td>
								  <td><?php echo $project->project_name; ?>  </td>
								  <td><?php foreach($users->result() as $user) if($user->id == $project->creator_id) echo $user->user_name; ?> </td>
								  <td><?php echo date('Y-m-d',$project->created); ?> </td>	
								  <td><?php echo date('Y-m-d',$project->enddate); ?></td>
								</tr>
								
								<?php
							}
							//Get latest open projects
							if(isset($todayOpen))
							{
								if($project->project_status == '0')
								  {?>
									 
									 <tr>
									  <td><input type="hidden" name="todayOpen" id="todayOpen" value="todayOpen" /> <input class="clsNoborder" type="checkbox" name="projectList[]" id="projectList[]" value="<?php echo $project->id; ?>"  /> </td>
									  <td><?php echo $i=$i+1; ?> </td>
									  <td><?php echo $project->id; ?> </td>
									  <td><?php echo $project->project_name; ?>  </td>
									  <td><?php foreach($users->result() as $user) if($user->id == $project->creator_id) echo $user->user_name; ?> </td>
									  <td><?php echo date('Y-m-d',$project->created); ?> </td>	
									  <td><?php echo date('Y-m-d',$project->enddate); ?></td>
									</tr>
									
								<?php
								}	
							}
							//Get latest open projects
							if(isset($todayClosed))
							{
								if($project->project_status == '2')
								  {?>
									
									 <tr>
									  <td><input type="hidden" name="todayClosed" id="todayClosed" value="todayClosed" /> <input class="clsNoborder" type="checkbox" name="projectList[]" id="projectList[]" value="<?php echo $project->id; ?>"  /> </td>
									  <td><?php echo $i=$i+1; ?> </td>
									  <td><?php echo $project->id; ?> </td>
									  <td><?php echo $project->project_name; ?>  </td>
									  <td><?php foreach($users->result() as $user) if($user->id == $project->creator_id) echo $user->user_name; ?> </td>
									  <td><?php echo date('Y-m-d',$project->created); ?> </td>	
									  <td><?php echo date('Y-m-d',$project->enddate); ?></td>
									</tr>
									
								<?php
								}	
							} 
						}	//Today Proejct End
						
					 //Get this week Post projects
					  if($days < 8)
						{ 
						  if(isset($thisWeek))
				           { ?>
								
								 <tr>
								  <td><input type="hidden" name="thisWeek" id="thisWeek" value="thisWeek" /> <input class="clsNoborder" type="checkbox" name="projectList[]" id="projectList[]" value="<?php echo $project->id; ?>"  /> </td>
								  <td><?php echo $i=$i+1; ?> </td>
								  <td><?php echo $project->id; ?> </td>
								  <td><?php echo $project->project_name; ?>  </td>
								  <td><?php foreach($users->result() as $user) if($user->id == $project->creator_id) echo $user->user_name; ?> </td>
								  <td><?php echo date('Y-m-d',$project->created); ?> </td>	
								  <td><?php echo date('Y-m-d',$project->enddate); ?></td>
								</tr>
								
								<?php
							}
				        } // This month project end
						
					 //Get this month Post projects
					  if($days < 30)
						{ 
						  if(isset($thisMonth))
				           { ?>
								 
								 <tr>
								  <td><input type="hidden" name="thisMonth" id="thisMonth" value="thisMonth" /> <input class="clsNoborder" type="checkbox" name="projectList[]" id="projectList[]" value="<?php echo $project->id; ?>"  /> </td>
								  <td><?php echo $i=$i+1; ?> </td>
								  <td><?php echo $project->id; ?> </td>
								  <td><?php echo $project->project_name; ?>  </td>
								  <td><?php foreach($users->result() as $user) if($user->id == $project->creator_id) echo $user->user_name; ?> </td>
								  <td><?php echo date('Y-m-d',$project->created); ?> </td>	
								  <td><?php echo date('Y-m-d',$project->enddate); ?></td>
								</tr>
								
								<?php
							}
				        } // This month project end
				//Get this month Post projects
					  if($days < 366)
						{ 
						  if(isset($thisYear))
				           { ?>
								
								 <tr>
								  <td><input type="hidden" name="thisYear" id="thisYear" value="thisYear" /> <input class="clsNoborder" type="checkbox" name="projectList[]" id="projectList[]" value="<?php echo $project->id; ?>"  /> </td>
								  <td><?php echo $i=$i+1; ?> </td>
								  <td><?php echo $project->id; ?> </td>
								  <td><?php echo $project->project_name; ?>  </td>
								  <td><?php foreach($users->result() as $user) if($user->id == $project->creator_id) echo $user->user_name; ?> </td>
								  <td><?php echo date('Y-m-d',$project->created); ?> </td>	
								  <td><?php echo date('Y-m-d',$project->enddate); ?></td>
								</tr>
								
								<?php
							}
				        } // This month project end
				
				
		     ?>
			 
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
    </div>
	  <div id="selLeftAlign">
	  <?php if(isset($projects) and $projects->num_rows()>0) {  ?>
	<?php echo $this->lang->line('With Selected'); ?>

		   <a name="edit" href="javascript: document.manageProject.action='<?php echo admin_url('skills/manageProjects'); ?>'; document.manageProject.submit();"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a>
           <a name="delete" href="javascript: document.manageProject.action='<?php echo admin_url('skills/deleteProjects'); ?>'; document.manageProject.submit();"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a><?php } ?></div> 

<!--PAGING-->
	  	<?php if(isset($pagination)) echo $pagination;?>
	 <!--END OF PAGING-->
      <!-- End clsTable-->
    </div>
    <!-- End clsMainSettings -->
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
