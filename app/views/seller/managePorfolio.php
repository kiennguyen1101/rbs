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
                          <h2><?php echo $this->lang->line('manage_portfolio'); ?></h2>
                          <?php
								//Show Flash Message
								if($msg = $this->session->flashdata('flash_message'))
								{
									echo $msg;
								}
								$editPortfolio = $editPortfolio->row();
							   ?>
                          <table cellspacing="0" cellpadding="1"  style="border: 1px solid #BFBFBF" width="80%;">
                            <tbody>
                              <?php
								
									if(isset($portfolio) and $portfolio->num_rows()>0)
								
									{
								
									foreach($portfolio->result() as $portfolio)
								
										{
								
									?>
                              <tr>
                                <td><table style="border-bottom:1px solid #BFBFBF;" cellspacing="0" cellpadding="0" width="100%">
                                    <tbody>
                                      <tr>
                                        <td align="center" width="20%" style="padding:20px 0 0;" ><a href="<?php echo site_url('seller/viewPortfolio/'.$portfolio->id);?>"> <img border="0" src="<?php echo pimage_url(get_thumb($portfolio->main_img));?>"/></a></td>
                                        <td style="padding:10px 0 0;" width="70%" ><table border="0" cellpadding="5" cellspacing="0" style="border:none;" width="90%">
                                            <tbody>
                                              <tr>
                                                <td width="15%"><b><?php echo $this->lang->line('Title:');?> </b></td>
                                                <td valign="top"><?php echo $portfolio->title;?></td>
                                              </tr>
                                              <tr>
                                                <td valign="top"><b><?php echo $this->lang->line('Description:');?> </b></td>
                                                <td valign="top"><?php echo word_limiter($portfolio->description,20);?></td>
                                              </tr>
                                              <tr>
                                                <td><b><?php echo $this->lang->line('Category:');?> </b></td>
                                                <td><?php 
					
								$ids= explode(',',$portfolio->categories);		
					
									if(isset($categories) and $categories->num_rows()>0)
					
									{
					
									?>
                                                  <?php 
					
									foreach($categories->result() as $category)
					
									{
					
									if(in_array($category->id,$ids))
					
									echo "<a href=".site_url('project/category/'.urlencode($category->category_name)).">".$category->category_name."</a> ";
					
									} ?>
                                                  <?php } ?>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>&nbsp;</td>
                                                <td><a class="buttonBlackShad" href="<?php echo site_url('seller/managePortfolio/'.$portfolio->id);?>"><span><?php echo $this->lang->line('Edit');?></span></a> <a class="buttonBlackShad" href="<?php echo site_url('seller/deletePortfolio/'.$portfolio->id);?>" onclick="return confirm('Do you want to delete this portfolio?');"><span><?php echo $this->lang->line('Delete');?></span></a></td>
                                              </tr>
                                            </tbody>
                                          </table></td>
                                      </tr>
                                    </tbody>
                                  </table></td>
                              </tr>
                              <?php }
					
						}
					
						?>
                            </tbody>
                          </table>
                          <div class=" clsEditProfile">
                            <?php if(is_object($editPortfolio)){?>
                            <form method="post" action="<?php echo site_url('seller/editPortfolio');?>" enctype="multipart/form-data" name="myForm">
                              <?=form_token();?>
                              <p><span><?php echo $this->lang->line('title'); ?></span>
                                <input type="text" size="35" value="<?php echo $editPortfolio->title; ?>" name="title"/>
                                <?php echo form_error('title'); ?> </p>
                              <p><span><?php echo $this->lang->line('thumbnail'); ?>:</span>
                                <input TYPE="file" NAME="thumbnail" />
                                (Allowed Types : JPEG, JPG, GIF, PNG ) <?php echo form_error('thumbnail'); ?> </p>
                              <p><img src="<?php echo pimage_url(get_thumb($editPortfolio->main_img));?>" /> </p>
                              <p><span><?php echo $this->lang->line('description'); ?></span>
                                <textarea rows="10" name="description" cols="60" onKeyDown="textCounter(document.myForm.description,document.myForm.remLen2,250)" onKeyUp="textCounter(document.myForm.description,document.myForm.remLen2,250)"><?php echo $editPortfolio->description; ?></textarea>
                                <?php echo form_error('description'); ?> </p>
                              <p> <span>&nbsp;</span>
                                <input readonly type="text" name="remLen2" size="3" maxlength="3" value="250">
                                &nbsp;<?php echo $this->lang->line('Characters Left') ?></p>
                              <div id="selAreaExpertise">
                                <p><span><?php echo $this->lang->line('category'); ?></span> <small>(You can make multiple selections.)</small></p>
                                <table>
                                  <?php
								$i=0;
								$ids= explode(',',$editPortfolio->categories);
					
										if(isset($categories) and $categories->num_rows()>0)
					
										{   
					
											foreach($categories->result() as $category)
					
											{ 
											 if($i%3 ==0)
												echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
												?>
                                  <td><input type="checkbox" name="categories[]" value="<?php echo $category->id; ?>" <?php if(in_array($category->id,$ids)) echo 'checked="checked"'; ?> <?php echo set_checkbox('categories[]', $category->id); ?>/>
                                      <?php echo $category->category_name; ?></td>
                                    <?php if($i%3 ==2)
												   echo '</tr>';
											   $i = $i + 1;   
											}//Foreach End
										}//If End
									?>
                                </table>
                                <?php echo form_error('categories[]'); ?>
                                <p> <span><strong><?php echo $this->lang->line('Attachment'); ?>#1</strong></span><strong>:</strong>
                                  <input type="file" name="attachment1" />
                                  (Allowed Types : JPEG, JPG, GIF, PNG )<?php echo form_error('attachment1'); ?> &nbsp;
                                  <?php if($editPortfolio->attachment1){?>
                                  <img src="<?php echo pimage_url(get_thumb($editPortfolio->attachment1));?>"/><a href="<?php echo site_url('seller/removeAttachment/1/'.$editPortfolio->id);?>" onclick="return confirm('Do you want to delete this image?');"><img src="<?php echo image_url('delete.png');?>" border="0" alt="delete" title="delete"/></a>
                                  <?php } ?>
                                </p>
                                <p> <span class="clsTopMargin"><strong><?php echo $this->lang->line('Attachment'); ?>#2</strong></span><strong>:</strong>
                                  <input type="file" name="attachment2" />
                                  (Allowed Types : JPEG, JPG, GIF, PNG )<?php echo form_error('attachment2'); ?> &nbsp;
                                  <?php if($editPortfolio->attachment2){?>
                                  <img src="<?php echo pimage_url(get_thumb($editPortfolio->attachment2));?>"/><a href="<?php echo site_url('seller/removeAttachment/2/'.$editPortfolio->id);?>" onclick="return confirm('Do you want to delete this image?');"><img src="<?php echo image_url('delete.png');?>" border="0" alt="delete" title="delete"/></a>
                                  <?php } ?>
                                </p>
                              </div>
                              <p>
                                <input type="hidden" name="confirmKey" value="<?php echo $this->uri->segment(3); ?>" />
                                <input type="submit" class="clsSmall" value="<?php echo $this->lang->line('Edit'); ?>" name="editPortfolio" />
                                <input type="hidden" name="portid" value="<?php echo $editPortfolio->id; ?>" />
                              </p>
                            </form>
                            <?php } else{?>
                            <form method="post" action="" enctype="multipart/form-data" name="myForm">
                              <?=form_token();?>
                              <p><span><?php echo $this->lang->line('title'); ?>:</span>
                                <input name="title" type="text" value="<?php echo set_value('title'); ?>" size="35" maxlength="25"/>
                                <?php echo form_error('title'); ?> </p>
                              <p> <span><?php echo $this->lang->line('thumbnail'); ?>:</span>
                                <input TYPE="file" NAME="thumbnail" />
                                (Allowed Types : JPEG, JPG, GIF, PNG ) <?php echo form_error('thumbnail'); ?> </p>
                              <p><span><?php echo $this->lang->line('description'); ?>:</span>
                                <textarea rows="10" name="description" cols="60" onKeyDown="textCounter(document.myForm.description,document.myForm.remLen2,250)" onKeyUp="textCounter(document.myForm.description,document.myForm.remLen2,250)"><?php echo set_value('description'); ?></textarea>
                                <?php echo form_error('description'); ?></p>
                              <p><span>&nbsp;</span>
                                <input readonly type="text" name="remLen2" size="3" maxlength="3" value="250">
                                &nbsp;<?php echo $this->lang->line('Characters Left') ?></p>
                              <div id="selAreaExpertise">
                                <p><span><?php echo $this->lang->line('category'); ?>:</span></p>
                                <p><small><?php echo $this->lang->line('(You can make multiple selections.)');?></small></p>
                                <p><span>&nbsp;</span>
                                <table>
                                  <?php $i=0;
					
										if(isset($categories) and $categories->num_rows()>0)
					
										{
					
											foreach($categories->result() as $category)
					
											{
					
									
					
							  if($i%3 ==0)
												echo '<tr><td>&nbsp;</td>';
												?>
                                  <td><input type="checkbox" name="categories[]" value="<?php echo $category->id; ?>" <?php //if(in_array($category->id,$ids)) echo 'checked="checked"'; ?> <?php echo set_checkbox('categories[]', $category->id); ?>/>
                                      <?php echo $category->category_name; ?></td>
                                    <?php if($i%3 ==2)
												   echo '</tr>';
											   $i = $i + 1;   
					
											}//Foreach End
					
										}//If End
					
									?>
                                </table>
                                </p>
                                <?php echo form_error('categories[]'); ?>
                                <p><span><?php echo $this->lang->line('Attachment'); ?><?php echo $this->lang->line('#1');?>:</span>
                                  <input TYPE="file" NAME="attachment1" />
                                  (Allowed Types : JPEG, JPG, GIF, PNG ) <?php echo form_error('attachment1'); ?> </p>
                                <p><span><?php echo $this->lang->line('Attachment'); ?><?php echo $this->lang->line('#2');?>:</span>
                                  <input TYPE="file" NAME="attachment2" />
                                  (Allowed Types : JPEG, JPG, GIF, PNG ) <?php echo form_error('attachment2'); ?> </p>
                              </div>
                              <p><span>&nbsp;</span>
                                <input type="hidden" name="confirmKey" value="<?php echo $this->uri->segment(3); ?>" />
                                <input type="submit" class="clsSmall" value="<?php echo $this->lang->line('Submit'); ?>" name="createPortfolio" />
                              </p>
                            </form>
                            <?php } ?>
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

<?php $this->load->view('footer'); ?>
