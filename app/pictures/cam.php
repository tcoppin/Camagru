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
	
	.ca_content_dev {
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

	.ca_video_cam {
		width: 100%;
	}
	.ca_width_50 {
		width: calc(50% - 3px) !important;
	}
	.ca_margin_top_5 {
		margin-top: 5px;
	}
</style>
<div class="ca_infoBloc ca_errorBloc ca_color_white" style="display: none;">
	<span id="ca_text"></span>
	<span class="ca_close ca_color_orange" id="closeErrorBloc">X</span>
</div>
<div class="ca_container ca_bloc_left">
	<div class="ca_content_dev">
		<video class="ca_video_cam"></video>
		<ul class="ca_listOverPictures">
			<?php
				$sql = 'SELECT * FROM ca_overPictures';
				$rtn = $db->selectInDb($sql);
				foreach ($rtn as $key => $value) {
					echo "<li>
							<img src=\"".ADDR_HOST."/content/overPicture/".$value['file_name']."\" />
						</li>";
				}
			?>
		</ul>
	</div>
	<button type="submit" name="button" id="takePicture" class="ca_width_50 ca_margin_top_5">Prendre une photo</button>
	<button type="submit" name="button" id="uploadPicture" class="ca_width_50 ca_margin_top_5">Uploader une photo</button>
	<canvas style="display: none;"></canvas>
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
	function closeError() {
		document.getElementsByClassName('ca_errorBloc')[0].style.display = 'none';
	}
	document.getElementById('closeErrorBloc').addEventListener('click', closeError, false);

	function maxHeightBlocRight() {
		if (!document.querySelector('.ca_list_pictures').querySelector('li')) {
			document.querySelector('.ca_list_pictures').innerHTML = "<p>Aucune photo</p>";
			return ;
		}
		var height = document.querySelector('.ca_list_pictures').querySelector('li').clientHeight;
		document.querySelector('.ca_bloc_right').style.maxHeight = (height * 5)+"px";
	}

	window.addEventListener('load', maxHeightBlocRight);

	navigator.getUserMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);

	if (navigator.getUserMedia) {
		navigator.getUserMedia (

			{
				video: true,
				audio: false
			},

			function(localMediaStream) {
				var video = document.querySelector('video');
				video.src = window.URL.createObjectURL(localMediaStream);
				video.play();
				var imgData = null;
				var canvas = document.querySelector('canvas');
				var ctx = canvas.getContext('2d');
				var alertMessage = document.querySelector('.ca_errorBloc');

				function takePicture() {
					var width = video.offsetWidth;
					var height = video.offsetHeight;
					canvas.setAttribute('width', width);
					canvas.setAttribute('height', height);
					if (localMediaStream) {
						ctx.drawImage(video, 0, 0, width, height);
						imgData = canvas.toDataURL();
						canvas.style.display = "inline";
						document.getElementById('namePicture').style.display = "inline";
						document.getElementById('validPicture').style.display = "inline";
					}
				}

				function getImage() {
					if (canvas.style.display == "inline") {
						var oReq = new XMLHttpRequest();
						var namePicture = document.getElementById('namePicture').value;
						if (!namePicture)
							namePicture = 'Camagru - ' + Math.round(Math.random()*100);
						var postData = "imgUser=" + imgData + "&name=" + namePicture;
						oReq.open("POST", "http://localhost:8080/camagru/pictures/new_image.php", true);
						oReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						oReq.send(postData);
						oReq.onreadystatechange = function() {
					 		if (oReq.readyState == 4 && oReq.status == 200) {
								var response = JSON.parse(oReq.responseText);
								if (response.code == 900) {
									addClass(alertMessage, 'ca_border_blue');
									if (!document.querySelector('.ca_list_pictures').querySelector('li'))
										document.querySelector('.ca_list_pictures').innerHTML = "";
									document.querySelector('.ca_list_pictures').innerHTML += response.new_image;
								} else if (response.code == 901) {
									addClass(alertMessage, 'ca_border_orange');
								}
								alertMessage.querySelector('#ca_text').innerText = response.message;
								alertMessage.style.display = "block";
								canvas.style.display = "none";
								document.getElementById('namePicture').style.display = "none";
								document.getElementById('namePicture').value = "";
								document.getElementById('validPicture').style.display = "none";
							}
						}
					}
				}
				document.getElementById('takePicture').addEventListener('click', takePicture, false);
				document.getElementById('validPicture').addEventListener('click', getImage, false);
			},
			function(err) {
				console.log("The following error occured: " + err);
			}
		);
	} else {
		console.log("getUserMedia not supported");
	}
</script>