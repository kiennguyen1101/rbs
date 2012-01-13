<?php
//header("content-type: application/x-javascript");
$serverIP=$_SERVER['REMOTE_ADDR'];
//echo "document.write(\"".$serverIP."".$serverIP."\")";
$tab = "<span style=\"padding-left: 100px\"></span>";
	$testfeed1	= 'Project Name'.$tab.'Bids'.$tab.'Avg Bid'.$tab.'Job Type'.$tab.'Started'.$tab.'Ends';
echo $testfeed1;
echo "<br/>";
echo "<br/>";
foreach($projectlist_result->result() as $row) {

$start_date	=	get_date($row->created);
$end_date	=	get_date($row->enddate);


$arr_start	=	explode(' ',$start_date);
$arr_end	=	explode(' ',$end_date);

	$testfeed	= $row->project_name.$tab.getNumBid($row->id).$tab.getBidsInfo($row->id).$tab.$row->project_categories.$tab.$arr_start[0].$tab.$arr_end[0];
	
	echo $testfeed;
//$somevariable = file_get_contents("http://localhost/prabhu/iboxaudio/app/views/affiliate/test.txt");
//echo ($somevariable);
	
}
exit;

?>