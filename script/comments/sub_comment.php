<?php
	require_once('../../conf/db_link.php');
	
	$subject_id = intval($_GET['subject_id']);
	$reply_context = $_GET['reply_context'];
	$user_id = 1;//user_id  = $_SESSION['user']
	$up = 0;
	$down = 0;
	$score = 0;
	$pid = intval($_GET['pid']);
	$sub_time = time();
	if (!$db_link_comment = get_connection()){
		echo -1;
		exit;
	}
	$sql_comment = 'insert into comment(subject_id,context,up,down,score,pid,user_id,sub_time) values (?,?,?,?,?,?,?,?)';
	$stmt_comment = mysqli_stmt_init($db_link_comment);
	if (mysqli_stmt_prepare($stmt_comment, $sql_comment)){
		mysqli_stmt_bind_param($stmt_comment, 'isiiiiis', $subject_id, $reply_context, $up, $down, $score, $pid, $user_id, $sub_time);
		if (mysqli_stmt_execute($stmt_comment)){
			echo 1;
		}else{
			echo 0;
		}
		mysqli_stmt_close($stmt_comment);
		mysqli_close($db_link_comment);
	}else{
		echo -2;
		mysqli_close($db_link_comment);
	}
?>