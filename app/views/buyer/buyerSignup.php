<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main">
  <!--POST PROJECT-->
  <div class="clsViewMyProject">
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
                        <div class="clsInnerCommon clsSitelinks">
                          <h2><?php echo $this->lang->line('new_buyer_signup'); ?></h2>
                          <?php
							//Show Flash Message
							if($msg = $this->session->flashdata('flash_message'))
							{
								echo $msg;
							}	?>
                          <!--SIGN-UP-->
                          <div id="selSignUp">
                            <h3><span class="clsNewBuyer"><?php echo $this->lang->line('new_buyer_signup'); ?></span></h3>
                            <form method="post" action="<?php echo site_url('buyer/signUp'); ?>">
                              <?php form_token(); ?>
                              <input type="hidden" name="new" value="user"/>
                              <p><?php echo $this->lang->line('not_a_buyer'); ?>? <a href="<?php echo site_url('seller/signUp'); ?>"><?php echo $this->lang->line('click_here'); ?></a> <?php echo $this->lang->line('to_sign_seller'); ?></p>
                              <p><strong><?php echo $this->lang->line('email_address'); ?>:</strong>
                                <input type="text" name="email" size="40" value="<?php echo set_value('email'); ?>"/>
                                <input type="submit" class="clsSmall" value="<?php echo $this->lang->line('Submit');?>" name="buyerSignup"/>
                                <?php echo form_error('email'); ?><br />
                                <small><?php echo $this->lang->line('provide_valid_mail'); ?>. <a href="<?php echo site_url('page/privacy');?>"><?php echo $this->lang->line('view_privacy_policy'); ?></a>.</small></p>
                            </form>
                          </div>
                          <br />
                          <div id="selSignUp2">
                            <h3><span class="clsResend"><?php echo $this->lang->line('Resend activation link');?></span></h3>
                            <br />
                            <form method="post" action="<?php echo site_url('buyer/resendActLink'); ?>">
                              <p><strong><?php echo $this->lang->line('email_address'); ?>:</strong>
                                <input type="text" name="email2" size="40" value="<?php echo set_value('email2'); ?>"/>
                                <input type="submit" class="clsSmall" value="<?php echo $this->lang->line('Submit');?>" name="resend"/>
                                <?php echo form_error('email2'); ?><br />
                              </p>
                            </form>
                          </div>
                          <!--SIGN-UP-->
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