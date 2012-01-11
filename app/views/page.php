<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<div id="main" >
  <?php
//Show Flash Message
if($msg = $this->session->flashdata('flash_message'))
{
	echo $msg;
}
?>
  <!--SIGN-UP-->
  
  <div id="selSignUp">
   <div class="clsPostProject">
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
						      
							  <!-- page content -->
								<?php //pr($page_content->result());
						        if(isset($page_content) and $page_content->num_rows()>0) 
								  { 
								  $pages = $page_content->row();
								  ?>
								  <h2><?php echo $pages->page_title; ?></h2> <?php 
								  }
								if(isset($page_content) and $page_content->num_rows()>0)
								{ 
									foreach($page_content->result() as $page)
									{
									
										echo $page->content;
									}
								}
								?>
								<!-- End of page content --> 
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
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>