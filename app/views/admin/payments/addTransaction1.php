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
      <h3><?php echo $this->lang->line('Add Transaction'); ?></h3>
      <form method="post" action="<?php echo admin_url('payments/addTransaction')?>">
           <p class="clsClearFixSub">
          <label><?php echo $this->lang->line('Transaction Description'); ?><span class="clsRed">*</span></label>
          <textarea class="clsTextArea" name="transactionDescription"><?php if(set_value('transactionDescription')) echo set_value('transactionDescription');if(isset($transactionDescription)) echo $transactionDescription; ?></textarea>
          <?php echo form_error('transactionDescription'); ?></p>
		
		<p class="clsClearFixSub">
          <label><?php echo $this->lang->line('from usertype'); ?><span class="clsRed">*</span></label>
          
		  <select name="from_usertype" id="from_usertype" onchange="javascript:return loadFromuser();">
		    <option value=""><?php echo $this->lang->line('choose usertype'); ?></option>
			<?php foreach($roles as $res)
			  { ?>
			  <option value="<?php echo $res->id;?>" <?php if(set_value('from_usertype') == $res->id ) echo "selected"; if(isset($from_usertype)) { if( $from_usertype == $res->id ) echo "selected"; }?> ><?php echo $res->role_name; ?></option>
			  <?php 
			  } ?>
		  </select>
          <?php echo form_error('from_usertype'); ?></p>
		
		<p>
          <label><?php echo $this->lang->line('from username'); ?><span class="clsRed">*</span></label>
          <select name="from_username" id="from_username" onfocus="javascript:return loadFromuser()">
		    <option value="" ><?php echo $this->lang->line('choose username'); ?></option>
		  </select>
          <?php echo form_error('from_username'); ?> </p>

        <p>
          <label><?php echo $this->lang->line('to usertype'); ?><span class="clsRed">*</span></label>
          <select name="to_usertype" id="to_usertype" onchange="javascript:return loadTouser();">
		    <option value=""><?php echo $this->lang->line('choose usertype'); ?></option>
			<?php foreach($roles as $res)
			  { ?>
			  <option value="<?php echo $res->id; ?>" <?php if(set_value('to_usertype') == $res->id ) echo "selected" ?> ><?php echo $res->role_name; ?></option>
			  <?php 
			  } ?>
		  </select>
          <?php echo form_error('to_usertype'); ?> </p>
		  
		<p>
          <label><?php echo $this->lang->line('to username'); ?><span class="clsRed">*</span></label>
         <select name="to_username" id="to_username" onfocus="javascript:return loadTouser()">
		    <option value=""><?php echo $this->lang->line('choose username'); ?></option>
		  </select>
          <?php echo form_error('to_username'); ?> </p>
		  
		<p>
          <label><?php echo $this->lang->line('Transaction Amount'); ?><span class="clsRed">*</span></label>
          <input class="clsTextBox" type="text" name="amount" value="<?php echo set_value('amount'); ?>"  size="10"/>
          <?php echo form_error('amount'); ?> </p>    
		  
        <p class="clsSubmitBlock">
          <input class="clsSubmitBt1" value="<?php echo $this->lang->line('submit'); ?>" name="addTransaction" type="submit" >
		  <input class="clsSubmitBt1" value="<?php echo $this->lang->line('reset'); ?>" name="resetTransaction" type="reset" >
        </p>
      </form>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>


<script type="text/javascript">

function  
{
	var url = '<?php echo admin_url('payments/load_users');?>';
	//alert($('from_usertype').value);
	new Ajax.Updater('from_username', url,   {  method     : 'post',
	  parameters : { from_usertype : $('from_usertype').value },
	 
}); //Ajax Object Creation End
} //Ajax funciton end here

function loadTouser()
{
	var url = '<?php echo admin_url('payments/load_users');?>';
	//alert($('from_usertype').value);
	new Ajax.Updater('to_username', url,   {  method     : 'post',
	  parameters : { from_usertype : $('to_usertype').value },
	  
}); //Ajax Object Creation End
} //Ajax funciton end here
</script>
