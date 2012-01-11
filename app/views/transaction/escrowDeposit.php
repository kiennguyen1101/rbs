<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->


<div id="main">
      <!--POST PROJECT-->
      <?php $this->load->view('innerMenu'); ?>
	 
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
                              <h3><span class="clsEscrow"><?php echo $this->lang->line('Escrow Funds');?></span></h3>
							  <?php $condition1=array('subscriptionuser.username'=>$loggedInUser->id);
								$certified1= $this->certificate_model->getCertificateUser($condition1);?>	
							<p><span><?php echo $this->lang->line('User name :');?></span><a class="glow" href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'programmer'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"> <?php echo $loggedInUser->user_name?></a>
							<?php if(count($certified1->result())>0)
								{?>
								<img src="<?php echo image_url('certified.gif');?>" />
								<?php }?>
							</p>
<p><span><?php echo $this->lang->line('Account Balance:');?></span> $ <?php if(isset($userAvailableBalance)) echo $userAvailableBalance.'.00'; ?></p>
							<?php  
							//Show Flash error Message  for deposit minimum amount
							if($msg = $this->session->flashdata('flash_message'))
								{
								echo $msg;
								}
							  ?>
							  <form name="drop_list" action="<?php echo site_url('escrow'); ?>"  method="post">
								 <p><span> <?php echo $this->lang->line('Select Project'); ?> :</span>
								  <select id="type_id" name="type_id" onchange="javascript:load_user(this.value);">
									  <option value="0"><?php echo '-- '.$this->lang->line('Select Project').' --'; ?></option>
									  <?php 
									  foreach($projectList->result() as $res)
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
								 <p><span><?php echo $this->lang->line('Programmer Name');?>:</span>
								 <b id="prog_id"> <select id="users_load" name="users_load"><option value="0"><?php echo $this->lang->line('Programmer Name');?></option>
								 </select></b></p>
									  
							  <div id="projectName" name="projectName" style="display:none; color:red;">
									<?php echo $this->lang->line('Select Project'); ?>
								</div>
							 <!-- Span for load the users list corresponding to the project --> 
							
							
							<p><span><?php echo $this->lang->line('Project Amount'); ?></span><div id="project_amount"><?php echo $this->lang->line('N/A'); ?></div></p>
							
							<!--<p><span><?php echo $this->lang->line('Due Amount'); ?></span> <div id="project_rem_amount"><?php echo $this->lang->line('N/A'); ?></div></p>-->
							
							<!--<p><span><?php echo $this->lang->line('Number of milestone payments:'); ?></span> <div id="escrow_sec"><?php echo $this->lang->line('N/A'); ?></div></p>-->
							
							<p><span><?php echo $this->lang->line('Transfer Amount'); ?></span> <input name="total" id="total" size="10" value="<?php echo set_value('total'); ?>"  type="text"/></p>
							
							<div name="totalAmount" id="totalAmount" style="display:none;color:red;">
							   <?php echo $this->lang->line('Enter the Amount'); ?>
							</div>
							<div name="Amountfield" id="Amountfield" style="display:none;color:red;">
							   <?php echo $this->lang->line('Invalid Input Value'); ?>
							</div>
                          <!--END OF SEND MONEY-->
							
							<p>(<?php echo $this->lang->line('select');?>)</p>
							<p>
							  <!--<input type="submit" value="Deposit" name="depositMoney" class="clsLogin"/>-->
							</p>                       
							
							<p>  <input  class="clsSmall" name="transferMoney"  value="<?php echo $this->lang->line('Transfer');?>" type="submit" onclick="javascript:return deposit_confirm(document.drop_list.type_id.value, document.drop_list.total.value);"/> </p>
							</form>
							<br />
							</div>
							<h3><span class="clsDepositTrans"><?php echo $this->lang->line('My Escrow Transactions');?></span></h3>
							<table cellspacing="1" cellpadding="2" width="96%">
                                <tbody><tr>
                                  <td width="30" class="dt"><?php echo $this->lang->line('SI.No');?></td>
                                  <td width="150" class="dt"><?php echo $this->lang->line('From');?></td>
								  <td width="150" class="dt"><?php echo $this->lang->line('To');?></td>
                                  <td width="50" class="dt"><?php echo $this->lang->line('Amount');?></td>
								  <td width="100" class="dt"><?php echo $this->lang->line('Date');?></td>
								   <td width="100" class="dt"><?php echo $this->lang->line('Project');?></td>
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
									   <a href="<?php if($user->role_id == '1') echo site_url('buyer/viewProfile/'.$user->id); if($user->role_id=='2') echo site_url('programmer/viewProfile/'.$user->id);?>"> <?php  echo $user->user_name; break; } }  ?></a></td>
									  
									  <td class="<?php echo $class1; ?>"><?php foreach($usersList->result() as $user) { if($user->id == $res->reciever_id) { ?>
									   <a href="<?php if($user->role_id == '1') echo site_url('buyer/viewProfile/'.$user->id); if($user->role_id=='2') echo site_url('programmer/viewProfile/'.$user->id);?>"> <?php  echo $user->user_name; break; } }  ?></a></td>
									   								  
									  <td class="<?php echo $class1; ?>"> $ <?php echo $res->amount; ?></td>
									  <td class="<?php echo $class1; ?>"><?php echo get_datetime($res->transaction_time); ?></td>
									   <td class="<?php echo $class1; ?>"><?php $project_name= get_project_name($res->project_id); if(isset($project_name)) echo $project_name; ?></td>
									  <td class="<?php echo $class1; ?>"><?php echo $res->status; ?> 
									   <?php if($res->status == 'Pending') { ?>
									   <a href="<?php echo site_url('escrow/releaseEscrow/'.$res->id); ?>"> <span class="clsEscrow1"><img alt="Escrow Release" title="Escrow Release" height="15" src="<?php echo image_url('release.png')?>"/></a></span></a>
									   <?php } ?>
									  </td> 
									 
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
							   <!--PAGING-->
								<?php if(isset($pagination)) echo $pagination;?>
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
     
    </div>

<script type="text/javascript">
<!-- Function used to load the corresponding users to make transfer for corresponding project
// Argument                   --     Nil
//Return value                --     Programmername or buyername -->
function load_user(value)
{
  var utype = value;
  //var utype = document.getElementById('type_id').value
  var projectAmountEle=document.getElementById('project_amount');
  var projectRemAmountEle=document.getElementById('project_rem_amount');
  var escrowSecEle=document.getElementById('escrow_sec');
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
	  
	  // Get the escrow due details
	  new Ajax.Request('<?php echo base_url().'index.php/escrow/escrow_project';?>'+'/'+utype,
	  {
		method:'post',
		onSuccess: function(transport){
		  
		  var response = transport.responseText || "no response";
		  if(response!='no response')
		  {
		 
			  var responseArr=response.split('#');
			  projectAmountEle.innerHTML='<input name="project_amount" type="hidden" value="'+responseArr[0]+'" />$'+responseArr[0];
			  
				if(responseArr[1]>0)
				{
					projectRemAmountEle.innerHTML='<input name="project_rem_amount" type="hidden" value="'+responseArr[1]+'" />$'+responseArr[1];
					if(responseArr[3]>0)
						escrowSecEle.innerHTML='<input name="escrow_due" type="hidden" value="'+responseArr[3]+'" />'+responseArr[3];
					else
						escrowSecEle.innerHTML='<input type="hidden" name="escrow_due" value="0" /><?php echo $this->lang->line('Escrow due complete'); ?>';
				}
				else
				{
					projectRemAmountEle.innerHTML='<span style="font-weight:bold"><?php echo $this->lang->line('Paid'); ?></span>';
					escrowSecEle.innerHTML='<input name="escrow_due" type="hidden" value="'+responseArr[3]+'" /><span style="font-weight:bold"><?php echo $this->lang->line('Paid'); ?></span>';
				}
				
			 if(responseArr[2]==0)
				escrowSecEle.innerHTML='<input type="text" name="escrow_due" maxlength="3" size="5" />';
			}
		  
		},
		onFailure: function(){ alert('Something went wrong ...') }
  	});
	}
	else
	{
		document.getElementById('placer').innerHTML='<select name="users_load" id="users_load"><option value="0"><?php echo $this->lang->line("Select Developer"); ?></option></select>';
		projectAmountEle.innerHTML='<?php echo $this->lang->line('N/A'); ?>';
		projectRemAmountEle.innerHTML='<?php echo $this->lang->line('N/A'); ?>';
		escrowSecEle.innerHTML='<?php echo $this->lang->line('N/A'); ?>';
	}
} //Function load_user end

//Fucntion for form validation
function formValidation()
{
	var e = $('projectName');
	var e2 = $('totalAmount');
	var e3 = $('Amountfield');
	var e4 = $('escrowValid');
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
	
	 // vaiidation of the escrow due
	 if($('escrow_due'))
	 {
	 	if(isNaN($('escrow_due').value) || $('escrow_due').value==0)
		{
			e4.style.display='block';
			return false;
		}
	 }
	 													
} //Function formValidation end


// Escrow deposit confirm
function deposit_confirm(projectId, amt)
{
	var opt=true;
	var exceedOpt=true;
	
	if(formValidation()==false)
		return false;
	else
	{
		if(isNaN(projectId))
		{
			alert('Invalid project id !');
			return false;
		}
		
		if(isNaN(amt))
		{
			alert('Invalid amount !');
			return false;
		}
		
		opt=confirm('<?php echo str_replace('release', 'deposit', $this->lang->line('Escrow confirm')); ?>');
		if(opt)
			return exceedOpt=is_exceed(projectId, amt);
		else
			return false;
	}
}

// Function for check whether the transaction will be exceed the project amount
function is_exceed(projectId, amt)
{
	var opt=true;
	new Ajax.Request('<?php echo base_url().'index.php/escrow/escrow_project';?>'+'/'+projectId,
	{
		asynchronous:false,
		method:'post',
		onSuccess: function(transport){
		var response = transport.responseText || "no response text";
		
		var responseArr=response.split('#');
		if(parseInt(responseArr[1])<parseInt(amt))
		{
			opt=confirm('<?php echo $this->lang->line("Project remaining amount"); ?> is: $'+responseArr[1]+'\n<?php echo $this->lang->line('Transaction amount'); ?> is : $'+amt+'\n\n<?php echo $this->lang->line('Exceeds project amount'); ?>');
		}
		},
		onFailure: function(){ alert('Something went wrong...') }
	});
	return opt;
}


// Escrow transfer confirm
function trans_confirm(projectId, amt)
{
	var opt=true;
	var exceedOpt=true;
	
	if(isNaN(projectId))
	{
		alert('Invalid project id !');
		return false;
	}
	
	if(isNaN(amt))
	{
		alert('Invalid amount !');
		return false;
	}
	
	opt=confirm('<?php echo str_replace('release', 'deposit', $this->lang->line('Escrow confirm')); ?>');
	if(opt)
		return exceedOpt=is_exceed(projectId, amt);
	else
		return false;
}
</script>
<?php $this->load->view('footer'); ?>