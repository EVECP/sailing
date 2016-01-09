<?php
	require_once('conf/constant.php');
	require_once('../../conf/constant.php');
	require_once('../../conf/db_link.php');
	if (!isset($_SESSION)){
		session_start();
	}
	if (!$db_link = get_connection()){
		//some code...
		echo -1;
		exit;
	}
	$sql = 'select subject_id from subject_score where panel_id=' . PANEL . ' order by score,subject_id desc limit 0,25';
	$subject_ids_arr = array();
	if ($res = mysqli_query($db_link, $sql)){
		while ($datarow = mysqli_fetch_array($res)){
			$subject_ids_arr[] = $datarow['subject_id'];
		}
		mysqli_free_result($res);
		mysqli_close($db_link);
	}else{
		mysqli_close($db_link);
		//some code...
		echo -2;
		exit;
	}
	
	$subjects_arr = array();
	foreach ($subject_ids_arr as $subject_id){
		if (!$db_link_subject = get_connection()){
			//some code...
			exit;
		}
		$sql_subject = 'select * from subject where id=' . $subject_id;
		if ($res_subject = mysqli_query($db_link_subject, $sql_subject)){
			if ($datarow_subject = mysqli_fetch_array($res_subject)){
				$subject = array();
				$subject['id'] = $datarow_subject['id']; //id
				$user_id = $datarow_subject['user_id'];
				if (!$db_link_user = get_connection()){
					//some code...
					echo -1;
					exit;
				}
				$sql_user = 'select username from user where id=' . $user_id;
				if ($res_user = mysqli_query($db_link_user, $sql_user)){
					if ($datarow_user = mysqli_fetch_array($res_user)){
						$subject['username'] = $datarow_user['username']; //username
					}
					mysqli_free_result($res_user);
					mysqli_close($db_link_user);
				}else{
					mysqli_close($db_link_user);
					//some code...
					echo -2;
					exit;
				}
				$subject['title'] = $datarow_subject['title'];
				$subject['link'] = $datarow_subject['link'];
				$subject['from_url'] = $datarow_subject['from_url'];
				$panel_id = $datarow_subject['panel_id'];
				if (!$db_link_panel = get_connection()){
					//some code...
					echo -1;
					exit;
				}
				$sql_panel = 'select name from panel where id=' . $panel_id;
				if ($res_panel = mysqli_query($db_link_panel, $sql_panel)){
					if ($datarow_panel = mysqli_fetch_array($res_panel)){
						$subject['panel'] = $datarow_panel['name'];
					}
					mysqli_free_result($res_panel);
					mysqli_close($db_link_panel);
				}else{
					mysqli_close($db_link_panel);
					//some code...
					echo -2;
					exit;
				}
				$up = $datarow['up'];
				$down = $datarow['down'];
				$subject['score'] = intval($up) - intval($down);
				$sub_time = $datarow_subject['sub_time'];
				$time_diff = time() - intval($sub_time);
				if ($time_diff < 60){
					$subject['time'] = floor($time_diff) . '秒';
				}elseif (3600 > $time_diff && $time_diff >= 60){
					$subject['time'] = floor($time_diff / 60) . '分钟';
				}elseif ((24 * 60 * 60) > $time_diff && $time_diff >= 3600){
					$subject['time'] = floor($time_diff / (60 * 24)) . '小时';
				}elseif ($time_diff >= (24 * 60 * 60)){
					$subject['time'] = floor($time_diff / (60 * 60 * 24)) . '天';
				}
				$subjects_arr[] = $subject;
			}
			mysqli_free_result($res_subject);
			mysqli_close($db_link_subject);
		}else{
			mysqli_close($db_link_subject);
		}
	}
?>
<!DOCTYPE>
<html lang="zh-CN">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../../css/includes/global.css">
		<link rel="stylesheet" href="../../css/includes/header.css">
		<link rel="stylesheet" href="../../css/includes/side.css">
		<link rel="stylesheet" href="../../css/index.css">
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
				<?php
					if (!isset($_SESSION['user'])){
				?>
				<div id="header-sign-in">
					<span>想要加入？<a href="">&nbsp;注册或登录帐号&nbsp;</a>不用几秒钟</span>
				</div>
				<?php
					}else{
				?>
				<div id="header-signed-in">
					<!--some code-->
				</div>
				<?php
					}
				?>
			</div>
		</div>
		<div id="content">
			<div id="c-content">
				<div id="side">
					<div class="spacer">
						<input type="text" placeholder="搜索" id="search">
					</div>
					<div class="spacer" id="side-sign-in">
						<form method="post" action="" onsubmit="">
							<input name="username" type="text" placeholder="用户名" maxlength="20"><input
							name="passwd" type="password" placeholder="密码">
							<div>
								<div id="remember-and-recover">
									<input id="remember" type="checkbox" name="remember">
									<label for="remember">记住我</label>
									<a id="recover" href="">重设密码</a>
								</div>
								<div>
									<input type="submit" value="登入">
								</div>
							</div>
						</form>
					</div>
					<div class="spacer">
						<a class="new-btn" id="new-link">发表新链接</a>
					</div>
					<div class="spacer">
						<a class="new-btn" id="new-sub">发表新文章</a>
					</div>
					<div class="spacer">
						<a class="new-btn" id="new-panel">建立新看板</a>
					</div>
				</div>
				<div id="subjects">
					<?php
						foreach($subjects_arr as $i=>$subject){
					?>
					<!--1-->
					<div class="spacer">
						<span class="num"><?=$i + 1?></span>
						<div class="unvoted">
							<div class="arrow up" id="upvote"></div>
							<div class="score" id="dislikes"><?=$subject['score'] - 1?></div>
							<div class="score active" id="unvoted"><?=$subject['score']?></div>
							<div class="score" id="likes"><?=$subject['score'] + 1?></div>
							<div class="arrow down" id="downvote"></div>
						</div>
						<div class="entry">
							<p class="c-title">
								<?php
									if ($subject['link'] == ''){
								?>
								<a class="title" href="<?=HOST?>/s/<?=$subject['panel']?>/comments/subject?subject=<?=$subject['id']?>">
								<?php
									}else{
								?>
								<a class="title" href="<?=$subject['link']?>">
								<?php
									}
								?>
								<?=$subject['title']?>
								</a>
								<span class="domain">
									<a>(<?=$subject['from_url']?>)</a>
								</span>
							</p>
							<p class="tagline">
								<time><?=$subject['time']?></time>
								前被
								<a class="author"><?=$subject['username']?></a>
								提交到
								<a class="subto">/s/<?=$subject['panel']?></a>
							</p>
							<p class="subtagline">
								<a class="comments">221留言</a>
							</p>
						</div>
					</div>
					<?php
						}
					?>
					<!--2-->
					<div class="spacer">
						<span class="num">2</span>
						<div class="unvoted">
							<div class="arrow up" id="upvote"></div>
							<div class="score" id="dislikes">21</div>
							<div class="score active" id="unvoted">22</div>
							<div class="score" id="likes">23</div>
							<div class="arrow down" id="downvote"></div>
						</div>
						<div class="entry">
							<p class="c-title">
								<a class="title" href="comments?subject=<??>">
								One of the best series of random events ever caught on film.
								TIL that a father was denied access to see his premature twins in the NICU when Beyonce and Jay-Z had their daughter at the same time.
								</a>
								<span class="domain">
									<a>(sailing.com)</a>
								</span>
							</p>
							<p class="tagline">
								<time>2小时</time>
								前被
								<a class="author">liyz</a>
								提交到
								<a class="subto">/s/music</a>
							</p>
							<p class="subtagline">
								<a class="comments">221留言</a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>