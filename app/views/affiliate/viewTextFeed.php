<?


foreach($textFeed_result->result() as $row) {

	$arr_categories	=	explode(',',$row->project_categories);
	$str	=	'';
	$str1	=	'|';

	for($i=0; $i<count($arr_categories); $i++) {
		$str	.=	$arr_categories[$i];
		if($i == count($arr_categories)-1) 
		$str	.=	'';
		else
		$str	.=	$str1;
	}

	if($row->is_feature == 1) {
		
		$feature	=	'featured ';
		
	}
	
	else {
		$feature	=	' ';
	}
	
	$testfeed	= $row->project_name.' '.site_url().'/project/view/'.$row->id.' '.getNumBid($row->id).' '.$row->created.' '.$row->enddate.' '.$str.' '.$feature;
	
	if(strlen($testfeed) > 209) {
	
	echo $testfeed;
	echo '\n';
	
	}
	
	else echo $testfeed;
	
}


exit;
?>
