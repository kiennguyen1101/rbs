<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<div id="main">
      <!--POST PROJECT-->
      <?php $this->load->view('innerMenu');?>

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
                            <div class="clsInnerCommon">
							<div class="clsEditProfile clsSitelinks"> 
                             <h3><span class="clsEscrow"><?php echo $this->lang->line('Transfer Funds');?></span></h3>
							<?php $condition1=array('subscriptionuser.username'=>$loggedInUser->id);
								$certified1= $this->certificate_model->getCertificateUser($condition1);?>
							<p><span><?php echo $this->lang->line('User name :');?></span><a class="glow" href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'programmer'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"> <?php echo $loggedInUser->user_name?></a>
							<?php if(count($certified1->result())>0)
								{?>
								<img src="<?php echo image_url('certified.gif');?>" />
								<?php }?></p>
							
							<p><span><?php echo $this->lang->line('Account Balance:');?></span> $ <?php if(isset($userAvailableBalance)) echo $userAvailableBalance.'.00'; ?></p>

							<?php  
							//Show Flash error Message  for deposit minimum amount
							if($msg = $this->session->flashdata('flash_message'))
								{
								echo $msg;
								}
							  
							  ?>
							 
							  <form name="drop_list" action="<?php echo site_url('transfer'); ?>"  method="post">
								 
								 <p><span><?php echo $this->lang->line('Select Project'); ?> :</span>
								  <select id="type_id" name="type_id" onchange="javascript:load_user();">
									  <option value="0"><?php echo '-- '.$this->lang->line('Select Project').' --'; ?></option>
									  <?php 
											print_r($projectList_tranferamount);				
									  foreach($projectList_tranferamount as $res)
										{ 
										  //Make transfer only for buyer to programmer
										  if($logged_userrole == '1')
											{
											  if($res->creator_id == $loggedInUser->id and $res->programmer_id != '0')
												{ ?>
												  <option value="<?php echo $res->id; ?>" > <?php echo $res->id.' -- '.$res->project_name; ?></option> 
												  <?php 	
												}	
											}
																				  
										  //Make transfer only for programmer to buyer
										  if($logged_userrole == '2')
											{
											  if($res->programmer_id == $loggedInUser->id)
												{ ?>
												 <option value="<?php echo $res->id; ?>" > <?php echo $res->id.' -- '.$res->project_name; ?></option> <?php 	
												}	
											}	
									  }	//foreah end here  ?>
								 </select></p>
								 <p><span><?php if($loggedInUser->role_id == '1') echo $this->lang->line('Select Programmer'); else echo $this->lang->line('Select Buyer');?>:</span>
								<select name="users_load" id="users_load" class="clsListBox">
								 												
									 <?php 
									 if($logged_userrole == '2')
									   { ?>
										 <option value="0" selected="selected"><?php echo '<b>-- '.$this->lang->line('Select Buyer').' --</b>'; ?> </option> <?php 
									   } 
									 if($logged_userrole == '1')	
									   { ?>
									     <option value="0" selected="selected"> <?php echo '<b>-- '.$this->lang->line('Select Programmer').' --</b>'; ?> </option> <?php 
									   } ?>
								</select>
							  </p>
							  <div id="projectName" name="projectName" style="display:none; color:red;">
									<p><span>&nbsp;</span><?php echo $this->lang->line('Select Project'); ?></p>
								</div>
							 <!-- Span for load the users list corresponding to the project --> 
							
							<p><span><?php echo $this->lang->line('Transfer Amount'); ?></span> <input name="total" id="total" size="10" value="<?php echo set_value('total'); ?>"  type="text"/></p>
							<div name="totalAmount" id="totalAmount" style="display:none;color:red;">
							  <p><span>&nbsp;</span><?php echo $this->lang->line('Enter the Amount'); ?></p>
							</div>
							<div name="Amountfield" id="Amountfield" style="display:none;color:red;">
							   <p><span>&nbsp;</span><?php echo $this->lang->line('Invalid Input Value'); ?></p>
							</div>
                          <!--END OF SEND MONEY-->
							<p><span>&nbsp;</span><input  class="clsSmall" name="transferMoney"  value="<?php echo $this->lang->line('Transfer');?>" type="submit" onclick="javascript:return formValidation();"/> </p>
							</form>
							</div>
							<br />
							<h3><span class="clsTransfer"><?php echo $this->lang->line('My Transfer Transactions');?></span></h3>
							<table cellspacing="1" cellpadding="2" width="96%">
                                <tbody><tr>
                                  <td width="30" class="dt"><?php echo $this->lang->line('SI.No');?></td>
                                  <td width="150" class="dt"><?php echo $this->lang->line('From');?></td>
								  <td width="150" class="dt"><?php echo $this->lang->line('To');?></td>
                                  <td width="50" class="dt"><?php echo $this->lang->line('Amount');?></td>
								  <td width="100" class="dt"><?php echo $this->lang->line('Date');?></td>
								  <td width="250" class="dt"><?php echo $this->lang->line('Status');?></td>
                                </tr>
								
								 <?php $i=1; $k=0;
						        foreach($transactions1->result() as $res)
								{ $i=$i+1; 
								  if($i%2 == 0)
								    {
								    $class ="dt1 dt0";
									$class1 = "dt1";
									}
								  else
								    {
								    $class ="dt2 dt0";	
									$class1 = "dt2";
									}
									  $k=$k+1;
										?>
									  <tr>
									  <td class="<?php echo $class; ?>"><?php echo $k; ?></td>
									  <td class="<?php echo $class1; ?>"><?php foreach($usersList->result() as $user) { if($user->id == $res->creator_id) { ?>
									   <a href="<?php if($user->role_id == '1') echo site_url('buyer/viewProfile/'.$user->id); if($user->role_id=='2') echo site_url('programmer/viewProfile/'.$user->id);?>"> <?php  echo $user->user_name; 
									   
									   		   $condition=array('subscriptionuser.username'=>$user->id);
								$certified1= $this->certificate_model->getCertificateUser($condition);?>
								 <?php if(count($certified1->result())>0)
								{?>
								<img src="<?php echo image_url('certified.gif');?>" />
								<?php }
									   break; } }  ?></a></td>
									  
									  
									  <td class="<?php echo $class1; ?>"><?php foreach($usersList->result() as $user) { if($user->id == $res->reciever_id) { ?>
									   <a href="<?php if($user->role_id == '1') echo site_url('buyer/viewProfile/'.$user->id); if($user->role_id=='2') echo site_url('programmer/viewProfile/'.$user->id);?>"> <?php  echo $user->user_name; break; } }  ?></a></td> 
									   								  
									  <td class="<?php echo $class1; ?>"> $ <?php echo $res->amount; ?></td>
									  <td class="<?php echo $class1; ?>"><?php echo get_datetime($res->transaction_time); ?></td>
									  <td class="<?php echo $class1; ?>"><?php echo $res->status; ?> </td> 
									  <?php 
								} 
								if($k=='0')
								   {
									echo '<td colspan="5">';
									echo 'There is no Transaction';
									echo '</td>';
								   }	
								?>	 </tr> 
                              </tbody></table>
							  
							 </span>
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
 <!--PAGING-->
<?php if(isset($pagination)) echo $pagination;?>
<!--END OF PAGING-->
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
} //Ajax funciton end here

//Fucntion for form validation
function formValidation()
{
	var e = $('projectName');
	var e2 = $('totalAmount');
	var e3 = $('Amountfield');
	
	//This is used to check the project name field
	if($('type_id').value == '0')
	  {
		   if(e.style.display == "none")
			{ //DynamicDrive.com change
			  
			   e.style.display = "block";
			   return false;
			}
			else
			{
			  e.style.display = "none";
			   return false;
			}
	  } //Project field check if end here
	
	//This is used to check the amount field
	if($('total').value=='')
	 {
		if(e2.style.display == "none")
		{ //DynamicDrive.com change
				  
		   e2.style.display = "block";
		   return false;
		}
		else
		{
		  e2.style.display = "none";
		   return false;
		}
	 }//Amount field check If end here
	 
	 //Check the input value field format
	 if($('total').value != '')
	 {
	 	var numericExpression = /^[0-9]+$/;
		var elem              = $('total');
		if(elem.value.match(numericExpression)){
			return true;
			
		}
		else
		{
			elem.clear();
			elem.focus();
			if(e3.style.display == "none")
			{ //DynamicDrive.com change
					  
			   e2.style.display = "none";
			   e3.style.display = "block";
			   return false;
			}
			else
			{
			  e2.style.display = "none";
			  e3.style.display = "none";
			   return false;
			}
		}

	 }
	 													
} //Function formValidation end
</script>
<?php $this->load->view('footer'); ?>
