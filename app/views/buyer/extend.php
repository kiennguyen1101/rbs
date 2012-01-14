<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<?php
if(isset($project) and $project->num_rows()>0)
{
	$project = $project->row();
	}
?>
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
						  <form method="post">
                        <div class="clsInnerCommon">
                          <h2><?phpecho $this->lang->line('Extend');?> - <?php echo $project->project_name;?></h2>
                          <p><?php echo $this->lang->line("Extend this project by");?>
                            <select name="openDays">
							<?php for($i=1;$i<=$project_period;$i++){?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php } ?>
							</select>
                            <?php echo $this->lang->line("days");?></span> </p>
                          <p>
                            <input type="submit" name="extend" value="<?php echo $this->lang->line('Submit');?>" class="clsMini" > 
							<input type="hidden" name="projectid" value="<?php echo $project->id;?>">
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
    <!--END OF POST PROJECT-->
  </div>
  <!--END OF MAIN-->
</div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>
