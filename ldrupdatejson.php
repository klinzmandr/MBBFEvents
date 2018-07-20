<?php
// AJAX response code - bootstrap is implemented in the receiving page.
// list contents of pictures folder for modal.

$pics = scandir("../mbbfLeaderPics");
$ssname = substr($_REQUEST['name'],0,3);
echo '<table class="table" border=1><tr><td>';
foreach ($pics as $p) {
  if (substr($p,0,1) == '.') continue;  // ignore dot files
  if (preg_match("/$ssname/i", $p)) {
    echo '<a onclick="lc(event, this);" href="'.$p.'">'.$p.'</a><br>'; }
  }
echo '</td></tr></table>';

?>