<?php include '../common/db.php'; ?>
<?php include '../common/list.php'; ?>
<?php
$outstr = "";

$ok = $db->open();
if ($ok) {
  $outstr = getJsondata(true, $db);
  $db->close();
}

echo $outstr;
?>
