</div>
  <!--END OF CONTENT-->
  <!--FOOTER-->
  <div id="footer">
  	<ul>	
		<li><a href="<?php echo site_url('page/sitemap'); ?>"><?php echo $this->lang->line('Site Map');?></a></li>
		<li><a href="<?php echo site_url('?c=rss'); ?>"><?php echo $this->lang->line('RSS'); ?></a></li>
		<?php /*?><li><a href="#"><?php echo $this->lang->line('Report Violations'); ?></a></li>								
		<li><a href="#"><?php echo $this->lang->line('Affiliates'); ?></a></li><?php */?>
		<li><a href="<?php echo site_url('faq'); ?>"><?php echo $this->lang->line('FAQ'); ?></a></li>
		<?php
		if(isset($pages))
		{
			foreach($pages->result() as $page)
			{
				echo '<li><a href="'.site_url('page').'/'.$page->url.'">'.$page->name.'</a></li>';
			 } 
			 }
		   ?>						
		<li class="clsNoBorder"><a href="<?php echo site_url('contact'); ?>"><?php echo $this->lang->line('Contact Support');?></a></li>								
	</ul>
	<p><?php echo $this->lang->line('copy');?></p>
	<p><?php echo $this->lang->line('Designed by:');?> <a href="http://cogzideltemplates.com" target="_blank"><?php echo $this->lang->line('Cogzidel Templates');?></a> | <?php echo $this->lang->line('Powered By:');?><a href="http://cogzidel.com" target="_blank"><?php echo $this->lang->line('Cogzidel Technologies');?></a></p>
	<p>Valid <a href="http://validator.w3.org/check/referer" target="_blank">XHTML</a> | Valid <a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank">CSS</a></p>
	<!-- Creative Commons License -->
	<p>
<a href="http://creativecommons.org/licenses/GPL/2.0/">
<img alt="CC-GNU GPL" border="0" src="http://creativecommons.org/images/public/cc-GPL-a.png" /></a><br />
This software is licensed under the <a href="http://creativecommons.org/licenses/GPL/2.0/">CC-GNU GPL</a> version 2.0 or later.</p>
<!-- /Creative Commons License -->
  </div>	
</div>
  <!--END OF FOOTER-->  
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-8037817-1");
pageTracker._trackPageview();
} catch(err) {}</script>
<script>
function themesubmit()
{
	document.themeform.submit();
}
</script>
</body>
</html>
