function login(urlReferer){
	var form = document.getElementById('login-form');
	xmlhttp = getXmlhttp();
	xmlhttp.open("POST", "../script/login/login.php", false);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("username=" + form.username.value + "&passwd=" + form.passwd.value);
	var result = xmlhttp.responseText;
	if (parseInt(result) > 0){
		window.location.href = urlReferer;
	}
}

function regCheck(username, passwd, passwd2, email){
	if (trim(username) == ""){
		regPrompt("请填写用户名");
		return false;
	}
	if (passwd != passwd2){
		regPrompt("两次密码填写不一致");
		return false;
	}
	regPrompt("");
	return true;
}
function trim(str){ 
	return str.replace(/(^\s*)|(\s*$)/g, ""); 
}

var form = document.getElementById('register-form');
form.username.onblur = function(){
	regCheck(form.username.value, form.passwd.value, form.passwd2.value, form.email.value);
}
form.passwd2.onblur = function(){
	regCheck(form.username.value, form.passwd.value, form.passwd2.value, form.email.value);
}

function regPrompt(info){
	var regPrompt = document.getElementById("reg-prompt");
	regPrompt.innerHTML = info;
}

function register(urlReferer){
	var form = document.getElementById('register-form');
	if (regCheck(form.username.value, form.passwd.value, form.passwd2.value, form.email.value)){
		xmlhttp = getXmlhttp();
		xmlhttp.open("POST", "../script/register/register.php", false);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("username=" + form.username.value + "&passwd=" + form.passwd.value + "&email=" + form.email.value);
		var result = xmlhttp.responseText;
		if (parseInt(result) > 0){
			window.location.href = urlReferer;
		}
	}	
}