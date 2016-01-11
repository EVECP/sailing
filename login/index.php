<?php
	require_once('../conf/constant.php');
	$url_referer = '';
	if (isset($_SERVER["HTTP_REFERER"])){
		$url_referer = $_SERVER["HTTP_REFERER"] . ($_SERVER["QUERY_STRING"] == '' ? '' : '?' . $_SERVER["QUERY_STRING"]);
	}else{
		$url_referer = '../';
	}
?>
<!DOCTYPE>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../css/includes/global.css">
		<link rel="stylesheet" href="../css/includes/header.css">
		<link rel="stylesheet" href="../css/login.css">
	</head>
	<body>
		<div id="header">
			<div id="c-header">
				<div id="title">
					<a id="site-title" href="<?=HOST?>">
						Sailing<!--img-->
					</a>
				</div><span
				id="subtitle">注册或登录账号</span>
			</div>
		</div>
		<div id="content">
			<div id="login">
				<div id="panel">
					<div id="reg-panel">
						<h4 id="reg-title">建立一个新账号</h4>
						<form id="register-form" method="post" action="javascript:void(0);">
							<div class="form-group">
								<input class="form-control" type="text" autocomplete="off" placeholder="选择一个用户名" tabindex="2" maxlength="20" name="username" value="">
							</div>
							<div class="form-group">
								<input class="form-control" type="password" tabindex="2" name="passwd" placeholder="密码">
							</div>
							<div class="form-group">
								<input class="form-control" type="password" tabindex="2" name="passwd2" placeholder="确认密码">
							</div>
							<div class="form-group">
								<input class="form-control" type="text" tabindex="2" name="email" placeholder="邮箱（选填）">
							</div>
							<div class="c-checkbox">
								<input id="rem-reg" type="checkbox" name="rem">
								<label for="rem-reg">记住我</label>
							</div>
							<div class="submit-group">
								<input class="sub-btn" type="submit" value="注册" onclick="register('<?=$url_referer?>');">
							</div>
						</form>
					</div>
					<div id="log-panel">
						<h4 id="log-title">登录</h4>
						<form id="login-form" method="post" action="javascript:void(0);">
							<div class="form-group">
								<input class="form-control" type="text" placeholder="用户名" tabindex="3" maxlength="20" name="username" value="">
							</div>
							<div class="form-group">
								<input class="form-control" type="password" placeholder="密码" tabindex="3" name="passwd">
							</div>
							<div class="c-checkbox">
								<input id="rem-login" type="checkbox" name="rem">
								<label for="rem-login">记住我</label>
								<a href="">重设密码</a>
							</div>
							<div class="submit-group">
								<input class="sub-btn" type="submit" value="登录" onclick="login('<?=$url_referer?>');">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<script src="../js/xmlhttp.js"></script>
		<script src="../js/regAndLog.js"></script>
	</body>
</html>