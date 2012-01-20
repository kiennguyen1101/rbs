<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); ?>
<!--MAIN-->

<div id="main">
  <?php $this->load->view('search'); ?>
  <!--category-->
  <div id="category">
	<ul>
	<?php
		$categories = array('');
	?>
	<!-- single category -->
	<?php for($i=0;$i<6;$i++): ?>
	<li class="box">
		<h1>Category title</h1>
		<table>
			<tr>
				<td class="item">Item</td>
				<td><a href="#"><img src="<?php echo base_url();?>/app/css/images/want.png"></td>
				<td class="price">$00.00</td>
			</tr>
			
			<tr>
				<td class="item">Item</td>
				<td><img src="<?php echo base_url();?>/app/css/images/want.png"></td>
				<td class="price">$00.00</td>
			</tr>
			
			<tr>
				<td class="item">Item</td>
				<td><img src="<?php echo base_url();?>/app/css/images/want.png"></td>
				<td class="price">$00.00</td>
			</tr>
			
			<tr>
				<td class="item">Item</td>
				<td><img src="<?php echo base_url();?>/app/css/images/want.png"></td>
				<td class="price">$00.00</td>
			</tr>
		</table>
		<span class="viewmore"><a href="#">View more</a></span>
	</li>
	<?php endfor;?>
	<!-- end single category -->
	
	
		
	</ul>
  </div>
  <!-- end category-->
    
  <!-- PROJECT DETAILS-->
  <div id="test">
    <!--Table will load here-->
  </div><?php //$this->load->model('certificate_model');?>
  <!-- END OF PROJECT DETAILS-->
  <div class="two-column clearfix">
    <!--TOP BUYERS -->
    <div class="col-1 left marginright">
      <!--<div class="slidetabsmenu">

		<ul>
		 	<li><a href="#"><span>Top Buyers</span></a></li>
		 	<li><a href="#"><span>Top Sellers</span></a></li>
		 </ul>

		 </div>-->
      <!--<div class="clsInfoBox">
        <div class="block">
          <div class="grey_t">
            <div class="grey_r">
              <div class="grey_b">
                <div class="grey_l">
                  <div class="grey_tl">
                    <div class="grey_tr">
                      <div class="grey_bl">
                        <div class="grey_br">
                          <div class="cls100_p">
                            <h4><span class="clsTopbuyer"><?php echo $this->lang->line('TOP BUYERS');?></span></h4>
                            <?php

							if(count($topBuyers) > 0)
							{
							foreach($topBuyers as $key=>$value)
							{

							$user = getUserInfo($key);
							
								//echo $user->id;
								 $condition=array('subscriptionuser.username'=>$user->id);
								$certified= $this->certificate_model->getCertificateUser($condition);
								
							?>
                            <div class="clsTop clearfix">
                              <div class="clsTopLeft clsFloatLeft">
                                <p class="clsBorder"><a href="<?php echo site_url('buyer/viewProfile/'.$user->id);?>">
                                  <?php 

								  if($user->logo != NULL){

								  ?>
                                  <img src="<?php echo uimage_url(get_thumb($user->logo));?>" alt="logo" />
                                  <?php } ?>
                                  </a></p>
                              </div>
                              <div class="clsTopRight clsFloatLeft">
                                <h5><a href="<?php echo site_url('buyer/viewProfile/'.$user->id);?>"><?php echo $user->user_name;?></a>
								<?php if(count($certified->result())>0)
								{?>
								<img src="<?php echo image_url('certified.gif');?>" />
								<?php }?>
								</h5>
                                <p><?php echo character_limiter($user->profile_desc,'115');?></p>
                              </div>
                            </div>
                            <?php } ?>
                            <div class="alignRight">
                              <p><a href="<?php echo site_url('buyer/getBuyersreview'); ?>"><img src="<?php echo image_url('bt_viewall.jpg');?>" width="92" height="41" alt="view all" /></a></p>
                            </div>
                            <?php } 

							else 
							echo "<div class=clsTop clearfix>".$this->lang->line('No records found')."</div>";
							?>
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
    </div>-->
    <!--END OF TOP BUYERS -->
    <!--CERTIFIED BUYERS -->
    <!--<div class="col-2 left">
      <div class="clsInfoBox">
        <div class="block">
          <div class="grey_t">
            <div class="grey_r">
              <div class="grey_b">
                <div class="grey_l">
                  <div class="grey_tl">
                    <div class="grey_tr">
                      <div class="grey_bl">
                        <div class="grey_br">
                          <div class="cls100_p">
                            <h4><span class="clsTopseller"><?php echo $this->lang->line('TOP SELLERS');?></span></h4>
                            <?php
							if(count($topProviders) > 0)
							{
							foreach($topProviders as $key=>$value)
							{
							$user2 = getUserInfo($key);

							$condition1=array('subscriptionuser.username'=>$user2->id);
								$certified1= $this->certificate_model->getCertificateUser($condition1);
							?>
                            <div class="clsTop clearfix">
                              <div class="clsTopLeft clsFloatLeft">
                                <p class="clsBorder"><a href="<?php echo site_url('seller/viewProfile/'.$user2->id);?>">
                                  <?php 
							  if($user2->logo != NULL){
							  ?>
                                  <img src="<?php echo uimage_url(get_thumb($user2->logo));?>" alt="logo"/>
                                  <?php }  ?>
                                  </a></p>
                              </div>
                              <div class="clsTopRight clsFloatLeft">
                                <h5><a href="<?php echo site_url('seller/viewProfile/'.$user2->id);?>"><?php echo $user2->user_name;?></a>
								<?php if(count($certified1->result())>0)
								{?>
								<img src="<?php echo image_url('certified.gif');?>" />
								<?php }?>
								</h5>
                                <p><?php echo character_limiter($user2->profile_desc,'120');?></p>
                              </div>
                            </div>
                            <?php } ?>
                            <div class="alignRight">
                              <p><a href="<?php echo site_url('seller/getSellersreview');?>"><img src="<?php echo image_url('bt_viewall.jpg');?>" width="92" height="41" alt="view all" /></a></p>
                            </div>
                            <?php } 
							else
							echo "<div class=clsTop clearfix>".$this->lang->line('No records found')."</div>";
							?>
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
    </div>-->
    <!--END OF CERTIFIED BUYERS -->
  </div>
  <!--CATEGORIES-->
  <!--<div id="selCategories">
    <div class="slidetabsmenu menu_fix">
      <ul>
        <?php

			if(isset($groups) and $groups->num_rows()>0)
			{
			$i=0;
			foreach($groups->result() as $group)
			{
						?>
        <li id="gr<?php echo $i;?>" class="<?php if($i == '0') echo "selected"; ?>"><a href="javascript:;" onclick="getCat('<?php echo $i ?>','<?php echo $groups->num_rows ?>',<?php echo $group->id;?>);"><span><?php echo $group->group_name;?></span></a></li>
        <?php $i++;}

			}

			?>
      </ul>
    </div>
    <div class="clsInfoBox flow">
      <div class="block">
        <div class="grey_t">
          <div class="grey_r">
            <div class="grey_b">
              <div class="grey_l">
                <div class="grey_tl">
                  <div class="grey_tr">
                    <div class="grey_bl">
                      <div class="grey_br">
                        <div class="cls100_p">
                          <h4><span class="clsCategory"><?php echo $this->lang->line('CATEGORIES');?></span></h4>
                          <div class="clsCategoryList clearfix" id="catInner"> </div>
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
  </div>-->
  <!--END OF CATEGORIES-->
</div>
<!--END OF MAIN-->
<?php 
//pr($_SERVER);
if($_SERVER['HTTP_HOST'] != 'localhost')
$base_url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
else
$base_url = base_url();

$base_url = str_replace($this->config->item('index_page'),"",base_url());

$base_url .= $this->config->item('index_page');
?>
<script type="text/javascript" >

    

//document.getElementById('test').innerHTML = '<img src="<?php echo image_url('load3.gif');?>" alt="loading" />' + 'Loading'
/*new Ajax.Request('<?php echo $base_url.'/home/listProjects/latest'; ?>',

  {
    method:'get',
    onSuccess: function(transport){
      var response = transport.responseText || "no response text";
      document.getElementById('test').innerHTML = response
    },

    onFailure: function(){ alert('Something went wrong...') }
  });*/
  
function checkFind(type){

document.getElementById('innerContent').innerHTML = '<img src="<?php echo image_url('load2.gif');?>" alt="loading" />' + ' Loading' ;
        
        switch (type) {
            case 'product':
                jQuery("#product").attr({
                    class :"selected",
                    classname: "selected"
                });
                jQuery('#s_buyer,#s_seller').attr({
                    class: "",
                    classname: ""
                });               	
                break;         
            case 's_buyer':
                 jQuery("#s_buyer").attr({
                    class :"selected",
                    classname: "selected"
                });
                jQuery('#product,#s_seller').attr({
                    class: "",
                    classname: ""
                });         
                break;
            case 's_seller':
               	jQuery("#s_seller").attr({
                    class :"selected",
                    classname: "selected"
                });
                jQuery('#product,#s_buyer').attr({
                    class: "",
                    classname: ""
                });         
                break;
             default:
                 break;
        }
	
	new Ajax.Request('<?php echo $base_url.'/home/checkFind/'; ?>'+type,  {
    method:'get',
    onSuccess: function(transport){
      var response = transport.responseText || "no response text";
      document.getElementById('innerContent').innerHTML = response
    },
    onFailure: function(){ alert('Search functions fail. Cannot send ajax...') }
  });
}

function getCat(id,count,catid){

	document.getElementById('catInner').innerHTML = '<img src="<?php echo image_url('load.gif');?>" alt="loading" />' + ' Loading'
	
	for(i=0;i<count;i++)
  {
	
	if(i == id)
	  {
	   	document.getElementById("gr"+i).setAttribute("class", "selected");		
		document.getElementById("gr"+i).setAttribute("className", "selected");		
	  }	 
	  else
	  {
	  	document.getElementById("gr"+i).setAttribute("className", "");
		document.getElementById("gr"+i).setAttribute("class", "");
	  }
  }
	
	new Ajax.Request('<?php echo $base_url.'/home/getCate/'; ?>'+catid,
  {
    method:'get',
    onSuccess: function(transport){
      var response = transport.responseText || "no response text";
      document.getElementById('catInner').innerHTML = response;
    },
    onFailure: function(){ alert('Something went wrong...') }

  });
}

 function getProjects(type){
	document.getElementById('test').innerHTML = '<img src="<?php echo image_url('load3.gif');?>" alt="loading" />' + 'Loading';	
 	new Ajax.Request('<?php echo $base_url.'/home/listProjects/'; ?>'+type,
  {

    method:'get',
    onSuccess: function(transport){
      var response = transport.responseText || "no response text";
      document.getElementById('test').innerHTML = response
    },
    onFailure: function(){ alert('Something went wrong...') }
  });
 }

<?php

if($groups->num_rows()>0)
{
	$row_count=$groups->num_rows();
	$i=0;
	foreach ($groups->result() as $catarray)
	{
		if($i==0)
		{
		$firstcatid=$catarray->id;
		  $i++;
		} 
	}	
}

?>

 <?php if($groups_num > 0){?>
 //getCat('0','<?php echo $row_count; ?>','<?php echo $firstcatid; ?>');
 <?php } ?>


 checkFind('product');

</script>
<?php $this->load->view('footer'); ?>