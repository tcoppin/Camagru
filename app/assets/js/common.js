function addClass(ele,className){
	try
	{
		if(ele.className.indexOf(className) < 0){
			ele.className += " "+className;
		}
	}
	catch(ex){errlog(ex);}
}

function removeClass(ele,className){
	try
	{
		var indexStart = ele.className.indexOf(className)
		if(indexStart >= 0){
			ele.className = ele.className.substring(0,indexStart)+ele.className.substring(indexStart+className.length);
		}
	}
	catch(ex){errlog(ex);}
}

function getPositionLeft (obj) {
	var curleft = 0;
	if (obj.offsetParent) {
		curleft = obj.offsetLeft;
		while (obj = obj.offsetParent) {curleft += obj.offsetLeft;}
	}
	return curleft;
}

function getPositionTop (obj) {
	var curtop = 0;
	if (obj.offsetParent) {
		curtop = obj.offsetTop;
		while (obj = obj.offsetParent) {curtop += obj.offsetTop;}
	}
	return curtop;
}

function getPostInfo(popIn) {
	var oReq = new XMLHttpRequest();
	var postData = "idPost=" + popIn.dataset.id;
	oReq.open("POST", "http://localhost:8080/camagru/treatement/getPostInfo.php", true);
	oReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	oReq.send(postData);
	oReq.onreadystatechange = function() {
		if (oReq.readyState == 4 && oReq.status == 200) {
			var response = JSON.parse(oReq.responseText);
			console.log(response);
			if (response.code == "900") {
				if (response.likeOrNot == 1)
					addClass(likeBtn, 'ca_like');
				else
					removeClass(likeBtn, 'ca_like');
				popIn.querySelector('#nbLike').innerText = response.nbLike;
				popIn.querySelector('#nbComment').innerText = response.nbComment;
				for (var i = 0; i < response.comment.length; i++) {
					popIn.querySelector('.ca_popIn_comment').innerHTML += "<li>" + JSON.parse(response.comment[i]).login + "</li>";
				}
			}
		}
	}
}

function showPopIn(e) {
	var overSite = document.querySelector('.ca_overSite');
	var popIn = document.querySelector('#popIn');
	var parent = e.target.parentNode;

	overSite.style.display = 'block';
	popIn.style.display = 'block';
	if (parent.classList.contains('ca_img_gallery')) {
		popIn.querySelector('.ca_color_blue').innerText = parent.querySelector('span').innerHTML;
		popIn.querySelector('.ca_img_popIn').src = parent.querySelector('img').src;
		popIn.dataset.id = parent.querySelector('img').dataset.name;
		getPostInfo(popIn);
	} else
		hidePopIn();
}

function hidePopIn() {
	var overSite = document.querySelector('.ca_overSite');
	var popIn = document.querySelector('#popIn');

	overSite.style.display = 'none';
	popIn.style.display = 'none';
}