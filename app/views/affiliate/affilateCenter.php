<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<?
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}	
			

?>
<?
	// Total Affiliate Earnings
	$cur_month	=	 date('Ym');
	//$condition = 'sales.refid = "'.$this->loggedInUser->user_name.'" AND MONTH( sales.created_date) = MONTH( CURRENT_DATE )';
	//$affiliate_search = $this->affiliate_model->getAffiliateSales(NULL,NULL,NULL,NULL,NULL,$condition);
	$condition = 'users.refid = "'.$this->loggedInUser->user_name.'" AND EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME( created ) ) = "'.$cur_month.'" AND users.user_name != "'.$this->loggedInUser->user_name.'"';
	$affiliate_search = $this->affiliate_model->getAffiliateReferels($condition);
	
	$affiliate_sales_total1  = $affiliate_sales_total->row();
	
	// Total Affiliate Earnings for current month
	$condition1 = 'sales.refid = "'.$this->loggedInUser->user_name.'" AND MONTH( sales.created_date) = MONTH( CURRENT_DATE )';
	$affiliate_sales_total_res = $this->affiliate_model->getSalesTotal(NULL,$condition1);
	$affiliate_sales_total2 = $affiliate_sales_total_res->row();
	
		// Released Earnings
		$cond2 = array('affiliate_released_payments.refid'=>$this->loggedInUser->user_name);
		$total_released_amount =  $this->affiliate_model->getReleasedPayments($cond2);
		$arr_total_released = $total_released_amount->row();
		
		// Unreleased Earnings
		$cond3 = array('affiliate_unreleased_payments.refid'=>$this->loggedInUser->user_name,'affiliate_unreleased_payments.is_released' => 0);
		$total_unreleased_amount =  $this->affiliate_model->getUnReleasePayments($cond3);
		$arr_total_unreleased = $total_unreleased_amount->row();
	
		// Released Earnings for current month
		$cond = array('affiliate_released_payments.refid'=>$this->loggedInUser->user_name, 'affiliate_released_payments.created_date_forrmat ' => date('M, Y'));
		$released_amount =  $this->affiliate_model->getReleasedPayments($cond);
		
		$arr_released = $released_amount->row();
		
		// Unreleased Earnings for current month
		$cond1 = array('affiliate_unreleased_payments.refid'=>$this->loggedInUser->user_name, 'affiliate_unreleased_payments.created_date_format' => date('M, Y'),'affiliate_unreleased_payments.is_released' => 0);
		$unreleased_amount =  $this->affiliate_model->getUnReleasePayments($cond1);
		$arr_unreleased = $unreleased_amount->row();
		
		// total unrealesed amount
		$cond1_total = array('affiliate_unreleased_payments.refid'=>$this->loggedInUser->user_name,'affiliate_unreleased_payments.created_date_format' => date('M, Y'),'affiliate_unreleased_payments.is_released' => 0);
		
		$unreleased_total_amount =  $this->affiliate_model->getUnReleasePayments($cond1_total);
		
		$arr_unreleased_total = $unreleased_total_amount->row();
		//pr($arr_unreleased_total);

		
		
		

	
if(isset($_POST['aff_m']) and isset($_POST['aff_y'])) 
{
	if($_POST['aff_m'] == 'Jan') $m = '01';
	if($_POST['aff_m'] == 'Feb') $m = '02';
	if($_POST['aff_m'] == 'Mar') $m = '03';
	if($_POST['aff_m'] == 'Apr') $m = '04';
	if($_POST['aff_m'] == 'May') $m = '05';
	if($_POST['aff_m'] == 'Jun') $m = '06';
	if($_POST['aff_m'] == 'Jul') $m = '07';
	if($_POST['aff_m'] == 'Aug') $m = '08';
	if($_POST['aff_m'] == 'Sep') $m = '09';
	if($_POST['aff_m'] == 'Oct') $m = '10';
	if($_POST['aff_m'] == 'Nov') $m = '11';
	if($_POST['aff_m'] == 'Dec') $m = '12';

	
	
	
	$signup_date_format = $_POST['aff_m'].', '.$_POST['aff_y'];
	$cur_month	=	$_POST['aff_y'].$m;
	
	$condition = 'users.refid = "'.$this->loggedInUser->user_name.'" AND EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME( created ) ) = "'.$cur_month.'" AND users.user_name != "'.$this->loggedInUser->user_name.'"';
	$affiliate_search = $this->affiliate_model->getAffiliateReferels($condition);

	//$condition = 'sales.refid = "'.$this->loggedInUser->user_name.'" AND MONTH( sales.created_date) ='.$m;
	//$affiliate_search = $this->affiliate_model->getAffiliateSales(NULL,NULL,NULL,NULL,NULL,$condition);
	
		$condition = 'users.refid = "'.$this->loggedInUser->user_name.'" AND EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME( created ) ) = "'.$cur_month.'" AND users.user_name != "'.$this->loggedInUser->user_name.'"';
	$affiliate_search = $this->affiliate_model->getAffiliateReferels($condition);
	

	
	//get sales total
	// Total Affiliate Earnings for posted month
	$condition1 = 'sales.refid = "'.$this->loggedInUser->user_name.'" AND MONTH( sales.created_date) ='.$m;
	$affiliate_sales_total_res = $this->affiliate_model->getSalesTotal(NULL,$condition1);
	$affiliate_sales_total2 = $affiliate_sales_total_res->row();
	
		// Released Earnings for posted month
		$cond = array('affiliate_released_payments.refid'=>$this->loggedInUser->user_name, 'affiliate_released_payments.created_date_forrmat ' => $signup_date_format);
		$released_amount =  $this->affiliate_model->getReleasedPayments($cond);
		
		$arr_released = $released_amount->row();
		
		// Unreleased Earnings for posted month
		$cond1 = array('affiliate_unreleased_payments.refid'=>$this->loggedInUser->user_name, 'affiliate_unreleased_payments.created_date_format' => $signup_date_format,'affiliate_unreleased_payments.is_released' => 0);
		$unreleased_amount =  $this->affiliate_model->getUnReleasePayments($cond1);
		
		$arr_unreleased = $unreleased_amount->row();
	   

}
?>
<!--MAIN-->

<div id="main">
<!--POST PROJECT-->
<div class="clsTabs clsInnerCommon clsInfoBox">
  <div class="block">
    <div class="main_t">
      <div class="main_r">
        <div class="main_b">
          <div class="main_l">
            <div class="main_tl">
              <div class="main_tr">
                <div class="main_bl">
                  <div class="main_br">
                    <div class="cls100_p ">
                      <?php $this->load->view('innerMenu'); ?>
                      <div class="clsAffiliate clsTabs ">
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
										  <div class="clsInnerCommon">
                 
                                             <h3><span class="clsAffil">Affiliate Center</span></h3>
                                              <p>Your referral URL is<?php echo site_url();?>/affiliate/ref/<? echo $this->loggedInUser->user_name; ?></p>
                                              <p><a href="<?php echo site_url(); ?>/affiliate">View</a> affiliate program details. </p>
                                              <br/>
                                              <p>Total Affiliate Earnings: 	$
                                                <?php if(isset($affiliate_sales_total1->total)) echo $affiliate_sales_total1->total; else echo "0.00"; ?>
                                              </p>
                                              <p>Released Earnings <font color="green"><b>$
                                                <?php if(isset($arr_total_released->total)) echo $arr_total_released->total; else echo "0.00"; ?>
                                                </b></font></p>
                                              <p>Unreleased Earnings: 	$
                                                <?php if(isset($arr_total_unreleased->total)) echo $arr_total_unreleased->total; else echo "0.00"; ?>
                                              </p>
                                              <br/>
                                              <p><b>Affiliate Stats for
                                                <?php if(isset($_POST['aff_m']) and isset($_POST['aff_y'])) echo $_POST['aff_m'].', '.$_POST['aff_y']; else echo date('M, Y'); ?>
                                                ...</b></p>
                                              <!-- project details -->
                                              <div class="clsAffiliateTable clsPad">
                                                <table width="650" cellspacing="1" cellpadding="2">
                                                  <tbody>
                                                    <tr>
                                                      <td width="248" class="dt"><?php echo $this->lang->line('Referral');?></td>
                                                      <td width="248" class="dt"><?php echo $this->lang->line('Account Type');?></td>
                                                      <td width="148" class="dt"><?php echo $this->lang->line('Date');?></td>
                                                    </tr>
                                                    <?
								$i=0;
								foreach($affiliate_search->result() as $k) {
									$affiliate_sale_result['referral'] 		=  $k->user_name;
									$affiliate_sale_result['account_type'] 	=  $k->role_id;
									$affiliate_sale_result['signup_date'] 	=  $k->created;
									
									$index = $i%2;
									
									if($index == 0) 
									$class = "dt1"; 
									else 
									$class = "dt2";
									if(isset($affiliate_sale_result['referral']) and isset($affiliate_sale_result['account_type']) and $affiliate_sale_result['signup_date'] != 0) {
								?>
                                                    <tr>
                                                      <td  class="<? echo $class; ?>"><? echo $affiliate_sale_result['referral']; ?></td>
                                                      <td class="<? echo $class; ?>"><? if($affiliate_sale_result['account_type'] == 1) echo $this->lang->line('Buyer'); else if($affiliate_sale_result['account_type'] == 2) echo $this->lang->line('Provider'); ?></td>
                                                      <td class="<? echo $class." "."dt0"; ?>"><? echo get_date($affiliate_sale_result['signup_date']); ?></td>
                                                    </tr>
                                                    <?
								}
								$i++;
								}
								?>
                                                    <?php 
								if($affiliate_search->num_rows == 0) { ?>
                                                    <tr>
                                                      <td colspan="3" align="center">No records found</td>
                                                    </tr>
                                                    <?php } ?>
                                                  </tbody>
                                                </table>
                                              </div>
                                              <!-- end of project details -->
                                              <br/>
                                              <?php 
		 $month = '';
		 if(isset($_POST['aff_m']) and isset($_POST['aff_y'])) {
		 	if($_POST['aff_m'] == 'Jan') $month = 'January';
		 	if($_POST['aff_m'] == 'Feb') $month = 'February';
		 	if($_POST['aff_m'] == 'Mar') $month = 'March';
		 	if($_POST['aff_m'] == 'Apr') $month = 'April';
		 	if($_POST['aff_m'] == 'May') $month = 'May';
		 	if($_POST['aff_m'] == 'Jun') $month = 'June';
		 	if($_POST['aff_m'] == 'Jul') $month = 'July';
		 	if($_POST['aff_m'] == 'Aug') $month = 'August';
		 	if($_POST['aff_m'] == 'Sep') $month = 'September';
		 	if($_POST['aff_m'] == 'Oct') $month = 'October';
		 	if($_POST['aff_m'] == 'Nov') $month = 'November';
		 	if($_POST['aff_m'] == 'Dec') $month = 'December';
		 } 
		 
		 ?>
                                              <p>
                                                <? if(isset($_POST['aff_m'])) echo $month; if($month == '') echo date('F'); ?>
                                                Earnings: 	$
                                                <? if(isset($affiliate_sales_total2->total)) echo $affiliate_sales_total2->total; else echo "0.00"; ?>
                                              </p>
                                              <p>
                                                <? if(isset($_POST['aff_m'])) echo $month; if($month == '') echo date('F'); ?>
                                                Released Earnings: <font color="green"><b>$
                                                <?php if(isset($arr_released->total)) echo $arr_released->total; else echo "0.00"; ?>
                                                </b></font></p>
                                              <p>
                                                <? if(isset($_POST['aff_m'])) echo $month; if($month == '') echo date('F'); ?>
                                                Unreleased Earnings: 	$
                                                <? if(isset($arr_unreleased->total)) echo $arr_unreleased->total; else echo "0.00"; ?>
                                              </p>
                                              <br/>
                                              <!--	  <p><b>Affiliate Stats</b></p>-->
                                              <!-- project details -->
                                              <div class="clsAffiliateTable clsPad">
                                                <table width="650" cellspacing="1" cellpadding="2">
                                                  <tbody>
                                                    <tr>
                                                      <td width="248" class="dt"><?php echo $this->lang->line('Amount');?></td>
                                                      <td width="248" class="dt"><?php echo $this->lang->line('Referral');?></td>
                                                      <td width="148" class="dt"><?php echo $this->lang->line('Date');?></td>
                                                    </tr>
                                                    <?
								$i=0;
								foreach($affiliate_sales->result() as $k) {
									$affiliate_sale_result['created_date'] 		=  $k->created_date;
									$affiliate_sale_result['referral'] 		=  $k->referral;
									$affiliate_sale_result['payment'] 	=  $k->payment ;
									$index = $i%2;
									
									if($index == 0) 
									$class = "dt1"; 
									else 
									$class = "dt2";
								?>
                                                    <tr>
                                                      <td  class="<? echo $class; ?>"><? echo $affiliate_sale_result['payment']; ?></td>
                                                      <td class="<? echo $class; ?>"><? echo $affiliate_sale_result['referral']; ?></td>
                                                      <td class="<? echo $class." "."dt0"; ?>"><? echo $affiliate_sale_result['created_date']; ?></td>
                                                    </tr>
                                                    <?
								$i++;
								}
								?>
                                                    <?php 
								if($affiliate_sales->num_rows == 0) { ?>
                                                    <tr>
                                                      <td colspan="3" align="center">No records found</td>
                                                    </tr>
                                                    <?php } ?>
                                                  </tbody>
                                                </table>
                                              </div>
                                              <p>
                                              <form method="POST" action="">
                                               <p> <input type="hidden" name="affiliate" value="1">
                                                View stats for
												
                                                <select name="aff_m" size="1">
                                                  <option value="Jan" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'Jan') echo 'selected'; } else { if(date('M') == 'Jan') echo 'selected'; } ?> >January</option>
                                                  <option value="Feb" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'Feb') echo 'selected'; } else { if(date('M') == 'Feb') echo 'selected'; } ?>>February</option>
                                                  <option value="Mar" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'Mar') echo 'selected'; } else { if(date('M') == 'Mar') echo 'selected'; } ?>>March</option>
                                                  <option value="Apr" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'Apr') echo 'selected'; } else { if(date('M') == 'Apr') echo 'selected'; } ?>>April</option>
                                                  <option value="May" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'May') echo 'selected'; } else { if(date('M') == 'May') echo 'selected'; } ?>>May</option>
                                                  <option value="Jun" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'Jun') echo 'selected'; } else { if(date('M') == 'Jun') echo 'selected'; } ?>>June</option>
                                                  <option value="Jul" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'Jul') echo 'selected'; } else { if(date('M') == 'Jul') echo 'selected'; } ?>>July</option>
                                                  <option value="Aug" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'Aug') echo 'selected'; } else { if(date('M') == 'Aug') echo 'selected'; } ?>>August</option>
                                                  <option value="Sep" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'Sep') echo 'selected'; } else { if(date('M') == 'Sep') echo 'selected'; } ?>>September</option>
                                                  <option value="Oct" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'Oct') echo 'selected'; } else { if(date('M') == 'Oct') echo 'selected'; } ?>>October</option>
                                                  <option value="Nov" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'Nov') echo 'selected'; } else { if(date('M') == 'Nov') echo 'selected'; } ?>>November</option>
                                                  <option value="Dec" <?php if(isset($_POST['aff_m'])) { if($_POST['aff_m'] == 'Dec') echo 'selected'; } else { if(date('M') == 'Dec') echo 'selected'; } ?>>December</option>
                                                </select>
                                                <select name="aff_y" size="1">
                                                  <option value="2006" <?php if(isset($_POST['aff_y'])) { if($_POST['aff_y'] == '2006') echo 'selected'; } else { if(date('Y') == '2006') echo 'selected'; } ?>>2006</option>
                                                  <option value="2007" <?php if(isset($_POST['aff_y'])) { if($_POST['aff_y'] == '2007') echo 'selected'; } else { if(date('Y') == '2007') echo 'selected'; } ?>>2007</option>
                                                  <option value="2008" <?php if(isset($_POST['aff_y'])) { if($_POST['aff_y'] == '2008') echo 'selected'; } else { if(date('Y') == '2008') echo 'selected'; } ?>>2008</option>
                                                  <option value="2009" <?php if(isset($_POST['aff_y'])) { if($_POST['aff_y'] == '2009') echo 'selected'; } else { if(date('Y') == '2009') echo 'selected'; } ?>>2009</option>
                                                  <option value="2010" <?php if(isset($_POST['aff_y'])) { if($_POST['aff_y'] == '2010') echo 'selected'; } else { if(date('Y') == '2010') echo 'selected'; } ?>>2010</option>
                                                  <option value="2011" <?php if(isset($_POST['aff_y'])) { if($_POST['aff_y'] == '2011') echo 'selected'; } else { if(date('Y') == '2011') echo 'selected'; } ?>>2011</option>
                                                  <option value="2012" <?php if(isset($_POST['aff_y'])) { if($_POST['aff_y'] == '2012') echo 'selected'; } else { if(date('Y') == '2012') echo 'selected'; } ?>>2012</option>
                                                </select>
                                                <input type="submit" value="Go" name="submit" class="clsSmall1">
												</p>
								
                                              </form>
                                        
                                              <br/>
                                              <p>Your referral URL is <?php echo site_url(); ?>/affiliate/ref/<? echo $this->loggedInUser->user_name; ?></p>
                                              <br>
                                              <p><b>Referral Welcome Message</b></p>
                                              <p style="width:400px">You can display a welcome message to your referrals when they signup. 
                                                Consider providing your contact information and helping them out with any questions they may have about the site. 
                                                It will increase the number of successfully completed projects and thereby earning you more affiliate commissions!</p>
                                              <br>
                                              <p style="width:400px">Example: Welcome to <?php echo $this->config->item('site_title');?>. My name is <?php echo $this->loggedInUser->user_name; ?>. Feel free to contact me at <?php echo $this->loggedInUser->email; ?> if have any questions about using the site. Good Luck!</p>
                                              <form method="POST" action="<?php echo site_url() ?>/affiliate/manageAffiliates">
                                               <p> <input type="hidden" name="affiliate_welcome" value="1">
                                                <textarea rows="10" name="welcome_message" cols="50"><?php if(isset($affiliate_welcome_msg)) echo $affiliate_welcome_msg; ?>
</textarea></p>
                                                <div><?php echo form_error('welcome_message'); ?></div>
                                                <br>
                                                <div class="buttonwrapperAff_Save">
                                                  <!--<p><span class="clsSpanMax">(Max 1000 characters)</span><a href="<?php echo site_url()?>" class="buttonBlack"><span>Save</span></a> </p>-->
										    <p align="justify"> <input type="submit" value="Save" name="welcomemsg" class="clsSmall1"></p>		  
										
                                                </div>
                                              </form>
                                              <!-- end of project details -->
                                              <!--<p>Your Total Earnings Are:  US Dollars</p>-->
                                              <!--		   <div class="clsAffiliateQuestion">
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
		   </div>-->
                                            </div>
                                            <!--end of clsProgram-->
                                          </div>
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
                      <div class="alignRight"> </div>
                    </div>
                    <!--END OF POST PROJECT-->
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
