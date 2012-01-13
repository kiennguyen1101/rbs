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
								<h2><?php echo $this->lang->line('Delete Project') ?></h2> <?php
								 if(isset($projects))
								  { 
								    foreach($projects->result() as $res)
									  { ?>
									  	<p><span><b><?php echo $this->lang->line('Project Name') ?></b></span>    	<a href="<?php echo site_url('project/view/'.$res->id); ?>"><?php echo $res->project_name;?></a></p>
										
										<?php 
									   }
								  } ?>
								<form action="<?php echo site_url('project/cancelProject/'.$res->id); ?>" method="post" name="myform" id="myform">
									<p>
									  <label><b><?php echo $this->lang->line('are you sure to delete'); ?> </b></label> &nbsp;&nbsp;
									  <input type="submit" name="delete" value="Yes" class="clsSmall" />
									  <input type="submit" name="viewProject" value="No" class="clsSmall" onclick="javascript:submit1(<?=$res->id?>)"/>
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
<script>
function submit1(id)
{
	document.myform.action='<?php echo site_url('project/view'); ?>/'+id;
}
</script>
