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
          <h3><?php echo $this->lang->line('Report Violation'); ?></h3>
        </div>
      </div>
     
       <table class="table" cellpadding="2" cellspacing="0">
        
		<th><?php echo $this->lang->line('Sl.No'); ?></th>
        <th><?php echo $this->lang->line('Project Id'); ?></th>
		<th><?php echo $this->lang->line('Project Name'); ?> </th>
        <th><?php echo $this->lang->line('Post By'); ?> </th>
		<th><?php echo $this->lang->line('Post Date'); ?> </th>
		<th><?php echo $this->lang->line('Report Type'); ?> </th>
        <th><?php echo $this->lang->line('Options'); ?> </th>
	  <?php
			if(isset($reportViolation) and $reportViolation->num_rows()>0)
			{  $i=0;
				foreach($reportViolation->result() as $report)
				{
		?>
		<!--	 <form action="" name="manageProject" method="post">-->
			 <tr>
			  
			  <td><?php echo $i=$i+1; ?> </td>
			  
			  <td><?php echo $report->project_id; ?>  </td>
			  <td><?php echo $report->project_name; ?> </td>
			  <td><?php foreach($users->result() as $user) if($user->id == $report->post_id) echo $user->user_name; ?> </td>	
			  <td><?php echo date('Y-m-d',$report->report_date); ?></td>
			  <td><?php echo $report->report_type; ?></td>
			  <td> <a name="delete" href="<?php echo admin_url('skills/deleteReport/'.$report->id); ?>"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a></td>
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
		<!--</form>-->
		</table><br />
    
	 
	<!--PAGING-->
	  	<?php if(isset($pagination)) echo $pagination;?>
	 <!--END OF PAGING-->
      <!-- End clsTable-->
    </div>
    <!-- End clsMainSettings -->
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
