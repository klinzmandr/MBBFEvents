<?php
session_start();

// print_r($_REQUEST);
$regnbr = $_REQUEST['RegNbr'];
$evtnbr = $_REQUEST['EvtNbr'];
$overall = $_REQUEST['Overall'];
$event = $_REQUEST['Event'];
$leader = $_REQUEST['Leader'];
$venue = $_REQUEST['Venue'];
$comments = $_REQUEST['Comments'];

$dt = date("Y-m-d;H:i:s", strtotime("now"));
echo "Rating - $dt:$regnbr:$evtnbr:$overall:$event:$leader:$venue:$comments";
$txt = "$dt;$regnbr;$evtnbr;$overall;$event;$leader;$venue;\"$comments\"";
echo $txt;
$txt .= "\n";
file_put_contents("ratingsfile.txt", $txt, FILE_APPEND );


?>