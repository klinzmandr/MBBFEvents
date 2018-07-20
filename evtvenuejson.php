<?php
// AJAX response code - bootstrap is implemented in the receiving page.

include 'Incls/datautils.planner.inc.php';

$vencode = $_REQUEST['vencode'];
echo "<h2>Venue Code: $vencode</h2>";
$sql  = "SELECT * FROM `venues` WHERE `VenCode` = '$vencode';"; 
// echo "sql: $sql<br>";

$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
$r = $res->fetch_assoc();

if ($rc == 0) {
  echo "<h3>There is no venue info for $vencode</h3>";
  exit;
  }

$r[VenInsurInfo] = html_entity_decode($r[VenInsurInfo], ENT_QUOTES);
$r[VenSpecNotes] = html_entity_decode($r[VenSpecNotes], ENT_QUOTES);

$mi = 'YES'; if ($r[VenGmapURL] == '') $mi = 'NO';

echo '<table class="table" border=1><tr><td>';
echo 
'<b>Name: </b>' . $r[VenName] . '<br>' .
'<b>Phone: </b>' . $r[VenPhone] . '<br>' .
'<b>Address: </b>' . $r[VenAddr] . '<br>' .
'<b>Address 2: </b>' . $r[VenAddr2] . '<br>' .
'<b>City: </b>' . $r[VenCity] . '  ' . 
'<b>State:</b> ' . $r[VenState] . '  ' .
'<b>Zip: </b> ' . $r[VenZip] . '<br>' .
'<b>Contact Name: </b>' . $r[VenContactName] . '<br>' .
'<b>Contact Phone: </b>' . $r[VenContactPhone] . '<br>' .
'<b>Conact Email: </b>' . $r[VenContactEmail] . '<br>' .
'<b>Insurance Info: </b>' . '<br>' . $r[VenInsurInfo] . '<br>' .
'<b>Special Notes: </b>' . '<br>' . $r[VenSpecNotes] . '<br>' .
'<b>Map Info Defined: </b>' . $mi . '<br>' . $r[VenGmapURL]
;

// echo '<pre>'; print_r($r); echo '</pre>';
echo '</td></tr></table>';

?>