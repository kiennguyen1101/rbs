      <!--SELSERVICES-->
      <!--RC-->
	   <div class="block">
        <div class="blue_t">
          <div class="blue_r">
            <div class="blue_b">
              <div class="blue_l">
                <div class="blue_tl">
                  <div class="blue_tr">
                    <div class="blue_bl">
                      <div class="blue_br">
                        <div class="cls100_p">
                          <div id="selFeatures">
                            <h3><?php echo $this->lang->line('Services_And_Features');?></h3>
                            <ul class="links">
							<?php 
								if(!isSeller())
								{
		?>
                              <li><a href="<?php echo site_url('project/create'); ?>"><?php echo $this->lang->line('Post a Project');?></a></li>
							   <li><a href="<?php echo site_url('joblist/create'); ?>"><?php echo $this->lang->line('Post Job listing');?></a></li>
							 <?php  
							 }  
							 ?>
							 
							 
							    <li><a href="<?php echo site_url('joblist/viewAlljoblists/flag'); ?>"><?php echo $this->lang->line('Job listings');?></a></li>
							  <?php if($this->session->userdata('role') == 'admin'){?>
							  <li><a href="<?php echo site_url('users/login'); ?>"><?php echo $this->lang->line('Login');?> </a></li>
                              <li><a href="<?php echo site_url('buyer/signUp'); ?>"><?php echo $this->lang->line('SignUp');?> </a></li>
							  <?php } ?>
                              <li><a href="<?php echo site_url('project/viewAllProjects/is_feature'); ?>"><?php echo $this->lang->line('Featured Projects');?></a></li>
							  
							  <li><a href="<?php echo site_url('dispute/openCase'); ?>"><?php echo $this->lang->line('Cancel Projects');?></a></li>
							  <li><a href="<?php echo site_url('certificate/viewcontent'); ?>"><?php echo $this->lang->line('Certified Members');?></a></li>
                              <li><a href="<?php echo site_url('seller/getSellersreview'); ?>"><?php echo $this->lang->line('Top Sellers');?></a></li>
							  <li><a href="<?php echo site_url('buyer/getBuyersreview'); ?>"><?php echo $this->lang->line('Top Buyers');?></a></li>
							  <li><a href="<?php echo site_url('affiliate/'); ?>"><?php echo $this->lang->line('Affiliate');?></a></li>
                          <?php /*?>   <li ><a href="<?php echo site_url('affiliateprojects/'); ?>">Script Lance Projects</a></li> <?php */?>
							  <li class="clsNoborder"><a href="<?php echo site_url('?c=rss'); ?>"><?php echo $this->lang->line('RSS Feeds');?></a></li>
							
                            </ul>
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
      <!--END OF RC-->
      <!--END OF SELSERVICES-->