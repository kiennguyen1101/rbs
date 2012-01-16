<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<?php
// get users details from user table
$userData = $userInfo->row();
// get users categories details from user categories table
$userCategoryInfo = $userCategories->row();
?>

<div id="main">
  <!--NEW BUYERS SIGN-UP-->
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
                            <div class="clsInnerCommon clsFormSpan">

								<form method="post" action="<?php echo site_url('seller/editProfile') ;  ?>" enctype="multipart/form-data">
								  <?phpform_token();?>
								  <h2><?php echo $this->lang->line('edit_account'); ?></h2>
								  <p><span><?php echo $this->lang->line('name'); ?></span> <?php echo $userData->user_name; ?></p>
								  <?php echo form_error('username'); ?>
								  <p><span><strong><?php echo $this->lang->line('pick_password'); ?></strong></span>
									<input type="password" size="25" name="pwd" value=""/></p>
								  <?php echo form_error('pwd'); ?>
								  <p><span><?php echo $this->lang->line('name/company'); ?></span>
								  <input type="text" size="25" value="<?php echo $userData->name; ?>" name="name"/></p>
								  <p class="clsPTB0"><span>&nbsp;</span><small><?php echo $this->lang->line('disp_others'); ?></small></p>								  
								  <?php if(form_error('name')) echo '<p><span>&nbsp;</span>'.form_error('name').'<br> </p>'; ?>
									<p><span><?php echo 'Email Address'; ?></span>
								   <input type="text" size="25" value="<?php echo $userData->email; ?>" name="email" />
								  </p>
								   
								   <p class="clsPTB0"><span>&nbsp;</span><small><?php echo $this->lang->line('disp_others'); ?></small></p>
									<br />
								  <?php $userContact = $userContactInfo->row();
							 ?>  <h3><span class="clsOptContact"><?php echo $this->lang->line('op_contact_details'); ?></span><?php echo $this->lang->line('(');?><a href="<?php echo site_url('page/privacy_policy');?>"><?php echo $this->lang->line('privacy_policy'); ?></a><?php echo $this->lang->line(')');?></h3>
									<p><span> <?php echo $this->lang->line('MSN:'); ?></span> 
										  <input type="text" name="contact_msn" value="<?php if(isset($userContact->msn))

												{
												   echo $userContact->msn ;
												 }else
													{
													  echo set_value('contact_msn');
													}  
													?>" size="25"/>
                                  </p>
                                  <p> <span > <?php echo $this->lang->line('Gtalk:'); ?></span>
                                    <input name="contact_gtalk" type="text" id="contact_gtalk" value="<?php if(isset($userContact->gtalk))
												{
												   echo $userContact->gtalk;
												 }else
													{
													  echo set_value('contact_msn');
													}  
													?>" size="25"/>
                                  </p>
                                  <p> <span > <?php echo $this->lang->line('Yahoo:'); ?></span>
                                    <input name="contact_yahoo" type="text" id="contact_yahoo" value="<?php if(isset($userContact->yahoo))
												{
												   echo $userContact->yahoo;
												 }else
													{
													  echo set_value('contact_yahoo');
													}  
													?>" size="25"/>
                                  </p>
                                  <p> <span> <?php echo $this->lang->line('Skype:'); ?></span>
                                    <input type="text" name="contact_skype" value="<?php if(isset($userContact->skype))
												{
												   echo $userContact->skype;
												 }else
													{
													  echo set_value('contact_skype'); 
													}  
													?>" size="25"/>

                                  </p><br />
                        
								 
								<h3><span class="clsCategory"><?php echo $this->lang->line('area_of_expertise'); ?></span></h3>

									<p><small><?php echo $this->lang->line('(');?><?php echo $this->lang->line('You can make multiple selections'); ?><?php echo $this->lang->line('.)');?></small></p>
								<!-- User Profile category -->
								<table>
									  <?php $i=0; $j =0;
											   if(count($userCategoryInfo) > 0)
												{			   
												$ids= explode(',',$userCategoryInfo->user_categories);
												if(isset($categories) and $categories->num_rows()>0)
												{
													foreach($categories->result() as $category)
													{
													
													if($i%3 ==0)
													  {
													  echo '<tr><td style="padding:0 20px 0 50px;"><td><td>';
													  }
													else
													  {
													  echo '<td style="padding:0 20px 0 20px;"><td><td>';
													  }  
													   
														?>
													<input type="checkbox" name="categories[]" value="<?php echo $category->id; ?>" <?php if(in_array($category->id,$ids)) echo 'checked="checked"'; ?> />
													<?php echo $category->category_name; if($i%3 =='2') echo '</td></tr>'; else echo '</td>';  $i++;?>
												  <?php
													}//Foreach End
												}//If End
											}else
											 {
											   if(isset($categories) and $categories->num_rows()>0)
												{
													foreach($categories->result() as $category)
													{
											 if($j%3 ==0)
													  {
													  echo '<tr><td style="padding:0 20px 0 50px;"><td><td>';
													  }
													else
													  {
													  echo '<td style="padding:0 20px 0 20px;"><td><td>';
													  }  
										?>
                                    <input type="checkbox" name="categories[]" value="<?php echo $category->id; ?>"/>
                                    <?php echo $category->category_name; if($j%3 =='2') echo '</td></tr>'; else echo '</td>';  $j++; ?> 
                                  <?php
													}//Foreach End
												}//If End   
											
											 }
											?>
                                </table>

                                <?php if(form_error('categories[]')) echo '<p><span>&nbsp;</span>'.form_error('categories[]').'<br></p>'; ?><br />
								<h3><span class="clsOptContact"><?php echo $this->lang->line('op_contact_details'); ?></span></h3>
								<p><span><?php echo $this->lang->line('your_average_hourly_rate'); ?></span>
									 $ <input type="text" name="rate" maxlength="3" size="3" value="<?php echo $userData->rate; ?>"/>
									  <?php echo $this->lang->line('/');?><?php echo $this->lang->line('hour'); ?></p>
									  <?php if(form_error('rate')) echo '<p><span>&nbsp;</span>'. form_error('rate').'<br></p>'; ?>
									
								
							    <p><span><?php echo $this->lang->line('your_profile_op'); ?></span>
									
									  <textarea rows="10" name="profile" cols="40"><?php echo $userData->profile_desc; ?></textarea>
									
                                <?php echo form_error('profile'); ?></p>
                                <p><span><?php echo $this->lang->line('your_pic_logo'); ?></span>
								 <input TYPE="file" NAME="logo" />&nbsp;&nbsp;<small><?php echo $this->lang->line('max_bytes'); ?></small></p>
                                <p><?php if(form_error('logo')) echo '<p><span>&nbsp;</span>'.form_error('logo').'<br></p>'; ?></p>
								
								<p><span><?php echo $this->lang->line('Current Photo'); ?> : </span>
								 <?php if($userData->logo){?>
                                  <img src="<?php echo uimage_url(get_thumb($userData->logo));?>"/><a href="<?php echo site_url('seller/removePhoto/'.$userData->id."/2");?>" onclick="return confirm('Do you want to delete this image?');"><img src="<?php echo image_url('delete.png');?>" border="0" alt="delete" title="delete"/></a>
                                  <?php } 
								  else
								  echo '<img src="'.image_url('noImage.jpg').'" width="49" height="48" />';
								  ?></p>
                                <p><span><?php echo $this->lang->line('new_project_noti'); ?></span>
								
                                  <select name="notify_project" size="1">
                                    <option value=""><?php echo $this->lang->line('None'); ?></option>
                                    <option value="<?php echo $this->lang->line('Instantly'); ?>" <?php if($userData->project_notify=='Instantly') echo 'selected="selected"'; ?>><?php echo $this->lang->line('Instantly'); ?></option>
                                    <option value="<?php echo $this->lang->line('Hourly'); ?>" <?php if($userData->project_notify=='Hourly') echo 'selected="selected"'; ?>><?php echo $this->lang->line('Hourly'); ?></option>
                                    <option value="<?php echo $this->lang->line('Daily'); ?>"  <?php if($userData->project_notify=='Daily') echo 'selected="selected"'; ?>><?php echo $this->lang->line('Daily'); ?></option>
                                  </select></p>
                                
                                <?php if(form_error('notify_project')) echo '<p><span>&nbsp;</span>'.form_error('notify_project').'<br></p>'; ?>
								
                                <p><span><?php echo $this->lang->line('new_message_noti'); ?></span>
														
                                  <select name="notify_message" size="1">
                                    <option value=""><?php echo $this->lang->line('None'); ?></option>
                                    <option value="<?php echo $this->lang->line('Instantly'); ?>" <?php if($userData->message_notify=='Instantly') echo 'selected="selected"'; ?>><?php echo $this->lang->line('Instantly'); ?></option>
                                    <option value="<?php echo $this->lang->line('Hourly'); ?>" <?php if($userData->message_notify=='Hourly') echo 'selected="selected"'; ?>><?php echo $this->lang->line('Hourly'); ?></option>
                                    <option value="<?php echo $this->lang->line('Daily'); ?>"  <?php if($userData->message_notify=='Daily') echo 'selected="selected"'; ?>><?php echo $this->lang->line('Daily'); ?></option>
                                  </select> </p>
					
                                  <p>                                  
                                  <button id="detect_area" name="detect_area" > <?php echo $this->lang->line('detect_area'); ?></button>
                              </p>
                                  
                                <?php if(form_error('notify_message')) echo '<p><span>&nbsp;</span>'.form_error('notify_message').'<br></p>'; ?>
                              <p><span><?php echo $this->lang->line('country'); ?></span>
							  
                                <select name="country" size="1">
                                  <option value="">None</option>
                                  <?php
											if(isset($countries) and $countries->num_rows()>0)
											{
												foreach($countries->result() as $country)
												{
										  ?>
                                  <option value="<?php echo $country->country_symbol; ?>"  <?php if($userData->country_symbol==$country->country_symbol) echo 'selected="selected"'; ?>><?php echo $country->country_name; ?></option>
                                  <?php
												}//Foreach End
											}//If End
										?>
                                </select>
								
								<?php if(form_error('country')) echo '<p><span>&nbsp;</span>'.form_error('country').'<br></p>'; ?></p>
                              <p><span><?php echo $this->lang->line('state/province'); ?></span>
							  
                                <input type="text" name="state" value="<?php echo $userData->state; ?>" maxlength="50" size="30"/></td>
								</p>
                              <p><span><?php echo $this->lang->line('city'); ?></span>
                                <input type="text" name="city" value="<?php echo $userData->city; ?>" maxlength="50" size="30"/>
							  </p>
                              
							  <!--<p><span>&nbsp;</span>	
							  	
                                <input type="checkbox" name="signup_agree_contact" value="1" <?php echo set_checkbox('signup_agree_contact', '1'); ?>/ >
                                <?php echo $this->lang->line('Display my own status'); ?></p>-->
								
                              <?php echo form_error('signup_agree_contact'); ?></p>
							  <p><span>&nbsp;</span><input type="hidden" name="confirmKey" value="<?php echo $userData->activation_key; ?>" />
                                <input type="submit" class="clsMini" value="<?php echo $this->lang->line('Edit'); ?>" name="updateSellerConfirm" />
								</p>
							 
                            </form>
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
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>
