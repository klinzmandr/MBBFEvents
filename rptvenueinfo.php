<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Venue Info Report</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">

<div class="container">
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/mainmenu.inc.php';

echo '
<h1>Venue Info Report</h1>';

$sql = '
SELECT * FROM `venues` WHERE `VenID` > 0;';

//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

echo '
<style>
 .page-break  {
      clear: left;
      display:block;
      page-break-after:always;
      }

</style>';

while ($r = $res->fetch_assoc()) {
  // echo '<pre> full record '; print_r($r); echo '</pre>';
  echo '<h3>' . $r['VenName'] .' (' . $r['VenCode'] . ')</h3>';
  $r['VenInsurInfo'] = html_entity_decode($r['VenInsurInfo']);
  $r['VenSpecNotes'] = html_entity_decode($r['VenSpecNotes']);
  echo '<table class="table" border=1><tr><td>';
  echo 
  '<b>Phone: </b>' . $r['VenPhone'] . '<br>' .
  '<b>Address: </b>' . $r['VenAddr'] . '<br>' .
  '<b>Address 2: </b>' . $r['VenAddr2'] . '<br>' .
  '<b>City: </b>' . $r['VenCity'] . '  ' . 
  '<b>State:</b> ' . $r['VenState'] . '  ' .
  '<b>Zip: </b> ' . $r['VenZip'] . '<br>' .
  '<b>Contact Name: </b>' . $r['VenContactName'] . '<br>' .
  '<b>Contact Phone: </b>' . $r['VenContactPhone'] . '<br>' .
  '<b>Conact Email: </b>' . $r['VenContactEmail'] . '<br>' .
  '<b>Insurance Info: </b><br>' . $r['VenInsurInfo'] . '<br>' .
  '<b>Special Notes: </b><br>' . $r['VenSpecNotes'] . '<br>' .
  '<b>Google Map Info: </b><br>' . $r['VenGmapURL']
  ;
  echo '</td></tr></table><div class="page-break"></div>';
  }
// echo '<pre>'; print_r($r); echo '</pre>';


?>
</div> <!-- container -->
</body>
</html>