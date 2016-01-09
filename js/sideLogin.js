function sideLogin(){
	var form = document.getElementById('side-login');
	xmlhttp = getXmlhttp();
	xmlhttp.open("POST", "script/side/side_login.php", false);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("username=" + form.username.value + "&passwd=" + form.passwd.value);
	var result = xmlhttp.responseText;
	alert(result);//...
	if (parseInt(result) > 0){
		window.location.href = './';
	}
}