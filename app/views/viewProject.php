<?php $this->load->view('header'); ?>

<?php $this->load->view('sidebar'); ?>

<!--MAIN-->

<?php

		//Get Project Info

     	$project = $projects->row();

?>



<div id="main">

  <?php

	//Show Flash error Message  for deposit minimum amount

	if($msg = $this->session->flashdata('flash_message'))

	{

	  echo $msg;

	}?>

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

                          <h2><?php echo $this->lang->line('Project');?>: <?php echo $project->project_name; ?></h2>

                          <div class="clsHeads clearfix">

                            <div class="clsHeadingLeft clsFloatLeft">

                              <h3><span class="clsFeatured"><?php echo 'View Project'; ?></span></h3>

                            </div>

                            <div class="clsHeadingRight clsFloatRight">

                              <?php if(isset($loggedInUser->role_id))

							   {

								if($loggedInUser->role_id =='1') 

								   {	 ?>

                              <p> <span class="clsPostProject"> <a class="buttonBlack" href="<?php if (!isset($loggedInUser->role_id)) echo site_url('users/login'); else  echo site_url('project/postProject/'.$project->id); ?>"><span><?php echo $this->lang->line('Post Similar Project'); ?></span></a><span class="clsBookMark"><a class="buttonBlack" href="<?php if (!isset($loggedInUser->role_id)) echo site_url('users/login'); else echo site_url('bookMark/'.$project->id); ?>"><span><?php echo $this->lang->line('Book Mark'); ?></span></a></span> <span class="clsManage">

                                <?php

							//Make only this featured properties only for programmer to make featurd

							

							if(isset($loggedInUser))

							  {

								if (isset($loggedInUser->role_id) =='1') 

								   { ?>

                                <a class="buttonBlack" href="<?php echo site_url('project/manageProject/'.$project->id); ?>"><span><?php echo $this->lang->line('Manage');?></span></a>

                                <?php } 

							  }

							if (!isset($loggedInUser->role_id))

							   {	 ?>

                                <a href="<?php echo site_url('users/login'); ?>"><?php echo $this->lang->line('Manage');?></a>

                                <?php } ?>

                                </span>

                                <?php

								   } 

							   } ?>

                                <?php

							if(isset($loggedInUser->role_id))

							   {

								if($loggedInUser->role_id =='2') 

								   {	 ?>

                                <a href="<?php  echo site_url('userList/addFavouriteUsers/'.$project->userid); ?>"><img src="<?php echo image_url('star_g.gif'); ?>" width="21" height="28" title="Add To Favourite"  alt="Add To Favourite"/> </a> <a href="<?php echo site_url('userList/addBlockedUsers/'.$project->userid); ?>"><img src="<?php echo image_url('block_g.gif'); ?>" width="21" height="28" alt="BlackList User" title="BlackList User"/> </a> <a href="<?php echo site_url('project/postReport/'.$project->id); ?>"><img src="<?php echo image_url('com_g.gif'); ?>" height="28" width="21" alt="Report Project Violation" title="Report Project Violation"/> </a>

                                <?php 

								   } 

							   }

							   else

							   { ?>

                                <a href="<?php  echo site_url('users/login'); ?>"><img src="<?php echo image_url('star_g.gif'); ?>" width="22" height="21"  alt="Add To Favourite"/> </a> <a href="<?php echo site_url('users/login'); ?>"><img src="<?php echo image_url('block_g.gif'); ?>" width="22" height="22" alt="BlackList User"/> </a> <a href="<?php echo site_url('users/login'); ?>"><img src="<?php echo image_url('com_g.gif'); ?>" height="21" width="28" alt="Report Project Violation"/> </a>

                                <?php } 

							   ?>

                            </div>

                          </div>

                          <table cellspacing="1" cellpadding="2" width="96%">

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

                                <td class="dt2"><?php echo $project->project_name; ?></td>

                              </tr>

                              <tr>

                                <td class="dt1 dt0"><?php echo $this->lang->line('Status');?>:</td>

                                <td class="dt1"><?php echo '<b style="color:green;">' .getProjectStatus($project->project_status).'</b>'; ?> </td>

                              </tr>

                              <tr>

                                <td class="dt2 dt0"><?php echo $this->lang->line('Budget');?>:</td>

                                <td class="dt2"><?php if($project->budget_max != '0') echo '$ '.$project->budget_max; else echo 'N/A'; ?></td>

                              </tr>

                              <tr>

                                <td class="dt1 dt0"><?php echo $this->lang->line('Created');?>:</td>

                                <td class="dt1"><?php echo get_datetime($project->created);?></td>

                              </tr>

                              <tr>

                                <td class="dt2 dt0"><?php echo $this->lang->line('Bidding Ends');?>:</td>

                                <td class="dt2"><?php echo get_datetime($project->enddate);?> (<?php echo '<b style="color:red;">'.days_left($project->enddate,$project->id).'</b>';?>)</td>

                              </tr>

                              <tr>

                                <td class="dt1 dt0"><?php echo $this->lang->line('Project Creator');?>:</td>

                                <td class="dt1"><a href="<?php echo site_url('buyer/viewProfile/'.$project->userid);?>"><?php echo $project->user_name; ?></a>

                                  <?php if($project->num_reviews == 0)

										echo '(No Feedback Yet) ';

										else{ ?>

                                  <a href="<?php echo site_url('buyer/review/'.$project->creator_id);?>"> <img height="7" border="0" width="81" src="<?php echo image_url('rating_'.$project->user_rating.'.gif');?>"/> (<b><?php echo $project->num_reviews;?> </b> <?php echo $this->lang->line('reviews');?>)</a>

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

                            </tbody>

                          </table>

                          <p><span class="clsPostProject"><a class="buttonBlackShad" href="<?php if (!isset($loggedInUser->role_id)) echo site_url('users/login');

							     else echo site_url('messages/project/'.$project->id); ?>"><span><?php echo $this->lang->line('View Project Message Board');?></span></a></span> <span><?php echo $this->lang->line('Message Posted');?>: <b>

                            <?php if(isset($totalMessages)) echo $totalMessages; ?>

                            </b></span></p>

                          <br />

                          <br />

                        </div>

                        <!-- Load view my bids -->

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

                                                <tbody>

                                                  <tr>

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

                                                  <tr class="<?php echo $class;?>">

                                                    <td ><a href="<?php echo site_url('programmer/viewProfile/'.$bid->uid);?>"><?php echo $bid->user_name; 

					             //Get the Favourite and Blocked users

								 if(isset($favourite))

								     {

									   foreach($favourite->result() as $result)

									     { 

										    if($result->user_id == $bid->user_id)

											  {

											    if($result->user_role == '1')

													{ ?> <img src="<?php echo image_url('star.jpg'); ?>" title="Favourite User" />

                                                      <?php 

													} 

												if($result->user_role == '2')

													{ ?>

                                                      <img src="<?php echo image_url('cross.jpg'); ?>" title="Blocked User"  />

                                                      <?php 

													}	

											 }

										  }

										}  	?>

                                                      </a></td>

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

                                                      <a href="<?php echo site_url('programmer/review/'.$bid->uid);?>"> <img height="7" border="0" width="81" alt="10.00/10" src="<?php echo image_url('rating_'.$bid->user_rating.'.gif');?>"/> (<b><?php echo $bid->num_reviews;?> </b> <?php echo $this->lang->line('reviews');?>)</a>

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

                                                  </tr>

                                                  <?php } }

					  else{

					  	if($projectRow->is_hide_bids == 1)

							echo ' <tr class="dt2 dt0"><td colspan=5><a href="'.site_url('buyer/viewProfile/'.$creatorInfo->id).'">'.$creatorInfo->user_name."</a> ".$this->lang->line('hidden_bids').'.</td></tr>';

						else

					  		echo '<tr class="dt2 dt0"><td colspan=5>'.$this->lang->line('no_bids').'.</td></tr>';

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

                        <p><a href="<?php echo site_url('project/postBid/'.$projectId); ?>" class="buttonBlackShad"><span><?php echo $toDisp;?></span></a></p>

                        <br />

                        <?php 

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

</div>

<?php $this->load->view('footer'); ?>

