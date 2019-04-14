<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>starRating, star rating jquery plugin</title>
<link rel="stylesheet" type="text/css" href="css/star-rating-svg.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<script src="js/jquery.min.js"></script>
<script src="js/jquery.star-rating-svg.js"></script>
<!-- <link rel="stylesheet" type="text/css" href="css/demo.css"> -->

</head>
<?php
//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';

$sql = 'SELECT `Trip`, `Event` FROM `events` 
WHERE `TripStatus` NOT LIKE "Delete" AND `TripStatus` = "Retain" 
ORDER BY `Trip` ASC;';
$res = doSQLsubmitted($sql);
while ($r = $res->fetch_assoc()) {
  $triparray[$r[Trip]] = $r[Trip] . ' ' . $r[Event];
  }
// echo '<pre>'; print_r($triparray); echo '</pre>';
$evtlist = '["' . implode('", "', $triparray) . '"]';
// echo "evtlist: $evtlist<br>";

?>

<body>
<img src="http://morrobaybirdfestival.org/wp-content/uploads/2016/08/LOGO3.png" alt="bird festival logo" >
<script src="js/bootstrap3-typeahead.js"></script>
<script>
$(function () {
 var evts = <?=$evtlist?>; 
  $("input.EVT").typeahead({source: evts});
});
</script>

<script>
$(document).ready(function(){
  $("#upd").click(function() {
    var regNumber = $("#regnbr").val();
    var evt = $("#evtnbr").val();
    var evtNumber = evt.substring(0,3);
    comments = $("#comments").val();
    $.post("rateeventJSON.php",
      {
        RegNbr: regNumber,
        EvtNbr: evtNumber,
        Overall: overallRating,
        Event: eventRating,
        Leader: leaderRating,
        Venue: venueRating,
        Comments: comments
      },
    function(data, status){
        console.log("exit Data: " + data + ", Status: " + status);
        // alert("Data: " + data + "\nStatus: " + status);
      });  // end $.post logic
    $("#upd").html('Thank You!');
    $('#upd').prop('disabled', true);      
    return false;
    });
});

var overallRating = 0;
var eventRating = 0;
var leaderRating = 0;
var venueRating = 0;
var comments = '';

$(function() {
  $(".overall").starRating({
    totalStars: 5,
    emptyColor: 'lightgray',
    hoverColor: 'slategray',
    activeColor: 'black',
    initialRating: 0,
    disableAfterRate: false,
    strokeWidth: 0,
    useGradient: false,
    callback: function(currentRating, $el){
      overallRating = currentRating;
    // alert('rated ' +  currentRating);
    // console.log("overallRating: "+currentRating);
    // console.log('DOM Element ', $el);
    }
  });
});

$(function() {
  $(".event").starRating({
    totalStars: 5,
    emptyColor: 'lightgray',
    hoverColor: 'slategray',
    activeColor: 'black',
    initialRating: 0,
    disableAfterRate: false,
    strokeWidth: 0,
    useGradient: false,
    callback: function(currentRating, $el){
      eventRating = currentRating;
    // alert('rated ' +  currentRating);
    // console.log("overallRating: "+currentRating);
    // console.log('DOM Element ', $el);
    }
  });
});

$(function() {
  $(".leader").starRating({
    totalStars: 5,
    emptyColor: 'lightgray',
    hoverColor: 'slategray',
    activeColor: 'black',
    initialRating: 0,
    disableAfterRate: false,
    strokeWidth: 0,
    useGradient: false,
    callback: function(currentRating, $el){
      leaderRating = currentRating;
    // alert('rated ' +  currentRating);
    // console.log("overallRating: "+currentRating);
    // console.log('DOM Element ', $el);
    }
  });
});

$(function() {
  $(".venue").starRating({
    totalStars: 5,
    emptyColor: 'lightgray',
    hoverColor: 'slategray',
    activeColor: 'black',
    initialRating: 0,
    disableAfterRate: false,
    strokeWidth: 0,
    useGradient: false,
    callback: function(currentRating, $el){
      venueRating = currentRating;
    // alert('rated ' +  currentRating);
    // console.log("overallRating: "+currentRating);
    // console.log('DOM Element ', $el);
    }
  });
});

</script>
<h1>Rate Your Event Experience</h1>
<h2>Enter your MBBF Registration Number:<br>
<input id="regnbr" value='' maxlength="4" style="width: 100px;" type=number></h2>
<h2>Enter the Event Number:<br>
<input id=evtnbr class=EVT data-provide="typeahead" value='' type=text></h2>
<h2>Event Rating: <span class="event"></span></h2>
<h2>Leader Rating: <span class="leader"></span></h2>
<h2>Venue Rating: <span class="venue"></span></h2>
<h2>Overall Rating: <span class="overall"></span></h2><br>
<h2>Added Comment(s)</h2>
<textarea rows="5" cols="60"  id="comments"></textarea><br><br>
<button id=upd>Apply Ratings</button> 
<a class="btn btn-success" href="index.php">Reload Page</a>

<br><br><br>
</body>
</html>