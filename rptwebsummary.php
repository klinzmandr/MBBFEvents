<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Web Summary</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
  $("#helptext").hide();

$("#help").click (function (){
  $("#helptext").toggle();
  });
});
</script>


<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/mainmenu.inc.php';
//include 'Incls/listutils.inc.php';

// Process listing based on selected criteria
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

print <<<helpPart
<h2>Web Detail Report<span id="help" title="Help" class="hidden-print glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span></h2>

<div id="helptext">
<p>This report lists all events with a status of &apos;<b>RETAIN</b>&apos; and duplicates the layout in the web site full summary format.</p>
<p>Printing of the report is possible but should be done after doing a print preview and adjusting the print settings appropriately.</p>
<p>Special formatting may be accomplished by doing a &quot;Select All/Copy&quot; in the web pge and &quot;Paste&quot; into a word processor or spreadsheet.</p>

</div>

helpPart;

//$sql = 'SELECT * FROM `events` WHERE 1=1 LIMIT 0,10;';
//$sql = 'SELECT * FROM `events` WHERE 1=1;';
$sql = '
SELECT * FROM `events` 
WHERE `TripStatus` LIKE "%Retain%" 
ORDER BY `Dnbr` ASC, `StartTime` ASC, `EndTime` ASC;
';


//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
//echo '<table border="1" class="table">';
echo '<table border="0">';

while ($r = $res->fetch_assoc()) {
  //echo '<pre> full record for '.$rowid.' '; print_r($r); echo '</pre>';
  $codes = array();
  if (strlen($r['Level']) > 0) $codes[] = str_replace(",","",$r['Level']); 
  if (strlen($r['FEE']) > 0)  $codes[] = '$'.$r['FEE'];
  $codeout = implode('&nbsp;&nbsp;&nbsp;&nbsp;',$codes);
  $st = date('h:i a', strtotime($r['StartTime']));
  $et = date('h:i a', strtotime($r['EndTime']));
  $timerange = $r['Day'] . ', ' . $st.' - '.$et;
  printf("<tr><td><b>%s</b></td><td>%s</td><td colspan=3>%s&nbsp;%s</td></tr>", 
    $r['Trip'], $r['Event'], $r['TypeOfEvent'], $r['Type']);
  printf("<tr><td colspan=2>%s</td><td>%s</td><td colspan=2>%s</td></tr>",$timerange,$r['Site'],$codeout);
  printf("<tr><td colspan=5>%s</td></tr>", $r['Program']);
  printf("<tr><td colspan=5>&nbsp;</td></tr>");
  }
echo '</table>';
// echo "<pre> csv \n"; print_r($csv); echo '</pre>';
file_put_contents("downloads/mailerlisting.csv", $csv);
// echo '<a href="rptmailerlisting.php">HOLD ON</a>';
?>
</body>
</html>