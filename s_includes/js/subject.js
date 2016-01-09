function subComment(subjectId, pid){
	var replyContext = document.getElementById("reply" + pid).value;
	if (replyContext != ''){
		var xmlhttp = getXmlhttp();
		xmlhttp.open("GET", "../../../../script/comments/sub_comment.php?subject_id=" + subjectId + "&reply_context=" + replyContext + "&pid=" + pid, false);
		xmlhttp.send();
		var result = xmlhttp.responseText;
		if (result == 1){
			window.location.href = "";
		}
	}
}

function showReply(commentId){
	document.getElementById("c-reply" + commentId).style.display = "block";
}

function cancelReply(commentId){
	document.getElementById("c-reply" + commentId).style.display = "none";
}

function upSubject(userId, subjectId){
	if (userId > 0){
		var arrowUpObj = document.getElementById("up-subject");
		arrowUpObj.className += " upvoted";
		arrowUpObj.onclick = function(){
			cancelUpSubject(userId, subjectId);
		}
		cancelDownSubject(userId, subjectId);
		showVote('likes');
		voteSubject(userId, subjectId, "up");
	}
}

function cancelUpSubject(userId, subjectId){
	var arrowObj = document.getElementById("up-subject");
	arrowObj.className = "arrow up";
	arrowObj.onclick = function(){
		upSubject(userId, subjectId);
	};
	showVote('unvoted');
	unvoteSubject(userId, subjectId);
}

function downSubject(userId, subjectId){
	if (userId > 0){
		var arrowDownObj = document.getElementById("down-subject");
		arrowDownObj.className += " downvoted";
		arrowDownObj.onclick = function(){
			cancelDownSubject(userId, subjectId);
		};
		cancelUpSubject(userId,subjectId);
		showVote('dislikes');
		voteSubject(userId, subjectId, "down");
	}
}

function cancelDownSubject(userId, subjectId){
	var arrowObj = document.getElementById("down-subject");
	arrowObj.className = "arrow down";
	arrowObj.onclick = function(){
		downSubject(userId, subjectId);
	}
	showVote('unvoted');
	unvoteSubject(userId, subjectId);
}

function showVote(vote){
	var dislikes = document.getElementById("dislikes");
	var unvoted = document.getElementById("unvoted");
	var likes = document.getElementById("likes");
	dislikes.setAttribute("class", "score");
	unvoted.setAttribute("class", "score");
	likes.setAttribute("class", "score");
	if (vote == 'dislikes'){
		dislikes.className += " active";
	}else if (vote == 'unvoted'){
		unvoted.className += " active";
	}else if (vote == 'likes'){
		likes.className += " active";
	}
}

function voteSubject(userId, subjectId, direct){
	var xmlhttp = getXmlhttp();
	xmlhttp.open("GET", "../../../../script/comments/add_subject_vote.php?user_id=" + userId + "&subject_id=" + subjectId + "&direct=" + direct, false);
	xmlhttp.send();
	var result = xmlhttp.responseText;
	//some code... if result < 0
}

function unvoteSubject(userId, subjectId){
	var xmlhttp = getXmlhttp();
	xmlhttp.open("GET", "../../../../script/comments/del_subject_vote.php?user_id=" + userId + "&subject_id=" + subjectId, false);
	xmlhttp.send();
	var result = xmlhttp.responseText;
	//some code... if result < 0
}