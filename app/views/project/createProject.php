<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
  <!--MAIN-->
    <div id="main">
      <!--POST PROJECT-->
      <div class="clsInnerCommon">
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
                            <div class="clsPostProject clsSitelinks">
                              <h2><?php echo $this->lang->line('POST A PROJECT');?> </h2>
							
							  <?php echo form_error('total'); 
								//Show Flash error Message  for deposit minimum amount
								if($msg = $this->session->flashdata('flash_message'))
									{
									echo $msg;
									}
									
								  if(isset($preview))	
									{ 
									  $preview  =  $preview->row();
									  echo '<h3><p><b>'.$this->lang->line('Please click the link for view Preview Project').'</b>';
									  ?>
									 <b><a href="<?php echo site_url('project/previewProject/'.$preview->id); ?> " target="_blank" style="color:green;" ><?php echo $this->lang->line('Preview');?></a></b></p></h3	> <?php 
									}
								  
								  //Calculate the no of days open the porject
								  if(isset($enddate) and isset($created))	
									$no_of_days = count_days($created,$enddate) - 1;
								  ?>
								<?php   
                             $this->load->view('project/draftControll'); ?>
                              <p><?php echo $this->lang->line('Note');?></p>
                              <h3><span class="clsFileManager"><?php echo $this->lang->line('Account Login Details...');?></span></h3>



                             <p class="clsSitelinks"><?php echo $this->lang->line('You are currently logged in as');?> <a class="glow" href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'programmer'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></a><?php 

							    $condition1=array('subscriptionuser.username'=>$loggedInUser->id);
								$certified1= $this->certificate_model->getCertificateUser($condition1);
								if($certified1->num_rows()>0)
			                    {
							       foreach($certified1->result() as $certificate)
				                     {
									$user_id=$certificate->username;
									$id=$certificate->id;
									$condition=array('subscriptionuser.flag'=>1,'subscriptionuser.id'=>$id);
					                $userlists= $this->certificate_model->getCertificateUser($condition);
									// get the validity
									$validdate=$userlists->row();
									$end_date=$validdate->valid; 
									$created_date=$validdate->created;
									$valid_date=date('d/m/Y',$created_date);
								    $next=$created_date+($end_date * 24 * 60 * 60);
									$next_day= date('d/m/Y', $next) ."\n";
							        if(time()<=$next)
								    {?>
								<img src="<?php echo image_url('certified.gif');?>"  title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php  echo $this->lang->line('Certified Member')?>"/>
								<?php } 
								  }
								   }?>



							   (<a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout') ?></a>).</p>

                              <h3><span class="clsOptDetial"><?php echo $this->lang->line('Required Project Details...');?></span></h3>
							  <form method="post" action="<?php echo site_url('project/create'); ?>" name="form"  enctype="multipart/form-data">
                              <ul>
                                <li>
                                  <h5><?php echo $this->lang->line('Project Name');?></h5>
                                  <p><?php echo $this->lang->line('Do not put a domain/URL in your project name.');?></p>
                                  <p>
                                   <input name="projectName" value="<?php echo set_value('projectName'); ?>" maxlength="50" size="50" type="text"/>
	                               <?php echo form_error('projectName'); ?>
                                  </p>
                                </li>
                                <li>
                                  <h5><?php echo $this->lang->line('Describe the project in detail:');?></h5>
                                  <p><?php echo $this->lang->line('Do not post any contact info');?> ( <a href="#"><?php echo $this->lang->line('Why?');?></a> | <a href="#"><?php echo $this->lang->line('Review Terms');?></a> )</p>
									<p>
									  <textarea rows="10" name="description" cols="70"><?php echo set_value('description'); ?></textarea>
									   <?php echo form_error('description'); ?>
									</p>
                                  <p><?php echo $this->lang->line('Tip');?></p>
                                </li>
								<li><h5><b><?php echo $this->lang->line('I want my project to stay open for bidding for');?>
									<select name="openDays">
									
									<?php for($i=1;$i<=$project_period;$i++){?>
									<option value="<?php echo $i;?>" <?php if(isset($project_period)) { if($project_period == $i) echo 'selected="selected"'; }?> ><?php echo $i;?></option>
									<?php } ?>
									</select>&nbsp;<?php echo $this->lang->line('days');?>
                                  </b></h5></li>
                                <li>
                                  <h5><?php echo $this->lang->line('Job Type: (Make up to 5 selections.)');?></span></h5>
                                  
                                  <!--OPTION LIST-->
									<?php
											if(isset($groupsWithCategories) and count($groupsWithCategories)>0 )
											{
												foreach($groupsWithCategories as $groupsWithCategory)
												{
													if($groupsWithCategory['num_categories']>0)
													{				
									?>
									<p><u><?php echo $groupsWithCategory['group_name']?></u></p>
									<div id="selProgrammingOptions">
									  <table>
									  <?php $i=0;
										foreach($groupsWithCategory['categories']->result() as $category)
										{
										if($category->is_active==1)
										{
										 if($i%3 ==0)
                							echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
									  ?>
										
										  <td><label><input onClick="count(this.form)" name="categories[]" value="<?php echo $category->id; ?>" <?php echo set_checkbox('categories[]', $category->id); ?> type="checkbox"/><?php echo $category->category_name;  ?></label></td>
										<?php if($i%3 ==2)
											   echo '</tr>';
										   $i = $i + 1; ?>
									<?php
									}
										} //Foreach End - Traverse Category
									?>
									 </table>
									</div>
									<?php
													}//Check For Cateogry Availability
											} //For Each Travesal - For Group
										}//If End - Check For Group Existence
									?>
									<?php echo form_error('categories[]'); ?>
								  
								 
                                  
                                </li>
								
                              </ul>
                              <div class="clsOptionalDetails">
                                <h3><span class="clsInvoice"><?php echo $this->lang->line('Optional Project Details...');?></span></h3>
                                <ul>
                                  <li>
                                    <h5><?php echo $this->lang->line('Attachment:');?>
                                      <img src="<?php echo image_url('clip.gif'); ?>" width="15" height="13" />
									  <input name="attachment" type="file"/>
									 <small style="color:red;" ><?php echo $this->lang->line('allowed files'); ?></small>	
									  <?php 
									   $filesize = '0';
									   foreach($fileInfo->result() as $fileDate)
										 {
										   $filesize =$filesize + $fileDate->file_size;
										 } ?>	 
									  <?php echo form_error('attachment'); ?>
									  
									
                                    </h5>
                                    <p><small><?php echo $this->lang->line('info'); ?> <?php echo round($filesize/1024,2);?> <?php echo $this->lang->line('info1');?> <?php echo $maximum_size.' MB'; ?></small></p></li>
                                  <li>
                                    <h5><?php echo $this->lang->line('Project Budget:');?></h5>
                                    <p><?php echo $this->lang->line('Minimum:');?>&nbsp;<span> $
                                      <input name="budget_min" value="<?php echo set_value('budget_min'); ?>" size="5" type="text"/><?php echo form_error('budget_min'); ?>
                                      </span></p>
                                    <p><?php echo $this->lang->line('Maximum:');?><span> $
                                      <input name="budget_max" value="<?php echo set_value('budget_max'); ?>" size="5" type="text"/><?php echo form_error('budget_max'); ?>
                                      </span></p>
                                  </li>
								  
								  <li class="clSNoBack">
									<ul class="clsFloatedList clearfix">
									  <li class="clsPercent30">
										<input  name="is_feature" value="1" type="checkbox" <?php echo set_checkbox('is_feature', '1'); ?> onClick="check_featured(this.form)" />
										<b><?php echo $this->lang->line('Make Project');?> <a href="#" target="_blank"><?php echo $this->lang->line('Featured');?></a>&nbsp; 
										<img src="<?php echo image_url('featured2.gif');?>" width="14" height="14" title="Featured project" alt="<?php echo $this->lang->line('Featured Project'); ?>" /></b> </li>
									  <li class="clsPercent10"> $ <?php echo $feature_project; ?>
									
									  </li>
									  <li class="clsPercent50"><?php echo $this->lang->line('pro1');?> <a href="#" target="_blank"><?php echo $this->lang->line('Click here');?></a> <?php echo $this->lang->line('read');?></li>
									</ul>
									<ul class="clsFloatedList clearfix">
									  <li class="clsPercent30">
										<input name="is_urgent" value="1" type="checkbox" <?php echo set_checkbox('is_urgent', '1'); ?> onClick="check_urgent(this.form)" />
										<b><?php echo $this->lang->line('Make Project Urgent');?>&nbsp;<img src="<?php echo image_url('urgent2.gif');?>" width="14" height="14" title="Urgent project" alt="<?php echo $this->lang->line('Urgent Project'); ?>" /></b> </li>
									  <li class="clsPercent10"> $ <?php echo $urgent_project; ?></li>
									  <li class="clsPercent50"> <?php echo $this->lang->line('pro2');?> <a href="#"><?php echo $this->lang->line('urgent projects');?></a> <?php echo $this->lang->line('page. This option is free if you are a');?> <a href="#" target="_blank"><?php echo $this->lang->line('Certified Member');?></a>. <?php echo $this->lang->line('If your project is');?> <a href="#" target="_blank"><?php echo $this->lang->line('Featured');?></a> <?php echo $this->lang->line('the urgent fee is only $1.');?> </li>
									</ul>
									<ul class="clsFloatedList clearfix">
									  <li class="clsPercent30">
										<input  name="is_hide_bids" value="1" type="checkbox" <?php echo set_checkbox('is_hide_bids', '1'); ?> />
										<b><?php echo $this->lang->line('Hide Project Bids');?></b></li>
									  <li class="clsPercent10"> $ <?php echo $hide_project; ?></li>
									  <li class="clsPercent50"> <?php echo $this->lang->line('pro3');?> <a href="#" target="_blank"><?php echo $this->lang->line('Featured');?></a>, <?php echo $this->lang->line('Private or Urgent, or if you are a');?> <a href="#" target="_blank"><?php echo $this->lang->line('Certified Member');?></a>. 
							
										<?php echo $this->lang->line('This option is free if your project is');?> <a href="#" target="_blank"><?php echo $this->lang->line('Featured');?></a>, <?php echo $this->lang->line('Private or Urgent, or if you are a');?> <a href="$" target="_blank"><?php echo $this->lang->line('Certified Member');?></a>. </li>
							
									</ul>
									
									<ul class="clsFloatedList clsClearFix clsMod2">
								 	 <li class="clsPercent30">
									 <input type="checkbox" <?php echo set_checkbox('is_private', '1'); ?> name="is_private" id="is_private" value="1" onclick="check_private(this.form)" /><b> <?php echo $this->lang->line('Private Invitation'); ?>&nbsp;<img src="<?php echo image_url('private.png');?>" width="14" height="14" title="private project" alt="<?php echo $this->lang->line('Private Project'); ?>" /></b><br /> </li>
									  <li class="clsPercent10"> $ <?php echo $private_project; ?></li>
									  <li class="clsPercent50"> <p><?php echo $this->lang->line('Private Messages'); ?><br /><br /><?php echo $this->lang->line('list'); ?></p></li>
									  
									  
									   <li class="clsPercent50">
									 <?php echo $this->lang->line('private invitation list');  ?> ( <?php echo $this->lang->line('one username per line');  ?>):
									 <br />
									  <span>
									  <textarea name="private_list" id="private_listfill" rows="7" cols="30"  <?php if(set_value('is_private')!='1'){ ?> disabled="disabled" <?php } ?> ><?php echo set_value('private_list'); ?></textarea>
									  <?php echo form_error('private_list');  ?>
									  </span></li>
									  
									</ul>  
								  </li>
								   <!-- Note: Dont delete this. This will used in phase 2 for private projects to post by buyer -->							  
							    </ul>
							  </div>
							  <p>
								<input class="clsSmall" value="<?php echo $this->lang->line('Save Draft');?>" name="save_draft" type="submit" />
								<input class="clsSmall" value="<?php echo $this->lang->line('Preview');?>" name="preview_project" type="submit" onClick="javascript:return formSubmit()" />
								<input class="clsMid" value="<?php echo $this->lang->line('Submit Project');?>" name="createProject" type="submit" />	
								
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
      <!--END OF POST PROJECT-->
    
	 		 
    <!--END OF MAIN-->
<script type="text/javascript">

function formSubmit()
{
var form = document.createElement("form");
//alert(form.);
form.setAttribute("target", "_blank");
}

/* For laod favouriteusers list into the textarea box */
function loadProgrammers(num)
{
   document.getElementById('private_listfill').value += num;
   return TRUE;
}

//Set the properties of textarea box disabled */
function check_private(formname)
{
  document.getElementById('private_listfill').disabled = !document.getElementById('is_private').checked;
  document.getElementById('private_listfill').value="";
}
</script>
<?php $this->load->view('footer'); ?>