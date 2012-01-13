<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
	 <div class="clsTop clsClearFixSub">
          <div class="clsNav">
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('Edit Subscription User'); ?></h3>
        </div>
      </div>
		     <?php
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
			
			foreach($subscriptionuserList as $sub) 
		    {
			foreach($sub->result() as $sub_user)
			  { ?>
   
      <form method="post" action="<?php echo admin_url('packages/editsubscriptionuser'); ?>">
       <table class="table1" cellpadding="2" cellspacing="0">
		<tbody>
              <tr>
            <td class="clsName"><?php echo $this->lang->line('Username');?> </td>
            <td class="clsMailIds">:
			<input name="username[]" type="text" id="username" value="<?php echo $sub_user->username;  ?>">
		    </td>
          </tr>
      
           <tr>
            <td class="clsName"><?php echo $this->lang->line('Package Name');?> </td>
            <td class="clsMailIds">:
			 <select name="package_name[]"  class="usertype" id="from_usertype" >
		    <option value=""><?php echo $this->lang->line('choose package'); ?></option>
			<?php if(isset($packages) and $packages->num_rows()>0)
				{
					foreach($packages->result() as $packages)
					{
			   ?>
			  <option value="<?php echo $packages->id; ?>"<?php if($packages->id==$sub_user->package_id) echo 'selected="selected"';?> ><?php echo $packages->package_name; ?></option>
			 
			  <?php
			    }
			    
			  } ?>
		  </select>
			
		    </td>
          </tr>
           <tr>
            <td width="25%"><?php echo $this->lang->line('valid');?></td>
            <td width="55%">:
                <input type="Text" id="valid" name="valid[]" maxlength="25" size="25" value="<?php echo  $sub_user->valid; ?>"></td>
          </tr>
		  
		 <!-- <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Payment Type');?></span></strong></td>
            <td width="55%">:
            <select name="payment_type[]">
		  	<option value="<?php echo $this->lang->line('Pay Pal'); ?>"<?php if($sub_user->payment_type=="PayPal") echo 'selected="selected"'; ?>><?php echo $this->lang->line('Pay Pal'); ?></option>
			<option value="<?php echo $this->lang->line('Escrow'); ?>"<?php if($sub_user->payment_type=="Escrow") echo 'selected="selected"';?>><?php echo $this->lang->line('Escrow'); ?></option>
		  </select>
          
				</td>
          </tr>-->
		   <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Amount');?></span></strong></td>
            <td width="55%">:
                <input name="amount[]" type="text" class="textbox" id="duration[]" value="<?php echo $sub_user->amount;?>">
				<input name="subscriptionuser_id[]" type="hidden" value="<?php echo $sub_user->id;?>"/>
				</td>
				<?php }
				}?>
          </tr>
		  <tr id="bansubmit" >
            <td></td>
            <td height="30" style="padding-left:6px;"><input name="editsubscriptionuser" type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('Submit');?>">
            </td>
          </tr>
        </table>
      </form>
	 
    </div>
	<?php if(isset($pagination)) echo $pagination;?>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>
</div>
