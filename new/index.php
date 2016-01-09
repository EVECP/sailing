<?php
	require_once('../conf/constant.php');
	require_once('../conf/db_link.php');
	if (!isset($_SESSION)){
		session_start();
	}
	
	/**
	 *count of all subjects
	 */
	if (!$db_link_count = get_connection()){
		//some code...
		exit;
	}
	$sql_count = 'select count(id) from subject';
	$subjects_count = 0;
	if ($res_count = mysqli_query($db_link_count, $sql_count)){
		if ($datarow = mysqli_fetch_array($res_count)){
			$subjects_count = $datarow[0];
		}
		mysqli_free_result($res_count);
		mysqli_close($db_link_count);
	}else{
		mysqli_close($db_link_count);
		//some code...
		exit;
	}
	
	$page = 1;
	$count = 25;
	$has_next_page = true;
	if (isset($_GET['page'])){
		$page = intval($_GET['page']);
	}
	if (isset($_GET['count'])){
		$count = intval($_GET['count']);
	}
	if ($page * $count >= $subjects_count){
		$has_next_page = false;
	}
	
	if (!$db_link = get_connection()){
		//some code...
		echo -1;
		exit;
	}
	$sql = 'select subject_id from subject_score order by subject_id desc limit ' . ($count * ($page - 1)) . ',' . $count;
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
				$up = $datarow_subject['up'];
				$down = $datarow_subject['down'];
				$subject['voted'] = intval($up) - intval($down);
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
				if (!$db_link_comment = get_connection()){
					//some code...
					exit;
				}
				$sql_comment = 'select count(id) from comment where subject_id=' . $subject['id'];
				if ($res_comment = mysqli_query($db_link_comment, $sql_comment)){
					if ($datarow_comment = mysqli_fetch_array($res_comment)){
						$subject['comments_count'] = $datarow_comment[0];
					}
					mysqli_free_result($res_comment);
					mysqli_close($db_link_comment);
				}else{
					//some code...
					mysqli_close($db_link_comment);
				}
				if (isset($_SESSION['user'])){
					if (!$db_link_subject_vote = get_connection()){
						//some code...
						exit;
					}
					$sql_subject_vote = 'select vote from subject_vote where subject_id=' . $subject['id'] . ' and user_id=' . $_SESSION['user'];
					if ($res_subject_vote = mysqli_query($db_link_subject_vote, $sql_subject_vote)){
						if ($datarow_subject_vote = mysqli_fetch_array($res_subject_vote)){
							$subject['vote'] = $datarow_subject_vote['vote'];
						}
						mysqli_free_result($res_subject_vote);
						mysqli_close($db_link_subject_vote);
					}else{
						mysqli_close($db_link_subject_vote);
						//some code...
						exit;
					}
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
		<link rel="stylesheet" href="../css/includes/global.css">
		<link rel="stylesheet" href="../css/includes/header.css">
		<link rel="stylesheet" href="../css/includes/side.css">
		<link rel="stylesheet" href="../css/index.css">
	</head>
	<body>
		<div id="header">
			<div id="c-header">
				<div id="title">
					<a id="site-title" href="<?=HOST?>">
						Sailing<!--img-->
					</a>
				</div>
				<div id="menu">
					<ul>
						<li><a href="../">热门</a></li>
						<li class="menu-active"><a href="./">最新</a></li>
						<li><a href="../rising/">好评上升中</a></li>
						<li><a href="../controversial/">具争议的</a></li>
						<li><a href="../top/">头等</a></li>
						<li><a href="../gilded/">精选</a></li>
						<li><a href="../wiki/index/">wiki</a></li>
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
					<?php
						if (!isset($_SESSION['user'])){
					?>
					<div class="spacer" id="side-sign-in">
						<form id="side-login" method="post" action="javascript:void(0);" onsubmit="return false;">
							<input name="username" type="text" placeholder="用户名" maxlength="20"><input
							name="passwd" type="password" placeholder="密码">
							<div>
								<div id="remember-and-recover">
									<input id="remember" type="checkbox" name="remember">
									<label for="remember">记住我</label>
									<a id="recover" href="">重设密码</a>
								</div>
								<div>
									<input type="submit" value="登入" onclick="sideLogin();">
								</div>
							</div>
						</form>
					</div>
					<?php
						}
					?>
					<div class="spacer">
						<a class="new-btn" id="new-link" href="../submit/?type=link">发表新链接</a>
					</div>
					<div class="spacer">
						<a class="new-btn" id="new-sub" href="../submit/">发表新文章</a>
					</div>
					<div class="spacer">
						<a class="new-btn" id="new-panel">建立新看板</a>
					</div>
				</div>
				<div id="subjects">
					<?php
						foreach($subjects_arr as $i=>$subject){
					?>
					<div class="spacer">
						<span class="num"><?=$i + 1?></span>
						<div class="unvoted">
							<?php
								if (isset($subject['vote']) && $subject['vote'] == 1){
							?>
							<div class="arrow up upvoted" id="up-subject<?=$subject['id']?>"
								onclick="cancelUpSubject(<?=(isset($_SESSION['user']) ? $_SESSION['user'] : 0)?>, <?=$subject['id']?>);"></div>
							<?php
								}else{
							?>
							<div class="arrow up" id="up-subject<?=$subject['id']?>"
								onclick="upSubject(<?=(isset($_SESSION['user']) ? $_SESSION['user'] : 0)?>, <?=$subject['id']?>);"></div>
							<?php
								}
							?>
							
							<?php
								if (isset($subject['vote']) && $subject['vote'] == -1){
							?>
							<div class="score active" id="dislikes<?=$subject['id']?>"><?=$subject['voted']?></div>
							<div class="score" id="unvoted<?=$subject['id']?>"><?=$subject['voted'] + 1?></div>
							<div class="score" id="likes<?=$subject['id']?>"><?=$subject['voted'] + 2?></div>
							<?php
								}elseif (isset($subject['vote']) && $subject['vote'] == 1){
							?>
							<div class="score" id="dislikes<?=$subject['id']?>"><?=$subject['voted'] - 2?></div>
							<div class="score" id="unvoted<?=$subject['id']?>"><?=$subject['voted'] - 1?></div>
							<div class="score active" id="likes<?=$subject['id']?>"><?=$subject['voted']?></div>
							<?php
								}else{
							?>
							<div class="score" id="dislikes<?=$subject['id']?>"><?=$subject['voted'] - 1?></div>
							<div class="score active" id="unvoted<?=$subject['id']?>"><?=$subject['voted']?></div>
							<div class="score" id="likes<?=$subject['id']?>"><?=$subject['voted'] + 1?></div>
							<?php
								}
							?>
							
							<?php
								if (isset($subject['vote']) && $subject['vote'] == -1){
							?>
							<div class="arrow down downvoted" id="down-subject<?=$subject['id']?>"
								onclick="cancelDownSubject(<?=(isset($_SESSION['user']) ? $_SESSION['user'] : 0)?>, <?=$subject['id']?>);"></div>
							<?php
								}else{
							?>
							<div class="arrow down" id="down-subject<?=$subject['id']?>"
								onclick="downSubject(<?=(isset($_SESSION['user']) ? $_SESSION['user'] : 0)?>, <?=$subject['id']?>);"></div>
							<?php
								}
							?>
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
								<a class="subto" href="<?=HOST?>/s/<?=strtolower($subject['panel'])?>">/s/<?=$subject['panel']?></a>
							</p>
							<p class="subtagline">
								<a class="comments" href="<?=HOST?>/s/<?=strtolower($subject['panel'])?>/comments/subject?subject=<?=$subject['id']?>"><?=($subject['comments_count'] == 0 ? '' : $subject['comments_count'])?>留言</a>
							</p>
						</div>
					</div>
					<?php
						}
					?>
				</div>
				<?php
					if ($has_next_page){
				?>
				<div class="nav-btns">
					<div class="next-page">
						继续阅读：
						<a href="./?page=<?=$page + 1?>&count=<?=$count?>">下一页 ›</a>
					</div>
				</div>
				<?php
					}
				?>
			</div>
		</div>
		<script src="../js/xmlhttp.js"></script>
		<script src="../js/sideLogin.js"></script>
		<script src="../js/sailing.js"></script>
	</body>
</html>