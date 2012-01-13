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
                        <div class="clsAffiliate1">
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
                                              <div class="clsprogram1">
                                                <h2>RBS Affiliate Program</h2>
                                                <h3 class="fsize">Earn <?php echo $affiliate['buyer_affiliate_fee']+$affiliate['seller_affiliate_fee']; ?>% Commission!</h3>
                                                <p class="fsize1">RBS pays affiliates half the fees we charge users to participate in projects. If you refer a Buyer we will pay you
                                                  <?php if(isset($affiliate['buyer_affiliate_fee'])) echo $affiliate['buyer_affiliate_fee']; ?>
                                                  % of ALL the fees we charge that user, as well as
                                                  <?php if(isset($affiliate['buyer_affiliate_fee'])) echo $affiliate['buyer_affiliate_fee']; ?>
                                                  % of the fees we charge the Seller he works with! If you refer a Seller you will earn
                                                  <?php if(isset($affiliate['seller_affiliate_fee'])) echo $affiliate['seller_affiliate_fee']; ?>
                                                  % of ALL the fees we charge that user, as well as the Buyer he works with.</p>
                                                <p class="fsize1">Here's a table that shows what we pay affiliates. Note that we charge Buyers free to post a regular project and we charge Sellers
                                                  <?php if(isset($settings['PROVIDER_COMMISSION_AMOUNT'])) echo $settings['PROVIDER_COMMISSION_AMOUNT']; ?>
                                                  % of their bid amount.</p>
                                                <!-- project details -->
                                                <div class="clsAffiliateTable1">
                                                  <table width="650" cellspacing="1" cellpadding="2">
                                                    <tbody>
                                                      <tr>
                                                        <td width="148" align="center" class="dt">&nbsp;</td>
                                                        <td width="248" class="dt">Refer aBuyer earn...</td>
                                                        <td width="248" align="center" class="dt">Refer a Seller earn...</td>
                                                      </tr>
                                                      <tr class="BdrLeft">
                                                        <td class="dt1 dt0"><b>Regular Project</b></td>
                                                        <td class="dt1"><b>
                                                          <?php if(isset($affiliate['buyer_affiliate_fee'])) echo $affiliate['buyer_affiliate_fee']; ?>
                                                          %</b> of fee we charge their selected Seller 
                                     
                                                          <br />
                                                          <br />
                                                         
                                                          Maximum payout per project: <b>
                                                          <?php if(isset($affiliate['buyer_max_payout'])) echo $affiliate['buyer_max_payout']; ?>
                                                          </b></td>
                                                        <td class="dt1"><b>
                                                          <?php if(isset($affiliate['seller_affiliate_fee'])) echo $affiliate['seller_affiliate_fee']; ?>
                                                          %</b> of fee charged to Seller
                                                          <br />
                                                         <br />
                                                          Maximum payout per project: <b>
                                                          <?php if(isset($affiliate['seller_max_payout'])) echo $affiliate['seller_max_payout']; ?>
                                                          </b></td>
                                                      </tr>
                                                      <tr class="BdrLeft">
                                                        <td class="dt2 dt0 "><b>Featured Project (?)</b></td>
                                                        <td class="dt2"><b>
                                                          <?php if(isset($affiliate['buyer_project_fee'])) echo $affiliate['buyer_project_fee']; ?>
                                                          %</b> of Featured project fee ($<? echo $settings['FEATURED_PROJECT_AMOUNT'] * $affiliate['buyer_project_fee']/100; ?>). Plus
                                                          <?php if(isset($affiliate['buyer_project_fee'])) echo $affiliate['buyer_project_fee']; ?>
                                                          % of fee charged to selected Seller.</td>
                                                        <td  class="dt2"><b>
                                                          <?php if(isset($affiliate['seller_project_fee'])) echo $affiliate['seller_project_fee']; ?>
                                                          %</b> of Featured project fee ($<? echo $settings['FEATURED_PROJECT_AMOUNT'] * $affiliate['seller_project_fee']/100; ?>). Plus
                                                          <?php if(isset($affiliate['seller_project_fee'])) echo $affiliate['seller_project_fee']; ?>
                                                          % of fee charged to selected Seller.</td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </div>
                                                <!-- end of project details -->
                                                <p>Example: If you refer a visitor who posts a normal project that gets completed for $1000, you will earn $
                                                  <?php 
								    $bid_amount 	=	1000;
								 	$provider_percentage_amount = $bid_amount * ($settings['PROVIDER_COMMISSION_AMOUNT']/100);
									$commision_amount = $provider_percentage_amount * ($affiliate['buyer_affiliate_fee']/100);	
									echo $commision_amount;
								 ?>
                                                  . </p>
                                                <p>If you want to become an affiliate simply signup for an account. Contact us with the form below if you have any questions or if there is any way we can help you setup your site as an affiliate. Thank you.</p>
                                                <div>
                                                  <?php
									//Show Flash Message
									if($msg = $this->session->flashdata('flash_message'))
									{
										echo $msg;
									}
								  ?>
                                                </div>
                                                <div class="clsAffiliateQuestion1">
                                                  <form name="affiliateQuestionForm" action="<?php echo site_url() ?>/affiliate" method="post">
                                                    <h3>Affiliate Questions</h3>
                                                    <? if(isset($question_failed)) {?>
                                                    <p style="color:#FF0000">
                                                      <label>&nbsp;</label>
                                                      <? if(isset($question_failed)) echo $question_failed; ?>
                                                    </p>
                                                    <br/>
                                                    <? } ?>
                                                    <p>
                                                      <label>Your Email:</label>
                                                      <input class="clsAffiliateTextBox" type="text" value="" name="your_email" />
                                                      <?php echo form_error('your_email'); ?></p>
                                                    <p>
                                                      <label>Subject:</label>
                                                      <input class="clsAffiliateTextBox" type="text" value="Affiliate Question" name="Subject" />
                                                    </p>
                                                    <p>
                                                      <label>Your Question:</label>
                                                      <textarea cols="50" name="description" rows="10"></textarea>
                                                    </p>
                                                    <p>
                                                      <label>&nbsp;</label>
                                                      <span> <a class="buttonBlackShad" href="#"><span>Submit Message</span></a></span></p>
                                                  </form>
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
  </div>
</div>
<!--end of clsAffiliate-->
<!--end of clsAffiliate-->
<!-- end of main -->
<?php $this->load->view('footer'); ?>
