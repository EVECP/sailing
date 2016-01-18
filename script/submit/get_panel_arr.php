<?php
	require_once('../../conf/db_link.php');
	
	$user_id = $_GET['user_id'];
	if (!$db_link = get_connection()){
		echo -1;
		exit;
	}
	/*$sql_user = 'select panels from user where id=' . $user_id;
	$panel_ids_arr = array();
	if ($res = mysqli_query($db_link, $sql_user)){
		if ($datarow = mysqli_fetch_array($res)){
			$panel_ids_json = $datarow['panels'];
			$panel_ids_arr = json_decode($panel_ids_json);
		}
		mysqli_free_result($res);
	}else{
		echo -2;
		exit;
	}
	$panels_arr = array();
	foreach ($panel_ids_arr as $panel_id){
		$sql_panel = 'select * from panel where id=' . $panel_id;
		if ($res = mysqli_query($db_link, $sql_panel)){
			if ($datarow = mysqli_fetch_array($res)){
				$panel = array();
				$panel['id'] = $panel_id;
				$panel['display_name'] = $datarow['display_name'];
				$panel['name'] = $datarow['name'];
				$panels_arr[] = $panel;
			}
			mysqli_free_result($res);
		}else{
			echo -2;
			exit;
		}
	}*/
	/**
	 *临时查找所有panel
	 */
	$panels_arr = array();
	$sql_panel = 'select * from panel';
	if ($res = mysqli_query($db_link, $sql_panel)){
		while ($datarow = mysqli_fetch_array($res)){
			$panel = array();
			$panel['id'] = $panel_id;
			$panel['display_name'] = $datarow['display_name'];
			$panel['name'] = $datarow['name'];
			$panels_arr[] = $panel;
		}
		mysqli_free_result($res);
	}else{
		echo -2;
		exit;
	}
	echo json_encode($panels_arr);
?>