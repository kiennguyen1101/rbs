<?php 
 echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
 ?>
<rss version="2.0">
  <channel>
  <title><?php if(isset($rss_title)) echo $rss_title; ?></title> 
  <link><?php echo base_url(); ?></link> 
  <description><?php if(isset($rss_description)) echo $rss_description; ?>.</description> 
  <language><?php echo $this->lang->line('en-us');?></language> 
  <pubDate><?php echo $this->lang->line('Sat, 17 Jan 2009 05:30:39 GMT');?></pubDate> 
  <lastBuildDate><?php echo $this->lang->line('Sat, 17 Jan 2009 05:30:39 GMT');?></lastBuildDate> 
  <copyright><?php echo $this->lang->line('Copyright'); ?> <?php echo $this->lang->line('(c) 2009');?> <?php echo $this->config->item('site_title'); ?></copyright> 
<?php
 if(isset($projects) and $projects->num_rows()>0)
 {
 	foreach($projects->result() as $project)
	{
		?>
			<item>
			<title><?php echo  $project->project_name?></title>
			<link><?php echo site_url('project/view/'.$project->id); ?></link>
			<guid><?php echo site_url('project/view/'.$project->id); ?></guid>
			<pubDate><?php echo show_date($project->created);?></pubDate>
			<?php 
				switch($type)
				{
					case 2:
						echo '<description>';
						echo word_limiter($project->description, 100);
						echo '</description>';
					case 3:
						echo '<description>';
						echo $project->description;
						echo '</description>';
				}
			 ?>
			</item>
		<?php
	}//Traverse Rss Feeds
 }//Check For Project Availability
?> 


</channel>
</rss>