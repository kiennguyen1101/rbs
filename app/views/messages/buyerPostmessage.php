<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<?php
		//Get Project Info
     	$project = $projects->row();
		//print_r($project);
?>
<!--MAIN-->
<div id="main">
	<form method="post" action="" >
		<input type="hidden" value="<?php echo $project->id; ?>"  name="project_id"/>
      <!--PROJECT MESSAGE BOARD-->
     <div class="clsEditProfile">
	  <div id="selPMB" class="clsMarginTop">
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
	  <p class="clsSitelinks"><?php echo $this->lang->line('You are currently logged in as'); ?> <a class="glow" href="<?php echo site_url('buyer/viewProfile/'.$loggedInUser->id); ?>"><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></a> <?php echo $this->lang->line('(');?><a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout') ?></a><?php echo $this->lang->line(').');?>
</p> <br />
                <?php
				  
					//Check the condition for the messages are saved or not
					if(isset($previewMessages))
					 { 
					   if(count($previewMessages) != '0')
						 {
					 ?>
					<!-- Preview Mail start Here -->
					
						<!--RC-->
						  
						  <h3><span  class="clsPMB"><?php echo $this->lang->line('Preview Message'); ?></span></h3>
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
						  <p><span><?php echo $this->lang->line('From'); ?></span><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></p>
						  <p><span><?php echo $this->lang->line('To'); ?></span><?php if(isset($user_name) != '') echo $user_name; else echo 'All users [ Public Message ]'; ?></p>
						  <p><span><?php echo $this->lang->line('Message'); ?></span><?php echo $previewMessages['message']; ?></p>
						  <p><span><?php echo $this->lang->line('Date'); ?></span><?php $date = $previewMessages['created']; echo get_date($date); ?></p>
						   <?php } ?>
						 



<!-- Preview mail end here -->
    <?php 
  } 
}
?>
    <!--RC-->
	 
                               <h3><span  class="clsOptContact"><?php echo $this->lang->line('Post Message'); ?></span></h3>
							    <?php
								 //Show Flash error Message  for deposit minimum amount
								if($msg = $this->session->flashdata('flash_message'))
								{
								  echo $msg;
								}
								 ?>
								<p><span><?php echo $this->lang->line('Project'); ?>:</b></span>
									<a href="<?php echo site_url('project/view/'.$project->id); ?>"><?php echo $project->project_name; ?></a>
								</p>		
	  
	 							<p><span><b><?php echo $this->lang->line('To');  ?>:</b></span>
	  
								  <?php 
							
								  if(isset($messages))
									{
									$j=0; $i=0;$k=1;$ss=0;$l=0;
									$res=array();
									
									 foreach($messages->result() as $message)
									  {
									   
										if($project->id == $message->project_id)
										  {
											//echo $message->from_id;
											
												$user = getUserInfo($message->from_id);
												 
											 $users=$user->user_name;
												for($i=0;$i<$j;$i++)
												  {
													if($res[$i] == $users)
													  {
														$k=0;
														break;
													  }
													  else
													  {
														$k=1;
													  }
												  }
												  if($k==1)
												   {
													 $res[$j]=$users;
													 $j=$j+1;
													}
											?>				
						
						
											  <?php }
											 }
											 }
						
									  $out=count($res);
									  if($out > 0)
									   {
												   for($i=0;$i<$out;$i++)
													{
														if($res[$i] != $loggedInUser->user_name)
														{
															$res1[$l]=$res[$i];
															$l=$l+1;
														}
													}
													if(isset($res1))
													 {
													  if($res1)
													   $out=count($res1);	
													 }  
										}		 	
			      ?>
											 <select name="to" id="to">
								  <option selected="selected" value=""></option>
								  <option value="0"><?php echo $this->lang->line('Everyone'); ?></option>
		 
								 <?php
								  //Load bid post progreammer names
								  foreach($bidUser->result() as $bid)
								   {
								     if(in_array($bid->user_id,$res1))
									  {?>	 
								      <option value="<?php echo $bid->user_id; ?>"><?php echo $bid->user_name; ?></option>	 <?php
								     } 
								   else{
								       
									 
									 ?> 
								 
												 <!-- Load the Users who are all post messages for the particular project -->
												 <option value="<?php echo $bid->user_id; ?>"><?php echo $bid->user_name; ?></option>
										<?php
									}
								   
								 
								 }?>			 
											 </select>
							  </p>
							 <!-- <p><span><?php echo $this->lang->line('Private Messsage'); ?>:</span><input type="text" name="toid" value="<?php echo set_value('toid'); ?>" class="clsText"><?php if(form_error('toid')) echo form_error('toid').'<br><br><br>'; ?></p>-->
							  <!--<p><span>&nbsp;</span> <?php echo $this->lang->line('Note: Only private message to programmer'); ?></p>-->
   						        <p><span><b><?php echo $this->lang->line('Message'); ?>:</b></span>
	                              <textarea rows="10" name="message" cols="60"><?php echo set_value('message'); ?></textarea>
						          <p><span>&nbsp;</span><?php if(form_error('message')) { echo form_error('message').'<br>'; } ?></p>
							  </p><br />
							  <p><span>&nbsp;</span><?php echo $this->lang->line('Tip: You can post programming code by placing it within [code] and [/code] tags.'); ?>

 							  <p><span>&nbsp;</span>
							     <input class="clsSmall" type="submit" value="<?php echo $this->lang->line('Submit'); ?>" name="postMessage"/> 
	                             <input class="clsSmall" type="submit" value="<?php echo $this->lang->line('Preview'); ?>"  name="previewMessage"/>
							  </p>
							 
                              
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
      <!--END OF PROJECT MESSAGE BOARD-->
	  </form>
    </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>