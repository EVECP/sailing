window.onload = function(){
	bgsize();
};
window.onresize = function(){
	bgsize();
};

function bgsize(){
	var bodyWidth = document.body.offsetWidth;
	if (bodyWidth > 1803){
		document.getElementById("c-header-copy").style.backgroundSize = bodyWidth + "px auto";
	}
}