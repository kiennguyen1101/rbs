<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
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
							  <h2><?php echo 'Provider Signup'; ?></h2>
							  <h3><span class="clsNewBuyer"><?php echo $this->lang->line('singup_2'); ?></span></h3>
								  
                              <div class="clsMainInfo">
							  <form method="post" action="" enctype="multipart/form-data">
								  <?=form_token();?>
								  <p><span> <?php echo $this->lang->line('conform_email'); ?></span>
									<?php if(isset($confirmed_mail)) echo $confirmed_mail; ?>
								  </p>
							  
							     <p><span><?php echo $this->lang->line('pick_username'); ?><label style="color:red;">*</label> </span>
								  
									<input type="text" size="25" value="<?php echo set_value('username'); ?>" name="username"/>
								 </p>
								  <p>
								  <?php if(form_error('username')) { echo '<span>&nbsp;</span>';echo form_error('username'); echo '<br>'; }?></p>
								  <p><span><?php echo $this->lang->line('pick_password'); ?><label style="color:red;">*</label></span>
									<input type="password" size="25" name="pwd"/>
									 
								  </p>
								  <p><?php if(form_error('pwd')) { echo '<span>&nbsp;</span>';echo form_error('pwd'); echo '<br>'; }?></p>
								  <p><span><?php echo 'Confirm Password'; ?><label style="color:red;">*</label></span>
								     <input type="password" size="25" name="ConfirmPassword"/>
								  <p class="clsPTB0"><span>&nbsp;</span><small><?php  echo $this->lang->line('password_info'); ?></small> </p>
								   
								  <p><?php if(form_error('ConfirmPassword')) { echo '<span>&nbsp;</span>';echo form_error('ConfirmPassword'); echo '<br>'; }   ?></p>
								  <p><span><?php echo $this->lang->line('name/company'); ?></span>
								  <input type="text" size="25" value="<?php echo set_value('name'); ?>" name="name"/></p>
								  <p class="clsPTB0"><span>&nbsp;</span><small><?php echo $this->lang->line('disp_others'); ?></small></p>
								  <p><?php if(form_error('name')) { echo '<span>&nbsp;</span>';echo form_error('name'); echo '<br>'; } ?></p>
							  </div>
                              <div id="selOptional">
                                <h3><span class="clsOptContact"><strong><?php echo $this->lang->line('Optinal Contact Details');?></strong></span>
								
	
<?php echo $this->lang->line('(');?>	<span class="underLine"><a href = "javascript:void(0)" onclick ="document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'"><?php echo $this->lang->line('Privacy Policy');?></a></span><?php echo $this->lang->line(')');?>

		<div id="light" class="white_content"> <?php if(isset($page_content) and $page_content->num_rows()>0)
	
													{ foreach($page_content->result() as $page) { echo '<div class="ClsPrivacyDesc">'.$page->content.'</div>';}	} ?> <a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'"><img src="<?php echo image_url('blacklist.png'); ?>" /></a></div>
		<div id="fade" class="black_overlay"></h3></div> 
                                <ul>
								  <li><span> <?php echo $this->lang->line('MSN:');?></span>
									 <input type="text" name="contact_msn" value="<?php echo set_value('msn'); ?>" size="25"/>
                                  </li>
                                  <li><span > <?php echo $this->lang->line('Gtalk:');?></span>
                                    <input name="contact_gtalk" type="text" id="contact_gtalk" value="<?php echo set_value('gtalk'); ?>" size="25"/></p>
                                  </li>
                                  <li><span > <?php echo $this->lang->line('Yahoo:');?></span>
                                    <input name="contact_yahoo" type="text" id="contact_yahoo" value="<?php echo set_value('yahoo'); ?>" size="25"/>
                                  </li>
                                  <li><span> <?php echo $this->lang->line('Skype:');?></span>
                                    <input type="text" name="contact_skype" value="<?php echo set_value('skype'); ?>" size="25"/>
                                  </li>
								  </ul>
                              </div> 
							  <div id="selAreaExpertise">
							    <h3><span class="clsCategory"><?php echo $this->lang->line('area_of_expertise'); ?><label style="color:red;">*</label></span></h3>
									<p><small><?php echo $this->lang->line('(You can make multiple selections.)');?></small></p>
									<table>
									  <?php $i=0;
										if(isset($categories) and $categories->num_rows()>0)
										  {
											foreach($categories->result() as $category)
												{
									            if($i%3 ==0)
													echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
														?>
													<td>									  <li>
												<input type="checkbox" name="categories[]" value="<?php echo $category->id; ?>" <?php echo set_checkbox('categories[]', $category->id); ?>/>
												<?php echo $category->category_name; ?></td>
										
													<?php if($i%3 ==2)
														   echo '</tr>';
													   $i = $i + 1;   
													}//Foreach End
												}//If End
											?>
								     </table>
									<p><?php if(form_error('categories[]')) { echo '<span>&nbsp;</span>';echo form_error('categories[]'); echo '<br>'; }  ?></p>
									<p><span><?php echo $this->lang->line('your_average_hourly_rate'); ?><label style="color:red;">*</label></span> <?php echo $this->lang->line('$');?>
									 <input type="text" name="rate" maxlength="3" size="3" value="<?php echo set_value('rate'); ?>"/> <?php echo $this->lang->line('/hour');?> </p>
									<p> <?php if(form_error('rate')) { echo '<span>&nbsp;</span>';echo form_error('rate'); echo '<br>'; } ?></p>
									<p><span><?php echo $this->lang->line('your_profile_op'); ?></span></p>
									<p>
									   <textarea rows="10" name="profile" cols="60"><?php echo set_value('profile'); ?></textarea></p>
									
									<p><?php if(form_error('profile')) { echo '<span>&nbsp;</span>';echo form_error('profile'); echo '<br>'; }  ?></p>
									<p><span><?php echo $this->lang->line('your_pic_logo'); ?></span><input TYPE="file" NAME="logo" /></p>
									<p><span>&nbsp;</span><small><?php echo $this->lang->line('max_bytes'); ?></small></p>
									
									<p><?php if(form_error('logo')) { echo '<span>&nbsp;</span>';echo form_error('logo'); echo '<br>'; }  ?></p>
									<p><b><?php echo $this->lang->line('new_project_noti'); ?></b>
									  <select name="notify_project" size="1">
										 <option value="">None</option>
											<option value="Instantly" <?php echo set_select('notify_project', 'Instantly'); ?>>Instantly</option>
											<option value="Hourly" <?php echo set_select('notify_project', 'Hourly'); ?>>Hourly</option>
											<option value="Daily" <?php echo set_select('notify_project', 'Daily'); ?>>Daily</option>
									  </select></p>
									
									<p><?php if(form_error('notify_project')) { echo '<span>&nbsp;</span>';echo form_error('notify_project'); echo '<br>'; }  ?></p>
									<p><span><?php echo $this->lang->line('new_message_noti'); ?></span>
									  <select name="notify_message" size="1">
										<option value="">None</option>
										<option value="Instantly" <?php echo set_select('notify_message', 'Instantly'); ?>>Instantly</option>
										<option value="Hourly" <?php echo set_select('notify_message', 'Hourly'); ?>>Hourly</option>
										<option value="Daily" <?php echo set_select('notify_message', 'Daily'); ?>>Daily</option>
									  </select>
									</p>
									<p><?php if(form_error('notify_message')) { echo '<span>&nbsp;</span>';echo form_error('notify_message'); echo '<br>'; }  ?> </p> </div>
								  <p><span><?php echo $this->lang->line('country'); ?></span>
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
								  </select></p>
								 <p><?php if(form_error('country')) { echo '<span>&nbsp;</span>';echo form_error('country'); echo '<br>'; }  ?></p>
								  <p><span><?php echo $this->lang->line('state/province'); ?></span>
									<input type="text" name="state" value="" maxlength="50" size="30"/>
								  </p>
								  <p><span><?php echo $this->lang->line('city'); ?></span>
								  
									<input type="text" name="city" value="" maxlength="50" size="25"/>
								  </p>
								  <p class="underLine"><label style="color:red;">*</label>
									<input type="checkbox" name="signup_agree_terms" value="1" <?php echo set_checkbox('signup_agree_terms', '1'); ?>/>
																	
									<?php echo $this->lang->line('I have read and agree to the');?><a href = "javascript:void(0)" onclick = "document.getElementById('light1').style.display='block';document.getElementById('fade1').style.display='block'"><?php echo $this->lang->line('RBS Terms &amp; Conditions');?></a>

		<div id="light1" class="white_content"><?php if(isset($page_content1) and $page_content1->num_rows()>0)
													{ foreach($page_content1->result() as $page1) { echo '<div class="ClsPrivacyDesc">'.$page1->content.'</div>';}	} ?> <a href = "javascript:void(0)" onclick = "document.getElementById('light1').style.display='none';document.getElementById('fade1').style.display='none'"><img src="<?php echo image_url('blacklist.png'); ?>" /></a></div>
		<div id="fade1" class="black_overlay"></p></div> 	
									
								 <p> <?php if(form_error('signup_agree_terms')) { echo '<span>&nbsp;</span>';echo form_error('signup_agree_terms'); echo '<br>'; } ?></p>
								  <p><?php if(form_error('signup_agree_contact')) { echo '<span>&nbsp;</span>';echo form_error('signup_agree_contact'); echo '<br>'; }  ?></p>
								  <p>
									<input type="hidden" name="confirmKey" value="<?php echo $this->uri->segment(3); ?>" />
									<input type="submit" class="clsSmall" value="<?php echo $this->lang->line('sign_up'); ?>" name="sellerConfirm" />
								  </p>
								</form>
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
      </div>
      <!--END OF POST PROJECT-->
    </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>
