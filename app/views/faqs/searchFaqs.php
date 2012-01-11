<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main">
	  <!--POST PROJECT-->
      <div class="clsContact">
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
							<h2><?php echo $this->lang->line('Search Result');?></h2>
							 <h3><span class="clsViewPro"><?php echo $this->lang->line('Search result for');?> <b><?php echo $keyword;?></b></span></h3>
								  <!--FAQ ANSWER-->
								  <div id="selFAQ" class="clsMarginTop">
								  <ul>
								  <?php
									if(isset($faqs) and $faqs->num_rows()>0)
									{
									foreach($faqs->result() as $faq)
									{
								  ?>
								  <li><a href="<?php echo site_url('faq/view/'.$faq->id); ?>"><?php echo highlight_phrase($faq->question,$keyword,'<b>','</b>')?></a></li>
								  <?php } }
								  else
								  echo $this->lang->line('No records found');?>
								  </ul>
								  </div>
								  <!--END OF FAQ ANSWER-->
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
        </div>
      </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>