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
          <h3><?php echo $this->lang->line('Release Affiliates Payments'); ?></h3>
        </div>
      </div>
    
       <table class="table" cellpadding="2" cellspacing="0">
        <th></th>
		<th><?php echo $this->lang->line('Sl.No'); ?></th>
		<th><?php echo $this->lang->line('Ref Id'); ?> </th>
        <th><?php echo $this->lang->line('Account Type'); ?> </th>
		<th><?php echo $this->lang->line('Payment'); ?> </th>
		<th><?php echo $this->lang->line('Release Payments'); ?> </th>
      
	  <?php
			if(isset($release_payments) and $release_payments->num_rows()>0)
			{  $i=0;
				foreach($release_payments->result() as $release_payment)
				{
				
		?>
		 <form action="" name="manageReleasePayments" method="post">
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="releaseList[]" id="releaseList[]" value="<?php echo $release_payment->id; ?>"  /></td>
			  <td><?php echo $i=$i+1; ?> </td>
			  <td><?php echo $release_payment->refid; ?>  </td>
			  <td><?php echo $release_payment->account_type; ?> </td>	
			  <td><?php echo $release_payment->total; ?> </td>	
			  <td><a href="<?php echo admin_url('affiliateSettings/releasedPayment/'.$release_payment->refid)?>"><?php echo $this->lang->line('Release'); ?></a> </td>	
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
