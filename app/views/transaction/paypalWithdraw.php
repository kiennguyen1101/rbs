<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div  id="main"  class="">
<!--DEPOSITE PAGE-->
<!--MAIN-->
<div  id="selDeposit">
<?php $this->load->view('innerMenu');?>
     <div class="clsAccountManage">
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
                            <div class="clsInnerCommon">
							<?php echo '<h2>'.$this->lang->line('Withdraw Funds').'</h2>'; ?>
							<p><?php $this->lang->line('Account Balance:');?>$ <?php  if(isset($userAvailableBalance)) echo $userAvailableBalance.'.00';
							 ?></p>
								<form name="withDraw" action="<?php echo  site_url('withdraw/withDrawAmount'); ?>" method="post">
								<p><label><?php echo $this->lang->line('Please enter your paypal address'); ?></label>
								<input type="hidden" name ="withdrawMoney" value="<?php echo $withdraw_minimum; ?>"> 
								<input type="hidden" name ="creator_id" value="<?php echo $creator_id; ?>">
								<input type="hidden" name ="total" value="<?php echo $total; ?>"> 
								<input type="hidden" name ="paymentMethod" value="<?php echo $paymentMethod; ?>"> 
								<input type="hidden" name ="userAvailableBalance" value="<?php echo $userAvailableBalance; ?>"> 
								<input type="text" name ="email" value="<?php echo set_value('email'); ?>"><?php echo form_error('email'); ?></p>
								<p><input type="submit" name"submit" class="clsSmall" value="<?php echo $this->lang->line('submit');?>" /></p>
								</form>
								<br />
				
							<!--END OF MAIN-->
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
      <!--END OF POST PROJECT-->
     </div>
    <!--END OF MAIN-->
</div>
</div>
<script type="text/javascript">
//document.formPaypal.submit();
</script>
<?php $this->load->view('footer'); ?>
