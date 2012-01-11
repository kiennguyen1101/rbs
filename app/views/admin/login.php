<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<META HTTP-EQUIV="Expires" CONTENT="0">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<title>Admin Section</title>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/prototype.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/scriptaculous.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/script.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>app/css/admin.css" />
</head>
<body>
<!--LAYOUT-->
<!--HEADER-->
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
        <?php if(isset($adminlogin)) { ?> <li><a href="<?php echo admin_url('logout');?>"> <?php  echo $this->lang->line('log_out');?> </a></li> <?php  } ?>
       </ul>
	  </div>
    </div>

<!--END OF HEADER-->
<!--WRAPPERR-->
<div id="wrapper">
  <!--MAIN-->
  <!--CONTENT BLOCK-->
  <div id="sellogin">
    <?php
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
	  	?>
    <!--CONTENT-->
    <div class="innercontent">
      <h2><?php echo $this->lang->line('Member Area');?> - <?php echo $this->lang->line('login'); ?> </h2>
      <div class="form_error"> </div>
      <form method="post" action="<?php echo admin_url('login'); ?>">
        <p>
          <label><?php echo $this->lang->line('username'); ?><span class="clsRed">*</span></label>
          <input class="focus" type="text" name="username" value="<?php echo set_value('username'); ?>"/>
        </p>
        <?php if(form_error('username')) { ?>
        <p>
          <label>&nbsp;</label>
          <img src="<?php echo image_url('error.jpg');?>" height="12" width="12" /><?php echo form_error('username'); ?></p>
        <?php } ?>
        <p>
          <label><?php echo $this->lang->line('password'); ?><span class="clsRed">*</span></label>
          <input class="focus" type="password" name="pwd" value=""/>
        </p>
        <p>
          <?php if(form_error('username')) { ?>
          <label>&nbsp;</label>
          <img src="<?php echo image_url('error.jpg');?>" height="12" width="12" /><?php echo form_error('pwd'); ?> </p>
        <?php } ?>
        <p>
          <label>&nbsp;</label>
          <input class="clsSubmitBt1 " value="<?php echo $this->lang->line('Submit');?>" name="loginAdmin" type="Submit">
          <input class="clsSubmitBt1" value="<?php echo $this->lang->line('Reset');?>" name="reset" type="reset">
        </p>
      </form>
    </div>
    <!--END OF CONTENT-->

<!-- Main Content End-->

<?php $this->load->view('admin/footer'); ?>
</body>