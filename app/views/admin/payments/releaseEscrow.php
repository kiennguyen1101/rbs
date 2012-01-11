<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
      <?php
	   //if(isset($transactions)) pr($transactions);
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
            <!--<li><a href="<?php echo admin_url('payments/searchTransaction');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>-->
			<!--<li><a href="<?php echo admin_url('payments/addTransaction');?>"><b><?php echo $this->lang->line('Add Transaction'); ?></b></a></li>-->
			<li><a href="<?php echo admin_url('payments/releaseEscrow');?>"><b><?php echo $this->lang->line('Escrow Release'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('payments/viewEscrow');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('Escrow Release'); ?></h3>
        </div>
      </div>
	  <br />
      <!--END OF TOP TITLE & RESET-->
	  
      <div class="clsTab">
    	  <table class="table" cellpadding="2" cellspacing="0">
		  <tr>
		    <th><?php echo $this->lang->line('Sl.No'); ?></li></th>
            <th><?php echo $this->lang->line('Description'); ?></th>
		    <th><?php echo $this->lang->line('Transaction From'); ?> </th>
	        <th><?php echo $this->lang->line('Transaction To'); ?></th>
	        <th><?php echo $this->lang->line('Amount'); ?></th>
            <th><?php echo $this->lang->line('Date'); ?></th>
            <th><?php echo $this->lang->line('Status'); ?></th>
			<th><?php echo $this->lang->line('Options'); ?></th>
		  </tr>	

		<?php $i=0;
			if(isset($transactions) and $transactions->num_rows()>0)
			{
				foreach($transactions->result() as $transactions)
				{//pr($transactions);
		?>
			<tr>	
			  <td><?php echo $i=$i+1; ?></td>
              <td><?php echo $transactions->type; ?></td>
			<td>  <?php foreach($usersList->result() as $users) { if($users->id == $transactions->creator_id) {?>
			      <a href="<?php echo site_url().$transactions->creator_id; ?>">
				  <?php echo $users->user_name; ?></a>
			      <?php } } ?>	 
				  </td>
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
			  <td><?php echo get_date($transactions->transaction_time); ?></td>
			  <td><?php echo $transactions->status; ?></td>
        	
			 <td>
			    <a href="<?php echo admin_url('payments/acceptEscrow/'.$transactions->id);?>"><?php echo $this->lang->line('Accept'); ?></a>&nbsp;&nbsp;&nbsp;
			    <a href="<?php echo admin_url('payments/deniedEscrow/'.$transactions->id);?>"><?php echo $this->lang->line('Denied'); ?></a>
			  </td>			  			  
        	</tr>
        <?php
				}//Foreach End
			}//If End
			else
			{ 			
			  echo '<tr><td colspan="8">'.$this->lang->line('No Transaction Found').'</td></tr>'; 
			}
		?>
		</table>
      </div>
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
