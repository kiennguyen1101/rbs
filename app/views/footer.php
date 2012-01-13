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
	<p>&#169; copyright 2012, iCrowdShop All Rights Reserved.</p>
	
	
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
