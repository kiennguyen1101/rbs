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
						  <form method="post" action="<?php echo site_url('programmer/retractBid');?>">
                            <div class="clsInnerCommon">
                              <h2><?php echo $this->lang->line('Retract Bid !');?></h2>
							  <p><?php echo $this->lang->line('Are you sure you want to retract your bid');?></p>
							  <p>
								<input type="submit" name="retractBid" value="<?php echo $this->lang->line('Retract');?>" class="clsSmall" />
								<input type="hidden" name="bidId" value="<?php echo $bidid;?>" />
							  </p>
                            </div>
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
      <!--END OF POST PROJECT-->
    </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>