<?php
	require_once('../conf/db_link.php');
	if (isset($_GET) && $_GET['name'] != '' && $_GET['display_name'] != ''){
		if (!$db_link = get_connection()){
			//some code...
			exit;
		}
		$sql = 'insert into panel (name,display_name,recode) values(?,?,0)';
		$stmt = mysqli_stmt_init($db_link);
		if (mysqli_stmt_prepare($stmt, $sql)){
			mysqli_stmt_bind_param($stmt, 'ss', $_GET['name'], $_GET['display_name']);
			if (mysqli_stmt_execute($stmt)){
				mysqli_stmt_close($stmt);
				mysqli_close($db_link);
			}
			echo -3;
			mysqli_stmt_close($stmt);
			mysqli_close($db_link);
		}else{
			echo -2;
			mysqli_close($db_link);
		}
	}
?>
<!DOCTYPE>
<html lang="zh-CN">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta charset="UTF-8">
	</head>
	<body>
		<form action="" method="GET">
			name:<input id="name" type="text" name="name">
			display_name:<input id="display-name" type="text" name="display_name">
			<input type="submit" value="提交">
		</form>
	</body>
</html>