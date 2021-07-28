<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Venue Lister</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
</head>
<body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/js.cookie.js" type="text/javascript"></script>

<span><font size="+2"><b>Venue List</b></font></span><br>
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/listutils.inc.php';
include 'Incls/mainmenu.inc.php';

?>
<script type="text/javascript">
// global var to hold filter
var inp = '';

// does case insensitive search in 'btnALL'
$.extend($.expr[":"], {
  "containsNC": function(elem, i, match, array) {
  return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
  });

// set up scripts
$(document).ready(function () { 
	// alert("first the inline function");
	$("tr").show();
  $("#head").show();
  $("#help").hide();
  inp = Cookies.get("venfilter");
  // console.log(inp);
  if (!inp) inp = '';
  $("#inp").val(inp);
  if (inp.length > 0) 
    $('tr').hide().filter(':containsNC('+inp+')').show();
  $("#head").show();
  
$("#helpclk").click(function() {
  $("#help").toggle();
  });

	});

$(function(){
  $('#inp').keyup(function() {
    var v = $("#inp").val();
    // console.log(v);
    if (!v) v = '';
    if (v.length) 
      $('tr').hide().filter(':containsNC('+v+')').show();
    $("#head").show();
    chgFlag = 0;
    Cookies.set("venfilter", v);
    });8

    $('#btnALL').click(function() {
      $('tr').show();
      $('#inp').val('');
      chgFlag = 0;
      Cookies.remove('venfilter');     
      });
 });
</script>

<input id="inp" type=text value="" name="ss" placeholder="FILTER" title="Enter a single word or short character string to search venue fields." autofocus>&nbsp;
<button id="btnALL">Show All</button>
<table border=1 class="table table-condensed table-hover">
<tr class="head hidden-print">
<th>VenueCode</th>
<th>VenueName</th>
<th>ContactNamePhone</th>
<th>ContactPhone</th>
<th>ContactEmail</th>
<th>Map Info</th>
</tr>

<?php
// Process listing based on selected criteria
if ($ss == '') $ss = "%";
$sql = "
SELECT * FROM `venues` WHERE 1 ORDER BY `VenCode` ASC;";
// echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

while ($r = $res->fetch_assoc()) {
  if ($r['VenCode'] == '--New--') continue;
  $mi = 'YES'; if ($r['VenGmapURL'] == '') $mi = 'NO';
  //  echo '<pre> full record '; print_r($r); echo '</pre>';
  echo "<tr onclick=\"window.location='venupdate.php?vencode=$r[VenCode]'\" style='cursor: pointer;'>";
  echo '
  <td>'.$r['VenCode'].'</td>
  <td>'.$r['VenName'].'</td>
  <td>'.$r['VenContactName'].'</td>
  <td>'.$r['VenContactPhone'].'</td>
  <td>'.$r['VenContactEmail'].'</td>
  <td>'.$mi.'</td>
  </tr>
  ';
  }
echo '</table>===== END REPORT =====<br>';
?>
</div> <!-- contianer -->
</body>
</html>