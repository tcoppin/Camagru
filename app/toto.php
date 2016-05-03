<video></video>
<button type="submit" name="button" value="sendPassForget" id="takePicture" class="ca_collapse">Prendre une photo</button>
<canvas width="640" height="480"></canvas>
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
				function takePicture() {
					if (localMediaStream) {
						var canvas = document.querySelector('canvas');
						var ctx = canvas.getContext('2d');
						ctx.drawImage(video, 0, 0, 640, 480);
					}
				}
				document.getElementById('takePicture').addEventListener('click', takePicture, false);
			},

			// errorCallback
			function(err) {
				console.log("The following error occured: " + err);
			}
		);
	} else {
		console.log("getUserMedia not supported");
	}
</script>