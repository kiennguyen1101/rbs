<?php
	session_start();
	
	include "db.php";
	
	if(	isset($_POST['submit']) && $_POST['submit'] == 'Submit' &&
		trim($_POST['site_title']) != '' &&
		trim($_POST['site_admin_mail']) != '' &&
		trim($_POST['admin_password']) != ''){
	
	osc_db_connect($_SESSION['mysql_host'], $_SESSION['mysql_uname'], $_SESSION['mysql_password']);
  osc_db_select_db($_SESSION['mysql_db']);

  osc_db_query('update settings set string_value = "' . trim($_POST['site_title']) . '",created = "'.time().'" where code = "SITE_TITLE"');
  osc_db_query('update settings set string_value = "' . trim($_POST['site_admin_mail']) . '",created = "'.time().'" where code = "SITE_ADMIN_MAIL"');
   osc_db_query('update settings set string_value = "' . trim($_SESSION['baseurl']) . '",created = "'.time().'" where code = "BASE_URL"');
  
  //echo 'select admin_name from admins where admin_name = "' . trim($HTTP_POST_VARS['admin_name']) . '"';exit;
  $check_query = osc_db_query('select admin_name from admins where admin_name = "' . trim($_POST['admin_name']) . '"');

  if (osc_db_num_rows($check_query)) {
    osc_db_query('update admins set password = "' . trim(md5($_POST['admin_password'])) . '" where admin_name = "' . trim($_POST['admin_name']) . '"');
  } else {
    osc_db_query('insert into admins set admin_name = "' . trim($_POST['admin_name']) . '", password = "' . trim(md5($_POST['admin_password'])) . '"');
  }
  header('Location: complete.php');
  }
  elseif(isset($_POST['submit']) && $_POST['submit'] == 'Submit')
	{
		$site_title	= trim($_POST['site_title']);
		$site_admin_mail	= trim($_POST['site_admin_mail']);
		$admin_name	= trim($_POST['admin_name']);
		$admin_password	= trim(md5($_POST['admin_password']));
		
		$error = 'All the fields are required';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/common.css" rel="stylesheet" type="text/css" /> 
<link href="css/style.css" rel="stylesheet" type="text/css" /> 
<title>RBS Step-3</title>
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
												    <img src="images/step-3.jpg" alt="Step-1" />
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
                        <!--RC--><form name="settings" method="post" action="">
                        <div id="selMain" class="clsclearfix">
						<h2>Site Settings</h2>
						<div class="clsContant">
						<div class="clsForm">
							<?php
	if(isset($error))
	echo '<div id="error" class="error">' . $error . '</div><BR>';
	?>
   
		<br><br>
		
		
				<table width="50%" cellpadding="5" cellspacing="0" border="0">
					<tr>
						<td width="25%"><span>Site Title:</span></td></tr><tr>
						<td><p><input type="text" name="site_title" size="35" value="<?php if(	isset($site_title)) echo $site_title; ?>" />&nbsp;<font>*</font></p></td>
					</tr>
					<tr>
						<td width="25%"><span>Site Admin Email:</span></td></tr><tr>
						<td><p><input type="text" name="site_admin_mail" size="35" value="<?php if(	isset($site_admin_mail)) echo $site_admin_mail; ?>" />&nbsp;<font>*</font></p></td>
					</tr>
					<tr>
						<td width="25%"><span>Admin Username:</span></td></tr><tr>
						<td><p><input type="text" name="admin_name" size="35" value="<?php if(	isset($admin_name)) echo $admin_name; ?>" />&nbsp;<font>*</font></p></td>
					</tr>
					<tr>
						<td width="25%"><span>Admin Password:</span></td></tr><tr>
						<td><p><input type="text" name="admin_password" size="35" value="<?php if(	isset($admin_password)) echo $admin_password; ?>" />&nbsp;<font>*</font></p></td>
					</tr>
				</table>             
		
		<br><br>
		<table width="50%" cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td width="25%"><p><font>* required</font></p></td>
				<td>&nbsp;&nbsp;</td>
			</tr>
		</table>
	
	
						</div>
						</div>
							
							
							
							<p class="clsAlign"><input type="submit" name="submit" class="clsbtn" value="Submit" /></p>
								</div></form>
								
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