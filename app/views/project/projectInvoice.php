<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<div id="main">
      <!--POST PROJECT-->
     <?php $this->load->view('innerMenu');?>
      <div class="clsTabs clsInnerCommon">
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
                           	<div class="clsEditProfile clsSitelinks">
						     <h3><span class="clsOptContact"><?php echo $this->lang->line('Invoice Report');
							 $condition1=array('subscriptionuser.username'=>$loggedInUser->id);
								$certified1= $this->certificate_model->getCertificateUser($condition1);  
							  ?></span></h3>
                              <p class="clsSitelinks"><span><?php echo $this->lang->line('User name');?> :</span><a class="glow" href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'seller'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"> <?php echo $loggedInUser->user_name?></a>
							  <?php if(count($certified1->result())>0)
								{?>
								<img src="<?php echo image_url('certified.gif');?>" />
								<?php }?>
							  </p>
								<p><span><?php echo $this->lang->line('Account Balance:');?></span> $ <?php if(isset($userAvailableBalance)) echo $userAvailableBalance.'.00'; ?></p>
                             
							 	<?php
								$userInfo = $loggedInUser;
								
								$res = $invoiceProject->num_rows();
								if($res <= 0)
								  {
									 echo '<p><b style="color:red;">'.$this->lang->line('There is no payment is closed to view Invoice').'</b></p>';
								  }
								else {  
								?> 
						
							<form name="invoice_form" action="<?php echo site_url('project/invoice'); ?>" method="post">
                            <p><span><?php echo $this->lang->line('Project') ?></span>
                              <select  multiple="multiple" name="project_name[]" size="5"> <?php 
								//pr($postSimilar->result());
								foreach($invoiceProject->result() as $res)
								 { 
								   if($res->project_status == '2')      
									 { ?>
										<option><?php echo $res->id.'--'.$res->project_name;?></option> <?php 
									 }
								 }	  ?>
							  </select>
                            </p>
							<p><span>&nbsp;</span><?php echo $this->lang->line('You can select multiple projects by holding down the CTRL key') ?></p>
							<p><span><?php echo $this->lang->line('To') ?></span><textarea rows="10" cols="30" name="user_name"><?php echo $loggedInUser->user_name; ?></textarea></p>
							<p><span>&nbsp;</span><?php echo $this->lang->line('Your name and address') ?></p>
							<p><span><?php echo $this->lang->line('Invoice No') ?></span>   <input type="text" name="invoice_no" value=""/> <?php echo $this->lang->line('Optional') ?></p>
                            <p><span>&nbsp;</span>
                              <input type="submit" value="<?php echo $this->lang->line('Submit');?>" name="invoice" class="clsSmall"/>
                            </p>
							</form>
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
      <!--END OF POST PROJECT-->
    </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>
