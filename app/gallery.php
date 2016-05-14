<div class="ca_container ca_gallery">
	<h1 class="ca_color_blue ca_no_margin">Galerie</h1>
	<ul class="ca_list_pictures">
	<?php
		$sql = 'SELECT `name_picture`, `file_name` FROM ca_pictures';
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

<div class="ca_overSite" style="display: none;"></div>
<div class="ca_popIn" id="popIn" style="display: none;"></div>

<script type="text/javascript">
	function bindGallery() {
		var listImg = document.querySelector('.ca_list_pictures').getElementsByTagName('li');
		for (var i = 0; i < listImg.length; i++) {
			listImg[i].addEventListener('click', showPopIn);
		}
	}
	bindGallery();
</script>