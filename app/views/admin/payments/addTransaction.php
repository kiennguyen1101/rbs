<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
	  <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('payments/searchTransaction');?>"><b><?php echo $this->lang->line('Search'); ?></b></a></li>
			<li><a href="<?php echo admin_url('payments/addTransaction');?>"><b><?php echo $this->lang->line('Add Transaction'); ?></b></a></li>
			<!--<li><a href="<?php echo admin_url('payments/releaseEscrow');?>"><b><?php echo $this->lang->line('Escrow Release'); ?></b></a></li>-->
			<li class="clsNoBorder"><a href="<?php echo admin_url('payments/viewTransaction');?>"><b><?php echo $this->lang->line('View All'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
	 <h3><?php echo $this->lang->line('Add Transaction'); ?></h3>
	 </div>
   	 
      <?php 
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	  ?>
     	  <table class="table" cellpadding="2" cellspacing="0">
	  <form method="post" action="<?php echo admin_url('payments/addTransaction')?>">
           <tr><td class="clsName">
          <?php echo $this->lang->line('Transaction Description'); ?><span class="clsRed">*</span></td>
		  <td class="clsMailId">
          <textarea class="clsTextArea" name="transactionDescription"><?php if(set_value('transactionDescription')) echo set_value('transactionDescription');if(isset($transactionDescription)) echo $transactionDescription; ?></textarea>
          <?php echo form_error('transactionDescription'); ?></td>
		
		<tr><td class="clsName">
         <?php echo $this->lang->line('from usertype'); ?><span class="clsRed">*</span></td><td>

           <select name="from_usertype"  class="usertype" id="from_usertype"  onchange="javascript:return loadFromuser(this.value);">
		    <option value=""><?php echo $this->lang->line('choose usertype'); ?></option>
			<?php foreach($roles as $res)
			  { ?>
			  <option value="<?php echo $res->id;?>" <?php if(set_value('from_usertype') == $res->id ) echo "selected"; if(isset($from_usertype)) { if( $from_usertype == $res->id ) echo "selected"; }?> ><?php echo $res->role_name; ?></option>
			  <?php 
			  } ?>
		  </select>
          <?php echo form_error('from_usertype'); ?></td></tr>
		
		<tr><td class="clsName">
         <?php echo $this->lang->line('from username'); ?><span class="clsRed">*</span></td><td>
          <!--<select name="from_username" class="usertype" id="from_username" onfocus="javascript:loadFromuser()">
		    <option value="" ><?php echo $this->lang->line('choose username'); ?></option>
		  </select>-->
		<b id="uname"> 
		 <select name="from_username" class="usertype" id="from_username" >
		      </select> </b>
		  <!--<div id="uname">User names will load here</div>-->
          <?php echo form_error('from_username'); ?> </td></tr>

        <tr>
          <td class="clsName"><?php echo $this->lang->line('to usertype'); ?><span class="clsRed">*</span></td><td>
          <select name="to_usertype" class="usertype" id="to_usertype" onchange="javascript:return loadTouser();">
		    <option value="0"><?php echo $this->lang->line('choose usertype'); ?></option>
			<?php foreach($roles as $res)
			  { ?>
			  <option value="<?php echo $res->id; ?>" <?php if(set_value('to_usertype') == $res->id ) echo "selected" ?> ><?php echo $res->role_name; ?></option>
			  <?php 
			  } ?>
		  </select>
          <?php echo form_error('to_usertype'); ?> </td></tr>
		  <tr>
		 
          <td class="clsName"><?php echo $this->lang->line('to username'); ?><span class="clsRed">*</span></td><td>
         <b id="uname1">  
		 <select name="to_username" class="usertype" id="to_username" >
		 
			  </select> </b>
		  
          <?php echo form_error('to_username'); ?></td> </td></tr>		  	  
		<tr>
          <td class="clsName"><?php echo $this->lang->line('Transaction Amount'); ?><span class="clsRed">*</span></td><td>
          <input class="clsTextBox" type="text" name="amount" value="<?php echo set_value('amount'); ?>"  size="10"/>
          <?php echo form_error('amount'); ?> </td></tr>		  
        <tr><td></td><td>
          <input class="clsSubmitBt1" value="<?php echo $this->lang->line('submit'); ?>" name="addTransaction" type="submit" >
		  <input class="clsSubmitBt1" value="<?php echo $this->lang->line('reset'); ?>" name="resetTransaction" onclick="javascript:ClearOptions('from_username');ClearOptions('to_username');" type="reset">
        </td></tr>
      </form>
	  </table>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>


<script type="text/javascript">
function ClearOptions(id)
{
	document.getElementById(id).options.length = 0;
}

function loadFromuser(utype)
{

var utype = document.getElementById('from_usertype').value
new Ajax.Request('<?php echo base_url()."index.php/".$this->config->item('admin_controllers_folder').'/payments/load_users/';?>'+utype,
  {
    method:'get',
    onSuccess: function(transport){
      var response = transport.responseText || "no response text";
	  document.getElementById('from_username').innerHTML = response
    },
    onFailure: function(){ alert('Something went wrong...') }
  });
} //Ajax funciton end here

function loadTouser()
{
    var utype = document.getElementById('to_usertype').value
   new Ajax.Request('<?php echo base_url()."index.php/".$this->config->item('admin_controllers_folder').'/payments/load_users1/';?>'+utype,
  {
    method:'get',
    onSuccess: function(transport){
      var response = transport.responseText || "no response text";
	  document.getElementById('to_username').innerHTML = response
    },
    onFailure: function(){ alert('Something went wrong...') }
  });

} //Ajax funciton end here
</script>
