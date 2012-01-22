<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/jquery.validate.min.js"> </script>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/jquery.maskedinput-1.3.min.js"> </script>

<script type="text/javascript">
    jQuery(document).ready(function($){
        
        sendable = true;
        
        $('#message').html('');
        $('input[name="discount_at[]"]').mask("9?99");
        $('input[name="discount_value[]"]').mask("9?9.99");
        
        $('input[name="discount_at[]"]').change(function(){
            check_discounted_items();
        });
        
        $('input[name="quantity"]').change(function(){
            check_discounted_items();
        });
        
        if ($('#discount_check').is(':checked')) 
            $('#discount_value').show();
        else 
            $('#discount_value').hide();
        
        $('#discount_check').click(function(){
           
            if ($(this).is(':checked')) {
                $('#discount_value').show();               
            }
                
            else {
                $('#discount_value').hide();
            }
               
            
        });
        
        var v = $("#bid_form").validate({
            errorClass: "help",
            onkeyup: false,
            onblur: false, 
            messages: {
                bidAmt: "Please input your basic price",
                quantity: "Please enter number of your products"
            },
            submitHandler: function(form) {
                
                if (v.form() && sendable) {                             
                    form.submit();
                }
                       
            }
        });
        
        function check_discounted_items() {
            var total_val = 0;
            $('#message').html('');
            $('input[name="discount_at[]"]').each(function(index){
                if ($(this).val() == '') 
                    return;
                total_val += parseFloat($(this).val());
            });
         
            if (total_val >  $('input[name="quantity"]').val()) {
                 $('#message').html('<p class="help">Your discounted items must not exceed the quantity of your items</p>');
                 sendable = false;
            }
               
            else {
                $('#message').html('');
                sendable = true;
            }
                
        }
        
    });
</script>
<?php
//set action to send to createBid or editBid
if (empty($bids)) {
    $action = site_url('project/createBid');
} else {
    $bid = $bids->row();
    $action = site_url('project/editBid');
}

//Get Project Info
if (!empty($projects))
    $project = $projects->row();
?>
<!--MAIN-->
<?php
//Show Flash Message
if ($msg = $this->session->flashdata('flash_message')) {
    echo $msg;
}
?>
<div id="main">
    <!--POST PROJECT-->
    <div class="clsInnerpageCommon">

        <h2><?php echo $this->lang->line('Bid on Product'); ?>: <?php echo $project->project_name; ?></h2>
        <div class="clsForm">
            <form method="post" action="<?php echo $action; ?>" name="myForm" id="bid_form">

                <!--PROJECT MESSAGE BOARD-->
                <div id="selPMB" class="clsMarginTop">

                    <div id="message"><?php echo validation_errors(); ?> </div>

                    <p class="clsSitelinks"><?php echo $this->lang->line('You are currently logged in as'); ?> 
                        <a class="glow" href="<?php echo site_url('seller/viewProfile/' . $this->loggedInUser->id); ?>">
                            <?php if (isset($loggedInUser) and is_object($loggedInUser))
                                echo $loggedInUser->user_name; ?>
                        </a> 
                        (<a href="<?php echo site_url('users/logout'); ?>"><?php echo $this->lang->line('Logout') ?></a>). 
                    </p>

                    <p> 
                        <label><?php echo $this->lang->line('Your Base Price'); ?>:</label>$ 
                         <input name="bidAmt" type="text" class="input required" value="<?php echo (isset($bid->bid_amount)) ? $bid->bid_amount : set_value('bidAmt'); ?>" size="8" />                                   
                        <?php echo form_error('bidAmt'); ?>
                    </p>
                    <p>
                        <label><?php echo $this->lang->line('Quantity'); ?>: </label>
                        <input name="quantity" type="text" class="input required" value="<?php echo (isset($bid->quantity)) ? $bid->quantity : set_value('quantity'); ?>" size="5" />
                        <?php echo form_error('quantity'); ?>
                    </p>
                    <p>
                        <label><?php echo $this->lang->line('Discount'); ?>:  </label>
                        <input id="discount_check" type="checkbox" name="discount" value="1"  <?php echo set_checkbox('discount', '1'); ?> <?php if (isset($bid->discount))  echo "checked='checked'" ?>/>      
                    <div id="discount_value" style="display: none;">
                        <?php echo form_error('discount_value[]'); ?>
                        <?php echo form_error('discount_at[]'); ?>
                        <?php for ($i = 0; $i < 3; $i++) : ?>
                            <?php echo $this->lang->line("$i discount"); ?>: 
                            <input type="text" name="discount_value[]" class="" value="<?php echo (isset($bid->discount_value)) ? $bid->discount_value[$i] : set_value('discount_value[]',0); ?>" size="2" />%
                            <?php echo $this->lang->line('for'); ?>:
                            <input type="text" name="discount_at[]" class="" value="<?php echo (isset($bid->discount_at)) ? $bid->discount_at[$i] : set_value('discount_at[]',0); ?>" size="5" />
                            <?php echo $this->lang->line("$i Product"); ?>
                            <br/>
<?php endfor; ?>
                    </div>
                    </p>

                    <p>
<?php echo $this->lang->line('bid same area') ?>
                        <input type="checkbox" name="same_area" value="1"  <?php echo set_checkbox('same_area', '1'); ?> <?php if (isset($bid->same_area))  echo "checked='checked'" ?>/>
                    </p>

                    <p>
                        <label><?php echo $this->lang->line('Message:'); ?></label>
                        <textarea name="message2" wrap="physical" rows=10 cols=60 onKeyDown="textCounter(document.myForm.message2,document.myForm.remLen2,250)" onKeyUp="textCounter(document.myForm.message2,document.myForm.remLen2,250)"><?php echo (isset($bid->bid_desc)) ? $bid->bid_desc : set_value('message2') ?></textarea>  <?php echo form_error('message2'); ?>
                    </p>
                    <p>
                        <label>&nbsp;</label>
                        <input readonly type="text" name="remLen2" size="3" maxlength="3" value="250"/>
<?php echo $this->lang->line('Characters Left') ?>
                    </p>
                    <p>
                        <label>&nbsp;</label>
                        <input class="clsSmall" type="submit" value="<?php echo $this->lang->line('Submit'); ?>" name="postBid"/>
                        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
                        <input type="hidden" name="bidId" value="<?php if (isset($bid->id))
    echo $bid->id; ?>" />
                    </p>
                </div>
                <!--END OF PROJECT MESSAGE BOARD-->
            </form>
        </div>

    </div>
</div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>