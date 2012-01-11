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
                              <ul class="clearfix">
                                <li id="gr0" class="selected" classname="selected"> <a href="#"> <em>&nbsp;</em> <span>About</span></a></li>
                                <li id="gr1" class="" classname=""> <a onclick="getCat('1','4','12');" href="javascript:funCall();"> <span>Text Link</span> </a> </li>
                                <li id="gr1" class="" classname=""> <a onclick="getCat('1','4','12');" href="javascript:;"> <span>Banners</span> </a> </li>
                                <li id="gr2" class="" classname=""> <a onclick="getCat('2','4','13');" href="javascript:;"> <span>Javascript Project List</span> </a> </li>
                                <li id="gr3" class="" classname=""> <a onclick="getCat('3','4','14');" href="javascript:;"> <span>Developer Text Feed</span> </a> </li>
                                <li id="gr4" class="" classname=""> <a onclick="getCat('3','4','14');" href="javascript:;"> <span>RSS Feeds</span> </a> </li>
                              </ul>
                            </div>
                            <div class="clsAffiliate">
         <div class="clsprogram">
              <h2>IBoxAudio Affiliate Program</h2>
			  <h3>Earn 50% Commission!</h3>
			  <p>IBoxAudio pays affiliates half the fees we charge users to participate in projects. If you refer a Buyer we will pay you <?php if(isset($affiliate['buyer_affiliate_fee'])) echo $affiliate['buyer_affiliate_fee']; ?>% of ALL the fees we charge that user, as well as <?php if(isset($affiliate['buyer_affiliate_fee'])) echo $affiliate['buyer_affiliate_fee']; ?>% of the fees we charge the Programmer he works with! If you refer a Programmer you will earn <?php if(isset($affiliate['programmer_affiliate_fee'])) echo $affiliate['programmer_affiliate_fee']; ?>% of ALL the fees we charge that user, as well as the Buyer he works with. On top of that you will also earn 50% of <a href="#">Certified Member</a> fees ($5-$12.50) and 50% of <a href="#">Job Listing</a> fees ($19.50). If you refer another affiliate we will also pay you <?php if(isset($affiliate['programmer_affiliate_fee'])) echo $affiliate['programmer_affiliate_fee']; ?>% of whatever they earn!</p>
			  <p>Here's a table that shows what we pay affiliates. Note that we charge Buyers free to post a regular project and we charge Programmers <?php if(isset($settings['PROVIDER_COMMISSION_AMOUNT'])) echo $settings['PROVIDER_COMMISSION_AMOUNT']; ?>% of their bid amount.</p>
              <!-- project details -->
         <div class="clsAffiliateTable">
                            <table width="650" cellspacing="1" cellpadding="2">
                              <tbody>
                                <tr>
                                  <td width="148" align="center" class="dt">&nbsp;</td>
								  <td width="248" class="dt">Refer aBuyer earn...</td>
                                  <td width="248" align="center" class="dt">Refer a Programmer earn...</td>
                                </tr>
                                <tr>
                                  <td class="dt1 dt0"><b>Regular Project</b></td>
                                  <td class="dt1"><b><?php if(isset($affiliate['buyer_affiliate_fee'])) echo $affiliate['buyer_affiliate_fee']; ?>%</b> of fee we charge their selected Programmer (Min: $<?php if(isset($affiliate['buyer_min_amount'])) echo $affiliate['buyer_min_amount']; ?>)<br />
                                  <br />Minimum payout per project: <b>$<?php if(isset($affiliate['buyer_min_payout'])) echo $affiliate['buyer_min_payout']; ?>.00</b><br />Maximum payout per project: <b><?php if(isset($affiliate['buyer_max_payout'])) echo $affiliate['buyer_max_payout']; ?></b></td>
                                  <td class="dt1"><b><?php if(isset($affiliate['programmer_affiliate_fee'])) echo $affiliate['programmer_affiliate_fee']; ?>%</b> of fee charged to Programmer (Min: $<?php if(isset($affiliate['programmer_min_amount'])) echo $affiliate['programmer_min_amount']; ?>                                    )<br />
                                  <br />Minimum payout per project: <b>$<?php if(isset($affiliate['programmer_min_payout'])) echo $affiliate['programmer_min_payout']; ?>.00</b><br />Maximum payout per project: <b><?php if(isset($affiliate['programmer_max_payout'])) echo $affiliate['programmer_max_payout']; ?></b></td>
                                </tr>
                                <tr>
                                  <td class="dt2 dt0"><b>Featured Project (?)</b></td>								
                                  <td class="dt2"><b><?php if(isset($affiliate['buyer_project_fee'])) echo $affiliate['buyer_project_fee']; ?>%</b> of Featured project fee ($10). Plus <?php if(isset($affiliate['buyer_project_fee'])) echo $affiliate['buyer_project_fee']; ?>% of fee charged to selected Programmer.</td>
                                  <td  class="dt2"><b><?php if(isset($affiliate['programmer_project_fee'])) echo $affiliate['programmer_project_fee']; ?>%</b> of Featured project fee ($2.50). Plus <?php if(isset($affiliate['programmer_project_fee'])) echo $affiliate['programmer_project_fee']; ?>% of fee charged to selected Programmer.</td>
                                </tr>

                              </tbody>
                            </table>
							  </div>
								 <!-- end of project details -->
								 <p>Example: If you refer a visitor who posts a normal project that gets completed for $1000, you will earn $20. </p>
								 <p>If you want to become an affiliate simply signup for an account. Contact us with the form below if you have any questions or if there is any way we can help you setup your site as an affiliate. Thank you.</p>
									<div>		   <?php
									//Show Flash Message
									if($msg = $this->session->flashdata('flash_message'))
									{
										echo $msg;
									}
								  ?></div>
								   <div class="clsAffiliateQuestion">
								   <form name="affiliateQuestionForm" action="<?php echo site_url() ?>/affiliate" method="post">
									  <h3>Affiliate Questions</h3>
									  <? if(isset($email_exist)) {?>
									  <p style="color:#FF0000"><label>&nbsp;</label><? if(isset($email_exist)) echo $email_exist; ?></p>
									  <? } ?>
									  <p><label>Your Email:</label><input class="clsTextBox" type="text" value="" name="your_email" /><?php echo form_error('your_email'); ?></p>
									  <p><label>Subject:</label><input class="clsTextBox" type="text" value="Affiliate Question" name="Subject" /></p>
									  <p><label>Your Question:</label><textarea cols="50" name="description" rows="10"></textarea></p>
									  <p><input class="clsSubmit" type="submit" value="Submit Message" name="submit_questions"/></p>
									  <p class="clsNote">Note: This contact form is for guest affiliate inquiries only. If you already have an account, please login here.</p>
								   </form>
								   </div>
								   
								   <div class="clsVisitor">
								   <h2>How to refer visitors: </h2>
								   <ul>
									  <li><a href="#">Text Link</a></li>
									  <li><a href="#">Banners</a></li>
									  <li><a href="#">Javascript Project List</a></li>
									  <li><a href="#">Developer Text Feed</a></li>
									  <li><a href="#">RSS Feeds</a></li>
								   </ul>
								   </div>
								   <div class="clsTextLink">
									  <h2>Text Link</h2>
									  <p><big>All you have to do is link to: <? echo base_url(); ?>?ref=<span>USER</span></big></p>
									  <p>(Replace <span>USER</span> with your username.)</p>
								   </div>
								   <div class="clsJavaScript">
									 <h2>Javascript Project List</h2>
									 <p>If you want to display a list of open projects on your website, copy and paste the following javascript tag onto your website pages:</p>
									 <p><textarea cols="70" rows="4" name="select1"><script src="<? echo base_url(); ?>cgi-bin/freelancers/d_projects.cgi?java=1&text=1&show=20&r=<? if(isset($loggedInUser->user_name)) echo $loggedInUser->user_name; else echo 'USER'; ?>"></script></textarea></p>
									 <p><a href="#">Select HTML</a></p>
									 <p>(Replace <span>USER</span> with your username.)</p> 
								   </div>
								   
								   <div class="clsTextFeed">
									 <h2>Developer Text Feed</h2>
									 <p>If you want to grab a list of our projects to include in a script, connect to the following URL. This prints out the projects 1 per line, with each data field seperated by a tab space. The following project data is printed, in order: title, url, number of bids, start time (in epoch seconds), end time, categories (seperated by pipelines: | ), and the word "featured" IF it's a featured project.</p>
									 <p><? echo base_url(); ?>cgi-bin/freelancers/d_projects.cgi?db=1&text=1&f=<span>Y</span>&r=<span>USER</span></p>
									 <p>(Replace <span>Y</span> with 1 to display only featured projects, or with 0 to display all.)</p>
								   </div>
								   
								   <div class="clsRssFeed">
									 <h2>Developer RSS Feeds</h2>
									 <p><a href="#">Click here</a> to view our project RSS feeds and make your own custom RSS feed!</p>
								   </div>
								   
								   <div class="clsBanners">
									  <h2>Banners</h2>
									  <p class="clsbanCreate">All our banners have been created by graphic designers on IBoxAudio. Select the one which best fits your needs. Replace <span>USER</span> with your username. For faster loading times we recommend you download and host the graphics on your own site.</p>
									  <p>Designed by <a href="#"><b>Dewed</b></a></p>
									  <p>Format: <b>PNG</b></p>
									  <p>Dimensions: <b>468 x 60</b></p>
									  <p>Size: <b>17.8 KB</b></p>
									  <p><img src="<?php echo base_url() ?>app/css/images/banner_9_468x60.jpg" width="468" height="60" alt="banner"/></p>
									  <p><textarea cols="70" rows="3" name="select1"><a href="<? echo base_url(); ?>index.php/affiliate/ref/<? if(isset($loggedInUser->user_name)) echo $loggedInUser->user_name; else echo 'USER'; ?>"><IMG SRC="<? echo base_url(); ?>app/css/images/banner_9_468x60.jpg" WIDTH="468" HEIGHT="60" BORDER="0" ALT="Find programmers and graphic design experts at ifindaudio.com"></a></textarea></p>
									  <p><a href="#">Select HTML</a></p>
								   </div>
								   
									<div class="clsBanners">
									  <p>Designed by <a href="#"><b>Wishdesign</b></a></p>
									  <p>Format: <b>JPG</b></p>
									  <p>Dimensions: <b>728 x 90</b></p>
									  <p>Size: <b>31 KB</b></p>
									  <p><img src="<?php echo base_url() ?>app/css/images/banner_4_728x90.jpg" width="728" height="90" alt="banner"/></p>
									  <p><textarea cols="70" rows="3" name="select1"><a href="<? echo base_url(); ?>index.php/affiliate/ref/<? if(isset($loggedInUser->user_name)) echo $loggedInUser->user_name; else echo 'USER'; ?>"><IMG SRC="<? echo base_url(); ?>app/css/images/banner_4_728x90.jpg" WIDTH="728" HEIGHT="90" BORDER="0" ALT="Outsource your programming projects at ifindaudio.com today - Free signup"></a></textarea></p>
									  <p><a href="#">Select HTML</a></p>
								   </div>
								   
									<div class="clsBanners">
									  <p>Designed by <a href="#"><b>Deepsniti</b></a></p>
									  <p>Format: <b>GIF</b></p>
									  <p>Dimensions: <b>300 x 250</b></p>
									  <p>Size: <b>49.8 KB</b></p>
									  <p><img src="<?php echo base_url() ?>app/css/images/banner_2_300x250.jpg" width="300" height="250" alt="banner"/></p>
									  <p><textarea cols="70" rows="3" name="select1"><a href="<? echo base_url(); ?>index.php/affiliate/ref/<? if(isset($loggedInUser->user_name)) echo $loggedInUser->user_name; else echo 'USER'; ?>"><IMG SRC="<? echo base_url(); ?>app/css/images/banner_2_300x250.jpg" WIDTH="300" HEIGHT="250" BORDER="0" ALT="Outsource your projects to thousands of programmers at ifindaudio.com"></a></textarea></p>
									  <p><a href="#">Select HTML</a></p>
								   </div>
								   
								   <div class="clsBanners">
									  <p>Designed by <a href="#"><b>Programguru</b></a></p>
									  <p>Format: <b>GIF</b></p>
									  <p>Dimensions: <b>120 x 600</b></p>
									  <p>Size: <b>32.6 KB</b></p>
									  <p><img src="<?php echo base_url() ?>app/css/images/banner_8_120x600.jpg" width="120" height="600" alt="banner"/></p>
									  <p><textarea cols="70" rows="3" name="select1"><a href="<? echo base_url(); ?>index.php/affiliate/ref/<? if(isset($loggedInUser->user_name)) echo $loggedInUser->user_name; else echo 'USER'; ?>"><IMG SRC="<? echo base_url(); ?>app/css/images/banner_8_120x600.jpg" WIDTH="120" HEIGHT="600" BORDER="0" ALT="Thousands of experts bid on your personal project at ifindaudio.com"></a></textarea></p>
									  <p><a href="#">Select HTML</a></p>
								   </div>
								   
								</div> <!--end of clsProgram--> 
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
      <!--end of clsAffiliate-->       <!--end of clsAffiliate-->
	  
    </div>
    <!-- end of main -->
<?php $this->load->view('footer'); ?>
<script>
function funCall() {
 alert('');
}
</script>