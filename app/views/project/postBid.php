<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<?php
		//Get Project Info
     	$bid = $bid->row();
		$project = $projects->row();
		if(is_object($bid))
		   $action = site_url('project/editBid');
		else
		   $action = site_url('project/createBid');
?>
<!--MAIN-->
 <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
  ?>
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
                              <h2><?php echo $this->lang->line('Bid on Project'); ?>:<?php echo $project->project_name; ?></h2>
							  <div class="clsForm">
							  <form method="post" action="<?php echo $action; ?>" name="myForm">
								<!--PROJECT MESSAGE BOARD-->
								
								
								
								<div id="selPMB" class="clsMarginTop">
								  
								  <p class="clsSitelinks"><?php echo $this->lang->line('You are currently logged in as');?> <a class="glow" href="<?php echo site_url('programmer/viewProfile/'.$this->loggedInUser->id);?>">
									<?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?>
									</a> (<a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout') ?></a>). </p>
								  <p><label><?php echo $this->lang->line('Your Bid'); ?>:</label>$ <input name="bidAmt" type="text" value="<?php if(isset($bid->bid_amount)) echo $bid->bid_amount; else echo set_value('bidAmt');?>" size="8" />
								  <?php echo form_error('bidAmt'); ?>
								  </p>
								  
								  <p><input type="checkbox" name="notify" value="1"/> <?php echo $this->lang->line('Notify me by e-mail if someone bids lower than me on this project.');?></p>
								  
						  <p><input type="checkbox" name="escrow"  value="1" <?php if(isset($bid->escrow_flag)){if($bid->escrow_flag=='1') echo 'checked="checked"';}?>/> <?php echo $this->lang->line('Required Escrow');?></p>
								  <p><label><?php echo $this->lang->line('Delivery Time'); ?>:</label>
								  <input type="text" value="<?php if(isset($bid->bid_days)) echo $bid->bid_days; else echo set_value('days');?>"  name="days"/> <?php echo $this->lang->line('Days'); ?><br /><?php echo form_error('days'); ?></p>
								  <p><label>&nbsp;</label><input type="text" value="<?php if(isset($bid->bid_hours)) echo $bid->bid_hours; else echo set_value('hours');?>"  name="hours"/> <?php echo $this->lang->line('Hours'); ?><?php echo form_error('hours'); ?>
								  </p>
								  
								  <p><label><?php echo $this->lang->line('Message:');?></label>
								 <textarea name="message2" wrap="physical" rows=10 cols=60 onKeyDown="textCounter(document.myForm.message2,document.myForm.remLen2,250)" onKeyUp="textCounter(document.myForm.message2,document.myForm.remLen2,250)"><?php if(isset($bid->bid_desc)) echo $bid->bid_desc; else echo set_value('message2')?></textarea>  <?php echo form_error('message2'); ?>
								  </p><p><label>&nbsp;</label><input readonly type="text" name="remLen2" size="3" maxlength="3" value="250">
							    <?php echo $this->lang->line('Characters Left') ?>
									</p>
									<p><label>&nbsp;</label>
									<?php echo $this->lang->line('Tip');?> </p>
								
								    <p><label>&nbsp;</label>
									<input class="clsSmall" type="submit" value="<?php echo $this->lang->line('Submit'); ?>" name="postBid"/>
									<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
									<input type="hidden" name="bidId" value="<?php if(isset($bid->id)) echo $bid->id; ?>" />
									</p>
								</div>
								<!--END OF PROJECT MESSAGE BOARD-->
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
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>