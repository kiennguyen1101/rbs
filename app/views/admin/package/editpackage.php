<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
	 <div class="clsTop clsClearFixSub">
          <div class="clsNav">
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('editpackage'); ?></h3>
        </div>
      </div>
		     <?php
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
			
			foreach($packagelist->result() as $package) 
		    {
			?>
   
      <form method="post" action="<?php echo admin_url('packages/editPackage'); ?>">
       <table class="table1" cellpadding="2" cellspacing="0">
		<tbody>
      
               <tr>
            <td class="clsName"><?php echo $this->lang->line('Package Name');?> </td>
            <td class="clsMailIds">:
			<input name="package[]" type="text" id="package" value="<?php echo $package->package_name; ?>">
		    </td>
          </tr>
        
          <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Description');?></span></strong></td>
            <td width="55%">:
                <textarea class="clsTextArea" name="description[]"><?php echo $package->description;?></textarea>
				</td>	
          </tr>
		  <tr>
            <td width="25%"><?php echo $this->lang->line('From');?></td>
            <td width="55%">:
                <input type="Text" id="start_date" name="start_date[]" maxlength="25" size="25" value="<?php echo date('d-M-Y',$package->start_date);   ?>"><a href="javascript:NewCal('start_date','ddmmmyyyy')"><img src="<?php echo image_url("cal.gif");?>" width="16" height="16" border="0" alt="Pick a date"></a></td>
          </tr>
		  <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('To');?></span></strong></td>
            <td width="55%">:
                <input type="Text" id="end" name="end[]" maxlength="25" size="25" value="<?php echo date('d-M-Y',$package->end_date);   ?>"><a href="javascript:NewCal('end','ddmmmyyyy')"><img src="<?php echo image_url("cal.gif");?>" width="16" height="16" border="0" alt="Pick a date"></a>
				</td>
          </tr>
		  <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Active');?></span></strong></td>
            <td width="55%">:
            <select name="is_active[]">
		  	<option value="0" <?php if($package->isactive==0)  echo "selected";  ?>><?php echo $this->lang->line('No'); ?></option>
			<option value="1" <?php if($package->isactive==1)  echo "selected"; ?>><?php echo $this->lang->line('Yes'); ?></option>
		  </select>
          
				</td>
          </tr>
		   <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('No.Of.days');?></span></strong></td>
            <td width="55%">:
			<select name="duration[]">
									<?php for($i=1;$i<=30;$i++){?>
									<option value="<?php echo $i;?>" <?php if(isset($package->total_days)) { if($package->total_days == $i) echo "selected"; }?>><?php echo $i;?></option>
									<?php } ?>
									</select>
               <!-- <input name="duration[]" type="text" class="textbox" id="duration" value="<?php  echo $package->total_days;  ?>">-->
				</td>
				
          </tr>
		  <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Amount');?></span></strong></td>
            <td width="55%">:
                <input name="amount[]" type="text" class="textbox" id="amount" value="<?php  echo $package->amount;  ?>">
				</td>
				<input type="hidden" name="packageid[]" value="<?php echo $package->id;?>" />
          </tr><?php }
		  ?>
          <tr id="bansubmit" >
            <td></td>
            <td height="30" style="padding-left:6px;"><input name="editpackage" type="submit" class="clsSubmitBt1" value="<?php echo $this->lang->line('Submit');?>">
			
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
