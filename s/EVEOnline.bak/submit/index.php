<?php
	require_once('../conf/constant.php');
	require_once('../../../conf/db_link.php');
	if (!isset($_SESSION)){
		session_start();
	}
	if (!isset($_SESSION['user'])){
		//header('Location: ../login');
	}
	/*
	if (!$db_link = get_connection()){
		//some code...
		exit;
	}
	$sql_panel = 'select name from panel where id=' . PANEL;
	$panel_name = '';
	if ($res_panel = mysqli_query($db_link, $sql_panel)){
		if ($datarow_panel = mysqli_fetch_array($res_panel)){
			$panel_name = $datarow_panel['name'];
		}
		mysqli_free_result($res_panel);
		mysqli_close($db_link);
	}else{
		mysqli_close($db_link);
		//some code...
		exit;
	}*/
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
					<a id="site-title" href="./">
						Sailing<!--img-->
					</a><span
					id="panel-name">
						<a><?=PANEL_NAME?><a>
					</span>
				</div>
				<div id="menu">
					<ul>
						<li class="menu-active"><a href="./">热门</a></li>
						<li><a href="new/">最新</a></li>
						<li><a href="rising/">好评上升中</a></li>
						<li><a href="controversial/">具争议的</a></li>
						<li><a href="top/">头等</a></li>
						<li><a href="gilded/">精选</a></li>
						<li><a href="wiki/index/">wiki</a></li>
					</ul>
				</div>
				<div id="header-signed-in">
					<!--some code-->
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
					<h1>发表至 <?=PANEL_NAME?></h1>
					<form id="form-new" method="get" name="new" action="javascript:void(0);" onsubmit="sub();">
						<ul id="tab-menu">
							<li id="new-link">链接</li><li
							class="tab-active" id="new-text">文本</li>
							<input id="menu-type" type="hidden" name="menu_type" value="text">
						</ul>
						<div class="spacer">
							<div class="field">
								<span class="title">标题</span>
								<div class="field-c">
									<textarea rows="2" name="title"></textarea>
								</div>
							</div>
						</div>
						<div class="spacer" id="spacer-text">
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
						<div class="spacer">
							<div class="field">
								<span class="title">选择看板</span>
								<div class="field-c">
									<input id="panel-id" type="hidden" name="panel_id" value="<?=PANEL?>">
									<input id="panel-name2" type="text" value="<?=PANEL_NAME?>">
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
		<script src="../../../s_includes/js/submit.js"></script>
		<script>
			window.onload = function(){
				getPanelArr(<?=1?>);
			}
		</script>
	</body>
</html>