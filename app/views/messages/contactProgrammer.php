<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main">

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
							 <h2><?php echo 'Contact Programmer'?></h2>
							 <p class="clsSitelinks"><?php echo $this->lang->line('You are currently logged in as');?> <a href="#" class="glow"><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></a> <?php echo $this->lang->line('(');?><a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout') ?></a><?php echo $this->lang->line(').');?>
						</p>
<br />
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
									  if($no != '0')
									  { ?>
										<p><label><?php echo $this->lang->line('From'); ?></label><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></p>
										<p><label><?php echo $this->lang->line('To'); ?></label><?php echo $touser;?></p>
										<p><label><?php echo $this->lang->line('Project Name'); ?></label><?php foreach($projectsList->result() as $projects) { if($projects->id == $projectName) echo $projects->project_name; }?></p>
										<p><label><?php echo $this->lang->line('message_validation'); ?></label><?php echo $previewMessages['message']; ?></p>
										<p><label><?php echo $this->lang->line('Date'); ?></label><?php $date = $previewMessages['created']; echo get_date($date); ?></p> <?php
									  } ?>
									 <!-- Preview mail end here -->
										<?php 
								 } 
							  }
							?>
							<form method="post" action="<?php echo site_url('userList/contactProgrammer'); ?>" >
							<input type="hidden" name="fromId" value="<?php echo $loggedInUser->id; ?>" />
							<input type="hidden" name="toId" value="<?php if(isset($touser)) echo $touser; ?>" />
							  <!--PROJECT MESSAGE BOARD-->
							  <div class="clsContactForm">

							  <h3><span class="clsOptContact"><?php echo $this->lang->line('Post Message'); ?></span></h3>
	  						
							  <p><label><?php echo $this->lang->line('From'); ?>:</label><a href="<?php echo site_url('buyer/viewprofile/'.$loggedInUser->id); ?>"><?php echo $loggedInUser->user_name; ?></a></p>	
							  
							  <p><label><?php echo $this->lang->line('To'); ?>:</label><?php foreach($usersList as $res) if($res->id == $touser) echo $res->user_name;?></p>
							  <p><label><?php echo $this->lang->line('Project Name'); ?>:</label>
								<select name="projectName"> <?php foreach($projectsList->result() as $projects) { ?><option value="<?php echo $projects->id; ?>"><?php echo $projects->project_name; ?></option><?php } ?></select></p>
							 
							  <p><label><?php echo $this->lang->line('message_validation'); ?>:</label><textarea rows="10" name="message" cols="60"><?php echo set_value('message'); ?></textarea>
							  <p><label>&nbsp;</label><?php echo $this->lang->line('Tip');?></p>	
  <p><label>&nbsp;</label><span ><?php echo form_error('message'); ?></span></p>							  							  
							  <p><label>&nbsp;</label><input class="clsSmall" type="submit" value="<?php echo $this->lang->line('Submit');?>" name="postMessage"/>
								<input class="clsSmall" type="submit" value="<?php echo $this->lang->line('Preview');?>" name="previewMessage"/></p>	  
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
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>