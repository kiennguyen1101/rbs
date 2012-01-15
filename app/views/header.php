<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php if(isset($page_title)) echo $page_title; ?></title>
<meta http-equiv="Pragma" content="no-cache" />
<meta name="keywords" content="<?php if(isset($meta_keywords))  echo $meta_keywords; ?>" />
<meta name="description" content="<?php if(isset($meta_description))  echo $meta_description;  ?>" />
<link href="<?php echo base_url() ?>app/css/css/common.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?php echo base_url() ?>app/css/css/header.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>app/css/css/st.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>app/css/css/menus.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>app/css/css/icons.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="<?php echo base_url() ?>favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="<?php echo base_url() ?>app/js/jquery-1.7.1.min.js"> </script>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/prototype.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/scriptaculous.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>app/js/script.js"></script>

<!--[if IE ]>
<link href="<?php echo base_url() ?>app/css/css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->
</head>
<body>
<div class="clsContainer">
  <!--HEADER-->
  <div id="header">
    <div id="selLeftHeader">
	<div id="selLogo">
      <h1><a href="<?php echo base_url() ?>"><?php echo $this->lang->line('Cogzidel Lance');?></a></h1>
    </div>
	</div>
	<div id="selRightHeader">
	<div id="selTopNavigation">
      <ul>
        <li><a href="#">Login</a></li>
        <li><a href="http://kabada.in/icrowd/index.php/buyer/signUp">Register</a></li>
        
      </ul>
    </div>
    <div id="selSubHeader">
	<?php if(!isset($current_page))
	$current_page = ''; 
	?>
		<?php if($current_page == 'home'){?>
		<ul class="clearfix">
			<li><a href="<?php echo base_url(); ?>" class="current"><span><?php echo $this->lang->line('Home');?></span></a></li>
			<li><a href="<?php echo site_url('project/create'); ?>"><span>Add new Product</a></li>
			<?php if($this->session->userdata('role')=='buyer') {?>
			<li><a href="<?php echo site_url('account'); ?>"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }
			else if($this->session->userdata('role')!='seller'){?>
			<li><a href="<?php echo site_url('buyer/signUp'); ?>"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }?>
			<?php if($this->session->userdata('role')=='seller') {?>
			<li><a href="<?php echo site_url('account'); ?>"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php }
			else if ($this->session->userdata('role')!='buyer') {?>
			<li><a href="<?php echo site_url('seller/signUp'); ?>"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php } ?>

		 
		</ul>
		<?php } 
		elseif($current_page == 'buyer'){
		?>
		<ul class="clearfix">
			<li><a href="<?php echo base_url(); ?>" ><span><?php echo $this->lang->line('Home');?></span></a></li>
			<li><a href="<?php echo site_url('project/create'); ?>"><span><?php echo $this->lang->line('Post Projects'); ?></span></a></li>
			<?php if($this->session->userdata('role')=='buyer') {?>
			<li><a href="<?php echo site_url('account'); ?>" class="current"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }
			else if($this->session->userdata('role')!='seller'){?>
			<li><a href="<?php echo site_url('buyer/signUp'); ?>" class="current"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }?>
			<?php if($this->session->userdata('role')=='seller') {?>
			<li><a href="<?php echo site_url('account'); ?>"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php }
			else if ($this->session->userdata('role')!='buyer'){?>
			<li><a href="<?php echo site_url('seller/signUp'); ?>"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php } ?>

		  
		</ul>
		<?php
		}
		elseif($current_page == 'provider'){
		?>
		<ul class="clearfix">
			<li><a href="<?php echo base_url(); ?>" ><span><?php echo $this->lang->line('Home');?></span></a></li>
			<li><a href="<?php echo site_url('project/create'); ?>"><span><?php echo $this->lang->line('Post Projects'); ?></span></a></li>
			<?php if($this->session->userdata('role')=='buyer') {?>
			<li><a href="<?php echo site_url('account'); ?>"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }
			else if($this->session->userdata('role')!='seller'){?>
			<li><a href="<?php echo site_url('buyer/signUp'); ?>"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }?>
			<?php if($this->session->userdata('role')=='seller') {?>
			<li><a href="<?php echo site_url('account'); ?>" class="current"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php }
			else if ($this->session->userdata('role')!='buyer'){?>
			<li><a href="<?php echo site_url('seller/signUp'); ?>" class="current"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php } ?>

		  <li><a href="<?php echo site_url('?c=rss'); ?>"><span><?php echo $this->lang->line('Feeds'); ?></span></a></li>
		</ul>
		<?php
		}
		elseif($current_page == 'rss'){
		?>
		<ul class="clearfix">
			<li><a href="<?php echo base_url(); ?>" ><span><?php echo $this->lang->line('Home');?></span></a></li>
			<li><a href="<?php echo site_url('project/create'); ?>"><span><?php echo $this->lang->line('Post Projects'); ?></span></a></li>
			<?php if($this->session->userdata('role')=='buyer') {?>
			<li><a href="<?php echo site_url('account'); ?>"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }
			else if($this->session->userdata('role')!='seller'){?>
			<li><a href="<?php echo site_url('buyer/signUp'); ?>"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }?>
			<?php if($this->session->userdata('role')=='seller') {?>
			<li><a href="<?php echo site_url('account'); ?>"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php }
			else if ($this->session->userdata('role')!='buyer'){?>
			<li><a href="<?php echo site_url('seller/signUp'); ?>"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php } ?>

		  <li><a href="<?php echo site_url('?c=rss'); ?>" class="current"><span><?php echo $this->lang->line('Feeds'); ?></span></a></li>
		</ul>
		<?php
		}
		elseif($current_page == 'post_project'){
		?>
		<ul class="clearfix">
			<li><a href="<?php echo base_url(); ?>" ><span><?php echo $this->lang->line('Home');?></span></a></li>
			<li><a href="<?php echo site_url('project/create'); ?>" class="current"><span><?php echo $this->lang->line('Post Projects'); ?></span></a></li>
			<?php if($this->session->userdata('role')=='buyer') {?>
			<li><a href="<?php echo site_url('account'); ?>"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }
			else if($this->session->userdata('role')!='seller'){?>
			<li><a href="<?php echo site_url('buyer/signUp'); ?>"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }?>
			<?php if($this->session->userdata('role')=='seller') {?>
			<li><a href="<?php echo site_url('account'); ?>"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php }
			else if ($this->session->userdata('role')!='buyer'){?>
			<li><a href="<?php echo site_url('seller/signUp'); ?>"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php } ?>

		  <li><a href="<?php echo site_url('?c=rss'); ?>"><span><?php echo $this->lang->line('Feeds'); ?></span></a></li>
		</ul>
		<?php
		}
		else {
		?>
		<ul class="clearfix">
			<li><a href="<?php echo base_url(); ?>"><span><?php echo $this->lang->line('Home');?></span></a></li>
			<li><a href="<?php echo site_url('project/create'); ?>"><span><?php echo $this->lang->line('Post Projects'); ?></span></a></li>
			<?php if($this->session->userdata('role')=='buyer') {?>
			<li><a href="<?php echo site_url('account'); ?>"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }
			else if($this->session->userdata('role')!='seller'){?>
			<li><a href="<?php echo site_url('buyer/signUp'); ?>"><span><?php echo $this->lang->line('Buyers'); ?></span></a></li>
			<?php }?>
			<?php if($this->session->userdata('role')=='seller') {?>
			<li><a href="<?php echo site_url('account'); ?>"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php }
			else if ($this->session->userdata('role')!='buyer'){?>
			<li><a href="<?php echo site_url('seller/signUp'); ?>"><span><?php echo $this->lang->line('Sellers'); ?></span></a></li>
			<?php } ?>

		  <li><a href="<?php echo site_url('?c=rss'); ?>"><span><?php echo $this->lang->line('Feeds'); ?></span></a></li>
		</ul>
		<?php
		}?>
	</div>
	</div>
  </div>
  <!--END OF HEADER-->
  <!--CONTENT-->
  <div id="content">