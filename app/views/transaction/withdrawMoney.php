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
                            <div class="clsEditProfile clsSitelinks">   
							   <h3><span class="clsEscrow"><?php echo $this->lang->line('withdraw_funds'); ?></span></h3>
							<?php $condition1=array('subscriptionuser.username'=>$loggedInUser->id);
								$certified1= $this->certificate_model->getCertificateUser($condition1);?>					   
							<p><span><?php echo $this->lang->line('user_name'); ?></span><a class="glow" href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'seller'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"> <?php echo $loggedInUser->user_name?></a>
							<?php if(count($certified1->result())>0)
								{?>
								<img src="<?php echo image_url('certified.gif');?>" />
								<?php }?>
							</p>
<p><span><?php echo $this->lang->line('Account Balance:');?></span> $<?php if(isset($userAvailableBalance)) echo $userAvailableBalance.'.00'; ?></p>

							<form name="withdrawAmount" action="<?php echo site_url('withdraw'); ?>"  method="post">
								<p><span><?php echo $this->lang->line('Withdraw Amount:');?></span> $
								  <input name="total" size="10" value="<?php echo set_value('total'); ?>"  type="text"/>
								  <?php echo form_error('total'); ?>
								  <?php
								//Show Flash error Message  for withdraw minimum amount
								if($msg = $this->session->flashdata('flash_message'))
									{
									echo $msg;
									}
								?>
								</p>
								<p><?php echo $this->lang->line('note'); ?></p>

							<h3><span class="clsPayments"><?php echo $this->lang->line('Payment methods');?></span></h3>
							 <?php echo form_error('paymentMethod'); ?>
							<table cellspacing="1" cellpadding="2" width="96%">
                                <tbody><tr>
                                  <td width="100" class="dt"><?php echo $this->lang->line('Payment Method');?></td>
                                  <td width="100" class="dt"><?php echo $this->lang->line('Cost');?></td>
                                  <td width="100" class="dt"><?php echo $this->lang->line('Approval');?></td>
								  <td width="250" class="dt"><?php echo $this->lang->line('Description');?></td>
                                </tr>
                                <tr>
                                  <td class="dt1 dt0"><input value="paypal" name="paymentMethod" type="radio"  <?php if(isset($amount)) echo "checked"; ?>/>
                        <label><?php echo $this->lang->line('Paypal') ?></label></td>
                                  <td class="dt1"><?php echo $this->lang->line('No Cost');?></td>
                                  <td class="dt1"><?php echo $this->lang->line('Instant*');?></td>
                                  <td class="dt1"><?php echo $this->lang->line('wire');?></td>
                                </tr>
                                
                              </tbody></table>
							  <p>
								<input  class="clsSmall" name="withdrawMoney"  value="<?php echo $this->lang->line('Withdraw'); ?>" type="submit"/>
							  </p>
							</form>
							</div>
							<br />
							<h3><span class="clsDepositTrans"><?php echo $this->lang->line('My Withdraw Transactions');?></span></h3>
							<table cellspacing="1" cellpadding="2" width="96%">
                                <tbody><tr>
                                  <td width="30" class="dt"><?php echo $this->lang->line('SI.No');?></td>
                                  <td width="150" class="dt"><?php echo $this->lang->line('To');?></td>
                                  <td width="50" class="dt"><?php echo $this->lang->line('Amount');?></td>
								  <td width="50" class="dt"><?php echo $this->lang->line('Date');?></td>
								  <td width="250" class="dt"><?php echo $this->lang->line('Status');?></td>
                                </tr>
								
								 <?php $i=1; $k=0;
						        foreach($transactions1->result() as $res)
								{ $i=$i+1; 
								  if($i%2 == 0)
								    {
								    $class ="dt1 dt0";
									$class1 = "dt1";
									}
								  else
								    {
								    $class ="dt2 dt0";	
									$class1 = "dt2";
									}
									  $k=$k+1;
										?>
									  <tr>
									  <td class="<?php echo $class; ?>"><?php echo $k; ?></td>
									  <td class="<?php echo $class1; ?>"><?php foreach($usersList->result() as $user) { if($user->id == $res->creator_id) { ?>
									   <a href="<?php if($user->role_id == '1') echo site_url('buyer/viewProfile/'.$user->id); if($user->role_id=='2') echo site_url('seller/viewProfile/'.$user->id);?>"> <?php  echo $user->user_name;
									      $condition=array('subscriptionuser.username'=>$user->id);
								$certified1= $this->certificate_model->getCertificateUser($condition);?>
								 <?php if(count($certified1->result())>0)
								{?>
								<img src="<?php echo image_url('certified.gif');?>" />
								<?php }
									    break; } }  ?></a></td>								  
									  <td class="<?php echo $class1; ?>"> $ <?php echo $res->amount; ?></td>
									  <td class="<?php echo $class1; ?>"><?php echo get_datetime($res->transaction_time); ?></td>
									  <td class="<?php echo $class1; ?>"><?php echo $res->status; ?> </td> 
									  <?php 
								} 
								if($k=='0')
								   {
									echo '<td colspan="5">';
									echo 'There is no Transaction';
									echo '</td>';
								   }	
								?>	 </tr> 
                              </tbody></table>
							   <!--PAGING-->
								<?php if(isset($pagination)) echo $pagination;?>
							 <!--END OF PAGING-->

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
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>