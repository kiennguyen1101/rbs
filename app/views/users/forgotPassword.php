<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main">
  <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	   ?>
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
                        <div class="clsInnerCommon">
                          <h2><?php echo $this->lang->line('Forgot Login Details?');?></h2>
                          <!--SIGN-UP-->
                          <div id="selSignUp">
                            <h3><span class="clsDepositTrans"><?php echo $this->lang->line('Forgot your username?');?></span></h3>
                            <form method="post" action="<?php echo site_url('users/forgotPassword'); ?>">
                              <p><?php echo $this->lang->line('Enter your e-mail address:');?>
                                <input type="text" name="email" value="<?php echo set_value('email'); ?>" size="20"/>
                                <input type="submit" value="<?php echo $this->lang->line('Find Username');?>" name="forgotUsername" class="clsMid"/>
                                <?php echo form_error('email'); ?> </p>
                            </form>
                          </div>
                          <br />
                          <div id="selSignUp2">
                            <h3><span class="clsTransfer"><?php echo $this->lang->line('Forgot your password?');?></span></h3>
                            <br />
                            <form method="post" action="<?php echo site_url('users/forgotPassword'); ?>">
                              <p><?php echo $this->lang->line('Enter your username:');?>
                                <input type="text" name="username" value="<?php echo set_value('username'); ?>" size="15"/>
                                <input type="submit" value="<?php echo $this->lang->line('E-mail Me My Password');?>" name="forgotPassword" class="clsLargeBL"/>
                                <?php echo form_error('username'); ?> </p>
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