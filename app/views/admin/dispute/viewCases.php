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
	 <!--TOP TITLE & RESET-->
        <div class="clsTop clsClearFixSub">
		<div class="clsNav3">
		  <div class="clsTitle1">
          <h3><?php echo $this->lang->line('view cases'); ?></h3>
        </div>  
    
      </div>
      <!--END OF TOP TITLE & RESET-->
    </div>
    <div class="clsMidWrapper">
      <!--MID WRAPPER-->
     
      
        <table class="table" cellpadding="2" cellspacing="0" align="left">
		  <tr>
          <th><?php echo $this->lang->line('case_id'); ?></th>
		  <th><?php echo $this->lang->line('Project'); ?></th>
		  <th><?php echo $this->lang->line('case_type'); ?></th>
		  <th><?php echo $this->lang->line('case_reason'); ?></th>
		  <th><?php echo $this->lang->line('opened_by'); ?></th>
		  <th><?php echo $this->lang->line('view'); ?></th>
		  </tr>
		        
		<?php
		   	if(isset($projectCases))
			{
				foreach($projectCases->result() as $projectCases)
				{ 
					
		?>
			 <tr>
			  <td><?php echo $projectCases->id; ?></td>
			  <td><a href="<?php echo admin_url('skills/projectDeatils/'.$projectCases->project_id);?>"><?php echo $projectCases->project_name; ?></a></td>
			  <td><?php echo $projectCases->case_type;?></td>
			  <td><?php echo $projectCases->case_reason; ?></td>
			  <td><a href="<?php echo admin_url('users/userDetails/'.$projectCases->user_id);?>"><?php echo getUserDetails($projectCases->user_id,'user_name');?></a></td>
		<td>
			  <a href="<?php echo admin_url('projectCases/view/'.$projectCases->id)?>"><?php echo $this->lang->line('view'); ?></a></td>
        	</tr>
        <?php
				}//Foreach End
			}//If End
		?>
		</table>
     
    </div>
    <!--END OF MID WRAPPER-->
  </div>
  <!-- End of clsSettings -->
</div>
</div>
<!-- End Of Main -->
<?php $this->load->view('admin/footer'); ?>