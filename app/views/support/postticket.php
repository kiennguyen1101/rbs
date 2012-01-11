<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--START MAIN-->
<div id="main">
	  <!--POST PROJECT-->
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
                            <div class="clsInnerCommon clsFormSpan">
							
                              <h2>Contacting Reverse Bidding System</h2>
							  <!--Contact page starting-->
							  
							  <div class="clsTopNavi">
							  <?php $this->load->view('support/submenu'); ?></div>
							    <p align="right" class="clsLogouts">
							    <?php if(isset($this->loggedInUser->id))  { ?>
						  <a href="<?php echo site_url('users/logout'); ?>">LOG OUT</a> , <?php echo $this->loggedInUser->user_name; ?>
							  <?php  }  ?>
							  </p>
							  
						
							  <!--<div class="clsFrequentlyCom clsFloatLeft">
							  <h3>Some useful forms...</h3>
							  <ul>
							  <li><a href="#">Click here</a> to report term violations.</li>
							  <li><a href="#">Click here</a> to cancel a project.</li>
							  <li><a href="#">Click here</a> if your account is suspended.</li>
							  </ul>
							  </div>-->
							 <form name="frmpostticket" method="post" action="<?php echo site_url('support/postticket'); ?>">
							   <p class="clsRed">Note: If your question is related to a support request you previously opened, please find that ticket and add the additional info to it. This will speed up response times. <a href="<?php echo site_url('support/open'); ?>"> click here </a> to view all your open support requests, and <a href="<?php echo site_url('support/close'); ?>">click here</a> to view all your closed support requests (which you can easily re-open).</p>
							   <p>Please fill out all of the below details to submit your support request. We will respond as soon as possible. Do not be worried if your support ticket is closed, you can easily re-open it and continue communicating with us. </p>
							   <!--Ticket Detail form start-->
							   <div class="clsTicketDetail"><br/>
							   <h3><p class="clsTitle">TICKET DETAILS </p></h3>
							  <p>  <span>Priority</span>
							   <select class="tbox" name="priority">
								<option value="1">Urgent</option>
								<option value="2">High</option>
								<option value="3">Normal</option>
								<option selected="" value="4">Low</option>
								<option value="5">Very Low</option>
								</select></p>
								<p><span>Category</span>
							   <select id="category" class="tbox"  name="category">
								<option value="1">General</option>
								<option value="2">Billing</option>
								<option value="3">Suspended Accounts</option>
								<option value="4">Problems</option>
								<option value="5">Abuse</option>
								</select></p>
								<!--Ticket Detail form Ending-->
								</div>
								<!--Ticket Detail form start-->
							   <div class="clsTicketDetail">
								 <h3><p class="clsTitle">REQUEST DETAILS</p> </h3>
							 	<!--  <p><span class="clsComSpace">
							   <input type="checkbox" value="0" name="chkrequest"/>
								This is a NEW support request. (<a href="#">Click here</a>to respond to support requests you already opened. Thank you.)</span></p>-->
							<p class="clsClear1">
							<span>Subject</span>
							<input class="clsText" type="text"  size="35" name="subject" value="<?php echo set_value('subject');  ?>"/></p>
						<p>	<span class="help"><?php echo form_error('subject');  ?></span></p>
					 
					 <br /><div class="clsClear"></div>
							<p class="clsClear1"> 
							<span> Comments </span>
							 <textarea class=""  rows="13" cols="65" name="description"/><?php echo set_value('description');  ?></textarea></p>
							<p><span class="help"> <?php echo form_error('description');  ?> </span></p>
							 <br/>
							 <p class="clsClear">
  							 <input type="submit" class="clsMini" name="postticket" value="Submit"/></p>

								<!--Ticket Detail form Ending-->
							</div>

							  <!--Contact page Ending-->							
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
      <!--END OF POST PROJECT-->
     </div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>