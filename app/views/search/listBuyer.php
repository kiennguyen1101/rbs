<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->
<div id="main">
  <!--POST PROJECT-->
  <div class="clsInnerpageCommon">
    <div class="block">
      <div class="inner_t">
        <div class="inner_r">
          <div class="inner_b">
            <div class="inner_l">
              <div class="inner_tl">
                <div class="inner_tr">
                  <div class="inner_bl">
                    <div class="inner_br">
                      <div class="cls100_p">
                        <div class="clsInnerCommon">
                          <h2><?php echo $this->lang->line('Search Results'); ?></h2>
                          <h3><span class="clsMyOpen">You have <?php echo $users->num_rows() ?> result(s)</span></h3>
                          <table cellspacing="1" cellpadding="2" width="96%">
                            <tbody>
                              <tr>
                                <td class="dt"><?php echo $this->lang->line('SI.No');?></td>
                                <td class="dt"><?php echo $this->lang->line('Name');
						  $odr = 'ASC';
						  if($order == 'ASC')
						  $odr = 'DESC';
						  ?>
                             </td>
                               
                                <td class="dt"><?php echo $this->lang->line('Country'); ?></td>
                                <td class="dt"><?php echo $this->lang->line('State'); ?></td>
                                <td class="dt"><?php echo $this->lang->line('City'); ?></td>
                              
                              </tr>
                              <?php $j=0; $i=0;
						 	if(isset($users) and $users->num_rows()>0)
							{
								foreach($users->result() as $users)
								{ 
                                                                   
								if($users->role_id == 1)
								{
								 $j=$j+1;
								if($j%2 == 0)
								  $class = 'dt1 dt0';
								else
								  $class = 'dt2 dt0'; 
								?>
                              <tr class="<?php echo $class; ?>">
                                <td><?php echo $j;  ?></td>
                                <td><a href="<?php echo site_url('seller/viewProfile/'.$users->id); ?>"><?php echo $users->user_name; ?> </td>
                                <td><?php echo $users->country_name; ?>
                                <td><?php echo $users->state; ?>
                                <td><?php echo $users->city; ?>
                                </td>
                               
                              </tr>
                              <?php 
						  }						
								}//Traverse Users
							}//Check For User Availability
							 ?>
                              <tr>
                                <td class="dt1 dt0" colspan="5"><form method="post" action="">
                                    <table cellspacing="0" cellpadding="0" width="100%">
                                    <tbody>
                                      <tr>
                                        <td align="center"><?php echo $this->lang->line('Customize Display'); ?>:</td>
                                        
                                        <td><select name="show_num" size="1">
                                            <option value="5" <?php if($this->session->userdata('show_num') == 5) echo "selected";?>>5</option>
                                            <option value="10" <?php if($this->session->userdata('show_num') == 10) echo "selected";?>>10</option>
                                            <option value="20" <?php if($this->session->userdata('show_num') == 20) echo "selected";?>>20</option>
                                            <option value="50" <?php if($this->session->userdata('show_num') == 50) echo "selected";?>>50</option>
                                            <option value="100" <?php if($this->session->userdata('show_num') == 100) echo "selected";?>>100</option>
                                          </select>
                                          <?php echo $this->lang->line('Results'); ?>
                                          <input type="submit" value="<?php echo $this->lang->line('Refresh'); ?>" class="clsSmall" name="customizeDisplay"/>
                                  </form></td>
                              </tr>
                            </tbody>
                          </table>
                          </td>
                          </tr>
                          </tbody>
                          </table>
                          <!--PAGING-->
                          <?php if(isset($pagination)) echo $pagination; ?>
                          <!--END OF PAGING-->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--END OF POST PROJECT-->
</div>

<!--END OF MAIN-->
<?php $this->load->view('footer'); ?>