<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
      <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	  ?>
    </div>
    <div class="clsMidWrapper">
      <!--MID WRAPPER-->
      <!--TOP TITLE & RESET-->
      <div class="clsTop clsClearFixSub">
          <div class="clsNav">
          <ul>
            <li class="clsNoBorder"><a href="<?php echo admin_url('skills/addCategory')?>"><?php echo $this->lang->line('add_category'); ?></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('categories'); ?></h3>
        </div>
      </div>
      <!--END OF TOP TITLE & RESET-->
	     <table class="table" cellpadding="2" cellspacing="0">
		 <th></th>
          <th><?php echo $this->lang->line('Sl.No'); ?></th>
          <th><?php echo $this->lang->line('category_name'); ?></th>
          <th><?php echo $this->lang->line('group'); ?></th>
          <th><?php echo $this->lang->line('status'); ?></th>
          <th><?php echo $this->lang->line('created'); ?></th>
          <th><?php echo $this->lang->line('action'); ?></th>
        
		<?php
			if(isset($categories) and $categories->num_rows()>0)
			{
				foreach($categories->result() as $category)
				{
		?>
		<form name="managecategory" method="post" action=""/>
			 <tr>
			 <td><input type="checkbox" class="clsNoborder" name="categoryList[]" id="categoryList[]" value="<?php echo $category->id; ?>"  /> </td>
			  <td><?php echo $category->id; ?></td>
			  <td><?php echo $category->category_name; ?></td>
			  <td><?php echo $category->group_name; ?></td>
			  <td><?php if ($category->is_active==0){ ?> <img src="<?php echo image_url('disable.png'); ?>" /> <?php } else { ?><img src="<?php echo image_url('enable.png'); ?>" /><?php } ?></td>
			  <td><?php echo date('Y-m-d',$category->created); ?></td>
			  <td><a href="<?php echo admin_url('skills/editCategory/'.$category->id)?>"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a>
			   <a href="<?php echo admin_url('skills/deleteCategory/'.$category->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a>
			 
			  </td>
        	</tr>
        <?php
				}//Foreach End
			}//If End
			else
			{
			  echo '<tr><td colspan="5">'.$this->lang->line('No Category Found').'</td></tr>'; 
			}
		?>
		</table>
		</div>
		 <div class="clscenter clearfix">
	  <div id="selLeftAlign">
	  <?php echo $this->lang->line('With Selected'); ?>
	  <a name="delete" href="javascript: document.managecategory.action='<?php echo admin_url('skills/deleteCategory/'); ?>'; document.managecategory.submit();" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a></div>
	  </div>
	  </div>
		 <!--PAGING-->
	  	<?php  echo $pagination;?>
	 <!--END OF PAGING-->
     
    </div>
    <!--END OF MID WRAPPER-->
  </div>
  <!-- End of clsSettings -->
</div>
<!-- End Of Main -->
<?php $this->load->view('admin/footer'); ?>
