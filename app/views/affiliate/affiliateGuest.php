<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<div>
<h3>Affiliate Guests</h3>
<br/>
<!-- project details -->
         <div class="clsAffiliateTable">
                            <table width="650" cellspacing="1" cellpadding="2">
                              <tbody>
                                <tr>
                                  <td width="148" align="center" class="dt">Email</td>
								  <td width="248" class="dt">Subject</td>
                                  <td width="248" align="center" class="dt">Questions</td>
                                </tr>
								<?
								$i=0;
								foreach($affiliate_guest as $k) {
									$affiliate_email_result['email'] 		=  $k->email;
									$affiliate_email_result['subject'] 		=  $k->subject;
									$affiliate_email_result['questions'] 	=  $k->questions ;
									$index = $i%2;
									
									if($index == 0) 
									$class = "dt1"; 
									else 
									$class = "dt2";
								?>
                                <tr>
                                  <td class="<? echo $class." "."dt0"; ?>"><? echo $affiliate_email_result['email']; ?></td>								
                                  <td class="<? echo $class; ?>"><? echo $affiliate_email_result['subject']; ?></td>
                                  <td  class="<? echo $class; ?>"><? echo $affiliate_email_result['questions']; ?></td>
                                </tr>
								
								<?
								$i++;
								}
								?>

                              </tbody>
                            </table>
      </div>
	  <div><a href="affiliate">Back</a></div>
         <!-- end of project details -->
</div>
<?php $this->load->view('footer'); ?>