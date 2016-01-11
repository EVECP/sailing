function upSubject(userId, subjectId){
	if (userId > 0){
		var arrowUpObj = document.getElementById("up-subject" + subjectId);
		arrowUpObj.className += " upvoted";
		arrowUpObj.onclick = function(){
			cancelUpSubject(userId, subjectId);
		}
		cancelDownSubject(userId, subjectId);
		showVote('likes', subjectId);
		voteSubject(userId, subjectId, "up");
	}
}

function cancelUpSubject(userId, subjectId){
	var arrowObj = document.getElementById("up-subject" + subjectId);
	arrowObj.className = "arrow up";
	arrowObj.onclick = function(){
		upSubject(userId, subjectId);
	};
	showVote('unvoted', subjectId);
	unvoteSubject(userId, subjectId);
}

function downSubject(userId, subjectId){
	if (userId > 0){
		var arrowDownObj = document.getElementById("down-subject" + subjectId);
		arrowDownObj.className += " downvoted";
		arrowDownObj.onclick = function(){
			cancelDownSubject(userId, subjectId);
		};
		cancelUpSubject(userId,subjectId);
		showVote('dislikes', subjectId);
		voteSubject(userId, subjectId, "down");
	}
}

function cancelDownSubject(userId, subjectId){
	var arrowObj = document.getElementById("down-subject" + subjectId);
	arrowObj.className = "arrow down";
	arrowObj.onclick = function(){
		downSubject(userId, subjectId);
	}
	showVote('unvoted', subjectId);
	unvoteSubject(userId, subjectId);
}

function showVote(vote, subjectId){
	var dislikes = document.getElementById("dislikes" + subjectId);
	var unvoted = document.getElementById("unvoted" + subjectId);
	var likes = document.getElementById("likes" + subjectId);
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
	xmlhttp.open("GET", "../script/comments/add_subject_vote.php?user_id=" + userId + "&subject_id=" + subjectId + "&direct=" + direct, false);
	xmlhttp.send();
	var result = xmlhttp.responseText;
	//some code... if result < 0
}

function unvoteSubject(userId, subjectId){
	var xmlhttp = getXmlhttp();
	xmlhttp.open("GET", "../script/comments/del_subject_vote.php?user_id=" + userId + "&subject_id=" + subjectId, false);
	xmlhttp.send();
	var result = xmlhttp.responseText;
	//some code... if result < 0
}