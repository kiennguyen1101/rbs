<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
      <?php
	   //if(isset($usersList)) pr($usersList);
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
 <h3><?php echo $this->lang->line('Search'); ?></h3>
        </div>
      </div>
      <!--END OF TOP TITLE & RESET-->

      <div class="clsTab">
	  <table class="table" cellpadding="2" cellspacing="0">
		 <form name="searchTransaction" action="<?php echo admin_url('payments/searchTransaction');?>" method="post">
		    
		     <tr><td class="clsName"><?php echo $this->lang->line('Enter Transaction Id'); ?></td><td class="clsMailIds"><input type="text" name="trasactionid" id="trasactionid" /></td></tr>
			 <tr><td></td><td><input type="submit" name="search" value="<?php echo $this->lang->line('search');?>" class="clsSub" /></td></tr>
		 </form>
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
