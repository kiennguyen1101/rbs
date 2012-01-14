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
                          <h2><?php echo $this->config->item('site_title'); ?> &nbsp;<?php echo $this->lang->line('Search Reults'); ?></h2>
                          <div class="clsCatSearch">
						  <div class="clsCatSearchLft clsFloatLeft">
						  <img src="<?php echo image_url('ic_category.png');?>" width="35" height="34" title="Category Description" alt="Category Description" />
						  </div>
						  <div class="clsCatSearchRt">
						  <p><?php echo $this->lang->line('Category');?> - <?php echo $category_name; ?> - <?php echo $meta_description; ?></p>
						  </div>
						  </div>
						
						  <form method="post" action="">
                          <table cellspacing="1" cellpadding="2" width="96%">
                            <tbody>
                              <tr>
                                <td width="30" class="dt"><?php echo $this->lang->line('SI.No');?></td>
                                <td width="" class="dt"><?php
						  $odr = 'ASC';
						  if($order == 'ASC')
						  $odr = 'DESC';
						  ?>
                                  <?php echo $this->lang->line('Project Name'); ?></td>
                                <?php if($this->session->userdata('show_cat'))
								  {
								  ?>
                                <td width="" class="dt"><?php echo $this->lang->line('Job Type'); ?></td>
                                <?php 
								  }
								  if($this->session->userdata('show_budget'))
								  {
								  ?>
                                <td width="" class="dt"><?php echo $this->lang->line('Budget'); ?></td>
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
                                <td width="" class="dt"><?php echo $this->lang->line('Bids'); ?></td>
                                <?php } 
								  if($this->session->userdata('show_avgbid'))
								  {
								  ?>
                                <td class="dt"><?php echo $this->lang->line('Avg Bid'); ?></td>
                                <?php } 
								  if($this->session->userdata('show_date'))
								  {
								  ?>
								  
								  <td class="dt"><?php echo $this->lang->line('Start Date'); ?></td>
								     <?php }  ?> 
        		              </tr>  
	
                              <?php $i=0;
						 	if(isset($projects) and $projects->num_rows()>0)
							{
								foreach($projects->result() as $project)
								{ 
								$i=$i+1;
								if($i%2 == 0)
								  $class = 'dt1 dt0';
								else
								  $class = 'dt2 dt0'; 
								?>
                              <tr class="<?php echo $class; ?>">
                                <td><?php echo $i; ?>.</td>
                                <td><a href="<?php echo site_url('project/view/'.$project->id); ?>"> <?php echo $project->project_name;?></a>
								 <?php if($project->is_urgent == 1) { ?>
								  &nbsp;<img src="<?php echo image_url('urgent2.gif');?>" width="14" height="14" title="Urgent projects" alt="Urgent project" />
								   <?php } 
								   if($project->is_feature == 1) { ?>
								    &nbsp;&nbsp;<img src="<?php echo image_url('featured2.gif');?>" width="14" height="14" title="Featured projects" alt="Featured project" />
								   <?php} 
								    if($project->is_private == 1) { ?>
								    &nbsp;&nbsp;<img src="<?php echo image_url('private.png');?>" width="14" height="14" title="Featured projects" alt="Private project" />
								   <?php} 
								   ?>
								</td>
                                <?php if($this->session->userdata('show_cat')):	?>
                                <td><?php echo getCategoryLinks($project->project_categories) ; ?> </td>
                                <?php endif;
								if($this->session->userdata('show_budget')):	?>
                                <td>$<?php echo  $project->budget_max;?></td>
                                <?php endif; 
								 if($this->session->userdata('show_status')):	?>
                                <td><?php 
								  echo getProjectStatus($project->project_status);?>
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
								 if($this->session->userdata('show_desc')):
								?>
								
								 
								 <?php endif;  ?>
                                <tr class="<?php echo $class; ?>">
                                <td colspan="8"><?php if($this->session->userdata('show_desc')):	?>
                                  <div class="clsDecrip clsAdd">
                                    <?php echo $description = word_limiter($project->description,50); ?>
                                  </div>
                                  <?php	endif;								
								}//Traverse Projects
							}//Check For Project Availability
							 ?></td>
                              </tr>
                              </tr>
                              
                              <tr>
                                <td class="dt1 dt0" colspan="8"><table cellspacing="0" cellpadding="0" width="100%">
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
                                          <input type="checkbox" value="1" <?php if($this->session->userdata('show_desc')) echo 'checked="checked"'; ?> name="show_desc"/>
                                          <?php echo $this->lang->line('Description'); ?></label></td>
                                        <td><select name="show_num" size="1">
                                            <option value="5" <?php if($this->session->userdata('show_num') == 5) echo "selected";?>>5</option>
                                            <option value="10" <?php if($this->session->userdata('show_num') == 10) echo "selected";?>>10</option>
                                            <option value="20" <?php if($this->session->userdata('show_num') == 20) echo "selected";?>>20</option>
                                            <option value="50" <?php if($this->session->userdata('show_num') == 50) echo "selected";?>>50</option>
                                            <option value="100" <?php if($this->session->userdata('show_num') == 100) echo "selected";?>>100</option>
                                          </select>
                                          <?php echo $this->lang->line('Results'); ?>
                                         
                                         
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table></td>
                              </tr>
                            </tbody>
                          </table>
						   <p><input type="submit" value="<?php echo $this->lang->line('Refresh'); ?>" class="clsSmall" name="customizeDisplay"/></p></form>
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