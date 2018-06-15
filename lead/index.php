<?php
session_start();
// include '../vardump.inc.php';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$ks = isset($_REQUEST['keystring']) ? $_REQUEST['keystring'] : '';
?>
<!DOCTYPE html>
<html>
<head>
<title>Leader Info</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="../css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script>
// initial setup of jquery function(s) for page
$(document).ready(function () {
  $('[name=submit]').prop('disabled', true);
  $("#MK").hide();
  $("#Entry-Form").hide();
  $("button").click(function(event){
    event.preventDefault();
    var inputkeystring = $("[name=keystring]").val();
    inputkeystring = inputkeystring.toLowerCase();
    $.post("kcaptchajson.php",
      {
        ks: inputkeystring
      },
    function(data, status) {
        if (data == 'OK') {
          $('[name=submit]').prop('disabled', false); 
          $("#MK").show();
          $("#Entry-Form").show();
          $("#sb").css({"background-color": "green"}); }
        else {
          $('#sb').prop('disabled', true);
          $("#sb").css({"background-color": "white"});
          alert("Data entered does not match validation string.");
          }
      });  // end $.post logic 
  });
});  // end ready function
</script>

<div id="Validation-Form">
<ul><img src="http://morrobaybirdfestival.net/wp-content/uploads/2016/08/LOGO3.png" alt="bird festival logo" >
<h2>Leader Information</h2></ul>
<table class="table" border="0" align="center" width="80%">
<tr>
<td width="30%" align="right">Validation String<br>
<img src='kcaptchagen.php?<?php echo session_name()?>=<?php echo session_id()?>'/>
</td>
<td>Verification - enter validation string. <span id="MK">MATCHED!</span><br><input type="text" name="keystring" autofocus><br>
<button>Validate String</button>&nbsp;&nbsp;<a class="btn btn-default btn-sm" href="index.php">New String</a></td>
</tr>
</table>
</div>  <!-- Validation-Form -->
<br>
<div id="Entry-Form">

<h3 style="text-align: center; ">Leader Email Address</h3>
<form class="form" name="EntryForm" method=post action="../leaderquery.php">
<table border="1" align="center">
<tr>
<td><input type="text" name="eaddr" value=""></td>
<td colspan=2 align=center><input type="submit" name="submit" value="Submit"></form></td>
</tr>
</table>'
</form>
</div>  <!-- entry-form -->

</body>
</html>
<ul>