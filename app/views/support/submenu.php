<br/><br/>
							 <h3><p><a href="<?php echo site_url('support');  ?>"><?php echo $this->lang->line('main'); ?></a> | 
							  <a href="<?php echo site_url('support/postticket');  ?>"><?php echo $this->lang->line('submit new request'); ?></a> | 
							  <a href="<?php echo site_url('support/open');  ?>"><?php echo $this->lang->line('open requests'); ?></a> | 
							  <a href="<?php echo site_url('support/close');  ?>"><?php echo $this->lang->line('closed requests'); ?></a> |  
							  <a href="<?php if($this->loggedInUser->role_id=='1') 
							    {
								   echo site_url('buyer/editProfile'); 
								}
								else if($this->loggedInUser->role_id=='2') {  echo site_url('seller/editProfile'); } ?>"><?php echo $this->lang->line('edit profile'); ?></a></p></h3>