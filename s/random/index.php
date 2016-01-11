<?php
	require_once('../../conf/constant.php');
	require_once('../../conf/db_link.php');
	if (!$db_link_panel = get_connection()){
		//some code...
		exit;
	}
	$sql_panel = 'select name from panel order by rand() limit 1';
	$panel_name = '';
	if ($res_panel = mysqli_query($db_link_panel, $sql_panel)){
		if ($datarow_panel = mysqli_fetch_array($res_panel)){
			$panel_name = $datarow_panel['name'];
		}
		mysqli_free_result($res_panel);
		mysqli_close($db_link_panel);
	}else{
		mysqli_close($db_link_panel);
		//some code...
		exit;
	}
	header('Location: ' . HOST . '/s/' . strtolower($panel_name));
?>