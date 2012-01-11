<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<?php
	$mailnotifyid = explode(',',$mailnotifyid);
?>
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
                              
                             <h2><?php echo $this->lang->line('New Mail Notification'); ?></h2>
	                          <p><b><?php echo $this->lang->line('User Name'); ?> : </b><?php echo $loggedInUser->user_name; ?></p>
							   <table width="96%" cellspacing="1" cellpadding="2" class="clsSitelinks">
                                <tbody>
							    <tr>
								  <td width="10%" class="dt"><?php echo $this->lang->line('Sl.No'); ?></td>							  
								  <td width="20%" class="dt"><?php echo $this->lang->line('Creator Name'); ?></td>
								  <td width="25%" class="dt"><?php echo $this->lang->line('Project Name'); ?></td>
						          <td width="15%" class="dt"><?php echo $this->lang->line('Post Date'); ?></td>  
								  <td width="10%" class="dt">&nbsp;</td>
								</tr>  
						      <?php $i=0; $k=0; $j=0;
						     foreach($notifyData as $res)
							 {
								foreach($res as $rec)
								  { 
								   $i=$i+1; 
								  
								  if($i%2 == 0)
								    {
								    $class ="dt1 dt0";
									$class2 = "dt1";
									}
								  else
								    {
								    $class ="dt2 dt0";
									$class2 = "dt2";	
									}
									  $k=$k+1;
										?>
									  <tr class="<?php echo $class; ?>">
									  <td class="<?php echo $class2; ?>"><?php echo $k; ?></td>
									  <input type="hidden" name="projectid<?php echo $k; ?>" id="projectid<?php echo $k; ?>" value="<?php echo $mailnotifyid[$j]; $j=$j+1; ?>" />
									  
									  <td class="<?php echo $class2; ?>"><?php foreach($Users->result() as $user) if($user->id == $rec->from_id) echo $user->user_name;  ?></td>							  
									  <td class="<?php echo $class2; ?>"><?php foreach($projects->result() as $project) { if($project->id == $rec->project_id) {echo $project->project_name; } } ?></td>
									  <td class="<?php echo $class2; ?>"><?php echo get_datetime($rec->created); ?> </td>
									 
									 
									  <td class="<?php echo $class2; ?>">
									  
									  <div ><a href="javascript:;" onclick="check('<?php echo $k; ?>','projectid<?php echo $k; ?>');" name="show<?php echo $k; ?>" id="show<?php echo $k; ?>"><?php echo $this->lang->line('Show');?></a> &nbsp;&nbsp;
									   <a href="javascript:;" onclick="check1('<?php echo $k; ?>');" name="hide<?php echo $k; ?>" id="hide<?php echo $k; ?>"><?php echo $this->lang->line('Hide');?></a>
									   </div><div class="msg<?php echo $k; ?>" id="msg<?php echo $k; ?>" style="display:none;"><?php echo $rec->message; ?></div></td>
									   
									  </tr>  <?php 
								} 
							}		
								?>
								</tbody>	 	  
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
 <!--END OF PAGING-->
<!--END OF MAIN-->
<script type="text/javascript">
  function check(value,value1)
	{
	  //alert(value1);
	  id = document.getElementById(value1).value;
	  
	  ex2 = document.getElementById('msg'+value);
	 
	  if(ex2.style.display == 'none')
		  {
			ex2.style.display = 'block';
			load_user(id);
		  }
	}
	
	function check1(value)
	{
	  ex2 = document.getElementById('msg'+value);
	  if(ex2.style.display == 'block')
		  {
			ex2.style.display = 'none';
		  }
	}
	
  function load_user(value1)
   {
	
	var url = '<?php echo site_url('projectNotify/mailupdate');?>'+'/'+value1;
	
	new Ajax.Request(url,
	  {
		method:'get',
		onSuccess: function(transport){
		  var response = transport.responseText || "no response text";
		  
		  //document.getElementById('sBids').innerHTML = response
		},
		onFailure: function(){ alert('Something went wrong...') }
	  });
													
  } //Function load_category end
  </script>
 <?php $this->load->view('footer'); ?>