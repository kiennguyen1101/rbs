<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<?php
		//Get Project Info
     	$project = $projects->row();
?>

<style type="text/css">
.clsIcons{
margin-right:20px;
}
</style>

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
                            <?php if (!$project->flag) { ?>							
                                 <h2><?php echo $this->lang->line('Product'); ?>: <?php echo $project->project_name; ?></h2><?php } else { ?>
                                 <h2><?php echo $this->lang->line('Job Listing'); ?>: <?php echo $project->project_name; ?></h2>
                             <?php } ?>

                             <div class="clsHeads clearfix">
                                 <div class="clsHeadingLeft clsFloatLeft">
                                     <?php if (!$project->flag) { ?>
                                         <h3><span class="clsViewPro"><?php echo 'View Product'; ?></span></h3><?php } else { ?>
                                         <h3><span class="clsViewPro"><?php echo 'View JobList'; ?></span></h3><?php } ?>
                                 </div> <!-- <div class="clsHeadingLeft clsFloatLeft"> -->
                                 <div class="clsHeadingRight clsFloatRight">
                                     <p class="clsFloatRight"> 
                                         <?php
                                         if (is_object($loggedInUser)) :
                                             if ($loggedInUser->role_id && (!$project->project_status || $project->project_status) && $loggedInUser->id == $project->creator_id && !$project->seller_id) {
                                                 ?>
                                                 <a class="clsIcons clsFloatLeft" href="<?php echo site_url('project/cancelProject/' . $project->id); ?>">
                                                     <img alt="Cancel" title="Cancel" src="<?php echo image_url('cancel.png'); ?>"/>
                                                 </a>
                                                 <a class="clsIcons clsFloatLeft" href="<?php echo site_url('project/extendBid/' . $project->id); ?>">
                                                     <img alt="Extend" title="Extend" src="<?php echo image_url('extend.png') ?>"/>
                                                 </a> 
                                             <?php } ?>    

    <?php if ($loggedInUser->role_name == "buyer" && !$project->flag) { ?>
                                                 <span class="clsPostProject"> 
                                                     <a class="buttonBlack" href="<?php if (!isset($loggedInUser->role_id))
            echo site_url('users/login'); else
            echo site_url('project/postProject/' . $project->id); ?>">
                                                         <span><?php echo $this->lang->line('Post Similar Product'); ?></span>
                                                     </a>
                                                 </span>

                                             <?php } /* else {?>
                                               <span class="clsPostProject">
                                               <a class="buttonBlack" href="<?php if (!isset($loggedInUser->role_id)) echo site_url('users/login'); else  echo site_url('joblist/postjoblist/'.$project->id); ?>">
                                               <span><?php echo $this->lang->line('Post Similar Job'); ?></span>
                                               </a>
                                               </span>

                                               <?php } */ ?>
                                             <?php endif; //End check loggedinuser ?>     
                                         <span class="clsManage">
                                             <?php
                                             if (is_object($loggedInUser)) {
                                                 //Make only this featured properties only for seller to make featured
                                                 if ($loggedInUser->role_name == 'buyer' && !$project->flag) {
                                                     ?>
                                                     <a class="buttonBlack" href="<?php echo site_url('project/manageProject/' . $project->id); ?>">
                                                         <span><?php echo $this->lang->line('Manage'); ?></span>
                                                     </a>                                           
                                                 <?php } else { ?>
                                                     <a class="buttonBlack" href="<?php echo site_url('joblist/manageJoblist/' . $project->id); ?>">
                                                         <span><?php echo $this->lang->line('Manage'); ?></span>
                                                     </a>                                     
    <?php } //End check role_name buyer  ?>
<?php } //End check loggedInUser     ?>   
                                     </span> <!--<span class="clsManage"> -->
                                         <span class="clsBookMark">
                                             <a class="buttonBlack" href="<?php if (!isset($loggedInUser->role_id))
    echo site_url('users/login'); else
    echo site_url('bookMark/' . $project->id); ?>">
                                                 <span><?php echo $this->lang->line('Book Mark'); ?></span>
                                             </a>
                                         </span>

<?php
if (is_object($loggedInUser)) {
    if ($loggedInUser->role_name == 'seller') {
        ?>

                                                 <a href="<?php echo site_url('userList/addFavouriteUsers/' . $project->userid); ?>">
                                                     <img src="<?php echo image_url('star_g.gif'); ?>" width="21" height="28" title="Add To Favourite"  alt="Add To Favourite" /> 
                                                 </a> 
                                                 <!--
                                     <a href="<?php echo site_url('userList/addBlockedUsers/' . $project->userid); ?>">
                                         <img src="<?php echo image_url('block_g.gif'); ?>" width="21" height="28" alt="BlackList User" title="BlackList User"/> 
                                     </a> 
                                              
                                     <a href="<?php echo site_url('project/postReport/' . $project->id); ?>">
                                         <img src="<?php echo image_url('com_g.gif'); ?>" height="28" width="21" alt="Report Product Violation" title="Report Product Violation"/> 
                                     </a>
                                                 -->
    <?php } //End check role_name seller  ?>
<?php } else { ?>

                                             <a href="<?php echo site_url('users/login'); ?>">
                                                 <img src="<?php echo image_url('star_g.gif'); ?>" width="21" height="28"  alt="Add To Favourite"/> 
                                             </a> 
                                             <!--
                                            <a href="<?php echo site_url('users/login'); ?>">
                                                <img src="<?php echo image_url('block_g.gif'); ?>" width="21" height="28" alt="BlackList User"/> 
                                            </a> 
                                             
                                            <a href="<?php echo site_url('users/login'); ?>">
                                                <img src="<?php echo image_url('com_g.gif'); ?>" height="28" width="21" alt="Report Product Violation"/> 
                                            </a>
                                             -->
                             <?php } //End check loggedInUser ?>
                                     </p>
                                                                             </p> <!-- <p class="clsFloatRight"> -->
                                 </div> <!-- <div class="clsHeadingRight clsFloatRight"> -->
                             </div> <!-- <div class="clsHeads clearfix"> -->
                                 <?php
                                 //Show Flash error Message  for deposit minimum amount
                                 if ($this->session->flashdata('flash_message')) {
                                     echo $this->session->flashdata('flash_message');
                                 }
                                 ?>	
                             <table cellspacing="1" cellpadding="2" width="96%" class="clsSitelinks">
<?php if ($project->flag == 0) { ?>
                                     <tbody>
                                         <tr>
                                             <td width="15%" class="dt"><?php echo $this->lang->line('Product Details'); ?></td>
                                             <td width="200" class="dt">&nbsp;</td>
                                         </tr>
                                         <tr>
                                             <td class="dt1 dt0"><?php echo $this->lang->line('Product'); ?> <?php echo $this->lang->line('ID'); ?>:</td>
                                             <td class="dt1"><?php echo $project->id; ?></td>
                                         </tr>
                                         <tr>
                                             <td class="dt2 dt0"><?php echo $this->lang->line('Product'); ?>:</td>
                                             <td class="dt2"><?php echo $project->project_name; ?> </td>

                                         </tr>
                                                 <?php if ($project->is_urgent || $project->is_feature) { ?>
                                             <tr>
                                                 <td class="dt1 dt0"><?php echo $this->lang->line('Type'); ?>:</td>
                                                 <td class="dt1"><?php if ($project->is_urgent == 1) { ?>  &nbsp;                                       
                                                         <img src="<?php echo image_url('urgent.gif'); ?>" width="56" height="14" title="Urgent project" alt="<?php echo $this->lang->line('Urgent Product'); ?>" />
                                             <?php }
                                             if ($project->is_feature) { ?>
                                                         &nbsp;&nbsp;
                                                         <img src="<?php echo image_url('featured.gif'); ?>" width="71" height="13" title="Featured project" alt="<?php echo $this->lang->line('Featured Product'); ?>" />  
                                                 <?php } ?>
                                                 </td>
                                             </tr>
    <?php } ?>                               
                                         <tr>
                                             <td class="dt2 dt0"><?php echo $this->lang->line('Status'); ?>:</td>
    <?php $status = getCurrentStatus($project->project_status, $project->seller_id, $project->id) ?>
                                             <td class="dt2"><?php echo '<b style="color:green;">' . $status['status'] . '</b>';
    if (isset($status['message']))
        echo $status['message']; ?> </td>
                                         </tr>
                                         <tr>
                                             <td class="dt1 dt0"><?php echo $this->lang->line('Budget'); ?>:</td>
                                             <td class="dt1"><?php if ($project->budget_min != '0')
        echo '$ ' . $project->budget_min; else
        echo 'N/A'; ?> -                                  <?php if ($project->budget_max != '0')
        echo '$ ' . $project->budget_max; else
        echo 'N/A'; ?></td>
                                         </tr>
                                         <tr>
                                             <td class="dt2 dt0"><?php echo $this->lang->line('Created'); ?>:</td>
                                             <td class="dt2"><?php echo get_datetime($project->created); ?></td>
                                         </tr>
                                         <tr>
                                             <td class="dt1 dt0"><?php echo $this->lang->line('Bidding Ends'); ?>:</td>
                                             <td class="dt1"><?php echo get_datetime($project->enddate); ?> (<?php echo '<b style="color:red;">' . days_left($project->enddate, $project->id) . '</b>'; ?>)</td>
                                         </tr>
                                         <tr>
                                             <td class="dt1 dt0"><?php echo $this->lang->line('Product Creator'); ?>:</td>
                                             <td class="dt1">
                                                 <a class="glow" href="<?php echo site_url('buyer/viewProfile/' . $project->userid); ?>">
                                                 <?php echo $project->user_name; ?>
                                                 </a>
                                                 <?php
                                                 $condition1 = array('subscriptionuser.username' => $project->userid);
                                                 $certified1 = $this->certificate_model->getCertificateUser($condition1);
                                                 if ($certified1->num_rows() > 0) {
                                                     foreach ($certified1->result() as $certificate) {
                                                         $user_id = $certificate->username;
                                                         $id = $certificate->id;
                                                         $condition = array('subscriptionuser.flag' => 1, 'subscriptionuser.id' => $id);
                                                         $userlists = $this->certificate_model->getCertificateUser($condition);
                                                         // get the validity
                                                         $validdate = $userlists->row();
                                                         $end_date = $validdate->valid;
                                                         $created_date = $validdate->created;
                                                         $valid_date = date('d/m/Y', $created_date);
                                                         $next = $created_date + ($end_date * 24 * 60 * 60);
                                                         $next_day = date('d/m/Y', $next) . "\n";
                                                         if (time() <= $next) {
                                                             ?>
                                                             <img src="<?php echo image_url('certified.gif'); ?>"  title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php echo $this->lang->line('Certified Member') ?>"/>
            <?php
            } //end if
        } //end foreach
    } //end certified1->num_rows 
    ?>
    <?php
    if ($project->num_reviews == 0) {
        echo '(No Feedback Yet) ';
    } else {
        ?>
                                                     <img height="7" border="0" width="81" src="<?php echo image_url('rating_' . $project->user_rating . '.gif'); ?>" alt="rating" /> (<b><?php echo $project->num_reviews; ?> </b> 
                                                     <a href="<?php echo site_url('buyer/review/' . $project->creator_id); ?>"><?php echo $this->lang->line('reviews'); ?>
                                                     </a> )
    <?php } ?></td>								  
                                         </tr>
                                         <tr>
                                             <td class="dt2 dt0"><?php echo $this->lang->line('Description'); ?>:</td>
                                             <td class="dt2"><?php echo nl2br($project->description); ?></td>		  
                                         </tr>
                                         <tr>
                                             <td class="dt1 dt0"><?php echo $this->lang->line('Job Type'); ?>:</td>
                                             <td class="dt1"><?php echo getCategoryLinks($project->project_categories); ?></td>
                                         </tr>
                                         <!-- Puhal Changes Start for downloading the Product attachment file (Sep 20 Isssue 17)-->
    <?php if (isset($project->attachment_name)) { ?>
                                             <tr>
                                                 <td class="dt1 dt0"><?php echo $this->lang->line('Product Attachment'); ?>:</td>
                                                 <td class="dt1"><?php echo $project->attachment_name; ?>
                                                     <a href="<?php echo site_url('project/download/' . $project->attachment_url); ?>" class="clsDown"><img src="<?php echo base_url(); ?>app/css/images/download1.png" />
                                                     </a>
                                                 </td>								  								
                                             </tr>
    <?php } ?>
                                         <!-- Puhal Changes End for downloading the Product attachment file (Sep 20 Isssue 17)-->
                                     </tbody>
                                         <?php } else { ?>
                                     <tbody>
                                         <tr>
                                             <td width="15%" class="dt"><?php echo $this->lang->line('JobListing  Details'); ?></td>
                                             <td width="200" class="dt">&nbsp;</td>
                                         </tr>
                                         <tr>
                                             <td class="dt1 dt0"><?php echo $this->lang->line('Job'); ?> <?php echo $this->lang->line('ID'); ?>:</td>
                                             <td class="dt1"><?php echo $project->id; ?></td>
                                         </tr>
                                         <tr>
                                             <td class="dt2 dt0"><?php echo $this->lang->line('Job Name'); ?>:</td>
                                             <td class="dt2"><?php echo $project->project_name; ?></td>
                                         </tr>
                                         <tr>
                                             <td class="dt1 dt0"><?php echo $this->lang->line('Status'); ?>:</td>
    <?php $status = getCurrentStatus($project->project_status, $project->seller_id, $project->id); ?>
                                             <td class="dt1"><?php echo '<b style="color:green;">' . $status['status'] . '</b>';
    if (isset($status['message']))
        echo $status['message']; ?> </td>
                                         </tr>							  
                                         <tr>
                                             <td class="dt2 dt0"><?php echo $this->lang->line('Budget'); ?>:</td>
                                             <td class="dt2"><?php echo $project->salary; ?></td>
                                         </tr>
                                         <tr>
                                             <td class="dt1 dt0"><?php echo $this->lang->line('Created'); ?>:</td>
                                             <td class="dt1"><?php echo get_datetime($project->created); ?></td>
                                         </tr>					
                                         <tr>
                                             <td class="dt2 dt0"><?php echo $this->lang->line('Closed'); ?>:</td>
                                             <td class="dt2"><?php echo get_datetime($project->enddate); ?> (<?php echo '<b style="color:red;">' . days_left($project->enddate, $project->id) . '</b>'; ?>) </td>
                                         </tr>
                                         <tr>
                                             <td class="dt1 dt0"><?php echo $this->lang->line('Product Creator'); ?>:</td>
                                             <td class="dt1">
                                                 <a class="glow" href="<?php echo site_url('buyer/viewProfile/' . $project->userid); ?>">
                                                 <?php echo $project->user_name; ?>
                                                 </a>
                                                 <?php
                                                 $condition1 = array('subscriptionuser.username' => $project->userid);
                                                 $certified1 = $this->certificate_model->getCertificateUser($condition1);
                                                 if ($certified1->num_rows() > 0) {
                                                     foreach ($certified1->result() as $certificate) {
                                                         $user_id = $certificate->username;
                                                         $id = $certificate->id;
                                                         $condition = array('subscriptionuser.flag' => 1, 'subscriptionuser.id' => $id);
                                                         $userlists = $this->certificate_model->getCertificateUser($condition);

                                                         // get the validity
                                                         $validdate = $userlists->row();
                                                         $end_date = $validdate->valid;
                                                         $created_date = $validdate->created;
                                                         $valid_date = date('d/m/Y', $created_date);
                                                         $next = $created_date + ($end_date * 24 * 60 * 60);
                                                         $next_day = date('d/m/Y', $next) . "\n";
                                                         if (time() <= $next) {
                                                             ?>
                                                             <img src="<?php echo image_url('certified.gif'); ?>"  title="<?php echo $this->lang->line('Certified Member') ?>" alt="<?php echo $this->lang->line('Certified Member') ?>"/>
            <?php
            } //end check time
        } //end foreach
    } //end certified1 num_rows 
    ?>

                                                 <?php
                                                 if ($project->num_reviews == 0) {
                                                     echo '(No Feedback Yet) ';
                                                 } else {
                                                     ?>
                                                     <img height="7" border="0" width="81" src="<?php echo image_url('rating_' . $project->user_rating . '.gif'); ?>" alt="rating" /> 
                                                     (<b><?php echo $project->num_reviews; ?> </b> 
                                                     <a href="<?php echo site_url('buyer/review/' . $project->creator_id); ?>">
        <?php echo $this->lang->line('reviews'); ?>
                                                     </a> )
    <?php } ?></td>								  
                                         </tr>
                                         <tr>
                                             <td class="dt2 dt0"><?php echo $this->lang->line('Description'); ?>:</td>
                                             <td class="dt2"><?php echo nl2br($project->description); ?></td>	  
                                         </tr>
                                         <tr>
                                             <td class="dt2 dt0"><?php echo $this->lang->line('Contact'); ?>:</td>
                                             <td class="dt2">
                                         <?php
                                         if (isset($this->loggedInUser->id)) {
                                             if ($project->contact == '') {
                                                 echo "No Contact Found.";
                                             } else {
                                                 echo nl2br($project->contact);
                                             }
                                         } else {
                                             ?>
                                                     <a href="<?php echo site_url('users/getData/' . $project->id); ?>">Login</a>
        <?php echo $this->lang->line('view'); ?>                               
                                                     }
                                                     ?> 
                                                 </td>									  
                                             </tr>
                                             <!--	Puhal Changes Start for downloading the Product attachment file (Sep 20 Isssue 17)-->
        <?php if (isset($project->attachment_name)) { ?>
                                                 <tr>
                                                     <td class="dt1 dt0"><?php echo $this->lang->line('Product Attachment'); ?>:</td>
                                                     <td class="dt1"><?php echo $project->attachment_name; ?>
                                                         <a href="<?php echo site_url('project/download/' . $project->attachment_url); ?>" class="clsDown"><img src="<?php echo base_url(); ?>app/css/images/download1.png" />
                                                         </a>
                                                     </td>								  								
                                                 </tr>
        <?php }
    } ?>
                                     </tbody>
                                 </table>
                                 <p>
                                     <span class="clsPostProject">
                                         <a class="buttonBlackShad" href="<?php echo site_url('messages/project/' . $project->id); ?>">
                                             <span><?php echo $this->lang->line('View Job Message Board'); ?></span>
                                         </a>
                                     </span> 
                                     <span><?php echo $this->lang->line('Message Posted'); ?>: 
                                         <b>
    <?php if (isset($totalMessages))
        echo $totalMessages; ?>
                                         </b>
                                     </span>
                                 </p>
                                 <br />
                                 <br />
                             </div>
                           
                           <!-- Load view my bids -->
                        <div class="clsInnerCommon">
                          <!-- RC -->
                          <div class="block">
                            <div class="black_t">
                              <div class="black_r">
                                <div class="black_b">
                                  <div class="black_l">
                                    <div class="black_tl">
                                      <div class="black_tr">
                                        <div class="black_bl">
                                          <div class="black_br clsZero">
                                            <div class="cls100_p">
                                                <h3>
                                                    <span class="clsPayments"><?php echo $this->lang->line('Product Bids'); ?></span>
                                                </h3>
                                                    <table cellspacing="1" cellpadding="2" width="96%">
                                                        <tbody>
                                                            <tr>
                                                                <td width="10%" class="dt"><?php echo $this->lang->line('Sellers'); ?></td>
                                                                <td width="20%" class="dt"><?php echo $this->lang->line('Message'); ?></td>
                                                                <td width="10%" class="dt"><?php echo $this->lang->line('Bids'); ?></td>
                                                                <td width="10%" class="dt"><?php echo $this->lang->line('Delivery Time'); ?></td>
                                                                <td width="15%" class="dt"><?php echo $this->lang->line('Time of Bid'); ?></td>
                                                                <td width="20%" class="dt"><?php echo $this->lang->line('Rating'); ?></td>
                                                                <td width="15%" class="dt"><?php echo $this->lang->line('Options'); ?></td>
                                                            </tr>                                                                                  
                                                            <?php
                                                                if (isset($bids) and $bids->num_rows() > 0) {
                                                                    foreach ($bids->result() as $bid) {
                                                                        $i++;
                                                                        if ($i % 2 == 0)
                                                                            $class = "dt1 dt0";
                                                                        else
                                                                            $class = "dt2 dt0";
                                                                        ?>
                                                                        <tr class="<?php echo $class; ?>">
                                                                            <?php
                                                                            $condition1 = array('subscriptionuser.username' => $bid->user_id);
                                                                            $certified1 = $this->certificate_model->getCertificateUser($condition1);
                                                                            if ($certified1->num_rows() > 0) {
                                                                                foreach ($certified1->result() as $certificate) {
                                                                                    $user_id = $certificate->username;
                                                                                    $id = $certificate->id;
                                                                                    $condition = array('subscriptionuser.flag' => 1, 'subscriptionuser.id' => $id);
                                                                                    $userlists = $this->certificate_model->getCertificateUser($condition);
                                                                                    // get the validity
                                                                                    $validdate = $userlists->row();
                                                                                    $end_date = $validdate->valid;
                                                                                    $created_date = $validdate->created;
                                                                                    $valid_date = date('d/m/Y', $created_date);
                                                                                    $next = $created_date + ($end_date * 24 * 60 * 60);
                                                                                    $next_day = date('d/m/Y', $next) . "\n";
                                                                                    if (time() <= $next) {
                                                                                        ?>
                                                                                        
                                                                                    <?php
                                                                                    } //end if check time
                                                                                } //end foreach certificate
                                                                            } //end if check certificate
                                                                        } //end foreach bid
                                                                    } //end if check bid
                                                                    ?>
                                                            </tr>
                                                      
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           
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