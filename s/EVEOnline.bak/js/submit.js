function checkTitle(title){
	//检查必填项title是否填写
	return true;
}

function sub(selftext){
	var formObj = document.forms["newlink"];
	var title = formObj.title.value;
	var text = formObj.text.value;
	var sendreplied = true;
	if (!document.getElementById("sendreplies").checked){
		sendreplied = false;
	}
	checkTitle(title);
	var xmlhttp = getXmlhttp();
	xmlhttp.open("POST", "../post/submit/", false);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("selftext=" + selftext + "&title=" + title + "&text=" + text + "&sendreplied=" + sendreplied);
	var result = xmlhttp.responseText;
	if (!isNaN(result) && result > 0){
		document.location.href="../comments/?subject=" + result;
	}
	return false;
}

function getXmlhttp(){
	var xmlhttp;
	if (window.XMLHttpRequest){
		xmlhttp = new XMLHttpRequest();
	}else{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
}