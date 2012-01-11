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
                              <h2><?php echo $title; ?></h2>
						       <p><b><?php echo $this->lang->line('User Name'); ?> : </b><?php echo $loggedInUser->user_name; ?></p>
							   <table>
		 					  	<tr>
							      <td width="10%" class="dt"><?php echo $this->lang->line('Sl.No'); ?></td>								  
								  <td width="20%" class="dt"><?php echo $this->lang->line('Creator Name'); ?></td>								  
								  <td width="20%" class="dt"><?php echo $this->lang->line('Project Name'); ?></td>
								  <td width="10%" class="dt"><?php echo $this->lang->line('Budget'); ?></td>
						          <td width="15%" class="dt"><?php echo $this->lang->line('Post Date'); ?></td>
							      <td width="10%" class="dt"><?php echo $this->lang->line('Status'); ?></td><?php 
								  if(!isset($invitation) and isset($awards) ) 
									  { ?>	
								 <!-- <td width="10%" class="dt">&nbsp;</td>--> <?php } ?>
								</tr>
						      <?php $i=1; $k=0; 
						     foreach($notifyData as $res)
							 {
								//pr($res);
								foreach($res as $rec)
								  { $i=$i+1; 
								  
								  if($i%2 == 0)
								    {
								    $class ="dt1 dt0";
									$class2 = "dt2";
									}
								  else
								    {
								    $class ="dt2 dt0";	
									$class2 = "dt1";
									}
									  $k=$k+1;
										?>
									  
									  <td class="<?php echo $class2; ?>"><?php echo $k; ?></td>
									  <td class="<?php echo $class2; ?>"><?php foreach($Users->result() as $user) { if($user->id == $rec->creator_id) { ?><a href="<?php echo site_url('buyer/viewProfile/'.$user->id); ?>"> <?php  echo $user->user_name; ?></a><?php  } } ?></td>	
									  <td class="<?php echo $class2; ?>"><a href="<?php echo site_url('project/view/'.$rec->id); ?>" onclick="check1('<?php echo $k; ?>');" id="show<?php echo $k; ?>"><?php echo $rec->project_name; ?></a></td>
									  <td class="<?php echo $class2; ?>"><?php echo '$'. $rec->budget_min.' - $'.$rec->budget_max;?></td>
									  <td class="<?php echo $class2; ?>"><?php echo get_datetime($rec->created); ?></td>
									  <td class="<?php echo $class2; ?> etopsp"><?php if($rec->project_status == '0') echo '<b style="color:green;">'.'Open'.'</b>'; if($rec->project_status == '2') echo '<b style="color:red;">'.'Closed'.'</b>'  ?><?php 
									  //Only for project Awards notification
									  if(!isset($invitation) and isset($awards) ) 
									  { ?>	      
									     <a href="<?php echo site_url('project/acceptProject/'.$rec->id.'/'.$rec->checkstamp); ?>"> <?php echo $this->lang->line('Accept'); ?></a>  
									     <a href="<?php echo site_url('project/denyProject/'.$rec->id.'/'.$rec->checkstamp); ?>"> <?php echo $this->lang->line('Denied'); ?></a> <?php
									  } ?>
									  
									 </td></tr>  <?php
									  

								} 
							}		
								?>	 	  
							</table>
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
	 <!--PAGING-->
	  	<?php if(isset($pagination_inbox)) echo $pagination_inbox;?>
	 <!--END OF PAGING-->
 
<!--END OF MAIN-->
<script type="text/javascript">
  function check(value,value1)
	{
	  id = document.getElementById(value1).value;
	  ex2 = document.getElementById('msg'+value);
	 load_user(id);
	}
	
  function load_user(value1)
   {
	
	var url = '<?php echo site_url('projectNotify/invitationupdate');?>'+'/'+value1;
	
	new Ajax.Request(url,
	  {
		method:'get',
		onSuccess: function(transport){
		  var response = transport.responseText || "no response text";
		  alert();
		},
		onFailure: function(){ alert('Something went wrong...') }
	  });
													
  } //Function load_category end
  </script>
<?php $this->load->view('footer'); ?>