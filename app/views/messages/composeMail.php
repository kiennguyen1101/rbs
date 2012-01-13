<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<?php
		//Get Project Info
     	$project = $projects->row();
		//pr($userList);
//		pr($previewMessages);
		//echo $previewMessages['to_id'];
		//foreach($previewMessages as $res)
	 	 // echo $res;
		//echo count($previewMessages)
		//$msg = $previewMessages->row(); 
?>
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
							  <h2><?php echo $this->lang->line('Post Message'); ?></h2>
								<!--PROJECT MESSAGE BOARD--> 
									  <div id="selPMB" class="clsMarginTop">
									
									 <p class="clsSitelinks"><?php echo $this->lang->line('You are currently logged in as');?> <a class="glow" href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'programmer'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></a> (<a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout') ?></a>).
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
						  if($no == '0')
						    {
						     echo '<br>';
							 echo 'There is no last trasaction';
						     echo '<br><br>'; 
							 }
						   
						  if($no != '0')
						    { ?>

                             
						    <p><label><?php echo $this->lang->line('From'); ?></label><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></p>
						   
						    <p><label><?php echo $this->lang->line('To'); ?> </label> <?php $i=0; foreach($userList as $user) { if($user->id == $previewMessages['to_id']) { $i=1; echo $user->user_name; break; } } if($i == '0') echo 'All User [ Public Message ]'?> </p>
						  
						  					  
						  <p><label><?php echo $this->lang->line('Message'); ?></b></label><?php echo $previewMessages['message']; ?></p>
						  <p><label><?php echo $this->lang->line('Date'); ?></b></label><?php $date = $previewMessages['created']; echo get_date($date); ?></p>
						   <?php } ?>
						 


<!-- Preview mail end here -->
    <?php 

  } 
}
?>

                          <h3><span class="clsOptContact"><?php echo $this->lang->line('Post Message'); ?></span></h3>
							<?php 
							if($msg = $this->session->flashdata('flash_message'))
								{
								  echo $msg;
								}?>
							
							<form method="post" action="<?php echo site_url('messages/composeMail'); ?>" >
					  	   
								<p><label><?php echo $this->lang->line('From'); ?>:</label> <?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></p>
								
								<?php if($loggedInUser->role_id == '1')
								       { ?>
								<p><label><?php echo $this->lang->line('Project Name'); ?> :</label>
								  <select id="to" name="to" onchange="javascript:load_user(this.value);">
									  <option value=""><?php echo '-- '.$this->lang->line('Select Project').' --'; ?></option>
									  <?php 
									 
									  foreach($wonProjects as $res)
										{ 
										  //Make transfer only for buyer to programmer
										  if($loggedInUser->role_id == '1')
											{
											  if($res->creator_id == $loggedInUser->id)
												{ ?>
												  <option value="<?php echo $res->id; ?>" > <?php echo $res->project_name; ?></option> 
												  <?php 	
												}	
											}
										  //Make transfer only for programmer to buyer
										  if($loggedInUser->role_id == '2')
											{
											  if($res->programmer_id == $loggedInUser->id)
												{ ?>
												 <option value="<?php echo $res->id; ?>" > <?php echo $res->project_name; ?></option> <?php 	
												}	
											}	
									  }	//foreah end here  ?>
								 </select><?php echo form_error('to'); ?></p>
								 <p><label><?php echo $this->lang->line('To');?>:</label>
								 <b id="prog_id"> <select id="users_load" name="users_load"><option value=""><?php echo '-- '.$this->lang->line('Select Provider').' --'; ?></option>
								 <option value="0"><?php echo $this->lang->line('Everyone'); ?></option>
								 </select></b><?php echo form_error('prog_id'); ?></p>
									  <?php } else { ?>
									  <p><label><?php echo $this->lang->line('Project Name'); ?> :</label>
								  <select id="to" name="to" onchange="javascript:load_users(this.value);">
									  <option value=""><?php echo '-- '.$this->lang->line('Select Project').' --'; ?></option>
									  <?php 
									 
									  foreach($wonProjects as $res)
										{ 
										  //Make transfer only for buyer to programmer
										  if($loggedInUser->role_id == '1')
											{
											  if($res->creator_id == $loggedInUser->id)
												{ ?>
												  <option value="<?php echo $res->id; ?>" > <?php echo $res->project_name; ?></option> 
												  <?php 	
												}	
											}
										  //Make transfer only for programmer to buyer
										  if($loggedInUser->role_id == '2')
											{
											  if($res->programmer_id == $loggedInUser->id)
												{ ?>
												 <option value="<?php echo $res->id; ?>" > <?php echo $res->project_name; ?></option> <?php 	
												}	
											}	
									  }	//foreah end here  ?>
								 </select><?php echo form_error('to'); ?></p>
								 <p><label><?php echo $this->lang->line('To');?>:</label>
								 <b id="prog_id"> <select id="users_load" name="users_load"><option value=""><?php echo '-- '.$this->lang->line('Select Buyer').' --'; ?></option>
								 </select></b><?php echo form_error('prog_id'); ?></p>
									  <?php }?>
							  <div id="projectName" name="projectName" style="display:none; color:red;">
									<?php echo $this->lang->line('Select Project'); ?>
								</div>
								<p><label><?php echo $this->lang->line('Message'); ?>:</label><textarea rows="10" name="message" cols="60"><?php echo set_value('message'); ?></textarea>
	                             </p>
							<p><label>&nbsp;</label><small><?php echo $this->lang->line('Tip');?></small></p>
							<p><label>&nbsp;</label><?php echo form_error('message'); ?></p>							
							<p><label>&nbsp;</label><input class="clsSmall" type="submit" value="<?php echo $this->lang->line('Submit');?>" name="postMessage"/>
	                           <input  class="clsSmall" type="submit" value="<?php echo $this->lang->line('Preview');?>" name="previewMessage"/></p>
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
 <script type="text/javascript">
<!-- Function used to load the corresponding users to make transfer for corresponding project
// Argument                   --     Nil
//Return value                --     Programmername or buyername -->
function load_user(value)
{
  var utype = value;
  //var utype = document.getElementById('type_id').value

   if(utype>0)
  {
	  new Ajax.Request('<?php echo base_url().'index.php/transfer/load_users1';?>'+'/'+utype,
	  {
		method:'post',
		onSuccess: function(transport){
		  var response = transport.responseText || "no response text";
		  if(response!="no response text")
			//response='<select name="users_load" id="users_load" class="clsListBox">'+response+'</select>';
		  document.getElementById('prog_id').innerHTML = response;
		},
		onFailure: function(){ alert('Something went wrong ...') }
	  });
	  }
	else
	{
		document.getElementById('placer').innerHTML='<select name="users_load" id="users_load"><option value="0"><?php echo $this->lang->line('Everyone'); ?></option></select>';
		
	}
} //Function load_user end


function load_users(value)
{
//alert('welcome');
  var utype = value;
  //var utype = document.getElementById('type_id').value

   if(utype>0)
  {
	  new Ajax.Request('<?php echo base_url().'index.php/transfer/load_users2';?>'+'/'+utype,
	  {
		method:'post',
		onSuccess: function(transport){
		  var response = transport.responseText || "no response text";
		  if(response!="no response text")
			//response='<select name="users_load" id="users_load" class="clsListBox">'+response+'</select>';
		  document.getElementById('prog_id').innerHTML = response;
		},
		onFailure: function(){ alert('Something went wrong ...') }
	  });
	  }
	else
	{
		document.getElementById('placer').innerHTML='<select name="users_load" id="users_load"><option value="0"><?php echo $this->lang->line('Everyone'); ?></option></select>';
		
	}

} //Function load_user end
</script>


	  
      <!--END OF POST PROJECT-->
     </div>

</div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>
