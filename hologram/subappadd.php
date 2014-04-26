<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$riname = $_POST["riname"];
$yearto = $_POST["yearto"];
$monthto = $_POST["monthto"];
$dayto = $_POST["dayto"];
$hourto = $_POST["hourto"];
$minuteto = $_POST["minuteto"];
?>
<?php include './common/db.php'; ?>
<?php include './common/file.php'; ?>
<?php

$ok = $db->open();

if ($ok) {
	if ($_FILES["file"]["error"] > 0) {
		$ok = false;
	}
	else {
		date_default_timezone_set('Asia/Seoul');
		$regdate = time();

		$prefix = "app/_".date("Ymd_His", $regdate);
		$dstpath = uploadfileMove($prefix, $_FILES["file"]["name"], $_FILES["file"]["tmp_name"]);

		if (strlen($dstpath) > 0) {
			$str = "INSERT INTO t_release (name, regdate, applydate, pathimage, imgtype) VALUES ('".$riname."', '".date("Y-m-d H:i:s", $regdate)."', '".$yearto."-".$monthto."-".$dayto." ".$hourto.":".$minuteto.":00', '".$dstpath."', 0);";
			if ($db->query($str) != 1) {
				fileDelete($dstpath);
				$ok = false;
			}
		}
		else {
			$ok = false;
		}
	}

	$db->close();
}
else echo "fail open<br/>";

?>
<html>
  <body bgcolor="#f1f1f1">
    <?php
echo "<script>";
if ($ok == false) {
	echo "alert('".$langRes["message"][0][$manLangCode]."');";
}
echo "document.location.replace('./subappman.php');";
echo "</script>";
?>
</body>
</html>
