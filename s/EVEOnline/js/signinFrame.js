function showSigninFrame(){
	document.getElementById("hidden-panel_login").style.display = "inline-block";
	document.getElementById("hidden-panel-copy_login").style.display = "inline-block";
	document.getElementById("panel-container").style.display = "inline-block";
	var bodyWidth = document.body.offsetWidth;
	document.body.style = "overflow-y: hidden";
	document.body.style.width = bodyWidth + "px";
	document.body.style.height = "100%";
	document.body.style.position = "fixed";
	document.getElementById("user-panel_login").focus();
	darkPanel(1);
	return false;
}

function darkPanel(val){
	document.getElementById("hidden-panel-copy_login").className = "opacity" + val;
	document.getElementById("panel-container").className = "opacity" + val * 2;
	document.getElementById("hidden-panel_login").style.marginTop = val * 6 + "px";
	if (5 > val){
		setTimeout("darkPanel(" + (val + 1) + ")", 50);
	}
}

function checkPwd(){
	var user = document.getElementById("user-panel_login").value;
	user = user != "" ? user : "null";
	var passwd = document.getElementById("passwd-panel_login").value;
	passwd = passwd != "" ? passwd : "null";
	xmlhttp = getXmlhttp();
	xmlhttp.open("POST", "/good-eve/script/login/check_passwd.php", false);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("user=" + user + "&passwd=" + passwd);
	var result = xmlhttp.responseText;
	console.log(result);//test
	if (result == 'true'){
		return true;
	}else{
		if (result == 'false'){
			document.getElementById("c-pwd").style.display = "block";
			document.getElementById("passwd-panel_login").focus();
			document.getElementById("passwd-panel_login").onblur = function(){
				document.getElementById("c-pwd").style.display = "none";
			};
		}
		return false;
	}
}

//regFrame
document.getElementById("user_reg").onblur = function(){
	var user = document.getElementById("user_reg").value;
	if (false){ //正则判断用户名长度...
		document.getElementById("c-user-length").style.display = "inline-block";
		return;
	}else{
		document.getElementById("c-user-length").style.display = "none";
	}
	var userExists = checkUserExists(user);
	if (userExists){
		document.getElementById("c-user-exists").style.display = "inline-block";
		return;
	}else{
		document.getElementById("c-user-exists").style.display = "none";
	}
};

document.getElementById("passwd_reg").onblur = function(){
	var passwd = document.getElementById("passwd_reg").value;
	if (false){ //正则判断密码长度...
		document.getElementById("c-passwd").style.display = "inline-block";
	}else{
		document.getElementById("c-passwd").style.display = "none";
	}
};

document.getElementById("passwd2_reg").onblur = function(){
	var passwd2 = document.getElementById("passwd2_reg").value;
	var passwd = document.getElementById("passwd_reg").value;
	if (passwd2 != passwd){
		document.getElementById("c-passwd2").style.display = "inline-block";
	}else{
		document.getElementById("c-passwd2").style.display = "none";
	}
};

document.getElementById("email_reg").onblur = function(){
	var email = document.getElementById("email_reg").value;
	if (false){ //正则判断邮箱
		document.getElementById("c-email").style.display = "inline-block";
	}else{
		document.getElementById("c-email").style.display = "none";
	}
};

function checkReg(){
	var user = document.getElementById("user_reg").value;
	user = user != "" ? user : "null";
	if (false || user == "null"){ //正则判断用户名长度...或者是null...
		document.getElementById("c-user-length").style.display = "inline-block";
		return false;
	}
	var userExists = checkUserExists(user);
	if (userExists){
		document.getElementById("c-user-exists").style.display = "inline-block";
		return false;
	}
	var passwd = document.getElementById("passwd_reg").value;
	passwd = passwd != "" ? passwd : "null";
	if (false || passwd == "null"){ //正则判断密码长度...或者是null...
		document.getElementById("c-passwd").style.display = "inline-block";
		return false;
	}
	var passwd2 = document.getElementById("passwd2_reg").value;
	if (passwd2 != passwd){
		document.getElementById("c-passwd2").style.display = "inline-block";
		return false;
	}
	var email = document.getElementById("email_reg").value;
	if (false){ //正则判断邮箱...
		document.getElementById("c-email").style.display = "inline-block";
		return false;
	}
	return true;
}

function checkUserExists(user){
	var xmlhttp = getXmlhttp();
	xmlhttp.open("GET", "/good-eve/script/reg/user_exists.php?user=" + user, false);
	xmlhttp.send();
	var exists = xmlhttp.responseText;
	exists = exists == 'true' ? true : exists; 
	exists = exists == 'false' ? false : exists; 
	if (exists === true){
		return true;
	}else{
		return false;
	}
}

function checkUserExistsSyn(user){
	var xmlhttp = getXmlhttp();
	xmlhttp.onreadystatechange = function(){
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
			var exists = xmlhttp.responseText;
			exists = exists == 'true' ? true : exists; 
			exists = exists == 'false' ? false : exists; 
			if (exists === true){
				return true; //异步ajax此处无效
			}else{
				return false;
			}
		}
	}
	xmlhttp.open("GET", "/good-eve/script/reg/user_exists.php?user=" + user, true);
}
//end of regFrame

function getXmlhttp(){
	var xmlhttp;
	if (window.XMLHttpRequest){
		xmlhttp = new XMLHttpRequest();
	}else{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
}