<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$idoperating = $_POST["idoperating"];
$scrsaverintv = $_POST["scrsaverintv"];
$updateintv = $_POST["updateintv"];
$showtime = $_POST["showtime"];
?>

<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
	$str = "update t_operating set scrsaverintv = ".$scrsaverintv.", updateintv = ".$updateintv.", showtime = ".$showtime." where idoperating = ".$idoperating.";";
	$db->query($str);

	$db->close();
}

?>
<html>
  <body bgcolor="#f1f1f1">
    <?php
echo "<script>";
if ($ok) {
	echo "alert('".$langRes["message"][0][$manLangCode]."');";
}
else {
	echo "alert('".$langRes["message"][1][$manLangCode]."');";
}
echo "document.location.replace('./subconfigureman.php');";
echo "</script>";
?>
</body>
</html>
