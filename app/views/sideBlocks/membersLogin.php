      <!--MEMBER LOGIN-->
     <div class="block">
        <div class="blue_t">
          <div class="blue_r">
            <div class="blue_b">
              <div class="blue_l">
                <div class="blue_tl">
                  <div class="blue_tr">
                    <div class="blue_bl">
                      <div class="blue_br">
                        <div class="cls100_p">
                          <div id="selLogin">
						  <form method="post" action="<?php echo site_url('users/login'); ?>">
                            <h3><?php echo $this->lang->line('MEMBER LOGIN');?></h3>
							
							<?php  $memeber_name= $this->auth_model->getUserCookie('uname');
							       $memeber_pwd=$this->auth_model->getUserCookie('pwd');
								 ?>
                            <p>
							<?php if(!empty($memeber_name)){?>
                             <input type="text" name="username" value="<?php if(!empty($memeber_name)){
							 echo $memeber_name; }
							 else{
							 echo "Username";
							 }?>" class="clsText" id="Username" onblur="SwapUsernamePlace();" style="display:none;"/>
							 <input type="text" value="<?php if(!empty($memeber_name)){
							 echo $memeber_name; }
							 else{
							 echo "Username";
							 }?>"
							 class="clsText" id="UsernamePlace" onfocus="javascript:SwapUsername();" style="color:#1589B2;"/>
							 <?php } else{?>
							 <input type="text" name="username" value="" class="clsText" id="Username" onblur="SwapUsernamePlace();" style="display:none;"/>
						      <input type="text" value="Username" class="clsText" id="UsernamePlace" onfocus="javascript:SwapUsername();" style="color:#1589B2;"/>
							 <?php }?>
							 
                            </p>
                            <p>
							<?php if(!empty($memeber_pwd)){?>
							<input type="password" value="<?php if(!empty($memeber_pwd)){
							 echo $memeber_pwd; }
							 else{
							 echo "Password";
							 }?>" class="clsText" onfocus="javascript:SwapPassword();" id="PasswordPlace" style="color:#1589B2;"/>
                             <input type="password" name="pwd" value="<?php if(!empty($memeber_pwd)){
							 echo $memeber_pwd; }
							 else{
							 echo "Password";
							 }?>" class="clsText" onblur="SwapPasswordPlace();" style="display:none;" id="Password"/>
							 <?php }else {?>
							<input type="text" value="Password" class="clsText" onfocus="javascript:SwapPassword();" id="PasswordPlace" style="color:#1589B2;"/>
                             <input type="password" name="pwd" value="" class="clsText" onblur="SwapPasswordPlace();" style="display:none;" id="Password"/>
							 <?php } ?>
                            </p>
									
							<p class="clsLeftSpace">
							<label>
							<?php  if(!empty($memeber_name)) {?>
						  <input type="checkbox" class="checkbox"  name="remember" value="<?php echo $memeber_name; ?>" <?php echo 'checked="checked"'?>/><?php }else{?>
						   <input type="checkbox" class="checkbox"  name="remember"/><?php }?>
		 			       </label>
		    			 <?php echo $this->lang->line('remember me');?>
						   </p>	
                            <p>
                              <input type="image" value="<?php echo $this->lang->line('Login');?>" name="usersLogin" src="<?php echo image_url('bt_login.png') ;?>" />
							  <input type="hidden" name="usersLogin" value="1" />
                            </p>
                            <p><a href="<?php echo site_url('users/forgotPassword'); ?>"><?php echo $this->lang->line('Forgot Password?');?></a> <a href="<?php echo site_url('buyer/signUp'); ?>"><?php echo $this->lang->line('Sign Up');?></a></p>
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
      <!--END OF MEMBER LOGIN-->
	  <script language="javascript" type="text/javascript">
	   function SwapPassword()
    {
	    var tfPassword = GetPageElement("Password");
	    var tfPasswordPlace = GetPageElement("PasswordPlace");

        tfPasswordPlace.style.display = "none";
        tfPassword.style.display = "";
        tfPassword.focus();
    }
    
    function SwapUsername()
    {
	    var tfUserName = GetPageElement("Username");
	    var tfUsernamePlace = GetPageElement("UsernamePlace");

        tfUsernamePlace.style.display = "none";
        tfUserName.style.display = "";
        tfUserName.focus();
    }     
    
    function SwapUsernamePlace()
    {
	    var tfUserName = GetPageElement("Username");
	    var tfUsernamePlace = GetPageElement("UsernamePlace");
	    
        if (tfUserName.value == '')
        {
            tfUsernamePlace.style.display = "";
            tfUserName.style.display = "none";
        }
    }
    
    function SwapPasswordPlace()
    {
	    var tfPassword = GetPageElement("Password");
	    var tfPasswordPlace = GetPageElement("PasswordPlace");

        if (tfPassword.value == '')
        {
            tfPasswordPlace.style.display = "";
            tfPassword.style.display = "none";
        }
    }    
	function GetPageElement(field){
		return document.getElementById(field);
	}
	  </script>