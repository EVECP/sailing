<?php
	require_once('../../conf/db_link.php');
	
	$user_id = 1;//...
	$type = $_GET['menu_type'];
	$title = $_GET['title'];
	$text = '';
	$link = '';
	$from_url = '';
	if ($type == 'text'){
		$text = $_GET['text'];
		$from_url = 'self.Music';//var from_url equals panel name
	}elseif ($type == 'link'){
		$link = $_GET['link'];		
		//var from_url equal...;
	}
	$panel_id = $_GET['panel_id'];
	$sendreplies = $_GET['sendreplies'];
	$up = 0;
	$down = 0;
	$sub_time = time();
	
	if (!$db_link = get_connection()){
		echo -1;
		exit;
	}
	$sql_subject = 'insert into subject (user_id,title,txt,link,sendreplies,from_url,panel_id,up,down,sub_time) values(?,?,?,?,?,?,?,?,?,?)';
	$subject_id = '';
	$stat = 0;
	if ($stmt = mysqli_prepare($db_link, $sql_subject)){
		mysqli_stmt_bind_param($stmt, 'isssisiiis', $user_id, $title, $text, $link, intval($sendreplies), $from_url, intval($panel_id), $up, $down, $sub_time);
		if (mysqli_stmt_execute($stmt)){
			$sql_last_id = 'select last_insert_id()';
			if ($res = mysqli_query($db_link, $sql_last_id)){
				if ($datarow = mysqli_fetch_array($res)){
					$subject_id = $datarow[0];
				}
				mysqli_free_result($res);
			}
		}else{
			$stat = -2;
		}
		mysqli_stmt_close($stmt);
	}else{
		$stat = -2;
	}
	mysqli_close($db_link);
	if ($stat != 0){
		echo $stat;
		exit;
	}
	
	if (!$db_link = get_connection()){
		echo -1;
		exit;
	}
	$sql_subject_score = 'insert into subject_score (subject_id,panel_id,score) values(' . $subject_id . ',' . $panel_id . ',0)';
	if (!$res = mysqli_query($db_link, $sql_subject_score)){
		mysqli_close($db_link);
		echo -2;
		exit;
	}
	mysqli_close($db_link);
	echo $subject_id;
?>