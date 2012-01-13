<?php //$this->load->view('header'); ?>
<?php //$this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main"  class="">
<!--DEPOSITE PAGE-->
<div  id="selDeposit">
<!--PAYMENT TABLE-->
<b><big><b><?php echo $this->lang->line('Invoice Report') ?></b></big></b><p>
<?php
$userInfo = $loggedInUser;
 $condition1=array('subscriptionuser.username'=>$loggedInUser->id);
$certified1= $this->certificate_model->getCertificateUser($condition1);   

//Show Flash error Message no invoice
if($msg = $this->session->flashdata('flash_message'))
	{
	echo $msg;
    }
?>
<p><b><?php echo $this->lang->line('Company'); ?></b>  
<b><?php echo $this->lang->line('date');?></b><?php echo date('d-M-y'); ?></p>
<p><b><?php echo $this->lang->line('Cogzidel'); ?></b>
<b><?php echo $this->lang->line('Invoice No'); ?></b><?php if($invoice_no) echo $invoice_no; ?></p>
<p><b><?php echo $this->lang->line('Address'); ?></b> <b><?php echo $this->lang->line('To'); ?></b></p>
<p><?php echo $this->lang->line('Company Address'); ?>  <?php echo $user_name; ?><?php 
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
<table name="invoice_record" border="1" >
<tr><td><?php echo $this->lang->line('No'); ?></td><td><?php echo $this->lang->line('description'); ?></td><td><?php echo $this->lang->line('amount'); ?></td></tr>
<?php  $res = count($project_name); $netamount = 0;
for($i=1;$i<=$res;$i++)
  { $k=0;
  
    foreach($project_name as $name)
	  { 
	   $rec = explode('--',$name);
	   ?>
		 <tr><td><?php echo $k=$k+1; ?></td><td><?php echo $rec[0].' -- '.$rec[1]; ?></td><td><?php foreach($bidsProjects->result() as $row) {  if( $rec[0] == $row->project_id ) { echo $row->bid_amount; $netamount = $netamount + $row->bid_amount; } }//echo $this->lang->line('amount'); ?></td></tr> <?php 
      }break;
  }
 ?><br />
 <tr><td></td></tr>
 <tr><td></td><td><?php echo $this->lang->line('Total'); ?></td><td><?php echo $netamount; ?></td></tr>
</table>
<br />

<input type="button" name="print" value="<?php echo $this->lang->line('Print');?>" class="clsButton" onclick="javascript:printReport()" />
<!--END OF PAYMENT TABLE-->
</div>
<!--END OF DEPOSITE PAGE-->

</div>
<!--END OF MAIN-->
<script type="text/javascript">
function printReport()
{
	window.print();
}
</script>
<?php //$this->load->view('footer'); ?>