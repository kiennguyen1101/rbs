<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
      <?php
	  // if(isset($transactions)) pr($transactions);
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
        <div class="clsTitle">
          <h3><?php echo $this->lang->line('Amount Withdraw'); ?></h3>
        </div>
        <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('payments/searchTransaction');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
			<li><a href="<?php echo admin_url('payments/addTransaction');?>"><b><?php echo $this->lang->line('Add Transaction'); ?></b></a></li>
			<li><a href="<?php echo admin_url('payments/releaseEscrow');?>"><b><?php echo $this->lang->line('Escrow Release'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('payments/viewTransaction');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
      </div>
      <!--END OF TOP TITLE & RESET-->
	  
      <div class="clsTable">
		
		    
	    <form name="formPaypal" action="<?php echo $paymentGateways['paypal']['url']; ?>"  method="post">
		    <input type="hidden" name="creator_id"  value="<?php echo $transactions->creator_id; ?>" />
		    <input type="hidden" name="transaction_id" value="<?php echo $transactions->id; ?>" /> 
			<input type="hidden" value="_xclick" name="cmd"/>
			<input type="hidden" value="<?php echo $transactions->paypal_address; ?>" name="business"/>
			<input type="hidden" value="1" name="item_number"/>
			<input type="hidden" value="Withdraw Amount" name="item_name"/>
			<input type="hidden" value="0" name="on0"/>
			<input type="hidden" value="<?php echo $transactions->id; ?>" name="custom"/>
			<input type="hidden" value="USD" name="currency_code"/>
			<input type="hidden" value="" name="option_selection1" value="<?php echo $transactions->id; ?>" /> 
			<input type="hidden" value="<?php echo admin_url('payments/acceptWithdraw'); ?>" name="notify_url"/>
			<input type="hidden" value="<?php echo admin_url('payments/acceptReturn'); ?>" name="return"/>
			<input type="hidden" value="<?php echo admin_url('payments/viewWithdraw'); ?>" name="cancel_return"/>
            
			 
			 <p><label><b><?php echo $this->lang->line('Transaction Id'); ?></b></label><input type="text" name="trasactionId" readonly=""  value="<?php echo $transactions->id; ?>" /></p>
			 <p><label><b><?php echo $this->lang->line('Transaction From'); ?></b></label><input type="text" name="trasactionFrom" readonly="" value="<?php echo $transactions->creator_id; ?>" /></p>
			 <p><label><b><?php echo $this->lang->line('Paypal Address'); ?></b></label><input type="text" name="trasactionAddress" readonly="" value="<?php echo $transactions->paypal_address; ?>" /></p>
			 <p><label><b><?php echo $this->lang->line('Description'); ?></b></label><input type="text" name="trasactionDescription" readonly="" value="<?php echo $transactions->description; ?>" /></p>
 			 <p><label><b><?php echo $this->lang->line('Amount'); ?></b></label><input type="text" name="amount" value="<?php echo $transactions->amount; ?>" /></p>
			 <p><input type="submit" name="withdrawAmount" value="<?php echo $this->lang->line('Submit');?>" class="clsSubmitBt1" /></p>
		 </form>
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
