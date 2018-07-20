<?php
session_start();

$ks = $_REQUEST['ks'];
$skey = $_SESSION['captcha_keystring'];
if ($ks == $skey) { echo "OK"; }
else { echo "NO"; }

?>