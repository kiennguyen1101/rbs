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
            <li class="clsNoBorder"><a href="<?php echo admin_url('affiliateSettings/searchQuestions')?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('View Affiliates Questions'); ?></h3>
        </div>
      </div>
      
       <table class="table" cellpadding="2" cellspacing="0">
        <th></th>
		<th><?php echo $this->lang->line('Sl.No'); ?></th>
        <th><?php echo $this->lang->line('Id'); ?></th>
		<th><?php echo $this->lang->line('Email'); ?> </th>
        <th><?php echo $this->lang->line('Subject'); ?> </th>
		<th><?php echo $this->lang->line('Questions'); ?> </th>
		<th><?php echo $this->lang->line('Answers'); ?> </th>
      
	  <?php
			if(isset($affiliate_guest) and $affiliate_guest->num_rows()>0)
			{  $i=0;
				foreach($affiliate_guest->result() as $affiliate_guestt)
				{
		?>
		 <form action="" name="manageQuestions" method="post">
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="questionsList[]" id="questionsList[]" value="<?php echo $affiliate_guestt->id; ?>"  /> </td>
			  <td><?php echo $i=$i+1; ?> </td>
			  <td><?php echo $affiliate_guestt->id; ?> </td>
			  <td><?php echo $affiliate_guestt->email; ?> </td>	
			  <td><?php echo $affiliate_guestt->subject; ?> </td>	
			  <td><?php echo $affiliate_guestt->questions; ?> </td>
			  <td><a href="<?php echo admin_url('affiliateSettings/replay/'.$affiliate_guestt->id)?>"><?php echo $this->lang->line('Reply'); ?></a></td>
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
		   <a name="edit" href="javascript: document.manageQuestions.action='<?php echo admin_url('affiliateSettings/manageQuestions'); ?>'; document.manageQuestions.submit();"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" onClick="javascript:return confirm(' Are you sure to edit this Questions?');"/></a>
           <!--<a name="delete" href="javascript: document.manageQuestions.action='<?php echo admin_url('affiliateSettings/deleteQuestions'); ?>'; document.manageQuestions.submit();"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a>-->
		   <a name="archive" href="javascript: document.manageQuestions.action='<?php echo admin_url('affiliateSettings/archiveQuestions'); ?>'; document.manageQuestions.submit();"><img src="<?php echo image_url('archive.png'); ?>" alt="Delete" title="Archive" width="20" height="20" onClick="javascript:return confirm(' Are you sure to archive this Questions?');"/></a></div>
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
