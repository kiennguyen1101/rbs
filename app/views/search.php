<!--SEARCH-->
<div class="clsMainSearch">
  <div class="navmenutabs">
    <ul>
      <li id="work"><a href="#" onclick="checkFind('work')"><span><?php echo $this->lang->line('Find Work');?></span></a></li>      
      <?php if (is_object($loggedInUser))                
        switch ($loggedInUser->role_name) { 
          case "seller": ?>
      <li id="s_buyer" class=""><a href="#" onclick="checkFind('s_buyer')"><span><?php echo $this->lang->line('Find Buyer');?></span></a></li>
              <?php break; ?>
         <?php case "buyer": ?>
      <li id="s_seller" class=""><a href="#" onclick="checkFind('s_seller')"><span><?php echo $this->lang->line('Find Seller');?></span></a></li>
              <?php break; 
          }         
      ?>
    </ul>
  </div>
  <div class="boxholder">
    <div class="block">
      <div class="search_t">
        <div class="search_r">
          <div class="search_b">
            <div class="search_l">
              <div class="search_tl">
                <div class="search_tr">
                  <div class="search_bl">
                    <div class="search_br">
                      <div class="cls100_p">
                        <div class="clsSearchBox clsSitelinks" id="innerContent">
                         
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
</div>
<!--END OF SEARCH-->