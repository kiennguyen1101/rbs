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
                                                <div class="clsTextFeed">
                                                  <h2>Developer Text Feed</h2>
                                                  <br/>
                                                  <p>If you want to grab a list of our projects to include in a script, connect to the following URL. This prints out the projects 1 per line, with each data field seperated by a tab space. The following project data is printed, in order: title, url, number of bids, start time (in epoch seconds), end time, categories (seperated by pipelines: | ), and the word "featured" IF it's a featured project.</p>
                                                  <br/>
                                                  <p><? echo site_url(); ?>/affiliate/textFeed/<font color="red">Y</font>/<font color="red"><? if(isset($this->loggedInUser->user_name)) echo $this->loggedInUser->user_name; else echo "USER";?>
                                                    </font></span></p>
                                                  <br/>
                                                  <p>(Replace <span>Y</span> with 1 to display only featured projects, or with 0 to display all.)</p>
                                                  <br/>
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
