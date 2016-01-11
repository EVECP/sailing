<?php
	require_once('../conf/constant.php');
	require_once('../../../conf/db_link.php');
	require_once('../../../conf/constant.php');
	if (!isset($_SESSION)){
		session_start();
	}
	if (isset($_SESSION['user'])){
		if (!$db_link_user = get_connection()){
			//some code...
			exit;
		}
		$sql_user = 'select username from user where id=' . $_SESSION['user'];
		$username = '';
		if ($res_user = mysqli_query($db_link_user, $sql_user)){
			if ($datarow_user = mysqli_fetch_array($res_user)){
				$username = $datarow_user['username'];
			}
			mysqli_free_result($res_user);
			mysqli_close($db_link_user);
		}else{
			//some code...
			mysqli_close($db_link_user);
			exit;
		}
	}
	$has_login = 'true';
	if (!isset($_SESSION['user'])){
		$has_login = 'false';
	}
?>
<!DOCTYPE>
<html lang="zh-CN">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../../../css/includes/global.css">
		<link rel="stylesheet" href="../../../css/includes/header.css">
		<link rel="stylesheet" href="../../../css/includes/side.css">
		<link rel="stylesheet" href="../../../css/submit.css">
	</head>
	<body>
		<div id="header">
			<div id="c-header">
				<div id="title">
					<a id="site-title" href="<?=HOST?>">
						Sailing<!--img-->
					</a><span
					id="send"><a href="<?=HOST?>/s/<?=strtolower(PANEL_NAME)?>"><?=PANEL_DISPLAY?></a>：送出</span>
				</div>
				<div id="header-signed-in">
					<span><?=$username?><span id="split">|</span><a href="<?=HOST?>/script/logout/logout.php">登出</a></span>
				</div>
			</div>
		</div>
		<div id="content">
			<div id="c-content">
				<div id="side">
					<div class="spacer">
						<input type="text" placeholder="搜索" id="search">
					</div>
				</div>
				<div id="c-new">
					<h1>发表至 <?=PANEL_DISPLAY?></h1>
					<form id="form-new" method="get" name="new" action="javascript:void(0);" onsubmit="sub();">
						<ul id="tab-menu">
							<?php
								if (isset($_GET['type']) && $_GET['type'] == 'link'){
							?>
							<li id="new-link" class="tab-active">链接</li><li
							id="new-text">文本</li>
							<input id="menu-type" type="hidden" name="menu_type" value="link">
							<?php
								}else{
							?>
							<li id="new-link">链接</li><li
							class="tab-active" id="new-text">文本</li>
							<input id="menu-type" type="hidden" name="menu_type" value="text">
							<?php
								}
							?>
						</ul>
						<div class="spacer">
							<div class="field">
								<span class="title">标题</span>
								<div class="field-c">
									<textarea rows="2" name="title"></textarea>
								</div>
							</div>
						</div>
						<?php
							if (isset($_GET['type']) && $_GET['type'] == 'link'){
						?>
						<div class="spacer hidden" id="spacer-text">
							<div class="field">
								<span class="title">文字</span>
								<span class="little white">（非必填项目）</span>
								<div class="field-c">
									<textarea rows="5" cols="1" name="text"></textarea>
								</div>
							</div>
						</div>
						<div class="spacer" id="spacer-link">
							<div class="field">
								<span class="title">网址</span>
								<div class="field-c">
									<input type="text" name="link">
								</div>
							</div>
						</div>
						<?php
							}
							if (!isset($_GET['type']) || (isset($_GET['type']) && $_GET['type'] == 'text')){
						?>
						<div class="spacer" id="spacer-text">
							<div class="field">
								<span class="title">文字</span>
								<span class="little white">（非必填项目）</span>
								<div class="field-c">
									<textarea rows="5" cols="1" name="text"></textarea>
								</div>
							</div>
						</div>
						<div class="spacer hidden" id="spacer-link">
							<div class="field">
								<span class="title">网址</span>
								<div class="field-c">
									<input type="text" name="link">
								</div>
							</div>
						</div>
						<?php
							}
						?>
						<div class="spacer">
							<div class="field">
								<span class="title">选择看板</span>
								<div class="field-c">
									<input id="panel-id" type="hidden" name="panel_id" value="<?=PANEL?>">
									<input id="panel-name2" type="text" value="<?=PANEL_DISPLAY?>">
									<div id="c-panels">
										<span>你已订阅的看板</span>
										<div id="panels"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="spacer">
							<div class="field">
								<span class="title">选项</span>
								<div class="field-c">
									<input id="sendreplies" type="checkbox" name="sendreplies" checked="checked">
									<label for="sendreplies">将回复寄到我的收件箱</label>
								</div>
							</div>
						</div>
						<input type="submit" value="送出">
					</form>
				</div>
			</div>
		</div>
		<script src="../../../s_includes/js/xmlhttp.js"></script>
		<script src="../../../s_includes/js/submit.js"></script>
		<script>
			window.onload = function(){
				if (!<?=$has_login?>){
					window.location.href = '../../../login';
				}
				getPanelArr(<?=$_SESSION['user']?>);
			}
		</script>
	</body>
</html>