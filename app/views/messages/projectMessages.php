<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<?php
		//Get Project Info
     	$project = $projects->row();
?>
<div id="main">
      <!--POST PROJECT-->
      <div class="clsInnerpageCommon">
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
                             <h2><?php echo $this->lang->line('Messages'); ?></h2>
							 <?php if($project->flag==0) { ?>
							  <h3><span class="clsFeatured"><?php echo 'Project Details'; ?></span></h3>
							  
							  <p><?php echo $this->lang->line('Project'); ?>: <a href="<?php echo site_url('project/view/'.$project->id); ?>"><?php echo $project->project_name; ?></a></p>
							 <br />
							  <h3><span class="clsPMB"><?php echo $this->lang->line('Project Message Board'); ?></span></h3><?php } else
							  {
							  ?>
							  <h3><span class="clsFeatured"><?php echo 'Job Details'; ?></span></h3>
							  
							  <p><?php echo $this->lang->line('Job'); ?>: <a href="<?php echo site_url('project/view/'.$project->id); ?>"><?php echo $project->project_name; ?></a></p>
							 <br />
							  <h3><span class="clsPMB"><?php echo $this->lang->line('Job Message Board'); ?></span></h3><?php }?>
							  <div class="buttonwrapper">
							   <p><a class="buttonBlackShad" href="<?php echo site_url('messages/post/'.$project->id); ?>"><span><?php echo $this->lang->line('Post Message'); ?></span></a></p></div>
							  
                              <table cellpadding="2" cellspacing="1" width="96%">
							   <tr>
		 					  	  <td width="15%" class="dt"><?php echo $this->lang->line('Author'); ?></td>
								  <td width="20%" class="dt"><?php echo 'Date'; ?></td>
								  <td width="20%" class="dt"><?php echo 'Message Type'; ?></td>
								  <td class="dt"><?php echo $this->lang->line('Message'); ?></td>
								  <td width="7%" class="dt" align="center"><?php echo $this->lang->line('Options'); ?></td>
							   </tr>
							  <?php $k=0; $i=0;
								if(isset($messages) and $messages->num_rows()>0)
								{
									foreach($messages->result() as $message)
									{
									  if($message->project_id == $project->id)
									   { $i++;
									   if($i%2==0)
									     {
										 	$class='dt1 dt0';
											$class2='dt1';
										 }
									   else
									     {
										 $class='dt2 dt0';
										 $class2='dt2';
										 }	 
							           ?>
									 <tr class="<?php echo $class; ?>">
										  <td class="<?php echo $class2; ?>"><?php echo $message->user_name; $k= $k+1; ?></td>
										  <td class="<?php echo $class2; ?>"><?php echo '# '.$message->id.' posted '.get_datetime($message->created); ?></td>
										  
										  
										   <td class="<?php echo $class2; ?>"><?php
												$user = getUserInfo($message->to_id);
												 if($message->to_id =='0' and $message->project_id == $project->id)
												  {
													   ?>
														 <span class="clsMsgLink"><?php echo $this->lang->line('[');?><?php echo $this->lang->line('Message for Everyone'); 
												   } else {
											?>
										  
										 <?php echo $this->lang->line('[');?><?php echo $this->lang->line('private message for'); ?>
										   <?php
										   		 $user = getUserInfo($message->to_id);
												 if(is_object($user)) 
										   		 	echo $user->user_name;
													 }
											?><?php echo $this->lang->line(']');?></td>
									<td class="<?php echo $class2; ?>">
									 <?php
									 	if($loggedInUser == FALSE)
										{
											if(is_object($loggedInUser) and $loggedInUser->id==$message->from_id and $loggedInUser->id==$message->to_id or $message->to_id =='0')
											{ 
												?>
													<p class="clsAdd clsClearFix">
														<?php echo nl2br($message->message); ?>
													 </p>
												<?php
											}
										}
										else
										{
											if(is_object($loggedInUser) and $loggedInUser->id==$message->from_id or $loggedInUser->id==$message->to_id or $message->to_id =='0' and $message->project_id == $project->id)
											{ 
												?>
													<p class="clsAdd clsMailMsg">
														<?php echo nl2br($message->message); ?>
													 </p></td>
												<?php
											}
										}
										 }
										else
										{
										    $k=0;
																				
										}
										
										?>
										<td class="<?php echo $class2; ?>" align="center">
										<a href="<?php if(!isset($loggedInUser->id)) echo site_url('users/login'); else echo site_url('messages/messageReply/'.$message->id); ?>">
										<?php 
										if(isset($loggedInUser->user_name)){
										if($message->user_name != $loggedInUser->user_name)
										{ echo $this->lang->line('Reply'); } 
										}
										?></a></td>
										<?php 
										
									}//Foreach End
								} else {
									?>
										<?php echo'<td colspan="5">'.$this->lang->line('No Messages Posted').'</td>'; ?>
									<?php
								}//If End
							?>
							
							</tr>
							</table>
							 <!--PAGING-->
								<?php if(isset($pagination_inbox)) echo $pagination_inbox;?>
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
      <!--END OF POST PROJECT-->
    </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>
