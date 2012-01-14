<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<?php
		//Get Project Info
     	$project            = $projects->row();
		$users              = $users->row();
		$message            = $messages->row();
?>
<!--MAIN-->
<div id="main">
<!--PROJECT MESSAGE BOARD-->
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
	<div class="clsContactForm">					
							  <h2><?php echo $this->lang->line('Post Message'); ?></h2>
							  <p class="clsSitelinks"><?php echo $this->lang->line('You are currently logged in as');?> <a class="glow" href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'seller'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></a> (<a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout') ?></a>).</p><br />
								<?php
								//Check the condition for the messages are saved or not
								if(isset($previewMessages))
								 { 
								    if(count($previewMessages) != '0')
									 {
										 ?>
										<!-- Preview Mail start Here -->
				
					
										  <h3><span class="clsPMB"><?php echo $this->lang->line('Preview Message');?></span></h3>
										  <?php 
										  $no=1;
										  if($no == '0')
											{
											 echo '<br>';
											 echo 'There is no last trasaction';
											 echo '<br><br>'; 
											 }
										   
										  if($no != '0')
											{ ?>
										  <ul>	
										  <li><span><b><?php echo $this->lang->line('From'); ?></b></span> <?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></li>
										  
										  
										  <li><span><b><?php echo $this->lang->line('To'); ?></b></span><?php echo $users->user_name;?> </li>
										  
															  
										  <li><span><b><?php echo $this->lang->line('Message'); ?></b></span><?php echo $previewMessages['message']; ?></li>
										  <li><span><b><?php echo $this->lang->line('Date'); ?></b></span><?php $date = $previewMessages['created']; echo get_date($date); ?></li>
										  </ul>
										   <?php } ?>
										   <!-- Preview mail end here -->
										<?php 
							       } 
							    }
							?>
							<h3><span class="clsOptContact"><?php echo $this->lang->line('Post Message');?></span></h3>
							<form method="post" action="<?php echo site_url('messages/messageReply/'.$message->id); ?>" >

								<p><label><b><?php echo $this->lang->line('From'); ?>:</b></label>
								  <?php if(isset($loggedInUser) and is_object($loggedInUser)) {  echo $loggedInUser->user_name; }?>  </p>
								   <p><label><b><?php echo $this->lang->line('To'); ?></b></label><?php echo $users->user_name;?> </p>
								   <p><label><b><?php echo $this->lang->line('Project Name'); ?>:</b></label>
										<?php $i =0 ; ?>
										<!-- Load the Users who are all post messages for the particular project -->
										<?php echo $project->project_name; ?>
								 </p>
								  
									  <p><label><b><?php echo $this->lang->line('Message'); ?>:</b></label>
									  <textarea rows="10" name="message" cols="60"><?php echo set_value('message'); ?></textarea></p>
									 <p><label>&nbsp;</label><?php echo $this->lang->line('Tip');?> </p>
									 <p><label>&nbsp;</label><?php echo form_error('message'); ?></p>
									  
									  <p><label >&nbsp;</label>
									  <input class="clsSmall" type="submit" value="<?php echo $this->lang->line('Submit');?>" name="postMessage"/>
									  <input  class="clsSmall" type="submit" value="<?php echo $this->lang->line('Preview');?>" name="previewMessage"/>
									</p>
							 
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
	  </div>
    </div>
	
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>