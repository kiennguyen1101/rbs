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
      </div>
      <h3><?php echo $this->lang->line('Suspend User'); ?></h3>
    <form method="post" action="">
	 <table class="table1" cellpadding="2" cellspacing="0">
		<tbody>           
          <tr>
            <td  class="clsName"><?php echo $this->lang->line('Suspend Type');?> </td>
            <td class="clsMailIds">:
                <select name="type" class="textbox" style="width:60%;" onChange="bantype(this);">
				  <option value="">Select Type</option>
                  <option value="EMAIL">Email Address</option>
				  <option value="USERNAME">Username</option>
                </select></td>
          </tr>
          <tr id="baninfo" style="display:none;">
            <td width="25%"><?php echo $this->lang->line('Suspend Info');?></td>
            <td width="55%">:
                <span id="baninfotxt"></span></td>
          </tr>
          <tr id="bandetails" style="display:none;">
            <td width="25%"><span id="valuen"><?php echo $this->lang->line('Suspend Value');?></span></td>
            <td width="55%">:
                <input name="value" type="text" class="textbox" id="value" style="width:60%" value=""></td>
				<?php echo form_error('value'); ?></p>
          </tr>
          
          <tr id="bansubmit" style="display:none;">
            <td></td>
            <td height="30" style="padding-left:6px;"><input name="addBan" type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('Submit');?>">
&nbsp;
            <input name="Reset" type="reset" class="clsSubmitBt1" value="<?php echo $this->lang->line('Reset');?>">
            </td>
          </tr>
        </table>
        </p>
      </form>
    </div>
  </div>
</div>
<script>
function bantype(btype)
{
	if(btype.value == '')
	{
		document.getElementById('valuen').innerHTML = '';
		document.getElementById('bandetails').style.display = 'none';
		document.getElementById('bansubmit').style.display = 'none';
		document.getElementById('baninfo').style.display = 'none';
	}
	else
	{
		if(btype.value == 'EMAIL')
		{
			document.getElementById('valuen').innerHTML = 'Email Address';
			document.getElementById('baninfotxt').innerHTML = 'Any user who signup with this email address will be told they can\'t bid to other projects, post to PMB or receive money';
		}
		else if(btype.value == 'IP')
		{
			document.getElementById('valuen').innerHTML = 'IP Address';
			document.getElementById('baninfotxt').innerHTML = 'Any user trying to login with this email address will be told they can\'t bid to other projects, post to PMB or receive money';
		}
		else if(btype.value == 'USERNAME')
		{
			document.getElementById('valuen').innerHTML = 'Username';
			document.getElementById('baninfotxt').innerHTML = 'Any user trying to login with this email address will be told they can\'t bid to other projects, post to PMB or receive money';
		}
		else if(btype.value == 'PAYPAL')
		{
			document.getElementById('valuen').innerHTML = 'Paypal Address';
			document.getElementById('baninfotxt').innerHTML = 'Any user trying to add this paypal address will be told they can\'t use it';
		}
		
		document.getElementById('bandetails').style.display = '';
		document.getElementById('bansubmit').style.display = '';
		document.getElementById('baninfo').style.display = '';
	}
}
</script>
<?php $this->load->view('admin/footer'); ?>