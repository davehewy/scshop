<?php 

	$extension = '?';
	$loop = 0;

	foreach($pass_mb as $key=>$value){
	
	if($loop==0){
		$extension.= $key.'='.urlencode($value);
	}else{
		$extension.='&'.$key.'='.urlencode($value);
	}
	
	$loop++;

	} 
	
?>
<iframe name="frame_content" scrolling="no" frameborder="0" src="https://www.moneybookers.com/app/payment.pl<?=$extension?>" width="100%" height="100%" border="0"></iframe>