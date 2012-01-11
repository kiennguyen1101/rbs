<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->

<script language="javascript" type="text/javascript">
function showfields(id)
{ 
    if (id==1)
    {
	
   document.getElementById('advance').style.display='none';
   document.getElementById('upload').style.display='block';
   document.getElementById('showjoblist').style.display='none';
   document.getElementById('showprojectlist').style.display='block';
	
	}
    else
    {
	document.getElementById('advance').style.display='block';
	document.getElementById('upload').style.display='none';

	document.getElementById('showjoblist').style.display='block';
    document.getElementById('showprojectlist').style.display='none';
    
    }
}
</script>
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
                              <h2><?php echo $this->lang->line('POST JOB LIST PROJECT');?> </h2>
								
								<?php 
								
							//Show Flash error Message  for deposit minimum amount
							if($msg = $this->session->flashdata('flash_message'))
								{
								echo $msg;
								}
							  ?>
							  <?php
									//echo $project_name;
								?>
							   <?php $this->load->view('joblist/draftControl'); ?>
								<p> <?php echo $this->lang->line('note1');?> <a href="#"><?php echo $this->lang->line('Click here');?></a> <?php echo $this->lang->line('to post a job listing instead.');?> </p>
								<h3><span class="clsFileManager"><?php echo $this->lang->line('Account Login Details...');?></span></h3>
								<p class="clsSitelinks"><?php echo $this->lang->line('You are currently logged in as');?> <a class="glow" href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'programmer'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></a> (<a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout') ?></a>).</p>
					<form method="post" action="<?php echo site_url('joblist/create'); ?>" name="form"  enctype="multipart/form-data">
								
								<?php 
								 if(isset($postSimilar))
								 {
								  foreach($postSimilar->result() as $postSimilarall)
								   {?>
								   <h3><div class="clsFileManager"><div id="showjoblist" style="display:block"><?php  echo $this->lang->line('Required Job Listing Details');?></div></div></h3>
							  <p class="clsSitelinks">
							  <input type="radio" name="postProject" value="Joblist" onclick="showfields(2)" checked="checked"/><?php echo $this->lang->line('Job Listing');?> (Post a help wanted ad to find a long-term employee or partner. $<?php $paymentSettings = $this->settings_model->getSiteSettings(); 
							echo $joblistamount  = $paymentSettings['JOBLISTING_PROJECT_AMOUNT']; ?> for <?php $paymentSettings = $this->settings_model->getSiteSettings(); 
							echo $joblistamount  = $paymentSettings['JOBLIST_VALIDITY_LIMIT']; ?>days of exposure.)<br/>

                              <h3><span class="clsOptDetial"><?php echo $this->lang->line('Required JobList Details...');?></span></h3>
					                              <ul>
                                <li>
                                  <h5><?php echo $this->lang->line('Project Name');?></h5>
                                  <p><?php echo $this->lang->line('Do not put a domain/URL in your project name.');?></p>
                                  <p>
                                   <input name="projectName" value="<?php echo $postSimilarall->project_name; ?>" maxlength="50" size="50" type="text"/>
                                  </p>
                                </li>
                                <li>
                                  <h5><?php echo $this->lang->line('Describe the project in detail:');?></h5>
									<p>
									  <textarea rows="10" name="description" cols="70"><?php  echo strip_tags($postSimilarall->description); ?></textarea>
									</p>
                                  <p><?php echo $this->lang->line('Tip');?></p>
                                </li>
								<!--<li><h5><b><?php echo $this->lang->line('I want my project to stay open for bidding for');?>
									<select name="openDays">
									<?php for($i=1;$i<=$project_period;$i++){?>
									<option value="<?php echo $i;?>" <?php if(isset($project_period)) { if($project_period == $i) echo 'selected="selected"'; }?> ><?php echo $i;?></option>
									<?php } ?>
									</select>&nbsp;<?php echo $this->lang->line('days');?>
                                  </h5></b></li>-->
                                <li>	
									  <?php echo $this->lang->line('Job Type: (Make up to 5 selections.)');?>
										 <!--OPTION LIST-->
								<?php
									 //calculate no of days to open
									 $no_of_days = count_days($postSimilarall->created,$postSimilarall->enddate) - 1;
									
									 $res = explode(',',$postSimilarall->project_categories);
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
														if($i%3 ==0)
															echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
													  ?>
														<td><label><input onClick="count(this.form)" name="categories[]" value="<?php echo $category->id; ?>" <?php if(in_array($category->category_name,$res)) echo 'checked="checked"'; ?>
														   <?php //echo set_checkbox('categories[]', $category->id); ?> type="checkbox"/><?php echo $category->category_name;  ?>
														  </td>
													<?php if($i%3 ==2)
															 echo '</tr>';
														   $i = $i + 1; ?>					  
														
													<?php
														} //Foreach End - Traverse Category
													?>
													  </table>
													</div>
													<?php
												}//Check For Cateogry Availability
										} //For Each Travesal - For Group
									}//If End - Check For Group Existence

								?>
                                </li>
								
                              </ul>
							  <?php if($postSimilarall->flag ==0){ ?>
							
                            <p><b><?php echo $this->lang->line('I want my project to stay open for bidding for');?></b>
										<select name="openDays">
										<?php for($i=1;$i<=$project_period;$i++){?>
										<option value="<?php echo $i;?>" <?php if(isset($no_of_days)) { if($no_of_days == $i) echo "selected"; }?>><?php echo $i;?></option>
										<?php } ?>
										</select>&nbsp;<?php echo $this->lang->line('days');?></p>
										
										<!-- OPTIONAL PROJECT DETAILS-->
										
									  </li>
								  </ul>  
										 
										<h3><span class="clsInvoice"><?php echo $this->lang->line('Optional Project Details...');?></span></h3>
										  <ul>
										  <li>
											<li>
																<p><h5><?php echo $this->lang->line('Attachment:');?>
																  <img src="<?php echo image_url('clip.gif'); ?>" width="15" height="13" />
																  <input name="attachment" type="file"/>
																 <small style="color:red;" ><?php echo $this->lang->line('allowed files'); ?></small>	
																  <?php 
																   $filesize = '0';
																   foreach($fileInfo->result() as $fileDate)
																	 {
																	   $filesize =$filesize + $fileDate->file_size;
																	 } ?>	 
																  
																</p>
																</h5>
																<p><small><?php echo $this->lang->line('info'); ?> <?php echo round($filesize/1024,2);?> <?php echo $this->lang->line('info1');?> <?php echo $maximum_size.' MB'; ?></small></p></li>
										  <li>
											<p><b><?php echo $this->lang->line('Project Budget:');?></b></p>
											<p><?php echo $this->lang->line('Minimum:');?>&nbsp;<span> $
											  <input name="budget_min" value="<?php echo $draft->budget_min; ?>" size="5" type="text"/> <?php //echo form_error('budget_min'); ?>
											  </span></p>
											  
											<p><?php echo $this->lang->line('Maximum:');?><span> $
											  <input name="budget_max" value="<?php echo $draft->budget_max; ?>" size="5" type="text"/><?php //echo form_error('budget_max'); ?>
											  </span></p>
										  </li>
										  <li class="clSNoBack">
											<ul class="clsFloatedList clearfix">
											  <li class="clsPercent30">
												<input  name="is_feature" value="1" type="checkbox" <?php if($draft->is_feature == '1') echo 'checked';?> />
												<b><?php echo $this->lang->line('Make Project');?> <a href="#" target="_blank"><?php echo $this->lang->line('Featured');?></a> <img src="<?php echo base_url() ?>app/css/black_images/featured2.gif" width="16" border="0" height="13" alt="."/></b> </li>
											  <li class="clsPercent10"> $ <?php echo $feature_project; ?>
											
											  </li>
											  <li class="clsPercent50"> <?php echo $this->lang->line('pro1');?> <a href="#" target="_blank"><?php echo $this->lang->line('Click here');?></a> <?php echo $this->lang->line('read');?></li>
											</ul>
											<ul class="clsFloatedList clearfix">
											  <li class="clsPercent30">
												<input name="is_urgent" value="1" type="checkbox" <?php if($draft->is_urgent == '1') echo 'checked';?>  />
												<b><?php echo $this->lang->line('Make Project Urgent');?><img src="<?php echo base_url() ?>app/css/black_images/urgent2.gif" width="16" border="0" height="13" alt="."/></b> </li>
											  <li class="clsPercent10"> $ <?php echo $urgent_project; ?></li>
											  <li class="clsPercent50"> <?php echo $this->lang->line('pro2');?> <a href="#"><?php echo $this->lang->line('urgent projects');?></a> <?php echo $this->lang->line('page. This option is free if you are a');?> <a href="#" target="_blank"><?php echo $this->lang->line('Certified Member');?></a>. <?php echo $this->lang->line('If your project is');?> <a href="#" target="_blank"><?php echo $this->lang->line('Featured');?></a> <?php echo $this->lang->line('the urgent fee is only $1.');?> </li>
											</ul>
											<ul class="clsFloatedList clearfix">
											  <li class="clsPercent30">
												<input  name="is_hide_bids" value="1" type="checkbox" <?php if($draft->is_hide_bids == '1') echo 'checked';?>  />
												<b><?php echo $this->lang->line('Hide Project Bids');?></b></li>
											  <li class="clsPercent10"> $ <?php echo $hide_project; ?></li>
											  <li class="clsPercent50"> <?php echo $this->lang->line('pro3');?> <a href="#" target="_blank"><?php echo $this->lang->line('Featured');?></a>, <?php echo $this->lang->line('Private or Urgent, or if you are a');?> <a href="#" target="_blank"><?php echo $this->lang->line('Certified Member');?></a>. </li>
											</ul>
										  </li>
										  
										  
							<?php } else {?>			  
								   
							<div id="advance" class="clsOptionalDetails">
							<h3><span class="clsFileManager"><?php echo $this->lang->line('Advanced Options');?></span></h3>
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
									 
									  
									
                                    </h5>
                                    <p><small><?php echo $this->lang->line('info'); ?> <?php echo round($filesize/1024,2);?> <?php echo $this->lang->line('info1');?> <?php echo $maximum_size.' MB'; ?></small></p></li>
									<li>
							  <p class="clsSitelinks"><?php echo $this->lang->line('Salary');?>(optional):  $<input type="text" name="salary" value="<?php echo $postSimilarall->salary; ?>"/>
							  <select name="salarytype" size="1">
										
										<option value="Annually" <?php if($postSimilarall->salarytype=='Annually') echo 'selected="selected"';?>>Annually</option>
										<option value="Yearly" <?php if($postSimilarall->salarytype=='Yearly') echo 'selected="selected"';  ?>>Yearly</option>
										<option value="Monthly" <?php if($postSimilarall->salarytype=='Monthly') echo 'selected="selected"'; ?>>Monthly</option>
										<option value="Weekly" <?php if($postSimilarall->salarytype=='Weekly') echo 'selected="selected"';?>>Weekly</option>
										<option value="Hourly" <?php if($postSimilarall->salarytype=='Hourly') echo 'selected="selected"'; ?>>Hourly</option>
									<option value="One-Time Payment" <?php if($postSimilarall->salarytype=='One-Time Payment') echo 'selected="selected"'; ?>>One-Time Payment</option>
										<option value="Commission" <?php if($postSimilarall->salarytype=='Commission') echo 'selected="selected"'; ?>>Commission</option>
							    </select></p>
								</li>
								<li>
								<h5><?php echo $this->lang->line('contact info');?></h5><p>Members must log in to view your contact info. They can also contact you via your message board.</p>
                       			 <textarea rows="10" name="contactinfo" cols="70"><?php echo $postSimilarall->contact; ?></textarea>(e.g. Please send your resumes to contact@email.com or call 1-555-555-5555.)
									  
									</p>
									</li>
									</ul>
								</div>
								<?php }?>
								   <!-- Note: Dont delete this. This will used in phase 2 for private projects to post by buyer -->
								  }
									} 
									?>
							  </div>
							  <p>
								 <input class="clsSmall" value="<?php echo $this->lang->line('Save Draft');?>" name="save_draft" type="submit"/>
									  <input class="clsSmall" value="<?php echo $this->lang->line('Preview');?>" name="preview_project" type="submit" onClick="javascript:return formSubmit()" />
									  <input class="clsMid" value="<?php echo $this->lang->line('Submit Job');?>" name="createProject" type="submit" />
									  
									  <?php if(isset($draft->id)) { ?>
									       <input class="clsMid" value="<?php echo $this->lang->line('Discard');?>" name="Discard" type="button" onClick="javascript:submitDraft()" />		  
									  <?php } ?>
									  </div>
									</p>
								  </li>
								</ul>
								</form><?php 
							   
							   ?>
							  <form action="<?php echo site_url('joblist/deleteDraft'); ?>" method="post" name="draft" id="draft">
							  <input name="projectid" value="<?php if(isset($draft->id)) echo $draft->id; ?>"type="hidden"/>
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
}
</script>
<?php $this->load->view('footer'); ?>