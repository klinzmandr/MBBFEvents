<?php 
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);
date_default_timezone_set('America/Los_Angeles');

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$day = isset($_REQUEST['day']) ? $_REQUEST['day'] : ""; // event day of week
$et = isset($_REQUEST['et']) ? $_REQUEST['et'] : "";    // event type
$ss = isset($_REQUEST['ss']) ? $_REQUEST['ss'] : '';    // event search string
$site = isset($_REQUEST['Site']) ? $_REQUEST['Site'] : '';
$transportneeded = isset($_REQUEST['TransportNeeded']) ? $_REQUEST['TransportNeeded'] : '';
$tripstatus = isset($_REQUEST['TripStatus']) ? $_REQUEST['TripStatus'] : '';
$typeofevent = isset($_REQUEST['TypeOfEvent']) ? $_REQUEST['TypeOfEvent'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>DEV Event Lister</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
<link href="css/bootstrap-sortable.css" rel="stylesheet">
</head>
<body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-sortable.js"></script>

<?php
// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/listutils.inc.php';
// include 'Incls/mainmenu.inc.php';
?>
<script>
$(function() {
  $.bootstrapSortable({ sign: 'AZ' })
});
</script>

<script> 
$(document).ready(function () { 
  $(function(){
     $("#btnMORE").click(function() {
      $("#btnMORE").toggle();
      $(".HIDE").toggle();
     });
     $("#btnLESS").click(function() {
      $(".HIDE").toggle();
      $("#btnMORE").toggle();
     });
  });
});
</script>
<script type="text/javascript">
// set up select lists
$(document).ready(function () { 
	//alert("start field initialization");
	$("#S2").val("<?=$et?>");
	var et = "<?=$et?>";
	if (et == "%") $("#S2").val("");
	$("#S1").val("<?=$day?>");
	$("#TripStatus").val("<?=$tripstatus?>");
	$("#SS").val("<?=$ss?>");
	$("#Site").val("<?=$site?>");
	$("#TypeOfEvent").val("<?=$typeofevent?>");
	// $("#TransportNeeded").val("'.$transportneeded.'");
  $(".HIDE").hide();
  //alert("end field initialization");
});
</script>
<script type="text/javascript">
// reset all select input fields to null
function resetflds() { 
	$(":input").val("");
	return false;
}
</script>

<h3>DEV Event Listing</h3>
<h4 class="hidden-print">Select from list or enter selection criteria to search: </h4>
<form class="hidden-print" id="F1" action="devlister.php" method="post">
<select id="S1" name=day>';
<?php echo readlist('Day'); ?>
</select>&nbsp;

<select id="TripStatus" name="TripStatus">';
<?php echo readlist('TripStatus'); ?>
</select>

<input id="SS" type=text value="" name=ss placeholder="Search Filter (use % to list all)" title="Enter a single word or short character string to search all program descriptions.">&nbsp;
<input type=hidden name=action value="list">
<button class="btn btn-primary" type="submit" form="F1" data-toggle="tooltip" data-placement="left" title="Search for % to list all">SEARCH</button>
<button class="btn btn-warning" onclick="return resetflds()">Reset</button>

<div class="HIDE">

<select id="Site" name="Site">
<option value="">Sites</option>';
<?php echo readvenlist('Site'); ?>
</select>

<select id="TypeOfEvent" name="TypeOfEvent">
<?php echo readlist('TypeOfEvent'); ?>
</select>

<br><button id="btnLESS">LESS</button>
</form>
</div>
<button class="hidden-print" id="btnMORE">MORE</button>

<?php

// setup day sequence number array  
$dayarray = array();
$days = readlistarray('Day');
$daynbr = 1;
foreach ($days as $v) {
  preg_match('/^.*>(.*)<.*$/i', $v, $matches);
  if ($matches[1] == 'Day') continue;
  // echo '<pre>'; print_r($matches[1]); echo '</pre>';
  $dayarray[$matches[1]] = $daynbr;
  $daynbr += 1;
  }
// echo '<pre>dayarray '; print_r($dayarray); echo '</pre>';

// Process listing based on selected criteria
$sql = '
SELECT * FROM `eventsdev` WHERE ';
$sqllen = strlen($sql);
if (strlen($day) > 0) { 
  $sql .= '`Day` LIKE "%'.$day.'%" AND '; }
if (strlen($et) > 0) {
  $sql .= '`Type` LIKE "%'.$et.'%" AND '; }
if (strlen($site) > 0) {
  list($s,$c) = explode(':', $site);
  $sql .= '`Site` LIKE "'.$s.'" AND '; }
if (strlen($transportneeded) > 0) {
  $sql .= '`TransportNeeded` = "'.$transportneeded.'" AND '; }
if (strlen($tripstatus) > 0) {
  $sql .= '`TripStatus` = "'.$tripstatus.'" AND '; }
if (strlen($typeofevent) > 0) {
  $sql .= '`TypeOfEvent` = "'.$typeofevent.'" AND '; }
if (strlen($ss) > 0) {
  $sql .= '
  (`Program` LIKE "%'.$ss.'%" 
  OR `Type` LIKE "%'.$ss.'%" 
  OR `Event` LIKE "%'.$ss.'%" 
  OR `Trip` LIKE "%'.$ss.'%" 
  OR `Leader1` LIKE "%'.$ss.'%" 
  OR `Leader2` LIKE "%'.$ss.'%"
  OR `Leader3` LIKE "%'.$ss.'%"
  OR `Leader4` LIKE "%'.$ss.'%"
  OR `MultiEventCode` LIKE "%'.$ss.'%"
  OR `SecondaryStatus` LIKE "%'.$ss.'%") AND ';
  }

if (strlen($sql) == $sqllen) {      // no criteria entered
  echo '
<h4 style="color: red; ">No criteria entered for search.</h4>
</div> <!-- contianer -->
</body>
</html>';
  exit;
  }

if (strlen($tripstatus) == 0) 
  $sql .= '`TripStatus` NOT LIKE "Delete" AND ';

$sql = substr($sql,0,-5);       // trip trailing 5 char's
$sql .= ' ORDER BY `Dnbr` ASC, `StartTime` ASC, `EndTime` ASC';
$sql .= ';';

//echo "<br>sql before submit: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

echo '
<h4>DEV Events Listed&nbsp;&nbsp;(Listed: '.$rc.')

<table border="1" class="sortable table table-condensed table-hover">
<thead><tr><th data-defaultsort="asc">Day</th><th>TripStatus</th><th>Trip Type</th><th>StartTime</th><th>Event</th><th>Venue</th><th>EventTitle</th><th>Leader</th></tr></thead><tbody>';
$navarray = array(); $var = array(); $ptr = 0;
while ($r = $res->fetch_assoc()) {
  //if ($r['Type'] == '**New**') continue;
  $navarray[] = $r['RowID'];
//  echo '<pre> full record '; print_r($r); echo '</pre>';
//  echo '
//<tr onclick=window.location="devupdateevent.php?ptr='.$ptr.'" style="cursor: pointer;">
echo "<tr onclick=\"window.location='devupdateevent.php?ptr=$ptr'\" style='cursor: pointer;'>";
$stime = ($r['StartTime'] != '') ? date("g:i A", strtotime($r['StartTime'])) : '';
$stv = strtotime($r['StartTime']);
$d = $dayarray[$r['Day']];
echo "
<td data-value='$d'>$r[Day]</td>
<td>$r[TripStatus]</td>
<td>$r[Type]</td>
<td data-value='$stv'>$stime</td>
<td>$r[TypeOfEvent]</td>
<td>$r[Site]</td>
<td>$r[Trip]&nbsp$r[Event]</td>
<td>$r[Leader1]</td>
</tr>
";
$ptr += 1;
}
echo '</tbody></table>';

$nav['start'] = 0; $nav['prev'] = ''; $nav['curr'] = '';
$nav['next'] = ''; $nav['last'] = count($navarray) - 1;

$_SESSION['navarray'] = $navarray;
$_SESSION['nav'] = $nav;

?>
</body>
</html>