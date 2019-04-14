<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Validate Database</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<div class="container">
<script>
$(document).ready(function() {
  $("#helptext").hide();
  $("#eventrpt").hide();
  $("#leaderrpt").hide();
  $("#venerrpt").hide();

$("#help").click (function (){
  $("#helptext").toggle();
  });
$("#evt").click (function (){
  $("#eventrpt").toggle();
  });
$("#ldr").click (function (){
  $("#leaderrpt").toggle();
  });
$("#ven").click (function (){
  $("#venerrpt").toggle();
  });
});
</script>

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/mainmenu.inc.php';
include 'Incls/letter_print_css.inc.php';

// Process listing based on selected criteria
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
?>

<h3>Validate Database
<span id="help" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>
</h3>
<div id="helptext">
Connection Info: <?=$mysqli->host_info?><br>
Client Info: <?=$mysqli->client_info?><br>
Server Info: <?=$mysqli->server_info?><br /><br>
<h4>Overview</h4>
<p>This program will perform various database validations including those listed:</p>
Examine all ACTIVE event records and report:
<ol>
	<li>any with missing Site Codes. (venue/site name and code need to be defined).</li>
	<li>any event with an UNDEFINED site/venue.</li>
	<li>any that do not have a Leader 1 identified.</li>
	<li>any with invalid leaders not in the leaders registry.</li>
	<li>any with missing start and/or end times.</li>
	<li>any with &quot;Fee Required&quot; indicated but no &quot;FEE&quot; entered.</li>
	<li>any with &quot;Transport Required&quot; indicated but no &quot;Transportation&quot; entry.</li>
	<li>any indicated to be a &quot;Multi-code&quot; but no &quot;Multi-code&quot; entered.</li>
	<li>any missing a value for the &quot;Max Attendees&quot;.</li>
	<li>any missing a expertise level rank.</li>
</ol>
Examine all leader records and report:
<ol>
	<li>any leader not having a primary phone number registered.</li>
	<li>any leader without an email address.</li>
	<li>any leader with a duplicated email address.</li>
	<li>any leader missing a photo image.</li>
	<li>any leader that is &apos;active&apos; but not assigned a current event.</li>
	<li>any leader that has not been assigned a &apos;Leader Type&apos;.</li>
</ol>
Examine all venue/site records and report:
<ol>
	<li>any venue that is defined in the site/venue list but has no registered information in the site/venue database.</li>
	<li>any venue lacking an address</li>
	<li>any venue lacking city, state or zip informaton.</li>
	<li>any venue lacking map information</li>
</ol>
<div class="page-break"></div>
</div>

<?php
// ============ load up leader array
$sql = '
SELECT * FROM `leaders` WHERE 1=1 AND `Active` = "Yes";';

//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$leaderrc = $res->num_rows;
$ldrs = array();
while ($r = $res->fetch_assoc()) {
//  echo '<pre> full record for '.$rowid.' '; print_r($r); echo '</pre>';
  $key = $r[FirstName] . ' ' . $r[LastName];
  $key = rtrim($key, ' ');
  if ($r[LastName] == '') $key = $r[FirstName];
  if ($r[FirstName] == '') $key = $r[LastName];
  $ldrs[$key] = $r;
  }
//echo '<pre> leader '; print_r($ldrs); echo '</pre>';

// ============= load up venue/site array
$sql = "SELECT * FROM `venues` WHERE 1 ORDER BY `VenCode` ASC;";
//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$venrc = $res->num_rows;
$venarray = array(); $venrow = array();
while ($r = $res->fetch_assoc()) {
  if ($r[VenCode] == '--New--') continue;
  $venarray[$r[VenCode]] = $r[VenName];
  $venrow[$r[VenCode]] = $r;
  }
// echo '<pre> venarray '; print_r($venarray); echo '</pre>';
// echo '<pre> vendrow '; print_r($venrow); echo '</pre>';

// ========== read and validate events
$sql = '
SELECT * FROM `events` 
-- WHERE `TripStatus` IS NULL
WHERE 1=1
ORDER BY `Trip` ASC;';

//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$eventrc = $res->num_rows;

$err = array();
while ($r = $res->fetch_assoc()) {
  // echo '<pre> full record for '.$rowid.' '; print_r($r); echo '</pre>';
  if($r[Trip] == 999) continue;  // ignore those parked
  if ($r[SiteCode] == '') $err[$r[Trip]][] = "is missing a site code";
  if (!array_key_exists($r[SiteCode], $venarray)) 
    $err[$r[Trip]][] = "site code $r[SiteCode] is not defined.";
  $venused[$r[SiteCode]] += 1;
  if ($r[Leader1] == '' ) $err[$r[Trip]][] = "has no Leader 1 defined";
  $ldrassigned[$r[Leader1]] += 1;
  if (($r[Leader1] != '') AND (!array_key_exists($r[Leader1], $ldrs))) {
    $err[$r[Trip]][] = sprintf("Leader1 (%s) is not registered.", $r[Leader1]);
    }
  $ldrassigned[$r[Leader2]] += 1;
  if (($r[Leader2] != '') AND (!array_key_exists($r[Leader2], $ldrs))) { 
    $err[$r[Trip]][] = sprintf("Leader2 (%s) is not registered.", $r[Leader2]); 
    }
  $ldrassigned[$r[Leader3]] += 1;
  if (($r[Leader3] != '') AND (!array_key_exists($r[Leader3], $ldrs))) { 
    $err[$r[Trip]][] = sprintf("Leader3 (%s) is not registered.", $r[Leader3]);
    }
  $ldrassigned[$r[Leader4]] += 1;
  if (($r[Leader4] != '') AND (!array_key_exists($r[Leader4], $ldrs))) {
    $err[$r[Trip]][] = sprintf("Leader4 (%s) is not registered.", $r[Leader4]);
    }
  if ($r[StartTime] == '') $err[$r[Trip]][] = "Has no start time defined";
  if ($r[EndTime] == '') $err[$r[Trip]][] = "Has no end time defined";
  if (($r[FeeRequired] == 'Yes') AND ($r[FEE] == '')) 
    $err[$r[Trip]][] = "Has a fee requirement and no fee.";
  if (($r[MultiEvent] == 'Yes') AND ($r[MultiEventCode] == '')) 
    $err[$r[Trip]][] = "is identified as a multi-event function but has no multi-event code.";
  if (($r[TransportRequired] == 'Yes') AND ($r[Transportation] == '')) 
      $err[$r[Trip]][] = "Has a trasport requirement and no transportaton identified.";
  if ($r[MaxAttendees] == '') $err[$r[Trip]][] = "has no max attendee limit defined.";
  if ($r[Level] == '') $err[$r[Trip]][] = "has no experience levels defined.";
  }

// echo '<pre>ldrs '; print_r($ldrs); echo '</pre>';
// echo '<pre>ldrassigned '; print_r($ldrassigned); echo '</pre>';

// check out leader info
$ema = array();
foreach ($ldrs as $k => $v) {
  $ldrfull = $v[FirstName] . " " . $v[LastName];
  $ldrfull = rtrim($ldrfull, ' ');
  if ($v[Email] == '') $ldrerr[$k][] = "Leader missing email address.";
  if (!in_array($v[Email], $ema)) $ema[] = $v[Email];
  else $ldrerr[$k][] = "Leader email address is a duplicate.";  
  if ($v[PrimaryPhone] == '') $ldrerr[$k][] = "Leader missing primary phone number.";
  if ($v[ImgURL] == '') $ldrerr[$k][] = 'Leader missing photo image.';
  if (!(array_key_exists($ldrfull, $ldrassigned))) 
    $ldrerr[$k][] = 'Leader active but not asssigned an event.'; 
  if (($v[LdrEvent] == '') AND ($v[LdrDay] == ''))
    $ldrerr[$k][] = 'Leader does not have a valid Leader Type designated.';
  }

// check venue records
$venueuse = array();
$venerr = array();   // errors noted
ksort($venused);  // venues used, $venarray = venues registered in DB
// echo '<pre> ven defined '; print_r($venarray); echo '</pre>';
// echo '<pre> ven used '; print_r($venused); echo '</pre>';
foreach ($venused as $k => $v) {
  if (!array_key_exists($k, $venarray)) $venerr[] .= "$k is used $venused[$k] times but not registered as a venue"; }
foreach ($venused as $k => $v) {
  $venueuse[$k] .= "$v"; }
foreach ($venrow as $k => $v) {
  if ($v[VenAddr] == '') $venerr[] .= "$k does not have an address defined";
  if (($v[VenCity] == '') || ($v[VenState] == '') || ($v[VenZip] == ''))
    $venerr[] .= "$k has incomplete city, state or zip code information provided";
  if ($v[VenGmapURL] == '') $venerr[] .= "$k has no map information provided.";
  }

// echo '<pre> ven errors '; print_r($venerr); echo '</pre>';
// echo '<pre> ven use '; print_r($venueuse); echo '</pre>';
  
//echo '<pre> error '; print_r($err); echo '</pre>';

echo '
<h4>Event Record Validation Report
<span id="evt" title="View Event Report" class="hidden-print glyphicon glyphicon-zoom-in" style="color: blue; font-size: 30px"></span></h4>
<div id="eventrpt">';
if (count($err) > 0 ) {
  //echo '<pre> error '; print_r($errors); echo '</pre>';
  foreach ($err as $k => $v) {
    echo "Trip $k<br><ul>";
    foreach ($v as $l) {
      echo $l . '<br>';
      }
    echo '</ul><br>';
    }
  echo "</div>TOTAL EVENT ERRORS: ".count($err).'<br>';
  }
else {
  echo 'No event errors to report<br><br>';
  } 
echo '<div class="page-break"></div>
<h4>Leader Record Validation Report
<span id="ldr" title="View Leader Report" class="hidden-print glyphicon glyphicon-zoom-in" style="color: blue; font-size: 30px"></span>
</h4>
<div id="leaderrpt">';
if (count($ldrerr) > 0) {
  foreach ($ldrerr as $k => $v) {
    echo "$k<br><ul>";
    foreach ($v as $l) {
      echo $l . '<br>';
      }
    echo '</ul><br>';
    }
  echo '</div>';
  echo "TOTAL LEADER ERRORS: ".count($ldrerr).'<br>';
  }
else {
  echo 'No leader errors to report<br><br>';
  }

echo '<div class="page-break"></div>
<h4>Veune Record Validation Report
<span id="ven" title="View Venue/Site Report" class="hidden-print glyphicon glyphicon-zoom-in" style="color: blue; font-size: 30px"></span>
</h4>
<div id="leaderrpt">
<div id="venerrpt">';
// report venue/site errors
if (count($venerr) > 0) {
  foreach ($venerr as $err) {
    echo "$err<br>";
    }
  }
else echo 'No venue errors to report<br><br>';

echo '</div>
TOTAL VENUE ERRORS: '.count($venerr).'<br>';

echo '<br><b>Total events: '.$eventrc.'<br>
Total ACTIVE leaders: '.$leaderrc.'<br>
Total Venue/Sites: '.$venrc.'</b><br><br>';
echo '=== END REPORT ===<br><br>';
?>
</div> <!-- container -->
</body>
</html>