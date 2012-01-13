<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/datetimepicker.js"></script>

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
           <li><a href="<?php echo admin_url('packages/searchSubscriptionpayment');?>"><b><?php echo $this->lang->line('Search Subscription Payment'); ?></b></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('packages/viewsubscriptionpayment');?>"><b><?php echo $this->lang->line('View subscription Payment'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('Search Subscription Payment'); ?></h3>
        </div>
      </div>
      <!--END OF TOP TITLE & RESET-->
	  <br />
      <div class="clsTab">
	  <table class="table" cellpadding="2" cellspacing="0">
		 <form name="searchPackage" action="<?php echo admin_url('packages/searchSubscriptionpayment');?>" method="post">
		    
		     <tr><td class="clsName"><?php echo $this->lang->line('username'); ?></td><td class="clsMailIds"> <input type="Text" id="username" name="username" maxlength="20" size="20"></td>
			 </tr>
			 <tr><td></td><td><input type="submit" name="searchUsers" value="<?php echo $this->lang->line('search');?>" class="clsSub" /></td></tr>
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
