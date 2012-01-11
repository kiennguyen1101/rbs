<div class="slidetabsmenu" id="tabMenu">
  <?php if($viewall == 'all'){?>
  <ul>
    <li class="selected"><a href="javascript:;" onclick="getProjects('latest')"><span>Latest Project</span></a></li>
    <li><a href="javascript:;" onclick="getProjects('featured')"><span>Featured Project</span></a></li>
    <li><a href="javascript:;" onclick="getProjects('high')"><span>High Budget Project</span></a></li>
    <li><a href="javascript:;" onclick="getProjects('urgent')"><span>Urgent Project</span></a></li>
  </ul>
  <?php } elseif($viewall == 'is_feature'){?>
  <ul>
    <li><a href="javascript:;" onclick="getProjects('latest')"><span>Latest Project</span></a></li>
    <li class="selected"><a href="javascript:;" onclick="getProjects('featured')"><span>Featured Project</span></a></li>
    <li><a href="javascript:;" onclick="getProjects('high')"><span>High Budget Project</span></a></li>
    <li><a href="javascript:;" onclick="getProjects('urgent')"><span>Urgent Project</span></a></li>
  </ul>
  <?php } elseif($viewall == 'high_budget'){?>
  <ul>
    <li><a href="javascript:;" onclick="getProjects('latest')"><span>Latest Project</span></a></li>
    <li><a href="javascript:;" onclick="getProjects('featured')"><span>Featured Project</span></a></li>
    <li class="selected"><a href="javascript:;" onclick="getProjects('high')"><span>High Budget Project</span></a></li>
    <li><a href="javascript:;" onclick="getProjects('urgent')"><span>Urgent Project</span></a></li>
  </ul>
  <?php } elseif($viewall == 'is_urgent'){?>
  <ul>
    <li><a href="javascript:;" onclick="getProjects('latest')"><span>Latest Project</span></a></li>
    <li><a href="javascript:;" onclick="getProjects('featured')"><span>Featured Project</span></a></li>
    <li><a href="javascript:;" onclick="getProjects('high')"><span>High Budget Project</span></a></li>
    <li class="selected"><a href="javascript:;" onclick="getProjects('urgent')"><span>Urgent Project</span></a></li>
  </ul>
  <?php } ?>
</div>
<div class="clsInfoBox" id="listProjects">
  <div class="block">
    <div class="grey_t">
      <div class="grey_r">
        <div class="grey_b">
          <div class="grey_l">
            <div class="grey_tl">
              <div class="grey_tr">
                <div class="grey_bl">
                  <div class="grey_br">
                    <div class="cls100_p">
                      <h4><span class="clsFeatured"><?php echo $title;?></span></h4>
                      <br />
                      <table cellspacing=1 cellpadding=2 width=100%>
                        <tr>
                          <td class=dt><?php echo $this->lang->line('Project Name');?></td>
                          <td class=dt width=50 align=center><?php echo $this->lang->line('Budget');?></td>
                          <td class=dt width=10 align=center><?php echo $this->lang->line('Bids');?></td>
                          <td class=dt width=50 align=center><?php echo $this->lang->line('Avg Bid');?></td>
                          <td class=dt><?php echo $this->lang->line('Job Type');?></td>
                          <td class=dt width=100><?php echo $this->lang->line('Posted');?></td>
                        </tr>
                        <?php
						if(isset($listProjects) and $listProjects->num_rows()>0)
						{
						    $i=0;
							foreach($listProjects->result() as $listProject)
							{
								if($i%2==0)
								$class = 'dt1';
								else 
								$class = 'dt2';	
								?>
                        <tr>
                          <td class="<?php echo $class;?> dt0"><a href="<?php echo site_url('project/view/'.$listProject->id); ?>"><?php echo $listProject->project_name; ?></a>
						  <?php  if($listProject->is_urgent == 1)
								  echo '&nbsp;<img src="'.image_url('urgent2.gif').'" width="14" height="14" title="Urgent project" alt="Urgent project" />';
								   if($listProject->is_feature == 1)
								    echo '&nbsp;&nbsp;<img src="',image_url('featured2.gif').'" width="14" height="14" title="Featured project" alt="Featured project" />';
									if($listProject->is_private == 1)
									 echo '&nbsp;&nbsp;<img src="',image_url('private.png').'" width="14" height="14" title="Private project" alt="private project" />';
								   ?>
                            
                          </td>
                          <td align=center class=<?php echo $class;?> align=right>
                          <?php echo $listProject->budget_max; ?>
                          </td>
                          <td align=center class=<?php echo $class;?> align=right>
                          <?php echo getNumBid($listProject->id); ?>&nbsp;
                          </td>
                          <td align=center class=<?php echo $class;?> align=right>
                          <?php echo getBidsInfo($listProject->id); ?> &nbsp;
                          </td>
                          <td class=<?php echo $class;?>><?php echo getCategoryLinks($listProject->project_categories); ?></td>
                          <td class=<?php echo $class;?>><?php echo get_date($listProject->created);?></td>
                        </tr>
                        <?php		
								$i++;						
						   }//For Each End - Latest Project Traversal															
					   }//If - End Check For Latest Projects
						?>
                      </table>
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
<div align="right">
  <input type="submit" value="<?php echo $this->lang->line('Post Project');?>" name="postproject" class="clsMid" onclick="window.location='<?php echo site_url('project/create');?>'" />
  <input type="submit" value="<?php echo $this->lang->line('View All');?>" name="viewall" class="clsMid" onclick="window.location='<?php echo site_url('project/viewAllProjects/'.$viewall); ?>'" />
</div>
<br />