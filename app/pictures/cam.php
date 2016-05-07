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
<div class="ca_container ca_bloc_left">
	<video class="ca_video_cam"></video>
	<button type="submit" name="button" id="takePicture" class="ca_width_50 ca_margin_top_5">Prendre une photo</button>
	<button type="submit" name="button" id="uploadPicture" class="ca_width_50 ca_margin_top_5">Uploader une photo</button>
	<canvas width="640" height="480" style="display: none;"></canvas>
	<button type="submit" name="button" id="validPicture" class="ca_width_50 ca_margin_top_5" style="display: none;">Valider la photo</button>
</div>

<div class="ca_container ca_bloc_right">
	rywubinfmweofybiuwenmfmweuyvb
</div>

<script type="text/javascript">
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

				function takePicture() {
					if (localMediaStream) {
						ctx.drawImage(video, 0, 0, 640, 480);
						imgData = canvas.toDataURL();
						canvas.style.display = "inline";
						document.getElementById('validPicture').style.display = "inline";
					}
				}

				function getImage() {
					if (canvas.style.display == "inline") {
						var oReq = new XMLHttpRequest();
						var postData= "imgUser="+imgData;
						oReq.open("POST", "http://localhost:8080/camagru/pictures/new_image.php", true);
						oReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						oReq.send(postData);
						oReq.onreadystatechange = function() {
					 		if (oReq.readyState == 4 && oReq.status == 200) {
								var response = oReq.responseText;
								console.log(response);
								document.querySelector('.ca_bloc_right').innerHTML = response;
								canvas.style.display = "none";
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