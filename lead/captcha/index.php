<?php
	unset($_SESSION['captcha_keystring']);
	?>
if(isset($_REQUEST[session_name()])){
$captcha = new KCAPTCHA();
if($_REQUEST[session_name()]){