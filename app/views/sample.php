<!--MEMBER LOGIN-->
<!--RC-->
<div class="block">
  <div class="black_t">
    <div class="black_r">
      <div class="black_b">
        <div class="black_l">
          <div class="black_tl">
            <div class="black_tr">
              <div class="black_bl">
                <div class="black_br">
                  <div class="cls100_p">
                    <div id="selLogin">
                      <h3><span class="clsOptDetial"><?php echo $this->lang->line('Sort Search Result');?></span></h3>
                      <style type="text/css">
							  div.slider { width:220px; margin:10px 0; background-color:#ccc; height:10px; position: relative; }
							  div.slider div.handle { width:10px; height:15px; background-color:#000; cursor:move; position: absolute; }
							  div#zoom_element { width:50px; height:50px; background:#2d86bd; position:relative; }
					  </style>
                      <div class="demo">
					  	<p><?php echo $this->lang->line('Budget'); ?></p>
                        <p><?php echo $this->lang->line('Budget'); ?></p>
                        <div id="zoom_slider" class="slider">
                          <div class="handle"></div>
                        </div>
                        
                      </div>
                      <script type="text/javascript">
						  (function() {
							var zoom_slider = $('zoom_slider'),
							   box = $('zoom_element');
						
							new Control.Slider(zoom_slider.down('.handle'), zoom_slider, {
							  range: $R(0,4000),
							  sliderValue:0,
							  onSlide: function(value) {
							   // box.setStyle({ width: value + 'px', height: value + 'px' });
							  },
							  onChange: function(value) { 
								//box.setStyle({ width: value + 'px', height: value + 'px' });
							  }
							});
  					})();
</script>
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
<!--END OF RC-->
<!--END OF MEMBER LOGIN-->
