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
            <li class="clsNoBorder"><a href="<?php echo admin_url('affiliateSettings/searchClickThroughs')?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('View ClickThroughs'); ?></h3>
        </div>
      </div>
    
       <table class="table" cellpadding="2" cellspacing="0">
        <th></th>
		<th><?php echo $this->lang->line('Sl.No'); ?></th>
       <th><?php echo $this->lang->line('id'); ?></th>
		<th><?php echo $this->lang->line('Ref Id'); ?> </th>
        <th><?php echo $this->lang->line('Created'); ?> </th>
		<th><?php echo $this->lang->line('Browser'); ?> </th>
		<th><?php echo $this->lang->line('IpAddress'); ?> </th>
		<th><?php echo $this->lang->line('Refferal Url'); ?> </th>
		<th><?php echo $this->lang->line('Buy'); ?> </th>
      
	  <?php
			if(isset($click_throughs) and $click_throughs->num_rows()>0)
			{  $i=0;
				foreach($click_throughs->result() as $click_through)
				{
		?>
		 <form action="" name="manageClickThroughs" method="post">
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="clickThroughList[]" id="clickThroughList[]" value="<?php echo $click_through->id; ?>"  /> </td>
			  <td><?php echo $i=$i+1; ?> </td>
			 	  <td><?php echo $click_through->id; ?>  </td>
			  <td><?php echo $click_through->refid; ?>  </td>
			  <td><?php echo $click_through->created_date; ?> </td>	
			  <td><?php echo $click_through->browser; ?> </td>	
			  <td><?php echo $click_through->ipaddress; ?> </td>
			  <td><?php echo $click_through->refferalurl; ?> </td>
			  <td><?php echo $click_through->buy; ?> </td>
        	</tr>
		  
        <?php
				}//Foreach End 
			?>
			 <?php 	
			}//If End
			else
			{ 			
			  echo '<tr><td colspan="8">'.$this->lang->line('No Projects Found').'</td></tr>'; 
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
