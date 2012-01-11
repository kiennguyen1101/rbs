<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<?php
		//Get Project Info
     	$project = $projects->row();
?>
<!--MAIN-->
<div id="main">
 <div class="clsEditProfile">
    <div class="clsContact">
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
							<div id="selPMB">
							
							 <h2><?php echo $this->lang->line('Post Message'); ?></h2>
							 <p class="clsSitelinks"><?php echo $this->lang->line('You are currently logged in as');?> <a class="glow" href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'programmer'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></a> <?php echo $this->lang->line('(');?><a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout') ?></a><?php echo $this->lang->line(').');?></p><br />
							<?php 
							//Check the condition for the messages are saved or not
							if(isset($previewMessages))
							 { 
							   if(count($previewMessages) != '0')
								 {
								// echo 'id value'.$to_id;
								 $user = getUserInfo($to_id);
								
							        ?>
									<!-- Preview Mail start Here -->
								  <h3><span class="clsPMB"><?php echo $this->lang->line('Preview Message');?></span></h3>
								  <?php 
								  $no=1;
								  if($no != '0')
									{ ?>
								  <p><span class="clsWidth100"><b><?php echo $this->lang->line('From'); ?></b></span> <?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></p>
								  <p><span><b><?php echo $this->lang->line('To'); ?></b></span><?php if(isset($to_id))echo $user->user_name; else $project->user_name; ?></p>
								   <?php if($project->flag=='0')  {?>
								  <p><span><b><?php echo $this->lang->line('Project Name'); ?></b></span><?php echo $project->project_name;?></p>
								  <?php  } else {?>
								  <p><span><b><?php echo $this->lang->line('Job Name'); ?></b></span><?php echo $project->project_name;?></p>
								  <?php }?>
								  <p><span><b><?php echo $this->lang->line('message_validation'); ?></b></span><?php echo $previewMessages['message']; ?></p>
								  <p><span><b><?php echo $this->lang->line('Date'); ?></b></span><?php $date = $previewMessages['created']; echo get_date($date); ?></p> <?php
								   } ?>
									<!-- Preview mail end here -->
								  <?php 
							   } 
							}
							?><br />
							       <h3><span class="clsOptContact"><?php echo $this->lang->line('Post Message');?></span></h3>
									<form method="post" action="<?php echo site_url('messages/post'); ?>" >
										<input type="hidden" value="<?php if($project->creator_id) echo $project->creator_id; ?>"  name="to"/>
										<input type="hidden" value="<?php if($project->id) echo $project->id; ?>"  name="project_id"/>
									  <!--PROJECT MESSAGE BOARD-->
									  <div id="selPMB" class="clsMarginTop">
									 
									  <p><span><b><?php echo $this->lang->line('Project'); ?>:</b></span><a href="<?php echo site_url('project/view/'.$project->id); ?>"><?php if($project->project_name) echo $project->project_name; ?></a></p>	
									  
								<p><span><b><?php echo $this->lang->line('To'); ?>:</b></span><?php if(isset($to_id)) echo $user->user_name; else echo $project->user_name; ?></p>
									  
								 <p><span><b><?php echo $this->lang->line('Message');?>:</b></span><textarea rows="10" name="message" cols="60"><?php echo set_value('message'); ?></textarea></p>
								    <p class="hidden" ><span>&nbsp;</span><?php echo $this->lang->line('Tip: You can post programming code by placing it within [code] and [/code] tags.'); ?></p>
						            <p class="hidden" ><span>&nbsp;</span><?php echo form_error('message'); ?></p>
									<p><span>&nbsp;</span><input class="clsSmall" type="submit" value="<?php echo $this->lang->line('Submit');?>" name="postMessage"/> <input  class="clsSmall" type="submit" value="<?php echo $this->lang->line('Preview');?>" name="previewMessage"/></p>	  
									  </div>
									  <!--END OF PROJECT MESSAGE BOARD-->
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
      <!--END OF POST PROJECT-->
     </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>