<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<?php
		//Get Project Info
     	$project = $projects->row();
?>


<div id="main">
<?php    var_dump($project); ?>
</div>
        
<!--END OF viewProject -->
<?php $this->load->view('footer'); ?>                     