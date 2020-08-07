<?phpinclude('kcaptcha.php');
if(isset($_REQUEST[session_name()])){	session_start();}
$captcha = new KCAPTCHA();
if($_REQUEST[session_name()]){
  $key = $captcha->getKeyString();	$_SESSION['captcha_keystring'] = $key;
	// echo "keystring: " . $key;
}?>