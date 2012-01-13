<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main">
      <!--POST PROJECT-->
      <?php $this->load->view('innerMenu'); ?>
      <div class="clsTabs clsInnerCommon clsInfoBox">
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
                              <h3><span class="clsHigh"><?php echo $this->lang->line('Deposit Funds');?></span></h3>
								<p><?php echo $this->lang->line('User name :');?><?php echo $this->lang->line('user_name'); ?><a href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'seller'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"> <?php echo $loggedInUser->user_name?></a></p>
								<p><?php echo $this->lang->line('Account Balance:');?> $ <?php if(isset($userAvailableBalance)) echo $userAvailableBalance.'.00'; ?></p>
								<p><font color="#FF0000">The amount includes the paypal commission <?php echo $commission;?>%</font></p>
								  
									<form name="formPaypal" action="<?php echo $paymentGateways['paypal']['url']; ?>"  method="post">
									  <input type="hidden" name="cmd" value="_xclick">
									  <input type="hidden" name="business" value="<?php echo $paymentGateways['paypal']['mail_id']; ?>">
									  <input type="hidden" name="item_number" value="1">
									  <input type="hidden" name="item_name" value="<?php echo $this->config->item('site_name').'Account Deposit'; ?>">
									  <p><label><?php echo $this->lang->line('please confirm to this amount'); ?></label>
									  <input type="text" name ="amount" value="<?php echo $total_with_commission; ?>" readonly="yes" >
									  <input type="hidden" name="on0" value="0">
									   <input type="hidden" name ="custom" value="<?php echo $transactionId."#".$loggedInUser->user_name."#".$this->loggedInUser->email; ?>">
									  <input type="hidden" name="currency_code" value="USD">
									  <input type="hidden" name="notify_url" value="<?php echo  site_url('payment/paypalIpn'); ?>">
									  <input type="hidden" name="return" value="<?php echo  site_url('payment/paymentSuccess'); ?>">
									  <input type="hidden" name="cancel_return" value="<?php echo  site_url('deposit/cancel'); ?>">
									  <input type="submit" name"submit" class="clsSmall" value="<?php echo $this->lang->line('submit');?>"/></p>
									  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									 </form>
									 <form name="confirm_amount" action="<?php echo site_url('deposit'); ?>" method="post">
										<p><input type="hidden" name ="amount" value="<?php echo $total; ?>" >
										<input type="submit" name="back" value="<?php echo $this->lang->line('Back');?>" class="clsSmall" /></p>
									</form>   
									<br />
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
<?php $this->load->view('footer'); ?>