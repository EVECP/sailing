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