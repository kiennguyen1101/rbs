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
                                              <div class="clsTextLink">
                                                <h2>Text Link</h2>
                                                <br/>
                                                <p>All you have to do is link to: <?php echo site_url(); ?>/affiliate/ref/<span><?php if(isset($this->loggedInUser->user_name))echo $this->loggedInUser->user_name; else echo"USER";?>
                                                  </span></big></p>
                                                <br/>
                                                <?php if(!isset($this->loggedInUser->user_name)) { ?>
                                                <p>(Replace<span>USER</span>with your username.)</p>
                                                <?php } ?>
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
<!-- end of main -->
<?php $this->load->view('footer'); ?>
