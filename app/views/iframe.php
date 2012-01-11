<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">
function resizeIframe() {
	var height = document.documentElement.clientHeight;
	var srcurl = '<?php echo $themeurl[$themename]; ?>';
    height -= document.getElementById('frame').offsetTop;
    
  		
	if(/Safari/.test(navigator.userAgent))
	{
		 height -= 32;  //for safari
		 				/* whatever you set your body bottom margin/padding to be */
	}
	else
	{
		// not sure how to get this dynamically
    	height -= 20; /* whatever you set your body bottom margin/padding to be */
	}
	    
    document.getElementById('frame').style.height = height +"px";
	document.getElementById('frame').src = srcurl;
};
document.getElementById('frame').onload = resizeIframe;

window.onresize = resizeIframe;
</script>
<title><?php if(isset($page_title)) echo $page_title; ?></title>
<meta HTTP-EQUIV=“Cache-Control” CONTENT=“no-cache”>
<meta HTTP-EQUIV=“Pragma” CONTENT=“no-cache”>
<meta HTTP-EQUIV=“Expires” CONTENT=“0”>
<meta name="keywords" content="<?php if(isset($meta_keywords))  echo $meta_keywords; ?>" />
<meta name="description" content="<?php if(isset($meta_description))  echo $meta_description;  ?>" />
<link href="<?php echo base_url() ?>app/css/css/common.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="<?php echo base_url() ?>app/css/css/header.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>app/css/css/st.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>app/css/css/menus.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>app/css/css/icons.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="<?php echo base_url() ?>favicon.ico" type="image/x-icon" />

<script type="text/javascript" src="<?php echo base_url() ?>app/js/prototype.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/scriptaculous.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/script.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/clock.js"></script>
<!--[if IE ]>
<link href="<?php echo base_url() ?>app/css/css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript">
function themesubmit()
{
	document.themeform.submit();
}	
</script>
</head>
<body onload="resizeIframe();" onresize="resizeIframe();">
<div class="clsContainer">
<iframe id="frame"  width="100%" frameborder="0" marginheight="0" marginwidth="0">
</iframe>
<div style="clear:both; height:1%"></div>
  <div class="clsfootertheme"><form method="post" name="themeform" action="<?php echo site_url('home'); ?>">
<table cellspacing="0" cellpadding="0" border="0" width="100%" class="footertable">
  <tbody><tr>
    <td align="right" width="51%" valign="bottom"><img height="40" width="180" 
src="<?php echo image_url('rbstheme.jpg'); ?>"/></td>
	<td align="left" width="3%" valign="middle" style="padding-bottom: 12px;">
  <select name="seltheme" onchange="themesubmit();">
  <option value="">------------------Theme Select------------------</option>
  <option value="theme2" <?php  if($themename=='theme2') { echo 'SELECTED=SELECTED'; } ?>>Grey Theme</option>
  <option value="theme3" <?php  if($themename=='theme3') { echo 'SELECTED=SELECTED'; } ?>>Green Theme</option>
   <option value="theme1" <?php  if($themename=='theme1') { echo 'SELECTED=SELECTED'; } ?>>Default Theme</option>
  </select></td>
   <td align="left" width="8%" valign="middle" style="padding-bottom: 12px;padding-left:2px;">  <input type="image" name="change" value="change" src="<?php echo image_url('go.gif'); ?>" />
   <input type="hidden" value="1" name="change"/>
   </td>
	<td align="left" width="38%" valign="top"><a target="_blank" href="http://www.cogzideltemplates.com/index.php/download_free/templates/37"><img border="0" src="<?php echo image_url('clickhere.jpg'); ?>" usemap="#Map"/></a></td>
  </tr>
</tbody></table>
</form></div>
</div>
</body>
</html>
