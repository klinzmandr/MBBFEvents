<?php
if(isset($_REQUEST[session_name()])){
$captcha = new KCAPTCHA();
if($_REQUEST[session_name()]){
  $key = $captcha->getKeyString();
	// echo "keystring: " . $key;
}