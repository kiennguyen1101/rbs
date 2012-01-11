  <?php
//Show Flash Message
?>

								<?php 
						   
								if(isset($page_content) and $page_content->num_rows()>0)
								{ 
									foreach($page_content->result() as $page)
									{
									
										echo $page->content;
									}
								}
								?>
								<!-- End of page content --> 
