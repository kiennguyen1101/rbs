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
								<h3><span class="clsOptContact"><?php echo $this->lang->line('Reporting'); ?> <a href="<?php echo site_url('project/view/'.$postSimilar->id); ?>"><?php echo $postSimilar->project_name; ?></a></span></h3> 
								<form action="<?php echo site_url('project/postReport/'.$postSimilar->id); ?>" name="projectReport" method="post">
									<p> <label><?php echo $this->lang->line('Project');?></label>
									<label><a href="<?php echo site_url('project/view/'.$postSimilar->id); ?>"><b><?php echo $postSimilar->project_name; ?></b></a></label></p>
									<p><b><?php echo $this->lang->line('Comment'); ?> </b><input type="text" name="report" size="60"   /></p>
									<p><?php echo $this->lang->line('Report Violation hint'); ?></p>
									<input type="hidden" name="projectname" value="<?php echo $postSimilar->project_name;?>" />
									<p><input type="submit" class="clsMid" name="submitReport" value="<?php echo $this->lang->line('Report Violation');?>" /></p>
								</form>
								<br />
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