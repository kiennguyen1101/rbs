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
            <li class="clsNoBorder"><a href="<?php echo admin_url('affiliateSettings/searchAffiliateSales')?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('View Affiliates Sales'); ?></h3>
        </div>
      </div>
    
       <table class="table" cellpadding="2" cellspacing="0">
        <th></th>
		<th><?php echo $this->lang->line('Sl.No'); ?></th>
        <th><?php echo $this->lang->line('Referral'); ?></th>
		<th><?php echo $this->lang->line('Affiliate'); ?> </th>
        <th><?php echo $this->lang->line('Created'); ?> </th>
		<th><?php echo $this->lang->line('Browser'); ?> </th>
		<th><?php echo $this->lang->line('IpAddress'); ?> </th>
		<th><?php echo $this->lang->line('Payment'); ?> </th>
      
	  <?php
			if(isset($affiliate_sales) and $affiliate_sales->num_rows()>0)
			{  $i=0;
				foreach($affiliate_sales->result() as $affiliate_sale)
				{
		?>
		 <form action="" name="manageSales" method="post">
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="salesList[]" id="salesList[]" value="<?php echo $affiliate_sale->id; ?>"  /> </td>
			  <td><?php echo $i=$i+1; ?> </td>
			  <td><?php echo $affiliate_sale->referral; ?> </td>
			  <td><?php echo $affiliate_sale->refid; ?>  </td>
			  <td><?php echo $affiliate_sale->created_date; ?> </td>	
			  <td><?php echo $affiliate_sale->browser; ?> </td>	
			  <td><?php echo $affiliate_sale->ipaddress; ?> </td>
			  <td><?php echo $affiliate_sale->payment; ?> </td>
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
    <!-- End clsMainSettings -->
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
