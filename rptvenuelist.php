<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Venue List</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<style>
table, th, td {
    border: 1px solid black;
}

th, td {
    padding: 5px;
}
</style>

<script>
$(document).ready( function() {
  $("#helptext").hide();

$("#help").click (function (){
  $("#helptext").toggle();
  });
});
</script>

<div class="container">

<h1>Venue List&nbsp;&nbsp;<span id="help" title="View Report Help" class="hidden-print glyphicon glyphicon-question-sign" style="color: blue; font-size: 30px"></span></h1>
<div id="helptext">
<p>Report lists all venues/sites defined in the system. </p>
<p>The total number of events that use the site/venue is reported.  A site/venue with a count of 0 might be a candidate for deletion.</p>
<p>Use the &quotAdd/Delete Venue(s)&quot; menu item to maintain the list of valid venue sites and codes.</p>
<p>NOTE: all site names and codes must UNIQUELY define a venue.  Duplicates are not allowed!</p>
</div>

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/mainmenu.inc.php';

$sql = 'SELECT * FROM `events` WHERE 1=1 ORDER BY `Trip` ASC;';
//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$eventrc = $res->num_rows;
$evtvencount = array();
while ($r = $res->fetch_assoc()) {
  $evtvencount[$r[SiteCode]] += 1;
  }
// echo '<pre> evtvencount '; print_r($evtvencount); echo '</pre>';

$sql = 'SELECT * FROM `venues` WHERE 1=1 ORDER BY `VenCode` ASC;';
//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$venrc = $res->num_rows;
$sitenames = array();
while ($r = $res->fetch_assoc()) {
  $sitenames[$r[VenCode]] = $r;
  }

// echo '<pre> sitenames '; print_r($sitenames); echo '</pre>';

echo '<table>
<tr><th>Venue Name</th><th>Code</th><th>Evt Count</th><th>Map Info?</th></tr>';  
foreach ($sitenames as $k => $v) {
  $vc = 0; if (array_key_exists($k, $evtvencount)) $vc = $evtvencount[$k];
  $mi = 'YES'; if ($v[VenGmapURL] == '') $mi = 'NO';
  echo "<tr><td>$v[VenName]</td><td>$k</td><td>$vc</td><td>$mi</td></tr>";
  }
echo '</table>===== END REPORT =====<br><br><br>';

?>
</div> <!-- container -->
</body>
</html>
