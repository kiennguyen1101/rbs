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
                                                <div class="clsRssFeed">
                                                  <h2>Developer RSS Feeds</h2>
                                                  <br/>
                                                  <p><a href="<?php echo site_url() ?>/?c=rss">Click here</a> to view our project RSS feeds and make your own custom RSS feed!</p>
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
                      <!--end of clsAffiliate-->
                      <!--end of clsAffiliate-->
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
<!-- end of main -->
<?php $this->load->view('footer'); ?>
