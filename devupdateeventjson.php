<?php
// AJAX response code - bootstrap is implemented in the receiving page.

include 'Incls/datautils.planner.inc.php';

// echo request
print_r($_REQUEST);

$recno = $_REQUEST['eventrow'];
$day = $_REQUEST['day'];
$updarray['Trip'] = '999';
$updarray['TripStatus'] = 'Delete';
$updarray['Day'] = "";
// $updarray['Leader1'] = "";
// $updarray['Leader2'] = "";
// $updarray['Leader3'] = "";
// $updarray['Leader4'] = "";
print_r($updarray);

$res = sqlupdate('eventsdev', $updarray, "`RowID` = '$recno'");
echo "status: ";
print_r($res);

?>