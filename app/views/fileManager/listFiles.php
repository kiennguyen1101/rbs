<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
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
                              <h3><span class="clsFileManager"><?php echo $this->lang->line('File Manager'); ?></span></h3>
							   
                              <p class="clsSitelinks"><?php echo $this->lang->line('You are currently logged in as');?> <a class="glow" href="<?php if($loggedInUser->role_id == '1') $res = 'buyer'; else $res = 'seller'; echo site_url($res.'/viewprofile/'.$loggedInUser->id); ?>"><?php if(isset($loggedInUser) and is_object($loggedInUser))  echo $loggedInUser->user_name;?></a>
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

							   <?php echo $this->lang->line('(');?><a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout') ?></a><?php echo $this->lang->line(').');?></p><br />
							  <p><?php //echo $this->lang->line('note'); ?></p>
							  <form method="post" action="<?php echo site_url('file/delete');?>" name="frmFile" onsubmit="return ConfirmDelete()">
                              <table cellspacing="1" cellpadding="2" width="94%">
                                <tbody>
                                  <tr>
								    <td width="3%" class="dt"></td>
                                    <td width="10%" class="dt"><?php echo $this->lang->line('File type'); ?></td>
                                    <td width="10%" class="dt"><?php echo $this->lang->line('Size'); ?></td>
                                    <td width="10%" class="dt"><?php echo $this->lang->line('Expiery'); ?></td>
									<td width="20%" class="dt"><?php echo 'Description'; ?></td>
									<td width="25%"class="dt"><?php echo 'File' ?></td>
                                  </tr> 
									
									<?php $filesize = 0; $i=0;
									  foreach($fileInfo->result() as $fileDate)
										  { $i++; 
										  if($i%2==0)
										    {
										    $class = 'dt1 dt0';
											$class2 = 'dt1';
											}
										  else
										    {
										    $class = 'dt2 dt0'; 	
											$class2 = 'dt2';
											}
										   ?>
										<tr class="<?php echo $class; ?>"><td class="<?php echo $class2; ?>"><input name="chkFile[]" type="checkbox" value="<?php echo $fileDate->id; ?>"/></td> 
										<td class="<?php echo $class2; ?>"><a href="<?php echo site_url('?c=file&m=view&key='); ?><?php echo $fileDate->key;?>"><?php echo $fileDate->original_name ; ?></a></td>
										<td class="<?php echo $class2; ?>"><?php echo $fileDate->file_size.'KB'; $filesize += $fileDate->file_size;?></li>
										<td class="<?php echo $class2; ?>">Expiery</td>
										<td class="<?php echo $class2; ?>"><?php echo $fileDate->description; ?></td>
										<td class="<?php echo $class2; ?>"><?php echo site_url('?c=file&m=download&key=');?><?php echo $fileDate->key ; ?></td></tr>
										<? } ?>	
																        						
								  
                                </tbody>
                              </table>
							  <p><input value="<?php echo $this->lang->line('Remove');?>" name="files_upload_remove" class="clsSmall" type="submit"/></p> 
							  </form>									
                              <p><small><?php echo $this->lang->line('info'); ?> <?php echo round($filesize/1024,2);?> <?php echo $this->lang->line('info1');?> <?php echo $maximum_size.' MB'; ?></small></p>
                               <div class="clsUpload">
							  <form method="post" action="<?php echo site_url('file') ;  ?>" enctype="multipart/form-data">
							  <p>
							  <label>
							  <b><?php echo $this->lang->line('Upload File'); ?></b>
							  </label><input type="file" name="attachment" id="attachment" value="<?php echo set_value('attachment'); ?>" />
							  <!--(Allowed Types : JPEG, JPG, GIF, PNG )-->
							  <div id="attachFile" name="attachFile" style="display:none; color:red;">
								 <?php echo $this->lang->line('Upload file is needed'); ?>
							  </div>
							  <?php echo form_error('attachment'); ?>
							  </p>
							  
							   <p>
							   <label>
							   <b><?php echo $this->lang->line('desc');?></b>
							   </label><input name="files_desc" size="40" type="text" value="<?php echo set_value('files_desc'); ?>"/>
								<?php echo form_error('files_desc'); ?>
							   </p>
							   <p><label>&nbsp;</label><input class="clsSmall" value="<?php echo $this->lang->line('Upload Button'); ?>" name="uploadFile" type="submit" onclick="javascript:return formValidation()"/></p></form>
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
<!--END OF MAIN-->
<!-- script -->
<script type="text/javascript">
function ConfirmDelete() {
	
	var intSelectedNews = 0;
	for (intCounter = 0;intCounter < document.frmFile.length;intCounter++) {
		if (document.frmFile.elements[intCounter].type == 'checkbox' && document.frmFile.elements[intCounter].name != 'All' && document.frmFile.elements[intCounter].checked)
			++intSelectedNews;
	}
	if (intSelectedNews) {
		if (confirm ('Are you sure to delete the '+intSelectedNews+' news item(s) selected..!'))
			return true;
	} else alert ('Atleast 1 item should be selected for deletion.')
	return false
	}
function formValidation()
{
	if ($('attachment').value == '')
	  {
	  	e = $('attachFile');
		if(e.style.display == "none")
		{ //DynamicDrive.com change
		  
		   e.style.display = "block";
		   return false;
		//alert(el.style.display);
		}
		else
		{
		  e.style.display = "none";
		   return false;
		}
	  }
	
}
</script>
<?php $this->load->view('footer'); ?>
