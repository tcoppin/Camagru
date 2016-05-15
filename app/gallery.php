<div class="ca_container ca_gallery">
	<h1 class="ca_color_blue ca_no_margin">Galerie</h1>
	<ul class="ca_list_pictures">
	<?php
		$sql = 'SELECT `name_picture`, `file_name` FROM ca_pictures';
		$rtn = $db->selectInDb($sql);
		foreach ($rtn as $key => $value) {
			echo "<li class=\"ca_img_gallery\">
					<img src=\"".ADDR_HOST."/content/tmp/".$value['file_name'].".png\" data-name=\"\" />
					<span class=\"ca_namePicture ca_color_blue\">".$value['name_picture']."</span>
				</li>";
		}
	?>
	</ul>
</div>

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
</style>

<div class="ca_overSite" style="display: none;" onclick="hidePopIn();"></div>
<div class="ca_container ca_popIn" id="popIn" style="display: none;">
	<span class="ca_abort_popIn" onclick="hidePopIn();"></span>
	<h2 class="ca_color_blue"></h2>
	<img class="ca_img_popIn" src="" />
	<div class="ca_popIn_option">
		<button class="ca_collapse right" id="likeBtn">Like</button><!-- add ca_like -->
		<input type="text" class="ca_collapse ca_width_70 ca_no_radius" placeholder="Commenter"></input>
		<button class="ca_collapse">Envoyer</button>
	</div>
	<ul class="ca_popIn_comment">
		<li></li>
	</ul>
</div>

<script type="text/javascript">
	function addLike() {
		var likeBtn = document.getElementById('likeBtn');

		addClass(likeBtn, 'ca_like');
	}
	document.getElementById('likeBtn').addEventListener('click', addLike);

	function bindGallery() {
		var listImg = document.querySelector('.ca_list_pictures').getElementsByTagName('li');
		for (var i = 0; i < listImg.length; i++) {
			listImg[i].addEventListener('click', showPopIn);
		}
	}
	bindGallery();
</script>