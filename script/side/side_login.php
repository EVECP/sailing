<?php
	require_once('../../conf/db_link.php');
	if (!isset($_SESSION)){
		session_start();
	}
	$username = $_POST['username'];
	$passwd = $_POST['passwd'];
	if (!$db_link_login = get_connection()){
		echo -1;
		exit;
	}
	$sql_login = 'select id from login where username=? and passwd=?';
	$stmt_login = mysqli_stmt_init($db_link_login);
	if (mysqli_stmt_prepare($stmt_login, $sql_login)){
		mysqli_stmt_bind_param($stmt_login, 'ss', $username, $passwd);
		mysqli_stmt_execute($stmt_login);
		mysqli_stmt_bind_result($stmt_login, $id);
		$user_id = 0;
		if (mysqli_stmt_fetch($stmt_login)){
			$user_id = $id;
		}
		mysqli_stmt_close($stmt_login);
		mysqli_close($db_link_login);
		if ($user_id > 0){
			$_SESSION['user'] = $user_id;
		}
		echo $user_id;
	}else{
		echo -2;
		mysqli_close($db_link_login);
	}
?>
