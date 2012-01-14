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
                          <h2><?php echo $this->lang->line('Certificate Memeber'); ?></h2>
                          <?php
							//Show Flash Message
							if($msg = $this->session->flashdata('flash_message'))
							{
								echo $msg;
							}	?>
                          <!--SIGN-UP-->
                          <div id="selSignUp">
                            <h3><span class="clsNewBuyer"><?php echo $this->lang->line('Certificate Memeber'); ?></span></h3>
                            <p><?php echo $this->lang->line('Note');?></p>
							<div class="clearfix">
                            <div class="clsInfoBox">
                              <div class="col-2 left Lspace">
                                <div class="clsInfoBox">
                                  <div class="block">
                                    <div class="grey_t">
                                      <div class="grey_r">
                                        <div class="grey_b">
                                          <div class="grey_l">
                                            <div class="grey_tl">
                                              <div class="grey_tr">
                                                <div class="grey_bl">
                                                  <div class="grey_br">
                                                    <div class="cls100_p1">
                                                      <h4><span class="clsTopseller"><?php echo $this->lang->line('Certificate Buyers');?></span></h4>
                                                      <div class="clsTop clearfix">
                                                        <div class="clsTopLeft clsFloatLeft">
                                                          <p class="clsBorder">
                                                            <?php 
                               foreach($certificatebuyer as $certficates_buyer)
								{
								foreach($certficates_buyer->result() as $certficate_buyer)
								{
								?>
                                                          <h5> <a href="<?php echo site_url('buyer/viewProfile/'.$certficate_buyer->id) ?>"><?php echo $certficate_buyer->user_name;?></a><img src="<?php echo image_url('certified.gif');?>" title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php  echo $this->lang->line('Certified Member')?>" /></h5>
                                                          </p>
                                                          <br>
                                                          <?php 
								}
								}?>
                                                          </p>
                                                        </div>
                                                        <div class="clsTopRight clsFloatLeft"> </div>
                                                      </div>
                                                      <div class="alignRight"> </div>
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
                            <div class="clsInfoBox">
                              <div class="col-2 left Lspace1">
                                <div class="clsInfoBox">
                                  <div class="block">
                                    <div class="grey_t">
                                      <div class="grey_r">
                                        <div class="grey_b">
                                          <div class="grey_l">
                                            <div class="grey_tl">
                                              <div class="grey_tr">
                                                <div class="grey_bl">
                                                  <div class="grey_br">
                                                    <div class="cls100_p1">
                                                      <h4><span class="clsTopseller"><?php echo $this->lang->line('Certificate Providers');?></span></h4>
                                                      <div class="clsTop clearfix">
                                                        <div class="clsTopLeft clsFloatLeft">
                                                          <p class="clsBorder">
                                                            <?php 
								 foreach($certificateseller as $certficates_seller)
								{
								foreach($certficates_seller->result() as $certficate_seller)
								{
								?>
                                                          <h5><a href="<?php echo site_url('seller/viewProfile/'.$certficate_seller->id) ?>"><?php echo $certficate_seller->user_name;?></a><img src="<?php echo image_url('certified.gif');  ?>" title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php echo $this->lang->line('Certified Member') ?>" /></h5>
                                                          </p>
                                                          <!--<p><?php echo character_limiter($certficate_seller->profile_desc,'115');?></p>-->
                                                          <br>
                                                          <?php 
								}
								}?>
                                                          </p>
                                                        </div>
                                                        <div class="clsTopRight clsFloatLeft"> </div>
                                                      </div>
                                                      <div class="alignRight"> </div>
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
                            <!-- <p><b><?php echo $this->
                            lang->line('question');?></b><br/>
                            <?php echo $this->lang->line('answer');?>
                            </p>
                            <p><b><?php echo $this->lang->line('question1');?></b><br/>
                              <?php echo $this->lang->line('answer1');?></p>
                            <p><b><?php echo $this->lang->line('question2');?></b><br/>
                              <?php echo $this->lang->line('answer2');?></p>
                            <p><b><?php echo $this->lang->line('question3');?></b><br/>
                              <?php echo $this->lang->line('answer3');?></p>
                            <p><b><?php echo $this->lang->line('question4');?></b><br/>
                              <?php echo $this->lang->line('answer4');?></p>
                            <p><b><?php echo $this->lang->line('question5');?></b><br/>
                              <?php echo $this->lang->line('answer5');?></p>
                            -->
							</div>
                            <p class="AlignRight"><a href ="<?php echo site_url('certificate/viewallpackage'); ?>"><img src="<?php echo image_url('bt_black_package.png'); ?>" />
                              <?php //echo $this->lang->line('View All Packages');?>
                              </a></p>
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
</div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>
