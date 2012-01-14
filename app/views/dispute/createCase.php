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
						<h2><?php echo $this->lang->line('Open new case');?></h2>
                          <div class="clsSitelinks clsEditProfile">
                            <form method="post" action="" name="myForm">
							<?phpform_token();?>
							  <p><span><?php echo $this->lang->line('project_title'); ?>:</span><a href="<?php echo site_url('dispute/view/'.$project->id);?>" class="glow"><?php echo $project->project_name;?></a></p>
							  <p><span><?php echo $this->lang->line('project_id'); ?>:</span><?php echo $project->id;?></p>
							  <p><span><?php echo $this->lang->line('Buyer'); ?>:</span><a href="<?php echo site_url('buyer/viewProfile/'.$project->userid);?>" class="glow"><?php echo $project->user_name;?></a></p>
							  <p><span><?php echo $this->lang->line('Provider'); ?>:</span><a href="<?php echo site_url('seller/viewProfile/'.$provider->id);?>" class="glow"><?php echo $provider->user_name;?></a></p>
							  <p><span><?php echo $this->lang->line('Case Type');?>:</span>
							  <select name="case_type">
						      <option value="Cancel"><?php echo $this->lang->line('project_cancel');?></option>
							  <option value="Dispute"><?php echo $this->lang->line('project_dispute');?></option>
						       </select></p>
                            <p><span><?php echo $this->lang->line('Case Reason');?>:</span>
							  <select name="case_reason">
						      <option value="<?php echo $this->lang->line('dispute over quality of service');?>"><?php echo $this->lang->line('dispute over quality of service');?></option>
							  <option value="<?php echo $this->lang->line('service not rendered');?>"><?php echo $this->lang->line('service not rendered');?></option>
							  <option value="<?php echo $this->lang->line('project description changed');?>"><?php echo $this->lang->line('project description changed');?></option>
							  <option value="<?php echo $this->lang->line('payment not recieved');?>"><?php echo $this->lang->line('payment not recieved');?></option>
							  <option value="<?php echo $this->lang->line('no communication');?>"><?php echo $this->lang->line('no communication');?></option>
							  <option value="<?php echo $this->lang->line('mutual cancellation');?>"><?php echo $this->lang->line('mutual cancellation');?></option>
							  <option value="<?php echo $this->lang->line('other');?>"><?php echo $this->lang->line('other');?></option>
						       </select></p>
							   
							   <p><span><?php echo $this->lang->line('problem_description')."<br>(".$this->lang->line('public').")"; ?>:</span>
                                <textarea rows="10" name="problem_description" cols="60" onKeyDown="textCounter(document.myForm.problem_description,document.myForm.remLen2,250)" onKeyUp="textCounter(document.myForm.problem_description,document.myForm.remLen2,250)"><?php echo set_value('problem_description'); ?></textarea>
                                <?php echo form_error('problem_description'); ?> </p>
                              <p><span>&nbsp;</span>
                                <input readonly type="text" name="remLen2" size="3" maxlength="3" value="250">
                                &nbsp;<?php echo $this->lang->line('Characters Left') ?></p>
								
								<p><span><?php echo $this->lang->line('comments')."<br>(".$this->lang->line('private').")"; ?>:</span>
                                <textarea rows="10" name="comments" cols="60" onKeyDown="textCounter(document.myForm.comments,document.myForm.remLen3,250)" onKeyUp="textCounter(document.myForm.comments,document.myForm.remLen3,250)"><?php echo set_value('comments'); ?></textarea>
                                <?php echo form_error('comments'); ?> </p>
                              <p><span>&nbsp;</span>
                                <input readonly type="text" name="remLen3" size="3" maxlength="3" value="250">
                                &nbsp;<?php echo $this->lang->line('Characters Left') ?></p>
								
								<p><span><?php echo $this->lang->line('Review');?>:</span>
							  <select name="review">
						      <option value="<?php echo $this->lang->line('remove review');?>"><?php echo $this->lang->line('remove review');?></option>
							  <option value="<?php echo $this->lang->line('add review');?>"><?php echo $this->lang->line('add review');?></option>
							  <option value="<?php echo $this->lang->line('dont change');?>"><?php echo $this->lang->line('dont change');?></option>
						       </select></p>
							   
							   <p><span>&nbsp;</span><?php echo $this->lang->line('Payment need');?>:$<input name="payment" type="text" value="<?php echo set_value('payment'); ?>" size="10"><?php echo form_error('payment'); ?>
							   </p>
							   <p><span>&nbsp;</span>
							   <input type="hidden" name="project_id" value="<?php echo $project->id;?>" />
                                <input type="submit" class="clsSmall" value="<?php echo $this->lang->line('Submit'); ?>" name="createCase" />
                              </p>
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
  </div>
</div>
</div>
</div>
</div>
<!--END OF POST PROJECT-->
</div>
<?php $this->load->view('footer'); ?>