<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main" class="">
      <!--SEARCH RESULTS-->
      <div id="selSearchResult">
        <!--SEARCH RESULTS TABLE-->
			<div class="clsTitle clsClearFix">
			  <div class="clsHeading clsFloatLeft">
				<h2><?php echo $this->config->item('site_title'); ?> &nbsp;<?php echo $this->lang->line('Featured Projects'); ?></h2>	
			  </div>
			</div>
        <div id="selProjectSearch" class="clsMarginTop">
          <!--RC-->
          <div class="block">
            <div class="black_t">
              <div class="black_r">
                <div class="black_b">
                  <div class="black_l">
                    <div class="black_tl">
                      <div class="black_tr">
                        <div class="black_bl">
                          <div class="black_br clsZero">
                            <div class="cls100_p">
                              <h3><span class="clsFeatured"><?php echo $this->lang->line('Featured Projects'); ?></span></h3>
                              <p class="clsHead clsClearFix">
		 					  	  <span class="clsProjectName"><?php echo $this->lang->line('Project Name');?></span>
								  <span class="clsImg">&nbsp;</span>
								   <?php if($this->session->userdata('show_cat'))
								  {
								  ?>
								  <span class="clsJobType"><?php echo $this->lang->line('Job Type');?></span>
								  <?php 
								  }
								  if($this->session->userdata('show_budget'))
								  {
								  ?>
							      	<span class="clsBudget"><?php echo $this->lang->line('Budget');?></span>
								  <?php
								  }
								  if($this->session->userdata('show_status'))
								  {
								  ?>
								  <span class="clsBudget"><?php echo $this->lang->line('Status');?></span>
								  <?php }
								   if($this->session->userdata('show_bids'))
								  {
								  ?>	
							      <span class="clsBids"><?php echo $this->lang->line('Bids');?></span>
								  <?php } 
								  if($this->session->userdata('show_avgbid'))
								  {
								  ?>
  							      <span class="clsBudget"><?php echo $this->lang->line('Avg Bid');?></span>
								  <?php } 
								  if($this->session->userdata('show_date'))
								  {
								  ?>
							      <span class="clsStartDate"><?php echo $this->lang->line('Start Date');?></span>
								  <?php } ?>
							  </p>
							  <?php
						 	if(isset($featureProjects) and $featureProjects->num_rows()>0)
							{
								foreach($featureProjects->result() as $project)
								{
								?>
							 <p class="clsAdd clsClearFix">
							  	  <span class="clsProjectName"><a href="<?php echo site_url('project/view/'.$project->id); ?>">
								  <?php echo $project->project_name;?></a></span>
								  <span class="clsImg"><img src="<?php echo image_url('urgent2.gif');?>" width="14" height="14" /></span>
								 <?php if($this->session->userdata('show_cat')):	?>
								  <span class="clsJobType">
								  	<?php echo $project->project_categories ; ?>
								</span>
								<?php endif;
								if($this->session->userdata('show_budget')):	?>
							      <span class="clsBudget">$<?php echo  $project->budget_max;?></span>
								 <?php endif; 
								 if($this->session->userdata('show_status')):	?>
							      <span class="clsBudget"><?php 
								  $status = $project->project_status;
								  if($status == 0)
									$stat = $this->lang->line('Open'); 
									if($status == 1)
									$stat = $this->lang->line('Frozen'); 
									if($status == 2)
									$stat = $this->lang->line('Closed'); 
								  echo $stat;?></span>
								 <?php endif; 
								 if($this->session->userdata('show_bids')):	?>
							      <span class="clsBids"><?php echo getNumBid($project->id);?></span>
								  <?php endif;
								  if($this->session->userdata('show_avgbid')):	?>
  							      <span class="clsBudget"><?php echo getBidsInfo($project->id); ?></span>
								  <?php endif; 
								  if($this->session->userdata('show_date')):	?>
							      <span class="clsStartDate"><?php echo get_date($project->created);?></span>
								  <?php endif;?>
							 </p >
							 <?php if($this->session->userdata('show_desc')):	?>
							 <div class="clsDecrip clsAdd"><?php echo $description = word_limiter($project->description,50); ?></div>
						 <?php	endif;								
								}//Traverse Projects
							}//Check For Project Availability
							 ?>	 
						 	<p class="clsLeftpad clsBgColor"><?php echo $this->lang->line('Customize Display:');?></p>
							 <form method="post" action="">
							 <ul class="clsCuzDisplay clsClearFix"> 
							
							 	<li><label><input type="checkbox" value="1" <?php if($this->session->userdata('show_cat')) echo 'checked="checked"'; ?>  name="show_cat"/> <?php echo $this->lang->line('Type');?></label></li>
							 	<li><label><input type="checkbox" value="1" <?php if($this->session->userdata('show_budget')) echo 'checked="checked"'; ?> name="show_budget"/> <?php echo $this->lang->line('Budget');?></label></li>																
							 	<li><label><input type="checkbox" value="1" <?php if($this->session->userdata('show_bids')) echo 'checked="checked"'; ?> name="show_bids"/> <?php echo $this->lang->line('Bids');?></label></li>																
							 	<!--<li><label><input type="checkbox" value="1" name="show_lowestbid"/> Lowest&nbsp;Bid</label></li>-->																
							 	<li><label><input type="checkbox" value="1" <?php if($this->session->userdata('show_avgbid')) echo 'checked="checked"'; ?> name="show_avgbid"/> <?php echo $this->lang->line('Avg Bid');?></label></li>																
							 	<li><label><input type="checkbox" value="1" <?php if($this->session->userdata('show_status')) echo 'checked="checked"'; ?> name="show_status"/> <?php echo $this->lang->line('Status');?></label></li>																
							 	<li><label><input type="checkbox" value="1" <?php if($this->session->userdata('show_date')) echo 'checked="checked"'; ?> name="show_date"/> <?php echo $this->lang->line('Date');?></label></li>																																																							 								<!--<li><label><input type="checkbox" value="1" name="show_timeleft"/> Time&nbsp;Left</label></li>-->
 								<!--<li><label><input type="checkbox" value="1" name="show_buyer"/> Buyer</label></li>-->
 								<li><label><input type="checkbox" value="1" <?php if($this->session->userdata('show_desc')) echo 'checked="checked"'; ?> name="show_desc"/> <?php echo $this->lang->line('Description');?></label></li>
 								<li><select name="show_num" size="1">
										<option value="5" <?php if($this->session->userdata('show_num') == 5) echo "selected";?>>5</option>
										<option value="10" <?php if($this->session->userdata('show_num') == 10) echo "selected";?>>10</option>
										<option value="20" <?php if($this->session->userdata('show_num') == 20) echo "selected";?>>20</option>
										<option value="50" <?php if($this->session->userdata('show_num') == 50) echo "selected";?>>50</option>
										<option value="100" <?php if($this->session->userdata('show_num') == 100) echo "selected";?>>100</option>
									</select> <?php echo $this->lang->line('Results');?></li>																								
							 </ul>
								<p class="clsLeftpad"><input type="submit" value="<?php echo $this->lang->line('Refresh');?>" class="clsSmall" name="customizeDisplay"/></p>
							</form>
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
          <!--END OF RC-->
        </div>
	  <!--PAGING-->
	  	<?php if(isset($pagination)) echo $pagination;?>
	 <!--END OF PAGING-->	
        
      </div>
      <!--END OF SEARCH RESULTS-->
    </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>