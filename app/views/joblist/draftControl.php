<form name="gotopage" id="draftForm" method="post" action="<?php echo site_url('joblist/draftView'); ?>">
  <p><b><?php echo $this->lang->line('My Saved Projects:');?></b>
    <select name="draftId" id="draftId"  onChange="javascript:submitDraft();">
      <option value="savedraft">-- Select a project to load a saved draft --</option>
      <?php 
		  foreach($draftProjects->result() as $draft)
		    { ?>
      <option value="<?php echo $draft->id; ?>" <?php if(isset($draftProjectsid)) { if($draftProjectsid == $draft->id) echo "selected"; } ?>><?php echo get_datetime($draft->created).' '.$draft->project_name; ?></option>
      <?php 
			} ?>
      <option value="clear">-- Clear form and create new project... --</option>
    </select>
	<input name="projectid1" value="<?php if(isset($draftProjectsid)) echo $draftProjectsid; ?>"type="hidden"/>
  </p>
</form>
<script type="text/javascript">
function submitDraft()
{
	if($('draftId').value!='')
	{
		$('draftForm').submit();
	}
}
</script>

