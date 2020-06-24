<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Leader Review</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
</head>
<body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.planner.inc.php';
//include 'Incls/listutils.inc.php';
if (isset($_REQUEST['rowid'])) {
  $rowid = $_REQUEST['rowid'];
  echo "<a class='btn btn-success' href=\"ldrupdate.php?rowid=$rowid\">RETURN</a><br>";
  }
// add return button if called from ldrupdate.php

// Process listing based on selected criteria
$eaddr = isset($_REQUEST['eaddr']) ? $_REQUEST['eaddr'] : "";

$sql = "SELECT * FROM `leaders` WHERE `Email`='$eaddr';";

//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

if ($rc == 0) {
  echo '<h2>ERROR: email address not associated with any registered leader.</h2>';
  exit;
  }  

if ($rc > 1) {
  echo "<h2>NOTE: multiple leaders have the same email address</h2>
  <h4>Following $rc leaders share the email address of &quot;$eaddr&quot;.</h4>";
  }  

echo '<img src="http://morrobaybirdfestival.org/wp-content/uploads/2016/08/LOGO3.png" alt="bird festival logo" >';
// there should be only 1 record returned so process it here
// but output them all if more than one
while ($l = $res->fetch_assoc()) {
$leader = $l[FirstName] . ' ' . $l[LastName];

addlogentry("ldrqry for $leader");

echo "<h1>Scheduled Events for $leader </h1>";
 
// generate activity report
$sql = "
SELECT * FROM `events` 
WHERE (`Leader1` LIKE '$leader' 
  OR `Leader2` LIKE '$leader' 
  OR `Leader3` LIKE '$leader' 
  OR `Leader4` LIKE '$leader')
 AND `TripStatus` NOT LIKE 'Delete'
ORDER BY `Trip` ASC;";

//echo "<br>sql: $sql<br>";
$rese = doSQLsubmitted($sql);
$rc = $rese->num_rows;

if ($rc == 0) {
  echo '<ul><h3>Leader not registered for any ACTIVE event.</h3></ul>'; }
else {  
  echo '
  <table border=1 class="table table-condensed">
  <tr><th>Trip#</th><th>Day</th><th>Time</th>
  <th>Site</th><th>SiteRm</th>
  <th>Event</th><th>Leader Group</th></tr>';
  while ($r = $rese->fetch_assoc()) {
    $kk = $r[Dnbr];
    if ($kk == 1) $dx='Friday '; if ($kk == 2) $dx='Saturday ';
    if ($kk == 3) $dx='Sunday '; if ($kk == 4) $dx='Monday ';
    if ($kk == '') $dx='NotSet';
    $st = date("g:iA", strtotime($r[StartTime]));
    $et = date("g:iA", strtotime($r[EndTime]));
    // echo '<pre> full record '.$rowid.' '; print_r($r); echo '</pre>';
    $ldrgrp = $r[Leader1];
    if (strlen($r[Leader2]) > 0) $ldrgrp .= ', '.$r[Leader2]; 
    if (strlen($r[Leader3]) > 0) $ldrgrp .= ',<br>'.$r[Leader3]; 
    if (strlen($r[Leader4]) > 0) $ldrgrp .= ', '.$r[Leader4]; 
    echo 
  "<tr><td>$r[Trip]</td><td>$dx</td><td>$st-$et</td>
  <td>$r[Site]</td>
  <td>$r[SiteRoom]</td>
  <td>$r[Event]</td><td>$ldrgrp</td><tr>";         
    }
  echo '</table>';
  }

echo "<h2>Information on file for $leader</h2>";
$img = $l[ImgURL];
$bio = preg_replace('/(?<!href="|">)(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/is', '<a href="\\1" target="_blank">\\1</a>', $l[Bio]);

if ($l[ImgURL] == '') $img = "./npa.png";
//echo '<pre> full leader record'; print_r($l); echo '</pre>';
print <<<infoPart
<table class="table" border=0><tr><td>
<img src="$img" width="200" height="150" alt=""></td>
<td>&nbsp;</td>
<td>
<table class="table" border=0>
<tr><td width="20%"><b>Primary Phone:</b></td><td>$l[PrimaryPhone]</td></tr>
<tr><td><b>Secondary Phone:</b></td><td>$l[SecondaryPhone]</td></tr>
<tr><td><b>Email Address:</b></td><td>$l[Email]</td></tr>
<tr><td><b>Firm:</b></td><td>$l[Address2]</td></tr>
<tr><td><b>Address:</b></td><td>$l[Address1]</td></tr>
<tr><td><b>City, State ZIP:</b></td><td>$l[City], $l[State] $l[Zip]</td></tr>
<tr><td valign="top"><b>Biography</b></td><td>$bio</td></tr>
</table>
</td></tr></table>
==========<br><br><br>
infoPart;
}
?>
</body>
</html>