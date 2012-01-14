<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main" class="">
  <!--NEW BUYERS SIGN-UP-->
  <div id="selSignUp">
    <form method="post" action=""  enctype="multipart/form-data">
      <?phpform_token();?>
      <h2>New Seller Signup (Step 2)</h2>
      <p> Confirmed E-mail1:
        <?php if(isset($confirmed_mail)) echo $confirmed_mail; ?>
      </p>
      <p><strong>Pick a Username:</strong> <br/>
        <input type="text" size="15" value="<?php echo set_value('username'); ?>" name="username"/>
		<?php echo form_error('username'); ?>
      </p>
      <p> <strong>Enter your password:</strong> <br/>
        <input type="password" size="15" name="pwd"/>
		<?php echo form_error('password'); ?>
      </p>
      <p><strong>Confirm Password:</strong> <br/>
        <small>(Enter the above password again to confirm it.)</small> <br/>
        <input type="password" size="15" name="ConfirmPassword"/>
		<?php echo form_error('ConfirmPassword'); ?>
      </p>
      <p> <strong>Name/Company:</strong> <br/>
        <small>(This name will be displayed to others.)</small> <br/>
        <input type="text" size="20" value="<?php echo set_value('name'); ?>" name="name"/>
		<?php echo form_error('name'); ?>
      </p>
      <div id="selOptional">
        <ul>
          <li class="clsHead">
            <p ><strong>Optinal Contact Details </strong>(<a href="#">Privacy Policy</a>)</p>
          </li>
          <li class="clsAdd">
            <p class="clsClearFix"> <span class="clsOptName"> MSN:</span> <span>
              <input type="text" name="contact_msn" value="" size="29"/>
              </span></p>
          </li>
          <li class="clsEven">
            <p class="clsClearFix"> <span class="clsOptName"> ATM:</span> <span>
              <input type="text" name="contact_msn" value="" size="29"/>
              </span> <span class="OptHelp">*Fields Required fsdfdg dfg dfgdf dfgdfgdfgdf</span> </p>
          </li>
          <li class="clsAdd">
            <p class="clsClearFix"> <span class="clsOptName"> Gtalk:</span> <span>
              <input type="text" name="contact_msn" value="" size="29"/>
              </span></p>
          </li>
          <li class="clsEven">
            <p class="clsClearFix"> <span class="clsOptName"> Yahoo:</span> <span>
              <input type="text" name="contact_msn" value="" size="29"/>
              </span></p>
          </li>
          <li class="clsAdd">
            <p class="clsClearFix"> <span class="clsOptName"> ICQ:</span> <span>
              <input type="text" name="contact_icq" value="" size="10"/>
              </span></p>
          </li>
          <li class="clsEven">
            <p class="clsClearFix"> <span class="clsOptName"> Skype:</span> <span>
              <input type="text" name="contact_skype" value="" size="20"/>
              </span></p>
          </li>
          <li class="clsAdd">
            <p class="clsClearFix"> <span class="clsOptName"> Phone:</span> <span>
              <input type="text" name="contact_phone" value="" size="15"/>
              </span></p>
          </li>
          <li class="clsEven">
            <p class="clsClearFix"> <span class="clsOptName"> Mobile:</span> <span>
              <input type="text" name="contact_mobile" value="" size="15"/>
              </span></p>
          </li>
        </ul>
      </div>
      <div id="selAreaExpertise">
        <p> <b>Your picture or company logo (optional):</b> <br/>
          <small>(Maximum 102400 bytes)</small> <br/>
          <input TYPE="file" NAME="logo" />
        </p>
		 <?php echo form_error('logo'); ?>
        <p> <b>New Bid E-Mail Notifications:</b>
          <select name="notify_bid" size="1">
           	 <option value="">None</option>
				<option value="Instantly" <?php echo set_select('notify_bid', 'Instantly'); ?>>Instantly</option>
				<option value="Hourly" <?php echo set_select('notify_bid', 'Hourly'); ?>>Hourly</option>
				<option value="Daily" <?php echo set_select('notify_bid', 'Daily'); ?>>Daily</option>
          </select>
		  <?php echo form_error('notify_project'); ?>
        </p>
        <p> <b>New Message E-Mail Notifications:</b>
          <select name="notify_message" size="1">
            <option value="">None</option>
				<option value="Instantly" <?php echo set_select('notify_message', 'Instantly'); ?>>Instantly</option>
				<option value="Hourly" <?php echo set_select('notify_message', 'Hourly'); ?>>Hourly</option>
				<option value="Daily" <?php echo set_select('notify_message', 'Daily'); ?>>Daily</option>
          </select>
		  <?php echo form_error('notify_message'); ?>
        </p>
      </div>
      <p> <b>Country:</b><br />
        <select name="country" size="1">
          <option value="">None</option>
          <?php
				if(isset($countries) and $countries->num_rows()>0)
				{
					foreach($countries->result() as $country)
					{
			  ?>
          <option value="<?php echo $country->country_symbol; ?>" <?php echo set_select('country', $country->country_symbol); ?>><?php echo $country->country_name; ?></option>
          <?php
					}//Foreach End
				}//If End
			?>
        </select>
		<?php echo form_error('country'); ?>
      </p>
      <p> <b>State/Province (optional):</b><br />
        <input type="text" name="state" value="<?php echo set_value('state'); ?>" maxlength="50" size="30"/>
      </p>
      <p> <b>City (optional):</b><br />
        <input type="text" name="city" value="<?php echo set_value('city'); ?>" maxlength="50" size="25"/>
      </p>

      <p>
        <input type="checkbox" name="signup_agree_terms" value="1" <?php echo set_checkbox('signup_agree_terms', '1'); ?>/>
        I have read and agree to the <a href="#"><?php echo $this->config->item('site_title'); ?> &amp; Conditions</a>.
		<?php echo form_error('signup_agree_terms'); ?>
		 </p>
      <p>
        <input type="checkbox" name="signup_agree_contact" value="1" <?php echo set_checkbox('signup_agree_contact', '1'); ?>/ >
        I will NOT post contact information on my projects. </p>
		<?php echo form_error('signup_agree_contact'); ?>
      <p>
	    <input type="hidden" name="confirmKey" value="<?php echo $this->uri->segment(3); ?>" />
        <input type="submit" class="clsSmall" value="Signup" name="buyerConfirm" />
      </p>
    </form>
  </div>
  <!--SIGN-UP-->
</div>
<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>