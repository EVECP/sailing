/**
 *切换link和text标签
 */
document.getElementById("new-link").onclick = function(){
	document.getElementById("spacer-link").style.display = "block";
	document.getElementById("spacer-text").style.display = "none";
	document.getElementById("new-link").className = "tab-active";
	document.getElementById("new-text").className = "";
	document.getElementById("menu-type").value = "link";
}
document.getElementById("new-text").onclick = function(){
	document.getElementById("spacer-link").style.display = "none";
	document.getElementById("spacer-text").style.display = "block";
	document.getElementById("new-text").className = "tab-active";
	document.getElementById("new-link").className = "";
	document.getElementById("menu-type").value = "text";
}
/**
 *end
 */

/**
 *选择看板
 */
var panelsArr = new Array();

function getPanelArr(userId){
	var xmlhttp = getXmlhttp();
	xmlhttp.open("GET", "../../../script/submit/get_panel_arr.php?user_id=" + userId, false);
	xmlhttp.send();
	var panelsJson = xmlhttp.responseText;
	panelsArr = eval("(" + panelsJson + ")");
	for (i in panelsArr){
		document.getElementById("panels").innerHTML += ("<a href=\"javascript:void(0);\" onclick=\"selectPanel(" + panelsArr[i]["id"] + ");\">" + panelsArr[i]["display_name"] + "</a>&nbsp;");
	}
}

function selectPanel(id){
	for (i in panelsArr){
		if (panelsArr[i]["id"] == id){
			document.getElementById("panel-name2").value = panelsArr[i]["display_name"];
			document.getElementById("panel-id").value = panelsArr[i]["id"];
		}
	}
}
/**
 *end
 */
 
function sub(){
	var form = document.getElementById("form-new");
	var sendreplies = 0;
	if (document.getElementById("sendreplies").checked == true){
		sendreplies = 1;
	}
	var xmlhttp = getXmlhttp();
	xmlhttp.open("GET", "../../../script/submit/submit_subject.php?user_id=1&menu_type=" + form.menu_type.value
		+ "&title=" + form.title.value
		+ "&text=" + form.text.value
		+ "&link=" + form.link.value
		+ "&sendreplies=" + sendreplies
		+ "&panel_id=" + form.panel_id.value, false)
	xmlhttp.send();
	var result = xmlhttp.responseText;
	window.location.href = "../comments/subject?subject=" + result;
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