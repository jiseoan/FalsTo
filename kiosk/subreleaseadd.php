<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();
?>

<?php include './common/file.php'; ?>
<?php include './common/db.php'; ?>
<?php include './common/brand.php'; ?>
<?php include './common/mainslide.php'; ?>
<?php include './common/shoppinginfo.php'; ?>
<?php

$ok = $db->open();
if ($ok) {
	$brandstr = getBrandJsondata("", $db);
	$shoppinginfo = getShoppingInfoJsondata("", $db);

	$mainslide = getMainSlideJsondata("images/dynamic/mainslide/", $db);
	$arrimg = filesInDir('images');

	date_default_timezone_set('Asia/Seoul');
	$releasename = "release/release_".date("Ymd_His").".zip";

	set_time_limit(0);
	$zip = new ZipArchive();
	if ($zip->open($releasename, ZIPARCHIVE::OVERWRITE) == true) {
		$zip->addEmptyDir('images');
		for ($i = 0, $n = count($arrimg) ; $i < $n ; $i++) {
			$zip->addFile($arrimg[$i], $arrimg[$i]);
		}
		
		$zip->addFromString('json/brand.json', $brandstr);
		$zip->addFromString('json/shopping_info.json', $shoppinginfo);
		$zip->addFromString('json/main_slide.json', $mainslide);
		$zip->close();
		
		$name = "새업데이트";
		$regdate = date("Y-m-d H:i:s");
		$applydate = date("Y-m-d H:i:s",strtotime ("+1 days"));

		$str = "INSERT INTO t_release (name, regdate, applydate, pathimage, imgtype) VALUES ('".$name."', '".$regdate."', '".$applydate."', '".$releasename."', 1);";
		if ($db->query($str) == 1) {
			$str = "SELECT LAST_INSERT_ID() as idrelimg;";
			$n = $db->queryCount($str, "idrelimg");
		}
		else {
			unlink($releasename);
			$ok = false;
		}
	}
	else {
		echo 'ZipArchive()...failed';
		$ok = false;
	}

	$db->close();
}
?>
<html>
<body bgcolor="#f1f1f1">
<?php
echo "<script>";
if ($ok == false) {
	echo "alert('추가하지못하였습니다');";
}
echo "document.location.replace('./subreleaseman.php');";
echo "</script>";
?>
</body>
</html>
