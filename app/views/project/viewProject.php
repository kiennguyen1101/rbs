<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<?php
//Get Project Info
$project = $projects->row();
?>

<link rel="stylesheet" href='<?php echo base_url(); ?>app/css/projectView.css' type="text/css" />

<div id="main">
    <div class="clsInnerpageCommon">        
        <?php if (!$project->flag) : ?>
            <h2><?php echo $this->lang->line('Product'); ?>: <?php echo $project->project_name; ?></h2>
        <?php else: ?>
            <h2><?php echo $this->lang->line('Job Listing'); ?>: <?php echo $project->project_name; ?></h2>
        <?php endif; // end check draft ?>

        <div class="clsHeading">
            <div class="icons left">
                <?php if ($project->flag == 0) : ?>
                    <h3><span class="clsViewPro"><?php echo $this->lang->line('View Product'); ?></span></h3>
                <?php endif; //end check flag ?>
            </div>
            <div class="icons right">
                <?php
                if (is_object($loggedInUser)) :
                    if (($project->project_status == 0 || $project->project_status == 1)
                            && $loggedInUser->id == $project->creator_id
                            && $project->seller_id == 0) :
                        ?>
                        <a class="clsIcons clsFloatLeft" href="<?php echo site_url('project/cancelProject/' . $project->id); ?>"><img alt="Cancel" title="Cancel" src="<?php echo image_url('cancel.png'); ?>"/></a>
                        <a class="clsIcons clsFloatLeft" href="<?php echo site_url('project/extendBid/' . $project->id); ?>"><img alt="Extend" title="Extend" src="<?php echo image_url('extend.png'); ?>"/></a>
                    <?php endif; //end check creator ?>
                    <?php if ($loggedInUser->role_name == 'buyer' and $project->flag == 0) : ?>

                        <span class="clsPostProject"> 
                            <a class="buttonBlack" href="<?php echo $loggedInUser->role_id ? site_url('users/login') : site_url('project/postProject/' . $project->id); ?>">
                                <span><?php echo $this->lang->line('Post Similar Product'); ?></span>
                            </a>
                        </span>
                        <a class="buttonBlack" href="<?php echo site_url('project/manageProject/' . $project->id); ?>">
                            <span><?php echo $this->lang->line('Manage'); ?></span>
                        </a>

                    <?php endif; //end check buyer ?>                        
                <?php endif; //end check object   ?>
                <span class="clsBookMark">
                    <a class="buttonBlack" href="<?php
                if (!isset($loggedInUser->role_id))
                    echo site_url('users/login'); else
                    echo site_url('bookMark/' . $project->id);
                ?>">
                        <span><?php echo $this->lang->line('Book Mark'); ?>
                        </span>
                    </a>
                </span>
                <!--
                <?php
                if (isset($loggedInUser->role_id)) {

                    if ($loggedInUser->role_id == '2') {
                        ?>

                                                                                                     <a href="<?php echo site_url('userList/addFavouriteUsers/' . $project->userid); ?>">
                                                                                                         <img src="<?php echo image_url('star_g.gif'); ?>" width="21" height="28" title="Add To Favourite"  alt="Add To Favourite" /> </a> <a href="<?php echo site_url('userList/addBlockedUsers/' . $project->userid); ?>">
                                                                                                         <img src="<?php echo image_url('block_g.gif'); ?>" width="21" height="28" alt="BlackList User" title="BlackList User"/> </a> <a href="<?php echo site_url('project/postReport/' . $project->id); ?>">
                                                                                                         <img src="<?php echo image_url('com_g.gif'); ?>" height="28" width="21" alt="Report Project Violation" title="Report Project Violation"/> 
                                                                                                     </a>

                        <?php
                    }
                } else {
                    ?>

                                                         <a href="<?php echo site_url('users/login'); ?>">
                                                             <img src="<?php echo image_url('star_g.gif'); ?>" width="21" height="28"  alt="Add To Favourite"/> 
                                                         </a> 
                                                         <a href="<?php echo site_url('users/login'); ?>">
                                                             <img src="<?php echo image_url('block_g.gif'); ?>" width="21" height="28" alt="BlackList User"/> 
                                                         </a> 
                                                         <a href="<?php echo site_url('users/login'); ?>">
                                                             <img src="<?php echo image_url('com_g.gif'); ?>" height="28" width="21" alt="Report Project Violation"/> 
                                                         </a>

                <?php }
                ?>
                
                -->
            </div>
        </div>
        <?php
        //Show Flash error Message for deposit minimum amount
        if ($this->session->flashdata('flash_message'))
            echo $this->session->flashdata('flash_message');
        ?>
    </div>

    <table cellspacing="1" cellpadding="2" width="96%" class="clsSitelinks">
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
            <?php if ($project->is_feature) : ?>
                <tr>
            <img src="<?php echo image_url('featured.gif'); ?>" width="71" height="13" title="Featured project" alt="<?php echo $this->lang->line('Featured Project'); ?>" />
            </tr>
        <?php endif; //End check featured    ?>
        <tr>
            <td class="dt2 dt0"><?php echo $this->lang->line('Status'); ?>:</td>
            <?php $status = getProjectStatus($project->project_status); ?>
            <td class="dt2" style="color: <?php echo ($status == "Open" ? "green" : "red"); ?>">
                <?php echo $status; ?>
            </td>

        </tr>
        <tr>
            <td class="dt2 dt0"><?php echo $this->lang->line('Created'); ?>:</td>
            <td class="dt2"><?php echo get_datetime($project->created); ?></td>
        </tr>
        <tr>
            <td class="dt2 dt0"><?php echo $this->lang->line('Created'); ?>:</td>
            <td class="dt2"><?php echo get_datetime($project->created); ?></td>
        </tr>
        <tr>
            <td class="dt1 dt0"><?php echo $this->lang->line('Project Creator'); ?>:</td>
            <td class="dt1">
                <a class="glow" href="<?php echo site_url('buyer/viewProfile/' . $project->userid); ?>"><?php echo $project->user_name; ?></a>
                <?php
                print_certified($project->userid);

                if ($project->num_reviews == 0) :
                    echo '(No Feedback Yet) ';
                else :
                    ?>
                    <img height="7" border="0" width="81" src="<?php echo image_url('rating_' . $project->user_rating . '.gif'); ?>" alt="rating" /> (
                    <b><?php echo $project->num_reviews; ?> </b> 
                    <a href="<?php echo site_url('buyer/review/' . $project->creator_id); ?>"><?php echo $this->lang->line('reviews'); ?></a> )
                <?php endif; //end check num_reviews  ?>
            </td>
        </tr>
        <tr>
            <td class="dt2 dt0"><?php echo $this->lang->line('Description'); ?>:</td>
            <td class="dt2"><?php echo nl2br($project->description); ?></td>									  

        </tr>
        <tr>
            <td class="dt1 dt0"><?php echo $this->lang->line('Product Type'); ?>:</td>
            <td class="dt1"><?php echo getCategoryLinks($project->project_categories); ?></td>								  
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
                    <a href="<?php echo site_url('users/getData/' . $project->id); ?>">Login</a><?php echo $this->lang->line('view'); ?>

                    <?php
                }
                ?> 
            </td>									  

        </tr>
        <!--	Puhal Changes Start for downloading the Project attachment file (Sep 20 Isssue 17)-->
        <?php if (isset($project->attachment_name)) : ?>
            <tr>
                <td class="dt1 dt0"><?php echo $this->lang->line('Project Attachment'); ?>:</td>
                <td class="dt1"><?php echo $project->attachment_name; ?><a href="<?php echo site_url('project/download/' . $project->attachment_url); ?>" class="clsDown"><img src="<?php echo base_url(); ?>app/css/images/download1.png" /></a></td>								  								
            </tr><?php endif; //end check attachment                   ?>
        <!--	Puhal Changes End for downloading the Project attachment file (Sep 20 Isssue 17)-->
        </tbody>
    </table>
    <br />
    <!--
    <p>
        <span class="clsPostProject">
            <a class="buttonBlackShad" href="<?php echo site_url('messages/project/' . $project->id); ?>">
                <span> <?php echo $this->lang->line('View Job Message Board'); ?> </span>
            </a>
        </span> 
        <span> <?php echo $this->lang->line('Message Posted'); ?>: 
            <b> <?php if (isset($totalMessages))
        echo $totalMessages; ?>  </b>
        </span>
    </p>    
    <br />
    -->
    <div class="clsInnerpageCommon">
        <?php
        //set up table template
        $tmpl = array(
            'table_open' => '<table cellpadding="2" cellspacing="1" width="96%">',
            'heading_row_start' => '<tr class="dt1 dt0">',
            'row_alt_start' => '<tr class="dt2 dt0">',
            'heading_cell_start' => '<th class="dt">',
        );
        $this->table->set_template($tmpl);

        //set default for empty cells
        $this->table->set_empty("&nbsp;");

        //set table heading
        $heading = array($this->lang->line('Sellers'),
            $this->lang->line('Message'),
            $this->lang->line('Bids'),
            $this->lang->line('Delivery Time'),
            $this->lang->line('Time of Bid'),
            $this->lang->line('Rating'),
            $this->lang->line('Options'),
        );
        //$this->table->make_columns($columns,count($columns));
        $this->table->set_heading($heading);

        $status_message = 0;

        //check if there is bids for this project
        if (isset($bids) && $bids->num_rows() > 0) {

            //traverse the bids
            foreach ($bids->result() as $bid) {

                if ($status == "Frozen") {
                    if (empty($loggedInUser))
                        break;
                    //stop processing a bid if project status is frozen and current user is not bidder
                    elseif ($bid->user_id != $loggedInUser->id)
                        continue;
                }

                //prepare table row
                $t_row = array();

                //username
                array_push($t_row, $bid->user_name);

                //bid description
                if (isset($bid->bid_desc))
                    array_push($t_row, $bid->bid_desc);

                //bid armount
                array_push($t_row, $bid->bid_amount);

                //bid time EST
                array_push($t_row, get_datetime($bid->bid_time));

                //feed back
                if ($bid->num_reviews == 0) {
                    array_push($t_row, '(No Feedback Yet) ');
                } else {
                    $bid_feedback = sprintf('<a href="%s"><img height="7" border="0" width="81" alt="rating" src="%s" /> ( <b>%s</b> %s) </a> ', site_url('seller/review/' . $bid->uid), image_url('rating_' . $bid->user_rating . '.gif'), $bid->num_reviews, $this->lang->line('reviews')
                    );
                    array_push($t_row, $bid_feedback);
                }

                //check user is logged on
                if (isset($loggedInUser->role_id)) {

                    //check for buyer and project status
                    if ($loggedInUser->role_name == 'buyer' && ($status == "Open" || $status == "Closed")) {
                        array_push($t_row, '<a class="glow" href="' . site_url('project/selProvider/' . $bid->id) . '">' . $this->lang->line('Pick Provider') . '</a>');
                    } elseif ($loggedInUser->role_id == '1' && $loggedInUser->id == $project->creator_id && $project->seller_id != 0) {
                        array_push($t_row, 'Already Picked');
                    }
                }
                
                //end iteration if we found
                if ($status == "Frozen" && $bid->user_id == $loggedInUser->id) {
                    $this->table->add_row($t_row);
                    $cell = array('data' => $this->lang->line('Frozen Message'), 'class' => 'help', 'colspan' => 8);
                    $this->table->add_row($cell);
                    $status_message = 1;
                    break;
                }

                $this->table->add_row($t_row);
            } //end foreach
            if (!$status_message && $status == "Frozen") {
                $cell = array('data' => $this->lang->line('Frozen Message'), 'class' => 'help', 'colspan' => 8);
                $this->table->add_row($cell);
            }
        } else {
            if (!isSeller()) {
                $cell = array('data' => $this->lang->line('no_bids1'), 'colspan' => 8);
                $this->table->add_row($cell);
            } else {
                $cell = array('data' => $this->lang->line('no_bids'), 'colspan' => 8);
                $this->table->add_row($cell);
            }
        }

        //print out the table
        echo $this->table->generate();
        ?>


    </div>
    <div class="clsInnerpageCommon">

        <?php
        if (!isSeller()) {
            $this->session->set_flashdata('flash_message', $this->common_model->flash_message('error', $this->lang->line('You must be logged in as a seller to place a bid')));
            //redirect('info');
        } else {
            if ($projectRow->project_status == 0) {
                if (count($tot) > 0)
                    $toDisp = $this->lang->line('Edit Bid');
                else
                    $toDisp = $this->lang->line('Place Bid');
                ?>
                <?php
                //Check for the project open date is end or not
                if ($projectRow->flag == 0) {

                    if (days_left($projectRow->enddate, $projectId) != 'Closed') {
                        ?>
                        <p><a href="<?php echo site_url('project/postBid/' . $projectId); ?>" class="buttonBlackShad"><span><?php echo $toDisp; ?></span></a></p>
                        <br />
                        <?php
                    }
                } else {
                    $created_date = $projectRow->created;
                    $end_date = $projectRow->enddate;
                    $next = $created_date + ($end_date * 24 * 60 * 60);
                    if (days_left($next, $projectId) != 'Closed') {
                        ?>
                        <p><a href="<?php echo site_url('project/postBid/' . $projectId); ?>" class="buttonBlackShad"><span><?php echo $toDisp; ?></span></a></p>
                        <br />
                        <?php
                    }
                }
            }
        }
        ?>  

    </div>
    <?php if ($project->is_feature == 1) { ?>
        &nbsp;&nbsp;<img src="<?php echo image_url('featured.gif'); ?>" width="71" height="13" title="Featured project" alt="<?php echo $this->lang->line('Featured Project'); ?>" />
    <?php } ?>
</div>

<!--END OF viewProject.php -->
<?php $this->load->view('footer'); ?>    

