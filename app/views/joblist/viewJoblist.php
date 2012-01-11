<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->

<div id="main">
  <!--POST PROJECT-->
  <div class="clsInnerpageCommon">
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
                        <div class="clsInnerCommon">
					
                          <h2><?php echo $this->config->item('site_title'); ?> -&nbsp;<?php echo $pName; ?></h2>&nbsp;&nbsp;
						
						  <?php  echo '<p>'.$this->lang->line('Important'); $paymentSettings = $this->settings_model->getSiteSettings(); 
				echo $joblistvalidity  = $paymentSettings['JOBLIST_VALIDITY_LIMIT'];?> days
				<?php echo $this->lang->line('Important1'); $paymentSettings = $this->settings_model->getSiteSettings(); 
				echo $joblistvalidity  = $paymentSettings['JOBLIST_VALIDITY_LIMIT'];  echo $this->lang->line('Import');?> &nbsp;
						  <h3><span class="clsFeatured"><?php echo $pName; ?></span></h3>
						  
                          <form method="post" action="">
						 
                            <table cellspacing="1" cellpadding="2" width="96%">
                              <tbody>
                                <tr>
                                  <td width="30" class="dt"><?php echo $this->lang->line('SI.No');?></td>
                                  <?php
								  if($order == 'DESC')
								 $order = 'ASC';
								 elseif($order == 'ASC')
								 $order = 'DESC';
								 else
								 $order = 'ASC';
								  ?>
                                  <td width="250" class="dt"><a href="<?php echo site_url('joblist/viewAlljoblists/'.$type."/".$page."/project_name/".$order);?>"> <?php echo $this->lang->line('Project Name'); ?> </a>
                                    <?php
								  if($order == 'ASC' && $field == 'project_name')
								  echo '<img src="'.image_url('arrow_up.gif').'" />';
								  elseif($order == 'DESC' && $field == 'project_name')
								  echo '<img src="'.image_url('arrow.gif').'" />';
								  ?>
                                  </td>
                                  <td width="250" class="dt"><?php if($this->session->userdata('show_cat'))
										  {
										   echo $this->lang->line('Job Type'); 
										  }
										  if($this->session->userdata('show_budget'))
										  {
										  ?>
                                  </td>
                                  <td width="60" class="dt"><a href="<?php echo site_url('joblist/viewAlljoblists/'.$type."/".$page."/budget_max/".$order);?>"> <?php echo $this->lang->line('Budget'); ?> </a>
                                    <?php
								  if($order == 'ASC' && $field == 'budget_max')
								  echo '<img src="'.image_url('arrow_up.gif').'" />';
								  elseif($order == 'DESC' && $field == 'budget_max')
								  echo '<img src="'.image_url('arrow.gif').'" />';
								  ?>
                                  </td>
                                  <?php
										  }
										  if($this->session->userdata('show_status'))
										  {
										  ?>
                                  <td class="dt"><?php echo $this->lang->line('Status'); ?></td>
                                  <?php }
										   if($this->session->userdata('show_bids'))
										  {
										  ?>
                                  <td width="70" class="dt"><?php echo $this->lang->line('Bids'); ?></td>
                                  <?php } 
										  if($this->session->userdata('show_avgbid'))
										  {
										  ?>
                                  <td class="dt"><?php echo $this->lang->line('Avg Bid'); ?></td>
                                  <?php } 
										  if($this->session->userdata('show_date'))
										  {
										  ?>
                                  <td class="dt"><a href="<?php echo site_url('joblist/viewAlljoblists/'.$type."/".$page."/created/".$order);?>"> <?php echo $this->lang->line('Start Date'); ?> </a>
                                    <?php
									  if($order == 'ASC' && $field == 'created')
									  echo '<img src="'.image_url('arrow_up.gif').'" />';
									  elseif($order == 'DESC' && $field == 'created')
									  echo '<img src="'.image_url('arrow.gif').'" />';
									  ?>

                                  </td>
                                  <?php } 
								  if($this->session->userdata('show_closedate'))
										  {
										  ?>
                                  <td class="dt"><a href="<?php echo site_url('joblist/viewAlljoblists/'.$type."/".$page."/created/".$order);?>"> <?php echo $this->lang->line('close_date'); ?> </a>
                                    <?php
									  if($order == 'ASC' && $field == 'created')
									  echo '<img src="'.image_url('arrow_up.gif').'" />';
									  elseif($order == 'DESC' && $field == 'created')
									  echo '<img src="'.image_url('arrow.gif').'" />';
									  ?>
                                  </td>
                                  <?php } ?>
								  </tr>

                                  <?php $i=0; 
									if(isset($joblistprojects))
									{
										foreach($joblistprojects->result() as $project)
										{ 
										$i=$i+1;
										  if($i%2 == 0)
											$class = 'dt1 dt0';
										  else
											$class = 'dt2 dt0';	
										?>
                                <tr class="<?php echo $class;?>">
                                  <td><?php echo $i; ?></td>
                                  <td><a href="<?php echo site_url('project/view/'.$project->id); ?>"> <?php echo $project->project_name;?></a>
                                  </td>
                                  <?php if($this->session->userdata('show_cat')):	?>
                                  <td><?php echo getCategoryLinks($project->project_categories) ; ?> </td>
                                  <?php endif;
										if($this->session->userdata('show_budget')):	?>
                                  <td>$<?php echo  $project->salary;?></td>
                                  <?php endif; 
										 if($this->session->userdata('show_status')):	?>
                                  <td><?php 
										  echo getProjectStatus($project->project_status);
										  ?>
                                  </td>
                                  <?php endif; 
										 if($this->session->userdata('show_bids')):	?>
                                  <td><?php echo getNumBid($project->id);?></td>
                                  <?php endif;
										  if($this->session->userdata('show_avgbid')):	?>
                                  <td><?php echo getBidsInfo($project->id); ?></td>
                                  <?php endif; 
										  if($this->session->userdata('show_date')):	?>
                                  <td><?php echo get_date($project->created);?></td>
                                  <?php endif;
								   if($this->session->userdata('show_closedate')):?>
                                  <td><?php echo get_date($project->enddate);
											?> left<?php echo '<b style="color:red;">('.days_left($project->enddate,$project->id).'</b>';?>)</td>
                                  <?php endif;?>
                                </tr>
                                <tr class="<?php echo $class; ?>">
                                  <td colspan="9"><?php 
								  if($this->session->userdata('show_desc')):?>
                                    <div class="clsDecrip clsAdd" style="padding:3px 0 3px 10px;"> <?php echo $description = word_limiter($project->description,50); ?> </div>
                                    <?php endif;?></td>
                                </tr>
                                <?php								
										}//Traverse Projects
									}//Check For Project Availability
									 ?>
                                <tr>
                                  <td class="dt1 dt0" colspan="9"><table cellspacing="0" cellpadding="0" width="100%">
                                      <tbody>
                                        <tr>
                                          <td align="center"><?php echo $this->lang->line('Customize Display'); ?>:</td>
                                          <td><label>
                                            <input type="checkbox" value="1" <?php if($this->session->userdata('show_cat')) echo 'checked="checked"'; ?>  name="show_cat"/>
                                            <?php echo $this->lang->line('Type'); ?></label></td>
                                          <td><label>
                                            <input type="checkbox" value="1" <?php if($this->session->userdata('show_budget')) echo 'checked="checked"'; ?> name="show_budget"/>
                                            <?php echo $this->lang->line('Budget'); ?></label></td>
                                          <td><label>
                                            <input type="checkbox" value="1" <?php if($this->session->userdata('show_bids')) echo 'checked="checked"'; ?> name="show_bids"/>
                                            <?php echo $this->lang->line('Bids'); ?></label></td>
                                          <td><label>
                                            <input type="checkbox" value="1" <?php if($this->session->userdata('show_avgbid')) echo 'checked="checked"'; ?> name="show_avgbid"/>
                                            <?php echo $this->lang->line('Avg Bid'); ?></label></td>
                                          <td><label>
                                            <input type="checkbox" value="1" <?php if($this->session->userdata('show_status')) echo 'checked="checked"'; ?> name="show_status"/>
                                            <?php echo $this->lang->line('Status'); ?></label></td>
                                          <td><label>
                                            <input type="checkbox" value="1" <?php if($this->session->userdata('show_date')) echo 'checked="checked"'; ?> name="show_date"/>
                                            <?php echo $this->lang->line('Start Date'); ?></label></td>
											
											 <td><label>
                                            <input type="checkbox" value="1" <?php if($this->session->userdata('show_closedate')) echo 'checked="checked"'; ?> name="show_closedate"/>
                                            <?php echo $this->lang->line('close_date'); ?></label></td>
											
                                          <td><label>
                                            <input type="checkbox" value="1" <?php if($this->session->userdata('show_desc')) echo 'checked="checked"'; ?> name="show_desc"/>
                                            <?php echo $this->lang->line('Description'); ?></label></td>
                                          <td><select name="show_num" size="1">
                                              <option value="5" <?php if($this->session->userdata('show_num') == 5) echo "selected='selected'";?>>5</option>
                                              <option value="10" <?php if($this->session->userdata('show_num') == 10) echo "selected='selected'";?>>10</option>
                                              <option value="20" <?php if($this->session->userdata('show_num') == 20) echo "selected='selected'";?>>20</option>
                                              <option value="50" <?php if($this->session->userdata('show_num') == 50) echo "selected='selected'";?>>50</option>
                                              <option value="100" <?php if($this->session->userdata('show_num') == 100) echo "selected='selected'";?>>100</option>
                                            </select>
                                            <?php echo $this->lang->line('Results'); ?> </td>
                                        </tr>
                                      </tbody>
                                    </table></td>
                                </tr>
                              </tbody>
                            </table>
                            <p>
                              <input type="submit" value="<?php echo $this->lang->line('Refresh'); ?>" class="clsSmall" name="customizeDisplay"/>
                            </p>
                          </form>
                          <!--PAGING-->
                          <?php if(isset($pagination)) echo $pagination; ?>
                          <!--END OF PAGING-->
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
  <!--END OF POST PROJECT-->
</div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>
