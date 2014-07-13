<?php include '../common/db.php'; ?>
<?php include '../common/shoppinginfo.php'; ?>
<?php
$base_URL = "/kiosk/";
$outstr = "";

$ok = $db->open();
if ($ok) {
  $outstr = getShoppingInfoJsondata($base_URL, true, $db);
  $db->close();
}

echo $outstr;
?>
