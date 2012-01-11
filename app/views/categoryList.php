<ul class="clsFloatLeft">
<?php
if(isset($categories) and $categories->num_rows()>0)
 {
	$i=0;
	foreach($categories->result() as $category)
	{
	if($category->is_active==1)
	{
  	   //$name = replaceSpaceWithUnderscore($category->category_name);
	  $name =$category->category_name;
	   	?>
       <li><a href="<?php echo site_url('project/category/'.$name); ?>"><?php echo $category->category_name;?></a></li>
       <?php $i++; 
	   }
	}
}  ?>
</ul>
