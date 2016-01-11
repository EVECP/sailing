<?php
	require_once('../../../../conf/constant.php');
	require_once('../../../../conf/db_link.php');
	if (!isset($_SESSION)){
		session_start();
	}
	if (!isset($_GET['subject'])){
		header('Location: ../../');
	}
	$uri = $_SERVER['REQUEST_URI'];
	$uri2 = substr($uri, strpos($uri, '/s/') + 3);
	$panel_name = substr($uri2, 0, strpos($uri2, '/'));
	$uri_back = substr($uri2, strpos($uri2, '/'));
	
	$subject_id = $_GET['subject'];
	if (!$db_link_subject = get_connection()){
		//some code...
		exit;
	}
	$sql_subject = 'select * from subject where id=' . $subject_id;
	$subject = array();
	if ($res_subject = mysqli_query($db_link_subject, $sql_subject)){
		if ($datarow_subject = mysqli_fetch_array($res_subject)){
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
			$subject['txt'] = $datarow_subject['txt'];
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
					if (strtolower($datarow_panel['name']) != strtolower($panel_name)){
						header('Location: ' . HOST . '/s/' . $datarow_panel['name'] . $uri_back);
					}
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
		}
		mysqli_free_result($res_subject);
		mysqli_close($db_link_subject);
	}else{
		mysqli_close($db_link_subject);
	}
	
	/**
	 *comments area
	 */
	$comments = get_comments_loop();
	
	function get_comments_loop($pid = 0){
		if (!$db_link_comment = get_connection()){
			return -1;
		}
		$subject_id = $GLOBALS['subject']['id'];
		$sql_comment = 'select id,content,user_id,sub_time from comment where subject_id=' . $subject_id . ' and pid=' . $pid . ' order by score';
		$comments = array();
		if ($res_comment = mysqli_query($db_link_comment, $sql_comment)){
			while ($datarow_comment = mysqli_fetch_array($res_comment)){
				$comments2 = array();
				$comments2['id'] = $datarow_comment['id'];
				$comments2['content'] = $datarow_comment['content'];
				$user_id = $datarow_comment['user_id'];
				if (!$db_link_user = get_connection()){
					return -1;
				}
				$sql_user = 'select username from user where id=' . $user_id;
				if ($res_user = mysqli_query($db_link_user, $sql_user)){
					if ($datarow_user = mysqli_fetch_array($res_user)){
						$comments2['username'] = $datarow_user['username'];
					}
					mysqli_free_result($res_user);
					mysqli_close($db_link_user);
				}else{
					mysqli_close($db_link_user);
					return -2;
				}
				$sub_time = $datarow_comment['sub_time'];
				$time_diff = time() - intval($sub_time);
				if ($time_diff < 60){
					$comments2['time'] = floor($time_diff) . '秒';
				}elseif (3600 > $time_diff && $time_diff >= 60){
					$comments2['time'] = floor($time_diff / 60) . '分钟';
				}elseif ((24 * 60 * 60) > $time_diff && $time_diff >= 3600){
					$comments2['time'] = floor($time_diff / (60 * 24)) . '小时';
				}elseif ($time_diff >= (24 * 60 * 60)){
					$comments2['time'] = floor($time_diff / (60 * 60 * 24)) . '天';
				}
				$children = get_comments_loop($comments2['id']);
				if (is_array($children) && !empty($children)){
					$comments2['children'] = $children;
				}
				$comments[] = $comments2;
			}
			mysqli_free_result($res_comment);
			mysqli_close($db_link_comment);
			return $comments;
		}else{
			mysqli_close($db_link_comment);
			return -2;
		}
	}
	
	function draw_comments_loop($comments, $is_parent){
		foreach ($comments as $comment){
			echo '<div class="comment' . ($is_parent ? '' : ' child-comment') . '" style="margin-left: ' . (10 * ($is_parent ? 0 : 1)) . 'px;">'
				. '<div class="unvoted comment-unvoted">'
				. '<div class="arrow comment-arrow up" onclick="up(' . $comment['id'] . ');"></div>'
				. '<div class="arrow comment-arrow down" onclick="down(' . $comment['id'] . ');"></div>'
				. '</div>'
				. '<div class="tagline-comment">'
				. '<a class="author">' . $comment['username'] . '</a>'
				. ' <time>' . $comment['time'] . '</time> 前提交'
				. '</div>'
				. '<div class="content">';
			echo $comment['content'];
			echo '</div>';
			if (isset($comment['children'])){
				draw_comments_loop($comment['children'], false);
			}
			echo '</div>';
		}
	}
?>
<!DOCTYPE>
<html lang="zh-CN">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../../../../css/includes/global.css">
		<link rel="stylesheet" href="../../../../css/includes/header.css">
		<link rel="stylesheet" href="../../../../css/includes/side.css">
		<link rel="stylesheet" href="../../../../css/subject.css">
	</head>
	<body>
		<div id="header">
			<div id="c-header">
				<div id="title">
					<a id="site-title" href="./">
						Sailing<!--img-->
					</a><span
					id="panel-name">
						<a><?=$subject['panel']?></a>
					</span>
				</div>
				<div id="menu">
					<ul>
						<li class="menu-active"><a href="./">留言</li>
						<li><a href="">相关主题</a></li>
					</ul>
				</div>
				<div id="header-sign-in">
					<span>想要加入？<a href="">&nbsp;注册或登录帐号&nbsp;</a>不用几秒钟</span>
				</div>
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
				<div id="subarea">
					<div id="c-subarea">
						<div class="unvoted">
							<div class="arrow up" id="upvote"></div>
							<div class="score" id="dislikes"><?$subject['score'] - 1?></div>
							<div class="score active" id="unvoted"><?=$subject['score']?></div>
							<div class="score" id="likes"><?$subject['score'] + 1?></div>
							<div class="arrow down" id="downvote"></div>
						</div>
						<div class="entry">
							<p class="c-title">
								<span class="title">
								 <?=$subject['title']?>
								</span>
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
							<div id="context">
								<div id="c-context">
								<?=$subject['txt']?>
								</div>
							</div>
							<ul id="extend-buttons">
								<li>
									<a href="">
									67 留言
									</a>
								</li>
								<li class="share">
									<a href="">
									分享
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div id="commentsarea">
					<div id="c-commentsarea">
						<div id="c-area-title">
							<span id="area-title">所有67则留言</span>
						</div>
						<!--<div id="comments">
							<div class="comment">
								<div class="unvoted">
									<div class="arrow up" id="upvote4comment*"></div>
									<div class="arrow down" id="downvote4comment*"></div>
								</div>
								<div class="tagline-comment">
									<a class="author">Butwella</a>
								</div>
								<div class="content">I don't think there are recreational football leagues for 16-17 year olds.. Atleast not where I live.</div>
							</div>
						</div>-->
							<?php
								draw_comments_loop($comments, true);
							?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>