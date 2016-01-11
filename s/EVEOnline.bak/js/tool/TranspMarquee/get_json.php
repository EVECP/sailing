<?php
	$file_name = $_GET['file'];
	
	$json_str = file_get_contents('../../../db/goodsPrice/' . $file_name);
	
	echo $json_str;
?>