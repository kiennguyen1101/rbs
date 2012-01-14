<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!-- main -->

<div id="main">
<div class="clsInnerCommon ">
  <div class="clsHeaderLeft">
    <div class="clsHeaderRight">
      <div class="clsHeaderCenter">
        <h5 class="clssearchicon">Affiliate Program</h5>
      </div>
    </div>
  </div>
</div>
<!--Menu-->
<div id="listProjects" class="clsInfoBox">
  <div class="block">
    <div class="main_t">
      <div class="main_r">
        <div class="main_b">
          <div class="main_l">
            <div class="main_tl">
              <div class="main_tr">
                <div class="main_bl">
                  <div class="main_br">
                    <div class="cls100_p">
                      <div id="tabMenu" class="clsCommonNav">
                        <?php $this->load->view('innerMenu1'); ?>
                      </div>
                      <div class="clsAffiliate">
                        <div class="block">
                          <div class="grey_t">
                            <div class="grey_r">
                              <div class="grey_b">
                                <div class="grey_l">
                                  <div class="grey_tl">
                                    <div class="grey_tr">
                                      <div class="grey_bl">
                                        <div class="grey_br padding0">
                                          <div class="cls100_p ">
                                            <div class="clsprogram">
                                              <div class="clsBanners">
                                                <h2>Banners</h2>
                                                <p class="clsbanCreate">All our banners have been created by graphic designers on IBoxAudio. Select the one which best fits your needs. Replace <span>USER</span> with your username. For faster loading times we recommend you download and host the graphics on your own site.</p>
                                                <p>Designed by <a href="#"><b>Dewed</b></a></p>
                                                <p>Format: <b>PNG</b></p>
                                                <p>Dimensions: <b>468 x 60</b></p>
                                                <p>Size: <b>17.8 KB</b></p>
                                                <p><img src="<?php echo base_url() ?>app/css/images/banner_9_468x60.jpg" width="468" height="60" alt="banner"/></p>
                                                <p>
                                                  <textarea cols="70" rows="3" name="select1"><a href="<?php echo base_url(); ?>index.php/affiliate/ref/<?php if(isset($loggedInUser->user_name)) echo $loggedInUser->user_name; else echo 'USER'; ?>"><IMG SRC="<?php echo base_url(); ?>app/css/images/banner_9_468x60.jpg" WIDTH="468" HEIGHT="60" BORDER="0" ALT="Find sellers and graphic design experts at ifindaudio.com"></a></textarea>
                                                </p>
                                                <p><a href="#">Select HTML</a></p>
                                              </div>
                                              <div class="clsBanners">
                                                <p>Designed by <a href="#"><b>Wishdesign</b></a></p>
                                                <p>Format: <b>JPG</b></p>
                                                <p>Dimensions: <b>728 x 90</b></p>
                                                <p>Size: <b>31 KB</b></p>
                                                <p><img src="<?php echo base_url() ?>app/css/images/banner_4_728x90.jpg" width="728" height="90" alt="banner"/></p>
                                                <p>
                                                  <textarea cols="70" rows="3" name="select1"><a href="<?php echo base_url(); ?>index.php/affiliate/ref/<?php if(isset($loggedInUser->user_name)) echo $loggedInUser->user_name; else echo 'USER'; ?>"><IMG SRC="<?php echo base_url(); ?>app/css/images/banner_4_728x90.jpg" WIDTH="728" HEIGHT="90" BORDER="0" ALT="Outsource your programming projects at ifindaudio.com today - Free signup"></a></textarea>
                                                </p>
                                                <p><a href="#">Select HTML</a></p>
                                              </div>
                                              <div class="clsBanners">
                                                <p>Designed by <a href="#"><b>Deepsniti</b></a></p>
                                                <p>Format: <b>GIF</b></p>
                                                <p>Dimensions: <b>300 x 250</b></p>
                                                <p>Size: <b>49.8 KB</b></p>
                                                <p><img src="<?php echo base_url() ?>app/css/images/banner_2_300x250.jpg" width="300" height="250" alt="banner"/></p>
                                                <p>
                                                  <textarea cols="70" rows="3" name="select1"><a href="<?php echo base_url(); ?>index.php/affiliate/ref/<?php if(isset($loggedInUser->user_name)) echo $loggedInUser->user_name; else echo 'USER'; ?>"><IMG SRC="<?php echo base_url(); ?>app/css/images/banner_2_300x250.jpg" WIDTH="300" HEIGHT="250" BORDER="0" ALT="Outsource your projects to thousands of sellers at ifindaudio.com"></a></textarea>
                                                </p>
                                                <p><a href="#">Select HTML</a></p>
                                              </div>
                                              <div class="clsBanners">
                                                <p>Designed by <a href="#"><b>Programguru</b></a></p>
                                                <p>Format: <b>GIF</b></p>
                                                <p>Dimensions: <b>120 x 600</b></p>
                                                <p>Size: <b>32.6 KB</b></p>
                                                <p><img src="<?php echo base_url() ?>app/css/images/banner_8_120x600.jpg" width="120" height="600" alt="banner"/></p>
                                                <p>
                                                  <textarea cols="70" rows="3" name="select1"><a href="<?php echo base_url(); ?>index.php/affiliate/ref/<?php if(isset($loggedInUser->user_name)) echo $loggedInUser->user_name; else echo 'USER'; ?>"><IMG SRC="<?php echo base_url(); ?>app/css/images/banner_8_120x600.jpg" WIDTH="120" HEIGHT="600" BORDER="0" ALT="Thousands of experts bid on your personal project at ifindaudio.com"></a></textarea>
                                                </p>
                                                <p><a href="#">Select HTML</a></p>
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
                      <!--end of clsProgram-->
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
  <!--end of clsAffiliate-->
  <!--end of clsAffiliate-->
</div>
<!-- end of main -->
<?php $this->load->view('footer'); ?>
