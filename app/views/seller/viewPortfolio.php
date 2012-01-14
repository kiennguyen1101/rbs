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
							 <?php
							//Show Flash Message
							 if($msg = $this->session->flashdata('flash_message'))
							  {
								echo $msg;
							  }
							  ?>
							  <!--NEW BUYERS SIGN-UP-->
							  <div id="selSignUp">
							  <h2><?php echo $this->lang->line('view_portfolio'); ?></h2>
							  <table cellpadding="1" cellspacing="0" style="border:none;">
							  <tbody>
							 <?php
								if(isset($portfolio) and $portfolio->num_rows()>0)
								{
								foreach($portfolio->result() as $portfolio)
									{	?>
							         <tr>
										<td><a href="<?php echo site_url('seller/viewPortfolio/'.$portfolio->id);?>">
										<img border="0" src="<?php echo pimage_url(get_thumb($portfolio->main_img));?>" alt="portfolio"/></a></td> 
										<td valign="middle">
										<table style="border:none;" cellpadding="3">
											<tbody><tr>
											<td width="15%"><b><?php echo $this->lang->line('Title:');?> </b></td> <td><a href="<?php echo site_url('seller/viewPortfolio/'.$portfolio->id);?>"><?php echo $portfolio->title;?></a> 
											</td>
											</tr>
											<tr>
											 <td valign="top"><b><?php echo $this->lang->line('Description:');?> </b></td> <td valign="top"><?php echo word_limiter($portfolio->description,20);?></td>
											</tr>
											<tr>
											<td><b><?php echo $this->lang->line('Category:');?> </b></td> <td>
											<?php 
											$ids= explode(',',$portfolio->categories);		
											if(isset($categories) and $categories->num_rows()>0)
											{
											  foreach($categories->result() as $category)
											  {
											    if(in_array($category->id,$ids))
											      echo "<a href='".site_url('project/category/'.urlencode($category->category_name))."'>".$category->category_name."</a> ";
											  }
											} ?>
											</td>
											</tr>
											<?php if($portfolio->attachment1){?>
											<tr>
											<td valign="top"><b><?php echo $this->lang->line('Attachement1:');?> </b></td> <td valign="top"><img border="0" src="<?php echo pimage_url(get_thumb($portfolio->attachment1));?>" alt="attachment1"/></td>
											</tr>
											<?php } if($portfolio->attachment2){?>
											<tr>
											<td valign="top"><b><?php echo $this->lang->line('Attachement2:');?> </b></td> <td valign="top"><img border="0" src="<?php echo pimage_url(get_thumb($portfolio->attachment2));?>" alt="attachment2"/></td>
											</tr>
											<?php } ?>
										</tbody></table>
									</td></tr>
									<?php }
								}
								?>
							  </tbody></table>
							  </div>
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
</div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>