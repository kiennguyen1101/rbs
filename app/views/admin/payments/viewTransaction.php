<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
      <?php
	   //if(isset($transactions)) pr($transactions->result());
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
            <li><a href="<?php echo admin_url('payments/searchTransaction');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
			<li><a href="<?php echo admin_url('payments/addTransaction');?>"><b><?php echo $this->lang->line('Add Transaction'); ?></b></a></li>
			<!--<li><a href="<?php echo admin_url('payments/releaseEscrow');?>"><b><?php echo $this->lang->line('Escrow Release'); ?></b></a></li>-->
			<li class="clsNoBorder"><a href="<?php echo admin_url('payments/viewTransaction');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
        <div class="clsTitle">
          <h3><?php echo $this->lang->line('View All'); ?></h3>
        </div>
           </div>
      <!--END OF TOP TITLE & RESET-->
	 
	  <table class="table" cellpadding="2" cellspacing="2">
		<thead>
		<tr>    	<th class="td1"><?php echo $this->lang->line('Sl.No'); ?></th>
					<th class="td2"><?php echo $this->lang->line('Description'); ?></th>
					 <th class="td3"><?php echo $this->lang->line('Transaction From'); ?></th>
					  <th class="td4"><?php echo $this->lang->line('Transaction To'); ?></th>
					 <th class="td5"><?php echo $this->lang->line('Amount'); ?></th>
					  <th class="td6"><?php echo $this->lang->line('Date'); ?></th>
					 <th class="td7"><?php echo $this->lang->line('Status'); ?></th></tr>
</thead>
<tbody>



<?php
			if(isset($transactions) and $transactions->num_rows()>0)
			{
				foreach($transactions->result() as $transactions)
				{
		?>
			 <tr>
			 <td><?php echo $transactions->id; ?></td>
			 <td><?php echo $transactions->type; ?></td>
			  <td>  <?php foreach($usersList->result() as $users) { if($users->id == $transactions->creator_id) {?>
			      <a href="<?php echo site_url().$transactions->creator_id; ?>">  <?php echo $users->user_name; ?></a>
			      <?php } } ?>	 </td> 
			   <td> <?php
			     foreach($usersList->result() as $users)
				   { 
				     if($transactions->reciever_id != '')
					   { 
					     if($users->id == $transactions->reciever_id) 
					     {  
				            echo $users->user_name; 
			             }
						}
					if($transactions->reciever_id == '')
					   { 
					     if($users->id == $transactions->creator_id) 
					     {  
				            echo $users->user_name; 
			             }
						}	
					} ?>
			  </td>
			   <td><?php echo $transactions->amount; ?></td>
			  <td><?php echo get_datetime($transactions->transaction_time); ?></td>
			  <td><?php echo $transactions->status; ?></td>
			  
			  
        	</tr>
        <?php
				}//Foreach End
			}//If End
			else
			{ 			
			  echo '<tr><td colspan=7>'.$this->lang->line('No Transaction Found').'</td></tr>'; 
			}
		?>
</tbody>
</table><br />
   
	  <!--PAGING-->
	  	<?php if(isset($pagination_outbox)) echo $pagination_outbox;?>
	 <!--END OF PAGING-->
    </div>
    <!--END OF MID WRAPPER-->
  </div>
  <!-- End of clsSettings -->
</div>
<!-- End Of Main -->
<?php $this->load->view('admin/footer'); ?>
