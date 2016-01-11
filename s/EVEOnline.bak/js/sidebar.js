document.getElementById("new-sub-btn").onclick = function(){
	clickBtn("new-sub-btn");
	checkLoginState('submit');
};

document.getElementById("new-panel-btn").onclick = function(){
	clickBtn("new-panel-btn");
};

function clickBtn(elementId){
	document.getElementById(elementId).style.marginLeft = document.getElementById(elementId).offsetLeft + 1	+ "px";
	setTimeout("afterClickBtn(\"" + elementId + "\")", 100);
}

function afterClickBtn(elementId){
	document.getElementById(elementId).style.margin = "0 auto";
}

function checkSidebarPasswd(){
	var user = document.getElementById("user").value;
	user = user != "" ? user : "null";
	var passwd = document.getElementById("passwd").value;
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
			//...
		}
		return false;
	}
}

function checkLoginState(dir){
	var xmlhttp = getXmlhttp();
	xmlhttp.open("GET", "../script/checkLoginState.php", false);
	xmlhttp.send();
	var result = xmlhttp.responseText;
	if (result == 'logged in'){
		window.location.href = dir + "/";
	}else{
		showSigninFrame();
	}
}