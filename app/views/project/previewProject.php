<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<?php
		//Get Project Info
     	$project = $projects->row();
?>
<div id="main" >
  <!--PROJECT DETAILS-->
   <div class="clsEditProfile">
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
							<h2> <?php echo $this->lang->line('Preview Project');?> </h2>
							<h3><span class="clsFeatured"><?php echo $this->lang->line('Project Details');?></span></h3>
							<p><span><?php echo $this->lang->line('Project');?>:</span> <?php echo $project->project_name; ?></p>
							<p><span><?php echo $this->lang->line('Status:');?></span>
							<?php echo 'Pending'?></p>
							<p><span><?php echo $this->lang->line('Budget:');?></span><?php if($project->budget_min != '0' and $project->budget_max!='0') { echo '$'.$project->budget_min.' - '.$project->budget_max; } else echo 'N/A'; ?></p>
							<p><span><?php echo $this->lang->line('Created:');?></span><?php echo get_date(time())." Today "; ?></p>
							<p><span><?php echo $this->lang->line('Bidding Ends:');?></span><?php echo date('d/M/Y',13);?> (<?php $val = date('j',date('d/M/Y')-date('d/M/Y')); if($val > 1) echo $val." days"; else $val." day";?> <?php echo $this->lang->line('Left');?>)</p>
							<p><span><?php echo $this->lang->line('Project Creator:');?></span><a href="<?php echo site_url('buyer/viewProfile/'.$loggedInUser->id);?>"><?php echo $loggedInUser->user_name; ?></a><?php 
							    $condition1=array('subscriptionuser.username'=>$loggedInUser->id);
								$certified1= $this->certificate_model->getCertificateUser($condition1);
								if($certified1->num_rows()>0)
			                    {
							       foreach($certified1->result() as $certificate)
				                     {
									$user_id=$certificate->username;
									$id=$certificate->id;
									$condition=array('subscriptionuser.flag'=>1,'subscriptionuser.id'=>$id);
					                $userlists= $this->certificate_model->getCertificateUser($condition);
									// get the validity
									$validdate=$userlists->row();
									$end_date=$validdate->valid; 
									$created_date=$validdate->created;
									$valid_date=date('d/m/Y',$created_date);
								    $next=$created_date+($end_date * 24 * 60 * 60);
									$next_day= date('d/m/Y', $next) ."\n";
							        if(time()<=$next)
								    {?>
								<img src="<?php echo image_url('certified.gif');?>"  title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php  echo $this->lang->line('Certified Member')?>"/>
								<?php } 
								  }
								   }?>
</p>
							<p><span><?php echo $this->lang->line('Description:');?></span><?php echo $project->description; ?></p>
							<p><span><?php echo $this->lang->line('Job Type:');?></span><?php echo $project->project_categories;?></p>
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