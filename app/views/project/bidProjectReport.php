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
                             <h2><?php echo $this->lang->line('Report Violation') ?></h2>
								
								<p><?php echo $this->lang->line('Report Violation content'); ?></p>
								<?php
								$userInfo = $loggedInUser;
								$postSimilar =  $postSimilar->row();
								?>
								<?php
								//Show Flash error Message  for deposit minimum amount
								if($msg = $this->session->flashdata('flash_message'))
								{
								  echo $msg;
								}?>
								<?php
								if(isset($getUsers))
								  $getUsers = $getUsers->row();
								if(isset($getBids))
								  $getBids  = $getBids->row();
								
								?>
								<h3><span class="clsInvoice"><?php echo $this->lang->line('Reporting'); ?> <a href="<?php echo site_url('project/view/'.$postSimilar->id); ?>"><?php echo $postSimilar->project_name; ?></a></span></h3> 
								<form action="<?php echo site_url('project/postBidReport/'.$getBids->id); ?>" name="projectReport" method="post">
									
									<p><span><b><?php echo $this->lang->line('username'); ?></b></span>
									   <span><a href="<?php echo site_url('programmer/viewProfile/'.$getUsers->id); ?>"><?php echo $getUsers->user_name; ?></a></span>
									</p>
									<p><span><label><b><?php echo $this->lang->line('Project');?></b></label></span>
									   <span><label><a href="<?php echo site_url('project/view/'.$postSimilar->id); ?>"><?php echo $postSimilar->project_name; ?></a></label></span>
									</p>
									<p><span><label><b><?php echo $this->lang->line('Comment'); ?></b></label></span></p>
									<p><span><textarea name="report" rows="10" cols="70" ></textarea></span></p>
									
									<p><span>&nbsp;</span><?php echo $this->lang->line('Report Violation hint'); ?></p>
									<input type="hidden" name="projectid" value="<?php echo $postSimilar->id;?>" />	
									<input type="hidden" name="projectname" value="<?php echo $postSimilar->project_name;?>" />
									<p><input type="submit" class="clsMid" name="submitReport" value="<?php echo $this->lang->line('Report Violation');?>" /></p>
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