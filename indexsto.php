<?php
session_start();
$lotype = isset($_REQUEST['lo']) ? $_REQUEST['lo'] : 'to';

// include 'Incls/vardump.inc.php';

include_once 'Incls/datautils.inc.php';
$user = $_SESSION['CTS_SessionUser'];
unset($_SESSION['CTS_SessionUser']);
unset($_SESSION['CTS_SecLevel']);

// logout or timeout reset requested
if ($lotype == 'nli') {
  addlogentry("user not logged in"); }
elseif ($lotype == 'lo') { 
  addlogentry("$user Logged out");	}
else { 
  addlogentry("$user Timed out"); }

session_unset();
session_destroy();

?>
<!DOCTYPE html>
<html>
<head>
<title><?=$title?></title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
<script src="js/jquery.min.js"></script>
<script>
$(document).ready(function() {
  // alert("document loaded");
  window.location.assign('index.php');
});
</script>

<div class="container">
  <p>Your session has been terminated.</p>
  <p>Click the following button to log into the application.</p>
  <a class="btn btn-success" href="index.php">Restart application</a>
</div>
</body>
</html>