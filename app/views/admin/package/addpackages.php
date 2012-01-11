<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/datetimepicker.js"></script>
<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
	<div class="clsTop clsClearFixSub">
          <div class="clsNav">
          <ul>
           <li><a href="<?php echo admin_url('packages/addpackages');?>"><b><?php echo $this->lang->line('Add Package'); ?></b></a></li>
			<!--<li><a href="<?php echo admin_url('packages/searchpackage');?>"><b><?php echo $this->lang->line('Search Packages'); ?></b></a></li>-->
			<li class="clsNoBorder"><a href="<?php echo admin_url('packages/viewpackage');?>"><b><?php echo $this->lang->line('View Packages'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
            <h3><?php echo $this->lang->line('Add Package'); ?></h3>
        </div>
      </div>
	     <?php
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
	  	?>
		<form method="post" action="">
	  <table class="table1" cellpadding="2" cellspacing="0">
		<tbody>
      
               <tr>
            <td class="clsName"><?php echo $this->lang->line('Package Name');?> </td>
            <td class="clsMailIds">:
			<input name="package" type="text" id="username" value="<?php echo set_value('package'); ?>">
			<?php echo form_error('package'); ?>
		    </td>
          </tr>
         
          <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Description');?></span></strong></td>
            <td width="55%">:
                <textarea class="clsTextArea" name="description"><?php echo set_value('description'); ?></textarea>
				<?php echo form_error('description'); ?>
				
				</td>
				
          </tr>
		  <tr>
            <td width="25%"><?php echo $this->lang->line('From');?></td>
            <td width="55%">:
                <input type="Text" id="start_date" name="start_date" maxlength="25" size="25"><a href="javascript:NewCal('start_date','ddmmmyyyy')"><img src="<?php echo image_url("cal.gif");?>" width="16" height="16" border="0" alt="Pick a date"></a><?php echo form_error('start_date'); ?></td>
          </tr>
		  <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('To');?></span></strong></td>
            <td width="55%">:
                <input type="Text" id="end" name="end" maxlength="25" size="25"><a href="javascript:NewCal('end','ddmmmyyyy')"><img src="<?php echo image_url("cal.gif");?>" width="16" height="16" border="0" alt="Pick a date"></a><?php echo form_error('end'); ?>
				</td>
          </tr>
		  <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Active');?></span></strong></td>
            <td width="55%">:
            <select name="is_active">
		  	<option value="0" <?php echo set_select('is_active', '0', TRUE); ?>><?php echo $this->lang->line('No'); ?></option>
			<option value="1" <?php echo set_select('is_active', '1'); ?>><?php echo $this->lang->line('Yes'); ?></option>
		  </select>
          <?php echo form_error('is_active'); ?>
				</td>
          </tr>
		   <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('No.Of.days');?></span></strong></td>
            <td width="55%">:
			<select name="duration">
									<?php for($i=1;$i<=30;$i++){?>
									<option value="<?php echo $i;?>"><?php echo $i;?></option>
									<?php } ?>
									</select>&nbsp;<?php echo $this->lang->line('days');?>
               <!-- <input name="duration" type="text" class="textbox" id="duration" value="<?php echo set_value('No.Of.days'); ?>">-->
				<?php echo form_error('duration'); ?>
				</td>
          </tr>
		  <tr>
            <td width="25%"><strong><span id="valuen"><?php echo $this->lang->line('Amount');?></span></strong></td>
            <td width="55%">:
                <input name="amount" type="text" class="textbox" id="duration" value="<?php echo set_value('amount'); ?>">
				<?php echo form_error('duration'); ?>
				</td>
          </tr>
             <tr id="bansubmit" >
            <td></td>
            <td height="30" style="padding-left:6px;"><input name="addPackage" type="submit" class="clsSub" value="<?php echo $this->lang->line('Submit');?>">
			&nbsp;
            <input name="Reset" type="reset" class="clsSub" value="<?php echo $this->lang->line('Reset');?>">
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer'); ?>