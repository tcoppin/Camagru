<style type="text/css">
	.ca_container button.ca_collapse.right {
		border-top-right-radius: 0;
		border-bottom-right-radius: 0;
		border-top-left-radius: 10px;
		border-bottom-left-radius: 10px;
		width: 10%;
	}
	.ca_collapse.ca_like {
		background-color: #FFFFFF;
		color: #4074A4;
	}
	.ca_collapse.ca_like:hover {
		color: #FFFFFF;
		background-color: #BE2F37;
	}
	.ca_popIn_option {
		margin-top: 10px;
	}
	.ca_popIn_comment {
		list-style-type: none;
	}
	.ca_no_radius {border-radius: 0 !important;}
	.ca_width_70 {width: 70% !important;}

	.ca_cnt_page {
		width: 100%;
		text-align: center;
	}
	.ca_page {
		overflow: hidden;
		display: inline-block;
	}
	.ca_page li {
		cursor: pointer;
		float: left;
		padding: 5px 10px;
		border-radius: 10px;
		border: 1px solid #E3E4E4;
		background-color: #FFFFFF;
		color: #4074A4;
		margin: 5px;
	}
	.ca_page li:hover, .ca_page li.active {
		border: 1px solid #E3E4E4;
		background-color: #4074A4;
		color: #FFFFFF;
	}
	.ca_page li.inactive {
		border: 1px solid #808285;
		background-color: #E4E3E3;
		cursor: auto;
		color: #808285;	
	}
	.ca_nbPost {
		font-style: italic;
		font-size: 12px;
	}
	.ca_popIn_comment {
		margin-top: 10px;
		width: 100%;
		max-height: 250px;
		overflow: auto;
	}
	.ca_popIn_comment li {
		border-bottom: 1px solid #E4E3E3;
		padding: 5px 0;
		padding-right: 10px;
	}
	.ca_popIn_comment li .ca_comm_login {
		float: left;
	}
	.ca_popIn_comment li .ca_comm_date {
		float: right;
		font-size: 12px;
		font-style: italic;
	}
	.ca_popIn_comment li .ca_comm_content {
		word-wrap: break-word;
		font-size: 14px;
	}
</style>
<div class="ca_infoBloc ca_errorBloc ca_color_white" style="display: none;">
	<span id="ca_text"></span>
	<span class="ca_close ca_color_orange" id="closeErrorBloc">X</span>
</div>
<div class="ca_container ca_gallery">
	<h1 class="ca_color_blue ca_no_margin">Galerie</h1>
	<ul class="ca_list_pictures">
	<?php
		$sql = 'SELECT COUNT(*) FROM `ca_pictures`';
		$rtn = $db->selectInDb($sql);
		$nbPic = $rtn[0][0];
		$sql = 'SELECT `id_picture`, `name_picture`, `file_name` FROM ca_pictures LIMIT 0, 6';
		$rtn = $db->selectInDb($sql);
		foreach ($rtn as $key => $value) {
			echo "<li class=\"ca_img_gallery\">
					<img src=\"".ADDR_HOST."/content/tmp/".$value['file_name'].".png\" data-name=\"".$value['id_picture']."\" />
					<span class=\"ca_namePicture ca_color_blue\">".$value['name_picture']."</span>
				</li>";
		}
	?>
	</ul>
	<div class="ca_cnt_page">
		<ul class="ca_page">
			<li class="inactive" id="prev"><</li>
			<li class="active" id="galleryPage_1">1</li>
			<?php
				$i = 2;
				while ($i <= ceil($nbPic / 6)) {
					echo "<li id=\"galleryPage_".$i."\">".$i."</li>";
					$i += 1;
				}
			?>
			<?php if ($nbPic < 6) { ?>
				<li class="inactive" id="next">></li>
			<?php } else { ?>
				<li id="next">></li>
			<?php } ?>
		</ul>
	</div>
</div>
<div class="ca_overSite" style="display: none;" onclick="hidePopIn();"></div>
<div class="ca_container ca_popIn" id="popIn" style="display: none;" data-id="">
	<span class="ca_abort_popIn" onclick="hidePopIn();"></span>
	<h2 class="ca_color_blue"></h2>
	<img class="ca_img_popIn" src="" />
	<?php
		if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
	?>
		<div class="ca_popIn_option">
			<button class="ca_collapse right" id="likeBtn">Like</button><!-- add ca_like -->
			<input type="text" class="ca_collapse ca_width_70 ca_no_radius" id="iptComment" placeholder="Commenter"></input>
			<button class="ca_collapse" id="commentBtn">Envoyer</button>
		</div>
		<script type="text/javascript">
			var alertMessage = document.querySelector('.ca_errorBloc');
			function closeError() {
				alertMessage.style.display = 'none';
			}
			document.getElementById('closeErrorBloc').addEventListener('click', closeError, false);

			var popIn = document.querySelector('#popIn');
			function addLike() {
				var likeBtn = document.getElementById('likeBtn');
				
				var oReq = new XMLHttpRequest();
				var postData = "idPost=" + popIn.dataset.id;
				oReq.open("POST", "<?= ADDR_HOST ?>/treatement/add_like.php", true);
				oReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				oReq.send(postData);
				oReq.onreadystatechange = function() {
					if (oReq.readyState == 4 && oReq.status == 200) {
						var response = JSON.parse(oReq.responseText);
						if (response.code == "900") {
							addClass(likeBtn, 'ca_like');
							getPostInfo(popIn);
						} else if (response.code == "910") {
							removeClass(likeBtn, "ca_like");
							getPostInfo(popIn);
						} else {
							hidePopIn();
							alertMessage.querySelector('#ca_text').innerText = response.message;
							alertMessage.style.display = "block";
						}
					}
				}
			}
			function addComment() {
				var commentBtn = document.getElementById('commentBtn');
				var iptComment = document.getElementById('iptComment');
				
				var oReq = new XMLHttpRequest();
				var postData = "idPost=" + popIn.dataset.id + "&comment=" + iptComment.value;
				oReq.open("POST", "<?= ADDR_HOST ?>/treatement/add_comment.php", true);
				oReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				oReq.send(postData);
				oReq.onreadystatechange = function() {
					if (oReq.readyState == 4 && oReq.status == 200) {
						var response = JSON.parse(oReq.responseText);
						console.log(response);
						if (response.code == "900") {
							iptComment.value = "";
							getPostInfo(popIn);
						} else if (response.code == "910") {
							getPostInfo(popIn);
						} else {
							console.log(response);
							//cache popIn et affiche message d'erreur
						}
					}
				}
			}
			document.getElementById('likeBtn').addEventListener('click', addLike);
			document.getElementById('commentBtn').addEventListener('click', addComment);
		</script>
	<?php } ?>
	<div class="ca_nbPost ca_color_fer"><span id="nbLike"></span> Like -- <span id="nbComment"></span> Commentaires</div>
	<ul class="ca_popIn_comment">
	</ul>
</div>

<script type="text/javascript">
	var currentPage = 1;
	var nbPage = <?= ceil($nbPic / 6) ?>;
	function changePage(e) {
		var target = e.target || e.srcElement;

		if (target.classList.contains('inactive'))
			return ;

		if (target.innerHTML == "&lt;" && !target.classList.contains('inactive')) {
			currentPage -= 1;
		} else if (target.innerHTML == "&gt;" && !target.classList.contains('inactive')) {
			currentPage += 1;
		} else if (!target.classList.contains('active') && target.innerHTML != "&lt;" && target.innerHTML != "&gt;") {
			currentPage = parseInt(target.innerHTML);
		}
		var oReq = new XMLHttpRequest();
		var postData = "newPage=" + currentPage;
		oReq.open("POST", "<?= ADDR_HOST ?>/pictures/new_page.php", true);
		oReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		oReq.send(postData);
		oReq.onreadystatechange = function() {
			if (oReq.readyState == 4 && oReq.status == 200) {
				var response = JSON.parse(oReq.responseText);
				var html = "";
				for (var i = 0; i < response.length; i++) {
					html += "<li class=\"ca_img_gallery\"><img src=\"<?= ADDR_HOST ?>/content/tmp/"+response[i]['file_name']+".png\" data-name=\""+response[i]['id_picture']+"\" /><span class=\"ca_namePicture ca_color_blue\">"+response[i]['name_picture']+"</span></li>";
				}
				document.querySelector('.ca_list_pictures').innerHTML = html;
				if (target.innerHTML != "&lt;" && target.innerHTML != "&gt;") {
					removeClass(document.querySelector('.active'), 'active');
					addClass(target, 'active');
				}
				if (currentPage != 1)
					removeClass(document.querySelector('#prev'), 'inactive');
				else
					addClass(document.querySelector('#prev'), 'inactive');
				if (currentPage == nbPage)
					addClass(document.querySelector('#next'), 'inactive');
				else
					removeClass(document.querySelector('#next'), 'inactive');
				if (target.innerHTML == "&lt;") {
					removeClass(document.querySelector('#galleryPage_' + (currentPage + 1)), 'active');
					addClass(document.querySelector('#galleryPage_' + currentPage), 'active');
				}
				if (target.innerHTML == "&gt;") {
					removeClass(document.querySelector('#galleryPage_' + (currentPage - 1)), 'active');
					addClass(document.querySelector('#galleryPage_' + currentPage), 'active');
				}
				bindGallery();
			}
		}
	}
	var listPage = document.querySelector('.ca_page').getElementsByTagName('li');
	for (var i = 0; i < listPage.length; i++) {
		listPage[i].addEventListener('click', changePage);
	}

	function bindGallery() {
		var listImg = document.querySelector('.ca_list_pictures').getElementsByTagName('li');
		for (var i = 0; i < listImg.length; i++) {
			listImg[i].addEventListener('click', showPopIn);
		}
	}
	bindGallery();
</script>