<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/sidebar'); ?>

<div id="main">
  <div class="clsSettings">
    <div class="clsMainSettings">
	<!--TOP TITLE & RESET-->
      <div class="clsTop clsClearFixSub">
          <div class="clsNav">
          <ul>
            <li><a href="<?php echo admin_url('faq/viewFaqCategories')?>"><?php echo $this->lang->line('view_faq_categories'); ?></a></li>
			<li><a href="<?php echo admin_url('faq/addFaqCategory')?>"><?php echo $this->lang->line('add_faq_category'); ?></a></li>
			<li class="clsNoBorder"><a href="<?php echo admin_url('faq/addFaq')?>"><?php echo $this->lang->line('add_faq'); ?></a></li>
          </ul>
        </div>
		<div class="clsTitle">
          <h3><?php echo $this->lang->line('view_faq_categories'); ?></h3>
        </div>
      </div>
      <!--END OF TOP TITLE & RESET-->
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
         
        <table class="table" cellpadding="2" cellspacing="0">
		<th></th>
          <th><?php echo $this->lang->line('id'); ?></th>
          <th><?php echo $this->lang->line('faq_category_name'); ?></th>
          <th><?php echo $this->lang->line('created'); ?></th>
          <th><?php echo $this->lang->line('action'); ?></th>
       
		<?php
			if(isset($faqCategories) and $faqCategories->num_rows()>0)
			{
				foreach($faqCategories->result() as $faqCategory)
				{
		?><form action="" name="managefaqlist" method="post" >
			 <tr>
			  <td><input type="checkbox" class="clsNoborder" name="faqlist[]" id="faqlist[]" value="<?php echo $faqCategory->id; ?>"  /> </td>
			  <td><?php echo $faqCategory->id; ?></td>
			  <td><?php echo $faqCategory->category_name; ?></td>
			  <td><?php echo date('Y-m-d',$faqCategory->created); ?></td>
			  <td><a href="<?php echo admin_url('faq/editFaqCategory/'.$faqCategory->id)?>"><img src="<?php echo image_url('edit-new.png'); ?>" alt="Edit" title="Edit" /></a>
			   <a href="<?php echo admin_url('faq/deleteFaqCat/'.$faqCategory->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a></td>
        	</tr>
        <?php
				}//Foreach End - Traverse Faq Categories
			}//If End
		?>
		</table>
		</form>
		<br />
    <div class="clscenter clearfix">
	  <div id="selLeftAlign">
	<?php echo $this->lang->line('With Selected'); ?>
	 <a name="delete" href="javascript: document.managefaqlist.action='<?php echo admin_url('faq/deleteFaqCat'); ?>'; document.managefaqlist.submit();" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a></div>
	</div>
	</div>
      <!--PAGING-->
	 	<?php if(isset($pagination)) echo $pagination;?>
	 <!--END OF PAGING-->
    </div>
    <!--END OF MID WRAPPER-->
  </div>
  <!-- End of clsSettings -->
</div>
<!-- End Of Main -->
<?php $this->load->view('admin/footer'); ?>
