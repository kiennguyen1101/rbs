<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
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
                          <!--<h2><?php echo $this->lang->line('Login to Cogzidel Lance');?></h2>
                          <h3><span class="clsTransfer"><?php echo $this->lang->line('Login');?></span></h3>-->
                         
						<?php	
						if($this->session->userdata('private_user'))
						{?>
						 <h2><?php echo $this->lang->line('Login to Private Project');?></h2>
                         <h3><span class="clsTransfer"><?php echo $this->lang->line('Private Project'); echo $this->uri->segment(3,0); ?> <?php echo '<img src="',image_url('private.png').'" width="14" height="14" title="Private projects" alt="private projects" />';?></span></h3>
						 
						 <p><?php echo $this->lang->line('note');?></p>
						 
						 <?php 
						}
						else
						{?>
						 <h2><?php echo $this->lang->line('Login to Cogzidel Lance');?></h2>
                          <h3><span class="clsTransfer"><?php echo $this->lang->line('Login');?></span></h3><?php
						}
							?>
							 <?php
							//Show Flash Message
							if($msg = $this->session->flashdata('flash_message'))
							{
								echo $msg;
							}?>
						  <form method="post" action="<?php echo site_url('users/login'); ?>">
                            <p>
							  <input type="text" name="username" value="<?php echo set_value('username'); ?>" class="clsText" id="UN" onblur="SwapUsernamePlace();" style="display:none;"/>
							  <input type="text" value="Username" class="clsText" id="UNP" onfocus="javascript:SwapUsername();" style="color:#1589B2;"/>
							  <?php echo form_error('username'); ?>
                            </p>
                            
                            <p>
							  <input type="text" value="Password" class="clsText" onfocus="javascript:SwapPassword();" id="PWP" style="color:#1589B2;"/>
                             <input type="password" name="pwd" value="" class="clsText" onblur="SwapPasswordPlace();" style="display:none;" id="PW"/>
							  <?php echo form_error('pwd'); ?>
                            </p>
                  
							 <p> <input type="checkbox" class="checkbox"  name="remember"/>
		    					 <?php echo $this->lang->line('remember me');?>
						   </p>	
                            <p>
                              <input type="submit" name="usersLogin" value="<?php echo $this->lang->line('Login');?>" class="clsMiniBL"/>
                            </p>
                            <p><a href="<?php echo site_url('users/forgotPassword'); ?>"><?php echo $this->lang->line('I forgot my login details');?>?</a> <a href="<?php echo site_url('buyer/signUp'); ?>"><?php echo $this->lang->line('Signup');?></a></p>
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
  </div>
  <!--END OF POST PROJECT-->
</div>
<!--END OF MAIN-->
 <script language="javascript" type="text/javascript">
	   function SwapPassword()
    {
	    var tfPassword = GetPageElement("PW");
	    var tfPasswordPlace = GetPageElement("PWP");

        tfPasswordPlace.style.display = "none";
        tfPassword.style.display = "";
        tfPassword.focus();
    }
    
    function SwapUsername()
    {
	    var tfUserName = GetPageElement("UN");
	    var tfUsernamePlace = GetPageElement("UNP");

        tfUsernamePlace.style.display = "none";
        tfUserName.style.display = "";
        tfUserName.focus();
    }     
    
    function SwapUsernamePlace()
    {
	    var tfUserName = GetPageElement("UN");
	    var tfUsernamePlace = GetPageElement("UNP");
	    
        if (tfUserName.value == '')
        {
            tfUsernamePlace.style.display = "";
            tfUserName.style.display = "none";
        }
    }
    
    function SwapPasswordPlace()
    {
	    var tfPassword = GetPageElement("PW");
	    var tfPasswordPlace = GetPageElement("PWP");

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
<?php $this->load->view('footer'); ?>