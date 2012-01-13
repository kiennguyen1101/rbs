<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--START MAIN-->
<div id="main">
	  <!--POST PROJECT-->
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
                            <div class="clsInnerCommon clsFormSpan">
							
                             <?php echo $this->lang->line('contacting Reverse Bidding System'); ?>
							  <!--Contact page starting-->
							  
							  <div class="clsTopNavi">
							 <h2> <?php echo $this->lang->line('the RBS support desk'); ?></h2>
							 <?php $this->load->view('support/submenu'); ?></div>
							 <p align="right" class="clsLogouts"><a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout'); ?> </a>, &nbsp;&nbsp; <?php echo $loggedInUser->user_name;  ?></p>
							  <p><b><?php echo $this->lang->line('Welcome'); ?>, <?php echo $loggedInUser->user_name;  ?>!</b></p>
						  <p><?php echo $this->lang->line('All your recent support requests are listed below'); ?>. <a href="<?php echo site_url('support/postticket'); ?>"><?php echo $this->lang->line('click here'); ?>&nbsp;</a><?php echo $this->lang->line('to submit a new request') ?>.</p>
							 <!-- <p align="right">PAGE: [<a href="#">1</a>]</p>-->
							  <table cellspacing="1" cellpadding="5" width="97%" class="clsSupportDesk">
                                <tbody><tr>
                                  <td width="15%" class="dt"><?php echo $this->lang->line('Call ID'); ?></td>
                                  <td width="15%" class="dt"><?php echo $this->lang->line('Status'); ?></td>
                                  <td width="15%" class="dt"><?php echo $this->lang->line('Priority'); ?></td>
								  <td width="15%" class="dt"><?php echo $this->lang->line('Category'); ?></td>
								  <td width="20%" class="dt"><?php echo $this->lang->line('Subject'); ?></td>
								  <td width="20%" class="dt"><?php echo $this->lang->line('Comment'); ?></td>
                                </tr>
								<?php
								$i=0;
								if(isset($support) and $support->num_rows()>0)
								{
									foreach($support->result() as $support)
								{
								if($i==0)
								{
									$class='dt1';
									$i=1;
								}
								else
								{
									$class='dt2';
									$i=0;
								}
								?>
								
                                <tr>
                                  <td class="<?php echo $class;  ?>"><?php echo $support->callid; ?></td>
                                  <td class="<?php echo $class;  ?>"><?php if($support->status==0) { echo $this->lang->line('open');  }else{ echo $this->lang->line('close'); } ?></td>
                                  <td class="<?php echo $class;  ?>"> <?php if($support->priority==1) { 
			 echo  $this->lang->line('urgent');
			  }
			  elseif($support->priority==2) { 
			 echo $this->lang->line('high');
			  }
			   elseif($support->priority==3) { 
			 echo $this->lang->line('normal');
			  }
			    elseif($support->priority==4) { 
			 echo  $this->lang->line('low');
			  }elseif($support->priority==5) { 
			echo  $this->lang->line('very low');
			  }
				 ?></td>
                                  <td class="<?php echo $class; ?>"><?php if($support->category==1) { 
			 echo $this->lang->line('general');
			  }
			  elseif($support->category==2) { 
			 echo $this->lang->line('billing');
			  }
			   elseif($support->category==3) { 
			 echo $this->lang->line('suspended accounts');
			  }
			    elseif($support->category==4) { 
			echo  $this->lang->line('problems');
			  }elseif($support->category==5) { 
			 echo $this->lang->line('abuse');
			  }
				 ?></td>
								  <td class="<?php echo $class;  ?>"><?php  { echo $support->subject; } ?></td>
							<td class="<?php echo $class;  ?>"><?php  echo $support->description; ?></td>
                                </tr>
								<?php } }else{ ?>
                               <tr>
							   <td colspan="6" align="center">No Tickets Present!
							   </td>
							   </tr>
                            <?php }    ?>
                              </tbody></table>
							  <!--<p>Displaying 0-0 of 0 results</p>
							  <div class="clsAnnoun">
							  <p><b>Announcement</b></p>
							  <p>No Announcements Set</p>-->

							  <!--Contact page Ending-->							
							  
                            </div>
							<?php if(isset($pagination)) echo $pagination;?>
                          <div class="clsSearchOp">
							  <form name='frmsearch' method="post" action="<?php echo site_url('support'); ?>"> 
							  <p>Search <input class="tbox" type="text" value="<?php if(isset($setext)) { echo $setext; } ?>" size="10" name="setext"/> IN <select class="tbox" name="secondition">
							<option <?php if(isset($secondition) and $secondition=='callid' ) { echo 'SELECTED=SELECTED'; } ?> value="callid">Call ID</option>
							<option <?php if(isset($secondition) and $secondition=='subject' ) { echo 'SELECTED=SELECTED'; } ?> value="subject">Subject</option>
							<option <?php if(isset($secondition) and $secondition=='description' ) { echo 'SELECTED=SELECTED'; } ?> value="description">Comments</option>
							</select> <input class="clsMini" value="Search" height="16" border="0" type="submit" name="btnsearch" src="http://localhost/prabhu/rbs1.2/app/css/images/btn_go.gif"/>
							
							</p></form></div>
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
  </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>