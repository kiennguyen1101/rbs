<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main">

	<div class="clsContact">
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
							
							 <div class="clsContactForm">
							  <?php
							//Show Flash Message
							if($msg = $this->session->flashdata('flash_message'))
							{
								echo $msg;
							}
							?>
							  <!--SIGN-UP-->
						
								<form method="post" action="<?php echo site_url('buyer/signUp'); ?>">
								  <input type="hidden" name="new" value="user"/>
								  <h2><?php echo $this->lang->line('Invite Programmer'); ?></h2>
								  <br />
								  <p><label><?php echo $this->lang->line('From'); ?>:</label><?php echo $loggedInUser->user_name; ?></p>
								  <p><label><?php echo $this->lang->line('To'); ?>:</label>
									 <select name="programmers[]" multiple="multiple"> <?php 
										  foreach($favouriteList->result() as $user)
										  { ?>
											 <option value="<?php echo $user->user_id; ?>"><?php echo $user->user_name; ?></option> <?php 
										  } ?>
										  </select>
									
								  </p>
								  <p><label><?php echo $this->lang->line('To'); ?>:</label><textarea name="content" rows="7" cols="30"></textarea></p>
								  <p><label><?php echo $this->lang->line('Other User'); ?>:</label>
									 <input type="text" name="email" size="40" value="<?php //echo set_value('email'); ?>"/>
									 <?php echo $this->lang->line('If your favourite user is not in the dropdown list please enter into the Textbox'); ?>
									<input type="submit" class="clsMini" value="<?php echo $this->lang->line('Submit');?>" name="buyerSignup"/>
									 <?php //echo form_error('email'); ?>
									<small><?php echo $this->lang->line('provide_valid_mail'); ?>. <a href="#"><?php echo $this->lang->line('view_privacy_policy'); ?></a>.</small></p>
								</form>
							
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