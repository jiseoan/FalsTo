<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$idoperating = $_POST["idoperating"];
$fromhour = $_POST["fromhour"];
$fromminute = $_POST["fromminute"];
$tohour = $_POST["tohour"];
$tominute = $_POST["tominute"];
?>

<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
	$str = "update t_operating set hourbegin = '".$fromhour.":".$fromminute.":00', hourend = '".$tohour.":".$tominute.":00' where idoperating = ".$idoperating.";";
	$db->query($str);

	$db->close();
}

?>
<html>
  <body bgcolor="#f1f1f1">
    <?php
echo "<script>";
if ($ok) {
	echo "alert('저장하였습니다');";
}
else {
	echo "alert('저장하지못하였습니다');";
}
echo "document.location.replace('./suboperatingtimeman.php');";
echo "</script>";
?>
</body>
</html>
