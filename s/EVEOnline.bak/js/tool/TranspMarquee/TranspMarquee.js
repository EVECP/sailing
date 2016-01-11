var marqueeId = "";
var makeDivsCount = 0;
var t;

function initMarquee(attrId, jsonFile){
	marqueeId = attrId;
	var marquee = document.getElementById(attrId);
	marquee.style.display = "inline-block";
	marquee.style.verticalAlign = "top";
	marquee.style.width = "100%";
	marquee.onmouseover = function(){
			clearTimeout(t);
		};
	marquee.onmouseout = function(){
			go();
		}
		
	marqueeBar = document.getElementById(attrId);
	marqueeBar.style.whiteSpace = "nowrap";
	marqueeBar.style.boxSizing = "padding-box";
	marqueeBar.style.position = "relative";
	marqueeBar.style.overflow = "hidden";
	
	getJson(jsonFile);
}

function getJson(file){
	var xmlhttp = getXmlhttp();
	xmlhttp.onreadystatechange = function(){
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
			var result = xmlhttp.responseText;
			settleDown(result);
		}
	}
	xmlhttp.open("GET", "js/tool/TranspMarquee/get_json.php?file=" + file, true);
	xmlhttp.send();
}

function settleDown(jsonStr){
	var div0 = document.createElement("div");
	div0.setAttribute("id", "marquee-0")
	document.getElementById(marqueeId).appendChild(div0);
	
	var arr = eval("(" + jsonStr + ")");
	makeDivs(arr, "marquee-0", 0);
	
	var marquee0 = document.getElementById("marquee-0");
	marquee0.style.whiteSpace = "nowrap";
	marquee0.style.display= "inline-block";
	marquee0.style.boxSizing = "margin-box";
	marquee0.style.position = "absolute";
	
	var width0 = document.getElementById("marquee-0").offsetWidth;
	var widthMarquee = document.getElementById(marqueeId).offsetWidth;
	makeDivsCount = (Math.ceil(widthMarquee / width0) + 1) * 2;
	
	for (var i = 0; i < makeDivsCount - 1; i++){
		var divObj = document.createElement("div");
		divObj.setAttribute("id", "marquee-" + (i + 1));
		document.getElementById(marqueeId).appendChild(divObj);
		
		var marqueeX = document.getElementById("marquee-" + (i + 1))
		marqueeX.style.whiteSpace = "nowrap";
		marqueeX.style.display = "inline-block"
		marqueeX.style.boxSizing = "margin-box";
		marqueeX.style.position = "absolute";
		
		initParams();
		makeDivs(arr, "marquee-" + (i + 1), (i + 1));
	}
	initDivs(makeDivsCount);
	setTimeout("go()", 2000);
}

function initParams(){
	level = 0;
	divId = "";
	levelArr = new Array();
}

var level = 0;
var divId = "";
var levelArr = new Array();

function makeDivs(arr, attrId, marqueeIndex){
	for (var i = 0; i < arr.length; i++){
		var divObj = document.createElement("div");
		divObj.style.whiteSpace = "nowrap";
		divObj.style.display = "inline-block";
		
		levelArr = divId.split("-");
		
		if (0 == level){
			document.getElementById(attrId).appendChild(divObj);
		}else{
			if (level < levelArr.length){
				for (var j = 0; j < level; j++){
					divId = 0 == j ? levelArr[0] : divId;
					divId += 0 == j ? "" : "-" + levelArr[j];
				}
			}
			document.getElementById(divId).appendChild(divObj);
		}
		
		if (0 == level){
			divId = marqueeIndex + "s" + i;
		}else{
			if (level < levelArr.length){
				for (var j = 0; j < level; j++){
					divId = 0 == j ? levelArr[0] : divId;
					divId += 0 == j ? "" : "-" + levelArr[j];
				}
			}
			divId += "-" + i;
		}
		divObj.setAttribute("id", divId);
		if (0 != level){
			divObj.setAttribute("class", "m-" + i);
		}else{
			divObj.setAttribute("class", "m");
		}
		
		if (arr[i] instanceof Array){
			level++;
			makeDivs(arr[i], attrId, marqueeIndex);
		}else{
			divObj.innerHTML = arr[i];
		}
		
		if (arr.length - 1 == i){
			divObj.className += " rise";
			level--;
		}
	}
}

function initDivs(count){
	var width = document.getElementById("marquee-0").offsetWidth;
	for (var i = 0; i < count; i += 2){
		document.getElementById("marquee-" + i).style.marginLeft = width * (i / 2) + "px";
		if (i % 2 == 0){
			document.getElementById("marquee-" + i).className += "opacity1";
		}
		document.getElementById("marquee-" + (i + 1)).style.marginLeft = width * (i / 2) + "px";
	}
	var height0 = document.getElementById("marquee-0").offsetHeight;
	document.getElementById(marqueeId).style.height = height0 + "px";
}

function go(){
	var width0 = document.getElementById("marquee-0").offsetWidth;
	for (var i = 0; i < makeDivsCount; i++){
		var marginLeft = document.getElementById("marquee-" + i).style.marginLeft;
		var val = marginLeft.substr(0, (marginLeft.length - 2));
		var newPx = (Number(val) -1) + "px";
		document.getElementById("marquee-" + i).style.marginLeft = newPx;
		
		var leftSpace = document.getElementById("marquee-" + i).offsetLeft;
		if ((leftSpace + width0) == 0){
			document.getElementById("marquee-" + i).style.marginLeft = width0 * (makeDivsCount / 2 - 1) + "px";
		}
	}
	t = setTimeout("go()", 100);
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