<?php
	require_once('../../conf/db_link.php');
	
	$user_id = $_GET['user_id'];
	$subject_id = $_GET['subject_id'];
	$direct = $_GET['direct'];
	
	$sql = '';
	$sql_subject = '';
	if ($direct == 'up'){
		$sql = 'insert into subject_vote (subject_id,user_id,vote) values(' . $subject_id . ',' . $user_id . ',1)';
		$sql_subject = 'update subject set up=up+1 where id=' . $subject_id;
	}elseif ($direct == 'down'){
		$sql = 'insert into subject_vote (subject_id,user_id,vote) values(' . $subject_id . ',' . $user_id . ',-1)';
		$sql_subject = 'update subject set down=down+1 where id=' . $subject_id;
	}
	if (!$db_link = get_connection()){
		echo -1;
		exit;
	}
	if (mysqli_query($db_link, $sql)){
		mysqli_close($db_link);
	}else{
		mysqli_close($db_link);
		echo -2;
		exit;
	}
	if (!$db_link_subject = get_connection()){
		echo -1;
		exit;
	}
	if (mysqli_query($db_link_subject, $sql_subject)){
		mysqli_close($db_link_subject);;
	}else{
		mysqli_close($db_link_subject);
		echo -2;
		exit;
	}
	/**
	 *热门排名算法
	 */
	if (!$db_link_subject_score = get_connection()){
		echo -1;
		exit;
	}
	$sql_subject_score = 'select up,down,sub_time from subject where id=' . $subject_id;
	$score = 0;
	if ($res_subject_score = mysqli_query($db_link_subject_score, $sql_subject_score)){
		if ($datarow_subject_score = mysqli_fetch_array($res_subject_score)){
			$up = $datarow_subject_score['up'];
			$down = $datarow_subject_score['down'];
			$sub_time = $datarow_subject_score['sub_time'];
			$x = $up - $down;
			$y = 0;
			if ($x > 0){
				$y = 1;
			}elseif ($x < 0){
				$y = -1;
			}
			$t = time() - $sub_time;
			$z = 1;
			if (($up - $down) > 0){
				$z = $up - $down;
			}
			$score = log10($z) + $y * $t / 45000;
		}
		mysqli_free_result($res_subject_score);
		mysqli_close($db_link_subject_score);
	}else{
		mysqli_close($db_link_subject_score);
		echo -2;
		exit;
	}
	if (!$db_link_score = get_connection()){
		echo -1;
		exit;
	}
	$sql_score = 'update subject_score set score=' . $score . 'where subject_id=' . $subject_id;
	if (mysqli_query($db_link_score, $sql_score)){
		mysqli_close($db_link_score);
		echo 1;
	}else{
		mysqli_close($db_link_score);
		echo -2;
	}
?>