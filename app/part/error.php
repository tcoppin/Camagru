<?php 
	if (isset($_SESSION['info']) && !empty($_SESSION['info']) && $_SESSION['info'] == true) {
		echo "<div class=\"ca_infoBloc ca_errorBloc ca_border_blue ca_color_white\">";
		unset($_SESSION['info']);
	} else {
		echo "<div class=\"ca_infoBloc ca_errorBloc ca_border_orange ca_color_white\">";		
	}
?>
	<?php 
		echo $_SESSION['error'];
		unset($_SESSION['error']);
	?>
	<span class="ca_close ca_color_orange" id="closeErrorBloc">X</span>
</div>

<script type="text/javascript">
	function closeError() {
		document.getElementsByClassName('ca_errorBloc')[0].style.display = 'none';
	}
	document.getElementById('closeErrorBloc').addEventListener('click', closeError, false);
</script>	