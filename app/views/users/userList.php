<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main">
<!--DEPOSITE PAGE-->
<div  id="selDeposit">
	  <?php $this->load->view('innerMenu'); 
	  //public $role;
	 // pr($usersList);

	  $usersLists = $usersList;
	  $role = $loggedInUser->role_id;
	  ?>
	  <div class="clsTabs clsInnerCommon clsUserList">
        <div class="block">
          <div class="grey_t">
            <div class="grey_r">
              <div class="grey_b">
                <div class="grey_l">
                  <div class="grey_tl">
                    <div class="grey_tr">
                      <div class="grey_bl">
                        <div class="grey_br padding0">
                          <div class="cls100_p">
						  <div class="clsEditProfile clsSitelinks"> 
							<h3><span class="clsTransfer"><?php echo $this->lang->line('User List');?></span></h3>      
							                        
							  <?php
								//Show Flash Message
								if($msg = $this->session->flashdata('flash_message'))
								{
									echo $msg;
								}
								
								?>
								
							<p class="clsSitelinks"><span>
							   <?php echo '<b>'.$this->lang->line('user').'</b>';?> :</span>
									<a class="glow" href="<?php if($loggedInUser->role_id == '1') { echo site_url('buyer/viewProfile/'.$loggedInUser->id);   } 
									   if($loggedInUser->role_id == '2') 
										 { 
										   echo site_url('seller/viewProfile/'.$loggedInUser->id);
										 } ?>" >
										<?php echo $loggedInUser->user_name; ?>
									</a>
									<?php 
							 $condition1=array('subscriptionuser.username'=>$loggedInUser->id);
								$certified1= $this->certificate_model->getCertificateUser($condition1);
								if($certified1->num_rows()>0)
			                    {
							       foreach($certified1->result() as $certificate)
				                     {
									$user_id=$certificate->username;
									$id=$certificate->id;
									$condition=array('subscriptionuser.flag'=>1,'subscriptionuser.id'=>$id);
					                $userlists= $this->certificate_model->getCertificateUser($condition);
									// get the validity
									$validdate=$userlists->row();
									$end_date=$validdate->valid; 
									$created_date=$validdate->created;
									$valid_date=date('d/m/Y',$created_date);
								    $next=$created_date+($end_date * 24 * 60 * 60);
									$next_day= date('d/m/Y', $next) ."\n";
							        if(time()<=$next)
								    {?>
								<img src="<?php echo image_url('certified.gif');?>"  title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php  echo $this->lang->line('Certified Member')?>"/>
								<?php } 
								  }
								   }?>

									</p>
							   <p><span><?php echo '<b>'.$this->lang->line('user id').'</b>';?> :</span> <?php echo $loggedInUser->id;  ?></p>
						</div>
							<h3><span class="clsMyOpen"><?php echo $this->lang->line('favorite user');?></span></h3>
							<table width="96%" cellspacing="1" cellpadding="2">
                                <tbody><tr>
                                  <td width="10%" class="dt"><?php echo '<b>'.$this->lang->line('User Id').'</b>'; ?> </td>
                                  <td width="150" class="dt"><?php echo '<b>'.$this->lang->line('User Name').'</b>'; ?></td>
								  <td width="20%" class="dt">Move</td>
								  <td width="10%" class="dt">Option</td>
                                </tr>
                                
								<?php if(isset($favouriteUsers))
							    {
							   
							    $i=0;
							    foreach($favouriteUsers->result() as $res)
								 { 
								 if( $res->user_role == '1')
								 {  
						         $i=$i+1; 
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
										?> 
										<tr class="<?php echo $class; ?>">
										<td><?php echo $res->user_id; ?> </td>
										<td><?php
										  foreach($usersList as $users)
										   { 
											 if($res->user_id == $users->id) 
											   {  ?>
												 <a href="<?php if($users->role_id == '1') { echo site_url('buyer/viewProfile/'.$users->id);   } 
												   if($users->role_id == '2') 
												   { 
													 echo site_url('seller/viewProfile/'.$users->id);
													} ?>">
													<?php echo $users->user_name;?>
													<?php $condition=array('subscriptionuser.username'=>$res->id);
								$certified= $this->certificate_model->getCertificateUser($condition);?>
													<?php 
							 $condition1=array('subscriptionuser.username'=>$loggedInUser->id);
								$certified1= $this->certificate_model->getCertificateUser($condition1);
								if($certified1->num_rows()>0)
			                    {
							       foreach($certified1->result() as $certificate)
				                     {
									$user_id=$certificate->username;
									$id=$certificate->id;
									$condition=array('subscriptionuser.flag'=>1,'subscriptionuser.id'=>$id);
					                $userlists= $this->certificate_model->getCertificateUser($condition);
									// get the validity
									$validdate=$userlists->row();
									$end_date=$validdate->valid; 
									$created_date=$validdate->created;
									$valid_date=date('d/m/Y',$created_date);
								    $next=$created_date+($end_date * 24 * 60 * 60);
									$next_day= date('d/m/Y', $next) ."\n";
							        if(time()<=$next)
								    {?>
								<img src="<?php echo image_url('certified.gif');?>"  title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php  echo $this->lang->line('Certified Member')?>"/>
								<?php } 
								  }
								   }?>

												<?php 	
													 break; 
												}
											} //foreach end here   ?>
												 </a>
										 </td>
										<td>
										  <a href="<?php echo site_url('userList/changeUser/'.$res->user_id); ?>">
										  <?php echo $this->lang->line('move block'); ?>
										  </a>
										</td>
										<td>
										  <a href="<?php echo site_url('userList/deleteUser/'.$res->user_id); ?>">
										  <?php echo $this->lang->line('Delete'); ?>
										  </a>
										</td></tr>
										 <?php 	
										 
									  }
								  }
							  } ?>	  	  	
			
								</tbody>
							</table>
							 <form name="add_favourite_list" action="<?php echo site_url('userList/addFavourite'); ?>" method="post">
							  <p><?php 
							  if($loggedInUser->role_id == '2')
								{
								   echo $this->lang->line('Add Buyer to favorites');
								}
							  if($loggedInUser->role_id == '1')
								{
								   echo $this->lang->line('Add Seller to favorites');
								}	
							  ?>
							  <input type="hidden" name="role" value="1">
							  <input type="hidden" name="creator_id" value="<?php echo $loggedInUser->id; ?>" />
							  <input type="hidden" name="creator_role" value="<?php echo $loggedInUser->role_id; ?>" />
							  <input type="text" name="add_favourite" value="<?php echo set_value('add_favourite'); ?>"/><?php echo form_error('add_favourite'); ?></p>
							  
							 <p class="clsRight"><input type="submit" name"addBlock" class="clsSmall" value="<?php echo $this->lang->line('Add User');?>" /></p>
						  </form>
							<h3><span class="clsMyClose"><?php echo $this->lang->line('Blocked user');?></span></h3>
							<table width="96%" cellspacing="1" cellpadding="2">
                                <tbody><tr>
                                  <td width="10%" class="dt"><?php echo '<b>'.$this->lang->line('User Id').'</b>'; ?> </td>
                                  <td width="150" class="dt"><?php echo '<b>'.$this->lang->line('User Name').'</b>'; ?></td>
								   <td width="20%" class="dt">Move</td>
								  <td width="10%" class="dt">Option</td>
                                </tr>
							
								
                             <?php if(isset($favouriteUsers))
			     {
				   
				   $i=0;
				   foreach($favouriteUsers->result() as $res)
				     { 
					   if( $res->user_role == '2')
					     {  
						         $i=$i+1; 
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
						  
										?>
										<tr class="<?php echo $class; ?>">
										<td><?php echo $res->user_id; ?> </td>
										<td><?php
										  foreach($usersList as $users)
										   { 
											 if($res->user_id == $users->id) 
											   {  ?>
												 <a href="<?php if($users->role_id == '1') { echo site_url('buyer/viewProfile/'.$users->id);   } 
												   if($users->role_id == '2') 
												   { 
													 echo site_url('seller/viewProfile/'.$users->id);

													} ?>"><?php echo $users->user_name;?>
													<?php 
							  $condition1=array('subscriptionuser.username'=>$users->id);
								$certified1= $this->certificate_model->getCertificateUser($condition1);
								if($certified1->num_rows()>0)
			                    {
									// get the validity
									$validdate=$certified1->row();
									$end_date=$validdate->valid; 
									$created_date=$validdate->created;
									$valid_date=date('d/m/Y',$created_date);
								    $next=$created_date+($end_date * 24 * 60 * 60);
									$next_day= date('d/m/Y', $next) ."\n";
							        if(time()<=$next)
								    {?>
								<img src="<?php echo image_url('certified.gif');?>"  title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php  echo $this->lang->line('Certified Member')?>"/>
								<?php } 
								   }
								   
								   

													break; 
												}
											} //foreach end here   ?>
												 </a>
										 </td>
										<td>
										  <a href="<?php echo site_url('userList/changeUser/'.$res->user_id); ?>">
										  <?php echo $this->lang->line('move favourite'); ?>
										  </a>
										</td>
										<td>
										  <a href="<?php echo site_url('userList/deleteUser/'.$res->user_id); ?>">
										  <?php echo $this->lang->line('Delete'); ?>
										  </a>
										</td></tr>
										 <?php 	
										 
									  }
								  }
							  } ?>	  	  	
			
								</tbody>
							</table>
					 
							<form name="add_block_list" action="<?php echo site_url('userList/addBlock'); ?>" method="post">
								 <p> <?php 
								  if($loggedInUser->role_id == '2')
									{
									   echo $this->lang->line('Blacklist a Buyer');
									}
								  if($loggedInUser->role_id == '1')
									{
									   echo $this->lang->line('Blacklist a Seller');
									}	
								  ?>
								  <input type="hidden" name="role" value="2">
								  <input type="hidden" name="creator_id" value="<?php echo $loggedInUser->id; ?>" />
								  <input type="hidden" name="creator_role" value="<?php echo $loggedInUser->role_id; ?>" />
								  <input type="text" name="add_block" value="<?php echo set_value('add_block'); ?>"/><?php echo form_error('add_block'); ?></p>
								  
								<p class="clsRight">  <input type="submit" name"addBlock" class="clsSmall" value="<?php echo $this->lang->line('Ban User');?>" /></p>
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
      	<div class="alignRight">
		</div>
	  </div>
      <!--END OF POST PROJECT-->
     </div>
<?php $this->load->view('footer'); ?>