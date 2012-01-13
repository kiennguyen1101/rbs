<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
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
                              <h2><?php echo $this->lang->line('Reviews of Buyer');?> - <?php echo $userDetails->user_name;?></h2>
                             <table cellspacing="1" cellpadding="2" width="96%">
                                <tbody><tr>
                                  <td width="10%" class="dt"><?php echo $this->lang->line('Rating');?></td>
                                  <td width="20%" class="dt"><?php echo $this->lang->line('Project Name');?> </td>
                                  <td width="10%" class="dt"><?php echo $this->lang->line('Review Date');?></td>
								  <td width="10%" class="dt"><?php echo $this->lang->line('Project status');?></td>
								  <td width="30%" class="dt"><?php echo $this->lang->line('Provider');?></td>	
								  <td width="30%" class="dt"><?php echo $this->lang->line('comments');?></td>							  
                                </tr>
								<?php
						  	if(isset($reviewDetails) and $reviewDetails->num_rows()>0)
							{
							$i=0;
							foreach($reviewDetails->result() as $reviewDetail)
							{
								if($i%2==0)
									$class = 'dt1';
								else 
									$class = 'dt2';
							?>
                                <tr>
                                  <td class="<?php echo $class; ?> dt0"><?php echo $reviewDetail->rating;?><img src="<?php echo image_url('rating_'.$reviewDetail->rating.'.gif');?>" /></td>
                                  <td class="<?php echo $class; ?>"><a href="<?php echo site_url('project/view/'.$reviewDetail->projectid);?>"><?php echo $reviewDetail->project_name; ?></a></td>
                                  <td class="<?php echo $class; ?>"><?php echo get_date($reviewDetail->review_time); ?></td>
                                  <td class="<?php echo $class; ?>"><?php echo getProjectStatus($reviewDetail->project_status);?></td>
                                  <td class="<?php echo $class; ?>">
								  <?php 
							$buyer = getUserInfo($reviewDetail->provider_id);
							?>
								 
							<?php 
							$chj = getAvgReview($reviewDetail->provider_id,'provider_id');
							echo $buyer->user_name;
							?>
							<img src="<?php echo image_url('rating_'.$chj.'.gif');?>" /> ( <a href="<?php echo site_url('programmer/review/'.$buyer->id);?>">
							<?php echo getNumReviews($reviewDetail->provider_id,'provider_id')." reviews";?></a> )
								  </td>			
								  <td class="<?php echo $class; ?>"><?php echo $reviewDetail->comments; ?></td>					  
                                </tr>
                                <?php $i++;} }else{
							
							echo $this->lang->line('No records found');
							} ?>
                              </tbody></table>
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