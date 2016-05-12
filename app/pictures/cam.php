<?php
	if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
		$_SESSION['error'] = "Vous devez être connecter pour accéder à cette page.";
		echo '<meta http-equiv="Refresh" content="0; URL=index.php?pg=connect" />';
	}
?>
<style type="text/css">
	.ca_bloc_left {
		float: left;
		width: 66%;
		box-sizing: border-box;
		margin-right: 1%;
	}
	.ca_bloc_right {
		float: left;
		width: 26%;
		box-sizing: border-box;
		margin-left: 1%;
		overflow: auto;
	}
	.ca_list_pictures li {
		position: relative;
		text-align: left;
		cursor: pointer;
	}
	.ca_list_pictures li img {
		width: 100%;
		margin-top: 10px;
	}
	.ca_list_pictures li:first-of-type img {
		margin-top: 0;
	}
	.ca_list_pictures li .ca_namePicture {
		position: absolute;
		bottom: 10px;
		padding: 10px;
		left: 10%;
		right: 10%;
		text-align: center;
		max-width: 80%;
		max-height: 50%;
		overflow: hidden;
		display: inline-block;
		border-radius: 8px;
		background-color: rgba(255, 255, 255, 0.8);
		opacity: 0;
		transition: all 0.8s;
		word-wrap: break-word;
	}

	.ca_list_pictures li:hover .ca_namePicture {
		opacity: 1;
	}
	.ca_bloc_right::-webkit-scrollbar {
	    width: 12px;
	}
	 
	.ca_bloc_right::-webkit-scrollbar-track {
		border: 1px solid #E4E3E3;
	    border-radius: 10px;
	}
	
	.ca_bloc_right::-webkit-scrollbar-thumb {
	    border-radius: 10px;
	    background-color: rgb(64,116,164);
	    -webkit-box-shadow: inset 0 0 6px rgba(64,116,164,0.5); 
	}
	
	.ca_content_dev, .ca_content_aper {
		position: relative;
	}

	.ca_listOverPictures {
		position: absolute;
		width: 100%;
		text-align: center;
		bottom: 10px;
	}

	.ca_listOverPictures li {
		float: left;
		padding: 5px;
		margin-left: 10px;
		cursor: pointer;
	}

	.ca_listOverPictures li:hover {
		background-color: rgba(255, 255, 255, 0.8);
	}

	.ca_listOverPictures li img {
		height: 50px;
	}

	#overPicture, #overPictureAper {
		position: absolute;
		top: 0;
	}

	.ca_video_cam {
		width: 100%;
		position: relative;
	}
	.ca_width_50 {
		width: calc(50% - 3px) !important;
	}
	.ca_margin_top_5 {
		margin-top: 5px;
	}

	@media screen and (max-width: 700px) {
		.ca_bloc_left {
			float: none;
			width: 94%;
			margin-right: 0%;
		}
		.ca_bloc_right {
			float: none;
			width: 94%;
			margin-left: 3%;
		}
		.ca_list_pictures li {
			width: 48%;
			margin: 1%;
			display: inline-block;
		}
		.ca_list_pictures div {
			display: inline-block !important;
		}
	}
</style>
<div class="ca_infoBloc ca_errorBloc ca_color_white" style="display: none;">
	<span id="ca_text"></span>
	<span class="ca_close ca_color_orange" id="closeErrorBloc">X</span>
</div>
<div class="ca_container ca_bloc_left">
	<div class="ca_content_dev">
		<video class="ca_video_cam"></video>
		<canvas style="display: none;" id="overPicture"></canvas>
		<ul class="ca_listOverPictures">
			<?php
				$sql = 'SELECT * FROM ca_overPictures ORDER BY id_overPicture';
				$rtn = $db->selectInDb($sql);
				foreach ($rtn as $key => $value) {
					echo "<li>
							<img id=\"".$value['id_overPicture']."\" src=\"".ADDR_HOST."/content/overPicture/".$value['file_name']."\" />
						</li>";
				}
			?>
			<div style="display: none; clear: both;"></div>
		</ul>
	</div>
	<button type="submit" name="button" id="takePicture" class="ca_width_50 ca_margin_top_5 ca_no_active">Prendre une photo</button>
	<button type="submit" name="button" id="uploadPicture" class="ca_width_50 ca_margin_top_5">Uploader une photo</button>
	<div class="ca_content_aper">
		<canvas style="display: none;" id="pictureToSend"></canvas>
		<canvas style="display: none;" id="overPictureAper"></canvas>
	</div>
	<input type="text" id="namePicture" class="ca_margin_top_5 ca_collapse" style="display: none;" name="name" placeholder="Nom de la photo (optionnel)" maxlength="255" required />
	<button type="submit" name="button" id="validPicture" class="ca_margin_top_5 ca_collapse" style="display: none;">Valider</button>
</div>

<div class="ca_container ca_bloc_right">
	<ul class="ca_list_pictures">
	<?php
		$sql = 'SELECT `name_picture`, `file_name` FROM ca_pictures WHERE id_user = "'.$_SESSION['id_user'].'"';
		$rtn = $db->selectInDb($sql);
		foreach ($rtn as $key => $value) {
			echo "<li>
					<img src=\"".ADDR_HOST."/content/tmp/".$value['file_name'].".png\" />
					<span class=\"ca_namePicture ca_color_blue\">".$value['name_picture']."</span>
				</li>";
		}
	?>
	</ul>
</div>

<script type="text/javascript">
	var alertMessage = document.querySelector('.ca_errorBloc');
	function closeError() {
		alertMessage.style.display = 'none';
	}
	document.getElementById('closeErrorBloc').addEventListener('click', closeError, false);
	
	// Bloc de droite
	var blocRight = document.querySelector('.ca_bloc_right');
	var listOldPicture = document.querySelector('.ca_list_pictures');

	// définition de la hauteur max
	function maxHeightBlocRight() {
		if (!listOldPicture.querySelector('li')) {
			listOldPicture.innerHTML = "<p>Aucune photo</p>";
			return ;
		}
		var blocRightHeight = listOldPicture.querySelector('li').clientHeight;
		blocRight.style.maxHeight = (blocRightHeight * 5) + "px";
	}
	window.addEventListener('load', maxHeightBlocRight);

	// Photos superposées
	var overPictureId = 0;
	var overPicture = document.querySelector('#overPicture');
	var overPictureCtx = overPicture.getContext('2d');
	var widthOP = 0, heightOP = 0, topOP = 0, leftOP = 0;
	var cntOverPictList = document.querySelector('.ca_listOverPictures');
	var overPicToSend = document.querySelector('#overPictureAper');
	var overPicToSendCtx = overPicToSend.getContext('2d');

	// Vidéo et image a envoyer
	var video = document.querySelector('video');
	var imgUserData = null;
	var imgToSend = document.querySelector('#pictureToSend');
	var imgToSendCtx = imgToSend.getContext('2d');
	var widthImgToSend = 0, heightImgToSend = 0;
	
	// Boutons
	var namePictureIpt = document.getElementById('namePicture');
	var validPicture = document.getElementById('validPicture');
	var takePictureBtn = document.getElementById('takePicture');

	// Superposition de la photo sur la vidéo
	function overPictureFt(e) {
		var target = e.target || e.srcElement;
		if (target.tagName == "li")
			target = target.querySelector('img');
		overPictureId = target.getAttribute('id');
		widthOP = target.clientWidth * 5 ;
		heightOP = target.clientHeight * 5;
		overPicture.setAttribute('width', widthOP);
		overPicture.setAttribute('height', heightOP);
		overPictureCtx.drawImage(target, 0, 0, widthOP, heightOP);
		overPicture.style.display = 'inline-block';
		removeClass(takePictureBtn, 'ca_no_active');
	}
	// Bind le click sur la liste des images superposées
	var listOverPicture = cntOverPictList.getElementsByTagName('li');
	for (var i = 0; i < listOverPicture.length; i++) {
		listOverPicture[i].addEventListener('click', overPictureFt);
	}

	// Init vidéo
	navigator.getUserMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
	if (navigator.getUserMedia) {
		navigator.getUserMedia (

			{
				video: true,
				audio: false
			},

			function(localMediaStream) {
				video.src = window.URL.createObjectURL(localMediaStream);
				video.play();

				// Affichage de tout les block a la prose de la photo
				function takePicture() {
					if (takePictureBtn.classList.contains('ca_no_active'))
						return ;
					widthImgToSend = video.offsetWidth;
					heightImgToSend = video.offsetHeight;
					imgToSend.setAttribute('width', widthImgToSend);
					imgToSend.setAttribute('height', heightImgToSend);
					if (localMediaStream) {
						imgToSendCtx.drawImage(video, 0, 0, widthImgToSend, heightImgToSend);
						imgUserData = imgToSend.toDataURL();
						imgToSend.style.display = "inline";
						overPicToSend.setAttribute('width', widthOP);
						overPicToSend.setAttribute('height', heightOP);
						// topOP = getPositionTop(overPicture) - getPositionTop(video);
						// leftOP = getPositionLeft(overPicture) - getPositionLeft(video);
						// console.log(getPositionTop(imgToSend));
						// overPicToSend.style.top = getPositionTop(imgToSend) + topOP + "px";
						// overPicToSend.style.left = getPositionLeft(imgToSend) + leftOP + "px";
						overPicToSendCtx.drawImage(overPicture, 0, 0, widthOP, heightOP);
						overPicToSend.style.display = "inline";
						namePictureIpt.style.display = "inline";
						validPicture.style.display = "inline";
					}
				}

				// Envoye de la photo
				function getImage() {
					if (imgToSend.style.display == "inline") {
						var namePicture = namePictureIpt.value;
						if (!namePicture)
							namePicture = 'Camagru - ' + Math.round(Math.random()*100);
						var oReq = new XMLHttpRequest();
						var postData = "imgUser=" + imgUserData + "&name=" + namePicture + "&overPicture=" + overPictureId + "&widthOP=" + parseInt(widthOP) + "&heightOP=" + parseInt(heightOP) + "&idOP=" + overPictureId;
						oReq.open("POST", "http://localhost:8080/camagru/pictures/new_image.php", true);
						oReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						oReq.send(postData);
						oReq.onreadystatechange = function() {
					 		if (oReq.readyState == 4 && oReq.status == 200) {
								var response = JSON.parse(oReq.responseText);
								if (response.code == 900) {
									addClass(alertMessage, 'ca_border_blue');
									if (!listOldPicture.querySelector('li'))
										listOldPicture.innerHTML = "";
									listOldPicture.innerHTML += response.new_image;
								} else if (response.code == 901) {
									addClass(alertMessage, 'ca_border_orange');
								}
								alertMessage.querySelector('#ca_text').innerText = response.message;
								alertMessage.style.display = "block";
								imgToSend.style.display = namePictureIpt.style.display = overPicToSend.style.display = validPicture.style.display = "none";
								namePictureIpt.value = "";
							}
						}
					}
				}
				takePictureBtn.addEventListener('click', takePicture, false);
				validPicture.addEventListener('click', getImage, false);
			},
			function(err) {
				console.log("The following error occured: " + err);
			}
		);
	} else {
		console.log("getUserMedia not supported");
	}
</script>