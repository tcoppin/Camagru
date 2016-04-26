<div class="ca_errorBloc ca_border_orange ca_color_white">
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