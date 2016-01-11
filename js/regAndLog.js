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
	if (username == ""){
		return false;
	}
	return true;
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