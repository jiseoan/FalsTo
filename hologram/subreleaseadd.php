<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];
?>

<?php include './common/file.php'; ?>
<?php include './common/db.php'; ?>
<?php include './common/list.php'; ?>
<?php

$ok = $db->open();
if ($ok) {
	$itemstr = getJsondata(false, $db);
	$arrimg = filesInDir('upimg');
	$arrmodel = filesInDir('upmodel');

	date_default_timezone_set('Asia/Seoul');
	$releasename = "release/release_".date("Ymd_His").".zip";

	set_time_limit(0);
	$zip = new ZipArchive();
	if ($zip->open($releasename, ZIPARCHIVE::OVERWRITE) == true) {
		$zip->addEmptyDir('img');
		for ($i = 0, $n = count($arrimg) ; $i < $n ; $i++) {
			$zip->addFile($arrimg[$i], str_replace('upimg/', 'img/', $arrimg[$i]));
		}
		
		$zip->addEmptyDir('model');
		for ($i = 0, $n = count($arrmodel) ; $i < $n ; $i++) {
			$zip->addFile($arrmodel[$i], str_replace('upmodel/', 'model/', $arrmodel[$i]));
		}
		
		$zip->addFromString('json/list.json', $itemstr);
		$zip->close();
		
		$name = $langRes["label"][0][$manLangCode];
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
	echo "alert('".$langRes["message"][0][$manLangCode]."');";
}
echo "document.location.replace('./subreleaseman.php');";
echo "</script>";
?>
</body>
</html>
