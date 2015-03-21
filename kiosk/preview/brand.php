<?php include '../common/db.php'; ?>
<?php include '../common/brand.php'; ?>
<?php
$base_URL = "/kiosk/";
$outstr = "";

$ok = $db->open();
if ($ok) {
  $outstr = getBrandJsondata($base_URL, true, $db);
  $db->close();
}

echo $outstr;
?>
