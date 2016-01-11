<?php
	require_once('../../conf/db_link.php');
	if (!isset($_SESSION)){
		session_start();
	}
	$username = $_POST['username'];
	$passwd = $_POST['passwd'];
	$email = $_POST['email'];
	if (!$db_link_reg = get_connection()){
		echo -1;
		exit;
	}
	$sql_reg = '';
	if ($email != ''){
		$sql_reg = 'insert into user (username,userpwd,panels,email) values (?,?,?,?)';
	}else{
		$sql_reg = 'insert into user (username,userpwd,panels) values (?,?,?)';
	}
	$panels = '[1,2]';
	$stmt_reg = mysqli_stmt_init($db_link_reg);
	if (mysqli_stmt_prepare($stmt_reg, $sql_reg)){
		if ($email != ''){
			mysqli_stmt_bind_param($stmt_reg, 'ssss', $username, $passwd, $panels, $email);
		}else{
			mysqli_stmt_bind_param($stmt_reg, 'sss', $username, $passwd, $panels);
		}
		if (mysqli_stmt_execute($stmt_reg)){
			$user_id = mysqli_insert_id($db_link_reg);
			$add_login_res = add_login($user_id, $username, $passwd);
			if ($add_login_res > 0){
				$_SESSION['user'] = $user_id;
				echo $user_id;
				mysqli_stmt_close($stmt_reg);
				mysqli_close($db_link_reg);
				exit;
			}else{
				del_user($user_id);
			}
		}
		echo -2;
		mysqli_stmt_close($stmt_reg);
		mysqli_close($db_link_reg);
	}else{
		echo -2;
		mysqli_close($db_link_reg);
	}
	
	function add_login($user_id, $username, $passwd){
		if (!$db_link = get_connection()){
			return -1;
		}
		$sql = 'insert into login (id, username, passwd) values (?,?,?)';
		$stmt = mysqli_stmt_init($db_link);
		if (mysqli_stmt_prepare($stmt, $sql)){
			mysqli_stmt_bind_param($stmt, 'iss', $user_id, $username, $passwd);
			if (mysqli_stmt_execute($stmt)){
				mysqli_stmt_close($stmt);
				mysqli_close($db_link);
				return 1;
			}else{
				mysqli_stmt_close($stmt);
				mysqli_close($db_link);
				return -2;
			}
		}else{
			mysqli_close($db_link);
			return -2;
		}
	}
	
	function del_user($user_id){
		if (!$db_link = get_connection()){
			return -1;
		}
		$sql = 'delete from user where id=' . $user_id;
		if (mysqli_query($db_link, $sql)){
			mysqli_close($db_link);
			return 1;
		}else{
			mysqli_close($db_link);
			return -2;
		}
	}
?>