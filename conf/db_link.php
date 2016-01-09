<?php
	$db_link;
	
	function get_connection(){
		$db_link = mysqli_connect('localhost', 'root', '', 'sailing');
		if (mysqli_error($db_link)){
			return false;
		}else{
			mysqli_query($db_link, 'set name utf8');
			return $db_link;
		}
	}
?>