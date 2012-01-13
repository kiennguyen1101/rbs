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
        
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('View Archived Questions'); ?></h3>
        </div>
      </div>
    
       <table class="table" cellpadding="2" cellspacing="0">
        <th></th>
		<th><?php echo $this->lang->line('Sl.No'); ?></th>
        <th><?php echo $this->lang->line('Id'); ?></th>
		<th><?php echo $this->lang->line('Email'); ?> </th>
        <th><?php echo $this->lang->line('Subject'); ?> </th>
		<th><?php echo $this->lang->line('Questions'); ?> </th>
		<th><?php echo $this->lang->line('Answer'); ?> </th>
      
	  <?php
			if(isset($affiliate_archive) and $affiliate_archive->num_rows()>0)
			{  $i=0;
				foreach($affiliate_archive->result() as $affiliate_archivee)
				{
		?>
		 <form action="" name="manageArchives" method="post">
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="archivesList[]" id="archivesList[]" value="<?php echo $affiliate_archivee->id; ?>"  /> </td>
			  <td><?php echo $i=$i+1; ?> </td>
			  <td><?php echo $affiliate_archivee->id; ?> </td>
			  <td><?php echo $affiliate_archivee->email; ?> </td>	
			  <td><?php echo $affiliate_archivee->subject; ?> </td>	
			  <td><?php echo $affiliate_archivee->questions; ?> </td>
			  <td><?php echo $affiliate_archivee->answer; ?> </td>
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
		   <a name="edit" href="javascript: document.manageArchives.action='<?php echo admin_url('affiliateSettings/manageArchives'); ?>'; document.manageArchives.submit();"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" onClick="javascript:return confirm(' Are you sure to edit this Archives?');"/></a>
           <a name="delete" href="javascript: document.manageArchives.action='<?php echo admin_url('affiliateSettings/deleteArchives'); ?>'; document.manageArchives.submit();"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" onClick="javascript:return confirm(' Are you sure to delete this Archives?');" /></a>
		  </div>
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
