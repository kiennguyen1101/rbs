<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->

<div id="main" class="">
	  <!--ACCOUNT MANAGEMENT -->
  <div id="selAccountManage">
	  <?php $this->load->view('innerMenu');
       $transactions1 = $transactions;	  
	   $transactions = $transactions->row();?>
	 
	  <?php  
	 //Show Flash error Message  after login successfully
	 if($msg = $this->session->flashdata('flash_message'))
		{
		echo $msg;
		}
	  ?>
      <h2><?php echo $this->lang->line('Buyer Account Management');?></h2>
	  <p class="clsTopMar"><?php echo $this->lang->line('Welcome');?> <?php echo $loggedInUser->name?>!  <font color="#6b80a1"><?php echo $this->lang->line('Local time');?> </font><?php echo show_date(time()); ?></p>
	 
	   
	  <!--MY ESCROW ACCOUNT -->	  
  	  <div class="block clsTopMar">
        <div class="black_t">
          <div class="black_r">
            <div class="black_b">
              <div class="black_l">
                <div class="black_tl">
                  <div class="black_tr">
                    <div class="black_bl">
                      <div class="black_br">
                        <div class="cls100_p">
						  <div id="selEscrowAccount">
						     <h2><?php echo $this->lang->line('My Account Transaction');?></h2>
						     <p class="clsEven clsClearFix">
		 					  	<b>
							      <span class="clsBudget"><?php echo $this->lang->line('Buyer');?></span>								  
								  <span class="clsEswAmt"><?php echo $this->lang->line('Amount');?></span>
						          <span class="clsEswDate"><?php echo $this->lang->line('Date');?></span>
								  <span class="clsEswProject"><?php echo $this->lang->line('Project');?></span>  							      
							      <span class="clsEswStatus"><?php echo $this->lang->line('Status');?></span>
								</b>
						    </p>
						      <?php $i=1; $k=0;
						      foreach($transactions1->result() as $res)
								{ $i=$i+1; $k=$k+1;
								  if($i%2 == 0)
								    $class ="clsAdd clsClearFix";
								  else
								    $class ="clsEven clsClearFix";	?>
						          <p class="<?php echo $class; ?>">
								  <span class="clsBudget"><?php echo $res->creator_id; ?></span>								  
								  <span class="clsEswAmt"><?php echo $res->amount; ?></span>
								  <span class="clsEswDate"><?php echo get_datetime($transactions->transaction_time); ?></span>
								  <span class="clsEswProject"><a href="<?php site_url('project/view/'.$res->project_id); ?>"><?php foreach($projectList->result() as $result) { if($result->id == $res->project_id) echo $result->project_name; } ?></a></span>  							      
								  <span class="clsEswStatus"><?php echo $res->status; ?> </span> 
							      </p>  <?php 
								  
								} ?>	 	  
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
	  <!--END OF -MY ESCROW ACCOUNT -->
  </div>	
	  <!--END OF ACCOUNT MANAGEMENT-->
    </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>
