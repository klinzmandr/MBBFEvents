<?php
session_start();

$recnos = isset($_REQUEST['sessArray']) ? $_REQUEST['sessArray'] : 0;
// echo '<pre>SESSARRAY '; print_r($recnos); echo '</pre>';

$nav['start'] = 0; $nav['prev'] = ''; $nav['curr'] = '';
$nav['next'] = ''; 

$nav['last'] = 0;
if (is_array($recnos)) $nav['last'] = count($recnos) - 1;

$_SESSION['navarray'] = $recnos;
echo '<pre>NAVARRAY '; print_r($_SESSION['navarray']); echo '</pre>';
$_SESSION['nav'] = $nav;
echo '<pre>NAV  '; print_r($_SESSION['nav']); echo '</pre>';

echo "OK";
?>