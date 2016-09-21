<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Utility Maintenance</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<div class="container">
<img src="http://morrobaybirdfestival.net/wp-content/uploads/2016/08/LOGO3.png" alt="bird festival logo" >
<?php
// session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

// output menu 
echo '
<div class="container">
<h1>Utilities Menu&nbsp;&nbsp;<a href="index.php" class="btn btn-primary">Main Menu</a><br>
</h1>

<table border="0" class="table">
<tr>
<td><a href="utllistmaint.php" class="btn btn-primary btn-xs">List Maintenance</a></td>
<td>Utility to maintain the contents of all select list (drop down) menu items used in the system.</td>
</tr>
<!-- <tr>
<td><a href="utlloader.php" class="btn btn-primary btn-xs">Spreadsheet Loader</a></td>
<td><a href="#" class="btn btn-primary btn-xs">Spreadsheet Loader</a></td>
<td>Utility to upload a pre-formatted CSV spreadsheet file.<br>(NOT YET AVAILABLE)</  td>
</tr> -->
<tr>
<td><a href="utllogbrowser.php" class="btn btn-primary btn-xs">Useage Log Browser</a></td>
<td>Provides the ability to examine the system usage log based on date and time.</td>
</tr>
<tr>
<td><a href="utlpagesum.php" class="btn btn-primary btn-xs">Page Usage Summary</a></td>
<td>System usage summary based on date/time range.</td>
</tr>
<tr>
<td><a href="utladmin.php" class="btn btn-primary btn-xs">User administration</a></td>
<td>Maintain system user identification and passwords. <br>(NOTE: requires secondary password)</td>
</tr>
<tr>
<td><a href="utlresequence.php" class="btn btn-primary btn-xs">Resequence Day Events</a></td>
<td>Provides the ability to resequence events for a single day. <br>(NOTE: requires secondary password)</td>
</tr>
<tr>
<td><a href="utlresetstatus.php" class="btn btn-primary btn-xs">Reset Trip Status</a></td>
<td>Sets &quot;Trip Status&quot; for all events to &quot;Under Consideration&quot;. <br>(NOTE: requires secondary password)</td>
</tr>
<tr>
<td><a href="utlvalidatedb.php" class="btn btn-primary btn-xs">Validate database</a></td>
<td>Performs basic validation of all events and leader records in the database.</td>
</tr>
<tr>
<td><a href="sumextract.php" class="btn btn-primary btn-xs" target="_blank" >SignUp Masters Extract</a></td>
<td>Extract event information for download to event registration vendor.<br>(NOTE: opens new window/tab)</td>
</tr>
<tr>
<td><a href="utlindex.php" class="btn btn-primary btn-xs">Refresh</a></td>
<td>Reload the menu page</td>
</tr>
</table>

</div> <!-- container -->
</body>
</html>';
exit;


?>
</div> <!-- container -->
</body>
</html>