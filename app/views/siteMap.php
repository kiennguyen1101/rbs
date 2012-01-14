<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar');?>
<link href="<?php echo base_url() ?>app/css/css/sitemap.css" rel="stylesheet" type="text/css" />
 <div id="main">
      <!--SITE MAP-->
      <div class="clsInnerpageCommon sitemap">
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
                            <div class="clsInnerCommon clearfix">
                              <h2><?php echo $this->lang->line('SITE MAP');?></h2>
                              <div class="clsSiteMap">

                                <h3><span class="clsFeatured"><?php echo $this->lang->line('General');?></span></h3>

                               

                                <ul>
                                  <li><a href="<?php echo site_url('?c=rss'); ?>"><?php echo $this->lang->line('RSS Feeds');?></a></li>
                                  <li><a href="<?php echo site_url('faq'); ?>"><?php echo $this->lang->line('FAQs');?></a></li>
                                  <li><a href="<?php echo site_url('seller/getSellersreview');?>"><?php echo $this->lang->line('Reports');?></a></li>
								  <li><a href="<?php echo site_url('seller/getSellersreview');?>"><?php echo $this->lang->line('Contacts');?></a></li>
								  <li><a href="<?php echo site_url('users/forgotPassword');?>"><?php echo $this->lang->line('Forgot Password?');?></a></li>
								 <?php
								if(isset($pages))
								{
									foreach($pages->result() as $page)
									{
								?>
									<li><a href=" <?php echo site_url('page')?>/<?php echo $page->url;?>"> <?php echo $page->name; ?></a></li>
								   <?php } 
									 }
								   ?>	
                                </ul>
                              </div>
                              <div class="clsSiteMap">

                                <h3><span class="clsTopseller"><?php echo $this->lang->line('Provide Services');?></span></h3>

                            

                                <ul>
                                  <li><a href="<?php echo site_url('seller/signUp');?>"><?php echo $this->lang->line('Sign Up');?></a></li>
                                  <li><a href="<?php echo base_url();?>"><?php echo $this->lang->line('Find Projects');?></a></li>
                                  <li><a href="<?php echo site_url('project/viewAllProjects/all');?>"><?php echo $this->lang->line('Latest Projects');?> </a></li>
                                  <li><a href="<?php echo site_url('project/viewAllProjects/all');?>"><?php echo $this->lang->line('Featured Projects');?> </a></li>
                                  <li><a href="<?php echo site_url('project/viewAllProjects/all');?>"><?php echo $this->lang->line('High Budget Projects');?> </a></li>
                                  <li><a href="<?php echo site_url('project/viewAllProjects/all');?>"><?php echo $this->lang->line('Urgent Projects');?></a></li>
                                </ul>
                              </div>
                              <div class="clsSiteMap">

                               <h3><span class="clsTopbuyer"><?php echo $this->lang->line('Get Services');?></span></h3>

                       

                                <ul>
                                  <li><a href="<?php echo site_url('project/create');?>"> <?php echo $this->lang->line('Post Your Project');?></a></li>
                                  <li><a href="<?php echo site_url('buyer/signUp');?>"><?php echo $this->lang->line('Sign Up');?></a></li>
                                  <li><a href="<?php echo site_url('seller/getSellersreview');?>"><?php echo $this->lang->line('Top Sellers');?></a></li>
                                </ul>
                              </div>
                              <div class="clear"></div>
                              <div class="clsSiteMap">

                                <h3><span class="clsCategory"><?php echo $this->lang->line('Latest Projects');?></span></h3>

                           

                                <ul>
                                  <?php
								if(isset($latestProjects) and $latestProjects->num_rows()>0)
								{
									$i=1;
									foreach($latestProjects->result() as $latestProject)
									{
								?>
                                  <li><a href="<?php echo site_url('project/view/'.$latestProject->id); ?>"><?php echo $latestProject->project_name; ?></a></li>
								<?php		
										$i++;						
									}//For Each End - Latest Project Traversal															
								} 
								?>
                                </ul>
                              </div>
                              <div class="clsSiteMap">

                                <h3><span class="clsFileManager"><?php echo $this->lang->line('Services Categories');?></span></h3>

                         

                                <ul>
								<?php
								if(isset($categories) and $categories->num_rows()>0)
								{
									$i=1;
									foreach($categories->result() as $category)
									{
									$name = replaceSpaceWithUnderscore($category->category_name);
								?>
                                  <li><a href="<?php echo site_url('project/category/'.$name); ?>"><?php echo $category->category_name;?></a></li>
								<?php }
								}
								?>
                                </ul>
                              </div>
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
      </div>
      <!--END OF SITE MAP-->
    </div>
<?php $this->load->view('footer'); ?>