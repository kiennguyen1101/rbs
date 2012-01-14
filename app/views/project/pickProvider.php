<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<div id="main">
      <!--POST PROJECT-->
      <div class="clsInnerpageCommon">
        <div class="block">
          <div class="inner_t">
            <div class="inner_r">
              <div class="inner_b">
                <div class="inner_l">
                  <div class="inner_tl">
                    <div class="inner_tr">
                      <div class="inner_bl">
                        <div class="inner_br">
                          <div class="cls100_p">
                            <div class="clsInnerCommon">
							 <form method="post" action="<?php echo site_url('project/awardBid');?>">
								<h2><?php echo $this->lang->line('Pick Provider');?></h2>
								<table cellpadding="2" cellspacing="1" width="96%">
								  <tr>
								  <td width="10%" class="dt">
									<?php echo $this->lang->line('Pick');?></td> <td width="20%" class="dt"> <?php echo $this->lang->line('Sellers');?> </td><td width="20%" class="dt"> <?php echo $this->lang->line('Bid');?> </td><td width="20%" class="dt"> <?php echo $this->lang->line('Delivery Time');?> </td><td width="20%" class="dt"> <?php echo $this->lang->line('Time of Bid');?> </td></tr>
								  <?php
									if(isset($bids) and $bids->num_rows()>0)
									{
										$i=1;
										foreach($bids->result() as $bid)
										{
											if($i%2==0)
											  {
												$class = 'dt1 dt0';
												$class2 = 'dt1';
											  }	
											else 
											  {
												$class = 'dt2 dt0';
												$class2 = 'dt2';
											  }	
											?>
								  <tr class="<?php echo $class; ?>">
									<td class="<?php echo $class2; ?>"> 
									<input type="radio" name="bidid" value="<?php echo $bid->id;?>" <?php if($i ==0) echo "checked";?>/></td>
									<td class="<?php echo $class2; ?>"> <a href="<?php echo site_url('seller/viewProfile/'.$bid->user_id);?>"><?php echo $bid->user_name;?></a>
									<?php foreach($favourite->result() as $favourite1)
									     { 
										   if($favourite1->user_id == $bid->user_id) 
										    { 
											  if($favourite1->user_role == '1') { ?> <img src="<?php echo image_url('favorite.png');?>" alt="Blocked User" /><?php }
											  if($favourite1->user_role == '2') { ?> <img src="<?php echo image_url('delete.png');?>" alt="Blocked User" /><?php }
											}
										 } ?>
									</td>
									<td class="<?php echo $class2; ?>">  $<?php echo $bid->bid_amount;?> </td> 
									<td class="<?php echo $class2; ?>">  <?php echo $bid->bid_days.$this->lang->line('days');?>&nbsp;<?php if($bid->bid_hours != 0) echo $bid->bid_hours.$this->lang->line('hours');?> </td> 
									<td class="<?php echo $class2; ?>">  <?php echo get_datetime($bid->bid_time);?> </td> 
									
									</tr>
								  <?php		
											$i++;						
										}//For Each End - Latest Project Traversal															
									}//If - End Check For Latest Projects
								  ?>
								
							   </table>
							   <p><input type="submit" name="pickBid" class="clsSmall" value="<?php echo $this->lang->line('Pick Bid');?>"></p> 
							 </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--END OF POST PROJECT-->
    </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>