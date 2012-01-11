<h3><span class="clsMyEscrow"><?php echo $this->lang->line('FIND PROFESSIONALS');?>(<?php echo $numProviders;?>)</span></h3>
<form method="get" name="searchForm" action="<?php echo base_url(); ?>">
  <p>
    <input type="text" name="keyword" id="keyword" class="clsText"/>
    <!-- <input type="text" class="clsText" />-->
    <select name="category">
	<option value="">Select A Category</option><?php
	 foreach($categories->result() as $cat)
	   { ?>
 		<option value="<?php echo $cat->id; ?>"><?php echo $cat->category_name; ?></option>
			<?php  
	   }   ?>
	</select>
	<input type="submit" value="<?php echo $this->lang->line('Search');?>" class="clsMiniBL"   />
    <input type="hidden" name="c" value="search" />
	<input type="hidden" name="m" value="professional" />
  </p>
</form>
<?php
if(isset($popular) and $popular->num_rows()>0)
{ ?>
	<p class="clsPopularLinks"><?php echo $this->lang->line('Popular searches:');?>
		<?php
		foreach($popular->result() as $popular)
		{ ?>
		  <a href="<?php echo base_url()."?category=&c=search&m=professional&keyword=".urlencode($popular->keyword);?>"><?php echo $popular->keyword;?></a> <?php 
		} ?>
	</p> <?php
} ?>
