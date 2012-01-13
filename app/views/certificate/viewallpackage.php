<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main">
  <!--POST PROJECT-->
  <div class="clsViewMyProject">
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
                        <div class="clsInnerCommon clsSitelinks">
                          <h2><?php echo $this->lang->line('List Of Packages'); ?></h2>
						  <h3><span class="clsFeatured">List of Packages</span></h3>
      <form action="<?php echo site_url('certificate/addpackage'); ?>" name="viewpackage" id="viewpackage" method="post">        
			 <table cellspacing="1" cellpadding="2" width="96%" class="clsSitelinks">
                            <tbody>
                              
                                <tr>
								<th class="dt"><?php echo $this->lang->line('Sl.No');?></th>
								<th class="dt"><?php echo $this->lang->line('Package Name');?></th>
								<th class="dt"><?php echo $this->lang->line('Description');?></th>
								<th class="dt"><?Php echo $this->lang->line('From');?></th>
								<th class="dt"><?php echo $this->lang->line('To');?></th>
								<th class="dt"><?php echo $this->lang->line('Total Days');?></th>
								<th class="dt"><?php echo $this->lang->line('Amount');?></th>
                              </tr>
			<?php if(isset($packagesList) and $packagesList->num_rows()>0)
				{
				foreach($packagesList->result() as $packagesLists)
					{?>
							   <tr>
		  <td><input type="radio" class="clsNoborder" name="selectpackage[]" id="selectpackage[]" value="<?php echo $packagesLists->id; ?>"  /> </td>
           
			<td><?php echo $packagesLists->package_name;?></td>
			<td><?php echo $packagesLists->description;?></td>
            <td><?php echo date('d/m/Y',$packagesLists->start_date);?></td>
			<td><?php echo date('d/m/Y',$packagesLists->end_date);?></td>
			<td><?php echo $packagesLists->total_days;?></td>
			<td><?php echo $packagesLists->amount;?></td>
              </tr>
                             
                              
		<?php	}
			}?>
			</tbody>
							  </table>		  
			<p><a name="edit" href="javascript: document.viewpackage.action='<?php echo site_url('certificate/addpackage'); ?>'; document.viewpackage.submit();" onclick="return confirm('Are you sure want to buy the package');"><img src="<?php echo image_url('bt_bl_apply.png'); ?>"/><?php //echo $this->lang->line('Apply Package');?></a></p>
			 
                          <!--SIGN-UP-->
                        
							
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
<?php $this->load->view('footer'); ?>