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
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/includes/global.css">
		<link rel="stylesheet" href="css/includes/header.css">
		<link rel="stylesheet" href="css/includes/marquee.css">
		<link rel="stylesheet" href="css/index.css">
		<link rel="stylesheet" href="css/login.css">
		<script src="js/tool/TranspMarquee/TranspMarquee.js"></script>
	</head>
	<body>
		<div id="header">
			<div id="c-header-copy"></div>
			<div id="c-header">
				<div id="title">
					<span>THE FRONT PAGE <span id="of">OF</span></span>
					<img src="img/header/eve-online.png" alt="EVE-Online">
				</div>
				<div id="menu-copy">
					<ul>
						<li><a>热门</a></li>
						<li><a>最新</a></li>
						<li><a>好评上升中</a></li>
						<li><a>具争议的</a></li>
						<li><a>头等</a></li>
						<li><a>精选</a></li>
						<li><a>wiki</a></li>
					</ul>
				</div>
				<div id="menu">
					<ul>
						<li><a href="" class="high-lighter">热门</a></li>
						<li><a href="new/">最新</a></li>
						<li><a href="">好评上升中</a></li>
						<li><a href="">具争议的</a></li>
						<li><a href="">头等</a></li>
						<li><a href="">精选</a></li>
						<li><a href="">wiki</a></li>
					</ul>
				</div>
				
				<?php
					if (!isset($_SESSION['user']) && !isset($_SESSION['id'])){
				?>
				
				<div id="headersign-copy">
					<span>想要加入？<a href="" class="btn signin">&nbsp;登入或注册账号&nbsp;</a>不用几秒钟</span>
				</div>
				<div id="headersign">
					<span>想要加入？<a href="" onclick="return showSigninFrame();" class="btn signin">&nbsp;登入或注册账号&nbsp;</a>不用几秒钟</span>
				</div>
				
				<?php
					}else{
				?>
				
				<div id="headersignedin-copy">
					<span>
						<a class="high-lighter"><?=$_SESSION['user']?></a>(<a
						 class="high-lighter"><??></a>)
						 |
						 <a class="high-lighter">登出</a>
					</span>
				</div>
				<div id="headersignedin">
					
					<span>
						<a href="" class="high-lighter"><?=$_SESSION['user']?></a>(<a
						 href="" class="high-lighter"><??></a>)
						 |
						<form id="logout" action="http://localhost/good-eve/pg/post/logout/" method="post">
							<a href="javascript:void(0);" onclick="document.getElementById('logout').submit();" class="high-lighter">登出</a>
						</form>
					</span>
				</div>
				
				<?php
					}
				?>
				
			</div>
			<div class="border-bot op"></div>
			<div id="marquee-bar">
				<div id="this-is-my-marquee"></div>
			</div>
		</div>
		
		<!-- signin frame -->
		<div id="hidden-panel-copy_login"></div>
		<div id="panel-container">
			<div id="hidden-panel_login">
				<div class="split-panel">
					<div class="split-panel-section split-panel-divider">
						<div class="contain_reg">
							<h4 class="modal-title">建立一个新账号</h4>
							<form id="register-form" method="post" action="http://localhost/good-eve/pg/post/reg/" class="form-v2" onsubmit="return checkReg();">
								<div class="c-form-group">
									<label for="user_reg" class="screenreader-only">用户名：</label>
									<input value name="user" id="user_reg" class="c-form-control" type="text" maxlength="20" placeholder="选择一个用户名">
									
									<div class="check c-user prompt" id="c-user-exists">
										<span class="tri"></span>
										<span class="prompt userexists">这个用户名已经被注册了</span>
									</div>
									<div class="check c-user prompt" id="c-user-length">
										<span class="tri"></span>
										<span class="prompt userlength">用户名必须介于3~20个字</span>
									</div>
									
								</div>
								<div class="c-form-group">
									<label for="passwd_reg" class="screenreader-only">密码：</label>
									<input id="passwd_reg" name="passwd" class="c-form-control" type="password" placeholder="密码">
									
									<div class="check c-passwd prompt" id="c-passwd">
										<span class="tri"></span>
										<span class="prompt passwdlength">密码长度至少要6位</span>
									</div>
									
								</div>
								<div class="c-form-group">
									<label for="passwd2_reg" class="screenreader-only">确认密码：</label>
									<input name="passwd2" id="passwd2_reg" class="c-form-control" type="password" placeholder="确认密码">
									
									<div class="check c-passwd2 prompt" id="c-passwd2">
										<span class="tri"></span>
										<span class="prompt passwd2wrong">密码不符</span>
									</div>
									
								</div>
								<div class="c-form-group">
									<label for="email_reg" class="screenreader-only"></label>
									<input value name="email" id="email_reg" class="c-form-control" type="text" placeholder="email（可选）">
									
									<div class="check c-email prompt" id="c-email">
										<span class="tri"></span>
										<span class="prompt emailwrong">此电子邮箱是无效的</span>
									</div>
									
								</div>
								<div class="c-checkbox">
									<div class="c-rem">
										<input type="checkbox" name="rem" id="rem_reg">
										<label for="rem_reg">记住我</label>
									</div>
								</div>
								<div class="c-clearfix c-submit-group">
									<span class="c-form-throbber"></span>
									<button type="submit" class="c-btn c-btn-primary c-pull-right">建立账号</button>
								</div>
							</form>
						</div>
					</div>
					<div class="split-panel-section">
						<div class="contain_signin">
							<h4 class="modal-title">登入</h4>
							<form id="login-form" method="post" action="http://localhost/good-eve/pg/post/login/" onsubmit="return checkPwd();" class="form-v2">
								<div class="c-form-group">
									<label for="user_login" class="screenreader-only">用户名：</label>
									<input name="user" id="user-panel_login" class="c-form-control" type="text" maxlength="20" placeholder="用户名" autofocus>
								</div>
								<div class="c-form-group">
									<label for="passwd_login" class="screenreader-only">密码：</label>
									<input id="passwd-panel_login" class="c-form-control" name="passwd" type="password" placeholder="密码">
									
									
									<div class="check c-pwd prompt" id="c-pwd">
										<span class="tri"></span>
										<span class="prompt pwdwrong">密码错误</span>
									</div>
									
								</div>
								<div class="c-checkbox">
									<div class="c-rem">
										<input type="checkbox" name="rem" id="rem_login">
										<label for="rem_login">记住我</label>
									</div>
									<a href="/password" class="c-pull-right">重设密码</a>
								</div>
								<div class="c-clearfix c-submit-group">
									<span class="c-form-throbber"></span>
									<button type="submit" class="c-btn c-btnprimary c-pull-right" id="submit-user-panel">登入</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end of signin frame -->
		<div id="content">
			<div id="c-content">
			
				<div id="sidebar">
					<div class="spacer">
						<input type="text" placeholder="search" id="search">
						<img class="btn search" src="../img/sidebar/search-magnifier2.png">
					</div>
					
					<?php
						if (!(isset($_SESSION['id']) && isset($_SESSION['user']))){
					?>
					
					<div class="spacer" id="login-area">
						<form method="post" action="http://localhost/good-eve/pg/post/login/" onsubmit="return checkSidebarPasswd();" id="login_login-main" class="login-form login-form-side">
							<input name="user" type="text" id="user" placeholder="用户名" maxlength="20">
							<input name="passwd" type="password" id="passwd" placeholder="密码">
							<div id="login-main">
								<div id="remember-me">
									<input type="checkbox" name="rem" id="rem-login-main">
									<label for="rem-login-main" id="rem-prompt">记住我</label>
									<a id="recover-password" href="">重设密码</a>
								</div>
								<div class="submit" id="login-subbutton">
									<button class="btn" type="submit" id="submit-user">登入</button>
								</div>
								<div class="clear"></div>
							</div>
						</form>
					</div>
					
					<?php
						}
					?>
					
					<div class="spacer c-button">
						<a class="btn large-btn" id="new-sub-btn">发表新文章</a>
					</div>
					<div class="spacer c-button">
						<a class="btn large-btn" id="new-panel-btn">建立新看板</a>
					</div>
				</div>
				
				
				<div id="subjects">
					<?php
						foreach ($subjects_arr as $i=>$subject){
					?>
					<div class="spacer" onmouseover="this.style.backgroundColor='#2b2b2b';" onmouseout="this.style.backgroundColor='#323232';">
						<div class="background-img top"></div>
						<span class="num"><?=++$i?></span>
						<div class="unvoted">
							<div class="arrow up" id="upvote"></div>
							<div class="score" id="dislikes"><?=$subject['score'] - 1?></div>
							<div class="score active" id="unvoted"><?=$subject['score']?></div>
							<div class="score" id="likes"><?=$subject['score'] + 1?></div>
							<div class="arrow down" id="downvote"></div>
						</div>
						<div class="entry">
							<p class="title">
								<a href="comments/subject?subject=<?=$subject['id']?>" class="title">
								<?=$subject['title']?>
								</a>
								<span class="domain">
									<a>(<?=$subject['from_url']?>)</a>
								</span>
							</p>
							<p class="tagline">
								<time><?=$subject['time']?></time>
								前被
								<a class="author may-blank"><?=$subject['username']?></a>
								提交到
								<a class="subsailing may-blank">/s/<?=$subject['panel']?></a>
							</p>
							<p class="subtagline btn">
								<a class="comments may-blank">221留言</a>
							</p>
						</div>
					</div>
					<?php
						}
					?>
				</div>
				
			</div>
		</div>
		
		<script>
			initMarquee("this-is-my-marquee", "today_goods_price.json");
		</script>
	</body>
</html>