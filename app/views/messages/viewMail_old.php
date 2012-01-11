<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<?php
		$usersInbox = $usersInbox;
		//Get the outbox mails
		$usersOutbox = $usersOutbox;
		
?>
<?php $innerClass='selected';?>

<div id="main">
  <!--POST PROJECT-->
  <?php $this->load->view('innerMenu');  ?>
  <div class="clsTabs clsInnerCommon clsInfoBox">
    <div class="block">
      <div class="grey_t">
        <div class="grey_r">
          <div class="grey_b">
            <div class="grey_l">
              <div class="grey_tl">
                <div class="grey_tr">
                  <div class="grey_bl">
                    <div class="grey_br padding0">
                      <div class="cls100_p ">
                        <div class="clsEditProfile clsSitelinks">
                          <h3><span class="clsPMB"><?php echo $this->lang->line('Project Mail Board'); ?></span></h3>
                          <p class="clsSitelinks"><span><?php echo $this->lang->line('user');?>:</span> <a class="glow" href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'programmer'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"> <?php echo $loggedInUser->user_name; ?></a> </p>
                          <p><span><?php echo $this->lang->line('User ID:');?></span> <?php echo $this->loggedInUser->id; ?></p>
                          <?php 
							  //Show Flash error Message  for keyword field is empty
								 if($msg = $this->session->flashdata('flash_message'))
									{
									echo $msg;
									}
							?>
                          <!-- Search particular mail -->
                          <form action="<?php echo site_url('messages/searchMail'); ?>" method="post">
                            <input type="hidden" value="<?php echo $logged_userrole; ?>" name="user_role"/>
                            <input type="hidden" value="<?php echo $loggedInUser->id; ?>" name="logged_id"/>
                            <p>
                              <input type="text" name="keyword" value="" id="keyword" />
                              <select name="searchmail"  id="userinfo">
                                <option name="projectid" value="projectid">Project Id</option>
                                <?php 
							 if($logged_userrole == '2')
							   { ?>
                                <option name="user" value="user">Buyer</option>
                                <?php 
							   } 
							if($logged_userrole == '1')	
							   { ?>
                                <option name="user" value="user">Programmer</option>
                                <?php
							   } ?>
                              </select>
                              <input type="submit" value="<?php echo $this->lang->line('Go');?>" class="clsMini"/>
                            </p>
                          </form>
						  <br />
                        </div>
                        <!-- end of Search particular mail -->
                        <h3><span class="clsInbox"><?php echo $this->lang->line('user_inbox'); ?></span></h3>
                        <p><span> <a href="<?php echo site_url('messages/composeMail'); ?>" class="buttonBlackShad"><span><?php echo $this->lang->line('Compose Project Mail Board');?></span></a><?php echo $this->lang->line('Message Posted:');?><b>
                          <?php if(isset($totalInbox)) echo $totalInbox; ?>
                          </b></span></p><br /><br/>
						    <?php 
							  //Show Flash error Message  for keyword field is empty
								 if($msg1 = $this->session->flashdata('flash_message1'))
									{
									echo $msg1;
									}
							?>
						
						  <form name="frminbox" action="<?php echo site_url('messages/deleteInbox'); ?>" method="post">
                        <table width="96%" cellspacing="1" cellpadding="2">
                          <tbody>
                            <tr>
							 <td width="5%" class="dt" align="center"><?php echo $this->lang->line('Delete'); ?></td>
                              <td width="10%" class="dt"><?php echo $this->lang->line('Project Id'); ?></td>
                              <td width="20%" class="dt"><?php echo 'From'; ?></td>
                              <td width="20%" class="dt"><?php echo $this->lang->line('Project Title'); ?></td>
                              <td width="15%" class="dt"><?php echo $this->lang->line('Time'); ?></td>
                              <td width="30%" class="dt"><?php echo $this->lang->line('Message'); ?></td>
                            </tr>
                            <?php
						  	$total_inbox=0;
									$noshow_total=0;
						  	if(isset($usersInbox) and $totalInbox>0)
							{
							
								
								$i=0; $j=0;			 
								 ?>
                            <?php
								
									 foreach($usersInbox as $Inbox)
									   { 
									  
									   		$total_inbox++;
											$deluserid=$Inbox->deluserid;
											$show='yes';
											if($deluserid!='')
											{
											$deluseridarr=explode(',',$deluserid);
											foreach($deluseridarr as $val)
											{
												if($val==$loggedInUser->id)
												{
													$noshow_total++;
													$show='no';
												}
											}
											}
											if($show=='yes')
											{
									   
									   $j=$j+1;
										if($j%2 == 0)
										 {
										  $class  = 'dt1 dt0';
										  $class2 = 'dt1';
										 }
										else
										 {
										  $class  = 'dt2 dt0'; 
										  $class2 = 'dt2';
										 }
										?>
                            <tr class="<?php echo $class; ?>">
							  
                              <?php
									 if(isset($keyword)) 
									   {    
										 if($keyword == $Inbox->project_id or $keyword == '' or $keyword == $Inbox->user_name)    
									        {	?>
                              <div id="masterdiv">
							 <td width="5%"  align="center"><input type="checkbox" name="inbox[]"  value="<?php echo $Inbox->id; ?>"/>  </td>
                                <td class="<?php echo $class2; ?>"><a href="<?php echo site_url('project/view/'.$Inbox->project_id); ?>">
                                  <?php 
															  echo $Inbox->project_id;
																 
																  ?>
                                  </a> </td>
                                <td class="<?php echo $class2; ?>"><?php 
													
													foreach($usersList as $userlist)
													{
														if($userlist->id == $Inbox->from_id )
														  { ?>
                                  <a href="<?php if($userlist->role_id == '2') echo site_url('programmer/viewProfile/'.$userlist->id); else echo site_url('buyer/viewProfile/'.$userlist->id); ?>">
                                  <?php 
															  echo $userlist->user_name; ?>
                                  </a>
                                  <?php 
															break;
														  }
													}?>
                                </td>
                                <td class="<?php echo $class2; ?>"><?php 
													 
													foreach($projectList as $plist)
													{
														if($plist->id == $Inbox->project_id )
														  {
															 $len=strlen($plist->project_name); 
																if($len < 10 )
																  { ?>
                                  <a href="<?php echo site_url('project/view/'.$plist->id) ?>" title="<?php echo $plist->project_name; ?>"><?php echo $plist->project_name; ?> </a>
                                  <?php 
																  }
																else
																   {
																		$out = substr($plist->project_name,0,10).'...';  ?>
                                  <a href="<?php echo site_url('project/view/'.$plist->id) ?>" title="<?php echo $plist->project_name; ?>"><?php echo $plist->project_name; ?> </a>
                                  <?php 
																   }
													 
															break;
														  } //if end here
													} //foreach end here ?>
                                </td>
                                <td><?php echo get_date($Inbox->created); ?> </td>
                                <td class="<?php echo $class2; ?>"><a href="<?php echo site_url('project/view/'.$Inbox->project_id); ?>">
                                  <?php $len=strlen($Inbox->message); 
														if($len < 25 )
														   echo $Inbox->message; 
														else
														   { ?>
                                  <div class="" onClick="return SwitchMenu('sub<?php echo $res = rand(5, 10000);?>')"> <?php echo substr($Inbox->message,0,25).'...';  ?></div>
                                  <?php  
														   }  
													 ?>
                                  </a></td>
                                <td class="<?php echo $class2; ?>"><p class="clsMailMsg"><span class="submenu" id="sub<?php echo $res;?>" style="display:none">
                                    <?php 
															echo $Inbox->message; 
															 //inner if end here
															$i=$i+1;
																?>
                                    <a href="">Reply</a> </span></p></td>
                               
                              </div>
							  </tr>
                              <?php 
										    }
											
										   
										 } 
										 
										 else 
										 
										 { 
												if($j%2 == 0)
												 {
												  $class  = 'dt1 dt0';
												  $class2 = 'dt1';
												  }
												else
												  {
												  $class  = 'dt2 dt0'; 
												  $class2 = 'dt2';
												  }
												?>
												 <tr class="<?php echo $class; ?>">
                              <div id="masterdiv">
                               
								   <td width="5%"  align="center" class="<?php echo $class2; ?>"><input type="checkbox" name="inbox[]"  value="<?php echo $Inbox->id; ?>"> </td>
                                    <td class="<?php echo $class2; ?>"><a href="<?php echo site_url('project/view/'.$Inbox->project_id); ?>">
                                      <?php 
															  echo $Inbox->project_id;
																 
																  ?>
                                      </a> </td>
                                    <td class="<?php echo $class2; ?>"><?php 
													foreach($usersList as $userlist)
													{
														if($userlist->id == $Inbox->from_id )
														  { ?>
                                      <a href="<?php if($userlist->role_id == '2') echo site_url('programmer/viewProfile/'.$userlist->id); else echo site_url('buyer/viewProfile/'.$userlist->id); ?>">
                                      <?php 
															  echo $userlist->user_name; ?>
                                      </a>
                                      <?php 
															break;
														  }
													}?>
                                    </td>
                                    <td class="<?php echo $class2; ?>"><?php 
													 
													foreach($projectList as $plist)
													{
														if($plist->id == $Inbox->project_id )
														  {
															 $len=strlen($plist->project_name); 
																if($len < 10 )
																  { ?>
                                      <a href="<?php echo site_url('project/view/'.$plist->id) ?>" title="<?php echo $plist->project_name; ?>"><?php echo $plist->project_name; ?> </a>
                                      <?php 
																  }
																else
																   {
																	$out = substr($plist->project_name,0,10).'...';  ?>
                                      <a href="<?php echo site_url('project/view/'.$plist->id) ?>" title="<?php echo $plist->project_name; ?>"><?php echo $plist->project_name; ?> </a>
                                      <?php 
																   }
													 
															break;
														  }
													}?>
                                    </td>
                                    <td class="<?php echo $class2; ?>"><?php echo get_date($Inbox->created); ?> </td>
                                    <td class="<?php echo $class2; ?>"><a href="<?php //echo site_url('project/view/'.$Inbox->project_id); ?>">
                                      <?php $len=strlen($Inbox->message); 
														if($len < 25 )
														   echo $Inbox->message; 
														else
														   { ?>
                                      <div class="" onClick="return SwitchMenu('sub<?php echo $res = rand(5, 10000);?>')"> <?php echo substr($Inbox->message,0,25).'...';  ?></div>
                                      <?php  
														   }
																										
														
														 
													 ?>
                                      </a><a href="<?php echo site_url('messages/messageReply/'.$Inbox->id); ?>">Reply</a>
                                      </p>
                                      <p class="clsMailMsg"><span class="submenu" id="sub<?php echo $res;?>" style="display:none">
                                        <?php 
															echo $Inbox->message; 
															 //inner if end here
															$i=$i+1;
																?>
                                        </span></p></td>
										
                              </div>
							  </tr>
                              <?php 
										    }    ?>
                            
                              <?php 
						  
						  	
						  			$i++;
									
								}				//If - End Check For inbox display					
							}//For Each End - Latest Project Traversal		
							if($noshow_total==$total_inbox)
							{
								echo '<td colspan="5">'.$this->lang->line('Inbox is Empty').'</td>';
							}	
							}//If - End Check For Latest Projects
							
							else
							{
								echo '<td colspan="5">'.$this->lang->line('Inbox is Empty').'</td>';
							}
						  ?>
                            </tr>
						<?php
							if(($noshow_total!=$total_inbox)  and ($totalInbox>0) ) 
							{  ?>
								<tr>
							<td colspan="5" align="left">
							<input class="clsMini" type="submit" value="<?php echo $this->lang->line('Delete');?>" name="Delete" />
							</td>
							</tr>
							<?php }	?>
							
                           
                            
                          </tbody>
                        </table>
						</form>
					
                        <h3><span class="clsOutbox"><?php echo $this->lang->line('user_outbox'); ?></span></h3>
                        <p><span class="clsPostProject"><a href="<?php echo site_url('messages/composeMail'); ?>" class="buttonBlackShad"><span><?php echo $this->lang->line('Compose Project Mail Board');?></span></a></span> <span><?php echo $this->lang->line('Message Posted:');?> <b>
                          <?php if(isset($totalOutbox)) echo $totalOutbox; ?>
                          </b></span></p><br /><br />
						     <?php 
							  //Show Flash error Message  for keyword field is empty
								 if($msg2 = $this->session->flashdata('flash_message2'))
									{
									echo $msg2;
									}
							?>
						
						    <form name="frmoutbox" action="<?php echo site_url('messages/deleteOutbox'); ?>" method="post">
                        <table width="96%" cellspacing="1" cellpadding="2">
                          <tbody>
                            <tr>
                            <td width="5%" class="dt" align="center"><?php echo $this->lang->line('Delete'); ?></td>
							  <td width="10%" class="dt"><?php echo $this->lang->line('Project Id'); ?></td>
                              <td width="20%" class="dt"><?php echo 'To'; ?></td>
                              <td width="20%" class="dt"><?php echo $this->lang->line('Project Title'); ?></td>
                              <td width="15%" class="dt"><?php echo $this->lang->line('Time'); ?></td>
                              <td width="30%" class="dt"><?php echo $this->lang->line('Message'); ?></td>
                            </tr>
                            <?php
							$total_outbox=0;
							$noshow_total=0;
									
						  	if(isset($usersOutbox) and $totalOutbox>0)
							{
							
									 	
								$i=0; $k=0;
								
								
								//pr($usersOutbox);
								?>
                            <?php foreach($usersOutbox as $Outbox)
								{
								          $deluserid=$Outbox->deluserid;
											$show='yes';
											if($deluserid!='')
											{
											$deluseridarr=explode(',',$deluserid);
											foreach($deluseridarr as $val)
											{
												if($val==$loggedInUser->id)
												{
													$noshow_total++;
													$show='no';
												}
											}
											}
											if($show=='yes')
											{
									if($i%2==0)
										$class = 'dt1 dt0';
									else 
										$class = 'dt2 dt0';
									?>
                            <?php 
							if(isset($keyword))
							  {
							   if($keyword == $Outbox->project_id or $keyword == '' or $keyword == $Outbox->user_name)
							     {
							       ?>
                          <div id="masterdiv">
                            <tr class="<?php echo $class; ?>">
                               <td width="5%"  align="center" class="<?php echo $class; ?>"><input type="checkbox" name="outbox[]"  value="<?php echo $Outbox->id; ?>"> </td>
							  <td><a href="<?php echo site_url('project/view/'.$Outbox->project_id); ?>"> <?php echo $Outbox->project_id; ?></a> </td>
                              <td><?php 
										foreach($usersList as $userlist)
										{
											if($userlist->id == $Outbox->to_id )
											  {?>
                                <a href="<?php if($userlist->role_id == '2') echo site_url('programmer/viewProfile/'.$userlist->id); else echo site_url('buyer/viewProfile/'.$userlist->id); ?>">
                                <?php 
															  echo $userlist->user_name; ?>
                                </a>
                                <?php
											  } 
										}
										if($Outbox->to_id == '' or $Outbox->to_id == '0') { echo 'All User';  }
										?>
                              </td>
                              <td><?php 
										 
										foreach($projectList as $plist)
										{
											if($plist->id == $Outbox->project_id )
											  {
												 $len=strlen($plist->project_name); 
												if($len < 10 )
												  { ?>
                                <a href="<?php echo site_url('project/view/'.$plist->id) ?>" title="<?php echo $plist->project_name; ?>"><?php echo $plist->project_name; ?> </a>
                                <?php 
												  }
												else
												   {
														$out = substr($plist->project_name,0,10).'...';  ?>
                                <a href="<?php echo site_url('project/view/'.$plist->id) ?>" title="<?php echo $plist->project_name; ?>"><?php echo $plist->project_name; ?> </a>
                                <?php 
												   }
										 
												break;
											  }
										}?>
                              </td>
                              <td><?php echo get_date($Outbox->created); ?> </td>
                              <td><a href="<?php //echo site_url('project/view/'.$outbox->id); ?>">
                                <?php $len=strlen($Outbox->message); 
											if($len < 25 )
										       echo $Outbox->message; 
										    else
											   { ?>
                                <div class="" onClick="return SwitchMenu('sub<?php echo $res = rand(5, 10000);?>')"> <?php echo substr($Outbox->message,0,25).'...';  ?></div>
                                <?php  
											   }   
										 ?>
                                </a>
                                <p class="clsMailMsg"><span class="submenu" id="sub<?php echo $res;?>" style="display:none">
                                  <?php 
												echo $Outbox->message; 
												 //inner if end here
												$i=$i+1;
													?>
                                  </span></p></td>
                            </tr>
                          </div>
                          <?php 
									}
								 }
								 else
								 { $i=1; $k=$k+1; 
								   if($k%2==0)
								      {
										$class = 'dt1 dt0';
										$class2= 'dt1';
									  }	
									else 
									  {
										$class = 'dt2 dt0';
										$class2= 'dt2';
									  }	
								   ?>
                          <div id="masterdiv">
                            <tr class="<?php echo $class; ?>">
							 <td width="5%"  align="center" class="<?php echo $class2; ?>"><input type="checkbox" name="outbox[]"  value="<?php echo $Outbox->id; ?>"> </td>
                              <td class="<?php echo $class2; ?>"><a href="<?php echo site_url('project/view/'.$Outbox->project_id); ?>"> <?php echo $Outbox->project_id; ?></a> </td>
                              <td class="<?php echo $class2; ?>"><?php 
									foreach($usersList as $userlist)
									{
										if($userlist->id == $Outbox->to_id )
										  {
											?>
                                <a href="<?php if($userlist->role_id == '2') echo site_url('programmer/viewProfile/'.$userlist->id); else echo site_url('buyer/viewProfile/'.$userlist->id); ?>">
                                <?php 
															  echo $userlist->user_name; ?>
                                </a>
                                <?php
										  } 
									}
									if($Outbox->to_id == '' or $Outbox->to_id == '0') { echo 'All User';  }
									
									?>
                              </td>
                              <td class="<?php echo $class2; ?>"><?php 
									 
									foreach($projectList as $plist)
									{
										if($plist->id == $Outbox->project_id )
										  {
											 $len=strlen($plist->project_name); 
												if($len < 10 )
												  { ?>
                                <a href="<?php echo site_url('project/view/'.$plist->id) ?>" title="<?php echo $plist->project_name; ?>"><?php echo $plist->project_name; ?> </a>
                                <?php 
												  }
												else
												   {
														$out = substr($plist->project_name,0,10).'...';  ?>
                                <a href="<?php echo site_url('project/view/'.$plist->id) ?>" title="<?php echo $plist->project_name; ?>"><?php echo $plist->project_name; ?> </a>
                                <?php 
												   }
									 
											break;
										  }
									}?>
                              </td>
                              <td class="<?php echo $class2; ?>"><?php echo get_date($Outbox->created); ?> </td>
                              <td class="<?php echo $class2; ?>"><a href="<?php //echo site_url('project/view/'.$outbox->id); ?>">
                                <?php 
									    
									    $len=strlen($Outbox->message); 
										if($len < 25 )
										    echo $Outbox->message; 
										else
										   { ?>
                                <div class="" onClick="return SwitchMenu('sub<?php echo $res = rand(5, 10000);?>')"> <?php echo substr($Outbox->message,0,25).'...';  ?></div>
                                <?php  
										   }
									 ?>
                                </a>
                                <p class="clsMailMsg"><span class="submenu" id="sub<?php echo $res;?>" style="display:none">
                                  <?php 
												echo $Outbox->message; 
											 //inner if end here
											$i=$i+1;
											?>
                                  </span></p></td>
                          </div>
                          <?php 

								 }	?>
                         
                          
                          <?php		
						  		$i++;						
								}
								}//For Each End - Latest Project Traversal		
								if($noshow_total==$total_outbox)
							{
								echo '<td colspan="5">'.$this->lang->line('Inbox is Empty').'</td>';
							}														
							}//If - End Check For Latest Projects
							else
							{
								echo '<td colspan="5">'.$this->lang->line('Outbox is Empty').'</td>';
							}
						  ?>
                          </tr>
						  
						  	<?php	if(($noshow_total!=$total_outbox)  and ($totalOutbox>0)) 
							{  ?>
								<tr>
							<td colspan="5" align="left">
							<input class="clsMini" type="submit" value="<?php echo $this->lang->line('Delete');?>" name="Delete" />
							</td>
							</tr>
							<?php }	?>
						 
                          
                          </tbody>
                          
                        </table>
						</form>
						
                        <?php if(isset($pagination_outbox)) echo $pagination_outbox;?>
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
<!--script -->
<script type="text/javascript">
<!-- Function used to load the corresponding users to make transfer for corresponding project
// Argument                   --     Nil
//Return value                --     Programmername or buyername -->
function load_user()
{
	var url = '<?php echo site_url('transfer/load_users');?>';
	new Ajax.Updater('users_load', url,   {  method     : 'post',
	  parameters : { type_id : $('type_id').value },
	  onLoading  : function ()
	  {
		$('users_load').innerHTML = '<img alt="loading..." src="<?php echo base_url().'images/loading.gif' ?>" />';
	  }
}); //Ajax Object Creation End
													
} //Function load_category end
</script>
<script>
new Ajax.Request('<?php echo site_url('project/showBids/'.$project->id); ?>',
  {
    method:'get',
    onSuccess: function(transport){
      var response = transport.responseText || "no response text";
      document.getElementById('sBids').innerHTML = response
    },
    onFailure: function(){ alert('Something went wrong...') }
  });
function amtSort(ord){
	new Ajax.Request('<?php echo site_url('project/showBids/'.$project->id); ?>'+'/'+ord,
	  {
		method:'get',
		onSuccess: function(transport){
		  var response = transport.responseText || "no response text";
		  document.getElementById('sBids').innerHTML = response
		},
		onFailure: function(){ alert('Something went wrong...') }
	  });
}
</script>
<style type="text/css">
.menutitle{
cursor:pointer;
margin-bottom: 5px;
background-color:#ECECFF;
color:#000000;
width:140px;
padding:2px;
text-align:center;
font-weight:bold;
/*/*/border:1px solid #000000;/* */
}

.submenu{
margin-bottom: 0.5em;
}
</style>
<script type="text/javascript">

/***********************************************
* Switch Menu script- by Martial B of http://getElementById.com/
* Modified by Dynamic Drive for format & NS4/IE4 compatibility
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

var persistmenu="yes" //"yes" or "no". Make sure each SPAN content contains an incrementing ID starting at 1 (id="sub1", id="sub2", etc)
var persisttype="sitewide" //enter "sitewide" for menu to persist across site, "local" for this page only

if (document.getElementById){ //DynamicDrive.com change
document.write('<style type="text/css">\n')
document.write('.submenu{display: none;}\n')
document.write('</style>\n')
}

function SwitchMenu(obj){
	if(document.getElementById){
		var el = document.getElementById(obj);
		var ar = document.getElementById("masterdiv").getElementsByTagName("span"); //DynamicDrive.com change
		if(el.style.display == "none")
		{ 
		   el.style.display = "block";
		   return false;
		}
		else{
			el.style.display = "none";
		   return false;
		}
	}
}

function get_cookie(Name) {
var search = Name + "="
var returnvalue = "";
if (document.cookie.length > 0) {
offset = document.cookie.indexOf(search)
if (offset != -1) {
offset += search.length
end = document.cookie.indexOf(";", offset);
if (end == -1) end = document.cookie.length;
returnvalue=unescape(document.cookie.substring(offset, end))
}
}
return returnvalue;
}

function onloadfunction(){
if (persistmenu=="yes"){
var cookiename=(persisttype=="sitewide")? "switchmenu" : window.location.pathname
var cookievalue=get_cookie(cookiename)
if (cookievalue!="")
document.getElementById(cookievalue).style.display="block"
}
}

function savemenustate(){
var inc=1, blockid=""
while (document.getElementById("sub"+inc)){
if (document.getElementById("sub"+inc).style.display=="block"){
blockid="sub"+inc
break
}
inc++
}
var cookiename=(persisttype=="sitewide")? "switchmenu" : window.location.pathname
var cookievalue=(persisttype=="sitewide")? blockid+";path=/" : blockid
document.cookie=cookiename+"="+cookievalue
}

if (window.addEventListener)
window.addEventListener("load", onloadfunction, false)
else if (window.attachEvent)
window.attachEvent("onload", onloadfunction)
else if (document.getElementById)
window.onload=onloadfunction

if (persistmenu=="yes" && document.getElementById)
window.onunload=savemenustate

</script>
<?php $this->load->view('footer'); ?>
