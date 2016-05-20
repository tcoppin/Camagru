<?php
	if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
		$_SESSION['error'] = "Vous devez être connecter pour accéder à cette page.";
		echo '<meta http-equiv="Refresh" content="0; URL=index.php?pg=connect" />';
	}
?>
<div class="ca_infoBloc ca_errorBloc ca_color_white" style="display: none;">
	<span id="ca_text"></span>
	<span class="ca_close ca_color_orange" id="closeErrorBloc">X</span>
</div>
<div class="ca_container ca_bloc_left">
	<div class="ca_content_dev">
		<video class="ca_video_cam"></video>
		<img src="" id="uploadPicturePvw" style="display: none;">
		<span class="ca_abort_pic"></span>
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
	<input type="file" style="display: none;" />
	<div class="ca_content_aper">
		<canvas style="display: none;" id="pictureToSend"></canvas>
		<canvas style="display: none;" id="overPictureAper"></canvas>
	</div>
	<input type="text" id="namePicture" class="ca_margin_top_5 ca_collapse" style="display: none;" name="name" placeholder="Nom de la photo (optionnel)" maxlength="255" required />
	<button type="submit" name="button" id="validPicture" class="ca_margin_top_5 ca_collapse" style="display: none;">Valider</button>
	<div style="clear: both;"></div>
</div>

<div class="ca_container ca_bloc_right">
	<ul class="ca_list_pictures">
	<?php
		$sql = 'SELECT `id_picture`, `name_picture`, `file_name` FROM ca_pictures WHERE id_user = "'.$_SESSION['id_user'].'"';
		$rtn = $db->selectInDb($sql);
		foreach ($rtn as $key => $value) {
			echo "<li data-id=\"".$value['id_picture']."\">
					<span class=\"ca_del_pic\"></span>
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

	var delPicList = document.getElementsByClassName('ca_del_pic');
	for (var i = 0; i < delPicList.length; i++) {
		delPicList[i].addEventListener('click', function(e) {
			var target = e.target || e.srcElement;
			var oReq = new XMLHttpRequest();
			console.log(target.parentNode);
			var postData = "idPost=" + target.parentNode.dataset.id;
			oReq.open("POST", "<?= ADDR_HOST ?>/treatement/del_picture.php", true);
			oReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			oReq.send(postData);
			oReq.onreadystatechange = function() {
				if (oReq.readyState == 4 && oReq.status == 200) {
					var response = JSON.parse(oReq.responseText);
					console.log(response);
					if (response.code == "900") {
						document.querySelector('.ca_list_pictures').removeChild(target.parentNode);
						alertMessage.querySelector('#ca_text').innerText = response.message;
						alertMessage.style.display = "block";
					} else {
						//cache popIn et affiche message d'erreur
					}
				}
			}
		}, false);
	}
	
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
	var listOP = document.querySelector('.ca_cnt_listOP');
	var cntOverPictList = document.querySelector('.ca_listOverPictures');
	var overPicToSend = document.querySelector('#overPictureAper');
	var overPicToSendCtx = overPicToSend.getContext('2d');

	// Vidéo et image a envoyer
	var video = document.querySelector('video');
	var imgUserData = null;
	var imgToSend = document.querySelector('#pictureToSend');
	var imgToSendCtx = imgToSend.getContext('2d');
	var widthImgToSend = 0, heightImgToSend = 0;
	var imgToPreview = video;
	
	// Boutons
	var namePictureIpt = document.getElementById('namePicture');
	var validPicture = document.getElementById('validPicture');
	var takePictureBtn = document.getElementById('takePicture');
	var abortPicture = document.querySelector('.ca_abort_pic');
	
	// Upload Image
	var uploadPictureIpt = document.querySelector('input[type=file]');
	var uploadPictureBtn = document.querySelector('#uploadPicture');
	var uploadPicturePvw = document.querySelector('#uploadPicturePvw');

	function getFile() {
		uploadPictureIpt.click();
		uploadPicturePvw.style.width = video.clientWidth + "px";
		uploadPicturePvw.style.height = video.clientHeight + "px";
		uploadPicturePvw.value = uploadPictureIpt.value;
		uploadPicturePvw.style.display = "inline-block";
	}

	function changeSize() {
		uploadPicturePvw.style.width = video.clientWidth + "px";
		uploadPicturePvw.style.height = video.clientHeight + "px";
	}
	window.addEventListener('resize', changeSize);

	function previewFile() {
		var file = uploadPictureIpt.files[0];
		var reader = new FileReader();
		
		if (file) {
			var extension = file.name.substring(file.name.lastIndexOf('.') + 1).toLowerCase();
			if (extension !== "png" && extension !== "jpg" && extension !== "jpeg") {
				alertMessage.querySelector('#ca_text').innerText = "Fichier invalide. Fichiers accepter : '.png', '.jpg', '.jpeg'";
				alertMessage.style.display = "block";
				return ;
			}
		}


		reader.onloadend = function () {
			uploadPicturePvw.src = reader.result;
		}

		if (file) {
			reader.readAsDataURL(file);
		} else {
			uploadPicturePvw.src = "";
		}
	}

	previewFile();
	uploadPictureBtn.addEventListener('click', getFile);
	uploadPictureIpt.addEventListener('change', previewFile);

	function clearPicture() {
		overPicture.style.display = 'none';
		uploadPicturePvw.style.display = "none";
		uploadPicturePvw.src = "";
		addClass(takePictureBtn, 'ca_no_active');
	}

	abortPicture.addEventListener('click', clearPicture);

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
	navigator.getUserMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.mediaDevices.getUserMedia);
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
					if (uploadPicturePvw.style.display == "inline-block")
						imgToPreview = uploadPicturePvw;
					widthImgToSend = imgToPreview.offsetWidth;
					heightImgToSend = imgToPreview.offsetHeight;
					imgToSend.setAttribute('width', widthImgToSend);
					imgToSend.setAttribute('height', heightImgToSend);
					if (localMediaStream) {
						imgToSendCtx.drawImage(imgToPreview, 0, 0, widthImgToSend, heightImgToSend);
						imgUserData = imgToSend.toDataURL();
						imgToSend.style.display = "inline";
						overPicToSend.setAttribute('width', widthOP);
						overPicToSend.setAttribute('height', heightOP);
						// topOP = getPositionTop(overPicture) - getPositionTop(imgToPreview);
						// leftOP = getPositionLeft(overPicture) - getPositionLeft(imgToPreview);
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
						oReq.open("POST", "<?= ADDR_HOST ?>/pictures/new_image.php", true);
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