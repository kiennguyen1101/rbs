<?php
	session_start();
	
	if($_SESSION['baseurl'] == '')
		$url	= '../../';
	else
		$url	= $_SESSION['baseurl']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/common.css" rel="stylesheet" type="text/css" /> 
<link href="css/style.css" rel="stylesheet" type="text/css" /> 
<title>RBS Step-4</title>
</head>

<body>
<div id="container">
<div id="header">
  <!--RC for Logo-->
    <div class="block">
      <div class="top_t">
        <div class="top_r">
          <div class="top_b">
            <div class="top_l">
              <div class="top_tl">
                <div class="top_tr">
                  <div class="top_bl">
                    <div class="top_br">
                      <div class="cls100_p" >
                        <!--RC-->
						<div class="clsclearfix">
					
						<div id="selLeftHeader">
                        <h1><a href="http://demo.cogzidel.in/rbs">RBS</a></h1>
						</div>
						<div id="selRightHeader">
						<ul>
					<li><a href="http://products.cogzidel.com/rbs" target="_blank">RBS</a></li>
					<li><a href="http://cogzidel.com" target="_blank">Cogzidel</a></li>
					<li class="clsNoBg"><a href="http://cogzideltemplates.com" target="_blank">Cogzidel Templates</a></li>
					</ul>
					</div>
					</div>
                        <!--RC-->
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
    <!--end of RC-->
	<!--Rc -->
	                <div id="banner">
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
                        <!--RC-->
						<div id="selBanner">
                        							<h2>RBS Installation Steps</h2>
												    <img src="images/step-4.jpg" alt="Step-1" />
							</div>
                        <!--RC-->
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
		<!--End Rc -->
	 <!--RC-->
	               <div id="main">
    <div class="block">
      <div class="b_t">
        <div class="b_r">
          <div class="b_b">
            <div class="b_l">
              <div class="b_tl">
                <div class="b_tr">
                  <div class="b_bl">
                    <div class="b_br">
                      <div class="cls100_p">
                        <!--RC-->
                        <div id="selMain">
						<p class="clsHead">Installation is Completed Succesfully.</p>
						<p>
						<!--Installation Complete, will be redirected to the home page. If not <a href="<?php echo $url; ?>">click here</a>.-->
	Congratulations!! You have successfully installed RBS script on your server!<br /><br />
	 
	Please choose appropriate  action:<br>
	<br>Good Luck!<br />
	<p class="clsAlign"><input type="button" name="home" value="Site Home" class="clsbtn" onClick="window.location='<?php echo $url; ?>'">&nbsp;&nbsp;&nbsp;<input class="clsbtn" type="button" name="home" value="Site Admin" onClick="window.location='<?php echo $url; ?>index.php/siteadmin'"></p>
	
						</p>
						
						
						
						</div>
                        <!--RC-->
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
    <!--end of RC-->
	
	<div id="footer">
	<p>Copyright &copy; 2008 - 2010 RBS (Copyright Policy, Trademark Policy) </p>
	</div>
	
</div>

</body>
</html>
