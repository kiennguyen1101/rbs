<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--START MAIN-->
<div id="main">
  <!--POST PROJECT-->
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
                        <div class="clsInnerCommon clsSitelinks">
                          <h2><?php echo $this->lang->line('contacting'); ?> <?php echo $this->config->item('site_title'); ?></h2>
                          <p><?php echo $this->lang->line('If you have a question,');?> <a href="<?php echo site_url('faq');?>"><?php echo $this->lang->line('check the FAQ');?></a> <?php echo $this->lang->line('con');?></p>
                          <h3><span class="clsFileManager"><?php echo $this->lang->line('Projects Feed');?></span></h3>
                          <ul>
                            <?php
							if(isset($frequentFaqs) and $frequentFaqs->num_rows()>0)
							{
								foreach($frequentFaqs->result() as $frequentFaq)
								{	?>
									<li><a href="<?php echo site_url('faq/view/'.$frequentFaq->id); ?>"><?php echo $frequentFaq->question; ?></a></li>
									<?php
								}//Foreach End
							}//If End
								?>
                          </ul>
                         
                          <p><b><?php echo $this->lang->line('Submit Message to');?> <?php echo $this->config->item('site_title'); ?> <?php echo $this->lang->line('support Stafff...');?></b></p>
                          <ul>
                            <li>   	<!-- Puhal Chnages Start for the Support Desk (Sep 18 Issue 7)-->
						   <?php if(isset($loggedInUser->id)) { ?>
							<b><a href="<?php echo site_url('support');?>"> <?php echo $this->lang->line('Members: Login to Support Desk...');?></a></b>
							<?php }else{ ?>
							<b><a href="<?php echo site_url('users/login/support');?>"> <?php echo $this->lang->line('Members: Login to Support Desk...');?></a></b>
							<?php } ?>
						   <!-- Puhal Chnages Start for the Support Desk (Sep 18 Issue 7)-->					 </li>
                          </ul>
						  
                          <div class="clsContactForm clSTextDec">
                            <h3><span class="clsCategory"><?php echo $this->lang->line('Guest Sales Questions');?></span></h3>
                              <form method="post" action="<?php echo site_url('contact')?>">
       							<p>
								  <label><?php echo $this->lang->line('your_email'); ?><span class="red">*</span></label>
								  <input class="clsText" type="text" name="c_email" value="<?php echo set_value('c_email'); ?>" />
								  <?php echo form_error('c_email'); ?>
								</p>
								<p>
								  <label><?php echo $this->lang->line('subject'); ?><span class="red">*</span> </label>
								  <input class="clsText" type="text" name="c_subject" value="<?php echo set_value('c_subject'); ?>" />
								  <?php echo form_error('c_subject'); ?>
								</p>
								<p>
								  <label class="clsComments"><?php echo $this->lang->line('comments'); ?><span class="red">*</span></label>
								  <textarea name="c_comments" rows="10" cols="40"><?php echo set_value('c_comments'); ?></textarea>
								  <?php echo form_error('c_comments'); ?>
								</p>
								<p class="clsSubmitBlock">
								  <label>&nbsp;</label>
								  <input type="submit" value="<?php echo $this->lang->line('Submit');?>" name="postContact" class="clsSmall" />
								  <!--<input type="image" src="<?php echo image_url('bt_sbmitmsg.jpg');?>"/>-->
								</p>
							</form>
                            <p>
                              <label>&nbsp;</label>
                              <b><?php echo $this->lang->line('note');?> <a href="<?php echo site_url('users/login');?>"><?php echo $this->lang->line('login here');?></a>.</p>
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
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>