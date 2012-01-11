<style type="text/css">

/*Credits: Dynamic Drive CSS Library */
/*URL: http://www.dynamicdrive.com/style/ */
.suckerdiv{
padding:3.8em 1.5em 0 0;
width:190px;
}
.suckerdiv ul{
margin: 0;
padding: 0;
list-style-type: none;
width: 190px; /* Width of Menu Items */
border-bottom: 1px solid #ccc;
text-align:left;
}
.suckerdiv ul li{
position: relative;
}
/*Sub level menu items */
.suckerdiv ul li ul{
position: absolute;
width: 170px; /*sub menu width*/
top: 0;
visibility: hidden;
}

/* Sub level menu links style */
.suckerdiv ul li a{
display: block;
overflow: auto; /*force hasLayout in IE7 */
color: black;
text-decoration: none;
background:#ccc;
padding: 5px 5px;
border: 1px solid #999;
border-bottom: 0;

color:#000;
}

.suckerdiv ul li a:visited{
color: black;
}

.suckerdiv ul li a:hover{
background-color: #000;
color:#fff;
}

.suckerdiv .subfolderstyle{
background: url(<?php echo image_url('arrow-list.gif');?>) no-repeat center right;
background-color:#ccc;
}

	
/* Holly Hack for IE \*/
* html .suckerdiv ul li { float: left; height: 1%; }
* html .suckerdiv ul li a { height: 1%; }
/* End */

</style>
<script type="text/javascript">

//SuckerTree Vertical Menu 1.1 (Nov 8th, 06)
//By Dynamic Drive: http://www.dynamicdrive.com/style/

var menuids=["suckertree1"] //Enter id(s) of SuckerTree UL menus, separated by commas

function buildsubmenus(){
for (var i=0; i<menuids.length; i++){
  var ultags=document.getElementById(menuids[i]).getElementsByTagName("ul")
    for (var t=0; t<ultags.length; t++){
    ultags[t].parentNode.getElementsByTagName("a")[0].className="subfolderstyle"
		if (ultags[t].parentNode.parentNode.id==menuids[i]) //if this is a first level submenu
			ultags[t].style.left=ultags[t].parentNode.offsetWidth+"px" //dynamically position first level submenus to be width of main menu item
		else //else if this is a sub level submenu (ul)
		  ultags[t].style.left=ultags[t-1].getElementsByTagName("a")[0].offsetWidth+"px" //position menu to the right of menu item that activated it
    ultags[t].parentNode.onmouseover=function(){
    this.getElementsByTagName("ul")[0].style.display="block"
    }
    ultags[t].parentNode.onmouseout=function(){
    this.getElementsByTagName("ul")[0].style.display="none"
    }
    }
		for (var t=ultags.length-1; t>-1; t--){ //loop through all sub menus again, and use "display:none" to hide menus (to prevent possible page scrollbars
		ultags[t].style.visibility="visible"
		ultags[t].style.display="none"
		}
  }
}

if (window.addEventListener)
window.addEventListener("load", buildsubmenus, false)
else if (window.attachEvent)
window.attachEvent("onload", buildsubmenus)

</script>
<div id="sideBar">
  <div class="sideBar1 clsFloatLeft">
      <div class="suckerdiv">
        <ul id="suckertree1">
          <li><a href="<?php echo admin_url('home');?>"><?php echo $this->lang->line('Dash Board'); ?></a></li>
          <li><a href="<?php echo admin_url('siteSettings');?>"><?php echo $this->lang->line('website_settings'); ?></a></li>
          <li><a href="<?php echo admin_url('paymentSettings');?>"><?php echo $this->lang->line('payment_settings'); ?></a></li>
          <li><a href="<?php echo admin_url('emailSettings');?>"><?php echo $this->lang->line('Email Settings'); ?></a></li>
		  <li><a href="#"><?php echo $this->lang->line('Payments'); ?></a>
            <ul>
              <li><a href="#"><?php echo $this->lang->line('Transaction'); ?></a>
                <ul>
                  <li><a href="<?php echo admin_url('payments/addTransaction');?>" ><?php echo $this->lang->line('Add Transaction'); ?></a></li>
                  <li><a href="<?php echo admin_url('payments/searchTransaction');?>"><?php echo $this->lang->line('Search'); ?></a></li>
                  <li><a href="<?php echo admin_url('payments/viewTransaction');?>"><?php echo $this->lang->line('View All'); ?></a></li>
                </ul>
              </li>
			<li><a href="#"><?php echo $this->lang->line('Withdrawal Queue'); ?></a>
			    <ul>
                  <li><a href="<?php echo admin_url('payments/releaseWithdraw');?>"><?php echo $this->lang->line('Release Withdraw'); ?></a></li>
                  <li><a href="<?php echo admin_url('payments/viewWithdraw');?>"><?php echo $this->lang->line('View All'); ?></a></li>
                 </ul>
			  </li>
			  
              <li><a href="#"><?php echo $this->lang->line('Escrow Transaction'); ?></a>
                <ul>
                  <li><a href="<?php echo admin_url('payments/releaseEscrow');?>"><?php echo $this->lang->line('Escrow Release'); ?></a></li>
                  <li><a href="<?php echo admin_url('payments/viewEscrow');?>"><?php echo $this->lang->line('View All'); ?></a></li>
                </ul>
				</li>
			</ul>	
          </li>
		  <li><a href="<?php echo admin_url('affiliateSettings');?>"><?php echo $this->lang->line('Affiliate Settings'); ?></a>
            <ul>
              <li><a href="<?php echo admin_url('affiliateSettings');?>" ><?php echo $this->lang->line('Affiliate Settings'); ?></a></li>
              <li><a href="<?php echo admin_url('affiliateSettings/clickThroughs');?>"><?php echo $this->lang->line('Click Throughs'); ?></a></li>
              <li><a href="<?php echo admin_url('affiliateSettings/sales');?>"><?php echo $this->lang->line('Sales'); ?></a></li>
			  <li><a href="<?php echo admin_url('affiliateSettings/questions');?>"><?php echo $this->lang->line('Affiliate Questions'); ?></a></li>
			  <li><a href="<?php echo admin_url('affiliateSettings/archives');?>"><?php echo $this->lang->line('Archived Questions'); ?></a></li>
			  <li><a href="<?php echo admin_url('affiliateSettings/releasePayment');?>"><?php echo $this->lang->line('Release Payments'); ?></a></li>
			</ul>	
		  </li>
          <li><a href="#"><?php echo $this->lang->line('Manage Users'); ?></a>
            <ul>
				<li><a href="#"><?php echo $this->lang->line('Users'); ?></a>
                <ul>
				<li><a href="<?php echo admin_url('users/searchUsers');?>"><?php echo $this->lang->line('search_user'); ?></a></li>
                  <li><a href="<?php echo admin_url('users/addUsers');?>"><?php echo $this->lang->line('Add users'); ?></a></li>
                  <li><a href="<?php echo admin_url('users/viewUsers');?>"><?php echo $this->lang->line('View users'); ?></a></li>
                  
                </ul>
				</li>
              <li><a href="#"><?php echo $this->lang->line('Bans'); ?></a>
                <ul>
                  <li><a href="<?php echo admin_url('users/addBans');?>"><?php echo $this->lang->line('Add bans'); ?></a></li>
                  <li><a href="<?php echo admin_url('users/editBans');?>"><?php echo $this->lang->line('Edit bans'); ?></a></li>
                </ul>
              </li>
              
			    <li><a href="#"><?php echo $this->lang->line('Suspend'); ?></a>
			  	<ul>
					<li><a href="<?php echo admin_url('users/addSuspend'); ?>"><?php echo $this->lang->line('Add Suspend'); ?></a></li>  
					<li><a href="<?php echo admin_url('users/editSuspend'); ?>"><?php echo $this->lang->line('Edit Suspend'); ?></a></li>     
		 		</ul>
			  </li>
            </ul>
          </li>
		  <li><a href="#"><?php echo $this->lang->line('Manage Packages'); ?></a>
            <ul>
              <li><a href="#"><?php echo $this->lang->line('Packages'); ?></a>
                <ul>
                  <li><a href="<?php echo admin_url('packages/addPackages');?>" ><?php echo $this->lang->line('Add Package'); ?></a></li>
                  <!--<li><a href="<?php echo admin_url('packages/searchPackage');?>"><?php echo $this->lang->line('Search'); ?></a></li>-->
                  <li><a href="<?php echo admin_url('packages/viewpackage');?>"><?php echo $this->lang->line('View All'); ?></a></li>
                </ul>
              </li>
              <li><a href="<?php echo admin_url('packages/viewsubscriptionuser');?>"><?php echo $this->lang->line('Subscription Users'); ?></a>
			  <ul>
                  <li><a href="<?php echo admin_url('packages/viewsubscriptionuser');?>" ><?php echo $this->lang->line('View subscription user'); ?></a></li>
                  <li><a href="<?php echo admin_url('packages/searchsubscriptionuser');?>"><?php echo $this->lang->line('Search Subscription user'); ?></a></li>
                </ul>
				</li>
              <li><a href="<?php echo admin_url('packages/viewsubscriptionpayment');?>"><?php echo $this->lang->line('Subscription payment'); ?></a>
			  <ul>
			   <li><a href="<?php echo admin_url('packages/viewsubscriptionpayment');?>" ><?php echo $this->lang->line('View subscription Payment'); ?></a></li>
                  <li><a href="<?php echo admin_url('packages/searchsubscriptionpayment');?>"><?php echo $this->lang->line('Search Subscription Payment'); ?></a></li>
				  </ul>
			  </li> 
			</ul>	
          </li>
		  <li><a href="<?php echo admin_url('users/viewAdmin');?>"><?php echo $this->lang->line('View Admin'); ?></a></li>
          <li><a href="<?php echo admin_url('skills/viewGroups');?>"><?php echo $this->lang->line('groups'); ?></a></li>
          <li><a href="<?php echo admin_url('skills/viewCategories');?>"><?php echo $this->lang->line('categories'); ?></a></li>
          <li><a href="<?php echo admin_url('skills/viewBids');?>"><?php echo $this->lang->line('Bids'); ?></a></li>
          <li><a href="<?php echo admin_url('skills/viewProjects');?>"><?php echo $this->lang->line('Projects'); ?></a></li>
		  <li><a href="<?php echo admin_url('support/viewSupport');?>"><?php echo $this->lang->line('support'); ?></a></li>
		  <li><a href="<?php echo admin_url('projectCases/viewCases');?>"><?php echo $this->lang->line('dispute'); ?></a></li>
          <li><a href="<?php echo admin_url('faq/viewFaqs');?>"><?php echo $this->lang->line('faqs'); ?></a></li>
          <li><a href="<?php echo admin_url('page/viewPages');?>"><?php echo $this->lang->line('Manage Static pages'); ?></a></li>
          
		
		 		  
		  
        </ul>
      </div> 
  </div>
</div>