<?php include '../common/db.php'; ?>
<?php include '../common/foodmenu.php'; ?>
<?php
$baseimgpath = "/kiosk/";
$outstr = "";

$ok = $db->open();
if ($ok) {
  $outstr = getFoodMenuJsondata($baseimgpath, true, $db);
  $db->close();
}

echo $outstr;
?>
