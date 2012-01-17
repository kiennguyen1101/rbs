<!--LATEST-->
<!--RC-->
 <!--<div class="block">
        <div class="blue_t">
          <div class="blue_r">
            <div class="blue_b">
              <div class="blue_l">
                <div class="blue_tl">
                  <div class="blue_tr">
                    <div class="blue_bl">
                      <div class="blue_br">
                        <div class="cls100_p">
                          <div id="selHelp">
                      <h3><?php echo $this->lang->line('Latest Projects');?></h3>
					  <ul class="links">
					   <?php
						  	if(isset($latestProjects) and $latestProjects->num_rows()>0)
							{
								$i=1;
								foreach($latestProjects->result() as $latestProject)
								{
						?>
                        <li <?php if($latestProjects->num_rows() == $i) 
						echo 'class="clsNoborder"';?>><a href="<?php echo site_url('project/view/'.$latestProject->id); ?>"><?php echo $latestProject->project_name; ?></a>
						 <?php if($latestProject->is_urgent == 1)
								  echo '&nbsp;<img src="'.image_url('urgent2.gif').'" width="14" height="14" title="Urgent project" alt="Urgent project" />';
								   if($latestProject->is_feature == 1)
								    echo '&nbsp;<img src="',image_url('featured2.gif').'" width="14" height="14" title="Featured project" alt="Featured project" />';
									if($latestProject->is_private == 1)
								    echo '&nbsp;<img src="',image_url('private.png').'" width="14" height="14" title="Private project" alt="Featured project" />';
									
								   ?>
						</li>
						 <?php		
						  			$i++;						
								}														
							} 
						  ?>
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
      </div>-->
<!--END OF RC-->
<!--END OF LATEST-->