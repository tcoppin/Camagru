<video></video>
<button type="submit" name="button" value="sendPassForget" id="takePicture" class="ca_collapse">Prendre une photo</button>
<img src="" />
<canvas style="display:none;"></canvas>
<script type="text/javascript">
	navigator.getUserMedia = ( navigator.getUserMedia ||
                       navigator.webkitGetUserMedia ||
                       navigator.mozGetUserMedia ||
                       navigator.msGetUserMedia);

if (navigator.getUserMedia) {
   navigator.getUserMedia (

      // constraints
      {
         video: true,
         audio: false
      },

      // successCallback
      function(localMediaStream) {
        var video = document.querySelector('video');
        video.src = window.URL.createObjectURL(localMediaStream);
        // Do something with the video here, e.g. video.play()
        video.play();
        function takePicture() {
			if (localMediaStream) {
				var canvas = document.createElement('canvas');
			  	var ctx = canvas.getContext && canvas.getContext('2d');
			    ctx.drawImage(video, 0, 0);
			    document.querySelector('img').src = canvas.toDataURL('image/webp');
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