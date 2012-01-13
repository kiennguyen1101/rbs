<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
      <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}		
	  ?>
      <div class="clsNav">
        <ul>
          <li><a href="<?php echo admin_url('projectCases/viewCases');?>"><b><?php echo $this->lang->line('go back'); ?></b></a></li>
        </ul>
      </div>
      <div class="clsTitle">
        <h3><?php echo $this->lang->line('case details'); ?></h3>
      </div>
	   <?php
	  	//Content of a group
		if(isset($projectCase) and $projectCase->num_rows()>0)
		{
			$projectCase = $projectCase->row();
			//echo $projectCase->created;exit;
	  ?>
	  <table class="table" cellpadding="2" cellspacing="0">
	  <tr><td><?php echo $this->lang->line('project_title'); ?></td><td><a href="<?php echo admin_url('skills/projectDeatils/'.$projectCase->project_id);?>"><?php echo $projectCase->project_name;?></a></td></tr>
	  <tr><td><?php echo $this->lang->line('project_id'); ?></td><td><?php echo $projectCase->project_id;?></td></tr>
	  <tr><td><?php echo $this->lang->line('Buyer'); ?></td><td><a href="<?php echo admin_url('users/userDetails/'.$projectCase->creator_id);?>"><?php echo getUserDetails($projectCase->creator_id,'user_name');?></a></td></tr>
	  <tr><td><?php echo $this->lang->line('Provider'); ?></td><td><a href="<?php echo admin_url('users/userDetails/'.$projectCase->programmer_id);?>"><?php echo getUserDetails($projectCase->programmer_id,'user_name');?></a></td></tr>
	  <tr><td><?php echo $this->lang->line('Case Type');?></td><td></span><?php echo $projectCase->case_type;?></td></tr>
	  <tr><td><?php echo $this->lang->line('Case Reason');?></td><td></span><?php echo $projectCase->case_reason;?></td></tr>
	  <tr><td><?php echo $this->lang->line('payment_requested')?></td><td></span>$<?php echo $projectCase->payment;?></td></tr>
	  <tr><td><?php echo "<b>".getUserDetails($projectCase->user_id,'user_name')."</b>&nbsp;".$this->lang->line('wants reviews to be');?></td><td></span><?php echo $projectCase->review_type;if($numReviews != 0) {?> (<a href="javascript:;" onclick="viewReview()"><?php echo $this->lang->line('View Review');?></a>) <?php } ?></td></tr>
	  <tr style="display:none; background-color:#CCCCCC;" id="show_review"><td colspan="2"><?php echo $this->lang->line('Review on');?> <b>
	  <?php echo getUserDetails($projectCase->user_id,'user_name');?></b> &raquo; <?php echo $userReviews->comments;?>
	  </td></tr>
	  </table>
	  <div class="clsTitle">
        <h3><?php echo $this->lang->line('Resolution board comments'); ?></h3>
      </div>
     
      <table class="table" cellpadding="2" cellspacing="0">
        <tr>
          <th><?php echo $this->lang->line('Author');?></th>
          <th><?php echo $this->lang->line('Comment');?></th>
        </tr>
        <tr class="dt2 dt0">
		
	 <td><?php echo getUserDetails($projectCase->user_id,'user_name')."<br><br>".get_date($projectCase->created)."<br><br>".dispute_time_left($projectCase->created,$this->config->item('dispute_respond_time'));?></td>
          <td><?php echo str_replace("\n", "<br>",$projectCase->problem_description);
			  if($projectCase->private_comments != "")
			  echo "<br><br><b>Private:<br>".str_replace("\n", "<br>",$projectCase->private_comments)."</b>"; ?></td>
        </tr>
        <?php
			if(isset($caseResolution) and $caseResolution->num_rows()>0)
			{
				$i=0;
				foreach($caseResolution->result() as $caseResolution)
				{
					if($i%2==0)
						$class = 'dt1 dt0';
					else 
						$class = 'dt2 dt0';	
					?>
        <tr class="<?php echo $class; ?>">
		

          <td><?php echo getUserDetails($caseResolution->user_id,'user_name')."<br><br>".get_date($caseResolution->created)."<br><br>".dispute_time_left($caseResolution->created,$this->config->item('dispute_respond_time'));?></td>
          <td><?php
		  if($caseResolution->problem_description != "")
		  echo str_replace("\n", "<br>",$caseResolution->problem_description);
		  if($caseResolution->private_comments != "")
		  echo "<br><br><b>Private:<br>".str_replace("\n", "<br>",$caseResolution->private_comments)."</b>";
		  if($caseResolution->updates != "")
		  echo "<b>".$this->lang->line('update')."</b>:".$caseResolution->updates;
		  ?></td>
        </tr>
        <?php		
				$i++;						
				}//For Each End
		}//If - End Check
		  ?>
      </table>
	  <div class="clsTitle">
        <h3><?php echo $this->lang->line('Options'); ?></h3>
      </div>
	  <table class="table">
		  <tr><td><?php echo $this->lang->line('Change the Case type');?></td><td>
		  <?php echo $this->lang->line('Cancel');?> 
		  <input type="radio" value="Cancel" class="clsRadioBut" <?php if($projectCase->case_type == 'Cancel')  echo 'checked="checked"'; ?> id="type" name="type"/> 
		  <?php echo $this->lang->line('Dispute');?> 
		  <input type="radio" value="Dispute" class="clsRadioBut" <?php if($projectCase->case_type == 'Dispute')  echo 'checked="checked"'; ?> id="type" name="type"/>
		  &nbsp;<input type="button" value="Click" class="clsSubmitBt1" onclick="changeCaseType(<?php echo $projectCase->id; ?>)"/>&nbsp;<div id="cstype" style="color:#FF0000;"></div>
		  </td></tr>
		  <tr><td><?php echo $this->lang->line('Change the Case status');?></td><td>
		  <?php echo $this->lang->line('Open');
		  ?> 
		  <input type="radio" value="open" class="clsRadioBut" <?php if($projectCase->status == 'open')  echo 'checked="checked"'; ?> name="status" id="status"/> 
		  <?php echo $this->lang->line('Close');?> 
		  <input type="radio" value="closed" class="clsRadioBut" <?php if($projectCase->status == 'closed')  echo 'checked="checked"'; ?> name="status" id="status"/>&nbsp;<input type="button" value="Click" class="clsSubmitBt1" onclick="changeCaseStatus(<?php echo $projectCase->id; ?>)"/>&nbsp;<div id="cstatus" style="color:#FF0000;"></div></td></tr>
		  <tr><td><?php echo $this->lang->line('Cancel the project');?></td><td><input type="button" value="Click" class="clsSubmitBt1" onclick="cancelProject(<?php echo $projectCase->project_id; ?>,<?php echo $projectCase->id; ?>)"/>&nbsp;<div id="cancel_project" style="color:#FF0000;"></div></td></tr>
		  <?php //if($projectCase->review_type == 'Remove review'){?>
		  <tr><td><?php echo $this->lang->line('Remove review');?> <b><?php echo getUserDetails($projectCase->user_id,'user_name');?></b></td><td>
		  <?php if(getUserDetails($projectCase->user_id,'role_id') == '1'){?>
		  <input type="button" value="<?php echo $this->lang->line('delete');?>" class="clsSubmitBt1" onclick="removeReview('1',<?php echo $projectCase->project_id; ?>,<?php echo $projectCase->creator_id; ?>)"/><?php } if(getUserDetails($projectCase->user_id,'role_id') == '2'){ ?>
		  <input type="button" value="<?php echo $this->lang->line('delete');?>" class="clsSubmitBt1" onclick="removeReview('2',<?php echo $projectCase->project_id; ?>,<?php echo $projectCase->programmer_id; ?>)"/><?php } ?>&nbsp;
		  <div id="rremove_review" style="color:#FF0000;"></div></td></tr>
		  <?php //} ?>
	  </table>
      <?php
	  }
	  ?>
    </div>
  </div>
  <!-- End of clsSettings -->
</div>

<!-- End Of Main -->
<script type="text/javascript" >
function changeCaseType(caseid){
	if(confirm('Are you sure want to change the type of this case??')){
	document.getElementById('cstype').innerHTML = 'Loading...'
	var ctype = getRadioValue('type');
	new Ajax.Request('<?php echo admin_url().'/projectCases/changeCaseType/'; ?>'+caseid+'/'+ctype,

  {

    method:'get',

    onSuccess: function(transport){

      var response = transport.responseText || "no response text";

      document.getElementById('cstype').innerHTML = response
	  window.location.href = '<?php echo admin_url().'/projectCases/view/'; ?>'+caseid;

    },

    onFailure: function(){ alert('Something went wrong...') }

  });
  }
}
function changeCaseStatus(caseid){
	if(confirm('Are you sure want to change the status of this case??')){
	document.getElementById('cstatus').innerHTML = 'Loading...'
	var status = getRadioValue('status');
	new Ajax.Request('<?php echo admin_url().'/projectCases/changeCaseStatus/'; ?>'+caseid+'/'+status,

  {

    method:'get',

    onSuccess: function(transport){

      var response = transport.responseText || "no response text";

      document.getElementById('cstatus').innerHTML = response
	  window.location.href = '<?php echo admin_url().'/projectCases/view/'; ?>'+caseid;

    },

    onFailure: function(){ alert('Something went wrong...') }

  });
  }
}
function cancelProject(project_id,caseid){
	
	
	
	if(confirm('Are you sure want to cancel this project??')){
	document.getElementById('cancel_project').innerHTML = 'Loading...'
	new Ajax.Request('<?php echo admin_url().'/projectCases/cancelProject/'; ?>'+project_id+'/'+caseid,
   {

    method:'get',

    onSuccess: function(transport){

      var response = transport.responseText || "no response text";
		//alert(response)
      document.getElementById('cancel_project').innerHTML = response
	  window.location.href = '<?php echo admin_url().'/projectCases/view/'; ?>'+caseid;

    },

    onFailure: function(){ alert('Something went wrong...') }

  });
  }
}
function removeReview(usertype,project_id,userid){
	if(confirm('Are you sure want to remove the review??')){
	document.getElementById('rremove_review').innerHTML = 'Loading...'
	new Ajax.Request('<?php echo admin_url().'/projectCases/removeReview/'; ?>'+project_id+'/'+usertype+'/'+userid,
   {

    method:'get',

    onSuccess: function(transport){

      var response = transport.responseText || "no response text";

      document.getElementById('rremove_review').innerHTML = response
	  
	  window.location.href = '<?php echo admin_url().'/projectCases/view/'; ?>'+caseid;

    },

    onFailure: function(){ alert('Something went wrong...') }

  });
  }
}

function getRadioValue(type)
{
    for (var i = 0; i < document.getElementsByName(type).length; i++)
    {
        if (document.getElementsByName(type)[i].checked)
        {
                return document.getElementsByName(type)[i].value;
        }
    }
}
function viewReview(){
	document.getElementById('show_review').style.display = '';
}
  </script>
<?php $this->load->view('admin/footer'); ?>
