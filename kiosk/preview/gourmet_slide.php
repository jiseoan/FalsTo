<?php include '../common/db.php'; ?>
<?php include '../common/gourmetslide.php'; ?>
<?php
$baseimgpath = "/kiosk/images/dynamic/gourmet/mainslide/";
$outstr = "";

$ok = $db->open();
if ($ok) {
  $outstr = getGourmetSlideJsondata($baseimgpath, true, $db);
  $db->close();
}

echo $outstr;
?>
