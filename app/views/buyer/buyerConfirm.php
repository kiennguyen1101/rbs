<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>


<!--MAIN-->
<div id="main">
      <!--POST PROJECT-->
      <div class="clsEditProfile">
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
							  <h2><?php echo $this->lang->line('NEW BUYERS SIGN-UP');?></h2>
							  <!--NEW BUYERS SIGN-UP-->
							  <div id="selSignUp">
								<form method="post" action=""  enctype="multipart/form-data">
								  <?phpform_token();?>
								  <h3><span class="clsNewBuyer"><?php echo $this->lang->line('New buyer Signup (Step 2)');?></span></h3>
								  <p> <?php echo $this->lang->line('Confirmed E-mail1:');?>
									<?php if(isset($confirmed_mail)) echo $confirmed_mail; ?>
								  </p>
								  <p><span><?php echo $this->lang->line('Pick a Username:');?><label style="color:red;">*</label></span>
									<input type="text" size="25" value="<?php echo set_value('username'); ?>" name="username"/></p>
								 <p><?php if(form_error('username')) { echo '<span>&nbsp;</span>';echo form_error('username'); echo '<br>'; }?></p>	
									
							     </p>
								  <p><span><?php echo $this->lang->line('Enter your password:');?><label style="color:red;">*</label></span>
									<input type="password" size="25" name="password"/>
									
								  </p>
								  						  
								  <p><?php if(form_error('password')) { echo '<span>&nbsp;</span>';echo form_error('password'); echo '<br>'; }?></p>
								  	
								  <p><span><?php echo $this->lang->line('Confirm Password:');?><label style="color:red;">*</label></span>
									<input type="password" size="25" name="ConfirmPassword"/>								  
								  <p><span>&nbsp;</span><small><?php echo $this->lang->line('(Enter the above password again to confirm it.)');?></small> </p>

									
								  </p>
								  <p><?php if(form_error('ConfirmPassword')) { echo '<span>&nbsp;</span>';echo form_error('ConfirmPassword'); echo '<br>'; }?></p>	
								  <p><span><?php echo $this->lang->line('Name/Company:');?></span>
									<input type="text" size="25" value="<?php echo set_value('name'); ?>" name="name"/></p>
								  <p><span>&nbsp;</span><small><?php echo $this->lang->line('(This name will be displayed to others.)');?></small> </p>
								   	
								  <p><?php if(form_error('name')) { echo '<span>&nbsp;</span>';echo form_error('name'); echo '<br>'; }?></p>
								  </p>
								  
								
								  <h3><span class="clsOptContact"><?php echo $this->lang->line('Optinal Contact Details');?></span><?php echo $this->lang->line('(');?>
								  
								  <!-- Puhal Changes For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2) -->
								  <span class="underLine">
	<a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'"><?php echo $this->lang->line('Privacy Policy');?></a></span><?php echo $this->lang->line(')');?></h3>
	<div id="light" class="white_content"> 
		<span class="clsClose"><a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'" >
		<img src="<?php echo image_url('blacklist.png'); ?>" />
		</a></span>
		
		<?php if(isset($page_content) and $page_content->num_rows()>0)
				
													{foreach($page_content->result() as $page) { echo '<div class="ClsPrivacyDesc">'.$page->content.'</div>';}	} ?> </div>
		 <div id="fade" class="black_overlay"></div> 
		
										  <div id="selOptional">
									<ul>
									  
									  <li>
										<span> <?php echo $this->lang->line('MSN:');?></span> 
										  <input type="text" name="contact_msn" value="<?php echo set_value('msn'); ?>" size="25"/>
										  
									  </li>
									
									  <li>
										<span> <?php echo $this->lang->line('Gtalk:');?></span> 
										  <input name="contact_gtalk" type="text" id="contact_gtalk" value="<?php echo set_value('gtalk'); ?>" size="25"/>
										  
									  </li>
									  <li>
										 <span><?php echo $this->lang->line('Yahoo:');?></span>  <input name="contact_yahoo" type="text" id="contact_yahoo" value="<?php echo set_value('yahoo'); ?>" size="25"/>
										 
									  </li>
									
									  <li><span>
										<?php echo $this->lang->line('Skype:');?> </span><input type="text" name="contact_skype" value="<?php echo set_value('skype'); ?>" size="25"/>
										  
									  </li>
									 
									</ul>
								  </div>
								  <div id="selAreaExpertise">
									<p><span><?php echo $this->lang->line('Your picture or company logo (optional):');?></span>
									  <input type="file" name="logo"/>
									</p>
									<p><span>&nbsp;</span><small style="color:red;"><?php echo $this->lang->line('(Maximum 102400 bytes)');?>  <?php echo $this->lang->line('allowed files'); ?></small> 
									<p><?php if(form_error('logo')) { echo '<span>&nbsp;</span>';echo form_error('logo'); echo '<br>'; }?></p>	
									
									<p><span><?php echo $this->lang->line('New Bid E-Mail Notifications:');?> </span>
									  <select name="notify_bid" size="1">
										 <option value="">None</option>
											<option value="Instantly" <?php echo set_select('notify_bid', 'Instantly'); ?>>Instantly</option>
											<option value="Hourly" <?php echo set_select('notify_bid', 'Hourly'); ?>>Hourly</option>
											<option value="Daily" <?php echo set_select('notify_bid', 'Daily'); ?>>Daily</option>
									  </select>
									  
									</p>
									<p><?php if(form_error('notify_project')) { echo '<span>&nbsp;</span>';echo form_error('notify_project'); echo '<br>'; }?></p>	
									<p><span><?php echo $this->lang->line('New Message E-Mail Notifications:');?></span>
									  <select name="notify_message" size="1">
										<option value="">None</option>
											<option value="Instantly" <?php echo set_select('notify_message', 'Instantly'); ?>>Instantly</option>
											<option value="Hourly" <?php echo set_select('notify_message', 'Hourly'); ?>>Hourly</option>
											<option value="Daily" <?php echo set_select('notify_message', 'Daily'); ?>>Daily</option>
									  </select>
									 
									</p>
									<p><?php if(form_error('notify_message')) { echo '<span>&nbsp;</span>';echo form_error('notify_message'); echo '<br>'; }?></p>	
								  </div>
								  <p> <span><?php echo $this->lang->line('Country:');?></span>
									<select name="country" size="1">
									  <?php
											if(isset($countries) and $countries->num_rows()>0)
											{
												foreach($countries->result() as $country)
												{
										  ?>
									  <option value="<?php echo $country->country_symbol; ?>" <?php echo set_select('country', $country->country_symbol); ?>><?php echo $country->country_name; ?></option>
									  <?php
												}//Foreach End
											}//If End
										?>
									</select>
									
								  </p>
								  <p><?php if(form_error('country')) { echo '<span>&nbsp;</span>';echo form_error('country'); echo '<br>'; }?></p>	
								  <p> <span><?php echo $this->lang->line('State/Province (optional):');?></span>
									<input type="text" name="state" value="<?php echo set_value('state'); ?>" maxlength="50" size="30"/>
								  </p>
								  <p> <span><?php echo $this->lang->line('City (optional):');?></span>
									<input type="text" name="city" value="<?php echo set_value('city'); ?>" maxlength="50" size="25"/>
								  </p>
							
								  <p class="underLine"><label style="color:red;">*</label>
								  
									<input type="checkbox" name="signup_agree_terms" value="1" <?php echo set_checkbox('signup_agree_terms', '1'); ?>/>
						<!-- Puhal Changes For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2) -->
						
					<?php echo $this->lang->line('I have read and agree to the');?> 	<a href = "javascript:void(0)" onclick = "document.getElementById('light1').style.display='block';document.getElementById('fade1').style.display='block'"><?php echo $this->lang->line('RBS Terms &amp; Conditions');?></a>

		<div id="light1" class="white_content"><?php if(isset($page_content1) and $page_content1->num_rows()>0)
													{ foreach($page_content1->result() as $page1) { echo  '<div class="ClsPrivacyDesc">'.$page1->content.'</div>';}	} ?> <a href = "javascript:void(0)" onclick = "document.getElementById('light1').style.display='none';document.getElementById('fade1').style.display='none'"><img src="<?php echo image_url('blacklist.png'); ?>" /></a></div>
		<div id="fade1" class="black_overlay"></h3></div> 												
								  </p>
								  <p><?php if(form_error('signup_agree_terms')) { echo form_error('signup_agree_terms'); echo '<br>'; }?></p>		 
								  <p><label style="color:red;">*</label>
									<input type="checkbox" name="signup_agree_contact" value="1" <?php echo set_checkbox('signup_agree_contact', '1'); ?>/ >
									<?php echo $this->lang->line('I will NOT post contact information on my projects.');?> 
								  </p>
									
								  <p><?php if(form_error('signup_agree_contact')) { echo form_error('signup_agree_contact'); echo '<br>'; }?></p>		 	
								  <p>
									<input type="hidden" name="confirmKey" value="<?php echo $this->uri->segment(3); ?>" />
									<input type="submit" class="clsSmall" value="<?php echo $this->lang->line('Signup');?>" name="buyerConfirm" />
								  </p>
								</form>
								<?php //print_r($_POST);
								//print_r($_FILES);
								?>
							  </div>
							  <!--SIGN-UP-->
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

<!--  Puhal Changes For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2) -->
<script type="text/javascript">
  function termspopups()
  {
     window.open('<?php echo site_url('info/terms'); ?>',"mywindow","menubar=1,resizable=1,width=650,height=450");
  }
   function privacypopups()
  {
     window.open('<?php echo site_url('info/privacy'); ?>',"mywindow","menubar=1,resizable=1,width=650,height=450");
  }
</script>


		
