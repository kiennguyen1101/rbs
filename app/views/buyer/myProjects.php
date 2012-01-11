<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
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

                              <h2><?php echo $this->lang->line('View My Projects');?></h2>
							 <h3><span class="clsPMB"><?php echo $this->lang->line('My Open Projects');?></span></h3>
                              <?php
							
								//Show Flash Message
							
								if($msg = $this->session->flashdata('flash_message'))
								{
									echo $msg;
								}
								?>
							   <table cellspacing="1" cellpadding="2" width="96%">

                                <tbody><tr>
                              						  
								  <td width="5%" class="dt"><?php echo $this->lang->line('Sl.No');?></td>
                                  <td width="20%" class="dt"><?php echo $this->lang->line('Project Name');?></td>
                                  <td width="8%" class="dt"><?php echo $this->lang->line('Bids');?></td>
								  <td width="8%" class="dt"><?php echo $this->lang->line('Lowest Bid');?></td>
								  <td width="8%" class="dt"><?php echo $this->lang->line('Avg Bid');?></td>
								  <td width="10%" class="dt"><?php echo $this->lang->line('Status');?></td>
								  <td width="10%" class="dt"><?php echo $this->lang->line('Posted');?> </td>
								  <td width="20%" class="dt"><?php echo $this->lang->line('Options');?></td>
								  <td width="20%" class="dt"><?php echo $this->lang->line('Type');?></td>
                                </tr>
								<?php
						  	if(isset($myProjects) and $myProjects->num_rows()>0)
							{
								$i=0;
								foreach($myProjects->result() as $myProjects)
								{
									if($i%2==0)
										$class = 'dt1 dt0';
									else 
										$class = 'dt2 dt0';	
									?>
									<tr class="<?php echo $class; ?>">
									<td ><?php echo $i+1;?></td>
									  <td><a href="<?php echo site_url('project/view/'.$myProjects->id); ?>"><?php echo $myProjects->project_name; ?></a><?php if($myProjects->is_urgent == 1) { ?>
                                    &nbsp;<img src="<?php echo image_url('urgent2.gif');?>" width="14" height="14" title="Urgent project" alt="<?php echo $this->lang->line('Urgent Project'); ?>" />
                                    <?php } 
								   if($myProjects->is_feature == 1) { ?>
                                    &nbsp;&nbsp;<img src="<?php echo image_url('featured2.gif');?>" width="14" height="14" title="Featured project" alt="<?php echo $this->lang->line('Featured Project'); ?>" />
                                    <? }
									if($myProjects->is_private == 1) {?>
									
									 &nbsp;&nbsp;<img src="<?php echo image_url('private.png');?>" width="14" height="14" title="private project" alt="<?php echo $this->lang->line('Private Project'); ?>" /><?php }
									 ?></td> <td> <?php echo $numbids = getNumBid($myProjects->id);?> </td><td> <?php echo getLowBid($myProjects->id);?> </td><td> <?php echo getBidsInfo($myProjects->id); ?> </td><td>
									  <?php 
		
									  echo getProjectStatus($myProjects->project_status);
		                         
								      ?>
								  </td><td><?php echo get_date($myProjects->created);?></td><td>
								  <?php 
								  if($numbids != 0 && ($myProjects->project_status == 0 || $myProjects->project_status == 1) || $myProjects->flag==1 ){ 
								  ?>
								  <a href="<?php echo site_url('project/pickProvider/'.$myProjects->id);?>"><?php echo $this->lang->line('Pick provider');?></a>
								  
								  <?php } 
								  if($myProjects->project_status == 0 && $myProjects->flag ==1 )
								  { ?>
									 <a href="<?php echo site_url('project/cancelProject/'.$myProjects->id); ?>"> <?php echo $this->lang->line('Cancel');?> </a>  <?php 
								  }
								  if($myProjects->project_status == 0 && $myProjects->flag ==0 )
								  { ?>
									 <a href="<?php echo site_url('project/cancelProject/'.$myProjects->id); ?>"> <?php echo $this->lang->line('Cancel');?> </a>  <?php 
								  }
								  if($myProjects->flag ==0){
								  ?>
								  
								  <a href="<?php echo site_url('project/extendBid/'.$myProjects->id);?>"><?php echo $this->lang->line('Extend');?></a>
								  <?php } ?>
								  </td>
								  <td>
								  <?php  if($myProjects->flag == 0)
								  
								 {
								 echo "Project"; }
								 else
								 {
								 echo "Job";
								 }?>
								  
								  </td></tr>
							       <?php		
						  			$i++;						
								}//For Each End - Latest Project Traversal															
							}//If - End Check For Latest Projects
							else{
							
							echo "<tr><td colspan='9'>".$this->lang->line('No Projects')."</td></tr>";
							}
						  ?>
                              </tbody></table>


							  <!--PAGING-->
							  <?php if(isset($pagination)) echo $pagination;?>
							 <!--END OF PAGING-->
							 <h3><span class="clsProDetial"><?php echo $this->lang->line('Closed Projects');?></span></h3> 
  <table cellspacing="1" cellpadding="2" width="96%">
                                <tbody><tr>
                                  <td width="5%" class="dt"><?php echo $this->lang->line('Sl.No');?></td>
                                  <td width="25%" class="dt"><?php echo $this->lang->line('Project Name');?></td>
								  <td width="25%" class="dt"><?php echo $this->lang->line('Project Winner');?></td>
                                  <td width="10%" class="dt"><?php echo $this->lang->line('Bid Price');?></td>
								  <td width="25#" class="dt"><?php echo $this->lang->line('Options');?></td>
								  
								   <td width="25#" class="dt"><?php echo $this->lang->line('Type');?></td>
                                </tr>
                          <?php
						  	if(isset($closedProjects) and $closedProjects->num_rows()>0)
							{
								$i=0;
								foreach($closedProjects->result() as $closedProject)
								{
								 $condition=array('subscriptionuser.username'=>$closedProject->userid);
								$certified2= $this->certificate_model->getCertificateUser($condition);
									$reviewDetails = getReviewStatus($closedProject->id,$closedProject->programmer_id);
									
									$reviewDetails = $reviewDetails->row();
									
								 
																
									if($i%2==0)
										$class = 'dt1 dt0';
									else 
										$class = 'dt2 dt0';	
									?>
                                   <tr class="<?php echo $class; ?>">
                                    <td><?php echo $i+1;?></td><td><a href="<?php echo site_url('project/view/'.$closedProject->id); ?>"><?php echo $closedProject->project_name; ?></a><?php if($closedProject->is_urgent == 1) { ?>
                                   &nbsp; <img src="<?php echo image_url('urgent2.gif');?>" width="14" height="14" title="Urgent project" alt="<?php echo $this->lang->line('Urgent Project'); ?>" />
                                    <?php } 
								   if($closedProject->is_feature == 1) { ?>
                                    &nbsp;&nbsp;<img src="<?php echo image_url('featured2.gif');?>" width="14" height="14" title="Featured project" alt="<?php echo $this->lang->line('Featured Project'); ?>" />
                                    <? }
									if($closedProject->is_private == 1) {?>
									
									&nbsp;&nbsp;<img src="<?php echo image_url('private.png');?>" width="14" height="14" title="private project" alt="<?php echo $this->lang->line('Private Project'); ?>" /><?php }
									 ?></td><td><a href="<?php echo site_url('programmer/viewProfile/'.$closedProject->userid);?>"><?php echo $closedProject->user_name; ?></a>
								<?php	if(count($certified2->result())>0)
								{?>
								<img src="<?php echo image_url('certified.gif');?>" title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php  echo $this->lang->line('Certified Member')?>" />
								<?php }?>
									 </td><td> <?php echo getLowestBid($closedProject->id,$closedProject->programmer_id); ?> </td><td> <a href="<?php echo site_url('buyer/reviewProgrammer/'.$closedProject->id);?>"><?php echo $this->lang->line('view review');?></a></td><td>
								  <?php  if($closedProject->flag == 0)
								  
								 {
								 echo "Project"; }
								 else
								 {
								 echo "Job";
								 }?>
								  
								  </td></tr>
                          <?php		
						  			$i++;		
																
								}//For Each End - Latest Project Traversal															
							}//If - End Check For Latest Projects
							else{
							
							echo "<tr><td colspan='9'>".$this->lang->line('No Projects')."</td></tr>";
							}
						  ?>
                              </tbody></table>		
							  
							  <h3><span class="clsOptDetial"><?php echo $this->lang->line('Bookmark').' '.$this->lang->line('Projects');?></span></h3> 
                             <table cellspacing="1" cellpadding="2" width="96%">
                                <tbody><tr>
                                  <td width="5%" class="dt"><?php echo $this->lang->line('Sl.No');?></td>
                                  <td width="30%" class="dt"><?php echo $this->lang->line('Project Name');?></td>
								  <td width="20%" class="dt"><?php echo $this->lang->line('Creator Name');?></td>
								  <td width="10%" class="dt"><?php echo $this->lang->line('Bid Amount');?></td>
								  <td width="10%" class="dt"><?php echo $this->lang->line('Posted');?></td>
								  <td width="10%" class="dt"><?php echo $this->lang->line('Status');?></td>
								  <td width="10%" class="dt"><?php echo $this->lang->line('Options');?></td>
								   <td width="25#" class="dt"><?php echo $this->lang->line('Type');?></td>
                                </tr>
                          <?php
						  	if(isset($bookMark) and $bookMark->num_rows()>0)
							{
								$i=0;
								foreach($bookMark->result() as $bookMark)
								{
								
									if($i%2==0)
										$class = 'dt1 dt0';
									else 
										$class = 'dt2 dt0';	
									?>
                                   <tr class="<?php echo $class; ?>">
                                    <td><?php echo $i+1;?></td>
									<td><a href="<?php echo site_url('project/view/'.$bookMark->project_id); ?>"><?php echo $bookMark->project_name; ?></a><?php if($bookMark->is_urgent == 1) { ?>
                                    &nbsp;<img src="<?php echo image_url('urgent2.gif');?>" width="14" height="14" title="Urgent project" alt="<?php echo $this->lang->line('Urgent Project'); ?>" />
                                    <?php } 
								   if($bookMark->is_feature == 1) { ?>
                                    &nbsp;&nbsp;<img src="<?php echo image_url('featured2.gif');?>" width="14" height="14" title="Featured project" alt="<?php echo $this->lang->line('Featured Project'); ?>" />
                                    <? }
									if($bookMark->is_private == 1) {?>
									
									 &nbsp;&nbsp;<img src="<?php echo image_url('private.png');?>" width="14" height="14" title="private project" alt="<?php echo $this->lang->line('Private Project'); ?>" /><?php }
									 ?></td>
									<td><a href="<?php echo site_url('buyer/viewProfile/'.$bookMark->creator_id);?>"><?php foreach($getUsers->result() as $user) { if($user->id == $bookMark->creator_id) { echo $user->user_name;
									
									 $condition=array('subscriptionuser.username'=>$user->id);
								$certified1= $this->certificate_model->getCertificateUser($condition);
										if(count($certified1->result())>0)
								{?>
								<img src="<?php echo image_url('certified.gif');?>" />
								<?php }
									 break; } } ?></a> </td>
									<td> <?php if(isset($bookMark->budget_min) or ($bookMark->budget_max)) echo '$ '.$bookMark->budget_min.' - '.$bookMark->budget_max; else echo 'N/A'; ?> </td>
									<td><?php echo get_date($bookMark->created);?></td>
									<td><?php echo getProjectStatus($bookMark->project_status); ?></td>
									<td><a href="<?php echo site_url('buyer/remove/'.$bookMark->project_id); ?>"><?php echo $this->lang->line('Remove');?></a></td>
									<td>
								  <?php  if($bookMark->flag == 0)
								  
								 {
								 echo "Project"; }
								 else
								 {
								 echo "Job";
								 }?>
								  
								  </td>
									
								  </tr>
                          <?php		
						  			$i++;						
								}//For Each End - Latest Project Traversal															
							}//If - End Check For Latest Projects
							else{
							
							echo "<tr><td colspan='9'>".$this->lang->line('No Projects')."</td></tr>";
							}
						  ?>
                              </tbody></table>		
							  <?php if(isset($pagination1)) echo $pagination1;?>
							  
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