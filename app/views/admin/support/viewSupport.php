<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
      
    </div>
    <div class="clsMidWrapper">
      <!--MID WRAPPER-->
      <!--TOP TITLE & RESET-->
      <div class="clsTop clsClearFixSub">
         <div class="clsNav">
          <ul>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('support'); ?></h3>
        </div>
      </div>
      <!--END OF TOP TITLE & RESET-->
	  <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	  ?>
     
        <table class="table" cellpadding="2" cellspacing="0" width="100%">
          <th><?php echo $this->lang->line('Sl.No'); ?></th>
          <th><?php echo $this->lang->line('call id'); ?></th>
          <th><?php echo $this->lang->line('subject'); ?> </th>
          <th><?php echo $this->lang->line('description'); ?></th>
          <th><?php echo $this->lang->line('priority'); ?></th>
          <th><?php echo $this->lang->line('status'); ?></th>
          <th><?php echo $this->lang->line('user id'); ?></th>
		  <th><?php echo $this->lang->line('user mail'); ?></th> 
		  <th><?php echo $this->lang->line('options'); ?></th> 
		  <th><?php echo $this->lang->line('Response'); ?></th> 
		<?php
			if(isset($support) and $support->num_rows()>0)
			{
				foreach($support->result() as $support1)
				{
		?>
			 <tr>
			  <td width="5%"><?php echo $support1->id; ?></td>
			  <td width="10%"><?php echo $support1->callid; ?></td>
			  <td width="15%"><?php echo $support1->subject; ?></td>
			  <td width="30%"><?php echo $support1->description; ?></td>
			  <td width="2%"><?php echo $support1->priority; ?></td>
			    <td width="2%"><?php if(($support1->status!='') && ($support1->status=='0')) 
						  {
						  	echo "Open";
						  }
						  else if(($support1->status!='') && ($support1->status=='1')) 
						  {
						  		echo "Closed";
						  } 	
				?></td>
				 <td width="2%"><?php echo $support1->user_id; ?></td>
				  <td width="5%"><?php echo $support1->email; ?></td>
				
			   <td width="14%">
			    <a href="<?php echo admin_url('support/deleteSupport/'.$support1->id)?>"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a>
				<?php if($support1->status==1)
				{ ?>
				  <a title="Display" class="icon edit tip" href="<?php echo admin_url('support/close/'.$support1->id) ?>"><img src="<?php echo image_url('disable.png'); ?>" alt="Enable" title="Open it" /></a> <?php ?>
			<?php }
				else {   ?>
				  <a title="Display" class="icon edit tip" href="<?php echo admin_url('support/open/'.$support1->id); ?>"><img src="<?php echo image_url('enable.png'); ?>" alt="Disable" title="Close it" /></a> 
				 <?php } ?> 				
			    <a href="<?php echo admin_url('support/sendMail/'.$support1->email.'/'.$support1->id)?>"><img src="<?php echo image_url('reply.png'); ?>" alt="Reply" title="Reply"/></a>
			  </td>
			  
			   <td width="12%"><?php echo $support1->reply; ?></td>
			  
        	</tr>
        <?php
				}//Foreach End
			}//If End
			
		?>
		</table>
		
	 <?php if(isset($pagination))echo $pagination; ?>
	 
	 <table style="margin-top:10px">
	 <tr>
	 <td> <b>Priority</b>
	 <td> &nbsp;	Urgent - 1  High - 2  Normal - 3  Low - 4  Very Low - 5 </td>
	 </tr>
	 </table>
	 
	  	 	
    </div>
    <!--END OF MID WRAPPER-->
  </div>
  <!-- End of clsSettings -->
</div>
<!-- End Of Main -->
<?php $this->load->view('admin/footer'); ?>
