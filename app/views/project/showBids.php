<div id="selProjectBids" class="clsMarginTop">
  <!--RC-->
  <div class="block">
    <div class="black_t">
      <div class="black_r">
        <div class="black_b">
          <div class="black_l">
            <div class="black_tl">
              <div class="black_tr">
                <div class="black_bl">
                  <div class="black_br clsZero">
                    <div class="cls100_p">
                      <h3><span class="clsHigh"><?php echo $this->lang->line('Project Bids');?></span></h3>
                      <p class="clsHead clsClearFix">
					  <table cellspacing="1" cellpadding="2" width="96%">
                                <tbody><tr>
                                  <td width="15%" class="dt"><?php echo $this->lang->line('Programmers');?></td>
								  <td width="100" class="dt"><?php echo $this->lang->line('Bids'); ?></td>
								  <td width="100" class="dt"><?php echo $this->lang->line('Delivery Time');?></td>
								  <td width="100" class="dt"><?php echo $this->lang->line('Time of Bid');?></td>
								  <td width="100" class="dt"><?php echo $this->lang->line('Rating');?></td>
                                </tr>
                      
                      <?php $i=0;
						  	if(isset($bids) and $bids->num_rows()>0)
							{ 
							foreach($bids->result() as $bid)
								{ $i++;
								if($i%2==0)
								  $class = "dt1 dt0";
							    else
								  $class = "dt2 dt0"; 	  
							?>
                      
					          <tr class="<?php echo $class;?>"><td ><a href="<?php echo site_url('programmer/viewProfile/'.$bid->uid);?>"><?php echo $bid->user_name; 
					             //Get the Favourite and Blocked users
								 if(isset($favourite))
								     {
									   foreach($favourite->result() as $result)
									     { 
										    if($result->user_id == $bid->user_id)
											  {
											    if($result->user_role == '1')
													{ ?>
													 <img src="<?php echo image_url('star.jpg'); ?>" title="Favourite User" /> <?php 
													} 
												if($result->user_role == '2')
													{ ?>
													 <img src="<?php echo image_url('cross.jpg'); ?>" title="Blocked User"  /> <?php 
													}	
											 }
										  }
										}  	?></a></td><td>$<?php echo $bid->bid_amount;?></td><td>
										<?php if($bid->bid_hours == 0 && $bid->bid_days == 0) 
											echo $this->lang->line('Immediately'); elseif($bid->bid_days != 0) echo $bid->bid_days.$this->lang->line('days');?>&nbsp;
                        <?php if($bid->bid_hours != 0) echo $bid->bid_hours." ".$this->lang->line('hours');?>
                        </td><td><?php echo get_datetime($bid->bid_time);?></td><td>
                        <?php if($bid->num_reviews == 0)
							echo '(No Feedback Yet) ';
							else{ ?>
                         <img height="7" border="0" width="81" alt="10.00/10" src="<?php echo image_url('rating_'.$bid->user_rating.'.gif');?>"/> ( <b><?php echo $bid->num_reviews;?> </b> <a href="<?php echo site_url('programmer/review/'.$bid->uid);?>"><?php echo $this->lang->line('reviews');?></a> )
                        <?php } ?>
						<?php 
							if(isset($this->loggedInUser->id))
							  { ?>
								<a href="<?php echo site_url('project/postBidReport/'.$bid->id); ?>"><img src="<?php echo image_url('icons.png'); ?>" height="28" width="21" alt="Report Project Violation" title="Report Project Violation"/> </a>	<?php
							  } else { ?>
								<a href="<?php echo site_url('users/login'); ?>"><img src="<?php echo image_url('icons.png'); ?>" height="28" width="21" alt="Report Project Violation"/> </a> <?php 
							  }
						  ?>
                        </td></tr>
                      <?php } }
					  else{
					  	if($projectRow->is_hide_bids == 1)
							echo ' <tr class="dt2 dt0"><td colspan=5><a href="'.site_url('buyer/viewProfile/'.$creatorInfo->id).'">'.$creatorInfo->user_name."</a> ".$this->lang->line('hidden_bids').'.</td></tr>';
						else
					  		echo '<tr class="dt2 dt0"><td colspan=5>'.$this->lang->line('no_bids').'.</td></tr>';
					  }
					  ?></tbody></table> 
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
  <!--END OF RC-->
</div>
<?php 
	if($projectRow->project_status == 0)
	{
		if(count($tot) > 0)
			$toDisp = $this->lang->line('Edit Bid');
		else
			$toDisp = $this->lang->line('Place Bid');
		?>
		<?php 
		//Check for the project open date is end or not
		if(days_left($projectRow->enddate,$projectId) != 'Closed')
		 {  ?>
		   <p><a href="<?php echo site_url('project/postBid/'.$projectId); ?>" class="buttonBlackShad"><span><?php echo $toDisp;?></span></a></p><br /><?php 
		 }
	}?>