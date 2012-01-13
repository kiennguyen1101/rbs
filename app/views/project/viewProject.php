<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<?php
		//Get Project Info
     	$project = $projects->row();
?>

<style>
.clsIcons{
margin-right:20px;
}
</style>

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
							<?php  if($project->flag==0) {?>
							
                              <h2><?php echo $this->lang->line('Project');?>: <?php echo $project->project_name; ?></h2><? }else {?>
							  <h2><?php echo $this->lang->line('Job Listing');?>: <?php echo $project->project_name; ?></h2>
							  <?php }?>
							<div class="clsHeads clearfix">

                            <div class="clsHeadingLeft clsFloatLeft">
                         <?php  if($project->flag==0) {?>
                              <h3><span class="clsViewPro"><?php echo 'View Project'; ?></span></h3><?php }else {?>
							   <h3><span class="clsViewPro"><?php echo 'View JobList'; ?></span></h3><?php }?>

                            </div>

                            <div class="clsHeadingRight clsFloatRight">
							<p class="clsFloatRight">
							<?php
							
							
			
							 if(isset($loggedInUser->role_id))
    	 					 {
									if($loggedInUser->role_id =='1' && ($project->project_status == 0 || $project->project_status == 1) && $loggedInUser->id==$project->creator_id && $project->seller_id==0)
								{ 
									
							echo '<a class="clsIcons clsFloatLeft" href="'.site_url('project/cancelProject/'.$project->id).'"><img alt="Cancel" title="Cancel" src="'.image_url('cancel.png').'"/></a>'; 
									   echo '<a class="clsIcons clsFloatLeft" href="'.site_url('project/extendBid/'.$project->id).'"><img alt="Extend" title="Extend" src="'.image_url('extend.png').'"/></a>'; 
			    		}
					}	  				
							
							
							
							
							
							 if(isset($loggedInUser->role_id))

							   {

								if($loggedInUser->role_id =='1' and $project->flag==0) 

								   {	 ?>

                            <span class="clsPostProject"> <a class="buttonBlack" href="<?php if (!isset($loggedInUser->role_id)) echo site_url('users/login'); else  echo site_url('project/postProject/'.$project->id); ?>"><span><?php echo $this->lang->line('Post Similar Project'); ?></span></a></span>
							   <?php } else {?>
							   <span class="clsPostProject"> <a class="buttonBlack" href="<?php if (!isset($loggedInUser->role_id)) echo site_url('users/login'); else  echo site_url('joblist/postjoblist/'.$project->id); ?>"><span><?php echo $this->lang->line('Post Similar Job'); ?></span></a></span>
							   
							   
							   <?php }?>
							   <span class="clsManage">

                                <?php

							//Make only this featured properties only for seller to make featurd

							

							if(isset($loggedInUser))

							  {

								if (isset($loggedInUser->role_id) =='1' and $project->flag==0) 

								   { ?>

                                <a class="buttonBlack" href="<?php echo site_url('project/manageProject/'.$project->id); ?>"><span><?php echo $this->lang->line('Manage');?></span></a>

                                <?php } 
								else
								{?>
								
								 <a class="buttonBlack" href="<?php echo site_url('joblist/manageJoblist/'.$project->id); ?>"><span><?php echo $this->lang->line('Manage');?></span></a>
								
								<?php }

							  }

							if (!isset($loggedInUser->role_id))

							   {	 ?>

                                <a href="<?php echo site_url('users/login'); ?>"><?php echo $this->lang->line('Manage');?></a>

                                <?php } ?>

                                </span>

                             
							  
							  
							  
							    <?php

								   } 
							   
							   ?>
				<span class="clsBookMark"><a class="buttonBlack" href="<?php if (!isset($loggedInUser->role_id)) echo site_url('users/login'); else echo site_url('bookMark/'.$project->id); ?>"><span><?php echo $this->lang->line('Book Mark'); ?></span></a></span>
				
							
				         <?php
						 

							if(isset($loggedInUser->role_id))

							   {

								if($loggedInUser->role_id =='2') 

								   {	
								    ?>

                                <a href="<?php  echo site_url('userList/addFavouriteUsers/'.$project->userid); ?>"><img src="<?php echo image_url('star_g.gif'); ?>" width="21" height="28" title="Add To Favourite"  alt="Add To Favourite" /> </a> <a href="<?php echo site_url('userList/addBlockedUsers/'.$project->userid); ?>"><img src="<?php echo image_url('block_g.gif'); ?>" width="21" height="28" alt="BlackList User" title="BlackList User"/> </a> <a href="<?php echo site_url('project/postReport/'.$project->id); ?>"><img src="<?php echo image_url('com_g.gif'); ?>" height="28" width="21" alt="Report Project Violation" title="Report Project Violation"/> </a>

                                <?php 

								   } 

							   }

							   else

							   { ?>

                                <a href="<?php  echo site_url('users/login'); ?>"><img src="<?php echo image_url('star_g.gif'); ?>" width="21" height="28"  alt="Add To Favourite"/> </a> <a href="<?php echo site_url('users/login'); ?>"><img src="<?php echo image_url('block_g.gif'); ?>" width="21" height="28" alt="BlackList User"/> </a> <a href="<?php echo site_url('users/login'); ?>"><img src="<?php echo image_url('com_g.gif'); ?>" height="28" width="21" alt="Report Project Violation"/> </a>

                                <?php } 

							   ?>
</p>
							
                            </div>
							
							 
							
							
                          </div>
						  	<?php
								//Show Flash error Message  for deposit minimum amount
								if($msg = $this->session->flashdata('flash_message'))
								{
								  echo $msg;
								}
							
								?>
                          <table cellspacing="1" cellpadding="2" width="96%" class="clsSitelinks">
						  <?php if($project->flag==0)	
						         {
						           ?>
                            <tbody>
                              <tr>
                                <td width="15%" class="dt"><?php echo $this->lang->line('Project Details');?></td>
                                <td width="200" class="dt">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="dt1 dt0"><?php echo $this->lang->line('Project');?> <?php echo $this->lang->line('ID');?>:</td>
                                <td class="dt1"><?php echo $project->id; ?></td>
                              </tr>
                              <tr>
                                <td class="dt2 dt0"><?php echo $this->lang->line('Project');?>:</td>
                                <td class="dt2"><?php echo $project->project_name; ?> </td>
					
                              </tr>
						<?php if($project->is_urgent ==1 or $project->is_feature == 1 or $project->is_private ==1) { ?>
							   <tr>
                                <td class="dt1 dt0"><?php echo $this->lang->line('Type');?>:</td>
                                <td class="dt1"><?php if($project->is_urgent == 1) { ?>
                                    &nbsp;<img src="<?php echo image_url('urgent.gif');?>" width="56" height="14" title="Urgent project" alt="<?php echo $this->lang->line('Urgent Project'); ?>" />
                                    <?php } 
								   if($project->is_feature == 1) { ?>
                                    &nbsp;&nbsp;<img src="<?php echo image_url('featured.gif');?>" width="71" height="13" title="Featured project" alt="<?php echo $this->lang->line('Featured Project'); ?>" />
                                    <? }
									if($project->is_private == 1) {?>
									
									 &nbsp;&nbsp;<img src="<?php echo image_url('private1.png');?>" width="61" height="13" title="private project" alt="<?php echo $this->lang->line('Private Project'); ?>" /><?php }
									 }
									 ?></td>
                              </tr>
                              <tr>
                              <td class="dt2 dt0"><?php echo $this->lang->line('Status');?>:</td>
								<?php $status=getCurrentStatus($project->project_status,$project->seller_id,$project->id)?>
							   <td class="dt2"><?php echo '<b style="color:green;">' .$status['status'].'</b>'; if(isset($status['message']))echo $status['message']; ?> </td>

                              </tr>
                              <tr>
                                <td class="dt1 dt0"><?php echo $this->lang->line('Budget');?>:</td>
                                <td class="dt1"><?php if($project->budget_min != '0') echo '$ '.$project->budget_min; else echo 'N/A'; ?> - <?php if($project->budget_max != '0') echo '$ '.$project->budget_max; else echo 'N/A'; ?></td>
                              </tr>
                              <tr>
                                <td class="dt2 dt0"><?php echo $this->lang->line('Created');?>:</td>
                                <td class="dt2"><?php echo get_datetime($project->created);?></td>
                              </tr>
                              <tr>
                                <td class="dt1 dt0"><?php echo $this->lang->line('Bidding Ends');?>:</td>
                                <td class="dt1"><?php echo get_datetime($project->enddate);?> (<?php echo '<b style="color:red;">'.days_left($project->enddate,$project->id).'</b>';?>)</td>
                              </tr>
                              <tr>
                                <td class="dt1 dt0"><?php echo $this->lang->line('Project Creator');?>:</td>



                                <td class="dt1"><a class="glow" href="<?php echo site_url('buyer/viewProfile/'.$project->userid);?>"><?php echo $project->user_name; ?></a><?php $condition1=array('subscriptionuser.username'=>$project->userid);
								$certified1= $this->certificate_model->getCertificateUser($condition1);
								if($certified1->num_rows()>0)
			                    {
							       foreach($certified1->result() as $certificate)
				                     {
									$user_id=$certificate->username;
									$id=$certificate->id;
									$condition=array('subscriptionuser.flag'=>1,'subscriptionuser.id'=>$id);
					                $userlists= $this->certificate_model->getCertificateUser($condition);
									// get the validity
									$validdate=$userlists->row();
									$end_date=$validdate->valid; 
									$created_date=$validdate->created;
									$valid_date=date('d/m/Y',$created_date);
								    $next=$created_date+($end_date * 24 * 60 * 60);
									$next_day= date('d/m/Y', $next) ."\n";
							        if(time()<=$next)
								    {?>
								<img src="<?php echo image_url('certified.gif');?>"  title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php  echo $this->lang->line('Certified Member')?>"/>
								<?php } 
								  }
								   }?>


                        <?php if($project->num_reviews == 0)


										echo '(No Feedback Yet) ';
										else{ ?>
									 <img height="7" border="0" width="81" src="<?php echo image_url('rating_'.$project->user_rating.'.gif');?>" alt="rating" /> (<b><?php echo $project->num_reviews;?> </b> <a href="<?php echo site_url('buyer/review/'.$project->creator_id);?>"><?php echo $this->lang->line('reviews');?></a> )
									<?php } ?></td>								  
		                        </tr>
                                <tr>
                                  <td class="dt2 dt0"><?php echo $this->lang->line('Description');?>:</td>
                                  <td class="dt2"><?php echo nl2br($project->description); ?></td>									  
															  
                                </tr>
								<tr>
                                  <td class="dt1 dt0"><?php echo $this->lang->line('Job Type');?>:</td>
                                  <td class="dt1"><?php echo getCategoryLinks($project->project_categories);?></td>								  
		                        </tr>
							<!--	Puhal Changes Start for downloading the Project attachment file (Sep 20 Isssue 17)-->
								<? if(isset($project->attachment_name)) { ?>
								<tr>
                                  <td class="dt1 dt0"><?php echo $this->lang->line('Project Attachment'); ?>:</td>
         <td class="dt1"><?php echo $project->attachment_name; ?><a href="<?php echo site_url('project/download/'.$project->attachment_url);?>" class="clsDown"><img src="<?php echo base_url();?>app/css/images/download1.png" /></a></td>								  								
		                        </tr><?php } ?>
								<!--	Puhal Changes End for downloading the Project attachment file (Sep 20 Isssue 17)-->
                              </tbody><?php }
						  else
						  {?>
						   <tbody>
                              <tr>
                                <td width="15%" class="dt"><?php echo $this->lang->line('JobListing  Details');?></td>
                                <td width="200" class="dt">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="dt1 dt0"><?php echo $this->lang->line('Job');?> <?php echo $this->lang->line('ID');?>:</td>
                                <td class="dt1"><?php echo $project->id; ?></td>
                              </tr>
                              <tr>
                                <td class="dt2 dt0"><?php echo $this->lang->line('Job Name');?>:</td>
                                <td class="dt2"><?php echo $project->project_name; ?></td>
                              </tr>
                              <tr>
                              <td class="dt1 dt0"><?php echo $this->lang->line('Status');?>:</td>
								<?php $status=getCurrentStatus($project->project_status,$project->seller_id,$project->id)?>
							   <td class="dt1"><?php echo '<b style="color:green;">' .$status['status'].'</b>'; if(isset($status['message']))echo $status['message']; ?> </td>

                              </tr>
							   </tr>
                              <tr>
                                <td class="dt2 dt0"><?php echo $this->lang->line('Budget');?>:</td>
                                <td class="dt2"><?php echo $project->salary; ?></td>
                              </tr>
                              <tr>
                                <td class="dt1 dt0"><?php echo $this->lang->line('Created');?>:</td>
                                <td class="dt1"><?php echo get_datetime($project->created);?></td>
                              </tr>
							  <tr>
                                <td class="dt2 dt0"><?php echo $this->lang->line('Closed');?>:</td>
                                 <td class="dt2"><?php echo get_datetime($project->enddate);?> (<?php echo '<b style="color:red;">'.days_left($project->enddate,$project->id).'</b>';?>)</td>
                              </tr>
                              <tr>
                                <td class="dt1 dt0"><?php echo $this->lang->line('Project Creator');?>:</td>
                                <td class="dt1"><a class="glow" href="<?php echo site_url('buyer/viewProfile/'.$project->userid);?>"><?php echo $project->user_name; ?></a><?php 
								
								$condition1=array('subscriptionuser.username'=>$project->userid);
								$certified1= $this->certificate_model->getCertificateUser($condition1);
								if($certified1->num_rows()>0)
			                    {
							       foreach($certified1->result() as $certificate)
				                     {
									$user_id=$certificate->username;
									$id=$certificate->id;
									$condition=array('subscriptionuser.flag'=>1,'subscriptionuser.id'=>$id);
					                $userlists= $this->certificate_model->getCertificateUser($condition);
									// get the validity
									$validdate=$userlists->row();
									$end_date=$validdate->valid; 
									$created_date=$validdate->created;
									$valid_date=date('d/m/Y',$created_date);
								    $next=$created_date+($end_date * 24 * 60 * 60);
									$next_day= date('d/m/Y', $next) ."\n";
							        if(time()<=$next)
								    {?>
								<img src="<?php echo image_url('certified.gif');?>"  title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php  echo $this->lang->line('Certified Member')?>"/>
								<?php } 
								  }
								   }?>


                                  <?php if($project->num_reviews == 0)

										echo '(No Feedback Yet) ';
										else{ ?>
									 <img height="7" border="0" width="81" src="<?php echo image_url('rating_'.$project->user_rating.'.gif');?>" alt="rating" /> (<b><?php echo $project->num_reviews;?> </b> <a href="<?php echo site_url('buyer/review/'.$project->creator_id);?>"><?php echo $this->lang->line('reviews');?></a> )
									<?php  } ?></td>								  
		                        </tr>
                                <tr>
                                  <td class="dt2 dt0"><?php echo $this->lang->line('Description');?>:</td>
                                  <td class="dt2"><?php echo nl2br($project->description); ?></td>									  
															  
                                </tr>
								 <tr>
                                  <td class="dt2 dt0"><?php echo $this->lang->line('Contact');?>:</td>
                                  <td class="dt2"><?php 
							if(isset($this->loggedInUser->id))
							  { 
							  if($project->contact=='') { 
							  echo "No Contact Found."; }
							  else{
							  echo nl2br($project->contact);
							  }
                                                    
							  } else { ?>
                                                      <a href="<?php echo site_url('users/getData/'.$project->id); ?>">Login</a><?php echo $this->lang->line('view');?>
													 
                                                      <?php 
							  }
						  ?> </td>									  
															  
                                </tr>
								</tr>
							<!--	Puhal Changes Start for downloading the Project attachment file (Sep 20 Isssue 17)-->
								<? if(isset($project->attachment_name)) { ?>
								<tr>
                                  <td class="dt1 dt0"><?php echo $this->lang->line('Project Attachment'); ?>:</td>
         <td class="dt1"><?php echo $project->attachment_name; ?><a href="<?php echo site_url('project/download/'.$project->attachment_url);?>" class="clsDown"><img src="<?php echo base_url();?>app/css/images/download1.png" /></a></td>								  								
		                        </tr>
							  
						  <?php }
						   } ?>
						  </table>
                          <p><span class="clsPostProject"><a class="buttonBlackShad" href="<?php echo site_url('messages/project/'.$project->id); ?>"><span><?php echo $this->lang->line('View Job Message Board');?></span></a></span> <span><?php echo $this->lang->line('Message Posted');?>: <b>
                            <?php if(isset($totalMessages)) echo $totalMessages; ?>
                            </b></span></p>
                          <br />
                          <br />
						  </div> 
                       
                        <!-- Load view my bids -->
                        <div class="clsInnerCommon">
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
                                              <h3><span class="clsPayments"><?php echo $this->lang->line('Project Bids');?></span></h3>
                                              <table cellspacing="1" cellpadding="2" width="96%">
                                                <tbody>
                                                  <tr>
                                                    <td width="10%" class="dt"><?php echo $this->lang->line('Sellers');?></td>
													<td width="20%" class="dt"><?php echo $this->lang->line('Message');?></td>
                                                    <td width="10%" class="dt"><?php echo $this->lang->line('Bids'); ?></td>
                                                    <td width="10%" class="dt"><?php echo $this->lang->line('Delivery Time');?></td>
                                                    <td width="15%" class="dt"><?php echo $this->lang->line('Time of Bid');?></td>
                                                    <td width="20%" class="dt"><?php echo $this->lang->line('Rating');?></td>
													<td width="15%" class="dt"><?php echo $this->lang->line('Options');?></td>
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
                                                  <tr class="<?php echo $class;?>">
												  <?php  
												   $condition1=array('subscriptionuser.username'=>$bid->user_id);
			
								$certified1= $this->certificate_model->getCertificateUser($condition1);
								if($certified1->num_rows()>0)
			                    {
							       foreach($certified1->result() as $certificate)
				                     {
									$user_id=$certificate->username;
									$id=$certificate->id;
									$condition=array('subscriptionuser.flag'=>1,'subscriptionuser.id'=>$id);
					                $userlists= $this->certificate_model->getCertificateUser($condition);
									// get the validity
									$validdate=$userlists->row();
									$end_date=$validdate->valid; 
									$created_date=$validdate->created;
									$valid_date=date('d/m/Y',$created_date);
								    $next=$created_date+($end_date * 24 * 60 * 60);
									$next_day= date('d/m/Y', $next) ."\n";
							        if(time()<=$next)
								    {?>
								<img src="<?php echo image_url('certified.gif');?>"  title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php  echo $this->lang->line('Certified Member')?>"/>
								<?php } 
								  }
								   }?>


                                                    <td ><a href="<?php echo site_url('seller/viewProfile/'.$bid->uid);?>"><?php echo $bid->user_name; 
					             //Get the Favourite and Blocked users
								 if(isset($favourite))
								     {
									   foreach($favourite->result() as $result)
									     { 
										    if($result->user_id == $bid->user_id)
											  {
											    if($result->user_role == '1')
													{ ?> <img src="<?php echo image_url('star.jpg'); ?>" title="Favourite User" alt="Favourite User" />
                                                      <?php 
													} 
												if($result->user_role == '2')
													{ ?>
                                                      <img src="<?php echo image_url('cross.jpg'); ?>" title="Blocked User" alt="Blocked User" />
                                                      <?php 
													}
														
											 }
										  }
										}  	?>

                                                    
													


                                                      </a> <?php  $condition=array('subscriptionuser.username'=>$bid->user_id);

								
								$certified1= $this->certificate_model->getCertificateUser($condition1);
								if($certified1->num_rows()>0)
			                    {
							       foreach($certified1->result() as $certificate)
				                     {
									$user_id=$certificate->username;
									$id=$certificate->id;
									$condition=array('subscriptionuser.flag'=>1,'subscriptionuser.id'=>$id);
					                $userlists= $this->certificate_model->getCertificateUser($condition);
									// get the validity
									$validdate=$userlists->row();
									$end_date=$validdate->valid; 
									$created_date=$validdate->created;
									$valid_date=date('d/m/Y',$created_date);
								    $next=$created_date+($end_date * 24 * 60 * 60);
									$next_day= date('d/m/Y', $next) ."\n";
							        if(time()<=$next)
								    {?>
								<img src="<?php echo image_url('certified.gif');?>"  title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php  echo $this->lang->line('Certified Member')?>"/>
								<?php } 
								  }
								   }?>

													 


										<?php if(isset($user_details->certifyend))if($user_details->certifyend >= get_est_time()){ ?> <img src="<?php echo image_url('certified.png');  ?>" alt="Special User" border="0" width="10"	height="13"> <?php  }?>			  

											  </td>
													  <td> <?php if (isset($bid->bid_desc))echo $bid->bid_desc; ?></td>
                                                    <td>$<?php echo $bid->bid_amount;?></td>
                                                    <td><?php if($bid->bid_hours == 0 && $bid->bid_days == 0) 
											echo $this->lang->line('Immediately'); elseif($bid->bid_days != 0) echo $bid->bid_days.$this->lang->line('days');?>
                                                      &nbsp;
                                                      <?php if($bid->bid_hours != 0) echo $bid->bid_hours." ".$this->lang->line('hours');?>
                                                    </td>
                                                    <td><?php echo get_datetime($bid->bid_time);?></td>
                                                    <td><?php if($bid->num_reviews == 0)
							echo '(No Feedback Yet) ';
							else{ ?>
                                                      <a href="<?php echo site_url('seller/review/'.$bid->uid);?>"> <img height="7" border="0" width="81" alt="rating" src="<?php echo image_url('rating_'.$bid->user_rating.'.gif');?>"/> (<b><?php echo $bid->num_reviews;?> </b> <?php echo $this->lang->line('reviews');?>)</a>
                                                      <?php } ?>
                                                      <?php 
							if(isset($this->loggedInUser->id))
							  { ?>
                                                      <a href="<?php echo site_url('project/postBidReport/'.$bid->id); ?>"><img src="<?php echo image_url('icons.png'); ?>" height="28" width="21" alt="Report Project Violation" title="Report Project Violation"/> </a>
                                                      <?php
							  } else { ?>
                                                      <a href="<?php echo site_url('users/login'); ?>"><img src="<?php echo image_url('icons.png'); ?>" height="28" width="21" alt="Report Project Violation"/> </a>
                                                      <?php 
							  }
						  ?> 
						  </td>
			<!--Puhal Changes -->				
			<td>							
			<?php if(isset($loggedInUser->role_id))
    	 	 {
				if($loggedInUser->role_id =='1' && ($project->project_status == 0 || $project->project_status == 1) && $loggedInUser->id==$project->creator_id && $project->seller_id==0)
	  		 { 
		 			 
		 			echo '<a class="glow" href="'.site_url('project/selProvider/'.$bid->id).'">'.$this->lang->line('Pick Provider').'</a>';
			
		  }elseif($loggedInUser->role_id =='1' &&   $loggedInUser->id==$project->creator_id && $project->seller_id!=0)
			 {
						echo 'Already Picked';
			  }
		 } 			
				
		 ?> </td>
													
                                                  </tr>
                                                  <?php } }
					  else{
					  	if($projectRow->is_hide_bids == 1)
							echo ' <tr class="dt2 dt0"><td colspan=8><a href="'.site_url('buyer/viewProfile/'.$creatorInfo->id).'">'.$creatorInfo->user_name."</a> ".$this->lang->line('hidden_bids').'.</td></tr>';
						else
						{
						
					              	if(!isSeller())
	                 	             {
        	                         echo '<tr class="dt2 dt0"><td colspan=8>'.$this->lang->line('no_bids1').'.</td></tr>';
		                             }
									 else
									 {
							echo '<tr class="dt2 dt0"><td colspan=8>'.$this->lang->line('no_bids').'.</td></tr>';
							}
						}
					  }
					  ?>
                                                </tbody>
                                              </table>
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
						if(!isSeller())
		{
        	$this->session->set_flashdata('flash_message', $this->common_model->flash_message('error',$this->lang->line('You must be logged in as a seller to place a bid')));
			//redirect('info');
		}
				else
			{	
						if($projectRow->project_status == 0)
						{ 
							if(count($tot) > 0)
								$toDisp = $this->lang->line('Edit Bid');
							else
								$toDisp = $this->lang->line('Place Bid');
							?>
											<?php 
							//Check for the project open date is end or not
							if($projectRow->flag== 0)
							{
							
								if(days_left($projectRow->enddate,$projectId) != 'Closed')
								 {  ?>
												<p><a href="<?php echo site_url('project/postBid/'.$projectId); ?>" class="buttonBlackShad"><span><?php echo $toDisp;?></span></a></p>
												<br />
												<?php 
								 }
							} 
							else
							{
							               $created_date=$projectRow->created;
								           $end_date=$projectRow->enddate;
					                       $next=$created_date+($end_date * 24 * 60 * 60);
											if(days_left($next,$projectId) != 'Closed')
								 {  ?>
												<p><a href="<?php echo site_url('project/postBid/'.$projectId); ?>" class="buttonBlackShad"><span><?php echo $toDisp;?></span></a></p>
												<br />
												<?php 
								 }
											
							}
						}
						}?>               
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
<?php $this->load->view('footer'); ?>
