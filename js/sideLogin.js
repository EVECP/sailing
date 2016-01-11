function sideLogin(hostParam){
	var form = document.getElementById('side-login');
	xmlhttp = getXmlhttp();
	var host = arguments[0] ? arguments[0] : "";
	xmlhttp.open("POST", host + "script/side/side_login.php", false);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("username=" + form.username.value + "&passwd=" + form.passwd.value);
	var result = xmlhttp.responseText;
	if (parseInt(result) > 0){
		window.location.href = './';
	}
}