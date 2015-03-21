<?php include '../common/db.php'; ?>
<?php include '../common/onlygalleria.php'; ?>
<?php
$baseimgpath = "/kiosk/";
$outstr = "";

$ok = $db->open();
if ($ok) {
  $outstr = getOnlyGelleriaJsondata($baseimgpath, true, $db);
  $db->close();
}

echo $outstr;
?>
