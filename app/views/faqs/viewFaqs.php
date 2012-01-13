<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
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
                              <h2><?php echo $this->lang->line('title'); ?></h2>
					          <p><?php echo $this->lang->line('doubt');?></p>
							  
							   <h3><span class="clsCategory"><?php echo $this->lang->line('sub_title'); ?></span></h3>
							   <div class="clsOptionalDetails">
								<ul>
								<?php
									if(isset($frequentFaqs) and $frequentFaqs->num_rows()>0)
									{
										foreach($frequentFaqs->result() as $frequentFaq)
										{
								?>		
										<li class="clSNoBack"><a href="<?php echo site_url('faq/view/'.$frequentFaq->id); ?>"><?php echo $frequentFaq->question; ?></a></li>
								   <?php
										}//Foreach End
									}//If End
								?>
								</ul>
							  </div>
							 <h3><span class="clsViewPro"><a href="<?php echo site_url('faq/all'); ?>"><?php echo $this->lang->line('browse'); ?></a></span></h3>
								<form method="get" action="<?php echo site_url('faq')?>">
								<p><?php echo $this->lang->line('Search the FAQ database'); ?>...</p>
								<p><input type="text" name="keywords" size="20"/>
								 <?php echo $this->lang->line('Match'); ?> <select name="match" size="1">
										  <option><?php echo $this->lang->line('All'); ?></option>
										  <option><?php echo $this->lang->line('Any'); ?></option>
										  </select> <?php echo $this->lang->line('words'); ?>.
										 								<input type="submit" value="<?php echo $this->lang->line('Search'); ?>" name="m" class="clsMini"/> 
										   </p>
										  <input type="hidden" name="c" value="faq" />
								</form>
								<p><?php echo $this->lang->line('If you dont find your answer here, you can'); ?> <a href="<?php echo site_url('contact');?>"><?php echo $this->lang->line('contact Support Staff'); ?>...</a></p>
							<p><b><?php echo $this->lang->line('Submit Message to Scriptlance support Stafff...');?></b></p>
							<ul>
							<li>
								<!-- Puhal Chnages Start for the Support Desk (Sep 18 Issue 7)-->
						  
						   <?php if(isset($loggedInUser->id)) { ?>
							<b><a href="<?php echo site_url('support');?>"> <?php echo $this->lang->line('Members: Login to Support Desk...');?></a></b>
							<?php }else{ ?>
							<b><a href="<?php echo site_url('users/login/support');?>"> <?php echo $this->lang->line('Members: Login to Support Desk...');?></a></b>
							<?php } ?>
							<!-- Puhal Chnages End for the Support Desk (Sep 18 Issue 7)-->	
							</li>
							</ul>
							<div class="clsContactForm clSTextDec">
							<h3><span class="clsInvoice"><?php echo $this->lang->line('Guest Sales Questions'); ?></span></h3>
							
							<form method="post" action="#">
							   <p>
								  <label><?php echo $this->lang->line('your_email'); ?><span class="red">*</span></label>
								  <input class="clsText" type="text" name="faq_email" value="<?php echo set_value('faq_email'); ?>" />
								   <?php echo form_error('faq_email'); ?>
							  </p>
								<p>
								  <label><?php echo $this->lang->line('subject'); ?><span class="red">*</span></label>
								  <input class="clsText" type="text" name="faq_subject"  value="<?php echo set_value('faq_subject'); ?>"/>
								   <?php echo form_error('faq_subject'); ?>
								</p>
								<p>
								  <label class="clsComments"><?php echo $this->lang->line('comments'); ?><span class="red">*</span></label>
								  <textarea name="faq_comments" rows="10" cols="40"><?php echo set_value('faq_comments'); ?></textarea>
								   <?php echo form_error('faq_comments'); ?>
								</p>
								<p class="clsSubmitBlock">
								<label>&nbsp;</label>
								  <input class="clsMid" type="submit" value="<?php echo $this->lang->line('submit_button'); ?>" name="faqPosts"/>
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
      <!--END OF POST PROJECT-->
     </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>
