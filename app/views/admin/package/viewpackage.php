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
		<div class="clsTop clsClearFixSub">
          <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('packages/addpackages');?>"><b><?php echo $this->lang->line('Add Package'); ?></b></a></li>
			<!--<li><a href="<?php echo admin_url('packages/searchpackage');?>"><b><?php echo $this->lang->line('Search Packages'); ?></b></a></li>-->
			<li class="clsNoBorder"><a href="<?php echo admin_url('packages/viewpackage');?>"><b><?php echo $this->lang->line('View Packages'); ?></b></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('View Packages'); ?></h3>
        </div>
		
      </div>
	  
      </div>
       <table width="700" class="table">
        <thead>
		  <tr><th></th>
            <th><?php echo $this->lang->line('Sl.No');?></th>
			<th><?php echo $this->lang->line('Package Name');?></th>
			<th><?php echo $this->lang->line('Description');?></th>
            <th><?Php echo $this->lang->line('Start_Date');?></th>
            <th><?php echo $this->lang->line('End_date');?></th>
			<th><?php echo $this->lang->line('Totaldays');?></th>
			<th><?php echo $this->lang->line('Amount');?></th>
			<th><?php echo $this->lang->line('Status');?></th>
			<th><?php echo $this->lang->line('options');?></th>
          <!--  <th colspan="2"><span class="functions text-center" id="tip" style="opacity: 1;"> </span></th>-->
          </tr>
        </thead>
        <tbody>
	<form action="<?php echo admin_url('packages/managePackage'); ?>" name="managePackage" id="managePackage" method="post">
		<?php $no=1;
		
		if(isset($packageDetails) and $packageDetails->num_rows()>0)
		{
		foreach($packageDetails->result() as $packageDetails)
			{
		?>
          <tr>
		  <td><input type="checkbox" class="clsNoborder" name="selectpackage[]" id="selectpackage[]" value="<?php echo $packageDetails->id; ?>"  /> </td>
            <td><?php echo $no++;?></td>
			<td><?php echo $packageDetails->package_name;?></td>
			<td><?php echo $packageDetails->description;?></td>
            <td><?php echo date('d/m/Y',$packageDetails->start_date);?></td>
			<td><?php echo date('d/m/Y',$packageDetails->end_date);?></td>
			<td><?php echo $packageDetails->total_days;?></td>
			<td><?php echo $packageDetails->amount;?></td>
			<td><?php if ($packageDetails->isactive==0){ ?> <img src="<?php echo image_url('disable.png'); ?>" /> <?php } else { ?><img src="<?php echo image_url('enable.png'); ?>" /><?php } ?></td>
			<td><a name="edit" href="javascript: document.managePackage.action='<?php echo admin_url('packages/managePackage/'.$packageDetails->id); ?>'; document.managePackage.submit();"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a></td>
          </tr>
		  <?php }
		  }
		  else{ ?>
		   <tr>
            <td colspan="5"><?php echo $this->lang->line('No packages found');?></td></tr>
		  <?php }
		  ?>
        </tbody>
      </table>
	  </form>
	    <div class="clscenter clearfix">
	  <div id="selLeftAlign">
	<?php echo $this->lang->line('With Selected'); ?>
		   <!--<a name="edit" href="javascript: document.managePackage.action='<?php echo admin_url('packages/managePackage'); ?>'; document.managePackage.submit();"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a>-->
           <a name="delete" href="javascript: document.managePackage.action='<?php echo admin_url('packages/deletePackage'); ?>'; document.managePackage.submit();" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a></div>
    </div>
  </div>
  <?php if(isset($pagination)) echo $pagination;?>
</div>
<?php $this->load->view('admin/footer'); ?>

<script type="text/javascript">
function formSubmit()
{
	document.managePackage.submit();
	//document.manageBids.action='<?php //echo admin_url('skills/manageBids'); ?>'; document.manageBids.submit();
}