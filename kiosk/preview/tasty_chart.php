<?php include '../common/db.php'; ?>
<?php include '../common/tastychart.php'; ?>
<?php
$baseimgpath = "/kiosk/";
$outstr = "";

$ok = $db->open();
if ($ok) {
  $outstr = getTastyChartJsondata($baseimgpath, true, $db);
  $db->close();
}

echo $outstr;
?>
