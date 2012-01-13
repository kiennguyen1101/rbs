<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<META HTTP-EQUIV="Expires" CONTENT="0">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<title>Admin Section</title>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/prototype.js"></script>
<!--<script type="text/javascript" src="<?php echo base_url() ?>app/js/scriptaculous.js"></script>-->
<script type="text/javascript" src="<?php echo base_url() ?>app/js/script.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/datetimepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>app/css/admin.css" />
</head>
<body>

<!--LAYOUT-->

<div class="clsContainer">
   <!--HEADER-->
   <div id="header" class="clsClearFixSub">
    <div id="selLeftHeader" class="clsFloatLeft">
      <h1 class="logo"> <a href="<?php echo admin_url('home'); ?>"><?php echo $this->config->item('site_title'); ?></a></h1>
    </div>
	    <div id="selRightHeader" class="clsFloatRight">
		  <ul id="mainnav">
			<li><a href="<?php echo admin_url('home');?>"><?php echo $this->lang->line('admin_home'); ?></a></li>
			<li><a href="<?php echo base_url();?>"><?php echo $this->lang->line('site_home'); ?></a></li>
			<li><a href="<?php echo admin_url('logout');?>"><?php echo $this->lang->line('log_out'); ?></a></li>
		  </ul>
	    </div>
    </div>
  <!--END OF HEADER-->
  <!--WRAPPERR-->
  <div id="wrapper">
    <!--MAIN-->
  <!--CONTENT BLOCK-->
  <div id="content"> 