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
            </tr><?php endif; //end check attachment         ?>
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
<?php if ($status == "Frozen") : ?>
                    <tr>

                        <td colspan="7" style="color:red;">
    <?php echo $this->lang->line('Frozen Message'); ?>
                            &nbsp;
    <?php $time_left = _date_diff($project->created + 60 * 60 * 24 * 7); ?>
    <?php
    $message = sprintf("%s: %d %s %02d:%02d", $this->lang->line('Time left'), $time_left['day'], $time_left['day'] > 1 ? $this->lang->line('days') : $this->lang->line('day'), $time_left['hour'], $time_left['min']
    );
    echo $message;
    ?>

                        </td>

                    </tr>
                        <?php else: ?>
                            <?php
                            $i = 0;
                            if (isset($bids) && $bids->num_rows() > 0) :

                                foreach ($bids->result() as $bid) :
                                    $i++;
                                    if ($i % 2 == 0)
                                        $class = "dt1 dt0";
                                    else
                                        $class = "dt2 dt0";
                                    ?>
                            <tr class="<?php echo $class; ?>">
                            <?php
                            print_certified($bid->user_id);
                            ?>
                                <td>
                                    <a href="<?php echo site_url('seller/viewProfile/' . $bid->uid); ?>">
                            <?php
                            echo $bid->user_name;
                            ?>

                                    </a> <?php
                print_certified($bid->user_id);
                            ?>

            <?php if (isset($user_details->certifyend))
                if ($user_details->certifyend >= get_est_time()) { ?> <img src="<?php echo image_url('certified.png'); ?>" alt="Special User" border="0" width="10"	height="13"> <?php } ?>			  

                                </td>  
                                <td> 
            <?php if (isset($bid->bid_desc))
                echo $bid->bid_desc; ?>
                                </td>
                                <td>$<?php echo $bid->bid_amount; ?></td>
                                <td>
                                    <?php
                                    if ($bid->bid_hours == 0 && $bid->bid_days == 0)
                                        echo $this->lang->line('Immediately');
                                    elseif ($bid->bid_days != 0)
                                        echo $bid->bid_days . $this->lang->line('days');
                                    ?>
                                    &nbsp;
            <?php if ($bid->bid_hours != 0)
                echo $bid->bid_hours . " " . $this->lang->line('hours'); ?>
                                </td>
                                <td><?php echo get_datetime($bid->bid_time); ?></td>
                                <td>
                                    <?php
                                    if ($bid->num_reviews == 0) :
                                        echo '(No Feedback Yet) ';
                                    else :
                                        ?>
                                        <a href="<?php echo site_url('seller/review/' . $bid->uid); ?>"> 
                                            <img height="7" border="0" width="81" alt="rating" src="<?php echo image_url('rating_' . $bid->user_rating . '.gif'); ?>"/> (
                                            <b><?php echo $bid->num_reviews; ?> </b> <?php echo $this->lang->line('reviews'); ?>)
                                        </a>
                                    <?php endif; //end check num reviews   ?>
                                    <!--
                                    <?php
                                    if (isset($this->loggedInUser->id)) :
                                        ?>
                                                <a href="<?php echo site_url('project/postBidReport/' . $bid->id); ?>">
                                                    <img src="<?php echo image_url('icons.png'); ?>" height="28" width="21" alt="Report Project Violation" title="Report Project Violation"/> 
                                                </a>
            <?php else : ?>
                                                <a href="<?php echo site_url('users/login'); ?>">
                                                    <img src="<?php echo image_url('icons.png'); ?>" height="28" width="21" alt="Report Project Violation"/> 
                                                </a>
                                    <?php endif; //end check logged in   ?> 
                                    -->
                                </td>
                            </tr>
        <?php endforeach; //end foreach bids   ?>
                            <?php endif; //end check bids    ?>

<?php endif; //end check status      ?>
            </tbody>
        </table>
    </div>
</div>

<!--END OF viewProject.php -->
                <?php $this->load->view('footer'); ?>    

                <?php if ($project->is_feature == 1) { ?>
    &nbsp;&nbsp;<img src="<?php echo image_url('featured.gif'); ?>" width="71" height="13" title="Featured project" alt="<?php echo $this->lang->line('Featured Project'); ?>" />
    <?php
}