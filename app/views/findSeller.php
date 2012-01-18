<h3><span class="clsMyEscrow">
<?php
if ($results->num_rows > 1) {
    echo $this->lang->line('There are');
} else {
    echo $this->lang->line('There is');
}
echo $results->num_rows . " ".$this->lang->line('Seller')." ". $this->lang->line('in area');
?>
    </span> </h3>


<form method="get" name="searchForm" action="<?php echo base_url(); ?>">
    <p>
        <input type="text" name="keyword" id="keyword" class="clsText"/>
        <!-- <input type="text" class="clsText" />-->
        <select name="category">
            <option value="">Select A Category</option><?php
foreach ($categories->result() as $cat) {
    ?>
                <option value="<?php echo $cat->id; ?>"><?php echo $cat->category_name; ?></option>
                <?php } ?>
        </select>
        <input type="submit" value="<?php echo $this->lang->line('Search'); ?>" class="clsMiniBL"   />
        <input type="hidden" name="c" value="search" />
        <input type="hidden" name="m" value="professional" />
    </p>
</form>
