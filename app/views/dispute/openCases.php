<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
 <div id="main">
  <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
  ?>
      <!--MY PROJECTS-->
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
                              <h2><?php echo $this->lang->line('project resolution board');?> - <?php echo $this->lang->line('open cases');?></h2>
							  <div class="clsHeads clearfix">
                            <div class="clsHeadingLeft clsFloatLeft">
                              <h3><span class="clsCancel"><?php echo $this->lang->line('Cancellation cases');?></span></h3>
                            </div>
                            <div class="clsHeadingRight clsFloatRight">
                              <p class="clsFloatRight"> <span class="clsPostProject"> <a href="<?php echo site_url('dispute/viewClosedCases');?>" class="buttonBlack"><span><?php echo $this->lang->line("view closed cases");?></span></a></span> </p>
                            </div>
                          </div>
							 
                             <table cellspacing="1" cellpadding="2" width="96%">
                                <tbody><tr>
                                  <td width="5%" class="dt"><?php echo $this->lang->line('SI.No');?></td>
                                  <td width="25%" class="dt"><?php echo $this->lang->line('Project Name');?></td>
                                  <td width="10%" class="dt"><?php echo $this->lang->line('Opened By');?></td>
								  <td width="10%" class="dt"><?php echo $this->lang->line('Date opened');?></td>
								  <td width="25%" class="dt"><?php echo $this->lang->line('reason');?></td>
								  <td width="12%" class="dt"><?php echo $this->lang->line('Last response');?></td>
								  <td width="8%" class="dt"><?php echo $this->lang->line('view');?></td>
                                </tr>
								<?php
						  	
						  	if(isset($cancellation) and $cancellation->num_rows()>0)
							{
								$i=0;
								foreach($cancellation->result() as $cancellation)
								{
									if($i%2==0)
										$class = 'dt1';
									else 
										$class = 'dt2';	
									?>
                                <tr>
                                  <td class="<?php echo $class;?> dt0"><?php echo $i+1;?>.</td>
                                  <td class="<?php echo $class;?>"><a href="<?php echo site_url('dispute/view/'.$cancellation->project_id); ?>"><?php echo $cancellation->project_name; ?></a></td>
                                  <td class="<?php echo $class;?>"><?php echo getUserDetails($cancellation->user_id,'user_name');?></td>
                                  <td class="<?php echo $class;?>"><?php echo get_date($cancellation->created); ?></td>
								  <td class="<?php echo $class;?>"><?php echo $cancellation->case_reason;?></td>
								  <td class="<?php echo $class;?>"><?php echo getLastResponse($cancellation->id,'Cancel');?></td>
                                  <td class="<?php echo $class;?>"><a href="<?php echo site_url('dispute/viewCase/'.$cancellation->id);?>" class="buttonBlack"><span><?php echo $this->lang->line('view');?></span></a></td>
                                </tr>
                              <?php		
						  			$i++;						
								}//For Each End - Latest Project Traversal															
							}//If - End Check For Latest Projects
							else
							echo "<tr><td colspan=7 class='dt2'>".$this->lang->line('No cancellation cases')."</td></tr>";
						  ?>
                              </tbody></table>
							 <h3><span class="clsDisputes"><?php echo $this->lang->line('dispute_cases');?></span></h3> 
                             <table cellspacing="1" cellpadding="2" width="96%">
                                <tbody><tr>
                                   <td width="5%" class="dt"><?php echo $this->lang->line('SI.No');?></td>
                                  <td width="25%" class="dt"><?php echo $this->lang->line('Project Name');?></td>
                                  <td width="10%" class="dt"><?php echo $this->lang->line('Opened By');?></td>
								  <td width="10%" class="dt"><?php echo $this->lang->line('Date opened');?></td>
								  <td width="25%" class="dt"><?php echo $this->lang->line('reason');?></td>
								  <td width="12%" class="dt"><?php echo $this->lang->line('Last response');?></td>
								  <td width="8%" class="dt"><?php echo $this->lang->line('view');?></td>
                                </tr>
								 <?php
						  	
						  	if(isset($disputes) and $disputes->num_rows()>0)
							{
								$i=0;
								foreach($disputes->result() as $dispute)
								{
									if($i%2==0)
										$class = 'dt1';
									else 
										$class = 'dt2';
									?>
                               <tr>
                                 <td class="<?php echo $class;?> dt0"><?php echo $i+1;?></td>
                                  <td class="<?php echo $class;?>"><a href="<?php echo site_url('dispute/view/'.$dispute->project_id); ?>"><?php echo $dispute->project_name; ?></a></td>
								  <td class="<?php echo $class;?>"><?php echo getUserDetails($dispute->user_id,'user_name');?></td>
                                  <td class="<?php echo $class;?>"><?php echo get_date($dispute->created); ?></td>
								  <td class="<?php echo $class;?>"><?php echo $dispute->case_reason;?></td>
								  <td class="<?php echo $class;?>"><?php echo getLastResponse($dispute->id,'Dispute');?></td> 
								  <td class="<?php echo $class;?>"><a href="<?php echo site_url('dispute/viewCase/'.$dispute->id);?>" class="buttonBlack"><span><?php echo $this->lang->line('view');?></span></a></td>
                                </tr>
                                <?php		
						  			$i++;						
								}//For Each End - Latest Project Traversal															
							}//If - End Check For Latest Projects
							else
							echo "<tr><td colspan=7 class='dt2'>".$this->lang->line('No dispute cases')."</td></tr>";
						  ?>
                              </tbody></table>							 
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