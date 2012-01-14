      <!--SELSERVICES-->
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
                          <div id="selLinks">
                            <h3><?php echo $this->lang->line('Welcome'); ?>,<?php if(is_object($loggedInUser))  echo substr($loggedInUser->user_name,0,15);?></h3>
							 <ul class="links">
							  <li><a href="<?php echo site_url('account'); ?>"><?php echo $this->lang->line('Manage Account'); ?></a></li>
                              <!--<li><a href="<?php echo site_url('seller/viewProfile/'.$loggedInUser->id); ?>"><?php echo $this->lang->line('view_profile'); ?></a></li>-->
							  <li><a href="<?php echo site_url('seller/editProfile'); ?>"><?php echo $this->lang->line('edit_profile'); ?></a></li>
							  <li><a href="<?php echo site_url('seller/viewMyProjects'); ?>"><?php echo $this->lang->line('bidding_on'); ?></a></li>
							  <li><a href="<?php echo site_url('seller/managePortfolio');?>"><?php echo $this->lang->line('manage_portfolio'); ?></a></li>
							  <li class="clsNoborder"><a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout'); ?></a></li>
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
      <!--END OF SELSERVICES-->