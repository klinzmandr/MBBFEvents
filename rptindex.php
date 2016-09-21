<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Report Menu</title>
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
<h1>Report Menu&nbsp;&nbsp;<a href="index.php" class="btn btn-primary">Main Menu</a><br>
</h1>

<table border="0" class="table">
<tr>
<td><a href="rptleaderinfo.php" class="btn btn-primary btn-xs">Leader Information</a></td>
<td>Listing of leader information.</td>
</tr>
<tr>
<td><a href="rptleaderactivity.php" class="btn btn-primary btn-xs">Leader Activity Report</a></td>
<td>Lists all event leaders that have assigned events in at least one of the four leader roles.</td>
</tr>
<tr>
<td><a href="rptmailerlisting.php" class="btn btn-primary btn-xs">Mailer Listing</a></td>
<td>Listing of events in the column layout of the event mailer.</td>
</tr>
<tr>
<td><a href="rpteventlisting.php" class="btn btn-primary btn-xs">Listing of Events</a></td>
<td>A listing of specific events.</td>
</tr>
<tr>
<td><a href="rpteventlistingfull.php" class="btn btn-primary btn-xs">Event Listing (Full)</a></td>
<td>This report produces a complete file of all fields for all events.</td>
</tr>
<tr>
<td><a href="rptleaderemailmerge.php" class="btn btn-primary btn-xs">Leader Mail Merge Extract</a></td>
<td>Creates a CSV file from all active events to be used for mail merge processing.</td>
</tr>
<tr>
<td><a href="rptprogramextract.php" class="btn btn-primary btn-xs">Program Info Extract</a></td>
<td>Extract of specific information from all registered events.</td>
</tr>
<tr>
<td><a href="rptsitesched.php" class="btn btn-primary btn-xs">Site Schedule</a></td>
<td>Provides the scheduled actvities for the venue seleted.</td>
</tr>
<tr>
<td><a href="rptindex.php" class="btn btn-primary btn-xs">Refresh</a></td>
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