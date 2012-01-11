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
                                                <div class="clsJavaScript">
                                                  <h2>Javascript Project List</h2>
                                                  <br/>
                                                  <p>If you want to display a list of open projects on your website, copy and paste the following javascript tag onto your website pages:</p>
                                                  <br/>
                                                  <p>
                                                    <textarea cols="70" rows="4" name="select1"><script src="<? echo site_url(); ?>/affiliate/projectList/<? if(isset($this->loggedInUser->user_name)) echo $this->loggedInUser->user_name; else echo 'USER'; ?>"></script>
</textarea>
                                                  </p>
                                                  <br/>
                                                  <p><a href="#">Select HTML</a></p>
                                                  <br/>
                                                  <? if(!isset($this->loggedInUser->user_name)) { ?>
                                                  <p>(Replace <span>USER</span> with your username.)</p>
                                                  <? } else echo '';?>
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
