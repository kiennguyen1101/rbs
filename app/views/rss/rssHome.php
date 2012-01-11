<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<link href="<?php echo base_url() ?>app/css/css/rssfeed.css" rel="stylesheet" type="text/css" />
<!--MAIN-->
<div id="main">
      <!--POST PROJECT-->
      <div class="clsRssFeed">
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
                              <h2><?php echo $this->config->item('site_title'); ?> <?php echo $this->lang->line('rss_feeds'); ?></h2>
                              <p><?php echo $this->lang->line('We_have several RSS feeds available to keep you up to date on the newest projects posted on'); ?> <?php echo $this->config->item('site_title'); ?>. <?php echo $this->lang->line('Click any links below to see an RSS feed, or use the form below to create your custom feed'); ?>.</p>
                              <h3><span class="clsViewPro"><?php echo $this->lang->line('Projects Feed'); ?></span></h3>
                              <table cellspacing=1 cellpadding=2 width=96%>
                                <tr>
                                  <td class=dt width="200"><?php echo $this->lang->line('Feed Content'); ?></td>
                                  <td class=dt width="250"><?php echo $this->lang->line('Titles'); ?></td>
                                  <td class=dt width="250"><?php echo $this->lang->line('Titles + Description'); ?></td>
                                </tr>
                                <tr>
                                  <td class="dt1 dt0"><a href="<?php echo site_url('?c=rss&amp;m=all') ?>"><?php echo $this->lang->line('All Projects'); ?></a> </td>
                                  <td class=dt1><a href="<?php echo site_url('?c=rss&amp;m=all') ?>"><?php echo site_url('?c=rss&amp;m=all') ?></a></td>
                                  <td class=dt1><a href="<?php echo site_url('?c=rss&amp;m=all&amp;type=2') ?>"><?php echo site_url('?c=rss&amp;m=all&amp;type=2') ?></a></td>
                                </tr>
								
								 <?php
							if(isset($categories) and $categories->num_rows()>0)
							{
								$i=1;
								foreach($categories->result() as $category)
								{   
								  if($i%2 == 0)
								    $class ="dt1";
								  else
								    $class ="dt2";	
									?>
								
                                <tr>
                                  <td class="<?php echo $class; ?> dt0"><a href="<?php echo site_url('?c=rss&amp;m=show&amp;cat='.$category->id); ?>"><?php echo $category->category_name; ?></a></td>
                                  <td class="<?php echo $class; ?>"><a href="<?php echo site_url('?c=rss&amp;m=show&amp;cat='.$category->id); ?>"><?php echo site_url('?c=rss&amp;m=show&amp;cat='.$category->id); ?></a></td>
                                  <td class="<?php echo $class; ?>"><a href="<?php echo site_url('?c=rss&amp;m=show&amp;type=2&amp;cat='.$category->id); ?>"><?php echo site_url('?c=rss&amp;m=show&amp;type=2&amp;cat='.$category->id); ?></a></td>
                                </tr>
								<?php
								$i++;
								}//For Each End - Categories Traversal
							}//If End	- Check For Categories Availability
						  ?>
                                
                              </table>
							  <div class="clsSitelinks"> 
                              <h3><span class="clsResend"><?php echo $this->lang->line('Custom RSS Feed');?></span></h3>
							  <form method="get" action="">
                              <p><?php echo $this->lang->line('Create');?></p>
                              <ul>
                                <li>
                                  <h5><?php echo $this->lang->line('No of projects to display:');?></h5>
                                  <p>
                                    <input type="text" class="text" name="show" value="10" size="5"/>
                                  </p>
                                </li>
                                <li>
                                  <h5><?php echo $this->lang->line('Info to display:');?></h5>
                                  <p><SELECT NAME="d" class="clsOption">
									<OPTION selected VALUE="1">Project titles only</OPTION>
									<OPTION VALUE="2">Titles and descriptions</OPTION>
									<OPTION VALUE="3">Titles and full descriptions</OPTION>
								  </select></p>
                                </li>
                                <li>
                                  <h5><?php echo $this->lang->line('Categories');?></h5>
                                  
                                  <ul class="clsList clearfix">
								  <?php
									if(isset($categories) and $categories->num_rows()>0)
									{
										foreach($categories->result() as $category)
										{
											  ?>
											<li>
											  <label>
											  <input name="category[]" value="<?php echo $category->id; ?>" type="checkbox"/>
											  <?php echo $category->category_name; ?></label>
											</li>
														   
											<?php
										}//For Each End - Categories Traversal
									}//If End	- Check For Categories Availability
								?>               
                                  </ul>
                                </li>
                                
                              </ul>
                              <p>
                                <input type="checkbox" value="1" name="f"/>
                                <?php echo $this->lang->line('Only');?> <a href="<?php echo site_url('project/viewAllProjects/is_feature');?>"><?php echo $this->lang->line('Featured');?></a> <?php echo $this->lang->line('projects.');?></p>
                              <p>
                                <input type="checkbox" value="1" name="u"/>
                                <?php echo $this->lang->line('Only');?><a href="<?php echo site_url('project/viewAllProjects/is_urgent');?>"><?php echo $this->lang->line('Urgent');?></a>   <?php echo $this->lang->line('projects.');?></p>
                              
                              <p><b><?php echo $this->lang->line('Keywords to match:');?></b></p>
                              <p><small>(<?php echo $this->lang->line('search');?>)</small></p>
                              <p>
                                <input type="text" class="clsPercent50" name="key"/>
                              </p>
                              <p>
								<input class="clsMid" type="submit" value="<?php echo $this->lang->line('Create RSS Feed');?>" name="submit"/>
								<input type="hidden" name="c" value="rss" />
								<input type="hidden" name="m" value="getCustom" />
							  </p>
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
          </div>
        </div>
      </div>
      <!--END OF POST PROJECT-->

<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>