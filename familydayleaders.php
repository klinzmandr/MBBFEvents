<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Family Day Leaders</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
</head>
<body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>

// set up select lists
$(document).ready(function () { 
	//alert("first the inline function");
//	$("#SS").val("$ss");
	$("#Day").val("$day");
	$("#Type").val("$et");
	
  $(".lc").click(function (e) {
    e.preventDefault();
    var ldrname = $(this).text();
    ldrname = ldrname.replace(/[,\s]/g, "");
    // alert("Modal button clicked: " + ldrname);
    $.post("eventleadersjson.php",
      {
          name: ldrname
      },
      function(data, status){ 
        // alert("Data: " + data + "\\nStatus: " + status);
        $("#content").html(data); 
        $('#ldrModal').modal('toggle', { keyboard: true });
        });  // end $.post logic 
      });
	});
</script>

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
echo '<h2>Leaders of Family Day Activities</h2>';

// get URL's for thumbnail photos from leader table
$ldrarray = array();
$sql = "SELECT `FirstName`,`LastName`, `ImgURL` FROM `leaders` WHERE `Active` = 'YES' AND `LdrDay` = 'TRUE';";
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);

while ($r = $res->fetch_assoc()) {
  $fullname = $r[FirstName] . ' ' . $r[LastName];
  $imgurl = $r[ImgURL];
  bldldr($fullname, $imgurl);
  }

function bldldr($name, $imgurl) {
  global $ldrarray;
  if (strlen($name) == 0) return; 
  $name = rtrim($name);
  list($f, $l) = preg_split('/ /', $name);
  
  $picurl = "<img src='npa.png' width='100' height='75'>";
  if ($imgurl != '') 
    $picurl = "<img src='$imgurl' width='100' height='75'>";
  if ($l == '') {
    $fc = substr($f,0,1);
    $ldrarray[$fc][$name][name] = $f;   
    $ldrarray[$fc][$name][pic] = $picurl; }   
  else {  
    $fc = substr($l,0,1);  
    $ldrarray[$fc][$name][name] = $l . ', ' . $f;   
    $ldrarray[$fc][$name][pic] = $picurl; }   
  return; 
  }

ksort($ldrarray);
// echo '<pre>ldrarray '; print_r($ldrarray); echo '</pre>';

foreach ($ldrarray as $k => $v) {
  echo "
  <div class='row'>
    <div class='col'><h3>$k</h3></div>
  </div>";
  $i = 1;
  echo "
  <div class='row'>";
  foreach ($v as $kk => $vv) {
    if ($i%5) echo " 
    <div class='col-xs-6 col-sm-4 col-md-3 col-lg-2'>
    <a class='lc' href='$vv[name]'>$vv[pic]<br>$vv[name]</a></div>";
    else echo "
    <div class='col-xs-6 col-sm-4 col-md-3 col-lg-2'>
    <a class='lc' href='$vv[name]'>$vv[pic]<br>$vv[name]</a></div>
    </div>
    <div class='row'>";
    $i++;
    }
  echo '
  </div>';
  }
?>

</div>
-----<br><br>
<div class="modal fade" id="ldrModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title" id="myModalLabel">Leader Information</h4>
</div>  <!-- modal header -->
<div class="modal-body">
<div id="content" style="overflow-y:scroll; height:400px;">
Test content.
</div>
</div>  <!-- modal body -->
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
</div>  <!-- modal footer -->
</div><!-- modal-content -->
</div><!-- modal-dialog -->
</div><!-- modal -->
<!-- end of modal -->

</div> <!-- container -->
</body>
</html>