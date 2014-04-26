<?php include '../common/db.php'; ?>
<?php include '../common/mainslide.php'; ?>
<?php
$baseimgpath = "/kiosk/images/dynamic/mainslide/";
$outstr = "";

$ok = $db->open();
if ($ok) {
  $outstr = getMainSlideJsondata($baseimgpath, $db);
  $db->close();
}

echo $outstr;
?>
